<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use ourcodeworld\NameThatColor\ColorInterpreter;

class MaterialController extends Controller
{

    public function index()
    {
        return view('employee.emp-item-list');
    }


    public function store(Request $request)
    {
        //$color = new ColorInterpreter();
        //dd($request);
        //dd($color->name($request->material_color)['name']);
        $this->validate($request, [
            'supplier_id' => 'required',
            'material_type' => 'required',
            'material_size' => 'required',
            'material_color' => 'required',
            'material_price' => 'required',
        ]);
        if (Material::where('supplier_id', $request->supplier_id)->where('material_type', $request->material_type)->where('material_size', $request->material_size)->where('material_color', $request->material_color)->where('material_price', $request->material_price)->first() == null) {
            Material::create([
                'material_number' => rand(),
                'supplier_id' => $request->supplier_id,
                'material_type' => $request->material_type,
                'material_size' => strtoupper($request->material_size),
                'material_color' => $request->material_color,
                'material_price' => $request->material_price,
            ]);
            return redirect()->route('dashboard.suppliers');
        } else {
            return back()->with('error', 'The record has already been added!');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'material_size' => 'required',
            'material_color' => 'required',
            'material_price' => 'required',
        ]);

        $targetMaterial = Material::find($id);
        $wrongMaterialCollection = DB::table('materials')->select('*')->where('id', '!=',  $targetMaterial->id)->get();
        $validator = true;
        foreach ($wrongMaterialCollection as $material) {
            if (($request->material_size == $material->material_size)
                && ($request->material_color == $material->material_color)
                && ($request->material_price == $material->material_price)
            ) {
                $validator = false;
                break;
            }
        }
        if ($validator) {
            $targetMaterial->material_size = $request->material_size;
            $targetMaterial->material_color = $request->material_color;
            $targetMaterial->material_price = $request->material_price;
            $targetMaterial->save();
            if (DB::table('material_transaction')->where('material_id', $id)->first()) {
                DB::table('material_transaction')->where('material_id', $id)->update([
                    'material_size' => $targetMaterial->material_size,
                    'material_color' => $targetMaterial->material_color,
                    'material_price' => $targetMaterial->material_price,
                    'updated_at' => Carbon::now(),
                ]);
            }
            return redirect()->route('materials.index');
        } else {
            return redirect()->route('materials.index')->with('error', 'Update not successful! Please do it again!');
        }
    }

    public function delete($id)
    {
        Material::find($id)->forceDelete();
        return redirect()->route('materials.index');
    }
}
