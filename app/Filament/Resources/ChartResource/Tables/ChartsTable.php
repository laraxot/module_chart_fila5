<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\ChartResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ChartsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'type' => TextColumn::make('type')->searchable()->sortable()->badge(),
            'group_by' => TextColumn::make('group_by')->searchable()->sortable(),
            'sort_by' => TextColumn::make('sort_by')->sortable(),
            'width' => TextColumn::make('width')->numeric()->sortable(),
            'height' => TextColumn::make('height')->numeric()->sortable(),
            'font_family' => TextColumn::make('font_family')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
            'font_style' => TextColumn::make('font_style')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
            'font_size' => TextColumn::make('font_size')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
