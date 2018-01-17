<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Magis\MagisContentController;

class ProductController extends MagisContentController
{
    protected $model = Product::class;
    protected $category = 'product_categories';
}
