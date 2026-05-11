<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\ChartResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class ChartInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, \Filament\Infolists\Components\Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'name' => TextEntry::make('name'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
        ];
    }
}