<?php

declare(strict_types=1);

namespace Modules\Chart\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Chart\Models\MixedChart;

class MixedChartSeeder extends Seeder
{
    public function run(): void
    {
        xotSeedModelOnce(MixedChart::class);
    }
}
