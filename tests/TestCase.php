<?php

declare(strict_types=1);

namespace Modules\Chart\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Xot\Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected $connectionsToTransact = ['mysql'];

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function createApplication()
    {
        $app = parent::createApplication();
        
        // Ensure the database manager is initialized
        $app->make('db.factory');
        
        return $app;
    }
}
