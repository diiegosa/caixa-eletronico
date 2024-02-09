<?php

namespace Tests\Feature;

use App\Enums\Errors;
use Tests\TestCase;

class WithdrawTest extends TestCase
{
    private $fillAtmInput;
    private $fillAtmOutput;
    private $withdrawInput;

    const WITHDRAW_PATH = "api/withdraw";
    const FILL_ATM_PATH = "api/fill";
    const ATM_BASE_ATRIBUTTES = [
        "caixa" => [
            "caixaDisponivel" => false,
            "notas" => [
                "notasDez" => 2,
                "notasVinte" => 3,
                "notasCinquenta" => 4,
                "notasCem" => 2
            ]
        ]
    ];
    const WITHDRAW_BASE_ATRIBUTTES = [
        "saque" => [
            "valor" => 150,
            "horario" => "2019-02-13T11:01:01.000Z",
        ]
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->initWithdrawInputAtributte();
        $this->initFillAtmInputAtributte();
        $this->initFillAtmOutputAtributte();
    }

    public function test_error_atm_not_exists(): void
    {
        $response = $this->post(self::WITHDRAW_PATH, $this->withdrawInput);
        $response->assertStatus(400);

        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals(
            $responseContent['erros'][0],
            Errors::ATM_NOT_EXISTS->value
        );
    }

    public function test_error_unavailable_atm(): void
    {
        $this->post(self::FILL_ATM_PATH, $this->fillAtmInput);

        $response = $this->post(self::WITHDRAW_PATH, $this->withdrawInput);
        $response->assertStatus(400);

        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals(
            $responseContent['erros'][0],
            Errors::ATM_UNAVAILABLE->value
        );
    }

    public function test_error_unavailable_cache(): void
    {
        $fillAtmInput = $this->fillAtmInput;
        $fillAtmInput['caixa']['caixaDisponivel'] = true;
        $withdrawInput = $this->withdrawInput;
        $withdrawInput['saque']['valor'] = 700;

        $this->post(self::FILL_ATM_PATH, $fillAtmInput);

        $response = $this->post(self::WITHDRAW_PATH, $withdrawInput);
        $response->assertStatus(400);

        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals(
            $responseContent['erros'][0],
            Errors::CACHE_UNAVAILABLE->value
        );
    }

    public function test_error_duplicated_withdraw(): void
    {
        $fillAtmInput = $this->fillAtmInput;
        $fillAtmInput['caixa']['caixaDisponivel'] = true;

        $withdrawInput = [
            "saque" => [
                "valor" => 150,
                "horario" => "2019-02-13T11:02:01.000Z",
            ]
        ];

        $this->post(self::FILL_ATM_PATH, $fillAtmInput);
        $this->post(self::WITHDRAW_PATH, $this->withdrawInput);

        $response = $this->post(self::WITHDRAW_PATH, $withdrawInput);
        $response->assertStatus(400);

        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals(
            $responseContent['erros'][0],
            Errors::WITHDRAW_DUPLICATED->value
        );
    }

    public function test_success_withdraw(): void
    {
        $fillAtmInput = $this->fillAtmInput;
        $fillAtmInput['caixa']['caixaDisponivel'] = true;

        $this->post(self::FILL_ATM_PATH, $fillAtmInput);

        $response = $this->post(self::WITHDRAW_PATH, $this->withdrawInput);

        $response->assertStatus(200);

        $responseContent = json_decode($response->getContent(), true);
        $this->assertEmpty($responseContent['erros']);
    }

    private function initWithdrawInputAtributte()
    {
        $this->withdrawInput = self::WITHDRAW_BASE_ATRIBUTTES;
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
