<?php

declare(strict_types=1);
use Modules\Chart\Tests\TestCase;

/**
 * Bootstrap Pest — modulo Chart.
 * Helper globali: tests/Support/helpers.php
 * Ogni file test dichiara uses(Modules\Chart\Tests\TestCase::class).
 */
pest()->extend(TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature');
