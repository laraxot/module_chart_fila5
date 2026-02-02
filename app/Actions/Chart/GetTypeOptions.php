<?php

declare(strict_types=1);

namespace Modules\Chart\Actions\Chart;

use Modules\Chart\Models\MixedChart;
use Spatie\QueueableAction\QueueableAction;

class GetTypeOptions
{
    use QueueableAction;

    /**
     * Get chart type options including mixed charts.
     *
     * @return array<string, string>
     */
    public function execute(): array
    {
        $options = (array) trans('chart::chart.options.type');

        $mixed = MixedChart::get()->pluck('name', 'id')->all();
        $data = [];
        foreach ($mixed as $k => $v) {
            if (! is_string($v)) {
                continue;
            }
            $k1 = 'mixed:'.$k;
            $data[$k1] = 'Mixed: '.$v;
        }

        return [...$options, ...$data];
    }
}
