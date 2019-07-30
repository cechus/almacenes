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
        $storage = Auth::user()->getStorage();

        //SOLICITUDES
        $aprobado = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$storage->id)
        ->where('state', '=', 'Aprobado')
        ->count();

        $entregado = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$storage->id)
        ->where('state', '=', 'Entregado')
        ->count();

        $pendiente = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$storage->id)
        ->where('state', '=', 'Pendiente')
        ->count();

        $rechazado = \DB::table('sisme.article_requests')
        ->where('storage_origin_id','=',$storage->id)
        ->where('state', '=', 'Rechazado')
        ->count();

        return view('home',compact('entregado','pendiente','rechazado','aprobado'));
    }
}
