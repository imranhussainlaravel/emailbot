<?php

// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmailController extends Controller
{
    public function showLoginForm()
    {
        return view('loginpage');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $User = User::where('email', $credentials['email'])->first();

        if ($User && Hash::check($credentials['password'], $User->password)) {
            // session(['customer' => $User]);
            // return view('welcome');

            return "Login Successful! Welcome, " . $User->name;
        } else {
            // return view('welcome');

            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }
    public function index(){
        return view("dashboard");
    }
}

