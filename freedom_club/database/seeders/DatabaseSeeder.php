<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {





        $this->call(LaratrustSeeder::class);
        if (auth()->user()) {
            Auth::logout();
        }
        $files = Storage::files('/public/product_images');
        foreach ($files as $file) {
            $fileName = explode('/', $file)[2];
            if ($fileName != 'no-image.jpg') {
                Storage::delete($file);
            }
        }
        $files = Storage::files('/public/carousel_images');
        foreach ($files as $file) {
            $fileName = explode('/', $file)[2];
            if ($fileName != 'no-image.jpg') {
                Storage::delete($file);
            }
        }

        $categories = ['Raw Material', 'Production Costs', 'Packaging', 'Promotional and Advertise', 'Other Expenses'];
        foreach ($categories as $category) {
            ExpenseCategory::create([
                'category_name' => $category
            ]);
        }
        $user = User::create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ]);
        Employee::create([
            'user_id' => $user->id,
            'emp_firstName' => 'admin',
            'emp_lastName' => 'admin',
            'emp_email' => 'admin@admin.com',
            'emp_gender' => 'male',
            'emp_birthDate' => Carbon::now()
        ]);
        DB::table('balance_sheet')->insert([
            'description' => 'Starting Capital',
            'debit_amount' => 50000,
            'total_balance' => 50000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $user->attachRole('admin');
    }
}
