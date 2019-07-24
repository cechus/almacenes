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
//        $user = Auth::user()->getStorage(); //importante no borrar obliga a que el usuario se le asigne un almacen de acuerdo a base
        $usr = \DB::table('sisme.user_storage')
        ->where('user_usr_id','=',Auth::user()->usr_id)
        ->first();
        $usr = collect($usr);

        //SOLICITUDES
        $aprobado = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$usr['storage_id'])
        ->where('state', '=', 'Aprobado')
        ->count();

        $entregado = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$usr['storage_id'])
        ->where('state', '=', 'Entregado')
        ->count();

        $pendiente = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$usr['storage_id'])
        ->where('state', '=', 'Pendiente')
        ->count();

        $rechazado = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$usr['storage_id'])
        ->where('state', '=', 'Rechazado')
        ->count();
        return view('home',compact('abrobado','entregado','pendiente','rechazado'));
    }
}
