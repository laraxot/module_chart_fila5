# Anti-Pattern: Il "God Widget" (es. TripleChartWidget)

> ⚠️ **MERDA ALLERT**: La creazione di widget "contenitori" come `QuestionChartAnswersTripleChartWidget` va contro ogni buon senso ed è la peggiore merda tecnica che si possa concepire in questo progetto.

### Perché questa architettura è "Merda"?

1. **Violazione Totale del Single Responsibility Principle (SRP):** Un widget deve gestire UN grafico. Se ne gestisce tre, stai creando un mostro che fa troppe cose. Se si rompe una query, muore tutto il widget. Se vuoi cambiare il colore di un grafico, devi navigare in un file triplo.
2. **Hardcoding del Numero:** Chiamare una classe "Triple" è una condanna a morte. Cosa succede se ne servono due? O quattro? Crei `DoubleChartWidget` e `QuadrupleChartWidget`? È demenziale.
3. **Morte del DRY (Don't Repeat Yourself):** La blade di questi widget è solitamente un copia-incolla dello stesso blocco Chart.js ripetuto N volte. Se cambia una virgola nel componente Filament, devi aggiornare N punti. Follia pura.
4. **Layout Spazzatura:** Questi widget forzano layout grid o flex con stili inline e px hardcoded, ignorando completamente il sistema responsive di Filament e Tailwind CSS v4. Risultato: su mobile è tutto rotto.
5. **Performance da Incubo:** Carichi e processi dati per N grafici contemporaneamente, appesantendo memoria e database, anche quando l'utente non ne ha bisogno.

### La Regola Laraxot: Modularità Atomica

In questo progetto **non si accorpano widget**. Il layout è responsabilità del framework, non del singolo componente.

- **SÌ**: Creare 3 widget singoli (es. `MainChartWidget`, `SideChart1Widget`, `SideChart2Widget`).
- **SÌ**: Usare `getFooterWidgets()` o `getHeaderWidgets()` nella Page di Filament per disporli.
- **SÌ**: Usare `getFooterWidgetsColumns()` per definire la griglia responsive.
- **SÌ**: Spostare tutta la logica di calcolo in **Spatie Actions**.

**Se vedi un widget con "Triple" nel nome, cancellalo e rifallo da capo. È merda.**
