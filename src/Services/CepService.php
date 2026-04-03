<?php

namespace Am2Tec\LaravelBrazilianCeps\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\Conditionable;
use Am2Tec\LaravelBrazilianCeps\CepProviders\ApiCep;
use Am2Tec\LaravelBrazilianCeps\CepProviders\BrasilApiV1;
use Am2Tec\LaravelBrazilianCeps\CepProviders\BrasilApiV2;
use Am2Tec\LaravelBrazilianCeps\CepProviders\OpenCep;
use Am2Tec\LaravelBrazilianCeps\CepProviders\Pagarme;
use Am2Tec\LaravelBrazilianCeps\CepProviders\Postomon;
use Am2Tec\LaravelBrazilianCeps\CepProviders\ViaCep;
use Am2Tec\LaravelBrazilianCeps\Contracts\ConsultableCEPProvider;
use Am2Tec\LaravelBrazilianCeps\Entities\CepEntity;
use Am2Tec\LaravelBrazilianCeps\Exceptions\CepNotFoundException;
use Am2Tec\LaravelBrazilianCeps\Helpers\MaskHelper;

class CepService
{
    use Conditionable;

    public function __construct(
        protected ?CepEntity $cepEntity = null,
        protected array      $cepApis = [
            ViaCep::class,
            Pagarme::class,
            OpenCep::class,
            ApiCep::class,
            Postomon::class,
            BrasilApiV1::class,
            BrasilApiV2::class
        ]
    )
    {
    }

    /**
     * @throws CepNotFoundException
     */
    public function get(string $cep): ?CepEntity
    {
        $hasCacheResultsEnabled = config('brazilian-ceps.cache_results', true);
        $cacheResultsLifetime   = config('brazilian-ceps.cache_lifetime_in_days', 30);

        if ($hasCacheResultsEnabled) {
            return Cache::remember(
                "cep:{$cep}",
                now()->addDays($cacheResultsLifetime),
                fn() => $this->processCep($cep));
        }

        return $this->processCep($cep);
    }

    /**
     * @throws CepNotFoundException
     */
    protected function processCep(string $cep): ?CepEntity
    {
        foreach ($this->cepApis as $cepApi) {
            $cepApiProvider = new $cepApi;
            if ($cepApiProvider instanceof ConsultableCEPProvider) {
                $this->when(
                    !$this->cepEntity?->cep,
                    fn() => $this->cepEntity = $cepApiProvider->get($cep)
                );
            }
        }

        $hasNotFoundExceptionEnabled = config(
            'brazilian-ceps.throw_not_found_exception',
            false
        );

        if ($hasNotFoundExceptionEnabled) {
            $cep = MaskHelper::make($cep, '#####-###');
            throw new CepNotFoundException($cep);
        }

        return $this->cepEntity;
    }
}
