<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Stringable;
use Modules\Xot\Actions\Cast\SafeFloatCastAction;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

use function Safe\json_encode;

class AnswersChartData extends Data
{
    public function __construct(
        public int $tot = 0,
        public string $title = 'no_set',
        public string $footer = 'no_set',
        #[MapInputName('tot_answered')]
        public int $totalAnswered = 0,
        #[MapInputName('tot_invited')]
        public int $totalInvited = 0,
        /** @var DataCollection<int, AnswerData> */
        public DataCollection $answers = new DataCollection(AnswerData::class, []),
        public ChartData $chart = new ChartData(),
        public ?int $total = null,
        public ?float $average = null,
    ) {
    }

    public function getChartJsType(): string
    {
        return $this->chart->getChartJsType();
    }

    /**
     * @return array<string, mixed>
     */
    public function getChartJsData(): array
    {
        $datasets = [];
        $answersCollection = $this->answers->toCollection();

        $labelsCollection = $answersCollection
            ->pluck('label')
            ->map(static fn ($label): string => (string) $label)
            ->values();

        $data = $answersCollection->pluck('value')->all();

        if (in_array($this->chart->type, ['pieAvg', 'pie1'], true)) {
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

            $firstAvg = $answersCollection->pluck('avg')->first();
            $label = isset($firstAvg) && ! is_string($firstAvg)
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

    /**
     * @return array<string, mixed>
     */
    public function getChartJsOptionsArray(): array
    {
        $options = [
            'plugins' => [
                'title' => ($this->title !== 'no_set') ? [
                    'display' => true,
                    'text' => $this->title,
                    'font' => ['size' => 14],
                ] : [],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];

        if ($this->chart->type === 'horizbar1' || $this->chart->type === 'horizontalBar') {
            $options['indexAxis'] = 'y';
        }

        return $options;
    }

    public function getChartJsOptionsJs(): HtmlString|RawJs
    {
        return new HtmlString(json_encode($this->getChartJsOptionsArray()));
    }

    /**
     * @return array<string, mixed>
     */
    public function getChartJsOptions(): array
    {
        return $this->getChartJsOptionsArray();
    }

    /**
     * @param  array<mixed>  $series
     * @return array<int, int|float|string>
     */
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
    }
}
