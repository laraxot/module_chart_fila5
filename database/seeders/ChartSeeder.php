<?php

declare(strict_types=1);

namespace Modules\Chart\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Chart\Models\Chart;

class ChartSeeder extends Seeder
{
    public function run(): void
    {
        xotSeedModelOnce(Chart::class);
    }
}
