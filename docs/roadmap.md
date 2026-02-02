# Chart Module Roadmap

"Vedere l'invisibile: trasformare dati in decisioni."

## 🎯 Visione
Rendere il modulo Chart l'unico punto di verità per la visualizzazione dati in Laraxot, garantendo parità visiva tra Web e PDF e introducendo capacità di analisi predittiva.

## 🏗️ Fasi di Sviluppo

### Fase 1: Cleanup & Filament v5 (In Progress)
- [x] Migrazione a Filament v5 e Chart.js 4.4.
- [ ] Implementazione dei **Chart Clusters** per raggruppare i widget.
- [ ] Refactoring delle actions per ridurre la memoria nel rendering JpGraph.
- [ ] Rimozione definitiva dei 300+ file di documentazione obsoleti.

### Fase 2: Advanced Rendering (Planned)
- [ ] Supporto nativo per grafici 3D e Radar avanzati.
- [ ] Implementazione di un "Live Preview" per la configurazione dei colori nei Tenant.
- [ ] Modulo di cache intelligente per grafici complessi su grandi dataset.

### Fase 3: AI-Driven Insights (Future)
- [ ] Suggerimento automatico del tipo di grafico in base ai dati (AI Analytics).
- [ ] Generazione di descrizioni testuali dei grafici per l'accessibilità (Alt-text AI).
- [ ] Integrazione con modelli predittivi per visualizzare trend futuri.

## ✅ Checklist Qualità
- [x] PHPStan Level 10.
- [ ] Riduzione cyclomatic complexity nelle actions di calcolo dati.
- [ ] 100% test coverage sui DTO di configurazione.

---
**Ultimo aggiornamento**: 31 Gennaio 2026
