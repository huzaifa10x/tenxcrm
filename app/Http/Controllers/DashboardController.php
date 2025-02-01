<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $role = $user->roles->first()->name;

        return view('dashboard', compact('role'));
    }
}
