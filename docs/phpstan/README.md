# Analisi PHPStan per il modulo Chart

> Per le regole correnti sulle Actions Chart e JpGraph consultare la [nota canonica Actions e qualità statica](../actions/README.md). Questo file conserva anche materiale storico sui livelli e sugli errori ricorrenti.

> **Stato corrente (2026-10-08):** `./vendor/bin/phpstan analyse Modules/Chart`
> restituisce **OK — No errors**. La mappa canonica delle Action è in
> [actions/README.md](../actions/README.md).

Data documento storico: Wed Jan 8 10:42:39 CEST 2025

## Riassunto storico

| Livello | Stato | Errori |
|---------|-------|--------|
| 9 | ✅ Successo | Nessun errore |

## Panoramica
Questo documento descrive l'analisi statica del codice tramite PHPStan per il modulo Chart, inclusi i livelli di analisi, gli errori comuni e le soluzioni implementate.

## Struttura della Documentazione

Questa cartella contiene l'analisi statica del codice eseguita con PHPStan per il modulo Chart.

## File di Configurazione

- [`phpstan.neon`](../../../../phpstan.neon): Configurazione principale del progetto
- [Verifica corrente delle Action](../actions/README.md#verifica-phpstan)

## Come Eseguire l'Analisi

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Chart --error-format=table
```

## Correzioni Recenti

### Metodo getSettings() - Chart Model
**Data**: 8 Gennaio 2025
**Problema**: Errori di tipizzazione PHPStan livello 9
- `Method should return array<string, array<int|string, mixed>> but returns array<mixed>`
- `PHPDoc tag @var with type array<string, array<int|string, mixed>> is not subtype of native type array{mixed}`

**Soluzione**: 
- Corretta la tipizzazione del return type da `array<string, array<int|string, mixed>>` a `array<string, array<string, mixed>>`
- Rimossa la tipizzazione ridondante `array<int|string, mixed>` che causava conflitti
- Aggiunta documentazione PHPDoc completa per il metodo
- Verificata compatibilità con PHPStan livello 9

**File**: `app/Models/Chart.php` - metodo `getSettings()`

## Livello corrente

Il progetto usa il livello massimo tramite la configurazione condivisa in
[`laravel/phpstan.neon`](../../../../phpstan.neon). La verifica eseguibile e
la mappa delle Action sono mantenute in
[actions/README.md](../actions/README.md#verifica-phpstan).

## Configurazione

### phpstan.neon
```yaml
parameters:
    level: 9
    paths:
        - app
    excludePaths:
        - app/Filament/Pages
        - build
        - vendor
        - Tests
    ignoreErrors:
        - '#Unsafe usage of new static#'
        - '#Access to an undefined property#'
        - '#Call to an undefined method#'
```

## Errori Comuni

### 1. Type Hints Mancanti
```php
// ❌ NON FARE QUESTO
function getData($id) {
    return Chart::find($id);
}

// ✅ FARE QUESTO
function getData(int $id): ?Chart {
    return Chart::find($id);
}
```

### 2. Array Type Specifications (RISOLTO: 2025-01-06)
```php
// ❌ NON FARE QUESTO
/** @return array<string, array<int|string, mixed>> */
public function getSettings(): array {
    return $mixed->charts->toArray(); // Collection->toArray() restituisce array<mixed>
}

// ✅ FARE QUESTO
/** @return array<int, array<string, mixed>> */
public function getSettings(): array {
    /** @var array<int, array<string, mixed>> $chartsArray */
    $chartsArray = $mixed->charts->toArray();
    return $chartsArray;
}
```

### 3. Null Safety
```php
// ❌ NON FARE QUESTO
$chart->title = $request->title;

// ✅ FARE QUESTO
$chart->title = $request->title ?? $chart->title;
```

### 4. Return Types
```php
// ❌ NON FARE QUESTO
public function getChartData() {
    return $this->data;
}

// ✅ FARE QUESTO
public function getChartData(): array {
    return $this->data;
}
```

## Best Practices

### 1. Type Declarations
- Usare sempre type hints
- Specificare return types
- Utilizzare nullable types quando appropriato

### 2. PHPDoc
- Documentare parametri complessi
- Specificare tipi generici
- Mantenere documentazione aggiornata

### 3. Testing
- Scrivere test per casi edge
- Verificare null safety
- Testare type conversions

## Risoluzione Problemi

### Analisi
```bash

# Analisi completa
php artisan phpstan:analyse

# Analisi specifica
php artisan phpstan:analyse app/Models/Chart.php

# Debug
php artisan phpstan:analyse --debug
```

### Fixing
```bash

# Fix automatici
php artisan phpstan:fix

# Fix specifici
php artisan phpstan:fix app/Models/Chart.php
```

### Fix Documentati
- [Actions e qualità statica](../actions/README.md) - Nota canonica sulle Actions e sull’integrazione JpGraph.

## Collegamenti Bidirezionali

### Collegamenti ad Altri Moduli
- [PHPStan Xot](../../../Xot/docs/phpstan/README.md)

> I moduli User e Activity non espongono una pagina PHPStan nel checkout
> corrente; non vengono collegati per evitare riferimenti non risolvibili.

### Collegamenti Interni
- [README Principale](../README.md)
- [Implementazione](../implementazione/README.md)
- [Testing](../implementazione/testing/README.md)
- [Performance](../performance/chart-bottlenecks.md)

## Collegamenti

- [README Chart](../README.md)
- Le regole PHPStan globali appartengono alla documentazione del repository e
  non sono duplicate nella documentazione del modulo.
