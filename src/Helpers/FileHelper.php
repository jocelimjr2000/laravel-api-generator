<?php

namespace JocelimJr\LaravelApiGenerator\Helpers;

class FileHelper
{

    /**
     * @param $stub
     * @return string
     */
    public static function getStubPath($stub): string
    {
        return file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'stubs', $stub . '.stub']));
    }
}
