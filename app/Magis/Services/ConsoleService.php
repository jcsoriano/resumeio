<?php

namespace App\Magis\Services;

class ConsoleService
{
    public static function revisionsArchives($console, $name, $timestampModifier)
    {
        if (!$console->option('no-revisions')) {
            $console->call('magis:migration:revisions', ['name' => $name,
                                                    '--timestamp-modifier' => $timestampModifier + 1]);
        }
        if (!$console->option('no-archives')) {
            $console->call('magis:migration:archive', ['name' => $name,
                                                    '--timestamp-modifier' => $timestampModifier + 2]);
        }
    }

    public static function categoryMigrations($console, $name, $module, $timestampModifier)
    {
        $noPivot = $console->option('no-pivot');
        $pivotTimestampModifier = $timestampModifier + 10;

        if (!$console->option('no-categories')) {
            $categoryName = $name.'Category';
            $args = [
                'name' => $categoryName,
                '--timestamp-modifier' => $timestampModifier + 3,
                '--no-categories' => true
            ];
            if ($module) {
                $args['--module'] = $module;
            }
            if ($console->option('no-category-revisions')) {
                $args['--no-revisions'] = true;
            }
            if ($console->option('no-category-archives')) {
                $args['--no-archives'] = true;
            }
            $console->call('magis:migration:content', $args);

            if (!$noPivot) {
                $console->call('magis:migration:pivot', [
                    'first' => $name,
                    'second' => $categoryName,
                    '--timestamp-modifier' => $pivotTimestampModifier
                ]);
            }
        }
    }
}
