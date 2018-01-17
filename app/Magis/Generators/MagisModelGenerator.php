<?php

namespace App\Magis\Generators;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MagisModelGenerator extends MagisGenerator
{
    public static function model($console, $name, $module = '', $template = 'app/Magis/Templates/Models/Model.txt', $options = [])
    {
        $name = studly_case($name);
        $moduleName = parent::module($module, $name);
        $output = $module == 'Magis' ? 'app/Magis/'.$name.'.php' : 'app/Models/'.$moduleName.'.php';
        $options = array_merge([
            '{moduleWithLeadingSlash}' => parent::moduleWithLeadingSlash($module),
            '{namespace}' => $module == 'Magis' ? 'App\Magis' : 'App\Models\\'.$module,
            '{module}' => $module,
            '{moduleAttribute}' => !empty($module) ? 'const MODULE = \''.$module.'\';' : '',
        ], $options);
        parent::build($console, $name, $template, $output, $options);
        self::policy($console, $name, $module);
        self::register($console, $name, $module);
    }

    public static function contentWithCategory($console, $name, $module = '')
    {
        return self::model($console, $name, $module, 'app/Magis/Templates/Models/ContentWithCategory.txt');
    }

    public static function contentWithoutCategory($console, $name, $module = '')
    {
        return self::model($console, $name, $module, 'app/Magis/Templates/Models/ContentWithoutCategory.txt');
    }

    public static function category($console, $name, $module = '')
    {
        $type = 'Category';
        $len = strlen($type);
        $mainResource = substr($name, 0, -$len);
        $capitalizedMainResource = studly_case($mainResource);
        return self::model($console, $name, $module, 'app/Magis/Templates/Models/Category.txt', [
            '{capitalizedMainResource}' => $capitalizedMainResource,
            '{lowercasePluralMainResource}' => str_plural(strtolower($mainResource)),
            '{categoryTitle}' => title_case(str_replace('_', ' ', snake_case($name))),
        ]);
    }

    public static function policy($console, $name, $module = '')
    {
        $name = studly_case($name);
        $template = 'app/Magis/Templates/Policies/Policy.txt';
        $output = 'app/Policies/'.parent::module($module, $name).'Policy.php';
        $options = [
            '{moduleWithLeadingSlash}' => parent::moduleWithLeadingSlash($module),
            '{modelNamespace}' => parent::module($module, $name, $separator = '\\')
        ];
        return parent::build($console, $name, $template, $output, $options);
    }

    public static function register($console, $name, $module = '')
    {
        $authServiceProvider = 'App\Providers\AuthServiceProvider.php';
        $contents = File::get($authServiceProvider);
        $search = '    protected $policies = [';
        $moduleName = ($name != 'User') ? parent::module($module, $name, '\\') : 'User';
        $insert = "        'App\\".self::modelFolder($module, $name, '\\') . $moduleName
            . "' => 'App\\" . self::policyFolder($module, $name, '\\') . $name . "Policy',";
        
        if (strpos($contents, $insert) === false) {
            $replace = "$search\n$insert";
            File::put($authServiceProvider, str_replace($search, $replace, $contents));

            return $console->info('Registered Policy for: '.$name);
        }

        $console->line('Policy for '.$name.' already registered');
    }

    private static function modelFolder($module, $name, $separator = '/')
    {
        return $module != 'Magis' && $name != 'User' ? 'Models'.$separator : '';
    }

    private static function policyFolder($module, $name, $separator = '/')
    {
        return $module != 'Magis' && $name != 'User'
            ? 'Policies' . $separator . $module . $separator
            : 'Magis' . $separator . 'Policies' . $separator;
    }
}
