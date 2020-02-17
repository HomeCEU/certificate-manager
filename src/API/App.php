<?php


namespace HomeCEU\API;


use Slim\Factory\AppFactory;

class App
{
    private $app;

    public function __construct()
    {
        $this->app = AppFactory::create();
    }

    public function get(): \Slim\App
    {
        return $this->app;
    }
}