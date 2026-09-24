<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\MixedChartResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Chart\Models\MixedChart;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class MixedChartsTable extends XotBaseResourceTable
{
    /**
     * @var class-string<MixedChart>
     */
    protected static string $model = MixedChart::class;

    /**
     * Definisce le colonne della tabella.
     *
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'charts_count' => TextColumn::make('charts_count')->counts('charts')->numeric()->sortable(),
            'id' => TextColumn::make('id')->sortable()->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
