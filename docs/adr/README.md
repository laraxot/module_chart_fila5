---
title: "ADR del modulo Chart"
type: index
tags: [adr, decisioni, chart]
created: 2026-11-24
updated: 2026-11-24
---

# ADR del modulo Chart

Il **perche'** delle scelte di Chart: una decisione per file, con le alternative scartate. Il nome del file e' l'argomento, senza date ne'
numeri (la data sta nella frontmatter).

Leggi qui prima di cambiare il modo in cui il modulo usa i widget Filament per i grafici.

## Metodo

Scrivere un ADR solo per una scelta difficile da invertire, sorprendente o fondata su un trade-off reale; non e' un diario ne' una
spiegazione retroattiva inventata. Le decisioni si verificano contro codice e test correnti. Una modifica sostanziale crea un nuovo ADR e
segna il precedente `superseded` o `deprecated`. Regole: [action-behavioral-contract](../../../Xot/docs/rules/action-behavioral-contract.md)
e [ADR di Xot](../../../Xot/docs/adr/README.md).

## Formato

```markdown
---
title: "ADR - <decisione in una riga>"
type: decision
status: proposed | accepted | superseded | deprecated
created: AAAA-MM-GG
updated: AAAA-MM-GG
---

# ADR: <decisione>

## Contesto
Fatti e vincoli che hanno reso necessaria la scelta (misure, norme, policy, incidenti).

## Decisione
Cosa si fa, in concreto, e dove sta nel codice.

## Alternative scartate
Ogni alternativa seria, con il motivo per cui non e' stata scelta.

## Conseguenze
Pro, contro, debito che resta, cosa misurare. Chi cambia la decisione scrive un nuovo ADR e segna questo `superseded`.

## Verifica
Comandi o test che esistono e falliscono se la decisione viene violata; solo cio' che si puo' ripetere.
```

Un ADR "sostanziale" aggiunge `## Driver della decisione` e `## Conferma`.

## Decisioni

Nessuna al 2026-11-24. Un ADR non e' stato scritto perche' nelle docs del modulo non c'e' una decisione che si possa registrare senza inventarla. `docs/decisioni/widget-frontend-decision.md` (2025-01-28) decide «widget Filament sia nell'admin sia nel frontend», ma le sezioni Vantaggi, Filosofia e Conseguenze dello stesso file argomentano il contrario (widget nell'admin, Volt/Livewire nel frontend), e la struttura che propone (`Admin/Patient/Doctor/Shared`) e' quella di un altro progetto: i widget del modulo sono sei grafici di esempio in `app/Filament/Widgets/Samples/`. `docs/bmad/livewire-widget-decision-log.md` e' un registro di inventario («nessun candidato conversione»), non una scelta con alternative. Da decidere con il proprietario del modulo: quale delle due letture del file e' quella voluta, poi scrivere l'ADR.
