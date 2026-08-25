<?php

declare(strict_types=1);

use Modules\Chart\Database\Factories\ChartFactory;
use Modules\Chart\Models\Chart;
use PHPUnit\Framework\Assert;

/**
 * @param  class-string<\Throwable>  $exceptionClass
 */
function assertChartThrows(callable $callback, string $exceptionClass): void
{
    try {
        $callback();
    } catch (\Throwable $exception) {
        Assert::assertInstanceOf($exceptionClass, $exception);

        return;
    }

    Assert::fail(sprintf('Expected exception %s was not thrown.', $exceptionClass));
}

/**
 * @param  array<string, mixed>  $attributes
 */
function createChart(array $attributes = []): Chart
{
    return ChartFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function makeChart(array $attributes = []): Chart
{
    return ChartFactory::new()->makeOne($attributes);
}
