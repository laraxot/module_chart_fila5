# Task: Chart Docs Consolidation & Cleanup

## 📋 Obiettivo
Sfoltire l'enorme documentazione del modulo Chart (360+ file), eliminando duplicati, backup e file temporanei, portando il totale a circa 20 documenti core.

## 🚨 Problemi Identificati
- 367 file in root `docs/`.
- Molteplicità di file `jpgraph-*.md`.
- File di log e report obsoleti.
- Redondanza tra README e indici.

## ✅ Checklist
- [ ] Archiviare file non-core in `archive/`.
- [ ] Consolidare le varie guide JpGraph in un unico documento `jpgraph-integration.md`.
- [ ] Rimuovere file `.ds_store`, `.env.example` e report di coverage da `docs/`.
- [ ] Eliminare i file di "Analisi Completa" obsoleti dopo aver estratto le info utili.
- [ ] Aggiornare `00-index.md` con la nuova struttura snella.

## 🔗 Riferimenti
- [Roadmap Chart](../roadmap.md)
