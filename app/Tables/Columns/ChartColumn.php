<?php

declare(strict_types=1);

namespace Modules\Chart\Tables\Columns;

use Filament\Tables\Columns\Column;
use Illuminate\Contracts\View\View;
use Modules\Chart\Datas\AnswersChartData;

use function Safe\json_encode;

class ChartColumn extends Column
{
    public string $dataChecksum = '';
    public ?string $filter = null;
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
    public array $chartOptions = [];
    protected ?array $cachedData = null;
    protected string $view = 'chart::tables.columns.chart-column';

    public function applyAnswersChartData(AnswersChartData $answersChartData): self
    {
        $this->chartData = $answersChartData->getChartJsData();
        $this->chartType = $answersChartData->getChartJsType();
        $this->chartOptions = $answersChartData->getChartJsOptionsArray();
        return $this;
    }

    public function getCachedData(): array
    {
        return $this->cachedData ??= $this->getData();
    }

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

    protected function getData(): array
    {
        return $this->chartData;
    }
}
