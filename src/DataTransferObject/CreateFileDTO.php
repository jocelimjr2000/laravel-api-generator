<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use ReflectionClass;

class CreateFileDTO extends AbstractDTO
{
    private bool $routes;
    private bool $controller;
    private bool $formRequest;
    private bool $migration;
    private bool $model;
    private bool $repository;
    private bool $mapper;
    private bool $dto;
    private bool $service;
    private bool $featureTest;

    public function __construct(object $data = null)
    {
        if($data){
            $reflectionClass = new ReflectionClass($this);

            foreach($reflectionClass->getProperties() as $p){
                $n = $p->getName();

                if(!isset($data->$n)){
                    continue;
                }

                $setter = 'set' . ucfirst($p->getName());

                $this->$setter($data->$n);
            }
        }else {
            $config = config('laravel-generator.default.createFile');

            foreach ($config as $p => $v) {
                $this->{$p} = $v;
            }
        }
    }

    public function isMigration(): bool
    {
        return $this->migration;
    }

    public function setMigration(bool $migration): CreateFileDTO
    {
        $this->migration = $migration;
        return $this;
    }

    public function isModel(): bool
    {
        return $this->model;
    }

    public function setModel(bool $model): CreateFileDTO
    {
        $this->model = $model;
        return $this;
    }

    public function isRepository(): bool
    {
        return $this->repository;
    }

    public function setRepository(bool $repository): CreateFileDTO
    {
        $this->repository = $repository;
        return $this;
    }

    public function isMapper(): bool
    {
        return $this->mapper;
    }

    public function setMapper(bool $mapper): CreateFileDTO
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function isDto(): bool
    {
        return $this->dto;
    }

    public function setDto(bool $dto): CreateFileDTO
    {
        $this->dto = $dto;
        return $this;
    }

    public function isService(): bool
    {
        return $this->service;
    }

    public function setService(bool $service): CreateFileDTO
    {
        $this->service = $service;
        return $this;
    }

    public function isController(): bool
    {
        return $this->controller;
    }

    public function setController(bool $controller): CreateFileDTO
    {
        $this->controller = $controller;
        return $this;
    }

    public function isFormRequest(): bool
    {
        return $this->formRequest;
    }

    public function setFormRequest(bool $formRequest): CreateFileDTO
    {
        $this->formRequest = $formRequest;
        return $this;
    }

    public function isRoutes(): bool
    {
        return $this->routes;
    }

    public function setRoutes(bool $routes): CreateFileDTO
    {
        $this->routes = $routes;
        return $this;
    }

    public function isFeatureTest(): bool
    {
        return $this->featureTest;
    }

    public function setFeatureTest(bool $featureTest): CreateFileDTO
    {
        $this->featureTest = $featureTest;
        return $this;
    }

}
