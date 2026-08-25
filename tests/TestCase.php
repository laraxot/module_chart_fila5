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
<<<<<<< HEAD
* @property Chart|null $chart
=======
 * @property Chart|null $chart
>>>>>>> laraxot/dev
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    public ?Chart $chart = null;

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
