---
id: story-211-chart-profile-model-to-contract
slug: story-211-chart-profile-model-to-contract
title: "STORY-211-CHART — Sostituisci Profile Model con ProfileContract"
description: "Sostituisci dipendenze su Modules\Quaeris\Models\Profile con ProfileContract in Chart module."
document_type: story
category: bmad
scope: "module:Chart"
status: todo
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: high
epic: 21
epic_title: "Refactoring dipendenze cross-modulo"
created_at: '2026-09-06'
updated_at: '2026-09-06'
tags: [bmad, story, refactor, dependency-inversion, profile-contract]
related:
  - ./21-1-replace-profile-model-with-contract.md
  - ../../../../../memory/profile-contract-over-model.md
github:
  repository: git@github.com:laraxot/module_chart_fila5.git
  issues: https://github.com/laraxot/module_chart_fila5/issues
---

# STORY-211-CHART — Sostituisci Profile Model con ProfileContract

Status: `todo` · Scope: `module:Chart` · Epic: 21

## Accettance Criteria

AC-1: Tutti i `use Modules\Quaeris\Models\Profile` sostituiti con `use Modules\Xot\Contracts\ProfileContract` in Chart.

AC-2: Tutti i type-hint su `Profile` (in docblock e signature) sostituiti con `ProfileContract`.

AC-3: PHPStan Level 10 su Chart ritorna zero errori (`analyse Modules/Chart`).

AC-4: Commit su modulo + push su tutti i remote.

## File toccati

- `Chart.php` — `@property-read \Modules\Quaeris\Models\Profile|null $creator|$deleter|$updater`
- `MixedChart.php` — `use Modules\Quaeris\Models\Profile`
