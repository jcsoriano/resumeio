<?php

namespace App\Magis\Services;

class CategoryService
{
    public static function appendType($name, $type = 'Category')
    {
        $type = 'Category';
        $len = strlen($type);
        if (substr($name, -$len) !== $type) {
            $name .= 'Category';
        }
        return $name;
    }
}
