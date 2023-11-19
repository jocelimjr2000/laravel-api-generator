<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnDateTime extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('dateTime');
    }

    private ?string $name = null;
    private int $precision = 0;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnDateTime
    {
        $this->name = $name;
        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): ColumnDateTime
    {
        $this->precision = $precision;
        return $this;
    }
}
