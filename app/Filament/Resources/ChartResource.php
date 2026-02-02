<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Modules\Chart\Actions\Chart\GetTypeOptions;
use Modules\Chart\Models\Chart;
use Modules\Xot\Filament\Resources\XotBaseResource;

class ChartResource extends XotBaseResource
{
    protected static ?string $model = Chart::class;

    /**
     * @return array<string, mixed>
     */
    public static function getFormSchema(): array
    {
        return [
            'type' => Select::make('type')
                ->options(app(GetTypeOptions::class)->execute()),
            
            'group_by' => Select::make('group_by')
                ->options((array) trans('chart::chart.options.group_by')),
            
            'sort_by' => Select::make('sort_by')
                ->options((array) trans('chart::chart.options.group_by')), // Reuse group_by options for simplicity if matching
            
            'width' => TextInput::make('width'),
            
            'height' => TextInput::make('height'),
            
            'show_box' => Toggle::make('show_box')
                ->inline(false),

            'font_family' => Select::make('font_family')
                ->options((array) trans('chart::chart.options.font_family')),
            
            'font_style' => Select::make('font_style')
                ->options((array) trans('chart::chart.options.font_style')),
            
            'font_size' => Select::make('font_size')
                ->options([
                    '8' => '8',
                    '10' => '10',
                    '12' => '12',
                    '14' => '14',
                    '16' => '16',
                    '18' => '18',
                ]),
            
            'list_color' => TextInput::make('list_color'),
            
            'transparency' => TextInput::make('transparency'),
        ];
    }
}
