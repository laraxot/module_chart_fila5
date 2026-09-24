<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources\MixedChartResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Modules\Chart\Filament\Resources\MixedChartResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditMixedChart extends XotBaseEditRecord
{
    protected static string $resource = MixedChartResource::class;

    /**
     * @return array<string, Action | ActionGroup>
     */
    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
