<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnDate extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('date');
    }

    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnDate
    {
        $this->name = $name;
        return $this;
    }

}
