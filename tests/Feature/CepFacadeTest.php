<?php

namespace Am2Tec\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Http;
use Am2Tec\LaravelBrazilianCeps\Entities\CepEntity;
use Am2Tec\LaravelBrazilianCeps\Exceptions\CepNotFoundException;
use Am2Tec\LaravelBrazilianCeps\Facades\CEP;
use Am2Tec\LaravelBrazilianCeps\Tests\TestCase;

class CepFacadeTest extends TestCase
{
    private function mockViaCepSuccessResponse(): void
    {
        Http::fake([
            '*' => Http::response([
                'cep' => '29018-210',
                'localidade' => $this->faker->city(),
                'logradouro' => $this->faker->streetName(),
                'uf' => 'ES',
                'bairro' => $this->faker->word(),
                'ibge' => '3205309',
            ], 200),
        ]);
    }

    public function testCepFacadeReturnsCorrectResponseStructure()
    {
        $this->mockViaCepSuccessResponse();

        $response = CEP::get('29018-210');

        $this->assertInstanceOf(CepEntity::class, $response);
    }

    public function testCepFacadeThrowsExceptionWhenCepNotFound()
    {
        Http::fake([
            '*' => Http::response([], 404),
        ]);

        config(['brazilian-ceps.throw_not_found_exception' => true]);

        $this->expectException(Exception::class);

        CEP::get('66666666');
    }
}
