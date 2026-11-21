<?php

declare(strict_types=1);

namespace Modules\Chart\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\ServiceProvider;
use Modules\Chart\Models\Chart;
use Modules\Chart\Providers\ChartServiceProvider;
use Modules\User\Providers\UserServiceProvider;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case for Chart module.
 *
 * @property Chart|null $chart
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /**
     * Le connessioni che la transazione di test deve coprire.
     *
     * `DatabaseTransactions` da solo avvolge **soltanto la connessione
     * predefinita**: tutto cio' che un test scrive sulle altre viene
     * **committato** e resta per la corsa successiva. Il sintomo e'
     * `UniqueConstraintViolationException` su un test che il giorno prima
     * passava — il modo peggiore di fallire, perche' sembra una regressione del
     * codice e invece e' sporcizia della corsa precedente. Nel modulo Tenant
     * erano 20 fallimenti su 62 (base_quaeris_fila5#228).
     *
     * Solo le connessioni su cui il modulo scrive, non tutte le 27 configurate:
     * avvolgerle tutte vorrebbe dire transazioni parallele sulle stesse righe,
     * cioe' attese di lock fra connessioni dello stesso test.
     *
     * Se un test nuovo scrive su un'altra connessione e lascia righe dietro, va
     * aggiunta qui.
     *
     * @var list<string>
     */
    protected $connectionsToTransact = [
        'mysql',
        'chart',
        'user',
        'quaeris_data',
    ];

    public ?Chart $chart = null;

    /** @return array<int, class-string<ServiceProvider>> */
    protected function getPackageProviders(Application $app): array
    {
        return [
            ...parent::getPackageProviders($app),
            ChartServiceProvider::class,
            UserServiceProvider::class,
        ];
    }

    public function requireChart(): Chart
    {
        if ($this->chart === null) {
            $this->fail('Chart test property is not initialized.');
        }

        return $this->chart;
    }
}
