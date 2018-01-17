<?php

namespace App\Magis\Generators;

use Illuminate\Support\Facades\File;
use App\Magis\Generators\MagisModelGenerator;
use App\Magis\Generators\MagisGeneratorResponse;

class MagisPublisher
{
    public static function publish($console, $in, $out, $isDirectory = false)
    {
        if (File::exists($out)) {
            $console->line('File already exists (will not overwrite): '.$out);
        } else {
            if ($isDirectory) {
                $console->info('is Directory');
                File::copyDirectory($in, $out);
            } else {
                $dirname = dirname($out);
                if (!File::exists($dirname)) {
                    File::makeDirectory($dirname, 493, true);
                }
                File::copy($in, $out);
            }

            $console->info('Published file: '.$out);
        }
    }

    // public static function directories($console)
    // {
    //     self::publish($console, 'App/Magis/Publish/Controllers/Magis', 'App/Http/Controllers/Magis', true);
    //     self::publish($console, 'App/Magis/Publish/Views/magis', 'resources/views/magis', true);
    //     self::publish($console, 'App/Magis/Publish/Public/Magis', 'public/Magis', true);
    // }

    public static function migration($console, $in, $out = '')
    {
        self::publish(
            $console,
            'App/Magis/Publish/Migrations/'.$in,
            'database/migrations/'.($out ?: $in)
        );
    }

    public static function controller($console, $in, $out = '')
    {
        self::publish(
            $console,
            'App/Magis/Publish/Controllers/'.$in,
            'App/Http/Controllers/Magis/'.($out ?: $in)
        );
    }

    public static function listeners($console)
    {
        $eventServiceProvider = 'App\Providers\EventServiceProvider.php';
        $contents = File::get($eventServiceProvider);
        $search = '    protected $listen = [';
        $insert = File::get('App\Magis\Publish\EventServiceProvider.txt');

        if (! str_contains($contents, $insert)) {
            $replace = "$search\n$insert";
            File::put($eventServiceProvider, str_replace($search, $replace, $contents));

            return $console->info('Registered Listeners');
        }

        $console->line('Listeners already registered');
    }

    public static function roles($console)
    {
        self::migration($console, '2014_10_11_000000_create_roles_table.php');
        self::migration($console, '2014_10_13_100000_insert_magis_user.php');
        self::migration($console, '2014_10_13_200000_create_role_user_table.php');
        self::migration($console, '2014_10_13_300000_create_custompermissions_table.php');
        self::migration($console, '2014_10_13_400000_create_socialaccounts_table.php');
    }

    public static function activities($console)
    {
        self::migration($console, '2014_10_09_000000_create_activities_table.php');
    }

    public static function settings($console)
    {
        self::migration($console, '2014_10_14_000000_create_settings_table.php');
    }

    public static function menu($console)
    {
        self::migration($console, '2014_10_11_100000_create_menus_table.php');
        self::migration($console, '2014_10_11_200000_create_menu_items_table.php');
    }

    public static function notifications($console)
    {
        MagisModelGenerator::register($console, 'NotificationList', 'Magis');
        self::migration($console, '2014_10_14_100000_create_notification_lists_table.php');
        self::migration($console, '2014_10_14_200000_create_notification_list_users_table.php');
    }

    public static function policies($console)
    {
        MagisModelGenerator::register($console, 'Setting', 'Magis');
        MagisModelGenerator::register($console, 'Menu', 'Magis');
        MagisModelGenerator::register($console, 'MenuItem', 'Magis');
        MagisModelGenerator::register($console, 'Role', 'Magis');
        MagisModelGenerator::register($console, 'User', 'Magis');
    }
}
