<?php

namespace App\Magis;

use App\Magis\MenuItem;
use App\Magis\Traits\Models\HasPhoto;
use App\Magis\Traits\Models\SavesSlug;
use App\Magis\Traits\Models\HasRevisions;
use Illuminate\Database\Eloquent\Builder;
use App\Magis\Traits\Models\RecordsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;

class Menu extends MagisModel
{
    use RecordsActivity, SavesSlug, SavesCreatedUpdatedBy, HasPhoto, HasRevisions;

    protected $fillable = ['photo', 'name', 'description'];

    const PLURAL = 'menus';
    const SINGULAR = 'menu set';
    const TITLE = 'Menus';
    const SLUG = 'menus';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public static function editableFields(Menu $menu = null)
    {
        return [
            'photo' => 'photo',
            'name' => 'string',
            'description' => 'text',
        ];
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public static function titleButtons()
    {
        return [];
    }

    /**
     * Insert Menuitems to attached to this model
     *
     * @param  array  $items
     * @return void
     */
    public function insertItems(array $items, $delete = true)
    {
        if ($delete) {
            $this->menuitems()->delete();
        }
        $this->menuitems()->createMany($items);
    }

    /**
     * Displays items of a menu, grouped by its module
     * @param  String $slug slug of the menu set
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function groupedByModule(String $slugString)
    {
        $menuItems = self::with('menuItems')            //eager load
                            ->findBySlug($slugString)   //get the menu that has slug
                            ->menuItems;                //get items of matched menuset
        return $menuItems->groupBy('module');           //group them by module
    }
}
