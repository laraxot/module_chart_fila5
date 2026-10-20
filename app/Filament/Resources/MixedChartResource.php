<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources;

// use Modules\Chart\Filament\Resources\MixedChartResource\RelationManagers;
// use Filament\Forms;
use Modules\Chart\Models\MixedChart;
use Modules\Xot\Filament\Resources\XotBaseResource;

// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

class MixedChartResource extends XotBaseResource
{
    protected static ?string $model = MixedChart::class;
}
