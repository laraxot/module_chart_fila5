<?php

declare(strict_types=1);

namespace Modules\Chart\Tests\Unit\Filament;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Modules\Chart\Actions\Chart\GetTypeOptions;
use Modules\Chart\Filament\Resources\ChartResource\Schemas\ChartForm;
use Modules\Chart\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/*
 * Il form vero del grafico e' `ChartForm` (`XotBaseResource::form()` usa solo lui). Fino al 2026-10-20 sette campi
 * e le opzioni vere di `type` esistevano solo in `ChartResource::getFormSchemaOld()`, che non arrivava al pannello.
 * Story: Modules/Chart/docs/stories/getformschemaold-final-chart-resources.story.md
 */

function chartFormField(string $name): Field
{
    $field = app(ChartForm::class)->getFormSchema()[$name] ?? null;
    Assert::assertInstanceOf(Field::class, $field, \sprintf('Il campo "%s" del grafico non e\' nel form del pannello.', $name));

    return $field;
}

/**
 * @return array<int|string, mixed>
 */
function chartFormSelectOptions(string $name): array
{
    $select = chartFormField($name);
    Assert::assertInstanceOf(Select::class, $select);

    return $select->getOptions();
}

test('chart_form_exposes_every_chart_attribute_edited_from_the_panel', function (): void {
    $fields = [
        'type', 'group_by', 'sort_by', 'width', 'height', 'show_box', 'font_family',
        'font_style', 'font_size', 'list_color', 'transparency', 'color', 'bg_color',
    ];

    foreach ($fields as $name) {
        Assert::assertSame($name, chartFormField($name)->getName());
    }
});

test('chart_form_type_options_are_the_chart_types_the_system_knows', function (): void {
    $options = chartFormSelectOptions('type');

    Assert::assertSame(array_keys(app(GetTypeOptions::class)->execute()), array_keys($options));
    Assert::assertArrayHasKey('pie1', $options);
    Assert::assertArrayNotHasKey('bar', $options);
});

test('chart_form_font_selects_keep_the_numeric_keys_of_the_translated_options', function (): void {
    app()->setLocale('it');

    foreach (['font_family', 'font_style'] as $name) {
        $expected = trans('chart::chart.options.'.$name);
        Assert::assertIsArray($expected);
        Assert::assertNotSame([], $expected);
        Assert::assertSame($expected, chartFormSelectOptions($name));
    }
});
