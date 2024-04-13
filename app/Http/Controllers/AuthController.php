<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    public function login()
    {
        return View::make('pages.home');
    }

    public function postlogin(Request $request)
    { 
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/#loggedin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'wrong password',
            
        ])->onlyInput('email');

    } 
    public function postRegister(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email','unique:users'],
            'password' => ['required', 'min:5'],
        ]);


        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        Auth::login($user);

        return redirect('/#loggedin');
    }

    //logout 
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function register()
    {
        return view('register');
    }
    public function forgot()
    {
        return view('forgot');
    }
    public function reset()
    {
        return view('reset');
    }

}
