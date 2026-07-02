<?php

declare(strict_types=1);

namespace Modules\Chart\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Chart\Models\Chart;
use Modules\Chart\Providers\ChartServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case for Chart module.
 *
 * @property Chart|null $chart
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    public ?Chart $chart = null;

    protected static bool $migrated = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (! self::$migrated) {
            $this->artisan('migrate:fresh', ['--force' => true]);
            $this->artisan('module:migrate', ['--force' => true]);
            self::$migrated = true;
        }
    }

    /** @return array<int, class-string<ServiceProvider>> */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            ChartServiceProvider::class,
            UserServiceProvider::class,
        ];
    }

    public function requireChart(): Chart
    {
        if ($this->chart === null) {
            $this->fail('Chart test property is not initialized.');
        }

        return $this->chart;
    }
}
