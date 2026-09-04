---
title: "Chart Module Test Coverage"
module: "Chart"
type: concept
tags: [coverage, phpstan, mixed-type]
created: 2026-09-04
updated: 2026-09-04
qmd: "coverage"
---

# Chart Module Test Coverage

## 2026-09-04 — `mixed` type reduction (BMAD: refactor/quality)

**Task**: reduce use of the PHP `mixed` type where a more specific type is actually
knowable, per project convention ("cerchiamo di non usare mixed, quando lo troviamo
cerchiamo di sostituirlo con qualcosa di adeguato").

**Scope**: `grep -rnE '\bmixed\b' Modules/Chart --include="*.php"` found 73
occurrences across 22 files.

**Outcome**: **7 files changed** (10 individual replacements), **15 files left
unchanged** after inspection — each remaining occurrence falls into a documented
exception:

| File | Change |
|---|---|
| `app/Actions/JpGraph/V1/LineSubQuestionAction.php` | `array_map(static fn (mixed $legend): string ...)` over `array_keys($first->value)` narrowed to `int\|string $legend` — `array_keys()` on a checked `array<int\|string, ...>` has a statically known key type, verified against `array_map`'s own generic `callable(T): U` contract. |
| `app/Datas/AnswersChartData.php` | `getChartJsData(): array<string, mixed>` narrowed to `array{datasets: array<int, array<string, mixed>>, labels: array<int, string>}` — mirrors the shape already declared on the sibling `ChartData::getChartJsData()`; `labels` values are always `SafeStringCastAction::cast()` output (`string`), `datasets` genuinely stays heterogeneous per-key (label/data/colors/datalabels). |
| `app/Filament/Widgets/Samples/Doughnut01Chart.php`, `Sample01Chart.php` | `getData(): array<string, mixed>` (override of `XotBaseChartWidget::getData()`, itself `@return array<string, mixed>`) narrowed the same way — a valid covariant narrowing, both bodies return literal `datasets`/`labels` matching the shape. |
| `app/Models/Chart.php` | `@property array<array-key, mixed> $colors` narrowed to `array<int, string>` — column is `json` (migration), every test/factory usage (`ChartFactoryTest`, `ChartIntegrationTest`, `ChartModelTest`) treats it as a plain list of color strings (`['red', 'blue', 'green']`, hex strings). |
| `app/Tables/Columns/ChartColumn.php` | `$chartData` property, `$cachedData` property, `getCachedData()`, `getData()` all narrowed from `array<string, mixed>` to the same `array{datasets: ..., labels: ...}` shape as `AnswersChartData::getChartJsData()`, which is the only producer feeding this property (`applyAnswersChartData()`). |
| `database/factories/MixedChartFactory.php` | `definition(): array<string, mixed>` narrowed to `array{id: int, name: string}` — matches `MixedChart`'s own `@property int $id` / `@property string $name` and its `$fillable = ['id', 'name']`; no other key possible. |

**Left as `mixed` — reviewed, not changed**:

- `app/Actions/Chart/BuildMinoritySliceOffsetAction.php`, `app/Actions/ChartJs/ExportToSvgAction.php` (`normalizeNumericSeries`, `normalizeColorPalette`), `app/Actions/JpGraph/V1/Horizbar1Action.php` (`->map(function (mixed $item)...)`), `app/Actions/JpGraph/V1/LineSubQuestionAction.php` (`->filter`/`->map` on `->pluck('label')`), `app/Datas/AnswersChartData.php` (two remaining `->map(static fn (mixed $label/$item)...)` on `pluck()` results) — **confirmed empirically**: `Illuminate\Support\Collection::map()`/`filter()` are generically typed `callable(TValue, TKey): TMapValue` and, without Larastan (commented out in `phpstan.neon`), base PHPStan cannot resolve `TValue` for values coming from `->pluck()` on an untyped/reflective source, so it stays `mixed`. Verified directly: narrowing `Horizbar1Action.php`'s closure param from `mixed` to `?string` produced a new PHPStan error (`argument.type`: "Type string|null ... needs to be same or wider than parameter type mixed") — reverted immediately. Same reasoning applies to the other Collection-method closures; none were touched.
- `app/Actions/ChartJs/ExportToPngAction.php`, `app/Actions/ExportChartToPngAction.php`, `app/Actions/ExportChartToSvgAction.php` — `array<string, mixed> $chartData` (Chart.js/client-supplied config). Every consumer already defends with `is_array()`/`is_scalar()`/`is_numeric()` checks before use; this is genuinely heterogeneous external JSON-like config, exactly the case flagged in the task brief as legitimate to leave alone.
- `app/Actions/Widget/RenderChartWidgetHtmlAction.php` — `@var array<string, mixed>` on values obtained via `ReflectionMethod::invoke()` against a Filament `ChartWidget`; reflection results are unknowable to static analysis by construction.
- `app/Datas/AnswerData.php` — `EloquentCollection<int, Model>|array<int, mixed> $data` param of `collection()`, a generic ingestion boundary for `Spatie\LaravelData\Data::collect()`; not called anywhere in this module (or the rest of the monorepo) so no concrete call-site shape to narrow against.
- `app/Datas/ChartData.php` — `legend`/`totali`/`options` DTO properties (`array<string, mixed>|null`, `array<int|string, mixed>|null`) — Chart.js legend/options config, genuinely heterogeneous nested JSON, no fixed shape evidenced anywhere in the module.
- `app/Tables/Columns/ChartColumn.php` — `$chartOptions` property stayed `array<string, mixed>` (fed by `getChartJsOptionsArray()`, itself a heterogeneous `plugins`/`responsive`/`scales` bag, not narrowed further — see below).
- `app/Actions/Chart/GetTypeOptions.php`, `lang/en/filament.php`, `tests/Feature/ChartIntegrationTest.php` — "mixed" here is either a variable name (`$mixed`), a domain string ("mixed chart"), or a test string literal, not a type usage.
- `database/factories/MixedChartFactory.php` is now fully clear; other `mixed` mentions in `tests/Support/helpers.php`, `tests/Unit/ChartDataTest.php` are `array<string, mixed> $attributes`/`$overrides` fixtures — feed either `ChartFactory::createOne()`/`makeOne()` (Eloquent attribute arrays, genuinely per-key heterogeneous) or `ChartData::from()` (same). Left unchanged, matching the Eloquent-attribute exception already established for `Chart::$attributes`.

No `@phpstan-ignore` added, no `phpstan.neon` change, no widening of any existing
narrower type back to `mixed`.

**PHPStan**: `./vendor/bin/phpstan analyse Modules/Chart --no-progress
--error-format=table` → **0 errors before, 0 errors after**.

**PHPMD**: `./tools/phpmd.sh Modules/Chart text ../docs/phpmd.ruleset.xml` ran
without crashing. All findings are pre-existing and unrelated to this change
(`CyclomaticComplexity`/`NPathComplexity`/`ExcessiveMethodLength` on the JpGraph V1
actions, `CamelCaseVariableName` on snake_case locals mirroring DB column names,
`UnusedFormalParameter` on several Widget/Policy methods, `ExcessiveParameterList` on
the two Data DTOs). Not touched — out of scope for this task.

**Pest**: **not verifiable**. `Modules/Chart/phpunit.xml` does not exist in this
module (only `tests/Feature`, `tests/Unit`, `tests/Support`, `Pest.php`,
`TestCase.php` are present, no PHPUnit config file), so the mandated
`--no-coverage -c Modules/Chart/phpunit.xml` invocation cannot run. Per the known
pre-existing suite-wide environment issue (see second-brain memory
`env-sqlite-manca-suite-non-eseguibile`), no attempt was made to fabricate an
alternate config.

**Git**: `Modules/Chart` is a nested repo on remote `laraxot` (branch `dev`). Baseline
`git status --short` was clean; module lock (`bashscripts/lock/lock.sh
laravel/Modules/Chart`) was acquired before editing and released after commit/push.
