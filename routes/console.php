<?php

use Illuminate\Foundation\Inspiring;
use App\Magis\Services\ConsoleService;
use App\Magis\Services\CategoryService;
use App\Magis\Generators\MagisGenerator;
use App\Magis\Generators\MagisModelGenerator;
use App\Magis\Generators\MagisRoutesGenerator;
use App\Magis\Generators\MagisControllerGenerator;
use App\Magis\Generators\MagisPublisher as Publish;
use App\Magis\Generators\MagisMigrationGenerator;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

$MAGIS_CONSOLE_NAME = '{name : The singular name of the table}';
$MAGIS_CONSOLE_MODULE = '{--module= : The name of the module}';
$MAGIS_CONSOLE_CATEGORY_OPTION = '{--no-categories : Whether this resource has a category}';
$MAGIS_CONSOLE_POLICY_OPTION = '{--no-policy : Whether a model should also include a policy}';
$MAGIS_CONSOLE_TIMESTAMP_MODIFER = '{--timestamp-modifier=0 : Adjust the seconds in migration filename to enforce an order}';
$MAGIS_CONSOLE_REVISION_OPTION = '{--no-revisions : Whether revisions should be tracked}';
$MAGIS_CONSOLE_ARCHIVE_OPTION = '{--no-archives : Whether archives should be saved}';
$MAGIS_CONSOLE_CATEGORY_REVISION_OPTION = '{--no-category-revisions : Whether the category table should have revisions}';
$MAGIS_CONSOLE_CATEGORY_ARCHIVE_OPTION = '{--no-category-archives : Whether the category table should have archives}';
$MAGIS_CONSOLE_PIVOT_OPTION = '{--no-pivot : Whether a pivot table should be created}';
$MAGIS_CONSOLE_FIELDS_OPTION = '{--fields= : Fields in the format "name:type:[required]". Important: Do not include id and name}';

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
});



/**
 * 
 * MIGRATIONS
 * 
 * Author: JC Soriano (jcsoriano.com)
 * Copyright: MagisSolutions, Inc. 
 *
 * License: You are not allowed to use any code in any file containing the name "Magis" 
 * or any file placed inside any folder containing the name "Magis" in any project not 
 * expressly owned by MagisSolutions or contractually negotiated by MagisSolutions and its 
 * Client.
 * 
 */

Artisan::command("magis:migration {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE} 
                {$MAGIS_CONSOLE_REVISION_OPTION} {$MAGIS_CONSOLE_ARCHIVE_OPTION}
                {$MAGIS_CONSOLE_CATEGORY_REVISION_OPTION} {$MAGIS_CONSOLE_CATEGORY_ARCHIVE_OPTION}
                {$MAGIS_CONSOLE_CATEGORY_OPTION} {$MAGIS_CONSOLE_PIVOT_OPTION}
                {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $timestampModifier = $this->option('timestamp-modifier');

    MagisMigrationGenerator::resource('Migration', $this, $name, $timestampModifier, $module);
    ConsoleService::revisionsArchives($this, $name, $timestampModifier);
    ConsoleService::categoryMigrations($this, $name, $module, $timestampModifier);
})->describe('Generate a content table (e.g. Post.)');

Artisan::command("magis:migration:content {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE} 
                {$MAGIS_CONSOLE_REVISION_OPTION} {$MAGIS_CONSOLE_ARCHIVE_OPTION}
                {$MAGIS_CONSOLE_CATEGORY_REVISION_OPTION} {$MAGIS_CONSOLE_CATEGORY_ARCHIVE_OPTION}
                {$MAGIS_CONSOLE_CATEGORY_OPTION} {$MAGIS_CONSOLE_PIVOT_OPTION}
                {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $timestampModifier = $this->option('timestamp-modifier');

    MagisMigrationGenerator::resource('Content', $this, $name, $timestampModifier, $module);
    ConsoleService::revisionsArchives($this, $name, $timestampModifier);
    ConsoleService::categoryMigrations($this, $name, $module, $timestampModifier);
})->describe('Generate a content table (e.g. Post.)');

Artisan::command("magis:migration:product {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE} 
                {$MAGIS_CONSOLE_REVISION_OPTION} {$MAGIS_CONSOLE_ARCHIVE_OPTION}
                {$MAGIS_CONSOLE_CATEGORY_REVISION_OPTION} {$MAGIS_CONSOLE_CATEGORY_ARCHIVE_OPTION}
                {$MAGIS_CONSOLE_CATEGORY_OPTION} {$MAGIS_CONSOLE_PIVOT_OPTION}
                {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $timestampModifier = $this->option('timestamp-modifier');
    
    MagisMigrationGenerator::resource('Product', $this, $name, $timestampModifier, $module);
    ConsoleService::revisionsArchives($this, $name, $timestampModifier);
    ConsoleService::categoryMigrations($this, $name, $module, $timestampModifier);
})->describe('Generate a product table (e.g. Product.)');


Artisan::command("magis:migration:revisions {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    MagisMigrationGenerator::revision($this, $this->argument('name'), $this->option('timestamp-modifier'));
})->describe('Generate a migration to track revisions on a table');


Artisan::command("magis:migration:archives {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    MagisMigrationGenerator::archive($this, $this->argument('name'), $this->option('timestamp-modifier'));
})->describe('Generate a migration to save archives on a table');


Artisan::command("magis:migration:pivot {first : The first table} {second : The second table} 
    {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    MagisMigrationGenerator::pivot(
        $this,
        $this->argument('first'),
        $this->argument('second'),
        $this->option('timestamp-modifier')
    );
})->describe('Generate a migration for a pivot table');



/**
 * 
 * MODELS
 * 
 * Author: JC Soriano (jcsoriano.com)
 * Copyright: MagisSolutions, Inc. 
 *
 * License: You are not allowed to use any code in any file containing the name "Magis" 
 * or any file placed inside any folder containing the name "Magis" in any project not 
 * expressly owned by MagisSolutions or contractually negotiated by MagisSolutions and its 
 * Client.
 * 
 */

Artisan::command("magis:model {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE} 
    {$MAGIS_CONSOLE_CATEGORY_OPTION}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');

    MagisModelGenerator::model($this, $name, $module);

    if (!$noCategories) {
        MagisModelGenerator::model($this, $name.'Category', $module);
    }
})->describe('Creates a MagisModel');

Artisan::command("magis:model:content {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}
                {$MAGIS_CONSOLE_CATEGORY_OPTION}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');

    if (!$noCategories) {
        MagisModelGenerator::contentWithCategory($this, $name, $module);
        $this->call('magis:model:category', ['name' => $name,
                                            '--module' => $module]);
    } else {
        MagisModelGenerator::contentWithoutCategory($this, $name, $module);
    }
})->describe('Creates a content model');

Artisan::command("magis:model:category {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}", function () {

    MagisModelGenerator::category(
        $this,
        CategoryService::appendType($this->argument('name')),
        $this->option('module')
    );
})->describe('Creates a category model');



/**
 * 
 * CONTROLLERS
 * 
 * Author: JC Soriano (jcsoriano.com)
 * Copyright: MagisSolutions, Inc. 
 *
 * License: You are not allowed to use any code in any file containing the name "Magis" 
 * or any file placed inside any folder containing the name "Magis" in any project not 
 * expressly owned by MagisSolutions or contractually negotiated by MagisSolutions and its 
 * Client.
 * 
 */

Artisan::command("magis:controller {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}
                {$MAGIS_CONSOLE_CATEGORY_OPTION}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');
    MagisControllerGenerator::resource($this, $name, $module, $noCategories);

    if (!$noCategories) {
        $this->call('magis:controller:category', ['name' => $name,
                                                '--module' => $module]);
    }
})->describe('Creates a content controller');

Artisan::command("magis:controller:content {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}
                {$MAGIS_CONSOLE_CATEGORY_OPTION}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');
    MagisControllerGenerator::content($this, $name, $module, $noCategories);

    if (!$noCategories) {
        $this->call('magis:controller:category', ['name' => $name,
                                                '--module' => $module]);
    }
})->describe('Creates a content controller');

Artisan::command("magis:controller:category {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}", function () {

    MagisControllerGenerator::category(
        $this,
        CategoryService::appendType($this->argument('name')),
        $this->option('module')
    );
})->describe('Creates a category controller');


/**
 * 
 * ROUTES
 * 
 * Author: JC Soriano (jcsoriano.com)
 * Copyright: MagisSolutions, Inc. 
 *
 * License: You are not allowed to use any code in any file containing the name "Magis" 
 * or any file placed inside any folder containing the name "Magis" in any project not 
 * expressly owned by MagisSolutions or contractually negotiated by MagisSolutions and its 
 * Client.
 * 
 */

Artisan::command("magis:routes {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE} 
    {$MAGIS_CONSOLE_CATEGORY_OPTION}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');
    MagisRoutesGenerator::resource($this, $name, $module);
    
    if (!$this->option('no-categories')) {
        $this->call('magis:routes:category', ['name' => $name,
                                            '--module' => $module]);
    }
})->describe('Creates routes for a resource');

Artisan::command("magis:routes:category {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}", function () {

    MagisRoutesGenerator::category(
        $this,
        CategoryService::appendType($this->argument('name')),
        $this->option('module')
    );
})->describe('Creates routes for a category');

Artisan::command("magis:routes:content {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}
                {$MAGIS_CONSOLE_CATEGORY_OPTION}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');

    MagisRoutesGenerator::content($this, $name, $module);
    if (!$this->option('no-categories')) {
        $this->call('magis:routes:category', ['name' => $name,
                                            '--module' => $module]);
    }
})->describe('Creates routes for a content resource');



/**
 * 
 * RESOURCES
 * 
 * Author: JC Soriano (jcsoriano.com)
 * Copyright: MagisSolutions, Inc. 
 *
 * License: You are not allowed to use any code in any file containing the name "Magis" 
 * or any file placed inside any folder containing the name "Magis" in any project not 
 * expressly owned by MagisSolutions or contractually negotiated by MagisSolutions and its 
 * Client.
 * 
 */

Artisan::command("magis:changetheworld {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}
                {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}
                {--has-categories : Whether this resource has a category}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = !$this->option('has-categories');
    $timestampModifier = $this->option('timestamp-modifier');

    $this->call('magis:migration', ['name' => $name,
                                    '--module' => $module,
                                    '--no-categories' => $noCategories,
                                    '--timestamp-modifier' => $timestampModifier + 1]);

    $this->call('magis:model', ['name' => $name,
                                '--module' => $module,
                                '--no-categories' => $noCategories]);

    $this->call('magis:controller', ['name' => $name,
                                    '--module' => $module,
                                    '--no-categories' => $noCategories]);

    $this->call('magis:routes', ['name' => $name,
                                '--module' => $module,
                                '--no-categories' => $noCategories]);
})->describe('Creates Crud for a module');

Artisan::command("magis:changetheworld:content {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}
                {$MAGIS_CONSOLE_CATEGORY_OPTION} {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');
    $timestampModifier = $this->option('timestamp-modifier');

    $this->call('magis:migration:content', ['name' => $name,
                                            '--module' => $module,
                                            '--no-categories' => $noCategories,
                                            '--timestamp-modifier' => $timestampModifier + 1]);

    $this->call('magis:model:content', ['name' => $name,
                                        '--module' => $module,
                                        '--no-categories' => $noCategories]);

    $this->call('magis:controller:content', ['name' => $name,
                                            '--module' => $module,
                                            '--no-categories' => $noCategories]);

    $this->call('magis:routes:content', ['name' => $name,
                                        '--module' => $module,
                                        '--no-categories' => $noCategories]);
})->describe('Creates Crud for a content module');

Artisan::command("magis:changetheworld:product {$MAGIS_CONSOLE_NAME} {$MAGIS_CONSOLE_MODULE}
                {$MAGIS_CONSOLE_CATEGORY_OPTION} {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    $name = $this->argument('name');
    $module = $this->option('module');
    $noCategories = $this->option('no-categories');
    $timestampModifier = $this->option('timestamp-modifier');

    $this->call('magis:migration:product', ['name' => $name,
                                            '--no-categories' => $noCategories,
                                            '--timestamp-modifier' => $timestampModifier + 1]);

    $this->call('magis:model:content', ['name' => $name,
                                        '--module' => $module,
                                        '--no-categories' => $noCategories]);

    $this->call('magis:controller:content', ['name' => $name,
                                            '--module' => $module,
                                            '--no-categories' => $noCategories]);

    $this->call('magis:routes:content', ['name' => $name,
                                        '--module' => $module,
                                        '--no-categories' => $noCategories]);
})->describe('Creates Crud for a product module');



/**
 * 
 * INSTALLS
 * 
 * Author: JC Soriano (jcsoriano.com)
 * Copyright: MagisSolutions, Inc. 
 *
 * License: You are not allowed to use any code in any file containing the name "Magis" 
 * or any file placed inside any folder containing the name "Magis" in any project not 
 * expressly owned by MagisSolutions or contractually negotiated by MagisSolutions and its 
 * Client.
 * 
 */

Artisan::command("magis:install:cms {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    $timestampModifier = $this->option('timestamp-modifier');

    $this->call('magis:changetheworld:content', ['name' => 'Page',
                                                '--module' => 'Content',
                                                '--no-categories' => true,
                                                '--timestamp-modifier' => $timestampModifier + 1]);

    $this->call('magis:changetheworld:content', ['name' => 'Post',
                                                '--module' => 'Content',
                                                '--no-categories' => false,
                                                '--timestamp-modifier' => $timestampModifier + 10]);
})->describe('Creates routes for a content resource');


/**
 * 
 * SET-UP
 * 
 * Author: JC Soriano (jcsoriano.com)
 * Copyright: MagisSolutions, Inc. 
 *
 * License: You are not allowed to use any code in any file containing the name "Magis" 
 * or any file placed inside any folder containing the name "Magis" in any project not 
 * expressly owned by MagisSolutions or contractually negotiated by MagisSolutions and its 
 * Client.
 * 
 */

Artisan::command("magis:init:roles {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}
                {$MAGIS_CONSOLE_REVISION_OPTION} {$MAGIS_CONSOLE_ARCHIVE_OPTION}", function () {

    $timestampModifier = $this->option('timestamp-modifier');
    Publish::roles($this);
    ConsoleService::revisionsArchives($this, 'Role', $timestampModifier);
})->describe('Initializes role migrations');

Artisan::command("magis:init:notifications {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}
                {$MAGIS_CONSOLE_REVISION_OPTION} {$MAGIS_CONSOLE_ARCHIVE_OPTION}", function () {

    $timestampModifier = $this->option('timestamp-modifier');
    Publish::notifications($this);
    ConsoleService::revisionsArchives($this, 'NotificationList', $timestampModifier + 1);
    $this->call('notifications:table');
})->describe('Initializes the notification migrations');

Artisan::command("magis:init:settings {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}
                {$MAGIS_CONSOLE_REVISION_OPTION} {$MAGIS_CONSOLE_ARCHIVE_OPTION}", function () {

    $timestampModifier = $this->option('timestamp-modifier');
    Publish::settings($this);
    Publish::menu($this);
    ConsoleService::revisionsArchives($this, 'Setting', $timestampModifier);
    ConsoleService::revisionsArchives($this, 'Menu', $timestampModifier);
})->describe('Create a migration for activities');

Artisan::command("magis:install {$MAGIS_CONSOLE_TIMESTAMP_MODIFER}", function () {

    $timestampModifier = $this->option('timestamp-modifier');
    
    Publish::policies($this);
    Publish::listeners($this);

    //publish routes
    MagisRoutesGenerator::init($this);
    $this->call('magis:init:settings');
    Publish::activities($this);
    $this->call('magis:init:roles', ['--timestamp-modifier' => $timestampModifier + 3]);
    $this->call('magis:init:notifications', ['--timestamp-modifier' => $timestampModifier + 1]);
    $this->call('magis:migration:archives', ['name' => 'User',
                                            '--timestamp-modifier' => $timestampModifier + 6]);

    $this->call('queue:failed-table');
    $this->call('queue:table');
    $this->call('storage:link');
})->describe('Installs activities and roles');
