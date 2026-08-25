<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Stringable;
use Modules\Xot\Actions\Cast\SafeFloatCastAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
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
        public ChartData $chart = new ChartData,
        public ?int $total = null,
        public ?float $average = null,
    ) {}

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
            ->map(static fn (mixed $label): string => SafeStringCastAction::cast($label))
            ->values();

        $data = $answersCollection->pluck('value')->all();

        if (in_array($this->chart->type, ['pieAvg', 'pie1'], true)) {
            $data = $answersCollection->pluck('avg')->all();
        }

        if (isset($data[0]) && is_array($data[0])) {
            $legends = array_keys($data[0]);
            $lastSeriesIndex = \count($legends) - 1;
            foreach ($legends as $seriesIndex => $legend) {
                $series = array_column($data, $legend);
                $datasets[] = [
                    'label' => (string) $legend,
                    'data' => $this->normalizeSeries($series),
                    'borderColor' => $this->chart->getColorsRgba(0.5),
                    'backgroundColor' => $this->chart->getColorsRgba(0.5),
                    'datalabels' => $this->datalabelsForSeries((int) $seriesIndex, $lastSeriesIndex),
                ];
            }
        } else {
            $avgValues = $answersCollection->pluck('avg')->values()->map(
                static fn (mixed $item): string => number_format(SafeFloatCastAction::cast($item, 0.0), 2, '.', '')
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
     * Posizione del numero di una serie in un grafico a barre accumulate.
     *
     * Le serie di una categoria stanno sulla stessa barra, quindi i numeri non possono
     * stare tutti nello stesso punto. La serie in cima porta il numero sopra la barra;
     * le altre lo tengono appena sopra la base del proprio segmento, cioe' subito sotto.
     * Tutti i numeri hanno un fondo chiaro con bordo: senza, il testo scuro sparisce sul
     * colore pieno della barra.
     *
     * @return array<string, mixed>
     */
    private function datalabelsForSeries(int $seriesIndex, int $lastSeriesIndex): array
    {
        $isTopSeries = $seriesIndex === $lastSeriesIndex;

        return [
            'anchor' => $isTopSeries ? 'end' : 'start',
            'align' => 'top',
            'offset' => $isTopSeries ? 4 : 2,
            'clamp' => true,
            'clip' => false,
            'color' => '#0f172a',
            'backgroundColor' => 'rgba(255, 255, 255, 0.9)',
            'borderColor' => 'rgba(15, 23, 42, 0.15)',
            'borderWidth' => 1,
            'borderRadius' => 4,
            'padding' => ['top' => 2, 'right' => 6, 'bottom' => 2, 'left' => 6],
            'font' => ['size' => 11, 'weight' => 'bold'],
            // Un segmento a zero non ha spessore: la sua etichetta si sovrapporrebbe a
            // quella del segmento vicino senza aggiungere informazione.
            'display' => RawJs::make(<<<'JS'
                function(ctx) {
                    var value = Number(ctx.dataset.data[ctx.dataIndex]);
                    return Number.isFinite(value) && value > 0;
                }
            JS),
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

        $isHorizontal = \in_array($this->chart->type, ['horizbar1', 'horizbar2', 'horizontalBar'], true);
        if ($isHorizontal) {
            $options['indexAxis'] = 'y';
        }

        // `bar3` e `horizbar2` sono le "barre accumulate" del catalogo tipi: le serie di una
        // categoria formano una barra sola. Senza dichiarare gli assi come stacked Chart.js
        // le disegna affiancate, e le etichette dei valori — pensate per una barra unica —
        // finiscono sovrapposte alle barre vicine.
        if (\in_array($this->chart->type, ['bar3', 'horizbar2'], true)) {
            $options['scales'] = [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true],
            ];
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
