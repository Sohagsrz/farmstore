<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\Category;
use App\Models\Category_Relation;
use App\Models\CustomFields;
use App\Models\CustomOptions;
use App\Models\Role;
use App\Models\Srz_Cpt;
use App\Models\User;
use Illuminate\Support\Facades\View;

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

Route::get('/', function () {
    /***
     * 
     * 
    //add new movie
    $movie = new Srz_Cpt();
    $movie->post_title = 'New Movie 2';
    $movie->post_content = 'New Movie Content';
    $movie->post_type = 'movies';
    $movie->save();
 
    //add new category
    $category = new Category();
    $category->name = 'Comedyx';
    $category->slug = 'comedyx';
    $category->type = 'movies'; 
    $category->save();
    // assing category to movie
    Category_Relation::create([
        'post_id' => $movie->id,
        'category_id' => $category->id
    ]);

    ***/

    // get recent movies 
    $products = Srz_Cpt::where('post_type', 'products')->orderBy('id', 'desc')->limit(12)->get();
    //top farmer 
    $farmers = User::whereHas('roles', function($q){
        $q->where('name', 'farmer');
    })->orderBy('id', 'desc')->limit(12)->get();
    
    // get recent news
    $news = Srz_Cpt::where('post_type', 'news')->orderBy('id', 'desc')->limit(3)->get();
    $categories = Category::where('type', 'products')->get();
    return view('pages.home', compact('products', 'farmers', 'news', 'categories'));

})->name('home');

//search results
Route::get('search', function(Request $request){
    $search = $request->input('q');
    $products = Srz_Cpt::where('post_title', 'like', '%'.$search.'%')
    ->where(
        'post_type', 'products'	
    )
    ->get();
    return view('pages.search', compact('products', 'request')); 
})->name("search");

 
//catergory view

Route::get('category/{category}', function(Category $category){ 
    
    $products =  $category->posts()->get(); 
    
    return view('pages.category', compact('category','products'));

})->name('category');



//login routes
Route::get('login', [AuthController::class,'login'])->middleware('guest')->name('login');

Route::post('login', [AuthController::class,'postlogin'] )->middleware('guest')->name('loginProccess');

Route::get('register', [AuthController::class,'register'] )->middleware('guest')->name('register');
Route::post('register', [AuthController::class,'postRegister'] )->middleware('guest')->name('registerProccess');


Route::get('logout', [AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::get('check', function(){
    //if exists get the id or insert 
    $is_admin =  User::find(1)->hasRole('admin');
    var_dump($is_admin);
    User::find(1)->assignRole('admin');
    var_dump(User::find(1)->getRole());

    return Auth::check();
})->middleware('role:admin');

    //chats
    
    Route::get('chats', [App\Http\Controllers\UserController::class, 'chats'])->name('chats' )->middleware('auth');

    //product single view
    Route::get('item/{product}', [App\Http\Controllers\ProductsController::class, 'single'])->name('productSingle' );
    //farmer view
    Route::get('farmer/shop/{farmer}', [App\Http\Controllers\ProductsController::class, 'farmerSingle'])->name('farmerSingle' );


    // add to cart
    Route::any('add-to-cart/{product}', [App\Http\Controllers\ProductsController::class, 'addToCart'])->name('addToCart' )->middleware('auth');
    // remove from cart
    Route::get('remove-from-cart/{product}', [App\Http\Controllers\ProductsController::class, 'removeFromCart'])->name('removeFromCart' )->middleware('auth');
    // update cart quantity
    Route::post('update-cart/{product}', [App\Http\Controllers\ProductsController::class, 'updateCart'])->name('updateCart' )->middleware('auth');
    // cart view
    Route::get('cart', [App\Http\Controllers\ProductsController::class, 'cart'])->middleware('auth')->name('cart' );
    Route::get('orders', [App\Http\Controllers\ProductsController::class, 'orders'])->middleware('auth')->name('orders' );
    //order single view
    Route::get('order/{order}', [App\Http\Controllers\ProductsController::class, 'order'])->name('orderDetails' )->middleware('auth');


    //checkout view
    Route::get('checkout', [App\Http\Controllers\ProductsController::class, 'checkout'])->name('checkout' )->middleware('auth');
    //checkout process
    Route::post('checkout', [App\Http\Controllers\ProductsController::class, 'checkoutProcess'])->name('checkoutProcess' )->middleware('auth');

    // emptyCart
    Route::get('emptyCart', [App\Http\Controllers\ProductsController::class, 'emptyCart'])->name('emptyCart' )->middleware('auth');

    //only for farmer routes farmer/{pages} group route
    Route::group(['prefix' => 'farmer', 'as' => 'farmer.', 'middleware' => 'role:farmer'], function () {
        Route::get('/',  [
            App\Http\Controllers\ProductsController::class, 'dashboard'
        ])->name('dashboard');
        Route::get('add-product', [App\Http\Controllers\ProductsController::class, 'addfromdashboard'])->name('addfromdashboard');
        //add post
        Route::post('add-product', [App\Http\Controllers\ProductsController::class, 'storefromdashboard'])->name('storefromdashboard');

         //update product
        Route::get('edit-product/{product}', [App\Http\Controllers\ProductsController::class, 'editfromdashboard'])->name('editfromdashboard');
        //update post 
        Route::post('edit-product/{product}', [App\Http\Controllers\ProductsController::class, 'updatefromdashboard'])->name('updatefromdashboard');
        // deletefromdashboard 
        Route::get('delete-product/{product}', [App\Http\Controllers\ProductsController::class, 'deletefromdashboard'])->name('deletefromdashboard');
        
        //list products
        Route::get('products', [App\Http\Controllers\ProductsController::class, 'productslist'])->name('productslist');
        //settings
        Route::get('settings', [App\Http\Controllers\ProductsController::class, 'settings'])->name('settings');
        // settings process
        Route::post('settings', [App\Http\Controllers\ProductsController::class, 'settingsProcess'])->name('settingsProcess');

        //orders
        Route::get('orders', [App\Http\Controllers\ProductsController::class, 'orders'])->name('orders');
        //order single view
        Route::get('order/{order}', [App\Http\Controllers\ProductsController::class, 'order'])->name('order');
        //update order
        Route::get('edit-order/{order}', [App\Http\Controllers\ProductsController::class, 'editOrder'])->name('editOrder');
        //delete order
        Route::get('delete-order/{order}', [App\Http\Controllers\ProductsController::class, 'deleteOrder'])->name('deleteOrder');




    });
    // add product for farmer->middleware('role:farmer');
   

// route group for admin, names also
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function () {
   
    Route::get('/', function () {
        return view('admin.pages.dashboard');
    })->name('dashboardHome');


    Route::get('dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');


     

    // add product for farmer

 
    // product manage group route , view like admin.pages.products
    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {

        Route::get('/',[App\Http\Controllers\ProductsController::class, 'index'])->name('productsHome');
        Route::get('add',  [App\Http\Controllers\ProductsController::class, 'add'])->name('add');
        //post add with img upload
        Route::post('add', [App\Http\Controllers\ProductsController::class, 'store']  )->name('addProccess');

        Route::get('edit/{id}', [App\Http\Controllers\ProductsController::class, 'edit'] ) ->name('edit');
        //post update
        Route::post('edit/{id}', [App\Http\Controllers\ProductsController::class, 'update'] )->name('update');
        
        //delete product
        Route::get('delete/{id}',  [App\Http\Controllers\ProductsController::class, 'delete'] )->name('delete');

    });
    

    //news manage group route , view like admin.pages.news
    Route::group(['prefix' => 'news', 'as' => 'news.'], function () {

        Route::get('/',[App\Http\Controllers\NewsController::class, 'index'])->name('index');
        Route::get('add',  [ App\Http\Controllers\NewsController::class, 'add'])->name('add');
        //post add with img upload
        Route::post('add', [ App\Http\Controllers\NewsController::class, 'store']  )->name('addProccess');

        Route::get('edit/{id}', [App\Http\Controllers\NewsController::class, 'edit'] ) ->name('edit');
        //post update
        Route::post('edit/{id}', [App\Http\Controllers\NewsController::class, 'update'] )->name('update');
        
        //delete news
        Route::get('delete/{id}',  [App\Http\Controllers\NewsController::class, 'delete'] )->name('delete');

    });


    //categories manage group route , view like admin.pages.categories
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {

        Route::get('/',[App\Http\Controllers\CategoryController::class, 'index'])->name('categoriesHome');
        Route::get('add',  [ App\Http\Controllers\CategoryController::class, 'add'])->name('add');
        //post add with img upload
        Route::post('add', [ App\Http\Controllers\CategoryController::class, 'store']  )->name('addProccess');

        Route::get('edit/{id}', [App\Http\Controllers\CategoryController::class, 'edit'] ) ->name('edit');
        //post update
        Route::post('update/{id}', [App\Http\Controllers\CategoryController::class, 'update'] )->name('update');
        
        //delete category
        Route::get('delete/{id}',  [App\Http\Controllers\CategoryController::class, 'delete'] )->name('delete');
    });

    //users manage group route , view like admin.pages.users
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {

        Route::get('/',[App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('add',  [ App\Http\Controllers\UserController::class, 'add'])->name('add');
        //post add with img upload
        Route::post('add', [ App\Http\Controllers\UserController::class, 'store']  )->name('store');

        Route::get('edit/{id}', [App\Http\Controllers\UserController::class, 'edit'] ) ->name('edit');
        //post update
        Route::post('update/{id}', [App\Http\Controllers\UserController::class, 'update'] )->name('update');
        
        //delete user
        Route::get('delete/{id}',  [App\Http\Controllers\UserController::class, 'delete'] )->name('delete');
    } );


 

    Route::get('settings', [App\Http\Controllers\AdminController::class, 'settings']  )->name('settings');
    //store settings 
    Route::post('settings',)->name('settingsProccess');
    //profile 
    Route::get('profile', [App\Http\Controllers\AdminController::class, 'profile']   )->name('profile');
    //store profile
    Route::post('profile', [App\Http\Controllers\AdminController::class, 'profileStore'] )->name('profileProccess');

    
});

//single view post_type=movies and /{slug}
Route::get('/{post}', [App\Http\Controllers\MoviesController::class, 'single'])->name('single' );


// fallaback route
Route::fallback(function(){
    return view('404');
});
Auth::routes();
