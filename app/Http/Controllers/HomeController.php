<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index() {
        return view('login');
    }

    public function register() {
        return view('plantillas.register');
    }
}
