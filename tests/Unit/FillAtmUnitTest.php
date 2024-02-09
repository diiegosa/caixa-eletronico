<?php

namespace Tests\Unit;

use App\Converters\AtmFillConverter;
use App\Enums\Errors;
use App\Models\Atm;
use App\Services\AtmFillService;
use Tests\TestCase;

class FillAtmUnitTest extends TestCase
{
    private AtmFillService $service;
    private AtmFillConverter $converter;
    private Atm $atm;

    public function setUp(): void
    {
        parent::setUp();

        $this->initAtm();

        $this->service = app(AtmFillService::class);
        $this->converter = app(AtmFillConverter::class);
    }

    public function test_error_available_atm(): void
    {
        $this->service->store($this->atm);
        $returnService = $this->service->store(new Atm(true, 0, 0, 0, 0));

        $returnExpect = $this->converter->atmModelToAtmOutputDTO($this->atm, [Errors::ATM_AVAILABLE]);

        $this->assertEquals($returnService, $returnExpect);

        $this->assertEquals(ATM::get(), $this->atm);
    }

    public function test_success_fill_atm(): void
    {
        $returnService = $this->service->store($this->atm);
        $returnExpect = $this->converter->atmModelToAtmOutputDTO($this->atm);

        $this->assertEquals($returnService, $returnExpect);

        $this->assertEquals(ATM::get(), $this->atm);
    }

    private function initAtm()
    {
        $this->atm = new Atm(true, 1, 2, 3, 4);
    }
}
