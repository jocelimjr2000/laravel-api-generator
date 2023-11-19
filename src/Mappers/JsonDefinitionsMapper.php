<?php

namespace JocelimJr\LaravelApiGenerator\Mappers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;
use ReflectionClass;
use ReflectionException;

class JsonDefinitionsMapper
{

    /**
     * @throws ReflectionException
     */
    public static function json2Class(mixed $json, bool $jsonDecode = true): JsonDefinitionsDTO
    {
        if($jsonDecode){
            $json = json_decode($json);
        }

        $jsonDefinitionsDTO = new JsonDefinitionsDTO();

        $reflectionClass = new ReflectionClass($jsonDefinitionsDTO);

        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            $propertyType = $reflectionClass->getProperty($propertyName)->getType();
            $propertyTypeName = $propertyType->getName();

            $setterMethod = 'set' . ucfirst($propertyName);

            if (!method_exists($jsonDefinitionsDTO, $setterMethod)) {
                continue;
            }

            if($propertyName == 'columns'){
                $columns = [];

                foreach($json->$propertyName as $column) {
                    $columnClass = 'JocelimJr\\LaravelApiGenerator\\DataTransferObject\\Column\\Column' . ucfirst($column->type);
                    $columnInstance = new $columnClass();

                    foreach($column as $k => $c) {
                        $columnSetterMethod = 'set' . ucfirst($k);
                        $columnInstance->$columnSetterMethod($c);
                    }

                    $columns[] = $columnInstance;
                }

                $jsonDefinitionsDTO->$setterMethod($columns);
            }

            else if ($propertyType->isBuiltin()) {
                $jsonDefinitionsDTO->$setterMethod($json->$propertyName);
            }

            else {
                $classInstance = new $propertyTypeName();
                $subReflectionClass = new ReflectionClass($classInstance);

                foreach ($subReflectionClass->getProperties() as $subProperty) {
                    $subPropertyName = $subProperty->getName();
                    $subSetterMethod = 'set' . ucfirst($subPropertyName);

                    $classInstance->$subSetterMethod($json->$propertyName->$subPropertyName);
                }

                $jsonDefinitionsDTO->$setterMethod($classInstance);
            }
        }

        return $jsonDefinitionsDTO;
    }
}
