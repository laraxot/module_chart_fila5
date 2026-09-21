---
title: "Architecture — Chart"
type: architecture
module: Chart
related:
  - ./livewire-inventory.md
  - ./livewire-widget-prd.md
  - ../../Xot/docs/bmad/livewire-widget-project-context.md
---

# Architecture Chart

Nessun flusso HTTP → widget: zero classi `Http\Livewire`. I 6 sample in `app/Filament/Widgets/Samples/` restano `XotBaseChartWidget`. Unico mount blade: `@livewire('notifications')` (Filament) nel layout `components/layouts/app.blade.php:25`. Verdetto: [livewire-inventory.md](./livewire-inventory.md).
