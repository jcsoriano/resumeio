<?php

namespace App\Magis;

use App\User;
use App\Magis\MagisModel;
use App\Magis\Traits\Models\SavesSlug;
use Illuminate\Broadcasting\PrivateChannel;
use App\Magis\Traits\Models\RecordsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;

class NotificationList extends MagisModel
{
    use RecordsActivity, SavesSlug, SavesCreatedUpdatedBy, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'content',
    ];

    const PLURAL = 'notification_lists';
    const SINGULAR = 'notification_list';
    const SLUG = 'notification_lists';
    const TITLE = 'Notification Lists';
    const MODULE = 'Magis';

    const RELATIONS = [
        'users' => 'many'
    ];

    const RELATION_ATTRIBUTE_DISPLAY = [
        'users' => 'name_email'
    ];

    public static function manageColumns()
    {
        return [
            'name' => 'Name',
            'users' => 'Users',
        ];
    }

    public function channels()
    {
        $channels = [];
        foreach ($this->users()->get() as $user) {
            $channels[] = new PrivateChannel('App.User.' . $user->id);
        }
        return $channels;
    }

    public static function manageColumnsDefault()
    {
        return ['name'];
    }

    public static function editableFields(NotificationList $notificationList = null)
    {
        return [
            'name' => 'string',
            'users' => 'many-relation',
            'content' => 'mediumText',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
