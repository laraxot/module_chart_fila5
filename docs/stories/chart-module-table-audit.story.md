---
id: story-continuation-chart-module-table-audit
title: "Chart Module — Table Class Audit and $model Property"
type: continuation
scope: module:Chart
github: {issues: "https://github.com/laraxot/module_xot_fila5/issues/115", discussions: "https://github.com/laraxot/module_xot_fila5/discussions/117"}
---

# Chart Module — Table Class Audit

## Stato attuale
- `Modules/Chart` ha 5 file modificati (table resources)
- Git status mostra modifiche non ancora sincronizzate

## Task
1. **Aggiungere `protected static string $model`** a tutte le table class che lo hanno mancante
2. **Audit `getTableColumns()`** — verificare che tutti i campi dichiarati esistano sul modello
3. **Migliorare UI/UX** — aggiungere badge, toggleable, sortable dove appropriato
4. **Verificare `php -l`** su tutti i file toccati
5. **Creare BMAD story specifica** per questo modulo

## Tabelle Chart da auditare
- ChartsTable
- MixedChartsTable

## Modelli attesi
- `Modules\Chart\Models\Chart`
- `Modules\Chart\Models\MixedChart`

## Prossimi passi
- `cd Modules/Chart && git status`
- `php artisan tinker --execute="echo implode('\n', Schema::getColumnListing('charts'));"` per verifica colonne
- Aggiungere `$model` alle table class mancanti