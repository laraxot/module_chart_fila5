<?php

declare(strict_types=1);

use Modules\Chart\Database\Factories\ChartFactory;
use Modules\Chart\Database\Factories\MixedChartFactory;
use Modules\Chart\Models\Chart;
use Modules\Chart\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('chart model can be created with factory', function (): void {
    $chart = ChartFactory::new()->createOne();

    Assert::assertInstanceOf(Chart::class, $chart);
    Assert::assertTrue($chart->exists);
});

test('chart model has expected fillable attributes', function (): void {
    $chart = new Chart;

    Assert::assertEquals([
        'id', 'post_id', 'post_type', 'type', 'width', 'height',
        'color', 'bg_color', 'font_family', 'font_size', 'font_style',
        'y_grace', 'yaxis_hide', 'list_color', 'grace', 'x_label_angle',
        'show_box', 'x_label_margin', 'plot_perc_width', 'plot_value_show',
        'plot_value_format', 'plot_value_pos', 'plot_value_color',
        'group_by', 'sort_by', 'transparency', 'colors',
    ], $chart->getFillable());
});

test('chart model has default attributes', function (): void {
    $chart = new Chart;

    Assert::assertSame('#d60021', $chart->getAttributes()['list_color']);
    Assert::assertSame('#d60021', $chart->getAttributes()['color']);
    Assert::assertSame(15, $chart->getAttributes()['font_family']);
    Assert::assertSame(9002, $chart->getAttributes()['font_style']);
    Assert::assertSame(12, $chart->getAttributes()['font_size']);
    Assert::assertSame(10, $chart->getAttributes()['x_label_margin']);
    Assert::assertSame(90, $chart->getAttributes()['plot_perc_width']);
});

test('chart model casts colors to array', function (): void {
    $chart = createChart(['colors' => ['red', 'blue', 'green']]);

    Assert::assertSame(['red', 'blue', 'green'], $chart->colors);
});

test('chart type accessor returns value when set', function (): void {
    $chart = createChart(['type' => 'bar']);

    Assert::assertSame('bar', $chart->type);
});

test('chart width accessor returns integer', function (): void {
    $chart = createChart(['width' => 800]);

    Assert::assertSame(800, $chart->width);
});

test('chart width accessor falls back to default', function (): void {
    $chart = makeChart(['width' => null]);

    Assert::assertSame(800, $chart->getWidthAttribute(null));
});

test('chart height accessor returns integer', function (): void {
    $chart = createChart(['height' => 600]);

    Assert::assertSame(600, $chart->height);
});

test('chart getSettings returns chart array', function (): void {
    $chart = createChart(['type' => 'bar']);
    $settings = $chart->getSettings();

    Assert::assertArrayHasKey('chart', $settings);
});

test('chart getSettings throws when type is null', function (): void {
    $chart = makeChart(['type' => null]);

    assertChartThrows(
        static fn (): array => $chart->getSettings(),
        \InvalidArgumentException::class
    );
});
