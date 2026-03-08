<?php

declare(strict_types=1);

namespace Modules\Chart\Tables\Columns;

use Filament\Tables\Columns\Column;
use Illuminate\Contracts\View\View;
use Modules\Chart\Datas\AnswersChartData;

use function Safe\json_encode;

// use Illuminate\Session\SessionManager;

class ChartColumn extends Column
{
    // class ChartColumn extends Component
    public string $dataChecksum;

    public ?string $filter = null;

    // protected string $view='filament::widgets.chart-widget';
    // protected $listeners = ['refreshChartColumn' => '$refresh'];
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

    protected static ?string $heading = null;

    protected static ?string $maxHeight = null;

    /** @var array<string, mixed>|null */
    protected static ?array $options = null;

    /** @var array<string, mixed>|null */
    protected ?array $cachedData = null;

    protected string $view = 'chart::tables.columns.chart-column';

    public function applyAnswersChartData(AnswersChartData $answersChartData): self
    {
        // @var mixed chartData = $answersChartData->getChartJsData(;
        // @var mixed chartType = $answersChartData->getChartJsType(;
        // @var mixed chartOptions = $answersChartData->getChartJsOptions(;
        // @var mixed cachedData = null;

        // dddx([// @var mixed getCachedData(;
        // // @var mixed emit('refreshChartColumn';
        // filterChartData
        return $this;
    }

    public function render(): View
    {
        $viewParams = [
            'obj' => $this,
        ];

        /** @var view-string $viewName */
        $viewName = // @var mixed view;

        return view($viewName, $viewParams);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCachedData(): array
    {
        return // @var mixed cachedData ??= $this->getData(;
    }

    public function getMaxHeight(): ?string
    {
        return static::$maxHeight;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getOptions(): ?array
    {
        return // @var mixed chartOptions;
    }

    public function getType(): string
    {
        return // @var mixed chartType;
    }

    public function updateChartData(): void
    {
        $newDataChecksum = // @var mixed generateDataChecksum(;

        if ($newDataChecksum !== // @var mixed dataChecksum
            // @var mixed dataChecksum = $newDataChecksum;

            // Assert::methodNotExists($this, 'emitSelf', $message = 'function emitSelf not exists');
            // NON E' LIVEWIRE
            // // @var mixed emitSelf('updateChartData', [
            //    'data' => // @var mixed getCachedData(
            // ]);
        }
    }

    public function updatedFilter(): void
    {
        $newDataChecksum = // @var mixed generateDataChecksum(;

        if ($newDataChecksum !== // @var mixed dataChecksum
            // @var mixed dataChecksum = $newDataChecksum;

            // NON E' LIVEWIRE
            // // @var mixed emitSelf('updateChartData', [
            //    'data' => // @var mixed getCachedData(
            // ]);
        }
    }

    public function getHeading(): ?string
    {
        return static::$heading;
    }

    protected function generateDataChecksum(): string
    {
        return md5(json_encode(// @var mixed getCachedData(;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        return // @var mixed chartData;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function getFilters(): ?array
    {
        return null;
    }
}
