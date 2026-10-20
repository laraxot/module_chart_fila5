<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\ChartResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component as SchemaComponent;
use Modules\Chart\Actions\Chart\GetTypeOptions;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class ChartForm extends XotBaseResourceForm
{
    /**
     * @return array<string, SchemaComponent>
     */
    public function getFormSchema(): array
    {
        return [
            'type' => Select::make('type')
                ->options(app(GetTypeOptions::class)->execute()),
            // Nessuna opzione sort_by nei file lang: si usano quelle di group_by, come faceva lo schema legacy.
            'group_by' => Select::make('group_by')
                ->options(self::getTransOptions('chart::chart.options.group_by')),
            'sort_by' => Select::make('sort_by')
                ->options(self::getTransOptions('chart::chart.options.group_by')),
            'width' => TextInput::make('width')->numeric(),
            'height' => TextInput::make('height')->numeric(),
            'show_box' => Toggle::make('show_box')
                ->inline(false),
            'font_family' => Select::make('font_family')
                ->options(self::getTransOptions('chart::chart.options.font_family')),
            'font_style' => Select::make('font_style')
                ->options(self::getTransOptions('chart::chart.options.font_style')),
            'font_size' => Select::make('font_size')
                ->options([
                    '8' => '8',
                    '10' => '10',
                    '12' => '12',
                    '14' => '14',
                    '16' => '16',
                    '18' => '18',
                ]),
            'color' => TextInput::make('color'),
            'bg_color' => TextInput::make('bg_color'),
            'list_color' => TextInput::make('list_color'),
            'transparency' => TextInput::make('transparency'),
        ];
    }

    /**
     * Le chiavi restano quelle del file lang: font_family e font_style hanno chiavi intere, che sono i valori salvati.
     *
     * @return array<int|string, string>
     */
    private static function getTransOptions(string $key): array
    {
        $raw = trans($key);
        if (! \is_array($raw)) {
            return [];
        }

        $options = [];
        foreach ($raw as $value => $label) {
            if (\is_string($label)) {
                $options[$value] = $label;
            }
        }

        return $options;
    }
}
