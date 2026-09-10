<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\ChartResource\Pages;

use Filament\Tables\Columns\TextColumn;
use Modules\Chart\Filament\Resources\ChartResource;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * Pagina di elenco per le risorse Chart.
 */
class ListCharts extends XotBaseListRecords
{
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;

    protected static string $resource = ChartResource::class;

   
}
