<?php

declare(strict_types=1);

use Modules\Chart\Models\Chart;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Indice di supporto alla relazione polimorfica `post` dei grafici.
 *
 * Solo aggiunte idempotenti (XotBaseMigration::ensureIndexes). Niente down(): togliere indici in
 * produzione e' una decisione dell'utente. Oggi `charts` ha solo la chiave primaria.
 *
 * Tipi verificati: `post_type` varchar, `post_id` int, entrambi confrontati con lo stesso tipo
 * (nome di morph map e id intero), quindi l'indice viene usato.
 *
 * @see ../../../Quaeris/docs/stories/quaeris-query-lente-e-indici.story.md
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = Chart::class;

    public function up(): void
    {
        $this->ensureIndexes([
            // HasChartTrait::chart() (morphOne, a ogni render di un QuestionChart) e
            // HasChartTrait::charts() (hasManyThrough con post_type = 'mixed_chart').
            (new Chart)->getTable() => [
                'charts_post_type_post_id_index' => ['post_type', 'post_id'],
            ],
        ]);
    }
};
