<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BantuanController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        return view('bantuan.index', compact('role'));
    }
}