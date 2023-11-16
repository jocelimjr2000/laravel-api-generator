<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

class SwaggerDTO extends AbstractDTO
{
    private bool $generate;
    private array $tags;

    public function __construct()
    {
        $config = config('laravel-generator.default.swagger');

        foreach($config as $p => $v){
            $this->{$p} = $v;
        }
    }

    public function isGenerate(): bool
    {
        return $this->generate;
    }

    public function setGenerate(bool $generate): SwaggerDTO
    {
        $this->generate = $generate;
        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): SwaggerDTO
    {
        $this->tags = $tags;
        return $this;
    }

}
