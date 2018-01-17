<?php

namespace App\Magis;

use App\Magis\Menu;
use App\Magis\Traits\Models\HasPhoto;
use App\Magis\Traits\Models\SavesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Magis\Traits\Models\SavesCreatedUpdatedBy;

class MenuItem extends MagisModel
{
    use SavesSlug, SavesCreatedUpdatedBy, HasPhoto;

    protected $fillable = ['menu_id', 'module', 'name', 'link'];

    const PLURAL = 'menuitems';
    const SINGULAR = 'menu item';
    const TITLE = 'Menu Items';
    const SLUG = 'menu-items';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public static function manageColumns()
    {
        return [
            'module' => 'Module',
            'name' => 'Name',
            'link' => 'Link',
        ];
    }

    public static function manageColumnsDefault()
    {
        return ['module', 'name', 'link'];
    }

    public static function editableFields(MenuItem $menuItem = null)
    {
        return [
            'module' => 'string',
            'name' => 'string',
            'link' => 'string',
        ];
    }

    public function menus()
    {
        $this->belongsTo(Menu::class);
    }

    /**
     * Options
     *  - check_permission
     *  - wrapper_tag
     *  - wrapper_class
     *  - link_class
     *  - active_class
     *
     * @param  array $options
     * @return String
     */
    public function htmlLink($options = array()) : String
    {
        $check_permission = $options['check_permission'] ?? false;

        //before anything, check permissions
        if ($check_permission && !PermissionService::showMenuItem($this)) {
            return '';
        }

        $wrapperTag = $options['wrapper_tag'] ?? 'li';
        $wrapperClass = $options['wrapper_class'] ?? '';
        $linkClass = $options['link_class'] ?? '';
        $activeClass = $options['active_class'] ?? 'active';


        return '<'.$wrapperTag.' class="'.(str_contains(request()->url(), str_replace('/manage', '', $this->link)) ? $activeClass : '').' '.$wrapperClass.'">'
                    .'<a href="'.url($this->link).'" class="'.$linkClass.'">'
                        .$this->name
                    .'</a>'
                .'</'.$wrapperTag.'>';
    }
}
