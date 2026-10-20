---
title: "[STORY] getFormSchemaOld() final: ChartResource e MixedChartResource bloccano PHPStan e l'avvio"
type: story
status: review
priority: high
created: 2026-10-20
updated: 2026-10-20
module: Chart
tags: [filament, form, xot, final, phpstan, fatal]
related:
  - ../../../Xot/docs/stories/dcp-184-getformschemaold-final-migrazione-e-consumatori-diretti.story.md
  - ../../../Xot/docs/stories/dcp-221-residui-di-dcp-184-getformschema-vuoto-e-consumatori-geo.story.md
  - ../../../Quaeris/docs/stories/getformschemaold-final-quaeris-resources.story.md
---

# [STORY] getFormSchemaOld() final: ChartResource e MixedChartResource

## Richiesta

Dopo il pull dei sottomoduli, `./vendor/bin/phpstan analyse Modules` si ferma con:

```
PHP Fatal error: Cannot override final method
Modules\Xot\Filament\Resources\XotBaseResource::getFormSchemaOld()
in Modules/Chart/app/Filament/Resources/ChartResource.php on line 45
```

## Analisi

### Perche' succede

Il 2026-10-14, nel progetto Fixcity, `XotBaseResource::getFormSchemaOld()` e' diventato `final` e restituisce `[]` (story Xot DCP-184). Fixcity ha migrato i suoi override nello stesso giorno. Questo progetto ha ricevuto il cambio di base solo con il pull: qui gli override sono ancora sei (due in Chart, quattro in Quaeris). DCP-221, decisione D221-2, aveva previsto questo caso: un modulo vive in piu' progetti, e un override rimasto in un altro progetto produce lo stesso fatal.

Il fatal blocca tutto cio' che avvia l'applicazione: PHPStan (Larastan), Pest, `artisan`, le pagine web.

### Lo scopo del metodo

`getFormSchemaOld()` era un ponte di migrazione. `XotBaseResource::form()` e' `final` e usa solo la classe `Resource\Schemas\<Model>Form`: il contenuto dell'Old non arrivava al pannello. Il docblock della base dice cosa fare: spostare il contenuto nella classe `*Form`, poi togliere l'override.

### Confronto campo per campo

| Resource | Old | Form | Decisione |
|---|---|---|---|
| `ChartResource` | `type` (da `GetTypeOptions`), `group_by`, `sort_by`, `width`, `height`, `show_box`, `font_family`, `font_style`, `font_size`, `list_color`, `transparency` | `type` (`bar`, `line`, `pie` scritti a mano), `color`, `bg_color`, `width`, `height`, `transparency` | Spostare in `ChartForm` i 7 campi mancanti e le opzioni vere di `type`; tenere `color` e `bg_color` |
| `MixedChartResource` | `type` | `name` | Cancellare l'Old: la tabella `mixed_charts` non ha la colonna `type` (`MixedChart::$fillable` contiene solo `name`) |

Le 7 colonne spostate esistono nel modello (`Chart::$fillable`).

### Difetti trovati nel contenuto spostato

1. **Tipi non validi nel pannello.** `ChartForm` offriva `bar`, `line`, `pie`. I tipi che il resto del sistema riconosce sono quelli di `chart::chart.options.type` (`pie1`, `horizbar1`, `bar2`, ...) piu' i grafici misti `mixed:<id>`, cioe' l'output di `GetTypeOptions`. Un grafico salvato dal pannello con `bar` non corrisponde a nessun tipo noto.
2. **Opzioni font vuote.** L'helper dell'Old (`getTransStringOptions`) scartava le chiavi non stringa. `font_family` (10-16) e `font_style` (9001-9004) hanno chiavi intere: le due select sarebbero nate senza opzioni. Nel `ChartForm` l'helper accetta chiavi `int|string`.

## Modifiche

- `app/Filament/Resources/ChartResource/Schemas/ChartForm.php`: aggiunti `group_by`, `sort_by`, `show_box`, `font_family`, `font_style`, `font_size`, `list_color`; `type` usa `GetTypeOptions`; helper delle opzioni tradotte con chiavi `int|string`.
- `app/Filament/Resources/ChartResource.php`: tolti `getFormSchemaOld()`, l'helper e gli `use` rimasti senza uso.
- `app/Filament/Resources/MixedChartResource.php`: tolti `getFormSchemaOld()` e gli `use` rimasti senza uso.
- `tests/Unit/Filament/ChartFormTest.php`: nuovo test (rosso prima dello spostamento, verde dopo).

## Verifica

Vedi sezione "Esito" in coda, con l'output reale.

## Decisioni

- `sort_by` usa le opzioni di `group_by`, come nell'Old. I file lang non hanno opzioni `sort_by` e il codice commentato in `QuestionChartResource` ne elenca altre (`_value`, `date:Y-m`): la scelta delle opzioni giuste non e' deducibile dal codice e resta aperta.

## Esito (2026-10-20)

- **Test prima della modifica:** tolti gli override, `ChartFormTest` rosso per il motivo giusto: `Tests: 3 failed` (campo `font_family` assente, opzioni di `type` = `bar`, `line`, `pie`).
- **Test dopo lo spostamento:** `Tests: 3 passed (41 assertions)`.
- **Suite Chart completa:** `Tests: 36 passed (129 assertions)`.
- **Avvio:** `php artisan about` risponde; `rg 'function getFormSchemaOld' Modules Themes` trova solo la base (e i RelationManager di Quaeris, che non sono override).
- **`php -l`:** pulito sui file toccati.
- **PHPMD (ruleset del modulo):** nessuna segnalazione sui tre file di Chart.
- **PHPInsights:** solo "le classi devono essere final o abstract", convenzione del progetto per Resource e Form, non introdotta qui.
- **PHPStan:** lanciato dall'utente su `Modules`. Il fatal e' sparito, l'analisi arriva in fondo; restano errori in altri moduli (marker di conflitto nei test di Geo), fuori da questa story.
- **Effetto collaterale atteso:** il primo run del test ha scritto in `lang/it/chart_form.php` le etichette segnaposto dei 7 campi nuovi e ha riscritto le 6 voci esistenti con una chiave per riga. Nessuna voce tolta. Il file va nel commit insieme al resto.

## Aperto

- Opzioni di `sort_by` (vedi Decisioni).
- Etichette dei campi in `lang/it/chart_form.php`: sono segnaposto (`group_by`, `font_family`, ...), mentre `lang/it/chart.php` ha gia' le etichette italiane degli stessi campi.
- I file `lang/en` e `lang/de` non hanno il blocco `options`: in quelle lingue le select `group_by`, `sort_by`, `font_family`, `font_style` restano vuote. Preesistente, non toccato.
