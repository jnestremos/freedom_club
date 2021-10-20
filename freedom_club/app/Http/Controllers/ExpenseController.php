<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'category_name' => 'required',
            'description' => 'required',
            'computed_expense' => 'required',
        ]);

        Expense::create([
            'expense_categories_id' => $request->category_name,
            'description' => $request->description,
            'computed_expenses' => $request->computed_expense,
        ]);

        return redirect()->route('dashboard.expenses');
    }
}
