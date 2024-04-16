<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category_Relation;
use App\Models\Srz_Cpt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    // profile view
    public function profile()
    {
        $name = "Profile";
        $user = Auth::user();
        return view('admin.pages.profile', compact('name', 'user')); 
    }
    // settings view
    public function settings()
    {
        $name = "Settings";
        return view('admin.pages.settings', compact('name')); 
    }
     

    public function settingsStore(Request $request){
        
        unset($request['_token']);

        foreach($request->all() as $key => $value){ 
            update_option($key, $value);
        }
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
    //proflile store
    public function profileStore(Request $request){
        $user = User::find(Auth::user()->id);
         
        if($request->has('password')){
            $request['password'] = bcrypt($request->password);
        }else{
            unset($request->password);
        }
        $user->update($request->all());
        // error or success redirect
        if ($user) {
            return redirect()->back()->with('success', 'Profile updated successfully');
        }
        return redirect()->back()->with('error', 'Profile not updated');
         
 
    }

    public function users()
    {
        return view('admin.users');
    }
    
}
