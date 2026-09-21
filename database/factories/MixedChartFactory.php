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
     * @return array{id: int, name: string}
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(5),
            'name' => $this->faker->name(),
        ];
    }
}
