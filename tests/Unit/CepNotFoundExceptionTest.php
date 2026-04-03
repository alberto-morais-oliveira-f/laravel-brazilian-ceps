<?php

namespace Am2Tec\LaravelBrazilianCeps\Tests\Unit;

use Am2Tec\LaravelBrazilianCeps\Exceptions\CepNotFoundException;
use Am2Tec\LaravelBrazilianCeps\Services\CepService;
use Am2Tec\LaravelBrazilianCeps\Tests\TestCase;

class CepNotFoundExceptionTest extends TestCase
{
    /**
     * @throws CepNotFoundException
     */
    public function testValidateCepNotFoundException()
    {
        $this->expectException(CepNotFoundException::class);
        
        config(['brazilian-ceps.throw_not_found_exception' => true]);

        $cepService = new CepService();

        $cepService->get('66666666');
    }
}
