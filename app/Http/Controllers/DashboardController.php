<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        // $user = new User;
        // dd($user->getAllControllers());
        //dd(session()->all());
        return Inertia::render('Dashboard');
    }
}
