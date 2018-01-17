<?php

use App\Magis\Menu;
use App\Magis\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('photo')->nullable();
            $table->float('photo_aspect_ratio')->nullable();
            $table->string('slug');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('created_by')->unsigned()->default(0);
            $table->integer('updated_by')->unsigned()->default(0);
            $table->timestamps();
        });

        $adminMenu = new Menu;
        $adminMenu->name = 'Admin';
        $adminMenu->save();

        $publicMenu = new Menu;
        $publicMenu->name = 'Public';
        $publicMenu->save();

        $developerRole = Role::first(); //developer role
        $adminRole = Role::find(2); //admin role
        $adminPermissions = [
            'manage-menus' => 'all',
            'list-menus' => 'all',
            'view-menu' => 'all',
            'update-menu' => 'all',
            'revert-menu' => 'all',
        ];
        $developerPermissions = [
            'manage-menus' => 'all',
            'list-menus' => 'all',
            'view-menu' => 'all',
            'create-menu' => 'all',
            'update-menu' => 'all',
            'delete-menu' => 'all',
            'revert-menu' => 'all',
            'restore-menu' => 'all',
        ];
        $adminRole->setPermissions($adminPermissions, false);
        $developerRole->setPermissions($developerPermissions, false);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
