<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\ChartResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component as SchemaComponent;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class ChartForm extends XotBaseResourceForm
{
    /**
     * @return array<string, SchemaComponent>
     */
    public static function getFormSchema(): array
    {
        return [
            'type' => Select::make('type')
                ->options([
                    'bar' => 'Bar',
                    'line' => 'Line',
                    'pie' => 'Pie',
                ]),
            'color' => TextInput::make('color'),
            'bg_color' => TextInput::make('bg_color'),
            'width' => TextInput::make('width')->numeric(),
            'height' => TextInput::make('height')->numeric(),
            'transparency' => TextInput::make('transparency'),
        ];
    }
}
