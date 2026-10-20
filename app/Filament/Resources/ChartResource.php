<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Resources;

use Modules\Chart\Models\Chart;
use Modules\Xot\Filament\Resources\XotBaseResource;

class ChartResource extends XotBaseResource
{
    protected static ?string $model = Chart::class;
}
