<?php

namespace App\Magis\Generators;

use Illuminate\Support\Str;

class MagisControllerGenerator extends MagisGenerator
{
    public static function controller($console, $name, $module = '', $template = 'app/Magis/Templates/Controllers/Controller.txt', $options = [])
    {
        $name = studly_case($name);
        // $template = ;
        $moduleName = parent::module($module, $name);
        $output = 'app/Http/Controllers/'.$moduleName.'Controller.php';
        $options = array_merge([
            '{moduleWithLeadingSlash}' => parent::moduleWithLeadingSlash($module),
        ], $options);
        return parent::build($console, $name, $template, $output, $options);
    }

    public static function resource($console, $name, $module = '', $noCategories = true)
    {
        $options = [
            '{categoryAttribute}' => self::categoryAttribute($name, $noCategories)
        ];
        return self::controller($console, $name, $module, 'app/Magis/Templates/Controllers/Controller.txt', $options);
    }

    public static function content($console, $name, $module = '', $noCategories = false)
    {
        $options = [
            '{categoryAttribute}' => self::categoryAttribute($name, $noCategories)
        ];
        return self::controller($console, $name, $module, 'app/Magis/Templates/Controllers/Content.txt', $options);
    }

    public static function category($console, $name, $module = '')
    {
        $type = 'Category';
        $len = strlen($type);
        $mainResource = substr($name, 0, -$len);
        return self::controller($console, $name, $module, 'app/Magis/Templates/Controllers/Category.txt', [
            '{capitalizedMainResource}' => ucfirst($mainResource),
            '{lowercasePluralMainResource}' => str_plural(strtolower($mainResource))
        ]);
    }

    private static function categoryAttribute($name, $noCategories = false)
    {
        return $noCategories ? '' : 'protected $category = \''.strtolower($name).'_categories\';';
    }
}
