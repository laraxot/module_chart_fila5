<?php

declare(strict_types=1);

namespace Modules\Chart\Tables\Columns;

use Modules\Chart\Datas\AnswersChartData;
use Modules\Xot\Filament\Tables\Columns\XotBaseColumn;

use function Safe\json_encode;

class ChartColumn extends XotBaseColumn
{
    public string $dataChecksum = '';

    public ?string $filter = null;

    /** @var array<string, mixed> */
    public array $chartData = [
        'datasets' => [
            [
                'label' => 'loading...',
                'data' => [],
            ],
        ],
        'labels' => [],
    ];

    public string $chartType = 'bar';

    /** @var array<string, mixed> */
    public array $chartOptions = [];

    /** @var array<string, mixed>|null */
    protected ?array $cachedData = null;

    protected string $view = 'chart::tables.columns.chart-column';

    public function applyAnswersChartData(AnswersChartData $answersChartData): self
    {
        $this->chartData = $answersChartData->getChartJsData();
        $this->chartType = $answersChartData->getChartJsType();
        $this->chartOptions = $answersChartData->getChartJsOptionsArray();

        return $this;
    }

    /** @return array<string, mixed> */
    public function getCachedData(): array
    {
        return $this->cachedData ??= $this->getData();
    }

<<<<<<< HEAD
   /** @return array<string, mixed>|null */
=======
    /** @return array<string, mixed>|null */
>>>>>>> laraxot/dev
    public function getOptions(): ?array
    {
        return $this->chartOptions;
    }

    public function getType(): string
    {
        return $this->chartType;
    }

    protected function generateDataChecksum(): string
    {
        return md5(json_encode($this->getCachedData()));
    }

<<<<<<< HEAD
   /** @return array<string, mixed> */
=======
    /** @return array<string, mixed> */
>>>>>>> laraxot/dev
    protected function getData(): array
    {
        return $this->chartData;
    }
}
