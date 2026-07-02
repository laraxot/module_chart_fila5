<?php

declare(strict_types=1);

use Modules\Chart\Database\Factories\ChartFactory;
use Modules\Chart\Database\Factories\MixedChartFactory;
use Modules\Chart\Models\Chart;
use Modules\Chart\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('can create chart with all required fields', function (): void {
    $chart = ChartFactory::new()->createOne([
        'type' => 'bar',
        'width' => 800,
        'height' => 600,
        'color' => '#ff0000',
        'bg_color' => '#ffffff',
        'post_id' => 123,
        'post_type' => 'report',
    ]);

    Assert::assertSame('bar', $chart->type);
    Assert::assertSame(800, $chart->width);
    Assert::assertSame(600, $chart->height);
    Assert::assertSame('#ff0000', $chart->color);
    Assert::assertSame('#ffffff', $chart->bg_color);
    Assert::assertSame(123, $chart->post_id);
    Assert::assertSame('report', $chart->post_type);
});

test('applies default attributes when creating chart', function (): void {
    $chart = new Chart;

    Assert::assertSame('#d60021', $chart->list_color);
    Assert::assertSame('#d60021', $chart->color);
    Assert::assertSame(15, $chart->font_family);
    Assert::assertSame(12, $chart->font_size);
    Assert::assertSame(90, $chart->plot_perc_width);
});

test('handles mixed chart type settings', function (): void {
    $mixedChart = MixedChartFactory::new()->createOne();
    $chart = ChartFactory::new()->createOne(['type' => 'mixed:'.$mixedChart->id]);

    $settings = $chart->getSettings();
    Assert::assertIsArray($settings);
});

test('can update chart properties', function (): void {
    $chart = ChartFactory::new()->createOne(['width' => 400]);
    $chart->update(['width' => 800, 'height' => 600]);

    $fresh = $chart->fresh();
    Assert::assertNotNull($fresh);
    Assert::assertSame(800, $fresh->width);
    Assert::assertSame(600, $fresh->height);
});

test('persists colors array correctly', function (): void {
    $colors = ['#ff0000', '#00ff00', '#0000ff'];
    $chart = ChartFactory::new()->createOne(['colors' => $colors]);

    $freshChart = Chart::query()->find($chart->id);
    Assert::assertNotNull($freshChart);
    Assert::assertSame($colors, $freshChart->colors);
    Assert::assertCount(3, $freshChart->colors);
});
