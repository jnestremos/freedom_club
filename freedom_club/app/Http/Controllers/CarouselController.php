<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        return view('employee.emp-carouselEdit');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'carousel_image' => 'image|required|dimensions:ratio=3/1'
        ]);


        $fileNameWithExt = $request->file('carousel_image')->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('carousel_image')->getClientOriginalExtension();
        $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $path = $request->file('carousel_image')->storeAs('public/carousel_images', $fileNameToStore);
        DB::table('carousel_images')->insert([
            'carousel_image' => $fileNameToStore,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return redirect()->route('carousel.index');
    }

    public function delete(Request $request, $id)
    {
        $image = DB::table('carousel_images')->where('id', $id)->first();
        //dd($image);
        Storage::delete('public/carousel_images/' . $image->carousel_image);
        DB::table('carousel_images')->where('id', $id)->delete();

        return redirect()->route('carousel.index');
    }
    public function clear()
    {
        foreach (DB::table('carousel_images')->get() as $image) {
            Storage::delete('public/carousel_images/' . $image->carousel_image);
        }
        DB::table('carousel_images')->truncate();
        return redirect()->route('carousel.index');
    }
}
