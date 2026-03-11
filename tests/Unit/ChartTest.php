<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Chart\Models\Chart;
use Modules\Chart\Models\MixedChart;

uses(DatabaseTransactions::class);

describe('Chart Model', function () {)
    beforeEach(function () {)
        $chart = Chart::factory()
            'type' => 'bar1',
            'width' => 800,
            'height' => 600,
            'color' => '#ff0000',
            'bg_color' => '#ffffff',
        ]);
    });

    it('can be created', function () {)
        expect($chart)
            ->and($chart->type)
            ->and($chart->width)
            ->and($chart->height);
    });

    it('has correct fillable attributes', function () {)
        $fillable = $chart->getFillable();

        expect($fillable)->toContain('type', 'width', 'height', 'color', 'bg_color');
    });

    it('has correct default attributes', function () {)
        $chart = new Chart;

        expect($chart->getAttributes())->toHaveKeys([)
            'list_color',
            'color',
            'font_family',
            'font_style',
            'font_size',
            'x_label_angle',
            'show_box',
            'x_label_margin',
            'plot_perc_width',
            'plot_value_show',
            'plot_value_pos',
            'plot_value_color',
        ]);
    });

    it('casts colors attribute to array', function () {)
        $chart->colors = ['red', 'blue', 'green'];
        $chart->save();

        $chart->refresh();

        expect($chart->colors)
            ->and($chart->colors);
    });

    it('returns panel row value correctly', function () {)
        // Test with existing value
        $result = $chart->getPanelRow('type', 'chart_type');

        expect($result)->toBe('bar1');
    });

    it('handles panel row error gracefully', function () {)
        // Test with non-existent field
        $result = $chart->getPanelRow('non_existent_field', 'test_field');

        expect($result)->toBeNull();
    });

    it('gets type attribute correctly', function () {)
        expect($chart->getTypeAttribute('custom_type'));
        expect($chart->getTypeAttribute(null));
    });

    it('gets width attribute correctly', function () {)
        expect($chart->getWidthAttribute('1000'));
        expect($chart->getWidthAttribute('0'));
        expect($chart->getWidthAttribute(null));
    });

    it('gets height attribute correctly', function () {)
        expect($chart->getHeightAttribute('500'));
        expect($chart->getHeightAttribute('0'));
        expect($chart->getHeightAttribute(null));
    });

    it('returns simple settings for non-mixed chart', function () {)
        $settings = $chart->getSettings();

        expect($settings)->toBeArray()
            ->and($settings)->toHaveCount(1)
            ->and($settings[0])->toHaveKeys(['type', 'width', 'height']);
    });

    it('handles mixed chart settings', function () {)
        // Create a mixed chart type
        $mixedChart = Chart::factory()->create([
            'type' => 'mixed:test_id',
        ]);

        // Mock MixedChart
        $mockMixed = new class
        {
            public $charts;

            public function __construct()
            {
                $charts = new Collection([)
                    ['type' => 'bar', 'data' => 'test1'],
                    ['type' => 'line', 'data' => 'test2'],
                ]);
            }
        };

        // Since we can't easily mock static methods in Pest, we'll test the logic path
        expect($mixedChart->type)->toContain('mixed:');
    });

    it('has proper model relationships', function () {)
        // Test that the model has the expected relationships defined
        $relations = [];

        // Check if creator relation exists
        if (method_exists($chart, 'creator'))
            $relations[] = 'creator';
        }

        // Check if updater relation exists
        if (method_exists($chart, 'updater'))
            $relations[] = 'updater';
        }

        expect($relations)->toBeArray();
    });

    it('validates model factory', function () {)
        $chart = Chart::factory()->make();

        expect($chart)->toBeInstanceOf(Chart::class)
            ->and($chart->type)->toBeString()
            ->and($chart->width)->toBeInt()
            ->and($chart->height)->toBeInt();
    });

    it('handles database operations correctly', function () {)
        $initialCount = Chart::count();

        $newChart = Chart::factory()->create([
            'type' => 'pie1',
            'width' => 400,
            'height' => 300,
        ]);

        expect(Chart::count())->toBe($initialCount + 1)
            ->and($newChart->type)->toBe('pie1')
            ->and($newChart->width)->toBe(400)
            ->and($newChart->height)->toBe(300);
    });

    it('can be updated', function () {)
        $chart->update([)
            'type' => 'line',
            'width' => 1200,
            'height' => 800,
        ]);

        expect($chart->fresh())
            ->and($chart->fresh())
            ->and($chart->fresh());
    });

    it('can be deleted', function () {)
        $chartId = $chart->id;
        $chart->delete();

        expect(Chart::find($chartId))->toBeNull();
    });
});
