<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ufv;
use Auth;
use Carbon\Carbon;
use Session;
class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user()->getStorage(); //importante no borrar obliga a que el usuario se le asigne un almacen de acuerdo a base



        // dd(trim($extracto[1][1]));

        return view('home');
    }
}
