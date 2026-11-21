<?php

declare(strict_types=1);
use Modules\Chart\Tests\TestCase;

/**
 * Bootstrap Pest — modulo Chart.
 * Helper globali: tests/Support/helpers.php
 * Ogni file test dichiara uses(Modules\Chart\Tests\TestCase::class).
 */
/*
 * Il binding del TestCase sta **solo** qui, per cartella.
 *
 * Sei file di test dichiaravano in piu' un `uses(TestCase::class)` proprio, e
 * Pest 4 rifiuta il doppio binding in fase di **raccolta**:
 *
 *     Pest\Exceptions\TestCaseAlreadyInUse
 *     The test case [Modules\Chart\Tests\TestCase] may not be used here.
 *     The folder [.../Feature/ChartFactoryTest.php] is already bound to ...
 *
 * L'intera suite del modulo non partiva, e nei numeri un modulo che non parte e'
 * indistinguibile da un modulo senza test: non appariva come fallimento,
 * appariva come niente. Era la causa, mai isolata, di base_quaeris_fila5#233;
 * l'ha scoperta la guardia di `bin/test-tutti-i-moduli.sh`, che fallisce quando
 * un modulo non produce la riga `Tests:`.
 *
 * Fra i due costrutti si tiene **questo**, non i sei: una cartella legata copre
 * anche i file di test che verranno, mentre un file nuovo senza `uses()` si
 * legherebbe in silenzio a `PHPUnit\Framework\TestCase` — closure senza
 * applicazione Laravel, e il sintomo e' `Target class [config] does not exist`,
 * che in Cms valeva 242 fallimenti.
 */
pest()->extend(TestCase::class)->in(__DIR__.'/Unit', __DIR__.'/Feature');
