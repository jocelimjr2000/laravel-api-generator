<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnUuid extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('uuid');
    }

    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnUuid
    {
        $this->name = $name;
        return $this;
    }

}
