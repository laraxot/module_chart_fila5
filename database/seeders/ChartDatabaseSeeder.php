<?php

declare(strict_types=1);

namespace Modules\Chart\Database\Seeders;

use Illuminate\Database\Seeder;

class ChartDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
       $this->call([
=======
        $this->call([
>>>>>>> laraxot/dev
            ChartSeeder::class,
            MixedChartSeeder::class,
        ]);
    }
}
