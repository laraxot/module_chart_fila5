<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Illuminate\Support\HtmlString;
use Spatie\LaravelData\Data;
use function Safe\json_encode;

/**
 * DTO per la configurazione di un chart.
 * Espone getChartJsType/getChartJsData/getChartJsOptionsJs per compatibilità
 * quando usato al posto di AnswersChartData (es. QuestionChartItemWidget).
 */
class ChartData extends Data
{
    public function __construct(
        public ?string $type = null,
        public ?string $group_by = null,
        public ?string $sort_by = null,
        public ?string $title = null,
        public ?array $options = null,
    ) {
    }

    public function getChartJsType(): string
    {
        return match ($this->type) {
            'pie1', 'pieAvg' => 'doughnut',
            'lineSubQuestion' => 'line',
            'bar2', 'bar1', 'bar3', 'horizbar1', 'horizontalBar' => 'bar',
            default => $this->type ?? 'bar',
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
