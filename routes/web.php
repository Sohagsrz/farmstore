<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\CustomFields;
use App\Models\CustomOptions;
use App\Models\Role;
use App\Models\User;

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
    // add new cpt
    // $cpt = new \App\Models\Srz_Cpt();
    // $cpt->post_type = 'post';
    // $cpt->post_title = 'I am Sohag';
    // $cpt->post_content = 'Hello world';
    // $cpt->post_status = 'publish';
    // $cpt->post_author = Auth::id();
    // // $cpt->post_slug= 'test-title'; 
    // $cpt->post_parent=  0; 

    // $cpt->post_comment_status = 'open'; 
    // $cpt->save();
    // var_dump(test());
    // $custom_fields = new CustomFields();
    // $custom_fields->name = 'img';
    // $custom_fields->value = 'nai';
    // $custom_fields->type = 'user';
    // $custom_fields->obj_id = Auth::id();
    // $custom_fields->save();
    // CustomOptions::setOption('siteurl', 'https://localhost:8000');
    // var_dump(CustomOptions::getOption('siteurl'));

    // CustomFields::addField('img', 'nai', 'user', Auth::id());
    // var_dump(CustomFields::getField('img', 'user', Auth::id()));

    
    return view('home');

});

//search results
Route::get('search', function(Request $request){
    $search = $request->input('search');
    $posts = \App\Models\Srz_Cpt::where('post_title', 'like', '%'.$search.'%')->get();
    return view('search', compact('posts'));
});

//single view , post_type=movies
Route::get('movies/{id}', function($id){
    $post = \App\Models\Srz_Cpt::where('id', $id)->where('post_type', 'movies')->first();
    return view('single.movies', compact('post'));
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

 