<?php

declare(strict_types=1);

namespace Modules\Chart\Actions\Chart;

use Modules\Xot\Actions\Cast\SafeFloatCastAction;
use Spatie\QueueableAction\QueueableAction;

/**
 * Calcola l'array 'offset' per un dataset Chart.js di tipo doughnut: 0 su ogni
 * fetta tranne quella col valore piu' piccolo, che riceve uno scostamento in
 * pixel per staccarsi visivamente dal resto dell'anello (richiesta utente,
 * story quaeris-dashboard-chart-visual-improvements.md, Task 1). A parita' di
 * valore minimo, vince il primo indice incontrato. Valori non numerici
 * contano come 0. 'offset' e' una proprieta' nativa di Chart.js sul dataset,
 * un valore in pixel per ciascun indice — nessun plugin necessario.
 */
class BuildMinoritySliceOffsetAction
{
    use QueueableAction;

    /**
     * Scostamento in pixel applicato alla fetta minoritaria.
     */
    private const int MINORITY_SLICE_OFFSET_PX = 40;

    /**
     * @param  array<int, mixed>  $values  gli stessi valori che finiscono in
     *                                     'data' — l'offset deve riferirsi a
     *                                     ciò che Chart.js disegna davvero
     * @return array<int, int>
     */
    public function execute(array $values): array
    {
        $numeric = array_map(
            static fn (mixed $value): float => SafeFloatCastAction::cast($value, 0.0),
            array_values($values)
        );

        if ($numeric === []) {
            return [];
        }

        $minIndex = array_key_first($numeric);
        $minValue = $numeric[$minIndex];
        foreach ($numeric as $index => $value) {
            if ($value < $minValue) {
                $minValue = $value;
                $minIndex = $index;
            }
        }

        return array_map(
            static fn (int $index): int => $index === $minIndex ? self::MINORITY_SLICE_OFFSET_PX : 0,
            array_keys($numeric)
        );
    }
}
