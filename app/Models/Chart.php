<?php

declare(strict_types=1);

namespace Modules\Chart\Models;

use Illuminate\Support\Carbon;
use Modules\Quaeris\Models\Profile;
use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

/**
 * Modules\Chart\Models\Chart.
 *
 * @property int $id
 * @property string|null $post_type
 * @property int|null $post_id
 * @property string|null $color
 * @property string|null $bg_color
 * @property int|null $font_family
 * @property int|null $font_style
 * @property int|null $font_size
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int|null $y_grace
 * @property int|null $yaxis_hide
 * @property string|null $list_color
 * @property string|null $x_label_angle
 * @property int|null $show_box
 * @property int|null $x_label_margin
 * @property int|null $width
 * @property int|null $height
 * @property string|null $type
 * @property int|null $plot_perc_width
 * @property int|null $plot_value_show
 * @property string|null $plot_value_format
 * @property int|null $plot_value_pos
 * @property string|null $plot_value_color
 * @property string|null $group_by
 * @property string|null $sort_by
 * @property string|null $lang
 * @property string $transparency
 * @property array<array-key, mixed> $colors
 * @property string|null $grace
 * @property-read Profile|null $creator
 * @property-read Profile|null $updater
 *
 * @method static Builder<static>|Chart newModelQuery()
 * @method static Builder<static>|Chart newQuery()
 * @method static Builder<static>|Chart query()
 * @method static Builder<static>|Chart whereBgColor($value)
 * @method static Builder<static>|Chart whereColor($value)
 * @method static Builder<static>|Chart whereColors($value)
 * @method static Builder<static>|Chart whereCreatedAt($value)
 * @method static Builder<static>|Chart whereCreatedBy($value)
 * @method static Builder<static>|Chart whereFontFamily($value)
 * @method static Builder<static>|Chart whereFontSize($value)
 * @method static Builder<static>|Chart whereFontStyle($value)
 * @method static Builder<static>|Chart whereGrace($value)
 * @method static Builder<static>|Chart whereGroupBy($value)
 * @method static Builder<static>|Chart whereHeight($value)
 * @method static Builder<static>|Chart whereId($value)
 * @method static Builder<static>|Chart whereLang($value)
 * @method static Builder<static>|Chart whereListColor($value)
 * @method static Builder<static>|Chart wherePlotPercWidth($value)
 * @method static Builder<static>|Chart wherePlotValueColor($value)
 * @method static Builder<static>|Chart wherePlotValueFormat($value)
 * @method static Builder<static>|Chart wherePlotValuePos($value)
 * @method static Builder<static>|Chart wherePlotValueShow($value)
 * @method static Builder<static>|Chart wherePostId($value)
 * @method static Builder<static>|Chart wherePostType($value)
 * @method static Builder<static>|Chart whereShowBox($value)
 * @method static Builder<static>|Chart whereSortBy($value)
 * @method static Builder<static>|Chart whereTransparency($value)
 * @method static Builder<static>|Chart whereType($value)
 * @method static Builder<static>|Chart whereUpdatedAt($value)
 * @method static Builder<static>|Chart whereUpdatedBy($value)
 * @method static Builder<static>|Chart whereWidth($value)
 * @method static Builder<static>|Chart whereXLabelAngle($value)
 * @method static Builder<static>|Chart whereXLabelMargin($value)
 * @method static Builder<static>|Chart whereYGrace($value)
 * @method static Builder<static>|Chart whereYaxisHide($value)
 *
 * @mixin \Eloquent
 */
class Chart extends BaseModel
{
    /** @var string */
    protected $connection = 'mysql'; // Use mysql connection to access question_charts table
    /** @var string */
    protected $table = 'question_charts';

    /** @var list<string> */
    protected $fillable = [
        'id',
        'post_id',
        'post_type',
        'type',
        'width', 'height',
        'color',
        'bg_color',
        'font_family',
        'font_size',
        'font_style',
        'y_grace',
        'yaxis_hide',
        'list_color',
        'grace',
        'x_label_angle',
        'show_box',
        'x_label_margin',
        'plot_perc_width',
        'plot_value_show',
        'plot_value_format',
        'plot_value_pos',
        'plot_value_color',
        'group_by',
        'sort_by',
        'transparency',
        'colors',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'list_color' => '#d60021',
        'color' => '#d60021',
        'font_family' => 15,
        'font_style' => 9002,
        'font_size' => 12,
        'x_label_angle' => 0,
        'show_box' => false,
        'x_label_margin' => 10,
        'plot_perc_width' => 90,
        'plot_value_show' => 1,
        'plot_value_pos' => 1,
        'plot_value_color' => '#000000',
    ];

    public function getPanelRow(string $parent_field, string $my_field): int|string|null
    {
        $panel_row = $this;
        $value = null;

        try {
            $value = $panel_row->{$parent_field};
            $this->{$my_field} = $value;
            $this->save();
        } catch (ErrorException $errorException) {
            // Error caught but not logged - intentionally silent
            // If logging is needed, implement explicitly here
            $value = null;
        }

        /** @var int|string|null $value */
        return $value;
    }

    public function getTypeAttribute(?string $value): ?string
    {
        if ($value !== null) {
            return $value;
        }

        $res = $this->attributes['type'] ?? (string) $this->getPanelRow('chart_type', 'type');
        Assert::string($res);

        return $res;
    }

    public function getWidthAttribute(?string $value): ?int
    {
        if ($value === null) {
            return (int) $this->getPanelRow('width', 'width');
        }

        if ((int) $value === 0) {
            return (int) $this->getPanelRow('width', 'width');
        }

        return (int) $value;
    }

    public function getHeightAttribute(?string $value): ?int
    {
        if ($value === null) {
            return (int) $this->getPanelRow('height', 'height');
        }
        if ((int) $value === 0) {
            return (int) $this->getPanelRow('height', 'height');
        }

        return (int) $value;
    }

    /**
     * Get chart settings as array of chart configurations.
     *
     * @return array<string, array<int|string, mixed>>
     */
    public function getSettings(): array
    {
        Assert::notNull($this->type, '['.__FILE__.']['.__LINE__.']');

        if (Str::startsWith($this->type, 'mixed')) {
            $parz = \array_slice(explode(':', $this->type), 1);
            $mixed_id = implode('|', $parz);
            $mixed = MixedChart::firstWhere(['id' => $mixed_id]);
            Assert::notNull($mixed, '['.__FILE__.']['.__LINE__.']');
            Assert::isInstanceof($mixed->charts, Collection::class);

            /** @var array<string, array<int|string, mixed>> */
            return $mixed->charts->toArray();
        }

        /** @var array<string, array<int|string, mixed>> */
        return ['chart' => $this->toArray()];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'colors' => 'array',
        ];
    }
}
