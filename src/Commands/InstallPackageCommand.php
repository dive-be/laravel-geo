<?php declare(strict_types=1);

namespace Dive\Geo\Commands;

use Illuminate\Console\Command;

class InstallPackageCommand extends Command
{
    protected $description = 'Install geo.';

    protected $signature = 'geo:install';

    public function handle(): int
    {
        if ($this->isHidden()) {
            $this->error('🤚  Geo is already installed.');

            return self::FAILURE;
        }

        $this->line('🏎  Installing geo...');
        $this->line('📑  Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "Dive\Geo\GeoServiceProvider",
            '--tag' => 'config',
        ]);

        $this->info('🏁  Geo installed successfully!');

        return self::SUCCESS;
    }

    public function isHidden(): bool
    {
        return file_exists(config_path('geo.php'));
    }
}
