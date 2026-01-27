<?php

declare(strict_types=1);

namespace Modules\Chart\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Chart\Providers\ChartServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Providers\XotServiceProvider;
use Modules\Xot\Tests\CreatesApplication;

/**
 * Base test case for Chart module.
 *
 * Uses MySQL from .env.testing (NOT SQLite).
 * Database names must have "_test" suffix (es: quaeris_data_test).
 * The .env.testing file is the single source of truth - NEVER override database configuration.
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected function getEnvironmentSetUp($app): void
    {
        // Set cache driver to array for testing (required for Sushi models)
        $app['config']->set('cache.default', 'array');

        // ✅ CORRETTO: Rispetta .env.testing - NON forzare SQLite
        // Il file .env.testing è la fonte unica di verità per la configurazione database
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Set cache driver to array for testing (required for Sushi models)
        $this->app['config']->set('cache.default', 'array');

        // ✅ CORRETTO: Rispetta .env.testing - NON forzare SQLite
        // Il file .env.testing definisce:
        // - DB_CONNECTION=mysql
        // - DB_DATABASE=quaeris_data_test (suffisso "_test" obbligatorio)
        // NON sovrascrivere mai questa configurazione
        
        $this->artisan('module:migrate', ['module' => 'Xot', '--force' => true]);
        $this->artisan('module:migrate', ['module' => 'User', '--force' => true]);
        $this->artisan('module:migrate', ['module' => 'Chart', '--force' => true]);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ChartServiceProvider::class,
            UserServiceProvider::class,
            XotServiceProvider::class,
        ];
    }
}