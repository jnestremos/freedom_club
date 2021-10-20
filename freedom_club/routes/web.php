<?php

use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierTransactionController;
use App\Http\Controllers\ProductController;
use App\Mail\ConfirmPayment;
use App\Mail\RemindPayment;
use App\Mail\TransferRequest;
use App\Models\Checkout;
use App\Models\Cart;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\SuppTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Psy\VersionUpdater\Checker;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/emailasd', function () {

    //dd(Carbon::now()->dayOfWeek);
});

//Admin
Route::get('/admin', [EmployeeController::class, 'adminView'])->middleware(['auth', 'role:admin'])->name('admin.adminView');
Route::put('/admin/{id}', [EmployeeController::class, 'setAdmin'])->middleware(['auth', 'role:admin'])->name('admin.setAdmin');

//Customer Registration Form
Route::get('/register/customer', [CustomerController::class, 'index'])->middleware('guest')->name('registerCustomer.index');
Route::post('/register/customer', [CustomerController::class, 'store'])->middleware('guest')->name('registerCustomer.store');

//Employee Registration Form
Route::get('/register/employee', [EmployeeController::class, 'index'])->middleware('auth')->name('registerEmployee.index');
Route::post('/register/employee', [EmployeeController::class, 'store'])->middleware('auth')->name('registerEmployee.store');

//Employee Profile
Route::get('/dashboard/profile/{id}', [EmployeeController::class, 'show'])->middleware(['auth', 'role:store_owner|warehouse_manager|product_manager']);

//Customer Profile
Route::get('/profile', [CustomerController::class, 'show'])->middleware('auth')->name('customer.profile');
Route::post('/profile/{id}', [CustomerController::class, 'update'])->middleware('auth')->name('customer.update');
Route::get('/purchase-history', [CustomerController::class, 'showHistory'])->middleware('auth')->name('customer.showHistory');
Route::get('/deliveries', [CustomerController::class, 'showDeliveries'])->middleware('auth')->name('customer.showDeliveries');
Route::get('/return', [CustomerController::class, 'return'])->middleware('auth')->name('customer.return');
Route::post('/return', [CustomerController::class, 'updateReturn'])->middleware('auth')->name('customer.updateReturn');

//Carousel Image Edit
Route::get('dashboard/carousel-images', [CarouselController::class, 'index'])->middleware('auth')->name('carousel.index');
Route::post('dashboard/carousel-images', [CarouselController::class, 'store'])->middleware('auth')->name('carousel.store');
Route::delete('dashboard/carousel-images/{id}', [CarouselController::class, 'delete'])->middleware('auth')->name('carousel.delete');
Route::post('dashboard/carousel-images/clear', [CarouselController::class, 'clear'])->middleware('auth')->name('carousel.clear');

//Product Images
Route::get('dashboard/product/images', function () {
    // foreach (DB::table('prod_name_color')->get() as $product_name_color) {
    //     if ($product_name_color->prod_color) {
    //         DB::table('prod_name_color')->where('prod_color')
    //     }
    // }
    // $product_names = [];
    // $products = Product::all();
    // foreach ($products as $index => $product) {
    //     if ($index == 0) {
    //         array_push($product_names, ($product->prod_name));
    //     } else {
    //         $check = true;
    //         foreach ($product_names as $product_name) {
    //             if (strtolower($product->prod_name) == strtolower($product_name)) {
    //                 $check = false;
    //                 break;
    //             }
    //         }
    //         if ($check) {
    //             array_push($product_names, ($product->prod_name));
    //         }
    //     }
    // }
    //dd(Product::whereIn('prod_name', $product_names)->get());
    return view('employee.emp-product-images', ['products' => DB::table('prod_name_color')->simplePaginate(6)]);
})->middleware('auth')->name('product.images');

Route::get('dashboard/product/images/{id}', function ($id) {
    return view('employee.emp-product-image', ['product_images' => DB::table('product_images')->where('prod_name_color_id', $id)->simplePaginate(6)]);
})->middleware('auth')->name('product.imageShow');

Route::post('dashboard/product/images/store', function (Request $request) {
    $request->validate([
        'product_image' => 'image|required|dimensions:ratio=3/2'
    ]);
    $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    $extension = $request->file('product_image')->getClientOriginalExtension();
    $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
    $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);
    DB::table('product_images')->insert([
        'prod_name_color_id' => $request->prod_color_name_id,
        'product_image' => $fileNameToStore,
        'image_main' => false
    ]);
    return redirect('/dashboard/product/images/' . $request->prod_name_color_id);
    //return view('employee.emp-product-image', ['product_images' => DB::table('product_images')->where('product_id', $request->product_id)->simplePaginate(4)]);
})->middleware('auth')->name('product.imageStore');

Route::put('dashboard/product/images/update/status/{id}', function (Request $request, $id) {
    //dd(DB::table('product_images')->where('product_id', $request->product_id)->where('image_main', '1')->first());   
    //dd($request->prod_name_color_id);
    if (DB::table('product_images')->where('id', $id)->first()->product_image == 'no-image.jpg') {
        return redirect('dashboard/product/images/' . $request->prod_name_color_id)->with('error', 'Invalid default picture!');
    } else {
        if (DB::table('product_images')->where('prod_name_color_id', $request->prod_name_color_id)->where('image_main', 1)->first() == null) {
            DB::table('product_images')->where('id', $id)->update([
                'image_main' => true
            ]);
        } else {
            DB::table('product_images')->where('prod_name_color_id', $request->prod_name_color_id)->where('image_main', 1)->update([
                'image_main ' => false
            ]);
            DB::table('product_images')->where('id', $id)->update([
                'image_main' => true
            ]);
        }
        return redirect('dashboard/product/images/' . $request->prod_name_color_id);
    }


    // if ($product_image->product_image == 'no-image.jpg') {
    //     return redirect('/dashboard/product/images/' . $request->prod_color_name_id)->with('error', 'Invalid default picture!');
    // } else {

    //     if (DB::table('product_images')->where('id', $id)->where('image_main', 1)->get() == null) {
    //         DB::table('product_images')->where('id', $id)->update([
    //             'image_main' => true
    //         ]);
    //     } else {
    //         DB::table('product_images')->where('image_main', '1')->update([
    //             'image_main' => false
    //         ]);
    //         DB::table('product_images')->where('id', $id)->update([
    //             'image_main' => true
    //         ]);
    //     }
    //     return redirect('/dashboard/product/images/' . $request->prod_color_name_id);
    // }
})->middleware('auth')->name('product.updateStatus');
Route::put('dashboard/product/images/update/image/{id}', function (Request $request, $id) {
    //dd(DB::table('product_images')->where('product_id', $request->product_id)->where('image_main', '1')->first()); 
    //dd($request->product_image);
    $request->validate([
        'product_image' => 'image|required|dimensions:ratio=3/2'
    ]);
    $product_image = DB::table('product_images')->where('id', $id)->first();
    $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    $extension = $request->file('product_image')->getClientOriginalExtension();
    $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
    $path = $request->file('product_image')->storeAs('public/product_images', $fileNameToStore);
    if (DB::table('product_images')->where('id', $id)->first()->product_image != 'no-image.jpg') {
        Storage::delete('/public/product_images/' . $product_image->product_image);
    }
    $prod_name_color_id = DB::table('product_images')->where('id', $id)->first()->prod_name_color_id;
    if (count(DB::table('product_images')->where('prod_name_color_id', $prod_name_color_id)->get()) == 1) {
        DB::table('product_images')->where('id', $id)->update([
            'product_image' => $fileNameToStore,
            'image_main' => true
        ]);
    } else {
        DB::table('product_images')->where('id', $id)->update([
            'product_image' => $fileNameToStore,
        ]);
    }

    // $product_image->product_image = $fileNameToStore;
    return redirect('/dashboard/product/images/' . $request->prod_color_name_id);
})->middleware('auth')->name('product.updateImage');

Route::delete('dashboard/product/images/delete/{id}', function (Request $request, $id) {
    $product_image = DB::table('product_images')->where('id', $id)->first()->product_image;
    $prod_name_color_id = DB::table('product_images')->where('id', $id)->first()->prod_name_color_id;
    if (DB::table('product_images')->where('id', $id)->first()->product_image != 'no-image.jpg') {
        Storage::delete('/public/product_images/' . $product_image);
    }
    if (count(DB::table('product_images')->where('prod_name_color_id', $prod_name_color_id)->get()) == 1) {
        DB::table('product_images')->where('id', $id)->update([
            'product_image' => 'no-image.jpg',
            'image_main' => false
        ]);
    } else {
        DB::table('product_images')->where('id', $id)->delete();
    }
    Product::where('prod_name', DB::table('prod_name_color')->where('id', $prod_name_color_id)->first()->prod_name)->where('prod_type', DB::table('prod_name_color')->where('id', $prod_name_color_id)->first()->prod_type)->where('prod_color', DB::table('prod_name_color')->where('id', $prod_name_color_id)->first()->prod_color)->update([
        'prod_status' => false
    ]);
    if (count(DB::table('product_images')->get()) == 0) {
        return redirect()->route('dashboard.products')->with('error', 'Please set your default picture for the product named \'' . DB::table('prod_name_color')->where('prod_name_color_id', $prod_name_color_id)->first()->prod_name . '\'');
    } else {
        return redirect('/dashboard/product/images/' . $prod_name_color_id);
        //return view('employee.emp-product-image', ['product_images' => DB::table('product_images')->where('product_id', $request->product_id)->simplePaginate(4)]);
    }
})->middleware('auth')->name('product.imageDelete');


//Forgot Password Module
Route::get('/forgot-password', function () {
    return view('password.forgotPassword');
})->middleware('guest')->name('forgotPassword');
Route::post('/forgot-password', [ResetPasswordController::class, 'emailVerify'])->middleware('guest')->name('password.request');
Route::get('/reset-password/{token}', function ($token) {
    if (DB::table('customer_password_resets')->select("*")->where('token', '=', $token)->first()) {
        $profile = DB::table('customer_password_resets')->select('email')->where('token', '=', $token)->first();
        $email = $profile->email;
        return view('password.resetPassword', ['token' => $token, 'email' => $email]);
    } else {
        return redirect()->route('forgotPassword')->with('error', 'Verification token already expired!');
    }
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.update');

//Email Verification Module
Route::get('users/verify/{token}', [CustomerController::class, 'verifyEmail'])->middleware('auth')->name('user.verify');
Route::put('users/resendVerify/{id}', [CustomerController::class, 'resendVerify'])->middleware(['auth', 'role:customer'])->name('user.resendVerify');

//Employee Dashboard
Route::get('/dashboard/home', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.home');

//Employee Profile
Route::get('/dashboard/profile/{id}', [EmployeeController::class, 'show'])->middleware('auth')->name('employees.show');
Route::put('/dashboard/profile/{id}', [EmployeeController::class, 'updateProfile'])->middleware('auth')->name('employees.updateProfile');

//Supplier Profile
Route::get('/dashboard/suppliers', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.suppliers');
Route::post('/dashboard/suppliers', [SupplierController::class, 'store'])->middleware('auth')->name('suppliers.store');
Route::put('/dashboard/suppliers/{id}', [SupplierController::class, 'update'])->middleware('auth')->name('suppliers.update');
Route::delete('/dashboard/suppliers/{id}', [SupplierController::class, 'delete'])->middleware('auth')->name('suppliers.delete');
Route::get('/dashboard/materials', [MaterialController::class, 'index'])->middleware('auth')->name('materials.index');
Route::post('/dashboard/materials', [MaterialController::class, 'store'])->middleware('auth')->name('materials.store');
Route::put('/dashboard/materials/{id}', [MaterialController::class, 'update'])->middleware('auth')->name('materials.update');
Route::delete('/dashboard/materials/{id}', [MaterialController::class, 'delete'])->middleware('auth')->name('materials.delete');

//Employee Profile Table
Route::get('/dashboard/employees', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.employees');
Route::put('/dashboard/employees/{id}', [EmployeeController::class, 'update'])->middleware('auth')->name('employees.update');

//Stocks Route
Route::get('/dashboard/stocks', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.stocks');

//Products Module
Route::get('/dashboard/products', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.products');
Route::post('/dashboard/products', [ProductController::class, 'store'])->middleware('auth')->name('products.store');
Route::put('/dashboard/products/{id}', [ProductController::class, 'update'])->middleware('auth')->name('products.update');
Route::delete('/dashboard/products/{id}', [ProductController::class, 'delete'])->middleware('auth')->name('products.delete');
Route::get('/show/{prod_type}/{prod_name}/{prod_color}/{prod_size}', [ProductController::class, 'show'])->name('products.show');

//Cart Module
Route::post('/cart', [CartController::class, 'store'])->middleware('auth')->name('cart.store');
Route::get('/cart', [CartController::class, 'index'])->middleware('auth')->name('cart.index');
Route::put('/cart/{id}', [CartController::class, 'update'])->middleware('auth')->name('cart.update');

//Checkout Module
//Route::post('/checkout', [CheckoutController::class, 'storeCart'])->middleware('auth')->name('checkout.storeCart');
Route::post('/checkout/pending', [CheckoutController::class, 'pending'])->middleware('auth')->name('checkout.pending');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::put('/checkout/confirm/{IDArray}', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
Route::get('/checkout/{IDArray}/{token}', function ($IDArray, $token) {
    $IDToSplit = explode('|', $IDArray);
    $check = true;
    foreach ($IDToSplit as $id) {
        // dd(empty(Checkout::find(Cart::find($id)->checkout_id)->status));
        if (Checkout::find(Cart::find($id)->checkout_id)->confirm_token == $token && Checkout::find(Cart::find($id)->checkout_id)->emailStatus === null) {
            $check = true;
        } else {
            $check = false;
            break;
        }
    }
    if ($check) {
        Checkout::find(Cart::find($id)->checkout_id)->update([
            'emailStatus' => true
        ]);
        Mail::to(auth()->user()->customer->cust_email)->send(new ConfirmPayment($IDArray));
        return view('customer.confirm-success', ['message' => 'Checkout request successful!']);
    } else {
        return view('customer.confirm-error', ['message' => 'Checkout request unsuccessful!']);
    }
});

//Shipment Module
Route::get('/dashboard/shipments', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.shipments');
Route::put('/dashboard/shipments/{id}', [ShipmentController::class, 'update'])->middleware('auth')->name('shipments.update');
Route::get('/dashboard/shipments/history', function () {
    return view('employee.emp-shipments-history', ['shipments' => Shipment::onlyTrashed()->get()]);
})->middleware('auth')->name('shipments.history');

//Sales
Route::get('/dashboard/sales', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.sales');
Route::get('/dashboard/sales/history', function () {
    return view('employee.emp-sales-history', ['sales' => DB::table('sales')->where('deleted_at', '!=', null)->get()]);
})->middleware('auth')->name('sales.history');

//Expenses
Route::get('/dashboard/expenses', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.expenses');
Route::post('dashboard/expenses', [ExpenseController::class, 'store'])->middleware('auth')->name('expense.store');
Route::get('/dashboard/expenses/history', function () {
    return view('employee.emp-expenses-history', ['expenses' => Expense::onlyTrashed()->get()]);
})->middleware('auth')->name('expenses.history');

//Balance Sheet
Route::get('/dashboard/balance', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.balance');


//Checkout Orders & Sales Return
Route::get('/dashboard/orders', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.orders');
Route::put('/dashboard/orders/{invoice_number}', [CheckoutController::class, 'update'])->middleware('auth')->name('orders.update');
Route::get('/dashboard/salesReturn', [CheckoutController::class, 'showReturn'])->middleware('auth')->name('orders.showReturn');
Route::put('/dashboard/salesReturn/{receipt_number}', [CheckoutController::class, 'updateReturn'])->middleware('auth')->name('orders.updateReturn');
Route::get('/dashboard/orders/history', function () {
    return view('employee.emp-orders-history', ['orders' => Checkout::onlyTrashed()->get()]);
})->middleware('auth')->name('orders.history');
Route::get('/dashboard/salesReturn/history', function () {
    return view('employee.emp-salesReturn-history', ['returns' => DB::table('sales_returns')->where('deleted_at', '!=', null)->get()]);
})->middleware('auth')->name('salesReturn.history');

//Stock to Material Transfer
Route::get('/dashboard/transfer', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.transfer');
Route::post('/dashboard/transfer', [TransferController::class, 'store'])->middleware('auth')->name('transfer.store');
Route::put('/dashboard/transfer/{id}', [TransferController::class, 'update'])->middleware('auth')->name('transfer.update');
Route::get('/dashboard/transfer/history', function () {
    return view('employee.emp-transfer-history', ['transfers' => DB::table('stock_transfers')->where('deleted_at', '!=', null)->get()]);
})->middleware('auth')->name('transfer.history');

//Supplier Purchases
Route::get('/dashboard/purchases', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.purchases');
Route::post('/dashboard/purchases', [SupplierTransactionController::class, 'store'])->middleware('auth')->name('purchases.store');
Route::put('/dashboard/purchases/{id}', [SupplierTransactionController::class, 'update'])->middleware('auth')->name('purchases.update');
Route::get('/dashboard/purchases/items/email/{id}', [SupplierTransactionController::class, 'updateFromEmail'])->middleware('auth')->name('purchases.updateFromEmail');
Route::delete('/dashboard/purchases/{id}', [SupplierTransactionController::class, 'delete'])->middleware('auth')->name('purchases.delete');
Route::get('/dashboard/purchases/items', [SupplierTransactionController::class, 'indexItems'])->middleware('auth')->name('purchases.indexItems');
Route::put('/dashboard/purchases/items/{id}', [SupplierTransactionController::class, 'updateItems'])->middleware('auth')->name('purchases.updateItems');
Route::delete('/dashboard/purchases/items/{id}', [SupplierTransactionController::class, 'deleteItems'])->middleware('auth')->name('purchases.deleteItems');
Route::get('/dashboard/purchases/history', function () {
    return view('employee.emp-purchases-history', ['purchases' => SuppTransaction::onlyTrashed()->get()]);
})->middleware('auth')->name('purchases.history');


//Logout Module
Route::resource('/logout', LogoutController::class)->middleware('auth');

//Customer Login Form
Route::get('/login/customer', [LoginController::class, 'index'])->middleware('guest')->name('loginCustomer.index');
//Employee Login Form
Route::get('/login/employee', [LoginController::class, 'index'])->middleware('guest')->name('loginEmployee.index');

//Login Redirect URL on error
Route::get('/login', function () {
    return redirect()->route('loginCustomer.index');
})->middleware('guest');

//Login Module
Route::post('/login', [LoginController::class, 'store'])->middleware('guest')->name('login.store');

//Home Redirect URL on error
Route::get('/', function () {
    if (Auth::user()) {
        if (auth()->user()->hasRole('store_owner') || auth()->user()->hasRole('warehouse_manager') || auth()->user()->hasRole('product_manager')) {
            return redirect()->route('dashboard.home');
        } else {
            return view('customer.home');
        }
    } else {
        return view('customer.home');
    }
})->name('home');

//Shop
Route::get('/shop', function () {
    return view('customer.shop');
})->name('shop');
Route::get('/shop/{category}', function ($category) {
    DB::table('home_products_queue')->truncate();
    $prod_name_colors = DB::table('prod_name_color')->get();
    $products = Product::where('prod_status', true)->where('prod_qty', '>', '0')->orderBy('prod_size', 'asc')->get();
    $product_images = DB::table('product_images')->get();
    foreach ($products as $product) {
        foreach ($prod_name_colors as $prod_name_color) {
            if (
                $product->prod_name == $prod_name_color->prod_name && $product->prod_type == $prod_name_color->prod_type
                && $product->prod_color == $prod_name_color->prod_color && $product->prod_qty > 0
            ) {
                $product_image = DB::table('product_images')->where('prod_name_color_id', $prod_name_color->id)->where('image_main', 1)->first()->product_image;
                DB::table('home_products_queue')->insert([
                    'prod_name_color_id' => $prod_name_color->id,
                    'prod_name' => $prod_name_color->prod_name,
                    'prod_type' => $prod_name_color->prod_type,
                    'product_image' => DB::table('product_images')->where('prod_name_color_id', $prod_name_color->id)->where('image_main', 1)->first()->product_image,
                    'prod_color' => $prod_name_color->prod_color,
                    'isUsed' => false,
                ]);
            }
        }
    }
    // $product_image_array = [];
    // $product_type_array = [];
    // $product_name_array = [];
    // $product_color_array = [];
    // $prod_name_color_id_array = [];
    // foreach (DB::table('home_products_queue')->get() as $index => $products) {
    //     for ($i = $index; $i < count(DB::table('home_products_queue')->get()); $i++) {
    //         if (count(DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->get()) > 1) {
    //             $prod_name = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_name;
    //             $product_image = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->product_image;
    //             $prod_type = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_type;
    //             $prod_color = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_color;
    //             $prod_name_color_id = DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->first()->prod_name_color_id;
    //             DB::table('home_products_queue')->where('prod_name_color_id', $products->prod_name_color_id)->delete();
    //             DB::table('home_products_queue')->insert([
    //                 'prod_name_color_id' => $prod_name_color_id,
    //                 'prod_name' => $prod_name,
    //                 'prod_type' => $prod_type,
    //                 'product_image' => $product_image,
    //                 'prod_color' => $prod_color,
    //                 'isUsed' => true,
    //             ]);
    //         }
    //     }
    // }
    if ($category == 'Accessories') {
        $home_products = DB::table('home_products_queue')->whereIn('prod_type', ['Hat', 'Bag'])->orderBy('prod_name_color_id', 'asc')->simplePaginate(9);
    } else {
        $home_products = DB::table('home_products_queue')->where('prod_type', $category)->orderBy('prod_name_color_id', 'asc')->simplePaginate(9);
    }
    return view('customer.categoryItems', ['products' => $home_products]);
})->name('shop.show');

//Facebook Auth
Route::get('/login/facebook/redirect', [LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('/login/facebook/callback', [LoginController::class, 'handleFacebookCallback']);

//Google Auth
Route::get('/login/google/redirect', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);
