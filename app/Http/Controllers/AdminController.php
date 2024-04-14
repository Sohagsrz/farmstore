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

    }

    public function settingsStore(Request $request){
        
        unset($request['_token']);

        foreach($request->all() as $key => $value){ 
            update_option($key, $value);
        }
        return redirect()->back()->with('success', 'Settings updated successfully');
        

    }
    
}
