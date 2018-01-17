<?php

use App\Magis\Role;
use App\Magis\MenuItem;
use App\Magis\NotificationList;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->index();
            $table->string('name');
            $table->mediumText('content')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        $developer = Role::find(1);
        $admin = Role::find(2);
        
        $adminPermissions = [
            'manage-notification_lists' => 'all',
            'list-notification_lists' => 'all',
            'view-notification_list' => 'all',
            'update-notification_list' => 'all',
            'revert-notification_list' => 'all',
        ];
        $developerPermissions = [
            'manage-notification_lists' => 'all',
            'list-notification_lists' => 'all',
            'view-notification_list' => 'all',
            'create-notification_list' => 'all',
            'update-notification_list' => 'all',
            'delete-notification_list' => 'all',
            'revert-notification_list' => 'all',
            'restore-notification_list' => 'all',
        ];
        $admin->setPermissions($adminPermissions, false);
        $developer->setPermissions($developerPermissions, false);

        $menuitem = MenuItem::create([
            'menu_id' => 1,
            'module' => 'Administration',
            'name' => 'Notification Lists',
            'link' => 'notification_lists/manage'
        ]);

        $sample = new NotificationList;
        $sample->name = 'Contact';
        $sample->content = 'Users in this notification list will receive emails whenever someone subscribes via the contact us form.';
        $sample->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification_lists');
    }
}
