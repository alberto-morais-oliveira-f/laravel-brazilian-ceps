<?php

namespace Am2Tec\LaravelBrazilianCeps\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Am2Tec\LaravelBrazilianCeps\LaravelBrazilianCepsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * @method artisan
 */
class TestCase extends Orchestra
{
    use WithFaker;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelBrazilianCepsServiceProvider::class,
        ];
    }

    protected function setUpFaker(): void
    {
        $this->faker = $this->makeFaker('pt_BR');
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('cache.default', 'array');
    }
}
