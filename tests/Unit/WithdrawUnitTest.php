<?php

namespace Tests\Unit;

use App\Converters\AtmFillConverter;
use App\Enums\Errors;
use App\Models\Atm;
use App\Models\Withdraw;
use App\Services\WithdrawService;
use Tests\TestCase;

class WithdrawUnitTest extends TestCase
{
    private WithdrawService $service;
    private AtmFillConverter $atmFillconverter;

    private Atm $atm;
    private Withdraw $withdraw;

    public function setUp(): void
    {
        parent::setUp();

        $this->initAtm();
        $this->initWithdraw();

        $this->service = app(WithdrawService::class);
        $this->atmFillconverter = app(AtmFillConverter::class);
    }


    public function test_error_atm_not_exists(): void
    {
        $returnService = $this->service->store($this->withdraw);

        $atmCached = ATM::get();
        $returnExpect = $this->atmFillconverter->atmModelToAtmOutputDTO($atmCached, [Errors::ATM_NOT_EXISTS]);

        $this->assertEquals($returnService, $returnExpect);
    }

    public function test_error_unavailable_atm(): void
    {
        $atm = $this->atm;
        $atm->available = false;
        $atmCached = ATM::save($atm);

        $returnService = $this->service->store($this->withdraw);

        $returnExpect = $this->atmFillconverter->atmModelToAtmOutputDTO($atmCached, [Errors::ATM_UNAVAILABLE]);

        $this->assertEquals($returnService, $returnExpect);

        $this->assertFalse($atmCached->available);
    }

    public function test_error_unavailable_cache(): void
    {
        ATM::save($this->atm);

        $withdraw = $this->withdraw;
        $withdraw->cache = 2000;
        $returnService = $this->service->store($this->withdraw);

        $returnExpect = $this->atmFillconverter->atmModelToAtmOutputDTO($this->atm, [Errors::CACHE_UNAVAILABLE]);

        $this->assertEquals($returnService, $returnExpect);
    }

    public function test_error_duplicated_withdraw(): void
    {
        ATM::save($this->atm);

        $this->service->store($this->withdraw);
        $returnService = $this->service->store($this->withdraw);

        $returnExpect = $this->atmFillconverter->atmModelToAtmOutputDTO($this->atm, [Errors::WITHDRAW_DUPLICATED]);

        $this->assertEquals($returnService, $returnExpect);
    }

    public function test_success_withdraw(): void
    {
        ATM::save($this->atm);

        $returnService = $this->service->store($this->withdraw);

        $atmCached = ATM::get();

        $returnExpect = $this->atmFillconverter->atmModelToAtmOutputDTO($atmCached, []);

        $this->assertEquals($returnService, $returnExpect);
    }

    private function initAtm()
    {
        $this->atm = new Atm(true, 1, 2, 3, 4);
    }

    private function initWithdraw()
    {
        $this->withdraw = new Withdraw(50, "2019-02-13T02:54:01.000Z");
    }
}
