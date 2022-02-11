<?php declare(strict_types=1);

namespace Dive\Geo\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PharData;
use PharFileInfo;
use RecursiveIteratorIterator;
use RuntimeException;

class UpdateDatabaseCommand extends Command
{
    protected $description = 'Update the GeoIP2 database.';

    protected $signature = 'geo:update';

    public function __construct(
        private Repository $config,
        private Filesystem $fs,
        private Factory $http,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $config = $this->config['geo']['detectors'];

        if (($driver = $config['driver']) !== 'maxmind_db') {
            $this->warn("🤚  The '{$driver}' driver does not need updating.");

            return self::FAILURE;
        }

        $this->info('⬇️  Downloading database...');

        $tuple = $this->download($config[$driver]);

        $this->info('👷  Updating database...');

        $this->replaceDatabase($tuple, $config[$driver]['database_path']);

        $this->info('✅  Database successfully updated.');

        $this->cleanUp($tuple->first());

        return self::SUCCESS;
    }

    private function cleanUp(string $directory)
    {
        $this->fs->deleteDirectory($directory);
    }

    private function download(array $credentials): Collection
    {
        $url = sprintf($credentials['url'], $credentials['license_key']);

        $response = $this->http->get($url);

        if ($this->fs->exists($dir = tempnam(sys_get_temp_dir(), 'geo'))) {
            $this->fs->delete($dir);
        }

        $this->fs->makeDirectory($dir);

        $fileName = Str::after($response->header('Content-Disposition'), 'filename=');

        $this->fs->put($dir . '/' . $fileName, $response->body());

        return Collection::make([$dir, $fileName]);
    }

    private function replaceDatabase(Collection $tuple, string $writeTo)
    {
        [$dir] = $tuple;
        $tar = new PharData($tuple->join('/'));

        $database = Collection::make(new RecursiveIteratorIterator($tar))
            ->first(fn (PharFileInfo $file) => $file->getExtension() === 'mmdb', function () {
                throw new RuntimeException('Database could not be found within the downloaded archive.');
            });

        $tar->extractTo(
            $dir,
            $relative = Str::afterLast($database->getPath(), '/') . '/' . $database->getFilename(),
        );

        if ($this->fs->exists($writeTo)) {
            $this->fs->delete($writeTo);
        }

        $this->fs->move($dir . '/' . $relative, $writeTo);
    }
}
