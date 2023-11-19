<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use JsonSerializable;
use ReflectionClass;

abstract class AbstractDTO implements JsonSerializable
{
    private array $_jsonIgnore = ['_jsonIgnore'];

    /**
     * @param mixed $value
     * @return AbstractDTO
     */
    public function addToJsonIgnore(string|array $value): AbstractDTO
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $this->_jsonIgnore = array_unique(array_merge($this->_jsonIgnore, $value));

        return $this;
    }

    /**
     * @param mixed $value
     * @return AbstractDTO
     */
    public function rmToJsonIgnore(string|array $value): AbstractDTO
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $this->_jsonIgnore = array_diff($this->_jsonIgnore, $value);

        return $this;
    }

    public function jsonSerialize(): array
    {
        $properties = array();

        $rc = new ReflectionClass($this);

        do {
            $rp = array();

            foreach ($rc->getProperties() as $p) {
                $p->setAccessible(true);

                if (in_array($p->getName(), $this->_jsonIgnore)) {
                    continue;
                }

                $rp[$p->getName()] = $p->getValue($this);
            }

            $properties = array_merge($rp, $properties);
        } while ($rc = $rc->getParentClass());

        return $properties;
    }
}
