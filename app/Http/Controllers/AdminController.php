<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __invoke()
    {
        $this->middleware('auth');
        return view('admin');
    }
}