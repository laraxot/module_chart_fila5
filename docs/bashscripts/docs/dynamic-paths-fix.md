# Correzione Calcolo Percorsi Dinamici - Bash Scripts

## Problema Identificato

Lo script `fix_git_conflicts_current_change_v2.sh` aveva una logica di calcolo percorsi che non era completamente riutilizzabile tra progetti diversi.

### Problema Specifico

**Entrambe le soluzioni nel conflitto Git erano sbagliate**:
1. **Soluzione HEAD**: Assumava che lo script fosse sempre in `bashscripts/conflicts/` (2 livelli sotto la root)
2. **Soluzione Incoming**: Usava percorsi hardcoded come `./laravel`

**Perché entrambe sono sbagliate**:
- La cartella `bashscripts` viene riutilizzata tra molti progetti diversi
- I percorsi devono essere calcolati dinamicamente, non hardcoded
- Lo script può essere in diverse sottocartelle di `bashscripts/` (fix/, git/, conflicts/, etc.)

## Soluzione Implementata

### Logica di Calcolo Percorsi Corretta

```bash
# 1. Calcola SCRIPT_DIR (directory dove risiede lo script)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# 2. Calcola BASHSCRIPTS_DIR e PROJECT_ROOT
# Strategia: sale fino a trovare la cartella bashscripts o la root del progetto
# Lo script può essere in: bashscripts/fix/, bashscripts/git/, bashscripts/conflicts/, etc.
# Oppure in: bashscripts_temp/fix/ (cartella temporanea)

CURRENT_DIR="$SCRIPT_DIR"
BASHSCRIPTS_DIR=""
PROJECT_ROOT=""

# Prima cerca la cartella bashscripts (preferita)
while [[ "$CURRENT_DIR" != "/" ]]; do
    if [[ "$(basename "$CURRENT_DIR")" == "bashscripts" ]]; then
        BASHSCRIPTS_DIR="$CURRENT_DIR"
        PROJECT_ROOT="$(cd "$BASHSCRIPTS_DIR/.." && pwd)"
        break
    fi
    CURRENT_DIR="$(dirname "$CURRENT_DIR")"
done

# Se bashscripts non trovata, cerca la root del progetto (contiene laravel o .git)
if [[ -z "$BASHSCRIPTS_DIR" ]]; then
    CURRENT_DIR="$SCRIPT_DIR"
    while [[ "$CURRENT_DIR" != "/" ]]; do
        if [[ -d "$CURRENT_DIR/laravel" ]] || [[ -d "$CURRENT_DIR/.git" ]]; then
            PROJECT_ROOT="$CURRENT_DIR"
            # Crea BASHSCRIPTS_DIR nella root (potrebbe non esistere ancora)
            BASHSCRIPTS_DIR="${PROJECT_ROOT}/bashscripts"
            break
        fi
        CURRENT_DIR="$(dirname "$CURRENT_DIR")"
    done
fi

# 3. Calcola BASE_DIR (directory Laravel)
if [[ -d "$PROJECT_ROOT/laravel" ]]; then
    BASE_DIR="$PROJECT_ROOT/laravel"
else
    BASE_DIR="$PROJECT_ROOT"
fi

# 4. Log e backup in bashscripts (centralizzati)
# Usa log/ e backup/ (singolare) per coerenza con struttura esistente
LOG_FILE="${BASHSCRIPTS_DIR}/log/fix_conflicts_current_change_v2_${TIMESTAMP}.log"
BACKUP_DIR="${BASHSCRIPTS_DIR}/backup/conflicts_current_change_v2_${TIMESTAMP}"
```

### Vantaggi della Soluzione

1. **Riutilizzabile**: Funziona in qualsiasi progetto che ha la struttura `{project_root}/bashscripts/`
2. **Robusta**: Trova `bashscripts` indipendentemente dalla profondità della sottocartella
3. **Centralizzata**: Log e backup in `bashscripts/log/` e `bashscripts/backup/` invece che in `SCRIPT_DIR/`
4. **DRY**: Non duplica percorsi hardcoded

## Pattern da Seguire

Per tutti gli script bash che devono essere riutilizzabili tra progetti:

```bash
# Pattern Standard per Calcolo Percorsi (Robusto e Riutilizzabile)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Strategia a due fasi per massima robustezza
CURRENT_DIR="$SCRIPT_DIR"
BASHSCRIPTS_DIR=""
PROJECT_ROOT=""

# Fase 1: Cerca la cartella bashscripts (preferita)
while [[ "$CURRENT_DIR" != "/" ]]; do
    if [[ "$(basename "$CURRENT_DIR")" == "bashscripts" ]]; then
        BASHSCRIPTS_DIR="$CURRENT_DIR"
        PROJECT_ROOT="$(cd "$BASHSCRIPTS_DIR/.." && pwd)"
        break
    fi
    CURRENT_DIR="$(dirname "$CURRENT_DIR")"
done

# Fase 2: Se bashscripts non trovata, cerca la root del progetto
if [[ -z "$BASHSCRIPTS_DIR" ]]; then
    CURRENT_DIR="$SCRIPT_DIR"
    while [[ "$CURRENT_DIR" != "/" ]]; do
        if [[ -d "$CURRENT_DIR/laravel" ]] || [[ -d "$CURRENT_DIR/.git" ]]; then
            PROJECT_ROOT="$CURRENT_DIR"
            BASHSCRIPTS_DIR="${PROJECT_ROOT}/bashscripts"
            break
        fi
        CURRENT_DIR="$(dirname "$CURRENT_DIR")"
    done
fi

# Verifica che PROJECT_ROOT sia stata trovata
if [[ -z "$PROJECT_ROOT" ]]; then
    echo "Errore: Root del progetto non trovata"
    exit 1
fi

# Calcola BASE_DIR (Laravel)
if [[ -d "$PROJECT_ROOT/laravel" ]]; then
    BASE_DIR="${PROJECT_ROOT}/laravel"
else
    BASE_DIR="$PROJECT_ROOT"
fi
```

## File Corretto

- `bashscripts_temp/fix/fix_git_conflicts_current_change_v2.sh` - Logica percorsi corretta

## Collegamenti

- [Script Corretto](../../../../../../bashscripts_temp/fix/fix_git_conflicts_current_change_v2.sh)
- [Common Utilities](../../../../../../bashscripts/utils/common.sh)
- [Organizzazione Bashscripts](./organization-structure.md)
