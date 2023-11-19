<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnId extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setName('id');
        $this->setType('id');
    }

    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnId
    {
        $this->name = $name;
        return $this;
    }

}
