<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

<<<<<<< Updated upstream
||||||| Stash base
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Modules\Xot\Actions\Cast\SafeFloatCastAction;
use Spatie\LaravelData\Attributes\MapInputName;
=======
use Illuminate\Support\HtmlString;
>>>>>>> Stashed changes
use Spatie\LaravelData\Data;

/**
<<<<<<< Updated upstream
 * DTO per i dati aggregati delle risposte di un chart
||||||| Stash base
=======
 * DTO per i dati aggregati delle risposte di un chart.
 * answers: array di elementi con label/value (non DataCollection per evitare CreationContext::next(null)).
>>>>>>> Stashed changes
 */
class AnswersChartData extends Data
{
    public function __construct(
        public ?ChartData $chart = null,
        public ?string $title = null,
<<<<<<< Updated upstream
        public array $answers = [],
        public ?int $total = null,
        public ?float $average = null,
        public ?int $totalInvited = null,
        public ?int $totalAnswered = null,
        public ?string $footer = null,
    ) {
||||||| Stash base
    public int $tot = 0;
    public string $title = 'no_set';
    public string $footer = 'no_set';

    #[MapInputName('tot_answered')]
    public int $totalAnswered = 0;

    #[MapInputName('tot_invited')]
    public int $totalInvited = 0;

    /**
     * @var DataCollection<AnswerData>
     */
    public DataCollection $answers;

    public ChartData $chart;

    public function getChartJsType(): string
    {
        $type = $this->chart->type;
        switch ($type) {
            case 'pie1':
            case 'pieAvg':
                $type = 'doughnut';
                break;
            case 'lineSubQuestion':
                $type = 'line';
                break;
            case 'bar2':
            case 'bar1':
            case 'bar3':
            case 'horizbar1':
            case 'horizontalBar':
                $type = 'bar';
                break;
        }

        return $type;
    }

    public function getChartJsData(): array
    {
        $datasets = [];
        $answersCollection = $this->answers->toCollection();

        $labelsCollection = $answersCollection
            ->pluck('label')
            ->map(static fn ($label): string => (string) $label)
            ->values();

        $data = $answersCollection->pluck('value')->all();

        if (in_array($this->chart->type, ['pieAvg', 'pie1'], false)) {
            $data = $answersCollection->pluck('avg')->all();
        }

        if (isset($data[0]) && is_array($data[0])) {
            $legends = array_keys($data[0]);
            foreach ($legends as $legend) {
                $series = array_column($data, $legend);
                $datasets[] = [
                    'label' => (string) $legend,
                    'data' => $this->normalizeSeries($series),
                    'borderColor' => $this->chart->getColorsRgba(0.5),
                    'backgroundColor' => $this->chart->getColorsRgba(0.5),
                ];
            }
        } else {
            $avgValues = $answersCollection->pluck('avg')->values()->map(
                static fn ($item): string => number_format(SafeFloatCastAction::cast($item, 0.0), 2, '.', '')
            )->all();

            $label = isset($answersCollection->pluck('avg')[0]) && ! is_string($answersCollection->pluck('avg')[0])
                ? 'Media'
                : 'Percentuale';

            $datasets = [
                [
                    'label' => $label,
                    'data' => array_values($avgValues),
                    'data2' => $this->normalizeSeries($answersCollection->pluck('value')->all()),
                    'borderColor' => $this->chart->getColorsRgba(0.5),
                    'backgroundColor' => $this->chart->getColorsRgba(0.5),
                ],
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labelsCollection->values()->all(),
        ];
    }

    public function getChartJsOptionsJs(): RawJs
    {
        return RawJs::make("{}");
    }

    private function normalizeSeries(array $series): array
    {
        $normalized = [];
        foreach (array_values($series) as $value) {
            if (is_int($value) || is_float($value) || is_string($value)) {
                $normalized[] = $value;
                continue;
            }
            if ($value === null) {
                $normalized[] = 0;
                continue;
            }
            $normalized[] = $value instanceof Stringable ? (string) $value : '';
        }
        return $normalized;
=======
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
            'bar2', 'bar1', 'bar3', 'horizbar1' => 'bar',
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
                'title' => $this->title !== 'no_set' ? [
                    'display' => true,
                    'text' => $this->title,
                    'font' => ['size' => 14],
                ] : [],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];

        if ($this->chart?->type === 'horizbar1') {
            $options['indexAxis'] = 'y';
        }

        return $options;
    }

    public function getChartJsOptionsJs(): HtmlString
    {
        $json = json_encode($this->getChartJsOptionsArray());
        return new HtmlString($json !== false ? $json : '{}');
>>>>>>> Stashed changes
    }
}
