<?php

namespace Am2Tec\LaravelBrazilianCeps\Facades;

use Illuminate\Support\Facades\Facade;
use Am2Tec\LaravelBrazilianCeps\Entities\CepEntity;
use Am2Tec\LaravelBrazilianCeps\Services\CepService;

/**
 * @method static null|CepEntity get(string $cep)
 */
class CEP extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CepService::class;
    }
}
