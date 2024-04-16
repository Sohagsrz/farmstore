<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category_Relation;
use App\Models\Srz_Cpt;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;


class newsController extends Controller
{
    public $type = 'news';

    public function index()
    { 
        $type= "news";
        $name= 'news';


        $page =  request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_LIMIT',10);
        $offset= ($page-1)*$limit;
        $total_news= Srz_Cpt::where([
            'post_type' =>$type,
            // 'status' => 1
            ])->count();
        $total_pages= ceil($total_news/$limit);
        $news= Srz_Cpt::where([
            'post_type' =>$type,
            // 'status' => 1
            ])->orderBy('id','desc')->paginate(
                $limit,
                ['*'],
                'page',
                $page
            );
        
        return view('admin.pages.news.index', compact('news','type','name','total_news','total_pages'));
   
  

    }
    // add 
    public function add()
    {
        $type= "news";
        $name= 'news';
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get();
        return view('admin.pages.news.add-news', compact('categories','type','name'));
    
    }

    //store 
    public function store(Request $request)
    {  
        
        $type = 'news';

        //validate request
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required', 
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        //add news
        $news = new Srz_Cpt();
        $news->post_title = $request->input('post_title');
        $news->post_content = $request->input('post_content');
        $news->post_type = 'news';
        $news->post_author = Auth::id();
        $news->post_status = 'publish';
        $news->save();
        
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
         
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $news->save();
        // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $news->id);


            //set thumbNail

            // $news->thumbnail = $name;
            // $news->save();
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), $type, $news->id);
            }
             
        }
        //watch urls
        $watch_urls = $request->input('watch_urls');
        if($watch_urls){
            //remove empty values
            $watch_urls = array_filter($watch_urls);

             update_field('watch_urls',  ($watch_urls), $type, $news->id);
        }
        //download urls
        $download_urls = $request->input('download_urls');
        if($download_urls){
            //remove empty values
            $download_urls = array_filter($download_urls);

            update_field('download_urls',  ($download_urls), $type, $news->id);
        }


        //redirect with success message
        return redirect()->route('admin.news.edit', [
            'id' => $news->id
        
        ])->with('success', 'News added successfully');

    }
    // edit 
    public function edit(Request $request, $id)
    {

        $name = 'news';
        $type = 'news';

        
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get();
 
        $newss = Srz_Cpt::find($id);
        $selected_categories = Category_Relation::where('post_id', $id)->pluck('category_id')->toArray();
        
        return view('admin.pages.news.edit-news', compact('newss', 'categories', 'selected_categories','type','name'));

    }
    // update
    public function update(Request $request, $id)
    {
        $name = 'news';
        $type = 'news';
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required', 
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $news = Srz_Cpt::find($id);
        $news->post_title = $request->input('post_title');
        $news->post_content = $request->input('post_content');
        $news->post_author = Auth::id();
        $news->post_status = $request->input('post_status', 'publish');

        $news->save();
         
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $news->save();
            // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $news->id);
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), 'news', $news->id);
            }
        }
        //watch urls
        $watch_urls = $request->input('watch_urls', []);
        if($watch_urls){
            $watch_urls = array_filter($watch_urls);
            update_field('watch_urls',  ($watch_urls), 'news', $news->id);
        }
        //download urls
        $download_urls = $request->input('download_urls', []);
        if($download_urls){
            
            $download_urls = array_filter($download_urls);
            update_field('download_urls',  ($download_urls), 'news', $news->id);
        }
        //redirect with success message

        return redirect()->back()->with('success', 'News updated successfully');
    }
    // delete
    public function delete($id)
    {
        $news = Srz_Cpt::find($id);
        $news->delete();
        return redirect()->back()->with('success', 'News deleted successfully');
    }
    // search
    public function search(Request $request)
    {
        $search = $request->input('search');
        $posts = Srz_Cpt::where('post_title', 'like', '%'.$search.'%')->get();
        return view('search', compact('posts'));
    }

    // single view
    public function single(Srz_Cpt $news)
    { 

        // views  field
        $views = get_field('views','news', $news->id);
        if($views){
            update_field('views', intval($views)+1, 'news', $news->id);
        }else{
            update_field('views', 1, 'news', $news->id);
        }

        return view('pages.single-news', compact('news'));
    }
   


}
