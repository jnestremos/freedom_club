<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function store(Request $request)
    {

        $this->validate($request, [
            'supplier_name' => 'required',
            'supplier_contactNumber' => 'unique:App\Models\Supplier,supp_contactNum|required|digits:11|regex:([0][9]\d\d\d\d\d\d\d\d\d)',
            'supplier_email' => 'unique:App\Models\Supplier,supp_email|required|email',
        ]);

        Supplier::create([
            'supp_name' => $request->supplier_name,
            'supp_contactNum' => $request->supplier_contactNumber,
            'supp_email' => $request->supplier_email,
        ]);

        return redirect()->route('dashboard.suppliers');
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'supplier_name' => 'required',
            'supplier_contactNumber' => 'required|digits:11|regex:([0][9]\d\d\d\d\d\d\d\d\d)',
            'supplier_email' => 'required|email',
        ]);

        $targetSupplier = Supplier::find($id);
        $wrongSupplierCollection = DB::table('suppliers')->select('*')->where('id', '!=',  $targetSupplier->id)->get();
        $validator = true;
        foreach ($wrongSupplierCollection as $supplier) {
            if (($request->supplier_name == $supplier->supp_name) || ($request->supplier_contactNumber == $supplier->supp_contactNum)
                || ($request->supplier_email == $supplier->supp_email)
            ) {
                $validator = false;
                break;
            }
        }
        if ($validator) {
            $targetSupplier->supp_name = $request->supplier_name;
            $targetSupplier->supp_contactNum = $request->supplier_contactNumber;
            $targetSupplier->supp_email = $request->supplier_email;
            $targetSupplier->save();
            return redirect()->route('dashboard.suppliers');
        } else {
            return redirect()->route('dashboard.suppliers')->with('error', 'Update not successful! Please do it again!');
        }
    }

    public function delete($id)
    {

        Supplier::find($id)->forceDelete();


        return redirect()->route('dashboard.suppliers');
    }
}
