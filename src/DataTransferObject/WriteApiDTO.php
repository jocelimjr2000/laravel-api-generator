<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use ReflectionClass;

class WriteApiDTO extends AbstractDTO
{
    private bool $findAll = true;
    private bool $findById = true;
    private bool $create = true;
    private bool $update = true;
    private bool $delete = true;

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
            $config = config('laravel-generator.default.writeApi');

            foreach ($config as $p => $v) {
                $this->{$p} = $v;
            }
        }
    }

    public function isFindAll(): bool
    {
        return $this->findAll;
    }

    public function setFindAll(bool $findAll): WriteApiDTO
    {
        $this->findAll = $findAll;
        return $this;
    }

    public function isFindById(): bool
    {
        return $this->findById;
    }

    public function setFindById(bool $findById): WriteApiDTO
    {
        $this->findById = $findById;
        return $this;
    }

    public function isCreate(): bool
    {
        return $this->create;
    }

    public function setCreate(bool $create): WriteApiDTO
    {
        $this->create = $create;
        return $this;
    }

    public function isUpdate(): bool
    {
        return $this->update;
    }

    public function setUpdate(bool $update): WriteApiDTO
    {
        $this->update = $update;
        return $this;
    }

    public function isDelete(): bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): WriteApiDTO
    {
        $this->delete = $delete;
        return $this;
    }

}
