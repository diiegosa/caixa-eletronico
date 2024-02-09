<?php

namespace Tests\Feature;

use App\Enums\Errors;
use Tests\TestCase;

class FillAtmTest extends TestCase
{
    private $fillAtmInput;
    private $fillAtmOutput;

    const FILL_ATM_PATH = "api/fill";
    const ATM_BASE_ATRIBUTTES = [
        "caixa" => [
            "caixaDisponivel" => true,
            "notas" => [
                "notasDez" => 0,
                "notasVinte" => 0,
                "notasCinquenta" => 2,
                "notasCem" => 1
            ]
        ]
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->initFillAtmInputAtributte();
        $this->initFillAtmOutputAtributte();
    }

    public function test_error_available_atm(): void
    {
        $this->post(self::FILL_ATM_PATH, $this->fillAtmInput);

        $response = $this->post(self::FILL_ATM_PATH, $this->fillAtmInput);
        $response->assertStatus(400);
        $response->assertJson($this->fillAtmOutput);
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals(
            $responseContent['erros'][0],
            Errors::ATM_AVAILABLE->value
        );
    }

    public function test_success_fill_atm(): void
    {
        $response = $this->post(self::FILL_ATM_PATH, $this->fillAtmInput);

        $response->assertStatus(200);

        $response->assertJson($this->fillAtmOutput);

        $responseContent = json_decode($response->getContent(), true);
        $this->assertEmpty($responseContent['erros']);
    }

    private function initFillAtmInputAtributte()
    {
        $this->fillAtmInput = self::ATM_BASE_ATRIBUTTES;
    }

    private function initFillAtmOutputAtributte()
    {
        $this->fillAtmOutput = self::ATM_BASE_ATRIBUTTES;
        $this->fillAtmOutput["erros"] = [];
    }
}
