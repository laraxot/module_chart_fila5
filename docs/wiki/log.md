---
title: "Chart Wiki Activity Log"
module: "Chart"
---

# Chart - Wiki Activity Log

## [2026-10-20] Il form del grafico vive solo in ChartForm

- Dopo il pull, `XotBaseResource::getFormSchemaOld()` e' `final` (Xot DCP-184): l'override in `ChartResource` e `MixedChartResource` bloccava PHPStan, Pest e l'avvio con `Cannot override final method`.
- `ChartForm` ha ora tutti i campi che il pannello deve modificare: aggiunti `group_by`, `sort_by`, `show_box`, `font_family`, `font_style`, `font_size`, `list_color`. `type` usa `GetTypeOptions` (tipi veri come `pie1`, `bar2`, piu' i grafici misti): prima offriva `bar`, `line`, `pie`, che nessuna parte del sistema riconosce.
- Trappola evitata: le opzioni di `font_family` e `font_style` hanno chiavi intere (sono i valori salvati). Un helper che tiene solo chiavi stringa lascia le select vuote.
- `MixedChartResource`: l'Old proponeva un campo `type` che `mixed_charts` non ha. Cancellato senza spostarlo.
- Il primo run del test ha aggiunto a `lang/it/chart_form.php` le etichette segnaposto dei 7 campi nuovi (meccanismo delle etichette): file da tenere nel commit.
- Test: `tests/Unit/Filament/ChartFormTest.php`. Story: [getformschemaold-final-chart-resources](../stories/getformschemaold-final-chart-resources.story.md).

## [2026-05-11] Wiki Structure Created

- Created wiki structure: rules/, skills/, commands/, memories/, concepts/
- Created INDEX.md for each section
- Created module index.md
- Ready for on-demand loading via QMD

