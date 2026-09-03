<?php

declare(strict_types=1);

namespace Modules\Chart\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Modules\Chart\Database\Factories\ChartFactory;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Traits\Updater;

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
 *
 * @method static Builder<static>|Chart newModelQuery()
 * @method static Builder<static>|Chart newQuery()
 * @method static Builder<static>|Chart query()
 * @method static ChartFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class Chart extends Model
{
    use HasXotFactory;

    use Updater;

    protected $table = 'charts';

    /** @var list<string> */
    protected $fillable = [
        'id', 'post_id', 'post_type', 'type', 'width', 'height', 'color', 'bg_color', 'font_family', 'font_size', 'font_style', 'y_grace', 'yaxis_hide', 'list_color', 'grace', 'x_label_angle', 'show_box', 'x_label_margin', 'plot_perc_width', 'plot_value_show', 'plot_value_format', 'plot_value_pos', 'plot_value_color', 'group_by', 'sort_by', 'transparency', 'colors',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'list_color' => '#d60021', 'color' => '#d60021', 'font_family' => 15, 'font_style' => 9002, 'font_size' => 12, 'x_label_angle' => 0, 'show_box' => false, 'x_label_margin' => 10, 'plot_perc_width' => 90, 'plot_value_show' => 1, 'plot_value_pos' => 1, 'plot_value_color' => '#000000',
    ];

    /**
     * Larghezza del grafico, con il default storico di 800.
     *
     * Fino al 2026-08-25 questo metodo si chiamava `getTypeAttribute()` e dichiarava
     * `?string`, ma il corpo era rimasto quello della larghezza: leggeva
     * `$this->attributes['width']`, ripiegava su 800 e passava il risultato a
     * `SafeIntCastAction`. Una rinomina sbagliata, non un accessor di `type`.
     *
     * Il difetto era latente: la guardia `if ($value !== null) return $value;`
     * restituiva la stringa intatta per ogni grafico reale, e solo un `type` nullo
     * avrebbe prodotto 800 al posto di `null`. `Modules\Chart\Datas\ChartData` usa
     * `$this->type` come stringa (`'bar3'`, `'pieAvg'`), quindi il giorno che fosse
     * scattato avrebbe rotto la risoluzione dell'Action.
     *
     * `type` non ha bisogno di accessor: il valore passa come sta.
     * Story ROOT-17.10.
     */
    public function getWidthAttribute(?string $value): ?int
    {
        return SafeIntCastAction::cast($value ?: $this->attributes['width'] ?? 800);
    }

    public function getHeightAttribute(?string $value): ?int
    {
        return SafeIntCastAction::cast($value ?: $this->attributes['height'] ?? 600);
    }

    /**
     * Get chart settings as array of chart configurations.
     *
     * @return array<string, array<int|string, mixed>>
     */
    public function getSettings(): array
    {
        $type = $this->type;
        if ($type === null) {
            throw new InvalidArgumentException('Chart type cannot be null');
        }

        return ['chart' => $this->toArray()];
    }

    /**
     * Get attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'colors' => 'array',
            'show_box' => 'boolean',
            'plot_value_show' => 'boolean',
            'yaxis_hide' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
