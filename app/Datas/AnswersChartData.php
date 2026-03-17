<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Spatie\LaravelData\Data;

/**
 * DTO per i dati aggregati delle risposte di un chart
 */
class AnswersChartData extends Data
{
    public function __construct(
        public ?ChartData $chart = null,
        public ?string $title = null,
        public array $answers = [],
        public ?int $total = null,
        public ?float $average = null,
        public ?int $totalInvited = null,
        public ?int $totalAnswered = null,
        public ?string $footer = null,
    ) {
    }
}
