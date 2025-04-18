<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class ShopController extends Controller
{
    public function index(){
        $products = Product::orderBy('created_at','DESC')->paginate(12);
        return view('shops.shop',compact('products'));
    }

    public function product_detail($product_slug){
        $product = Product::where('slug',$product_slug)->first();
        return view('shops.details',compact('product'));
    }
}
