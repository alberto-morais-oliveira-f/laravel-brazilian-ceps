<?php

namespace Am2Tec\LaravelBrazilianCeps\Contracts;

use Am2Tec\LaravelBrazilianCeps\Entities\CepEntity;

interface ConsultableCEPProvider
{
    public function get(string $cep): ?CepEntity;

    public function getBaseUrl(): string;
}
