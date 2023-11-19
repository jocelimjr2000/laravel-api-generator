<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnBoolean extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('boolean');
    }

    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnBoolean
    {
        $this->name = $name;
        return $this;
    }

}
