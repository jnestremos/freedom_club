<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        //dd($request);

        $this->validate($request, [
            'product_name' => 'required',
            'product_type' => 'required',
            'product_size' => 'required',
            'product_price' => 'required',
            'product_color' => 'required',
            // 'product_image' => 'required|image',
        ]);

        if (count(Product::all()) > 0) {
            //dd(DB::table('prod_name_color')->get());
            $validate = true;
            foreach (Product::all() as $product) {
                if (
                    strtolower($request->product_name) == strtolower($product->prod_name) && $request->product_type == $product->prod_type
                    && $request->product_size == $product->prod_size && $request->product_size == $product->prod_size
                    && $request->product_price == $product->prod_price && $request->product_price == $product->prod_price
                    && $request->product_color == $product->prod_color
                ) {
                    $validate = false;
                    break;
                } else {
                    $validate = true;
                }
            }
            if ($validate == true) {
                $product = Product::create([
                    'product_number' => rand(),
                    'prod_name' => $request->product_name,
                    'prod_type' => $request->product_type,
                    'prod_size' => $request->product_size,
                    'prod_price' => $request->product_price,
                    'prod_color' => $request->product_color,
                    'prod_status' => false
                ]);
                // $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
                // $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // $extension = $request->file('product_image')->getClientOriginalExtension();
                // $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
                // $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);


                if (DB::table('prod_name_color')->where('prod_name', $product->prod_name)->where('prod_color', $product->prod_color)->where('prod_type', $product->prod_type)->first() == null) {
                    DB::table('prod_name_color')->insert([
                        'product_id' => $product->id,
                        'prod_name' => $product->prod_name,
                        'prod_type' => $product->prod_type,
                        'prod_color' => $product->prod_color,
                    ]);
                    $prod_name_color_id = DB::table('prod_name_color')->where('prod_name', $product->prod_name)->where('prod_color', $product->prod_color)->where('prod_type', $product->prod_type)->first()->id;
                    DB::table('product_images')->insert([
                        'prod_name_color_id' => $prod_name_color_id,
                        'product_image' => 'no-image.jpg',
                        'image_main' => false,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
                return redirect()->route('product.images');
            } else {
                return redirect()->route('dashboard.products')->with('error', 'Record was already added!');
            }
        } else {
            $product = Product::create([
                'product_number' => rand(),
                'prod_name' => $request->product_name,
                'prod_type' => $request->product_type,
                'prod_size' => $request->product_size,
                'prod_price' => $request->product_price,
                'prod_color' => $request->product_color,
                'prod_status' => false
            ]);
            DB::table('prod_name_color')->insert([
                'product_id' => $product->id,
                'prod_name' => $product->prod_name,
                'prod_type' => $product->prod_type,
                'prod_color' => $product->prod_color,
            ]);
            $prod_name_color_id = DB::table('prod_name_color')->where('product_id', $product->id)->where('prod_name', $product->prod_name)->where('prod_color', $product->prod_color)->first()->id;
            // $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
            // $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // $extension = $request->file('product_image')->getClientOriginalExtension();
            // $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            // $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);
            DB::table('product_images')->insert([
                'prod_name_color_id' => $prod_name_color_id,
                'product_image' => 'no-image.jpg',
                'image_main' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);


            return redirect()->route('product.images');
        }
    }



    public function update(Request $request, $id)
    {
        $status = 0;
        if ($request->setStatus == 'true') {
            $status = 1;
        } else {
            $status = 0;
        }

        if ($status != Product::find($id)->prod_status) {
            Product::find($id)->update([
                'prod_status' => $status
            ]);
        }
        return redirect()->route('dashboard.products');
    }





    public function delete($id)
    {
        //dd(DB::table('prod_name_color')->where('prod_type', Product::find($id)->prod_type)->first()->id);
        $prod_name_color_id = DB::table('prod_name_color')->where('prod_name', Product::find($id)->prod_name)->where('prod_color', Product::find($id)->prod_color)->where('prod_type', Product::find($id)->prod_type)->first()->id;
        if (DB::table('product_images')->where('prod_name_color_id', $prod_name_color_id)->where('product_image', 'no-image.jpg')->first() == null) {
            //$product_image = DB::table('product_images')->where('product_id', $id)->first()->product_image;
            $product_image = DB::table('product_images')->where('prod_name_color_id', $prod_name_color_id)->first()->product_image;
            Storage::delete('public/product_images/' . $product_image);
        }
        Product::find($id)->forceDelete();
        return redirect()->route('dashboard.products');
    }
    //{prod_type}/{prod_name}/{prod_color}/{prod_size}
    public function show($type, $name, $color, $size)
    {
        $product = DB::table('home_products_queue')->where('prod_name', $name)->where('prod_type', $type)->where('prod_color', '#' . $color)->first();
        if (str_contains(url()->previous(), 'show')) {
            $previousUrl = url()->previous();
            $previousUrlColor = explode('/', $previousUrl)[9];
            if ($previousUrlColor != $color) {
                $size = Product::where('prod_type', $type)->where('prod_name', $name)->where('prod_color', '#' . $color)->where('prod_qty', '>', 0)->orderBy('prod_size', 'asc')->first()->prod_size;
                return view('customer.product-show', ['product' => Product::where('prod_type', $type)->where('prod_name', $name)->where('prod_color', '#' . $color)->where('prod_qty', '>', 0)->where('prod_size', $size)->orderBy('prod_size', 'asc')->first(), 'prod_name_color_id' => $product->prod_name_color_id, 'product_image' => $product->product_image]);
            } else {
                return view('customer.product-show', ['product' => Product::where('prod_type', $type)->where('prod_name', $name)->where('prod_color', '#' . $color)->where('prod_qty', '>', 0)->where('prod_size', $size)->orderBy('prod_size', 'asc')->first(), 'prod_name_color_id' => $product->prod_name_color_id, 'product_image' => $product->product_image]);
            }
        } else {
            return view('customer.product-show', ['product' => Product::where('prod_type', $type)->where('prod_name', $name)->where('prod_color', '#' . $color)->where('prod_qty', '>', 0)->where('prod_size', $size)->orderBy('prod_size', 'asc')->first(), 'prod_name_color_id' => $product->prod_name_color_id, 'product_image' => $product->product_image]);
        }

        // dd(Product::where('prod_type', $type)->where('prod_name', $name)->where('prod_color', '#' . $color)->where('prod_qty', '>', 0)->orderBy('prod_size', 'asc')->first());

    }
}
