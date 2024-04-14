<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $page= request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_LIMIT',10);
        $offset= ($page-1)*$limit;
        $total_users= User::count();
        $total_pages= ceil($total_users/$limit);
        $users= User::orderBy('id','desc')->paginate(
            $limit,
            ['*'],
            'page',
            $page
        );
        return view('admin.pages.users.index', compact('users','total_users','total_pages'));
    }
         

    public function search(Request $request)
    { 


    }

    //update user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        
        $user->save();
        return redirect()->back()->with('success', 'User updated successfully');
    }


    // view user
    public function view($id)
    {
        $user = User::find($id);
        return view('user.view', compact('user'));
         
    }
    //soft delete user
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
    //add user
    public function add()
    {
        return view('admin.pages.users.add-user');
    }
    


}
