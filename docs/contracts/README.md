---
title: "Contratti del modulo Chart"
type: index
tags: [contracts, actions, chart]
created: 2026-11-24
updated: 2026-11-24
---

# Contratti del modulo Chart

Un contratto descrive una singola operazione pubblica del modulo (un'Action che scrive dati, chiama servizi esterni o ha
effetti collaterali, oppure un'interfaccia di `app/Contracts`): cosa promette, cosa pretende, dove si ferma. Documenta il
comportamento osservabile, inclusi errori e non-goals, senza trasformare ogni metodo interno in un contratto.

Formato e regola: `Modules/Xot/docs/rules/action-behavioral-contract.md`. Un file per Action, nome in kebab-case della
classe (`create-ticket-action.md`), frontmatter `type: contract` e `action: <FQCN>`, e queste sei sezioni nell'ordine:

```markdown
## Input
## Output
## Invarianti
## Effetti collaterali
## Errori
## Non fa
```

Ogni invariante ha un ID stabile `CHART-<OPERAZIONE>-INV-<N>`; il test Pest corrispondente lo usa come
group. Il docblock dell'Action rimanda al file con il marker `@contract docs/contracts/<file>.md`; contratto, codice e test
cambiano nello stesso commit. Ogni contratto aggiunto va elencato qui sotto con una riga che ne linka il file.

## Contratti

Nessun contratto documentato al momento. Il modulo ha 23 Action in `app/Actions/JpGraph` (tra cui `GetGraphAction`,
`ApplyPlotStyleAction` e le varianti di grafico in `V1/`), nessuna con il contratto: il candidato naturale e'
`ApplyPlotStyleAction`, perche' oltre a Chart la usa anche Quaeris (`QuestionChartChartData`); `GetGraphAction` e' usata
solo dentro il modulo.
