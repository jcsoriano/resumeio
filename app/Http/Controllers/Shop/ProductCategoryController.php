<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use App\Http\Controllers\Magis\MagisCategoryController;

class ProductCategoryController extends MagisCategoryController
{
    protected $model = ProductCategory::class;
    protected $mainResource = 'products';
}
