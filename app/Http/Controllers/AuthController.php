<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('logged_in')) {
            return redirect('/greetings');
        }
        
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        session(['captcha_answer' => $num1 + $num2]);
        
        return view('auth.login', compact('num1', 'num2'));
    }

    public function processLogin(Request $request)
    {
        if ((int)$request->captcha !== session('captcha_answer')) {
            return back()->with('error', 'Jawaban perhitungan matematika salah.');
        }

        // Mockup Database logic
        if ($request->username === 'Admin Roren' && $request->password === 'r0rEn9$pr!or!ta5') {
            session(['logged_in' => true, 'username' => 'Admin Roren']);
            return redirect('/greetings');
        }
        
        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->forget('logged_in');
        return redirect('/login');
    }
}
