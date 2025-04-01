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

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }


    // partie brand
    public function brands(){
        $brands = Brand::orderBy('id','desc')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand(){
        return view('admin.brands_add');
    }

    public function brand_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg,svg|max:4048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $images = $request->file('image');
        $file_extensition = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extensition;
        $this->GenerateBrandThumbailsImage($images, $file_name);
        $brand->image = $file_name;
        $brand->save(); 
        return redirect()->route('admin.brands')->with('status','Brand has been added succesfully!');
    }

    public function edit_brand($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand_edit', compact('brand'));
    }

    public function brand_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg,svg|max:4048'
        ]);
            $brand = Brand::find($request->id);
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            if($request->hasfile('image')){
                if(File::exists(public_path('uploads\brands').'/'.$brand->image))
                {
                    File::delete(public_path('uploads\brands').'/'.$brand->image);
                }
            $image = $request->file('image');
            $file_extensition = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extensition;
            $this->GenerateBrandThumbailsImage($image, $file_name);
            $brand->image = $file_name;
            }
        $brand->save(); 
        return redirect()->route('admin.brands')->with('status','Brand has been update succesfully!');

    }

    public function GenerateBrandThumbailsImage($image, $imagesName){
        $destinationPath = public_path('uploads\brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imagesName);
    }

    public function brand_delete($id){
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads\brands').'/'.$brand->image)){
            File::delete(public_path('uploads\brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.index')->with('status','Brand has deleted successfully!');
    }


    // partie categorie
    public function categories(){
        $categories = Category::orderBy('id','desc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function category_add(){
        return view('admin.categories-add');
    }

    public function category_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg,svg|max:4048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $images = $request->file('image');
        $file_extensition = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extensition;
        $this->GenerateCategoryThumbailsImage($images, $file_name);
        $category->image = $file_name;
        $category->save(); 
        return redirect()->route('admin.categories')->with('status','category has been added succesfully!');
    }

    public function GenerateCategoryThumbailsImage($image, $imagesName){
        $destinationPath = public_path('uploads\categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imagesName);
    }

    public function category_edit($id){
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg,svg|max:4048'
        ]);
            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            if($request->hasfile('image')){
                if(File::exists(public_path('uploads\categories').'/'.$category->image))
                {
                    File::delete(public_path('uploads\categories').'/'.$category->image);
                }
            $image = $request->file('image');
            $file_extensition = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extensition;
            $this->GenerateBrandThumbailsImage($image, $file_name);
            $category->image = $file_name;
            }
        $category->save(); 
        return redirect()->route('admin.categories')->with('status','Brand has been update succesfully!');

    }

    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads\categories').'/'.$category->image)){
            File::delete(public_path('uploads\categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Brand has deleted successfully!');
    }

    // products part
    public function products()
    {
        $products = Product::orderBy('created_at','DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add(){
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories','brands'));
    }

    public function product_store(Request $request){
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug',
            'short_description'=>'required',
            'description'=>'required|',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'required|mimes:png,jpg,jpeg,svg|max:4048',
            'category_id'=>'required',
            'brand_id'=>'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timstamp = Carbon::now()->timestamp;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $current_timstamp . '.' . $image->extension();
            $this->GenerateProductThumbailsImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_image = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            $allowedfileExtion = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);

                if($gcheck){
                    $gfileName = $current_timstamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbailsImage($file, $gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_image = implode(',',$gallery_arr);
        }
        $product->images = $gallery_image;
        $product->save();
        return redirect()->route('admin.products')->with('staus','Product has been added successfullu!');
    }

    public function GenerateProductThumbailsImage($image, $imageName){
        $destinationPathThumbnails = public_path('uploads\products\thumbnails');
        $destinationPath = public_path('uploads\products');
        $img = Image::read($image->path());

        $img->cover(540,689,"top");
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnails.'/'.$imageName);
    }

    public function product_edit($id){
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderby('name')->get();
        $brands = Brand::select('id','name')->orderby('name')->get();
        return view('admin.product-edit', compact('product','categories','brands'));
    }

    public function product_update(Request $request){
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug,' . $request->id,
            'short_description'=>'required',
            'description'=>'required|',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'mimes:png,jpg,jpeg,svg|max:4048',
            'category_id'=>'required',
            'brand_id'=>'required'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timstamp = Carbon::now()->timestamp;
        if($request->hasFile('image')){

            if(File::exists(public_path('uploads\products').'/'.$product->image)){
                File::delete(public_path('uploads\products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads\products\thumbnails').'/'.$product->image)){
                File::delete(public_path('uploads\products\thumbnails').'/'.$product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timstamp . '.' . $image->extension();
            $this->GenerateProductThumbailsImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_image = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            foreach(explode(',',$product->images) as $ofile)
            {
                if(File::exists(public_path('uploads\products').'/'.$ofile)){
                File::delete(public_path('uploads\products').'/'.$ofile);
                }
                if(File::exists(public_path('uploads\products\thumbnails').'/'.$ofile)){
                    File::delete(public_path('uploads\products\thumbnails').'/'.$ofile);
                }
            }
            $allowedfileExtion = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);

                if($gcheck){
                    $gfileName = $current_timstamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbailsImage($file, $gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_image = implode(',',$gallery_arr);
            $product->images = $gallery_image;
        }
        $product->save();
        return redirect()->route('admin.products')->with('status','product update seccessfully');

    }
}
