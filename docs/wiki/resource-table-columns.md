# Colonne delle Resource — verifica 2026-09-10

## Evidenze e decisioni

MixedChart e migrazione 2024_01_01_000002 confermano id, name, timestamps; description non esiste. charts() consente conteggio reale. Chart e migrazione 000001 confermano type, group_by, sort_by, width, height, font_family/style/size; dettagli font opzionali.

## Contratto e verifica

Ogni getTableColumns restituisce array<string, Column>. Le colonne primarie supportano lettura e ricerca; metadati tecnici restano selezionabili. Nessun campo aggiunto senza evidenza nel modello e nello schema/produttore Sushi. QMD search tentato prima delle modifiche: indisponibile per incompatibilità ABI better-sqlite3 (127/147); consultati direttamente sorgenti e documentazione.
