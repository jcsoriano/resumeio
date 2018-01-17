<?php

use App\User;
use App\Magis\Role;
use App\Magis\Menu;
use App\Magis\Permission;
use Illuminate\Support\Facades\Schema;
use App\Magis\Services\PermissionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');

            $table->primary(['role_id', 'user_id']);
        });

        //assign to magis developer on-install
        $magis = User::first(); //magis user
        $developer = Role::first(); //developer role
        $admin = Role::find(2); //admin role
        $developer->assignTo($magis);
        $admin->assignTo($magis);

        $developer->setPermissions([
            'manage-roles' => 'all',
            'create-role' => 'all',
            'update-role' => 'all',
            'delete-role' => 'all',
            'revert-role' => 'all',
            'restore-role' => 'all',
            'manage-users' => 'all',
            'create-user' => 'all',
            'update-user' => 'all',
            'delete-user' => 'all',
            'restore-user' => 'all',
            'settings' => 'all',
        ]);

        $adminMenu = Menu::first();

        $adminMenu->insertItems([
            [
                'module' => 'Administration',
                'slug' => 'roles',
                'name' => 'Roles',
                'link' => 'roles/manage'
            ],
            [
                'module' => 'Administration',
                'slug' => 'users',
                'name' => 'Users',
                'link' => 'users/manage'
            ],
            [
                'module' => 'Administration',
                'slug' => 'menus',
                'name' => 'Menus',
                'link' => 'menus/manage'
            ],
        ]);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
    }
}
