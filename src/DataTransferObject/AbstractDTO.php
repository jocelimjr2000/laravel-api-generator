<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use JsonSerializable;
use ReflectionClass;

abstract class AbstractDTO implements JsonSerializable
{
    private array $_jsonIgnore = [];

    /**
     * jsonIgnore
     *
     * @param  mixed $value
     * @return AbstractDTO
     */
    public function jsonIgnore(string|array $value): AbstractDTO
    {
        if(!is_array($value)){
            $value = [$value];
        }

        $this->_jsonIgnore = array_merge($this->_jsonIgnore, $value);

        return $this;
    }

    /**
     * jsonSerialize
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = [];

        foreach($reflection->getProperties() as $property) {

            if(in_array($property->getName(), $this->_jsonIgnore)){
                continue;
            }

            $property->setAccessible(true);
            $method = 'get' . ucfirst($property->getName());

            if(method_exists($this, $method)){
                $properties[$property->getName()] = $this->$method();
            }else{
                $properties[$property->getName()] = $property->getValue($this);
            }
        }

        return $properties;
    }
}
