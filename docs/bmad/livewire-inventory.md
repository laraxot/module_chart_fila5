---
title: "Inventario Http/Livewire → Filament widget — Chart"
type: inventory
module: Chart
status: approved
track: campaign
related:
  - ./livewire-widget-project-context.md
  - ./livewire-widget-decision-log.md
  - ./livewire-widget-epics.md
  - ./livewire-widget-prd.md
  - ../../Xot/docs/bmad/livewire-widget-project-context.md
  - ../../Cms/docs/bmad/livewire-inventory.md
---

# Inventario: Livewire HTTP → Filament — modulo Chart

**Solo documentazione. Nessun PHP toccato in questo audit.**

Questo file è la SSoT del modulo Chart per la campagna di conversione Livewire → Filament widget. Formato e metodo sono ripresi da [Modules/Cms/docs/bmad/livewire-inventory.md](../../Cms/docs/bmad/livewire-inventory.md), con la differenza che qui non esiste nemmeno una classe da classificare: il risultato verificato è **zero componenti Livewire**. Il modulo ha invece già sei Filament widget (i sample chart), elencati sotto.

## Metodo (codice, non assunzione)

```bash
find Modules/Chart -path '*/vendor/*' -prune -o -name '*.php' -print | xargs grep -l 'extends.*\(Component\|Livewire\)'
find Modules/Chart -iname '*livewire*' -not -path '*/vendor/*'
ls Modules/Chart/app/Http/Livewire/ Modules/Chart/app/Livewire/
grep -rn "@livewire" Modules/Chart --include="*.blade.php"
grep -rn "<livewire:" Modules/Chart --include="*.blade.php"
find Modules/Chart/app/Filament -iname '*widget*'
ls Modules/Chart/resources/views/pages
```

## Classi Livewire trovate: zero

Il primo comando (`extends Component|Livewire` su tutti i `.php` del modulo, vendor escluso) non restituisce alcun file. In dettaglio:

| Posizione | Esito |
|---|---|
| `Modules/Chart/app/Http/Livewire/` | La directory esiste ma contiene solo `_components.json` (contenuto: `[]`, cioè zero alias registrati) e `.gitkeep` — nessun `.php` |
| `Modules/Chart/app/Livewire/` | Non esiste |
| `find -iname '*livewire*'` | Unici hit: documentazione in `docs/` (questa campagna, più `docs/backend/framework/livewire.md`, `docs/rules/filament-livewire-properties.md`, `docs/errori_gravi/livewire-multiple-root-elements.md`) e la directory vuota `app/Http/Livewire`. Nessuna classe, nessuna vista `livewire/` |

Conclusione verificata: il modulo **non possiede** alcun componente Livewire, né classico (`Http/Livewire`) né nel layout Livewire 3/4 (`app/Livewire`).

## Verifica del montaggio: un solo hit, componente di un altro package

Verificato su tutti i 7 file `.blade.php` di `Modules/Chart/resources/views/`:

| Meccanismo | Comando | Esito |
|---|---|---|
| `@livewire(...)` | `grep -rn "@livewire" Modules/Chart --include="*.blade.php"` | **1 hit**: `Modules/Chart/resources/views/components/layouts/app.blade.php:25` → `@livewire('notifications')`, con commento "Only required if you wish to send flash notifications". Monta l'alias `notifications`, cioè il componente `Filament\Notifications\Livewire\Notifications` del package `filament/notifications` — **non** un componente del modulo Chart. Il file è un layout HTML standalone (`@filamentStyles`, `@vite`, `{{ $slot }}`), probabilmente per preview/demo dei sample chart |
| `<livewire:... />` | `grep -rn "<livewire:" Modules/Chart --include="*.blade.php"` | **0 hit** |
| Pagina Folio/Volt | `ls Modules/Chart/resources/views/pages` | La directory `pages/` non esiste: Chart non contribuisce rotte Folio/Volt |

Conclusione verificata: Chart non monta nessun componente proprio; l'unico `@livewire` presente instanzia il componente notifiche di Filament dentro un layout di supporto. Non è un candidato di conversione.

## Widget Filament esistenti nel modulo Chart: sei, tutti sample

`find Modules/Chart/app/Filament -iname '*widget*'` restituisce la directory `app/Filament/Widgets/` con la sottocartella `Samples/` contenente sei classi PHP, tutte `extends Modules\Xot\Filament\Widgets\XotBaseChartWidget`:

| Widget | File | Righe |
|---|---|---|
| `Filament\Widgets\Samples\Sample01Chart` | `app/Filament/Widgets/Samples/Sample01Chart.php` | 163 |
| `Filament\Widgets\Samples\Sample02Chart` | `app/Filament/Widgets/Samples/Sample02Chart.php` | 51 |
| `Filament\Widgets\Samples\Sample03Chart` | `app/Filament/Widgets/Samples/Sample03Chart.php` | 145 |
| `Filament\Widgets\Samples\Bar01Chart` | `app/Filament/Widgets/Samples/Bar01Chart.php` | 69 |
| `Filament\Widgets\Samples\Bar02Chart` | `app/Filament/Widgets/Samples/Bar02Chart.php` | 94 |
| `Filament\Widgets\Samples\Doughnut01Chart` | `app/Filament/Widgets/Samples/Doughnut01Chart.php` | 131 |

Sono campioni dimostrativi (dati hardcoded tipo `[50, 60, 70, 180, 190]` con label `January…May`), accompagnati da `Samples/links.txt` e `Samples/plugins.txt` (riferimenti a documentazione Chart.js/plugins). Già nella forma canonica `XotBase*Widget`: non derivano da una conversione HTTP e non ne richiedono una.

## Classificazione

| Classe | Alias/hook | Gemello widget | Cluster | Nota |
|---|---|---|---|---|
| — | — | — | — | Tabella vuota: zero classi Livewire da classificare |

**Cluster A: zero candidati.** Non esistono componenti Livewire in Chart, quindi nessuno può essere montato nel chrome di un panel Filament. L'unico mount trovato (`notifications`) è del package Filament, non del modulo.

**Cluster B: zero candidati.** I sei widget in `Filament/Widgets/Samples/` non hanno alcun "gemello" Livewire HTTP da ritirare: sono nati direttamente come widget.

**Cluster C: zero candidati.** Non c'è alcuna pagina/componente instradato da escludere.

## Verdetto

**Nessun candidato, nessuna story di implementazione.** Chart è un modulo "a valle": fornisce widget già nella forma target (`XotBaseChartWidget`), non componenti HTTP da migrare. Questo file resta come gate anti-scope-creep: chiunque proponga una conversione in Chart deve prima creare o individuare il componente Livewire reale e rieseguire questo inventario.

## Riferimenti correlati (non SSoT, coerenti col verdetto)

- [livewire-widget-architecture.md](./livewire-widget-architecture.md)
- [livewire-widget-brainstorming.md](./livewire-widget-brainstorming.md)
- [livewire-widget-decision-log.md](./livewire-widget-decision-log.md)
- [livewire-widget-epics.md](./livewire-widget-epics.md)
- [livewire-widget-prd.md](./livewire-widget-prd.md)
- [livewire-widget-product-brief.md](./livewire-widget-product-brief.md)
- [livewire-widget-project-context.md](./livewire-widget-project-context.md)
- [livewire-widget-tech-spec.md](./livewire-widget-tech-spec.md)
- [livewire-widget-ux.md](./livewire-widget-ux.md)

## Successo

- [x] Inventario completo del modulo (0 classi trovate, `extends Component|Livewire` su tutti i `.php`)
- [x] Verifica montaggio nelle viste del modulo (1 mount documentato: `notifications` di Filament, file:riga)
- [x] Verifica widget esistenti (6 sample `XotBaseChartWidget` elencati)
- [x] Nessun widget nuovo proposto senza prima verificare l'esistenza di un componente
- [x] Nessuna story di conversione creata (zero candidati reali)
