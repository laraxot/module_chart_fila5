<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\MixedChartResource\Pages;

use Modules\Chart\Filament\Resources\MixedChartResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * Pagina di elenco per le risorse MixedChart.
 */
class ListMixedCharts extends XotBaseListRecords
{
    /**
     * Risorsa associata a questa pagina.
     */
    protected static string $resource = MixedChartResource::class;
}
