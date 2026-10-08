---
title: "LineSubQuestionAction"
description: "Tipizzazione e responsabilità dell’Action per grafici lineari multi-serie."
module: Chart
tags: [chart, action, jpgraph, phpstan]
status: active
updated: 2026-10-08
---

# LineSubQuestionAction

## scenario
- l’azione `LineSubQuestionAction` bloccava PHPStan livello 10 perché accedeva a proprietà/method di `mixed`
- l’assenza di tipizzazione fra `AnswersChartData` → `ChartData` impediva anche a PHP Insights/PHPMD di valutare correttamente la complessità

## intervento
- conversione esplicita di `AnswersChartData->chart` in `ChartData` e di `answers` in `Collection<int, AnswerData>`
- normalizzazione dei dataset tramite helper dedicati (`buildDataSeries`, `extractNumericValue`) per eliminare `mixed`
- wrapping dell’accesso alle proprietà JpGraph tramite `isset()`/`instanceof` per rispettare la regola “no property_exists con magic attributes”
- parametrizzazione dei marker/colori in costanti e applicazione di helper (`configureAxes`, `configureLegend`, `configureTitles`, `clearFooter`) per ridurre la complessità ciclomatica
- validazione continua con `phpstan analyse Modules/Chart/app/Actions/JpGraph/V1/LineSubQuestionAction.php --level=10` e `phpinsights analyse ...`

## impatti
- l’azione ora estende i pattern documentati nella [nota canonica Actions e qualità statica](./README.md)
- i grafici di sottodomanda non rompono più l’intera esecuzione di PHPStan del modulo Chart
- pattern riusabile per le altre azioni JpGraph (vedi [Actions e qualità statica](./README.md))

## prossimi passi
- replicare gli helper di tipizzazione anche sulle altre azioni JpGraph legacy (bar/pie) prima di rieseguire `phpstan analyse Modules/Chart --level=10`
- spostare la generazione dei marker in un Value Object condiviso per ridurre ulteriormente la complessità segnalata da PHP Insights

## backlinks
- [Integrazione JpGraph](../tasks/jpgraph-integration.md)
- [Actions e qualità statica](./README.md)
