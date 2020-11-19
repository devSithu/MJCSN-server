<?php

namespace App\Lib\CpsBlade;

use Illuminate\Support\Str;

class Directive
{

    public static function dataTable($instance, $arg1 = null, $arg2 = [])
    {
        if ($arg1 && is_string($arg1)) {
            $class = "App\\DataTables\\$arg1";
            $instance = new $class;
            return $instance->render($arg2);
        }
        return $instance->render($arg1 ?? []);
    }

    public static function css($file)
    {
        $path = static::getCssPath($file);
        return '<link href="' . auto_version($path) . '" rel="stylesheet">';
    }

    public static function cssIf($file)
    {
        $path = static::getCssPath($file);
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
            return static::css($path);
        }
    }

    protected static function getCssPath($file)
    {
        $path = $file;
        if (!Str::startsWith($path, '/')) {
            $path = env('PUBLIC_PATH') . '/stylesheets/' . str_replace('.', '/', $path);
        }
        if (!Str::endsWith($path, '.css')) {
            $path .= '.css';
        }
        return $path;
    }

    public static function readCustomFileIf($file)
    {
        $path1 = $file;
        $content = '';
        if (!Str::startsWith($path1, '/')) {
            $path1 = '/customize/' . str_replace('.', '/', $path1) . '.html';
        }
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path1)) {
            $content .= file_get_contents($_SERVER['DOCUMENT_ROOT'] . $path1) . ' ';
        }
        $path2 = $file;
        if (!Str::startsWith($path2, '/')) {
            $path2 = '/customize/' . str_replace_first('.', '/', $path2) . '.html';
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path2)) {
                $content .= file_get_contents($_SERVER['DOCUMENT_ROOT'] . $path2) . ' ';
            }
        }

        return $content;
    }

}
