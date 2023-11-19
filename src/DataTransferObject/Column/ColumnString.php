<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnString extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('string');
    }

    private ?string $name = null;
    private ?int $length = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnString
    {
        $this->name = $name;
        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): ColumnString
    {
        $this->length = $length;
        return $this;
    }

}
