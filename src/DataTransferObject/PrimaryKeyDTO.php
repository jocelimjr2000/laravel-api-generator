<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

class PrimaryKeyDTO extends AbstractDTO
{
    private ?string $name = null;
    private ?string $type = null;
    private ?string $alias = null;

    public function __construct()
    {
        $config = config('laravel-generator.default.primaryKey');

        foreach($config as $p => $v){
            $this->{$p} = $v;
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): PrimaryKeyDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): PrimaryKeyDTO
    {
        $this->type = $type;
        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): PrimaryKeyDTO
    {
        $this->alias = $alias;
        return $this;
    }

}
