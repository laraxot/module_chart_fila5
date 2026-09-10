<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\MixedChartResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class MixedChartsTable extends XotBaseResourceTable
{
     /**
     * Definisce le colonne della tabella.
     *
     * @return array<int, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->sortable()
                ->searchable(),
            TextColumn::make('name')
                ->sortable()
                ->searchable(),
            TextColumn::make('description')
                ->limit(50)
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ];
    }
}