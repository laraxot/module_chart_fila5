<?php

declare(strict_types=1);

namespace Modules\Chart\Datas;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * DTO per i dati di un singolo answer
 */
class AnswerData extends Data
{
    public function __construct(
        public ?string $code = null,
        public ?string $answer = null,
        public ?int $count = null,
        public ?float $percent = null,
        public ?string $label = null,
        public int $gid = 0,
        public float|array $value = 0,
        public float|array|string $value1 = '',
        public ?string $key = null,
        public float|array|string $avg = 0,
        public ?string $title = null,
        public ?string $subtitle = null,
    ) {
    }

    /**
     * @param  EloquentCollection<int, Model>|array<int, mixed>  $data
     */
    public static function collection(EloquentCollection|array $data): DataCollection
    {
        return self::collect($data, DataCollection::class);
    }
}
