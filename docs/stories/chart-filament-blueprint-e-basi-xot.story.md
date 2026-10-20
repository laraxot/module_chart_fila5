---
title: "Blueprint Filament e basi Xot per il modulo Chart"
type: story
status: ready-for-dev
priority: P2
created: 2026-10-20
updated: 2026-10-20
module: Chart
epic: ../../../Xot/docs/bmad/epic-code-quality-ratchet.md
depends_on: ["verdetto Pest P0 dell'utente (solo per la fase 3)", "guardia DB dentro (solo per la fase 3)", "run completo PHPStan finito (solo per analyse Modules intero)"]
related: [../bmad/livewire-inventory.md, ../../../Xot/docs/bmad/livewire-to-filament-widgets-filament-blueprint.md, ../../../Xot/docs/bmad/livewire-to-filament-widgets-inventory.md, ./getformschemaold-final-chart-resources.story.md, ./chart-module-table-audit.story.md, ./docs-link-triage-chart.story.md, ../../../User/docs/bmad/filament-blueprint.md]
tags: [bmad, filament, blueprint, xotbase, widget, lang]
---

# Blueprint Filament e basi Xot per il modulo Chart

Fase BMAD: Story pronta per lo sviluppo (analisi, nessun codice toccato).

## Richiesta

Come sviluppatore voglio un contratto scritto per la parte Filament di Chart e tutte le sue classi su basi `XotBase*`,
cosi' che il modulo segua la stessa regola degli altri e non mostri dati finti nel pannello.
La parte documentale (670 md in `docs/` alle 13:51 UTC con `/usr/bin/find Modules/Chart/docs -name '*.md' | wc -l`, link rotti) e' coperta da `docs-link-triage-chart.story.md`: qui non si ripete.

## Scopo

Chart offre a Quaeris (36 file lo importano, `rg -l "Modules\\\\Chart\\\\" --glob '*.php' Modules/Quaeris/app`) i modelli `Chart` e `MixedChart` (configurazione di
grafici: tipo, colori, font), le Action di esportazione in PNG/SVG e la colonna di tabella `Tables\Columns\ChartColumn` che disegna i
risultati dei sondaggi. La parte Filament e' il pannello di configurazione dei grafici (due Resource) piu' due pagine dashboard e sei
widget dimostrativi (`Widgets/Samples`). Il pubblico del pannello e' chi configura i report.

## Stato misurato

Misure del 2026-10-20 13:51 UTC, cartella `laravel/`.
- File: `/usr/bin/find Modules/Chart/app/Filament -name '*.php' | wc -l` = 22 (con `-type f`: 24, due `.gitkeep`). Test: `/usr/bin/find Modules/Chart/tests -name '*.php' | wc -l` = 10, di cui 7 con casi (36 casi: `rg -c -P -e '^(it|test)\(|function test|#\[Test\]' Modules/Chart/tests`); mai eseguiti oggi (Pest fermo).
- Blueprint: `/usr/bin/find Modules/Chart/docs -iname 'filament-blueprint*'` = nessun file. Controprova: stesso comando su `Modules/User/docs` = 1 file.
- `extends` diretti di classi Filament: 1. `rg -n -P "^\s*(abstract\s+|final\s+)*class\s+\w+\s+extends\s+(?!XotBase)\w+" --glob '*.php' Modules/Chart/app/Filament` e `git -C Modules/Chart grep -n -P "extends\s+(CreateRecord|ViewRecord|EditRecord|ManageRecords|ListRecords|Action|Page|Widget|Resource|Dashboard|ChartWidget)\b" -- app/Filament` danno la stessa riga: `Pages/DashboardV2.php:10` (`extends Dashboard`, con `use Filament\Pages\Dashboard;` riga 7). Le altre 21 classi estendono gia' `XotBase*` (Resource, Create, Edit, List, Page, ChartWidget, Form, Table, Infolist). `Tables/Columns/ChartColumn.php`, fuori da `app/Filament`, estende `XotBaseColumn`.
- Base da usare: `Modules/Xot/app/Filament/Pages/XotBaseDashboard.php` (20 file la estendono: `rg -l "extends XotBaseDashboard" --glob '*.php' Modules/*/app | wc -l`).
- Stringhe UI nelle Resource: `->label('...')` = 0 file (controprova del pattern: Quaeris 25 file, User 8); altri metodi UI con letterale (`placeholder|helperText|heading|description|tooltip|title`) = 0. Le opzioni dei Select vengono dal lang (`getTransOptions('chart::chart.options.*')`, `ChartForm.php:26-36`); restano solo le dimensioni font `'8' => '8'` ... (numeri, non testo).
- Stringhe UI nei widget dimostrativi: `rg -n -P "\\\$heading\s*=\s*'" Modules/Chart/app/Filament` = 6 righe, tutte `'Blog Posts'` (Bar01, Bar02, Doughnut01, Sample01, Sample02, Sample03); `'label' => 'Blog posts created'` in `Doughnut01Chart.php:21` e `Sample02Chart.php:18`, `'label' => 'label 1 !'` in `Doughnut01Chart.php:59`; i mesi `['January', ..., 'May']` in 6 file. Dati numerici finti (`[50, 60, 70, 180, 190]`). Vista `resources/views/filament/widgets/samples/overlook-v2.blade.php:24,33,40` ("Post views", "SMS", "Email"), che nessun codice richiama (`rg -n "overlook-v2" Modules/Chart --glob '*.php' --glob '*.blade.php'` = 0 righe; resta un commento su `OverlookV2Widget`, classe che non esiste).
- Chi mostra i dimostrativi: `Pages/Dashboard.php:28,31,32` (Bar02, Doughnut01, Sample01) e `Pages/DashboardV2.php:33` (Bar01); Sample02 e Sample03 non sono referenziati (`rg -n -P "Sample0[23]Chart" --glob '*.php' Modules app` = solo le definizioni, confermato da `git grep -w`). Se il pannello scopre le pagine del modulo, un utente vede un grafico "Blog Posts" con numeri inventati. Non verificato in browser.
- Livewire: `/usr/bin/find Modules/Chart -path '*/Http/Livewire/*.php' | wc -l` = 0 (la cartella ha solo `.gitkeep` e `_components.json`); `rg -l -i livewire --glob '*.php' Modules/Chart/app` = `Actions/ExportChartFromWidgetAction.php` (solo commenti); nelle viste restano `@livewire('notifications')` in `components/layouts/app.blade.php:29` e `wire:ignore` in `tables/columns/chart-column.blade.php:53`; le altre `wire:` di quel file (righe 26, 31, 46) sono dentro commenti Blade. Nessun componente da convertire: il piano Livewire-widget e' gia' chiuso in `docs/bmad/livewire-inventory.md` (approvato).
- Test e database: `Modules\Chart\Tests\TestCase` usa `DatabaseTransactions` e `tests/Pest.php` lo lega a `Unit` e `Feature`; 6 file dichiarano `uses(TestCase::class)` (`rg -l "uses\(TestCase::class\)" Modules/Chart/tests | wc -l` = 6: Factory, Model, Chart, Integration, ChartData, ChartForm): aprono una transazione sul DB di default al primo setUp, quindi non sono "senza database".

## Lavoro

Fase 1, blueprint. Scrivere `laravel/Modules/Chart/docs/bmad/filament-blueprint.md` (modello `Modules/User/docs/bmad/filament-blueprint.md`, 46 righe): regole non negoziabili; elenco `ChartResource`, `MixedChartResource` (Create, Edit, List, Form, Infolist, Table), `Pages\Dashboard`, `Pages\DashboardV2`, widget `Samples`, colonna `ChartColumn`; asset Chart.js registrati in `Providers/Filament/AdminPanelProvider.php`; regola "le opzioni vengono dal lang"; policy in `Models/Policies`; divieto di dati finti in produzione.

Fase 2, basi (un agente PHP sul modulo, `php -l` dopo ogni file):
1. `Pages/DashboardV2.php:10`: `extends XotBaseDashboard` e togliere `use Filament\Pages\Dashboard;`. Rischio: la base ridefinisce `getWidgets()` e `getColumns()`; l'override esistente di `getWidgets()` resta valido. Il `$routePath = 'v2'` resta.
2. Widget `Samples`: decisione 1. Se si tengono come esempio, `$heading` e label da `lang/it/` (chiavi `chart::widgets.samples.*`) e mesi con `translatedFormat('F')`; se si tolgono, cancellare i 6 file, la vista `overlook-v2.blade.php` e il `use` in `Dashboard.php`/`DashboardV2.php`.
3. `Pages/Dashboard.php`: dopo la decisione, i `getHeaderWidgets()` ritornano i widget veri o niente; la vista `filament/pages/dashboard.blade.php` e' un guscio vuoto (`<x-filament::page>` senza contenuto).
4. Lang: ogni chiave nuova in `lang/it/` con la forma `fields.<campo>.label`; verificare che `lang/it/chart.php` (esiste, con `chart_form.php`, `charts.php`, `mixed_chart.php`) copra `options.group_by`, `font_family`, `font_style` (gia' usate).

Fase 3, test (dopo guardia e verdetto Pest). `tests/Architecture/FilamentBasesTest.php` senza `TestCase` (per reflection: ogni classe in `Modules\Chart\Filament` ha un antenato `Modules\Xot\Filament\*`), e un test sui widget che non contiene testo inglese letterale. Estendere `Unit/Filament/ChartFormTest.php` solo se si toccano i form.

## Acceptance criteria

- [ ] `/usr/bin/find Modules/Chart/docs/bmad -name filament-blueprint.md | wc -l` = 1.
- [ ] `rg -n -P -e "extends (CreateRecord|ViewRecord|EditRecord|ManageRecords|ListRecords|Action|Dashboard|Page|Widget|ChartWidget)\b" --glob '*.php' Modules/Chart/app` = nessuna riga (oggi 1: `DashboardV2.php:10`).
- [ ] `rg -n -P "^use Filament\\\\Pages\\\\Dashboard;" --glob '*.php' Modules/Chart/app` = nessuna riga (oggi 1).
- [ ] `rg -n -P "\\\$heading\s*=\s*'Blog Posts'" --glob '*.php' Modules/Chart/app` = nessuna riga (oggi 6).
- [ ] `rg -n -P "'(January|February|March)'" --glob '*.php' Modules/Chart/app/Filament` = nessuna riga (oggi 6 file).
- [ ] PHPStan gated su `Modules/Chart/app/Filament` (22 file, in due passate da ~11): exit 0 senza ignore.
- [ ] Fase 3: test architetturale verde senza aprire connessioni al database.

## Verifica

1. Prima: i tre `rg` sopra stampano rispettivamente 1, 1 e 6 righe (controprova). Dopo: nessun output (exit 1).
2. `php -l` su `Pages/DashboardV2.php` e su ogni file toccato, poi il gate PHPStan del blocco Vincoli.
3. Con l'utente in browser, dopo la fase 2: le dashboard del modulo non mostrano piu' "Blog Posts".

## Vincoli

- PHPStan: unica config `laravel/phpstan.neon`, la modifica solo l'utente; mai altri `.neon`, mai `-c`/`--level`, nessun baseline/ignore. Ogni invocazione passa dal gate condiviso: `cd laravel && flock -w 1500 /tmp/claude-1000/-mnt-nas07-var-www--bases-base-quaeris-fila5/aeabb365-8395-48f4-b7d4-9fbb17ad85d3/scratchpad/phpstan-gate.lock nice -n 10 ./vendor/bin/phpstan analyse <path...> --memory-limit=2G --no-progress --error-format=raw` (max ~8 file o un modulo; `analyse Modules` intero solo dopo il "run completo finito" dell'altra sessione).
- Pest e PHPUnit FERMI: il 2026-10-20 `RefreshDatabase` ha fatto `migrate:fresh` su `quaeris_data_test`. Si riprende solo quando la guardia DB e' dentro (story `laravel/Modules/Xot/docs/stories/test-database-guard.story.md`) e l'utente ha confermato che i DB `*_test` sono usa-e-getta. Fino ad allora la verifica e' PHPStan + `php -l` + lettura.
- Concorrenza: altre sessioni scrivono nello stesso repo. Prima di scrivere un file esistente: `bashscripts/lock/check.sh <f> && [ $(( $(date +%s) - $(stat -c %Y <f>) )) -gt 120 ] && bashscripts/lock/lock.sh <f>`; un solo agente PHP per modulo; niente commit, reset, checkout, stash.
- zsh non divide `$VAR` in argomenti: usa array (`A=(x y); cmd $A`) o `xargs -a`. Un pattern `rg` che inizia con `-` richiede `-e`. Un `0` va sempre controprovato (rg -P piu' un secondo metodo).
- Docs: artefatti BMAD solo in `laravel/Modules/<M>/docs/`; nomi senza date; nessun Service (`app/Services` vietata, logica in Spatie QueueableAction con `execute()`); Filament: solo basi `XotBase*`, logica nelle Actions, nessuna stringa UI hardcoded (chiavi in `lang/`).
- Specifici: non toccare `Models/Chart.php` e le Action di esportazione (usate da Quaeris); non toccare la parte docs (altra story); nessun file md spostato.

## Decisioni aperte

1. Widget `Samples` e pagine `Dashboard`/`DashboardV2`: cancellare o tenere come esempi. Raccomandazione: cancellare Sample02 e Sample03 (mai referenziati) subito; per le altre, tenerle solo se la dashboard del modulo serve a qualcuno, altrimenti cancellare anche le pagine. Nessun dato finto in un pannello di lavoro.
2. Nome delle chiavi lang dei widget (se restano): `chart::widgets.samples.*` (proposto) o riuso di chiavi esistenti.

## Come ripartire

1. `cd laravel && sed -n 1,46p Modules/User/docs/bmad/filament-blueprint.md && sed -n 1,36p Modules/Chart/app/Filament/Pages/DashboardV2.php`.
2. Scrivere `Modules/Chart/docs/bmad/filament-blueprint.md` (fase 1); chiedere all'utente la decisione 1.
3. Applicare il passo 1 della fase 2 (`DashboardV2`), `php -l`, gate PHPStan su `Modules/Chart/app/Filament/Pages`, e annotare qui le cifre con data e ora UTC.
