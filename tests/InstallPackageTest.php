<?php declare(strict_types=1);

namespace Tests;

use function Pest\Laravel\artisan;

afterEach(function () {
    file_exists(config_path('geo.php')) && unlink(config_path('geo.php'));
});

it('copies the config', function () {
    artisan('geo:install')->execute();

    expect(file_exists(config_path('geo.php')))->toBeTrue();
});
