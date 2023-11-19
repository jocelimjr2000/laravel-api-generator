<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;

abstract class AbstractReplace
{
    protected JsonDefinitionsDTO $jsonDefinitionsDTO;
    protected array $importsToReplace = [];

    public function __construct(JsonDefinitionsDTO $jsonDefinitionsDTO)
    {
        $this->jsonDefinitionsDTO = $jsonDefinitionsDTO;
    }

    /**
     * {{ model }}
     * @return string
     */
    protected function model(): string
    {
        return $this->jsonDefinitionsDTO->getFileName()->getModel();
    }

    /**
     * {{ modelVar }}
     * @return string
     */
    protected function modelVar(): string
    {
        return '$' . lcfirst($this->jsonDefinitionsDTO->getFileName()->getModel());
    }

    /**
     * {{ modelTable }}
     * @return string
     */
    protected function table(): string
    {
        return $this->jsonDefinitionsDTO->getTable();
    }

    /**
     * {{ imports }}
     * @return string
     */
    protected function imports(): string
    {
        $str = '';

        foreach($this->importsToReplace as $k => $v){
            $str .= 'use ' . $v . ';' . (($k < count($this->importsToReplace) - 1) ? PHP_EOL : '');
        }

        return $str;
    }
}
