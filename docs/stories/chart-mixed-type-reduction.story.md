---
title: "Chart — mixed type reduction"
status: done
module: Chart
date: 2026-09-04
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
reduction".
