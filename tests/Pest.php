<?php

declare(strict_types=1);

/*
 * Bootstrap Pest — modulo Chart.
 * Helper globali: tests/Support/helpers.php
 * Ogni file test dichiara uses(Modules\Chart\Tests\TestCase::class).
 */

pest()->extend(\Modules\Chart\Tests\TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature');
