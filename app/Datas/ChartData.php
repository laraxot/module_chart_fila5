<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Spatie\Color\Hex;
use Spatie\LaravelData\Data;

use function Safe\json_encode;

/**
 * DTO per la configurazione di un chart JpGraph / Chart.js.
 */
class ChartData extends Data
{
    public function __construct(
        public string $type = 'bar',
        public float $max = 100.0,
        public float $min = 0.0,
        public ?int $width = 800,
        public int $height = 600,
        public ?string $title = null,
        public ?string $subtitle = null,
        public string $list_color = '#d60021',
        public ?string $bg_color = null,
        public string $font_family = '1',
        public string $font_size = '12',
        public string $font_style = '0',
        public ?int $y_grace = null,
        public ?int $yaxis_hide = null,
        public string $x_label_angle = '0',
        public int $show_box = 1,
        public int $x_label_margin = 10,
        public int $plot_perc_width = 90,
        public bool $plot_value_show = true,
        public int|string|null $plot_value_format = null,
        public ?string $plot_value_color = '#000000',
        public string $transparency = '0.5',
        public ?string $engine_type = null,
        public ?string $footer = null,
        public int $plot_value_pos = 0,
        public ?string $answer_value_no_txt = null,
        public ?string $answer_value_txt = null,
        /** @var array<string, mixed>|null */
        public ?array $legend = null,
        /** @var array<int, string>|null */
        public ?array $sublabels = null,
        public ?float $avg = null,
        /** @var array<int|string, mixed>|null */
        public ?array $totali = null,
        public ?string $group_by = null,
        public ?string $sort_by = null,
        /** @var array<string, mixed>|null */
        public ?array $options = null,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function getColors(): array
    {
        return explode(',', $this->list_color);
    }

    /**
     * @return array<int, string>
     */
    public function getColorsRgba(float $alpha = 1): array
    {
        $colors = $this->getColors();

        return collect($colors)->map(function (string $item) use ($alpha): string {
            if (! Str::startsWith($item, '#')) {
                return $item;
            }

            $hex = Hex::fromString($item);

            return (string) $hex->toRgba($alpha);
        })->all();
    }

    public function getActionClass(): string
    {
        $engine = 'JpGraph\V1';
        $action = Str::studly($this->type).'Action';

        return '\Modules\Chart\Actions\\'.$engine.'\\'.$action;
    }

    public function getChartJsType(): string
    {
        return match ($this->type) {
            'pie1', 'pieAvg' => 'doughnut',
            'lineSubQuestion' => 'line',
            'bar2', 'bar1', 'bar3', 'horizbar1', 'horizontalBar' => 'bar',
            default => $this->type,
        };
    }

    /**
     * @return array{datasets: array<int, array<string, mixed>>, labels: array<int, string>}
     */
    public function getChartJsData(): array
    {
        return [
            'datasets' => [['label' => 'Dati', 'data' => [], 'borderColor' => null, 'backgroundColor' => null]],
            'labels' => [],
        ];
    }

    public function getChartJsOptionsJs(): HtmlString
    {
        $options = [
            'plugins' => ['title' => ['display' => true, 'text' => $this->title ?? '', 'font' => ['size' => 14]]],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
        if ($this->type === 'horizbar1' || $this->type === 'horizontalBar') {
            $options['indexAxis'] = 'y';
        }

        return new HtmlString(json_encode($options));
    }
}
