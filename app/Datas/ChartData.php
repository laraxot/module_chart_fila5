<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Spatie\LaravelData\Data;

/**
 * DTO per la configurazione di un chart
 */
class ChartData extends Data
{
    public function __construct(
        public ?string $type = null,
        public ?string $group_by = null,
        public ?string $sort_by = null,
        public ?string $title = null,
        public ?array $options = null,
    ) {
    }
}
