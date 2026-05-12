<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\ChartResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ChartsTable extends XotBaseResourceTable
{
    public static function getTableColumns(): array
    {
    /**
     * @return array<int\|string, \Filament\Tables\Columns\Column>
     */
        return [
            'id' => TextColumn::make('id')->searchable()->sortable(),
            'title' => TextColumn::make('title')->searchable()->sortable(),
            'type' => TextColumn::make('type'),
            'created_at' => TextColumn::make('created_at')->dateTime(),
        ];
    }
}