<?php

declare(strict_types=1);

use Modules\Chart\Datas\ChartData;
use Modules\Chart\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function chartDataFixture(array $overrides = []): array
{
    return array_merge([
        'type' => 'bar1',
        'max' => 100.0,
        'min' => 0.0,
        'height' => 400,
        'list_color' => '#ff0000',
        'font_family' => 'Arial',
        'font_size' => '12',
        'font_style' => 'normal',
        'x_label_angle' => '0',
        'show_box' => 1,
        'x_label_margin' => 10,
        'plot_perc_width' => 90,
        'plot_value_show' => true,
        'plot_value_pos' => 1,
        'plot_value_color' => '#000000',
    ], $overrides);
}

test('chart data can be created from array', function (): void {
    $chartData = ChartData::from(chartDataFixture(['type' => 'bar1']));

    Assert::assertInstanceOf(ChartData::class, $chartData);
    Assert::assertSame('bar1', $chartData->type);
    Assert::assertSame(100.0, $chartData->max);
    Assert::assertSame(0.0, $chartData->min);
    Assert::assertSame(400, $chartData->height);
    Assert::assertSame('#ff0000', $chartData->list_color);
});

test('chart data handles optional properties', function (): void {
    $chartData = ChartData::from(chartDataFixture([
        'type' => 'line',
        'title' => 'Test Title',
        'subtitle' => 'Test Subtitle',
        'width' => 800,
        'plot_value_show' => false,
    ]));

    Assert::assertSame('Test Title', $chartData->title);
    Assert::assertSame('Test Subtitle', $chartData->subtitle);
    Assert::assertSame(800, $chartData->width);
    Assert::assertFalse($chartData->plot_value_show);
});

test('chart data converts to array', function (): void {
    $chartData = ChartData::from(chartDataFixture(['type' => 'doughnut']));
    $arrayData = $chartData->toArray();

    Assert::assertSame('doughnut', $arrayData['type']);
    Assert::assertSame(100.0, $arrayData['max']);
    Assert::assertSame(400, $arrayData['height']);
});

test('chart data supports chart types', function (): void {
    foreach (['bar1', 'bar2', 'bar3', 'pie1', 'pieAvg', 'horizbar1', 'lineSubQuestion'] as $type) {
        $chartData = ChartData::from(chartDataFixture(['type' => $type]));
        Assert::assertSame($type, $chartData->type);
    }
});

test('chart data extends spatie data', function (): void {
    $chartData = ChartData::from(chartDataFixture());

    Assert::assertInstanceOf(Data::class, $chartData);
});
