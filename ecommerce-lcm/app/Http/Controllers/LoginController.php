<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Apenas exibe a view do login
    public function showForm()
    {
        return view('auth.login');
    }
}
