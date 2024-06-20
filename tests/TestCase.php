<?php

namespace MatheusFS\Laravel\Checkout\Tests;

use MatheusFS\Laravel\Checkout\ServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase{

    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app){

        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app){

        return [];
    }

    public function setUp(): void{

        parent::setUp();
    }
}