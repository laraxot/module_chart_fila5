<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Collection;
use Modules\Chart\Database\Factories\ChartFactory;
use Modules\Chart\Models\Chart;
use Modules\Chart\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('creates chart with factory', function (): void {
    $chart = ChartFactory::new()->createOne();

    Assert::assertInstanceOf(Chart::class, $chart);
    Assert::assertTrue($chart->exists);
    Assert::assertIsInt($chart->id);
    Assert::assertIsInt($chart->post_id);
    Assert::assertIsString($chart->post_type);
    Assert::assertIsString($chart->type);
    Assert::assertIsInt($chart->width);
    Assert::assertIsInt($chart->height);
});

test('creates chart with custom attributes', function (): void {
    $chart = ChartFactory::new()->createOne([
        'type' => 'bar',
        'width' => 800,
        'height' => 600,
        'color' => '#ff0000',
    ]);

    Assert::assertSame('bar', $chart->type);
    Assert::assertSame(800, $chart->width);
    Assert::assertSame(600, $chart->height);
    Assert::assertSame('#ff0000', $chart->color);
});

test('makes chart without persisting', function (): void {
    $chart = ChartFactory::new()->makeOne();

    Assert::assertInstanceOf(Chart::class, $chart);
    Assert::assertFalse($chart->exists);
});

test('creates multiple charts', function (): void {
    $charts = ChartFactory::new()->count(3)->create();

    Assert::assertInstanceOf(Collection::class, $charts);
    Assert::assertCount(3, $charts);
});

test('creates chart with colors array', function (): void {
    $colors = ['#ff0000', '#00ff00', '#0000ff'];
    $chart = ChartFactory::new()->createOne(['colors' => $colors]);

    Assert::assertSame($colors, $chart->colors);
    Assert::assertIsArray($chart->colors);
});
