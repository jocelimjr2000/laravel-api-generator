<?php

namespace JocelimJr\LaravelApiGenerator\Classes\Column;

use JocelimJr\LaravelApiGenerator\DataTransferObject\AbstractDTO;
use ReflectionClass;

class AbstractColumn extends AbstractDTO
{
    private ?string $name = null;

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
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): AbstractColumn
    {
        $this->name = $name;
        return $this;
    }


}
