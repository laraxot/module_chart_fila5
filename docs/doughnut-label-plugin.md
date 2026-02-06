# Plugin doughnutLabel (Chart.js)

## Scopo

Plugin custom per Chart.js che disegna una **label al centro** del grafico doughnut/pie (nell’area del “buco”). Registrato nel modulo Chart e condiviso da tutti i widget Filament che usano `window.filamentChartJsPlugins`.

## Dove si trova

- **Implementazione**: `Modules/Chart/resources/js/filament-chart-js-plugins.js` (oggetto `doughnutLabel`, push in `window.filamentChartJsPlugins`).
- **Uso tipico**: widget Quaeris `QuestionChartAnswersChartWidget` per grafici radial (doughnut/pie).

## Comportamento

- **Hook**: `beforeDatasetsDraw`.
- **Testo** (in ordine di priorità):
  1. `chart.data.datasets[0].centerLabel` (stringa) — preferito, coerente con i dati.
  2. `chart.config.options.plugins.doughnutLabel.label` — fallback da opzioni.
- **Multilinea**: il testo viene spezzato su `\n` e ogni riga è disegnata con `fillText` e `lineHeight` fisso (36px), centrata verticalmente sul centro del grafico.
- **Stile**: font `bold 30px sans-serif`, colore nero, `textAlign: 'center'`, `textBaseline: 'middle'`. Se non c’è testo valido, il plugin non disegna nulla.

## Utilizzo da widget PHP

1. **Dati**: in `getData()`, per il primo dataset e solo se il tipo è doughnut/pie, impostare `centerLabel` (stringa, es. `"Totale\n1.234\nrisposte"`).
2. **Opzioni**: in `getOptions()`, per grafici radial aggiungere `plugins.doughnutLabel = []` (o `['label' => '...']` se si vuole solo opzione statica).

Esempio: vedi `QuestionChartAnswersChartWidget::getDoughnutCenterLabel()` e la sezione “Label al centro del doughnut” in [Quaeris – question-chart-answers-chart-widget](../../Quaeris/docs/question-chart-answers-chart-widget.md).

## Riferimenti

- [chartjs-plugin-annotation – doughnutLabel](https://www.chartjs.org/chartjs-plugin-annotation/3.1.0/guide/types/doughnutLabel.html) (standard alternativo).
- [Quaeris – chartjs-doughnut-center-label-guide](../../Quaeris/docs/chartjs-doughnut-center-label-guide.md).
