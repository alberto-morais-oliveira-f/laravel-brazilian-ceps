<?php

namespace Am2Tec\LaravelBrazilianCeps\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Am2Tec\LaravelBrazilianCeps\Tests\Helpers\DefaultValues;
use Am2Tec\LaravelBrazilianCeps\Tests\TestCase;

class ConsultCepApiRouteTest extends TestCase
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

    public function testValidatesIfTheCepsQueryRouteIsAccessible()
    {
        $routename       = 'consult-cep.api';
        $routes          = collect(RouteFacade::getRoutes());
        $consultCepRoute = $routes->map(fn(Route $route) => $route->getName())
                                  ->first(fn(string $name) => $name === $routename);

        $this->assertNotEmpty($consultCepRoute);
    }

    public function testValidateTheReturnStructureOfTheRouteOnSuccess()
    {
        $this->mockViaCepSuccessResponse();

        $response = $this->get('api/consult-cep/29018210');

        $response->assertStatus(200);
        $response->assertJsonStructure(
            DefaultValues::successfullyRequiredFields()
        );
    }

    public function testValidateTheReturnStructureOfTheRouteOnFailure()
    {
        Http::fake([
            '*' => Http::response([], 404),
        ]);

        $cepNotFoundMessage = config('brazilian-ceps.not_found_message');
        $response           = $this->get('api/consult-cep/66666666');

        $response->assertStatus(200);
        $response->assertJsonPath('failed', $cepNotFoundMessage);
    }
}
