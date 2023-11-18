<?php

namespace JocelimJr\LaravelApiGenerator\Helpers;

class PathHelper
{

    /**
     * @param string|array|null $path
     * @return string
     */
    public static function basePath(string|array $path = null): string
    {
        $_p = '';

        if($path) {
            if (is_array($path)) {
                $_p = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path);
            }else{
                if (str_starts_with(DIRECTORY_SEPARATOR, $path)) {
                    $_p = $path;
                }else{
                    $_p = DIRECTORY_SEPARATOR . $path;
                }
            }
        }

        return implode('', [
            getcwd(),
            rtrim($_p, '\/')
        ]);
    }
}
