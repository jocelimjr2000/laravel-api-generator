<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnFloat extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('float');
    }

    private ?string $name = null;
    private int $total = 8;
    private int $places = 2;
    private bool $unsigned = false;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnFloat
    {
        $this->name = $name;
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): ColumnFloat
    {
        $this->total = $total;
        return $this;
    }

    public function getPlaces(): int
    {
        return $this->places;
    }

    public function setPlaces(int $places): ColumnFloat
    {
        $this->places = $places;
        return $this;
    }

    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function setUnsigned(bool $unsigned): ColumnFloat
    {
        $this->unsigned = $unsigned;
        return $this;
    }

}
