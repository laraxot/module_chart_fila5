<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Illuminate\Support\HtmlString;
use Spatie\LaravelData\Data;

/**
 * DTO per i dati aggregati delle risposte di un chart.
 * answers: array di elementi con label/value (non DataCollection per evitare CreationContext::next(null)).
 */
class AnswersChartData extends Data
{
    public function __construct(
        public ?ChartData $chart = null,
        public ?string $title = null,
        /** @var array<int, array<string, mixed>> */
        public array $answers = [],
        public ?int $total = null,
        public ?float $average = null,
        public ?int $totalInvited = null,
        public ?int $totalAnswered = null,
        public ?string $footer = null,
    ) {
    }

    public function getChartJsType(): string
    {
        if ($this->chart === null) {
            return 'bar';
        }

        $type = $this->chart->type;
        return match ($type) {
            'pie1', 'pieAvg' => 'doughnut',
            'lineSubQuestion' => 'line',
            'bar2', 'bar1', 'bar3', 'horizbar1', 'horizontalBar' => 'bar',
            default => $type ?? 'bar',
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function getChartJsData(): array
    {
        $labels = collect($this->answers)
            ->pluck('label')
            ->map(fn ($label) => (string) $label)
            ->values()
            ->all();

        $data = collect($this->answers)->pluck('value')->all();

        $colors = $this->chart?->options['colors'] ?? null;
        $colorsArray = is_array($colors) ? $colors : [];

        if (isset($data[0]) && is_array($data[0])) {
            $legends = array_keys($data[0]);
            $datasets = [];

            foreach ($legends as $key => $legend) {
                $series = array_column($data, $legend);
                $datasets[] = [
                    'label' => (string) $legend,
                    'data' => $series,
                    'borderColor' => $colorsArray[$key] ?? null,
                    'backgroundColor' => $colorsArray[$key] ?? null,
                ];
            }
        } else {
            $datasets = [[
                'label' => 'Dati',
                'data' => $data,
                'borderColor' => $colorsArray,
                'backgroundColor' => $colorsArray,
            ]];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getChartJsOptionsArray(): array
    {
        $options = [
            'plugins' => [
                'title' => ($this->title !== null && $this->title !== 'no_set') ? [
                    'display' => true,
                    'text' => $this->title,
                    'font' => ['size' => 14],
                ] : [],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];

        if ($this->chart?->type === 'horizbar1' || $this->chart?->type === 'horizontalBar') {
            $options['indexAxis'] = 'y';
        }

        return $options;
    }

    public function getChartJsOptionsJs(): HtmlString
    {
        $json = json_encode($this->getChartJsOptionsArray());
        return new HtmlString($json !== false ? $json : '{}');
    }

    /**
     * Alias per compatibilità con QuestionChart Livewire.
     *
     * @return array<string, mixed>
     */
    public function getChartJsOptions(): array
    {
        return $this->getChartJsOptionsArray();
    }
}
