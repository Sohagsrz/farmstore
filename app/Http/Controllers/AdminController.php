<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category_Relation;
use App\Models\Srz_Cpt;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
        
    public function search(Request $request)
    {
        $search = $request->input('search');
        $posts = \App\Models\Srz_Cpt::where('post_title', 'like', '%'.$search.'%')->get();
        return view('search', compact('posts'));
    }

    public function MovieStore(Request $request){
    }
   
    //delete

    public function MovieDelete($id)
    {
        $post = \App\Models\Srz_Cpt::find($id);
        $post->delete();
        return redirect()->back();
    }
    // edit
    public function edit($id)
    {
        
        $post = \App\Models\Srz_Cpt::find($id);
        return view('admin.pages.movies.edit-movie', compact('post'));
    }
    //update
    public function update(Request $request, $id)
    {
        $post = \App\Models\Srz_Cpt::find($id);
        $post->post_title = $request->input('post_title');
        $post->post_content = $request->input('post_content');
        $post->save();
        return redirect()->back();
    }
    
}
