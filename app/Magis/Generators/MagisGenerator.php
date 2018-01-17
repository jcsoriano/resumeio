<?php

namespace App\Magis\Generators;

use Illuminate\Support\Facades\File;
use App\Magis\Generators\MagisGeneratorResponse;

class MagisGenerator
{
    public static function build($console, $name, $template, $output, $options = [])
    {
        if (File::exists($output)) {
            $console->error('File already exists: '.$output);
            return;
        }

        $template = File::get($template);

        //make sure $name is singular
        $name = str_singular($name);

        $humanCapitalized = title_case(str_replace('_', ' ', snake_case($name)));

        $values = [
            '{humanpluralcapitalized}' => str_plural($humanCapitalized),
            '{humancapitalized}' => $humanCapitalized,
            '{pluralcapitalized}' => studly_case(str_plural($name)),
            '{capitalized}' => studly_case($name),
            '{camelcase}' => camel_case($name),
            '{lowercase}' => strtolower($name),
            '{pluralsnakecase}' => str_plural(snake_case($name)),
            '{snakecase}' => snake_case($name),
            '{plural}' => str_plural(strtolower($name)),
            '{slug}' => snake_case(str_plural($name)),
            '{singularslug}' => snake_case($name),
        ];
        $values = array_merge($values, $options);

        $compiled = str_replace(array_keys($values), array_values($values), $template);

        $dirname = dirname($output);
        if (!File::exists($dirname)) {
            File::makeDirectory($dirname, 493, true);
        }
        File::put($output, $compiled);

        $console->info('Created file: '.$output);
    }

    public static function publish($cosole, $source, $path)
    {
        File::put($path, $source);

        $console->info('Published file: '.$output);
    }

    protected static function module($module, $name, $separator = '/')
    {
        return strlen($module) > 0 ? $module.$separator.$name : $name;
    }

    protected static function moduleWithLeadingSlash($module)
    {
        return !empty($module) ? '\\'.$module : '';
    }
}
