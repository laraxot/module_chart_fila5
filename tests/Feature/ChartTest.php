<?php

declare(strict_types=1);

use Modules\Chart\Database\Factories\ChartFactory;
use Modules\Chart\Models\Chart;
use Modules\Chart\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('can create a chart with valid data', function (): void {
    $chart = ChartFactory::new()->createOne([
        'post_id' => 1,
        'post_type' => 'report',
        'type' => 'bar',
        'width' => 800,
        'height' => 600,
        'color' => '#FF0000',
        'bg_color' => '#FFFFFF',
        'font_family' => 15,
        'font_size' => 12,
        'font_style' => 0,
        'y_grace' => 10,
        'yaxis_hide' => false,
        'list_color' => '#00FF00',
<<<<<<< HEAD
       'grace' => '5',
=======
        'grace' => '5',
>>>>>>> laraxot/dev
        'x_label_angle' => '45',
        'show_box' => true,
        'x_label_margin' => 10,
        'plot_perc_width' => 80,
        'plot_value_show' => true,
        'plot_value_format' => 'integer',
<<<<<<< HEAD
       'plot_value_pos' => 1,
=======
        'plot_value_pos' => 1,
>>>>>>> laraxot/dev
        'plot_value_color' => '#0000FF',
        'group_by' => 'category',
        'sort_by' => 'name',
        'transparency' => '0.5',
    ]);

    Assert::assertInstanceOf(Chart::class, $chart);
    Assert::assertSame(1, $chart->post_id);
    Assert::assertSame('bar', $chart->type);
    Assert::assertSame(800, $chart->width);
    Assert::assertSame('#FF0000', $chart->color);
});

test('can update a chart', function (): void {
    $chart = ChartFactory::new()->createOne();
    $chart->update(['width' => 1024]);

    $fresh = $chart->fresh();
    Assert::assertNotNull($fresh);
    Assert::assertSame(1024, $fresh->width);
});

test('can delete a chart', function (): void {
    $chart = ChartFactory::new()->createOne();
    $chartId = $chart->id;
    $chart->delete();

    Assert::assertNull(Chart::query()->find($chartId));
});
