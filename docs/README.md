# 📈 **Chart Module** - Visualizzazione & Data Intelligence

[![Laravel 12.x](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)
[![PHPStan level 10](https://img.shields.io/badge/PHPStan-Level%2010-brightgreen.svg)](https://phpstan.org/)
[![Filament 5.x](https://img.shields.io/badge/Filament-5.x-blue.svg)](https://filamentphp.com/plugins/filament-charts)

> **🚀 Modulo Chart**: Il motore di visualizzazione analitica dell'ecosistema Laraxot. Supporta un'architettura duale per garantire interattività sul web e fedeltà nei documenti PDF.

## 📋 **Panoramica**

Il modulo **Chart** centralizza la logica di generazione grafici, separando la presentazione dalla business logic dei dati.

- 🌐 **Frontend interattivo**: Basato su **Chart.js 4.4.x**, integrato nativamente nelle dashboard Filament v5.
- 📄 **Backend generativo**: Basato su **JpGraph 4.1**, ottimizzato per la generazione di immagini PNG da includere nei PDF (embedding).
- 🧬 **DTO-First Architecture**: Uso di `ChartData` e `AnswersChartData` per trasportare le configurazioni in modo type-safe.
- 🎨 **Dynamic Coloring**: Sistema di palette colori centralizzato con supporto per Dark Mode e Branding dei Tenant.

## ⚡ **Engine di Rendering**

### 💻 **Chart.js (Web)**
Utilizzato per widget interattivi, drill-down e dashboard.
- Supporto per Plugin Annotations (media, target lines).
- Esportazione client-side (PNG/SVG).

### 🖼️ **JpGraph (PDF)**
Utilizzato per la generazione server-side di grafici statici ad alta fedeltà.
- Rendering ottimizzato per l'integrazione con il modulo Ptv/Reporting.
- Parità funzionale con i tipi di grafico web.

## 🚀 **Quick Start**

### 📦 **Uso delle Actions**
```php
use Modules\Chart\Actions\GetChartDataAction;

$chartData = app(GetChartDataAction::class)->execute($model, 'bar');
```

### ⚙️ **Integrazione Filament Widget**
```php
class MyChartWidget extends XotBaseChartWidget {
    protected static string $type = 'bar';
    
    protected function getData(): array {
        return app(GetChartDataAction::class)->execute($this->record, 'bar')->toArray();
    }
}
```

## 📚 **Documentazione Centrale**

- 📖 **[Indice Documentazione](./00-index.md)** - Mappa per navigare tra i 360+ documenti originali.
- 🗺️ **[Roadmap 2026](./roadmap.md)** - Piani per AI-Driven reports e nuovi tipi di grafici.
- 🎨 **[Color Palette System](./color-palette.md)** - Guida alla personalizzazione grafica.
- 📄 **[JpGraph Integration](./jpgraph-complete-guide.md)** - Dettagli tecnici per la generazione PDF.

---

**🔄 Ultimo aggiornamento**: 31 Gennaio 2026
**📦 Versione**: 1.2.0
**✅ PHPStan level 10**: Compliance verificata
