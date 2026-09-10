<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\ChartResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ChartsTable extends XotBaseResourceTable
{
     /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->sortable()
                ->searchable(),
            'type' => TextColumn::make('type')
                ->sortable()
                ->searchable(),
            'group_by' => TextColumn::make('group_by')
                ->sortable()
                ->searchable(),
            'sort_by' => TextColumn::make('sort_by')
                ->sortable(),
            'width' => TextColumn::make('width')
                ->numeric()
                ->sortable(),
            'height' => TextColumn::make('height')
                ->numeric()
                ->sortable(),
            'font_family' => TextColumn::make('font_family')
                ->searchable(),
            'font_style' => TextColumn::make('font_style')
                ->searchable(),
            'font_size' => TextColumn::make('font_size')
                ->numeric()
                ->sortable(),
        ];
    }
}