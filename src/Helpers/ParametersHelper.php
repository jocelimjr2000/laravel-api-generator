<?php

namespace JocelimJr\LaravelApiGenerator\Helpers;

use Exception;

class ParametersHelper
{

    /**
     * @param string $ref
     * @param bool $nullable
     * @param bool $swagger
     * @return string
     */
    public static function getParameterType(string $ref, bool $nullable = true, bool $swagger = false): string
    {
        $type = 'string';

        if(in_array($ref, ['integer', 'id'])) $type = ($swagger ? 'integer' : 'int');

        if(in_array($ref, ['boolean', 'bool'])) $type = ($swagger ? 'boolean' : 'bool');

        if(in_array($ref, ['float', 'double'])) $type = ($swagger ? 'number' : 'float');

        if($swagger) return $type;

        return ($nullable ? '?' : '') . $type;
    }

    /**
     * @param string $ref
     * @param string $prefixSuffix
     * @return mixed
     * @throws Exception
     */
    public static function generateDataByType(string $ref, string $prefixSuffix = "", mixed $value = null): mixed
    {
        $type = self::getParameterType($ref, false);

        if($type == 'int') return ((int) $value ?: mt_rand(1, 150));

        if($type == 'bool') return ((bool) $value ?: (random_int(0, 1) ? 'true' : 'false'));

        if($type == 'float') return (float) ($value ?: (round(random_int(0, 100 - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX), 2)));

        if($ref == 'timestamp' || $ref == 'dateTime') return $prefixSuffix . ($value ?: date("Y-m-d H:i:s.v")) . $prefixSuffix;

        if($ref == 'date') return $prefixSuffix . ($value ?: date("Y-m-d")) . $prefixSuffix;

        $length = 10;

        return $prefixSuffix .
            ($value ?: substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length)) .
            $prefixSuffix;
    }

    /**
     * @param $content
     * @param $start
     * @param $end
     * @return array
     */
    public static function getBetween($content, $start, $end): array
    {
        $n = explode($start, $content);
        $result = [];
        foreach ($n as $val) {
            $pos = strpos($val, $end);
            if ($pos !== false) {
                $v = substr($val, 0, $pos);

                $result[$start . $v . $end] = trim($v);
            }
        }
        return $result;
    }
}
