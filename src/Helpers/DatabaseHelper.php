<?php

namespace JocelimJr\LaravelApiGenerator\Helpers;

class DatabaseHelper
{

    /**
     * https://laravel.com/docs/10.x/migrations#available-column-types
     *
     * @return string[]
     */
    public static function getAvailableColumns(): array
    {
        return [
            'boolean',
            'dateTimeTz',
            'dateTime',
            'date',
            'decimal',
            'double',
            'float',
            'id',
            'integer',
            'string',
            'timestamp',
            'uuid',
        ];
    }

}
