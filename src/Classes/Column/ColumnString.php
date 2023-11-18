<?php

namespace JocelimJr\LaravelApiGenerator\Classes\Column;

class ColumnString extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
    }

    private ?int $length = null;

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
