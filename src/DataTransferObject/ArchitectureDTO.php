<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

class ArchitectureDTO extends AbstractDTO
{
    private bool $service;
    private bool $repository;
    private bool $mapper;
    private bool $dto;

    public function __construct()
    {
        $config = config('laravel-generator.default.architecture');

        foreach($config as $p => $v){
            $this->{$p} = $v;
        }
    }

    public function isService(): bool
    {
        return $this->service;
    }

    public function setService(bool $service): ArchitectureDTO
    {
        $this->service = $service;
        return $this;
    }

    public function isRepository(): bool
    {
        return $this->repository;
    }

    public function setRepository(bool $repository): ArchitectureDTO
    {
        $this->repository = $repository;
        return $this;
    }

    public function isMapper(): bool
    {
        return $this->mapper;
    }

    public function setMapper(bool $mapper): ArchitectureDTO
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function isDto(): bool
    {
        return $this->dto;
    }

    public function setDto(bool $dto): ArchitectureDTO
    {
        $this->dto = $dto;
        return $this;
    }

}
