<?php

namespace Am2Tec\LaravelBrazilianCeps\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Am2Tec\LaravelBrazilianCeps\Resources\CepResource;
use Am2Tec\LaravelBrazilianCeps\Services\CepService;

class ConsultCepController extends Controller
{
    public function __construct(protected CepService $cepService)
    {
    }

    public function __invoke(string|int $cep): JsonResponse|CepResource
    {
        try {
            $cep = $this->cepService->get($cep);
        } catch (Exception) {
            $cep = null;
        }
        
        $cepNotFoundMessage = config(
            'brazilian-ceps.not_found_message',
            'CEP não encontrado.'
        );

        return $cep?->cep
            ? CepResource::make($cep)
            : response()->json(['failed' => $cepNotFoundMessage]);
    }
}
