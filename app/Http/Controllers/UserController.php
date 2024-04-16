<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $name = "Users";
        $page= request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_PER_PAGE',10);
        $offset= ($page-1)*$limit;
        $total_users= User::count();
        $total_pages= ceil($total_users/$limit);
        // not the current user id
        $users= User::orderBy('id','desc') 
        // ->whereNot('id',auth()->user()->id)
        ->paginate(
            $limit,
            ['*'],
            'page',
            $page
        );

        

        return view('admin.pages.users.index', compact('users','total_users','total_pages','name'));
    }
         
    // edit user
    public function edit($id)
    {
        $name = "Edit User";
        $user = User::find($id);
        $roles = Role::all();
        $user->role = $user->getRole();
        return view('admin.pages.users.edit-user', compact('user', 'name' ,'roles'));
    }

    //add user
    public function add()
    {
        $name = "Add User";
        $roles = Role::all();
        return view('admin.pages.users.add-user', compact('roles', 'name')); 
    }
    // store user
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required', 
            'password' => 'required',
        ]);
        // encrypt password
        $request['password'] = bcrypt($request->password);
        $user = User::create($request->all());
        //set role
        $user->assignRole($request->role);

        // error or success redirect
        if ($user) {
            // redirect to edit
            return redirect()->route('admin.users.edit', $user->id)->with('success', 'User added successfully'); 
        }
        return redirect()->back()->with('error', 'User not added');
    }

    public function search(Request $request)
    { 

        $search = $request->input('q');
        $users = User::where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('email', 'LIKE', '%' . $search . '%')
            ->orWhere('role', 'LIKE', '%' . $search . '%')
            ->orWhere('status', 'LIKE', '%' . $search . '%')
            ->get();
        return view('admin.pages.users.index', compact('users'));

    }

    //update user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($request->has('password')){
            $request['password'] = bcrypt($request->password);
        }else{
            unset($request->password);
        }
        $user->update($request->all()); 
        // error or success redirect
        if ($user) {
            return redirect()->back()->with('success', 'User updated successfully');
        }
        return redirect()->back()->with('error', 'User not updated');
          
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
        // soft delete user
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
    


}
