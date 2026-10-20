---
title: "Chart — Indice canonico docs"
status: canonical
module: Chart
updated: 2026-10-20
---

> Indice canonico. Indici storici alternativi: `00-index.md`, `README.md`, `wiki/index.md`, `roadmap/indice.md`, `modules/modules-index.md`, `tasks/tasks-index.md`. Nessun file storico cancellato.

> Convenzione: [Docs Organization — Modules & Themes](../../Xot/docs/conventions/docs-organization.md)

# Chart — Indice docs

Modulo Chart (grafici / dashboard). Documentazione molto estesa: le sezioni di dettaglio restano nelle rispettive sottocartelle.

## Panoramica
- [README.md](./README.md) — presentazione
- [core/progetto.md](./core/progetto.md) — contesto progetto
- [core/presentazione.md](./core/presentazione.md) — presentazione
- [core/filosofia.md](./core/filosofia.md) — filosofia
- [01-progetto/progetto.md](./01-progetto/progetto.md)
- [COVERAGE.md / coverage.md](./coverage.md) — copertura

## Architettura
- [architecture/](./architecture/) — architettura (moduli, namespace, struttura)
- [core/architettura-folio-volt.md](./core/architettura-folio-volt.md) — architettura Folio/Volt
- [tecnico/](./tecnico/) — sezione tecnica (struttura, service provider, temi)
- [modules/](./modules/) — documentazione moduli (Xot, ecc.)
- [moduli/](./moduli/) — stato e struttura moduli
- [standards/](./standards/) — standard (architettura, code review, naming, performance)

## PHPStan
- [phpstan/](./phpstan/) — report per livello (level_1..level_9, `level.md`)
- [analysis/phpstan-summary.md](./analysis/phpstan-summary.md) — sintesi
- [error_analysis/](./error_analysis/) — analisi errori strutturali

## BMAD / Stories
- [bmad/](./bmad/) — dossier BMAD (decision-log, tech-spec, prd, ux)
- [stories/](./stories/) — storie feature (es. `chart-module-table-audit.story.md`)
- [decision-log.md](./decision-log.md) — decisioni

## Testing
- [standards/testing.md](./standards/testing.md) — standard di test
- [roadmap/testing.md](./roadmap/testing.md)
- [implementazione/testing/](./implementazione/testing/)

## API / Guide
- [api/README.md](./api/README.md) — API
- [advanced/api.md](./advanced/api.md) — API avanzata
- [guides/](./guides/) — guide (es. `installation.md`)
- [technical/README.md](./technical/README.md)
- [components/chartjs/](./components/chartjs/) · [components/graph.md](./components/graph.md) — componenti grafici

## Struttura
- `bmad/` — dossier BMAD (10 file: `livewire-widget-*.md`)
- `stories/` — storie feature (6 file)
- `errors/`, `errori/`, `errori_gravi/`, `error_analysis/` — cataloghi errori
- `wiki/` — wiki (`index.md`, `commands/`, `concepts/`, `memories/`, `rules/`, `skills/`)
- `concepts/` — (cfr. `wiki/concepts/`) concetti architetturali
- `roadmap/`, `roadmap_backoffice/`, `roadmap_frontoffice/` — pianificazione
- `regole/`, `rules/`, `standards/` — regole e convenzioni
- `implementazione/`, `refactoring/`, `performance/`, `troubleshooting/` — sezioni operative
- `images/`, `assets/`, `screenshots/` — asset e screenshot

---
*Aggiornato: 2026-10-20 — conforme a `docs-organization.md`; `00-index.md` mantenuto in posto.
