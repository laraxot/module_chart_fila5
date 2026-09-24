---
title: "Chart — XotBaseResourceTable $model audit (batch-chart)"
status: done
module: Chart
date: 2026-09-11
---

# Story: Chart — audit `$model` + colonne su XotBaseResourceTable (batch-chart)

**Fase BMAD**: Audit / qualità (verifica tipizzazione e schema, nessuna modifica
funzionale distruttiva).

**Contesto**: audit cross-modulo su tutte le classi `XotBaseResourceTable` per
confermare che dichiarino `protected static string $model = X::class;` con il
model corretto (autorevole = quello dichiarato sulla Resource sorella), e che
`getTableColumns()` referenzi solo colonne realmente esistenti sullo schema DB.

**Owned scope** (file toccati in questo batch, repo `module_chart_fila5`):
- `app/Filament/Resources/ChartResource/Tables/ChartsTable.php`
- `app/Filament/Resources/MixedChartResource/Tables/MixedChartsTable.php`

## Task 1 — `$model`

Entrambi i file avevano **già** `protected static string $model = X::class;`
corretto e coerente con la Resource sorella:

- `ChartsTable::$model = Chart::class` — confermato contro
  `ChartResource::$model = Chart::class` (`app/Filament/Resources/ChartResource.php:16`).
- `MixedChartsTable::$model = MixedChart::class` — confermato contro
  `MixedChartResource::$model = MixedChart::class`
  (`app/Filament/Resources/MixedChartResource.php:19`).

Nessuna modifica necessaria.

## Task 2 — verifica colonne contro schema reale

Schema letto in sola lettura via tinker:

```
php artisan tinker --execute="echo implode(',', Illuminate\Support\Facades\Schema::getColumnListing((new \Modules\Chart\Models\Chart)->getTable()));"
php artisan tinker --execute="echo implode(',', Illuminate\Support\Facades\Schema::getColumnListing((new \Modules\Chart\Models\MixedChart)->getTable()));"
```

**`charts`** (tabella di `Chart`): `id, post_type, post_id, color, bg_color,
font_family, font_style, font_size, created_at, updated_at, created_by,
updated_by, y_grace, yaxis_hide, list_color, x_label_angle, show_box,
x_label_margin, width, height, type, plot_perc_width, plot_value_show,
plot_value_format, plot_value_pos, plot_value_color, group_by, sort_by, lang,
transparency, colors, grace, deleted_at, deleted_by`.

`ChartsTable::getTableColumns()` usa: `id, type, group_by, sort_by, width,
height, font_family, font_style, font_size` — **tutte presenti**, nessuna
colonna sospetta, nessuna chiave con `.` (relazione).

**`mixed_charts`** (tabella di `MixedChart`): `id, name, created_at,
updated_at, created_by, updated_by`.

`MixedChartsTable::getTableColumns()` usa: `name, charts_count, id,
created_at, updated_at` — `name/id/created_at/updated_at` presenti;
`charts_count` è un aggregato (`->counts('charts')` sulla relazione
`MorphMany` `MixedChart::charts()`), non una colonna fisica — corretto così,
nessuna azione.

Esito: **nessuna colonna sospetta in nessuno dei due file**.

## Task 3 — miglioria UX (additiva, basso rischio)

`ChartsTable.php`, colonna `sort_by`: aggiunto `->searchable()` per coerenza
con la colonna gemella `group_by` (stessa natura — entrambe pilotate dalla
stessa fonte di opzioni `chart::chart.options.group_by` in
`ChartResource::getFormSchemaOld()`) che già aveva `->searchable()->sortable()`
mentre `sort_by` aveva solo `->sortable()`. Nessuna altra colonna toccata:
`width`/`height`/`font_family`/`font_style`/`font_size` sono valori numerici o
codici lookup, `->searchable()` non è giustificato lì.

`MixedChartsTable.php`: nessuna modifica — colonne già coerenti
(`name` searchable+sortable, `created_at`/`updated_at` già `->dateTime()`,
schema.org `dateCreated`/`dateModified` già rispettato).

Nessuna colonna rimossa. Nessun uso di `PersonColumn` (nessun campo
anagrafico multi-campo in questi due file).

## Verifica

- `php -l` su entrambi i file: OK, nessun errore di sintassi.
- `vendor/bin/phpstan analyse app/Filament/Resources/ChartResource/Tables/ChartsTable.php app/Filament/Resources/MixedChartResource/Tables/MixedChartsTable.php --no-progress` (da `laravel/`): **0 errori**.
- Nessun comando di scrittura sul DB eseguito (solo `Schema::getColumnListing` in tinker).

## Lock

Lock acquisito/rilasciato per entrambi i file via
`bashscripts/lock/lock.sh` / `unlock.sh` con owner `xotbaseresourcetable-model-audit`
/ batch `batch-chart`, nessuna collisione rilevata.

## Collisioni / dead code

Nessun duplicato o dead code rilevato per questi due file in questo batch.
