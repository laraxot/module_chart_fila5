<?php

declare(strict_types=1);

namespace Modules\Chart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Chart\Models\MixedChart;

/**
 * @extends Factory<MixedChart>
 */
class MixedChartFactory extends Factory
{
    /**
     * @var class-string<MixedChart>
     */
    protected $model = MixedChart::class;

    /**
<<<<<<< HEAD
    * @return array<string, mixed>
=======
     * @return array<string, mixed>
>>>>>>> laraxot/dev
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(5),
<<<<<<< HEAD
           'name' => $this->faker->name(),
=======
            'name' => $this->faker->name(),
>>>>>>> laraxot/dev
        ];
    }
}
