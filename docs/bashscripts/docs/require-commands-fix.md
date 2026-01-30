# Correzione Funzione require_commands

## Problema

Lo script `bashscripts/ai/ai_init.sh` utilizzava la funzione `require_commands` che non esisteva in `bashscripts/utils/common.sh`, causando l'errore:

```
./bashscripts/ai/ai_init.sh: line 61: require_commands: command not found
```

## Analisi

### Logica dello Script
Lo script `ai_init.sh` crea symlink nella root del progetto per le directory AI che iniziano con punto (es. `.cursor`, `.claude`, `.ai`) da `bashscripts/ai/`.

### Filosofia
- **DRY**: Utilizza funzioni comuni da `utils/common.sh`
- **KISS**: Una chiamata invece di multiple verifiche
- **Portabilità**: Path relativi invece di assoluti
- **Idempotenza**: Può essere eseguito più volte senza effetti collaterali

### Causa Root
La funzione `require_commands` era referenziata ma non implementata. Esisteva solo `check_command_exists` che verifica un singolo comando.

## Soluzione Implementata

Aggiunta la funzione `require_commands` in `bashscripts/utils/common.sh`:

```bash
require_commands() {
    local missing_commands=()
    for cmd in "$@"; do
        if ! command -v "$cmd" >/dev/null 2>&1; then
            missing_commands+=("$cmd")
        fi
    done
    
    if [ ${#missing_commands[@]} -gt 0 ]; then
        log_error "Missing required commands: ${missing_commands[*]}"
        fail "Please install the missing commands or ensure they are in your PATH."
    fi
}
```

### Perché Questa Soluzione

**Vincitore della "litigata interna"**: Estensione invece di duplicazione

1. **DRY**: Una funzione per verificare più comandi invece di chiamate multiple
2. **KISS**: Una chiamata `require_commands find ln readlink` invece di tre `check_command_exists`
3. **Riutilizzabile**: Altri script possono usare la stessa funzione
4. **Principio Open/Closed**: Estende senza modificare funzioni esistenti

## Utilizzo

```bash
# Verifica un singolo comando
check_command_exists git

# Verifica più comandi contemporaneamente
require_commands find ln readlink git
```

## Verifica

Lo script `ai_init.sh` ora funziona correttamente:

```bash
./bashscripts/ai/ai_init.sh
```

Crea symlink per tutte le directory AI nella root del progetto.

## File Modificati

- `bashscripts/utils/common.sh`: Aggiunta funzione `require_commands`

## Collegamenti

- [Script AI Initializer](../../../../../../bashscripts/ai/ai_init.sh)
- [Common Utilities](../../../../../../bashscripts/utils/common.sh)
- [Documentazione Bashscripts](./README.md)
