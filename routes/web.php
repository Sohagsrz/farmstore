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
    $posts = Srz_Cpt::where('post_type', 'movies')->orderBy('id', 'desc')->limit(5)->get();
    return View::make('pages.home', compact('posts'));

});

//search results
Route::get('search', function(Request $request){
    $search = $request->input('q');
    $posts =  Srz_Cpt::where('post_title', 'like', '%'.$search.'%')->get();
    return view('pages.search', compact('posts'));
});

 
//catergory view

Route::get('category/{category}', function(Category $category){ 
    
    $posts =  $category->posts()->get(); 
    
    return view('pages.category', compact('category','posts'));

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

// route group for admin, names also
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function () {
   
    Route::get('/', function () {
        return view('admin.pages.dashboard');
    })->name('dashboardHome');


    Route::get('dashboard', function () {
        return view('admin.pages.dashboard');
    })->name('dashboard');

    //movies manage group route , view like admin.pages.movies
    Route::group(['prefix' => 'movies', 'as' => 'movies.'], function () {

        Route::get('/',[App\Http\Controllers\MoviesController::class, 'index'])->name('moviesHome');
        Route::get('add',  [ App\Http\Controllers\MoviesController::class, 'add'])->name('add');
        //post add with img upload
        Route::post('add', [ App\Http\Controllers\MoviesController::class, 'store']  )->name('addProccess');

        Route::get('edit/{id}', [App\Http\Controllers\MoviesController::class, 'edit'] ) ->name('edit');
        //post update
        Route::post('edit/{id}', [App\Http\Controllers\MoviesController::class, 'update'] )->name('update');
        
        //delete movie
        Route::get('delete/{id}',  [App\Http\Controllers\MoviesController::class, 'delete'] )->name('delete');

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


    Route::get('users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('settings', function () {  return view('admin.pages.settings'); })->name('settings');
    //store settings 
    Route::post('settings', [App\Http\Controllers\AdminController::class, 'settingsStore'] )->name('settingsProccess');
    
});

//single view post_type=movies and /{slug}
Route::get('/{movie}', [App\Http\Controllers\MoviesController::class, 'single'])->name('single' );


// fallaback route
Route::fallback(function(){
    return view('404');
});
Auth::routes();
