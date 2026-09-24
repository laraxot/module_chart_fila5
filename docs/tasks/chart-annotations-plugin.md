# Task: Chart Annotations Plugin

**Modulo**: Chart  
**Priorità**: Alta  
**Gap**: Linee media/mediana, threshold, target lines sui grafici

## Obiettivo

Implementare chartjs-plugin-annotation per linee di riferimento (media, mediana, soglie) sui widget Chart.js. Registrare plugin nel modulo Chart (centralizzazione asset).

## Sottotask

- [ ] Aggiungere chartjs-plugin-annotation a package Chart
- [ ] Registrare plugin in AdminPanelProvider del modulo Chart
- [ ] Configurazione standard (averageLine, threshold) in getOptions()
- [ ] Documentare in filament-charts-professional-guide
- [ ] Test widget con annotazioni

## Dipendenze

Nessuna. Asset Chart devono essere registrati solo in [Chart](../chart-assets-centralization-rule.md).

## Collegamenti

- [Roadmap Chart](../roadmap.md)
- [Indice task Chart](tasks-index.md)
- [Chart assets centralization](../chart-assets-centralization-rule.md)
