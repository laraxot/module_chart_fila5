---
title: "Chart — mixed type reduction"
status: done
module: Chart
date: 2026-09-04
updated: 2026-09-21
---

# Story: Chart — `mixed` type reduction

**Fase BMAD**: Refactor / qualità (narrowing di tipi, nessuna modifica funzionale).

**Contesto**: convenzione di progetto — "cerchiamo di non usare mixed, quando lo
troviamo cerchiamo di sostituirlo con qualcosa di adeguato" — applicata a
`Modules/Chart` (22 file con `mixed`, 73 occorrenze totali tra type-hint nativi e
docblock).

**Azione**: censite tutte le 73 occorrenze tramite
`grep -rnE '\bmixed\b' Modules/Chart --include="*.php"`. Ogni occorrenza è stata
letta nel contesto reale (chiamante, generics vendor, consumer a valle) prima di
decidere se sostituirla. Per i casi dubbi su `Illuminate\Support\Collection::map()`
è stata fatta una verifica empirica: narrowing di un parametro di closure da `mixed`
a `?string` in `Horizbar1Action.php` ha prodotto un nuovo errore PHPStan
(`argument.type`, "needs to be same or wider than parameter type mixed") — reverted
subito, e la stessa conclusione è stata applicata (senza modificarli) a tutti gli
altri `->map()`/`->filter()` su risultati di `->pluck()`, dato che senza Larastan
(commentato in `phpstan.neon`) PHPStan non risolve il tipo generico degli elementi.

**Esito**: **10 sostituzioni su 7 file**, le restanti 15 file con `mixed` lasciati
invariati con motivazione documentata (dettaglio completo in `docs/coverage.md`,
sezione "2026-09-04"):

- `LineSubQuestionAction.php`: `array_map` su `array_keys()` narrowed a
  `int|string`.
- `AnswersChartData.php`, `Doughnut01Chart.php`, `Sample01Chart.php`,
  `ChartColumn.php` (4 punti): `array<string, mixed>` → shape esplicita
  `array{datasets: array<int, array<string, mixed>>, labels: array<int, string>}`,
  coerente con la shape già dichiarata su `ChartData::getChartJsData()`.
- `Chart.php` (model): `@property array<array-key, mixed> $colors` →
  `array<int, string>`, confermato da migration (`json`) e da tre test che
  trattano la colonna come lista di stringhe colore.
- `MixedChartFactory.php`: `definition(): array<string, mixed>` →
  `array{id: int, name: string}`, coerente con `$fillable` e i `@property` del
  model `MixedChart`.

Lasciati `mixed` (con motivazione, non per pigrizia): closure `Collection::map()`/
`filter()` su `->pluck()` (limite PHPStan senza Larastan, verificato
empiricamente); `array<string, mixed> $chartData` nelle Export*Action (config
Chart.js client-side, genuinamente eterogenea, già difesa con `is_array`/
`is_scalar`/`is_numeric`); valori da `ReflectionMethod::invoke()` in
`RenderChartWidgetHtmlAction.php`; DTO `legend`/`totali`/`options` in
`ChartData.php` (config Chart.js eterogenea); fixture di test
(`array<string, mixed> $attributes`/`$overrides`, stesso pattern degli attributi
Eloquent). Nessun `@phpstan-ignore` aggiunto, nessuna modifica a `phpstan.neon`,
nessun allargamento di tipi già più stretti.

**Verifica**:
- PHPStan (`./vendor/bin/phpstan analyse Modules/Chart --no-progress
  --error-format=table`): 0 errori prima → 0 errori dopo.
- PHPMD (`./tools/phpmd.sh Modules/Chart text ../docs/phpmd.ruleset.xml`): eseguito
  senza crash; findings pre-esistenti, non correlati a `mixed`, non toccati.
- Pest: non verificabile — `Modules/Chart/phpunit.xml` non esiste.

**Collisioni**: nessuna. Modulo `FREE` per `bashscripts/lock/check.sh` all'avvio;
lock acquisito prima di editare. `docs/chat/chart-submodule-sync-already-pushed.md`
documenta un lavoro precedente diverso (sync submodule, gia' concluso e pushato) —
nessuna sovrapposizione con questo task.

**Dettaglio completo**: vedi `docs/coverage.md`, sezione "2026-09-04 — mixed type
reduction" e "2026-09-21 — strict_types + secondo giro mixed".

## Dev Agent Record (2026-09-21)

**Agent**: php-backend-agent. **Lock**: `FREE` → acquisito `laravel/Modules/Chart`, unlock a fine sessione. User 10.3 e Livewire non toccati. Nome classe `MixedChart` invariato.

### File

- `strict_types`: 99/99 file `.php` del modulo (lang, blade, app, tests, routes). Aggiunto su `lang/{en,de}/filament.php` e 7 blade in `resources/views`. `MixedChart.php` e `routes/api.php`: `declare` spostato prima del docblock.
- mixed ridotti in `app/`: `AnswersChartData.php`, `AnswerData.php`, `ChartData.php`, `Chart.php`, `ChartColumn.php`, `Doughnut01Chart.php`, `BuildMinoritySliceOffsetAction.php`, `RenderChartWidgetHtmlAction.php`, `Horizbar1Action.php`, `LineSubQuestionAction.php`, `ExportToSvgAction.php` (ChartJs), `ExportToPngAction.php` (ChartJs).

### Mixed rimossi (perché il tipo reale era noto)

- Closure `fn (mixed …)` su `Collection::pluck()` → `foreach` su `AnswerData` + `Assert::isInstanceOf` (`AnswersChartData`, `Horizbar1Action`, `LineSubQuestionAction`). Nessun `mixed` nativo rimasto in `app/`.
- `BuildMinoritySliceOffsetAction`: `array<int, mixed>` / `fn (mixed)` → `array<int, int|float|string>` / `int|float|string` (test: int e `'n/a'`).
- `Chart::$attributes`: `array<string, mixed>` → `array<string, string|int|bool>`. `getSettings()`: shape `array{chart: array<array-key, mixed>}`.
- `ChartData::$legend` → `array<int|string, string>|null`; `$totali` → `array<int|string, int|float|string>|null`.
- `datalabelsForSeries` / `getChartJsOptionsArray` / `Doughnut01Chart::getOptions`: shape esplicita, niente mixed.
- `ExportToSvgAction`: `mixed $rawValues`/`$rawColors` → `array` / `array|string|null`; dataset SVG con payload tipizzato; `@var` su width/height sostituiti da `is_int|is_float|is_string`.
- `RenderChartWidgetHtmlAction`: `@var` su `ReflectionMethod::invoke()` sostituiti da `is_array`/`is_string` (niente `@var` per zittire PHPStan).
- `ExportToPngAction::execute` return: shape con chiavi note (`chart_id`, `chart_data`, `export_options`, `timestamp`).
- `normalizeSeries`: `array<mixed>` → `array<int|string, mixed>` (chiavi note; valori da `pluck`/`array_column` ancora eterogenei).
- `AnswerData::collection`: `array<int, mixed>` → `array<int, Model|array<string, mixed>>`.

### Mixed lasciati (ultima spiaggia JSON/config/vendor)

- Inner `array<string, mixed>` dei dataset Chart.js (`getChartJsData` e consumer): bag eterogeneo (stringhe, liste, `RawJs`). PHPStan `missingType.iterableValue` se si usa `array` nudo nell'unione.
- `array<string, mixed> $chartData` nelle Export*Action: JSON client Chart.js, già difeso a runtime.
- `ChartData::$options`, `toArray()` in `getSettings`, reflection in `RenderChartWidgetHtmlAction`, `normalizeNumericSeries`/`normalizeColorPalette`/`normalizeLabels` su payload JSON: valori nested non sigillabili senza mentire.
- `GetTypeOptions`: `$mixed` è dominio (grafico misto), non un type-hint.
- `PieAvgAction.php:83`: commento storico, non un tipo.
- Test fixtures `array<string, mixed>`: non in `app/`, invariati.

Niente `--level`, niente `@phpstan-ignore`, niente `@var` per forzare i tipi.

### Gate

- `php -l`: ok su tutti i PHP toccati (app, lang, routes, blade).
- PHPStan (`vendor/bin/phpstan analyse FILE --memory-limit=2G`, neon di progetto): 0 errori sui 16 file `app/` toccati.
- PHPMD (`bash tools/phpmd.sh FILE text ../docs/phpmd.xml`): findings pre-esistenti (CC DTO/JpGraph stub SVG, camelCase colonne DB). Non toccati.
- PHPInsights: skip (`laravel/vendor/bin/phpinsights` assente).
- Pest `Modules/Chart/tests`: 5 passed (`BuildMinoritySliceOffsetActionTest`, unico test isolato dalla nostra firma). 28 failed pre-esistenti: `HasTeamsContract` in User — fuori scope (User 10.3 non toccato). `phpunit.xml` del modulo assente.
