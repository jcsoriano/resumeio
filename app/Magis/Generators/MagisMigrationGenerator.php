<?php

namespace App\Magis\Generators;

use Illuminate\Support\Str;

class MagisMigrationGenerator extends MagisGenerator
{
    public static function build($console, $name, $template, $timestampModifier = 0, $suffix = 'table', $action = 'create', $options = [])
    {
        // $name = strtolower($name);
        $singularTable = snake_case($name);
        $table = str_plural($singularTable);
        $output = 'database/migrations/'.self::datePrefix($timestampModifier)."_{$action}_{$table}_{$suffix}.php";
        $options = array_merge([
            '{singular_table}' => $singularTable,
            '{table}' => $table,
        ], $options);
        return parent::build($console, $name, $template, $output, $options);
    }

    public static function resource($type, $console, $name, $timestampModifier = 0, $module = '')
    {
        $capitalizedModule = ucfirst($module);
        $options = [
            '{capitalizedmodule}' => !empty($module) ? $capitalizedModule : ucfirst($name),
            '{moduleOption}' => empty($module) ? '' : "'module' => '".$capitalizedModule."',",
            '{moduleWithLeadingSlash}' => parent::moduleWithLeadingSlash($module)
        ];
        return self::build(
            $console,
            $name,
            'App/Magis/Templates/Migrations/'.$type.'.txt',
            $timestampModifier,
            'table',
            'create',
            $options
        );
    }

    public static function revision($console, $name, $timestampModifier = 0)
    {
        return self::build(
            $console,
            $name,
            'App/Magis/Templates/Migrations/Revisions.txt',
            $timestampModifier,
            'revisions'
        );
    }

    public static function archive($console, $name, $timestampModifier = 0)
    {
        return self::build(
            $console,
            $name,
            'App/Magis/Templates/Migrations/Archives.txt',
            $timestampModifier,
            'archives'
        );
    }

    public static function pivot($console, $first, $second, $timestampModifier = 0)
    {
        // $tables = array_map(function($val) {
        //     return Str::singular(strtolower($val));
        // }, $tables);
        // sort($tables);
        // $studly = implode('_', $tables);
        //
        $firstSingular = snake_case($first);
        $secondSingular = snake_case($second);
        $pivotTable = $firstSingular.'_'.$secondSingular;

        $options = [
            '{pivotTable}' => $pivotTable,
            '{camelCapitalizedPlural}' => ucfirst(str_plural(camel_case($pivotTable))),
            '{firstSingular}' => $firstSingular,
            '{firstPlural}' => str_plural($firstSingular),
            '{secondSingular}' => $secondSingular,
            '{secondPlural}' => str_plural($secondSingular),
        ];

        return self::build(
            $console,
            $pivotTable,
            'App/Magis/Templates/Migrations/Pivot.txt',
            $timestampModifier,
            'table',
            'create',
            $options
        );
    }

    public static function datePrefix($timestampModifier = 0)
    {
        return date('Y_m_d_His', strtotime('+'.$timestampModifier.' seconds'));
    }
}
