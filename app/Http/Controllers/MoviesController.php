<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category_Relation;
use App\Models\Srz_Cpt;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;


class MoviesController extends Controller
{
    public $type = 'movies';

    public function index()
    { 
        $type= "movies";
        $name= 'Movies';


        $page =  request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_LIMIT',10);
        $offset= ($page-1)*$limit;
        $total_movies= Srz_Cpt::where([
            'post_type' =>$type,
            // 'status' => 1
            ])->count();
        $total_pages= ceil($total_movies/$limit);
        $movies= Srz_Cpt::where([
            'post_type' =>$type,
            // 'status' => 1
            ])->orderBy('id','desc')->paginate(
                $limit,
                ['*'],
                'page',
                $page
            );
        
        return view('admin.pages.movies.index', compact('movies','type','name','total_movies','total_pages'));
   
  

    }
    // add 
    public function add()
    {
        $type= "movies";
        $name= 'Movie';
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get();
        return view('admin.pages.movies.add-movie', compact('categories','type','name'));
    
    }

    //store 
    public function store(Request $request)
    {  
        
        $type = 'movies';

        //validate request
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required',
            'categories' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        //add movie
        $movie = new Srz_Cpt();
        $movie->post_title = $request->input('post_title');
        $movie->post_content = $request->input('post_content');
        $movie->post_type = 'movies';
        $movie->post_author = Auth::id();
        $movie->post_status = 'publish';
        $movie->save();
        //add categories
        $categories = $request->input('categories');
        
        
        if($categories){ 
            foreach($categories as $category){
                 $movie->categories()->attach($category);
            }
        }
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
         
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $movie->save();
        // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $movie->id);


            //set thumbNail

            // $movie->thumbnail = $name;
            // $movie->save();
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), $type, $movie->id);
            }
             
        }
        //watch urls
        $watch_urls = $request->input('watch_urls');
        if($watch_urls){
            //remove empty values
            $watch_urls = array_filter($watch_urls);

             update_field('watch_urls',  ($watch_urls), $type, $movie->id);
        }
        //download urls
        $download_urls = $request->input('download_urls');
        if($download_urls){
            //remove empty values
            $download_urls = array_filter($download_urls);

            update_field('download_urls',  ($download_urls), $type, $movie->id);
        }


        //redirect with success message
        return redirect()->route('admin.movies.edit', [
            'id' => $movie->id
        
        ])->with('success', 'Movie added successfully');

    }
    // edit 
    public function edit(Request $request, $id)
    {

        $name = 'Movie';
        $type = 'movies';

        
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get();
 
        $movie = Srz_Cpt::find($id);
        $selected_categories = Category_Relation::where('post_id', $id)->pluck('category_id')->toArray();
        
        return view('admin.pages.movies.edit-movie', compact('movie', 'categories', 'selected_categories','type','name'));

    }
    // update
    public function update(Request $request, $id)
    {
        $name = 'Movie';
        $type = 'movies';
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required',
            'categories' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $movie = Srz_Cpt::find($id);
        $movie->post_title = $request->input('post_title');
        $movie->post_content = $request->input('post_content');
        $movie->post_author = Auth::id();
        $movie->post_status = $request->input('post_status', 'publish');

        $movie->save();
        //update categories
        $categories = $request->input('categories');
 
        if($categories){
            $movie->categories()->detach();
            foreach($categories as $category){
                 $movie->categories()->attach($category);
            }
        }
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $movie->save();
            // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $movie->id);
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), 'movies', $movie->id);
            }
        }
        //watch urls
        $watch_urls = $request->input('watch_urls', []);
        if($watch_urls){
            $watch_urls = array_filter($watch_urls);
            update_field('watch_urls',  ($watch_urls), 'movies', $movie->id);
        }
        //download urls
        $download_urls = $request->input('download_urls', []);
        if($download_urls){
            
            $download_urls = array_filter($download_urls);
            update_field('download_urls',  ($download_urls), 'movies', $movie->id);
        }
        //redirect with success message

        return redirect()->back()->with('success', 'Movie updated successfully');
    }
    // delete
    public function delete($id)
    {
        $movie = Srz_Cpt::find($id);
        $movie->delete();
        return redirect()->back()->with('success', 'Movie deleted successfully');
    }
    // search
    public function search(Request $request)
    {
        $search = $request->input('search');
        $posts = Srz_Cpt::where('post_title', 'like', '%'.$search.'%')->get();
        return view('search', compact('posts'));
    }

    // single view
    public function single(Srz_Cpt $movie)
    { 

        // views  field
        $views = get_field('views','movies', $movie->id);
        if($views){
            update_field('views', intval($views)+1, 'movies', $movie->id);
        }else{
            update_field('views', 1, 'movies', $movie->id);
        }

        return view('pages.single-movie', compact('movie'));
    }
   


}
