---
title: "Riferimenti Chart.js raccolti"
slug: riferimenti-chartjs
module: Chart
type: reference
status: active
language: it-IT
created: 2026-11-18
updated: 2026-11-18
tags: [chartjs, donut, plugin, etichette]
description: "Link raccolti durante il lavoro sui grafici: testo al centro del donut ed etichette sopra le barre."
document_type: reference
category: documentation
---

# Riferimenti Chart.js raccolti

Questi link stavano in due file `links.txt` e `plugins.txt` dentro
`app/Filament/Widgets/Samples/`, cioe' in mezzo al codice, dove nessuno che
cerchi documentazione guarda. Sono note di ricerca vere, non scarti: riguardano
i due problemi di resa dei grafici su cui il progetto e' tornato piu' volte.

## Testo al centro di un grafico a ciambella

Il numero grande nel buco del donut non e' una funzione di Chart.js: si ottiene
con un plugin che disegna sul canvas dopo il grafico.

- https://jsfiddle.net/simonbrunel/mo5y35yg/
- https://quickchart.io/documentation/chart-js/custom-pie-doughnut-chart-labels/
- https://stackoverflow.com/questions/60333278/how-to-add-text-at-center-of-the-chart-js-donut-chart
- https://jsfiddle.net/kdvuxbtj/
- https://www.geeksforgeeks.org/how-to-add-text-inside-the-doughnut-chart-using-chart-js/
- https://jsfiddle.net/beaver71/rLcecb33/
- https://codepen.io/stockinail/pen/eYjRgXV

**Vincolo del progetto da non perdere di vista.** Un plugin del genere va
registrato **inline**, non come file caricato a parte: lo snapshot PDF cattura
il canvas, e un plugin che arriva dopo il render non compare nel PDF. E'
l'osservazione che tiene aperta
[module_quaeris_fila5#35](https://github.com/laraxot/module_quaeris_fila5/issues/35).

## Etichette sopra le barre

- https://stackoverflow.com/questions/68406981/chart-js-2-bars-with-one-customize-label-on-top
