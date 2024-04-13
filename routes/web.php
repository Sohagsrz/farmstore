<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\CustomFields;
use App\Models\CustomOptions;
use App\Models\Role;
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
    // get recent movies 
    $posts = \App\Models\Srz_Cpt::where('post_type', 'movies')->orderBy('id', 'desc')->limit(5)->get();
    return View::make('pages.home', compact('posts'));

});

//search results
Route::get('search', function(Request $request){
    $search = $request->input('search');
    $posts = \App\Models\Srz_Cpt::where('post_title', 'like', '%'.$search.'%')->get();
    return view('search', compact('posts'));
});

 
//catergory view
Route::get('category/{slug}', function($slug){
    $category = \App\Models\Category::where('slug', $slug)->first();
    
    $posts = \App\Models\Srz_Cpt::where('post_type', 'post')->where('category', $category->id)->get();
    return view('category', compact('posts'));
});





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

// route group for admin, names also
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('users', function () {
        return view('admin.users');
    })->name('users');
    Route::get('settings', function () {
        return view('admin.settings');
    })->name('settings');

});

//single view post_type=movies and /{slug}
Route::get('/{slug}', function($slug){
    $post = \App\Models\Srz_Cpt::where('post_slug', $slug)->where('post_type', 'movies')->first();
    return view('single.movies', compact('post'));
});

// fallaback route
Route::fallback(function(){
    return view('404');
});
