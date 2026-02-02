# Chart Module

[![Laravel 12.x](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)
[![Filament 5.x](https://img.shields.io/badge/Filament-5.x-blue.svg)](https://filamentphp.com/)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-Level%2010-brightgreen.svg)](https://phpstan.org/)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![Chart.js 4.4](https://img.shields.io/badge/Chart.js-4.4-orange.svg)](https://www.chartjs.org/)
[![JpGraph 4.1](https://img.shields.io/badge/JpGraph-4.1-green.svg)](https://jpgraph.net/)

> **Dual-engine chart generation**: Chart.js 4.4 per dashboard interattive in Filament, JpGraph 4.1 per generazione PNG server-side nei PDF. DTO-first architecture con Spatie Data.

---

## Cosa fa

Il modulo Chart fornisce due motori di rendering per grafici:

1. **Chart.js 4.4** (frontend): grafici interattivi nelle dashboard Filament, con plugin datalabels e annotation
2. **JpGraph 4.1** (backend): generazione PNG server-side per embedding nei PDF via Html2Pdf

```php
// Frontend: Chart.js in Filament widget
class SurveyChart extends XotBaseChartWidget
{
    protected function getData(): array
    {
        return [
            'datasets' => [['data' => [7.5, 8.0, 7.3], 'backgroundColor' => '#d60021']],
            'labels' => ['Q1', 'Q2', 'Q3'],
        ];
    }
    protected function getType(): string { return 'bar'; }
}

// Backend: JpGraph per PDF
$chartData = ChartData::from(['type' => 'bar2', 'width' => 800, 'height' => 600]);
$graph = app(Bar2Action::class)->execute($answersChartData);
$graph->Stroke('charts/output.png');
```

---

## Architettura

```
Dati Survey (Limesurvey Module)
    |
    v
DTO Layer (Spatie Data)
    +-- ChartData (tipo, dimensioni, colori, trasparenza)
    +-- AnswersChartData (risposte aggregate + configurazione)
    |
    +----> Chart.js 4.4 (Filament Widget, browser)
    |       +-- Plugin: chartjs-plugin-datalabels
    |       +-- Plugin: chartjs-plugin-annotation
    |
    +----> JpGraph 4.1 (PHP, server-side PNG)
            +-- Bar, Pie, Line, HorizBar, Mixed
            +-- Output: file PNG per Html2Pdf
```

### Asset centralizzati

```php
// I plugin Chart.js sono registrati SOLO nel modulo Chart
// Tutti gli altri moduli li ereditano automaticamente
// Modules/Chart/app/Providers/Filament/AdminPanelProvider.php
FilamentAsset::register([
    Js::make('chart-js-plugins', Vite::asset(
        'resources/js/filament-chart-js-plugins.js', 'assets/chart'
    ))->module(),
]);
```

---

## Modelli (2)

| Modello | Funzione |
|---------|----------|
| **Chart** | Configurazione chart con tipo, dati, stile |
| **MixedChart** | Chart combinata (es. bar + line) |

---

## Azioni

### JpGraph Actions (backend PNG)

| Action | Chart type |
|--------|-----------|
| **Bar1Action** | Barre verticali semplici |
| **Bar2Action** | Barre verticali raggruppate |
| **Bar3Action** | Barre verticali impilate |
| **HorizBar1Action** | Barre orizzontali |
| **Pie1Action** | Torta standard |
| **PieAvgAction** | Torta con medie |
| **LineSubQuestionAction** | Linee per sotto-domande |
| **MixedAction** | Combinazione tipi |

### Export Actions

| Action | Funzione |
|--------|----------|
| **ExportToPngAction** | Esporta chart come PNG |
| **ExportToSvgAction** | Esporta chart come SVG |
| **ExportFromWidgetAction** | Esporta da widget Filament |

---

## Tipi di chart supportati

### Chart.js (frontend interattivo)

| Tipo | Uso |
|------|-----|
| `bar` | Barre verticali |
| `line` | Linee con marker |
| `doughnut` | Ciambella |
| `pie` | Torta |
| `radar` | Radar/spider |
| `polarArea` | Area polare |

### JpGraph (backend PNG per PDF)

| Tipo | Codice | Uso |
|------|--------|-----|
| Bar verticali | `bar1`, `bar2`, `bar3` | Confronti |
| Bar orizzontali | `horizbar1` | Ranking |
| Torta | `pie1`, `pieAvg` | Distribuzioni |
| Linee | `lineSubQuestion` | Trend |
| Combinati | `mixed:X` | Analisi composita |

---

## DTO Pattern (Spatie Data)

```php
use Modules\Chart\Datas\ChartData;
use Modules\Chart\Datas\AnswersChartData;

// Type-safe chart configuration
$chartData = ChartData::from([
    'type' => 'bar2',
    'width' => 800,
    'height' => 600,
    'list_color' => '#d60021,#0066cc,#28a745',
    'transparency' => 0.8,
]);

$answersChartData = AnswersChartData::from([
    'answers' => $aggregatedAnswers,
    'chart' => $chartData,
]);

// Esegui il rendering
$graph = app(Bar2Action::class)->execute($answersChartData);
```

---

## Filament Integration

| Resource | Funzione |
|----------|----------|
| **ChartResource** | CRUD configurazioni chart |
| **MixedChartResource** | CRUD chart combinate |

### Plugin JavaScript

```javascript
// Modules/Chart/resources/js/filament-chart-js-plugins.js
import ChartDataLabels from 'chartjs-plugin-datalabels';

window.filamentChartJsPlugins ??= [];
window.filamentChartJsPlugins.push(ChartDataLabels);
```

---

## Integrazione con altri moduli

```
Chart <── Limesurvey (dati risposte per chart)
Chart <── Quaeris    (48 widget dashboard, PDF con chart)
Chart ──> Xot        (XotBaseChartWidget base class)
Chart ──> UI         (design system per colori/temi)
```

---

## Quick Start

```bash
php artisan module:enable Chart
npm install chart.js chartjs-plugin-datalabels chartjs-plugin-annotation
npm run build
```

---

## Metriche

| Metrica | Valore |
|---------|--------|
| **Modelli** | 2 |
| **JpGraph Actions** | 8+ tipi chart |
| **Export Actions** | 3 (PNG, SVG, Widget) |
| **Resource Filament** | 2 |
| **Tipi chart frontend** | 6 (bar, line, doughnut, pie, radar, polarArea) |
| **Tipi chart backend** | 8+ (bar, horizbar, pie, line, mixed) |
| **PHPStan Level** | 10 |

---

## Documentazione

| Guida | Link |
|-------|------|
| **Indice** | [docs/README.md](docs/README.md) |
| **Professional Charts** | [docs/filament-charts-professional-guide.md](docs/filament-charts-professional-guide.md) |
| **JpGraph Custom Package** | [docs/jpgraph-custom-package-creation.md](docs/jpgraph-custom-package-creation.md) |
| **JpGraph Wrapper Classes** | [docs/jpgraph-wrapper-classes.md](docs/jpgraph-wrapper-classes.md) |
| **Chart Assets Rule** | [docs/chart-assets-centralization-rule.md](docs/chart-assets-centralization-rule.md) |

---

**Module Type**: Chart Generation (Dual Engine)
**Architecture**: Chart.js frontend + JpGraph backend, DTO-first, centralized assets
**Quality**: PHPStan Level 10

*Due motori, un'interfaccia: Chart.js per dashboard interattive, JpGraph per PDF professionali.*
