<?php

namespace App\Magis\Generators;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MagisRoutesGenerator extends MagisGenerator
{
    public static function routes($console, $name, $module = '', $template = 'app/Magis/Templates/Routes/Resource.txt', $options = [])
    {
        $webRoute = 'routes/web.php';
        $template = File::get($template);

        //make sure $name is singular
        $name = str_singular($name);

        $options = array_merge([
            '{capitalized}' => studly_case($name),
            '{slug}' => snake_case(str_plural($name)),
            '{capitalizedModule}' => ucfirst($module),
            '{moduleWithTrailingSlash}' => self::moduleWithTrailingSlash($module),
        ], $options);

        $compiled = str_replace(array_keys($options), array_values($options), $template);

        if (strpos(File::get($webRoute), $compiled) === false) {
            File::append($webRoute, $compiled);
            return $console->info('Published routes for: '.$name);
        }

        $console->info('Routes for '.$name.' already published');
    }

    public static function init($console)
    {
        self::routes($console, '', '', 'app/Magis/Templates/Routes/Init.txt');
    }

    public static function resource($console, $name, $module = '')
    {
        self::routes($console, $name, $module);
    }

    public static function content($console, $name, $module = '')
    {
        self::routes($console, $name, $module, 'app/Magis/Templates/Routes/Content.txt');
    }

    public static function category($console, $name, $module = '')
    {
        self::routes($console, $name, $module, 'app/Magis/Templates/Routes/Category.txt');
    }

    protected static function moduleWithTrailingSlash($module)
    {
        return !empty($module) ? $module.'\\' : '';
    }
}
