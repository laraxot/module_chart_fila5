---
title: "Chart — Actions e qualità statica"
description: "Indice canonico delle Action del modulo Chart e della verifica PHPStan."
module: Chart
status: active
updated: 2026-10-08
related:
  - ../00-index.md
  - ../phpstan/README.md
---

# Actions del modulo Chart

Questa è la mappa canonica delle Action applicative. Le pagine storiche nelle
cartelle `analysis/` e `phpstan/` descrivono problemi rilevati in date
precedenti; non sostituiscono la verifica corrente.

## Contratto comune

- La logica applicativa vive in `app/Actions/`, non in `app/Services/`.
- Le Action usano `Spatie\QueueableAction\QueueableAction` e espongono
  `execute()` come entry point principale.
- I DTO (`ChartData` e `AnswersChartData`) attraversano il confine tra dati e
  rendering con tipi espliciti.

## Mappa per responsabilità

| Area | Responsabilità | Riferimento |
| --- | --- | --- |
| Chart | Opzioni e calcolo dei dati di base | [`app/Actions/Chart/`](../../app/Actions/Chart/) |
| ChartJs | Esportazione web PNG/SVG e salvataggio su file | [`app/Actions/ChartJs/`](../../app/Actions/ChartJs/) |
| JpGraph | Stile, costruzione del grafo e grafici legacy | [`app/Actions/JpGraph/`](../../app/Actions/JpGraph/) |
| Widget | Rendering ed esportazione dei widget | [`app/Actions/Widget/`](../../app/Actions/Widget/) |
| Export | Facciate per esportare grafici da dati o widget | [`app/Actions/ExportChartFromWidgetAction.php`](../../app/Actions/ExportChartFromWidgetAction.php) |

La nota specifica sull’azione JpGraph per le sottodomande è
[line-subquestion-action.md](./line-subquestion-action.md).

## Confini di tipo JpGraph

Le Actions ricevono DTO tipizzati e restringono le proprietà dinamiche di JpGraph
prima di chiamare metodi o costruire plot. In particolare:

1. `AnswersChartData` fornisce `ChartData` e `DataCollection<int, AnswerData>`;
2. `LineSubQuestionAction` normalizza le serie numeriche in `float`;
3. `instanceof`, `is_object()` e `method_exists()` proteggono gli accessi che la
   libreria esterna può non esporre;
4. `mixed`, cast gratuiti e ignore PHPStan non sostituiscono una guardia che
   descrive il contratto reale.

Questo mantiene separati il contratto dei dati e il rendering, senza introdurre un
layer Service: le Actions restano invocabili tramite `execute()` e il trait
`Spatie\\QueueableAction\\QueueableAction`.

## Verifica PHPStan

Eseguita dalla root `laravel` il 2026-10-08:

```bash
./vendor/bin/phpstan analyse Modules/Chart --error-format=table
```

Risultato: **OK — No errors**.

Per ripetere la verifica dopo una modifica a un’Action, usare prima il file
interessato e poi l’intero modulo. Non aggiungere `mixed`, baseline o ignore per
nascondere un errore: correggere il tipo alla sorgente o documentare il limite
della libreria esterna.
