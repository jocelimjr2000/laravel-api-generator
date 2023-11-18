<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use ReflectionClass;

class FileNameDTO extends AbstractDTO
{
    private ?string $routes = null;
    private ?string $controller = null;
    private ?string $formRequest = null;
    private ?string $formCreateRequest = null;
    private ?string $formUpdateRequest = null;
    private ?string $formDeleteRequest = null;
    private ?string $model = null;
    private ?string $repository = null;
    private ?string $repositoryInterface = null;
    private ?string $mapper = null;
    private ?string $dto = null;
    private ?string $service = null;
    private ?string $featureTest = null;
    private ?string $migration = null;

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
            $config = config('laravel-generator.default.fileName');

            foreach ($config as $p => $v) {
                $this->{$p} = $v;
            }
        }
    }

    public function getRoutes(): ?string
    {
        return $this->routes;
    }

    public function setRoutes(?string $routes): FileNameDTO
    {
        $this->routes = $routes;
        return $this;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function setController(?string $controller): FileNameDTO
    {
        $this->controller = $controller;
        return $this;
    }

    public function getFormRequest(): ?string
    {
        return $this->formRequest;
    }

    public function setFormRequest(?string $formRequest): FileNameDTO
    {
        $this->formRequest = $formRequest;
        return $this;
    }

    public function getFormCreateRequest(): ?string
    {
        return $this->formCreateRequest;
    }

    public function setFormCreateRequest(?string $formCreateRequest): FileNameDTO
    {
        $this->formCreateRequest = $formCreateRequest;
        return $this;
    }

    public function getFormUpdateRequest(): ?string
    {
        return $this->formUpdateRequest;
    }

    public function setFormUpdateRequest(?string $formUpdateRequest): FileNameDTO
    {
        $this->formUpdateRequest = $formUpdateRequest;
        return $this;
    }

    public function getFormDeleteRequest(): ?string
    {
        return $this->formDeleteRequest;
    }

    public function setFormDeleteRequest(?string $formDeleteRequest): FileNameDTO
    {
        $this->formDeleteRequest = $formDeleteRequest;
        return $this;
    }

    public function getModel(bool $variableMode = false): ?string
    {
        if($variableMode) return '$' . lcfirst($this->model);

        return $this->model;
    }

    public function setModel(?string $model): FileNameDTO
    {
        $this->model = $model;
        return $this;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    public function setRepository(?string $repository): FileNameDTO
    {
        $this->repository = $repository;
        return $this;
    }

    public function getRepositoryInterface(): ?string
    {
        return $this->repositoryInterface;
    }

    public function setRepositoryInterface(?string $repositoryInterface): FileNameDTO
    {
        $this->repositoryInterface = $repositoryInterface;
        return $this;
    }

    public function getMapper(): ?string
    {
        return $this->mapper;
    }

    public function setMapper(?string $mapper): FileNameDTO
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function getDto(bool $variableMode = false): ?string
    {
        if($variableMode) return '$' . lcfirst($this->dto);

        return $this->dto;
    }

    public function setDto(?string $dto): FileNameDTO
    {
        $this->dto = $dto;
        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): FileNameDTO
    {
        $this->service = $service;
        return $this;
    }

    public function getFeatureTest(): ?string
    {
        return $this->featureTest;
    }

    public function setFeatureTest(?string $featureTest): FileNameDTO
    {
        $this->featureTest = $featureTest;
        return $this;
    }

    public function getMigration(): ?string
    {
        return $this->migration;
    }

    public function setMigration(?string $migration): FileNameDTO
    {
        $this->migration = $migration;
        return $this;
    }

}
