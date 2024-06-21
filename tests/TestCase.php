<?php

namespace Tests;

use Illuminate\Http\Request;
use MatheusFS\Laravel\Checkout\ServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase{

    protected $loadEnvironmentVariables = true;

    public function setUp(): void{

        parent::setUp();
    }

    protected function getPackageProviders($app){

        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app){

        return [];
    }

    protected function create_request_from_example($name){

        $example = $this->get_example($name);

        $request = new Request();
        $request->headers->replace($example['headers']);
        $request->merge($example['payload']);

        return $request;
    }

    protected function get_example($name, $as_array = true){

        $directory = dirname(__DIR__) . '/storage/examples';
        $example = file_get_contents("$directory/$name.json");

        return json_decode($example, $as_array);
    }
}