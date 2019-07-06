<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\ArticleHistory;
use App\Article;
use App\ArticleIncome;
use App\ArticleRequest;
use App\User;
use App\Stock;
use App\Storage;
use App\Ufv;
use Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Log;

class ReportExcelController extends Controller
{
	public function index()
    {
    	$articulos = \DB::table('sisme.article_histories')
                ->join('sisme.articles as art', 'sisme.article_histories.article_id', '=', 'art.id')
                ->join('sisme.categories as cat', 'art.category_id', '=', 'cat.id')
                ->join('sisme.article_income_items as ing', 'sisme.article_histories.article_income_item_id', '=', 'ing.id')
                ->join('sisme.units as uni', 'art.unit_id', '=', 'uni.id')
                ->leftjoin('sisme.article_request_items as sali', 'sisme.article_histories.article_request_item_id', '=', 'sali.id')
                ->select('art.code as codigo','art.name as detalle', 'cat.name as categoria','ing.cost as ingcost', 'uni.name as unidad', 'ing.quantity as ingcant', 'article_histories.article_income_item_id',DB::raw('sum(article_histories.quantity_desc) as quantity'))
                ->groupBy('article_histories.article_income_item_id', 'codigo', 'detalle', 'categoria', 'ingcost', 'unidad', 'ingcant')
                //->where('article_histories.type', 'Entrada')
                ->get();
       // echo $articulos;
       return view('reportExcel.index');
    }

      public function rptInventarioExcel($dia_inicio,$mes_inicio,$anio_inicio)
    {
         //return $dia;
        $dia = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $date=$dia;
        $storage=Auth::user()->getStorage();
       // return $storage;
         // return $date;
        $articulos =\DB::table('sisme.stocks')
                ->join('sisme.articles as art', 'sisme.stocks.article_id', '=', 'art.id')
                ->join('sisme.units as uni', 'art.unit_id', '=', 'uni.id')
                ->join('sisme.categories as cat', 'art.category_id', '=', 'cat.id')
                ->select('art.code as codigo','art.name as detalle', 'uni.name as unidad', 'cat.name as categoria', 'stocks.article_id',DB::raw('sum(stocks.quantity) as quantity'))
                ->where(DB::raw('cast(stocks.created_at as date)'),'=',$dia)
                ->where('stocks.storage_id','=',$storage->id)
                ->groupBy('stocks.article_id', 'codigo', 'detalle', 'unidad', 'categoria')
                ->get();

		Excel::create('rptInventario', function($excel)  use ($articulos, $date) {
		    $excel->sheet('rptInventario', function($sheet)  use ($articulos, $date){
		    $sheet->loadView('reportExcel.rptInventario',  array('articulos'=>$articulos), array('date'=>$date));
		    });

		})->export('xls');
    }

      public function rptInventarioExcelRangos($dia_inicio,$mes_inicio,$anio_inicio,$dia_fin,$mes_fin,$anio_fin)
    {
         //return $dia;
        $fechainicial = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $fechafinal = $anio_fin . "-" . $mes_fin . "-" . $dia_fin;
        // return $fechainicial;
        // return $fechafinal;
        $date = $fechainicial." "."AL"." ".$fechafinal;
        $storage=Auth::user()->getStorage();
        
        $articulos = \DB::table('sisme.stocks')
                ->join('sisme.articles as art', 'sisme.stocks.article_id', '=', 'art.id')
                ->join('sisme.units as uni', 'art.unit_id', '=', 'uni.id')
                ->join('sisme.categories as cat', 'art.category_id', '=', 'cat.id')
                ->select('art.code as codigo','art.name as detalle', 'uni.name as unidad', 'cat.name as categoria', 'stocks.article_id',DB::raw('sum(stocks.quantity) as quantity'))
                ->where(DB::raw('cast(stocks.created_at as date)'),'>=',$fechainicial)
                ->where(DB::raw('cast(stocks.created_at as date)'),'<=',$fechafinal)
                ->where('stocks.storage_id','=',$storage->id)
                ->groupBy('stocks.article_id', 'codigo', 'detalle', 'unidad', 'categoria')
                ->get();
        // return $articulos;
        Excel::create('rptInventarioRango', function($excel)  use ($articulos, $date) {
            $excel->sheet('rptInventarioRango', function($sheet)  use ($articulos, $date){
            $sheet->loadView('reportExcel.rptInventarioRango',  array('articulos'=>$articulos), array('date'=>$date));
            });

        })->export('xls');
    }


     public function rptResumidoExcel($dia_inicio,$mes_inicio,$anio_inicio)
    {
        $resdia = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $date=$resdia;
        $storage=Auth::user()->getStorage();
        $articulos = \DB::table('sisme.articles')
                        ->join('sisme.stocks as stock','sisme.articles.id','=','stock.article_id')
                        ->join('sisme.units as uni','sisme.articles.unit_id','=','uni.id')
                        ->select('articles.id as article_id','articles.code as codigo','articles.name as detalle', 'uni.name as unidad')
                        ->groupBy('articles.id','unidad')
                        ->get();
        //return $articulo;                
		Excel::create('rptResumen', function($excel)  use ($articulos, $date) {
		    $excel->sheet('New sheet', function($sheet)  use (&$articulos, $date){
		        $sheet->loadView('reportExcel.rptResumido', array('articulos'=>$articulos), array('date'=>$date));
		    });

		})->export('xls');

       // return view('reportExcel.rptResumido', compact('articulos','date'));
    }

    public function rptResumidoExcelRangos($dia_inicio,$mes_inicio,$anio_inicio,$dia_fin,$mes_fin,$anio_fin)
    {
        $fechainicial = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $fechafinal = $anio_fin . "-" . $mes_fin . "-" . $dia_fin;
        // return $fechainicial;
        // return $fechafinal;
        return $fechafinal;
        $date = $fechainicial." "."AL"." ".$fechafinal;
        $storage=Auth::user()->getStorage();
        $articulos = \DB::table('sisme.articles')
                        ->join('sisme.stocks as stock','sisme.articles.id','=','stock.article_id')
                        ->join('sisme.units as uni','sisme.articles.unit_id','=','uni.id')
                        ->select('articles.id as article_id','articles.code as codigo','articles.name as detalle', 'uni.name as unidad')
                        ->groupBy('articles.id','unidad')
                        ->get();
        //return $articulos;

        Excel::create('rptResumenRango', function($excel)  use ($articulos, $date, $fechainicial, $fechafinal) {
            $excel->sheet('New sheet', function($sheet)  use ($articulos, $date, $fechainicial, $fechafinal){
                $sheet->loadView('reportExcel.rptResumidorango', array('articulos'=>$articulos), array('date'=>$date), array('fechainicial'=>$fechainicial), array('fechafinal'=>$fechafinal));
            });

        })->export('xls');
    }

       public function rptIngresoAlmExcel()
    {
        $user= DB::table('public._bp_personas')
                ->where('prs_id','=',Auth::user()->usr_prs_id)
                ->first();
        $usr =collect($user);
        $articulos = Stock::where('storage_id',Auth::user()->getStorage()->id)->select('article_id',DB::raw('sum(stocks.quantity) as quantity'))->groupBy('stocks.article_id')->get();
        Excel::create('rptMensual', function($excel)  use ($articulos) {
            $excel->sheet('New sheet', function($sheet)  use (&$articulos){
                $sheet->loadView('reportExcel.rptMensual');
            });
        })->export('xls');
    }

    public function rptMensualExcel($mes, $anio)
    {
        // return $mes;
        $anio1 = $anio;        
        $diafinal = date("d", mktime(0, 0, 0, $mes + 1, 0, $anio1));
        $fechainicial = $anio1 . "-" . $mes . "-01";
        // return $fechainicial;
        $fechafinal = $anio1 . "-" . $mes . "-" . $diafinal;
        // return $fechafinal;
        $storage=Auth::user()->getStorage();
        if($mes==1)
        {
            $mes='ENERO';
        }if($mes==2)
        {
            $mes='FEBRERO';
        }if($mes==3)
        {
            $mes='MARZO';
        }if($mes==4)
        {
            $mes='ABRIL';
        }if($mes==5)
        {
            $mes='MAYO';
        }if($mes==6)
        {
            $mes='JUNIO';
        }if($mes==7)
        {
            $mes='JULIO';
        }if($mes==8)
        {
            $mes='AGOSTO';
        }if($mes==9)
        {
            $mes='SEPTIEMBRE';
        }if($mes==10)
        {
            $mes='OCTUBRE';
        }if($mes==11)
        {
            $mes='NOVIEMBRE';
        }if($mes==12)
        {
            $mes='DICIEMBRE';
        }

        $articulos = \DB::table('sisme.article_income_items')
                        ->join('sisme.article_incomes as income','sisme.article_income_items.article_income_id','=','income.id')
                        ->join('sisme.articles as art', 'sisme.article_income_items.article_id', '=', 'art.id')
                        ->join('sisme.categories as cat', 'art.category_id', '=', 'cat.id')
                        ->join('sisme.units as uni', 'art.unit_id', '=', 'uni.id')
                        ->select('article_income_items.id as idng','art.code as codigo','art.name as detalle', 'cat.name as categoria','article_income_items.cost as ingcost', 'uni.name as unidad', DB::raw('sum(article_income_items.quantity) as quantitytot'))
                        ->where('income.storage_id','=',$storage->id)
                        ->groupBy('idng','codigo', 'detalle', 'categoria', 'ingcost','unidad')
                        ->orderby('codigo')
                        ->get();
       
        Excel::create('rptMensual', function($excel)  use ($articulos,$mes) {
            $excel->sheet('New sheet', function($sheet)  use (&$articulos,$mes){
                $sheet->loadView('reportExcel.rptMensual', array('articulos'=>$articulos), array('mes'=>$mes));
            });
        })->export('xls');
    }

    public function rptIngresoGeneralExcel($dia_inicio,$mes_inicio,$anio_inicio)
    {
        $ing = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $date=$ing;
        $ingresos = ArticleIncome::join('sisme.storages as sto', 'sisme.article_incomes.storage_id','=','sto.id')
                                ->join('sisme.article_income_items as item', 'sisme.article_incomes.id', '=', 'item.article_income_id')
                                ->join('sisme.articles as art', 'item.article_id', '=', 'art.id')
                                ->select('sto.name as almacen', 'article_incomes.id as num', 'art.code as codigo', 'art.name as articulo', 'item.quantity as cantidad', 'item.cost as costo')
                                ->where('article_incomes.storage_id','=', Auth::user()->getStorage()->id)
                                ->where(DB::raw('cast(article_incomes.created_at as date)'),'=',$ing)
                                ->get();
        // return $ingresos;
        Excel::create('rptGeneralIngreso', function($excel)  use ($ingresos, $date) {
            $excel->sheet('rptGeneralIngreso', function($sheet)  use ($ingresos, $date){
                $sheet->loadView('reportExcel.rptIngresoGeneral', array('ingresos'=>$ingresos), array('date'=>$date));
            });
        })->export('xls');
    }

     public function rptIngresoGeneralExcelRango($dia_inicio,$mes_inicio,$anio_inicio,$dia_fin,$mes_fin,$anio_fin)
    {
        $fechainicial = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $fechafinal = $anio_fin . "-" . $mes_fin . "-" . $dia_fin;
        // return $fechainicial;
        // return $fechafinal;
        $date = $fechainicial." "."AL"." ".$fechafinal;
        $ingresos = ArticleIncome::join('sisme.storages as sto', 'sisme.article_incomes.storage_id','=','sto.id')
                                ->join('sisme.article_income_items as item', 'sisme.article_incomes.id', '=', 'item.article_income_id')
                                ->join('sisme.articles as art', 'item.article_id', '=', 'art.id')
                                ->select('sto.name as almacen', 'article_incomes.id as num', 'art.code as codigo', 'art.name as articulo', 'item.quantity as cantidad', 'item.cost as costo')
                                ->where('article_incomes.storage_id','=', Auth::user()->getStorage()->id)
                                ->where(DB::raw('cast(article_incomes.created_at as date)'),'>=',$fechainicial)
                                ->where(DB::raw('cast(article_incomes.created_at as date)'),'<=',$fechafinal)
                                ->get();
        // return $ingresos;
        Excel::create('rptIngresoGeneralRango', function($excel)  use ($ingresos, $date) {
            $excel->sheet('rptIngresoGeneralRango', function($sheet)  use ($ingresos, $date){
                $sheet->loadView('reportExcel.rptIngresoGeneralRango', array('ingresos'=>$ingresos), array('date'=>$date));
            });
        })->export('xls');
    }

    public function rptIngresoSalidasExcel($dia_inicio,$mes_inicio,$anio_inicio)
    {
        $idsal = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $date=$idsal;
        $salidas = ArticleRequest::join('sisme.storages as sto', 'sisme.article_requests.storage_origin_id','=','sto.id')
                                ->join('sisme.article_request_items as item', 'sisme.article_requests.id', '=', 'item.article_request_id')
                                ->join('sisme.articles as art', 'item.article_id', '=', 'art.id')
                                ->select('sto.name as almacen', 'article_requests.id as num', 'art.code as codigo', 'art.name as articulo', 'item.quantity as cantidad', 'item.quantity_apro as cantapro')
                                ->where('article_requests.storage_origin_id','=', Auth::user()->getStorage()->id)
                                ->where(DB::raw('cast(article_requests.created_at as date)'),'=',$idsal)
                                ->get();
        // return $salidas;
        Excel::create('rptSalidaGeneral', function($excel)  use ($salidas, $date) {
            $excel->sheet('rptSalidaGeneral', function($sheet)  use ($salidas, $date){
                $sheet->loadView('reportExcel.rptSalidaGeneral', array('salidas'=>$salidas), array('date'=>$date));
            });
        })->export('xls');
    }

    public function rptIngresoSalidasExcelRango($dia_inicio,$mes_inicio,$anio_inicio,$dia_fin,$mes_fin,$anio_fin)
    {
         $fechainicial = $anio_inicio . "-" . $mes_inicio . "-". $dia_inicio;
        $fechafinal = $anio_fin . "-" . $mes_fin . "-" . $dia_fin;
        // return $fechainicial;
        // return $fechafinal;
        $date = $fechainicial." "."AL"." ".$fechafinal;
        $salidas = ArticleRequest::join('sisme.storages as sto', 'sisme.article_requests.storage_origin_id','=','sto.id')
                                ->join('sisme.article_request_items as item', 'sisme.article_requests.id', '=', 'item.article_request_id')
                                ->join('sisme.articles as art', 'item.article_id', '=', 'art.id')
                                ->select('sto.name as almacen', 'article_requests.id as num', 'art.code as codigo', 'art.name as articulo', 'item.quantity as cantidad', 'item.quantity_apro as cantapro')
                                ->where('article_requests.storage_origin_id','=', Auth::user()->getStorage()->id)
                                ->where(DB::raw('cast(article_requests.created_at as date)'),'>=',$fechainicial)
                                ->where(DB::raw('cast(article_requests.created_at as date)'),'<=',$fechafinal)
                                ->get();
        // return $salidas;
        Excel::create('rptSalidaGeneralRango', function($excel)  use ($salidas, $date) {
            $excel->sheet('rptSalidaGeneralRango', function($sheet)  use ($salidas, $date){
                $sheet->loadView('reportExcel.rptSalidaGeneralRango', array('salidas'=>$salidas), array('date'=>$date));
            });
        })->export('xls');
    }


    public function listalmacenes()
    {
        $data = Storage::select('id', 'name')->get();
        return view('reportExcel.rptalmacen', compact('data'));
    }

    public function listalmacenes1($id)
    {
        $almacenes = ArticleIncome::join('sisme.storages as sto', 'sisme.article_incomes.storage_id','=','sto.id')
                                ->join('sisme.article_income_items as item', 'sisme.article_incomes.id', '=', 'item.article_income_id')
                                ->join('sisme.articles as art', 'item.article_id', '=', 'art.id')
                                ->select('sto.name as almacen', 'article_incomes.id as num', 'art.code as codigo', 'art.name as articulo', 'item.quantity as cantidad', 'item.cost as costo')
                                ->where('article_incomes.storage_id',$id)
                                ->get();
         return response()->json($almacenes);                      
    }
    public function listalmacenesSal()
    {
         $data = Storage::select('id', 'name')->get();
        return view('reportExcel.rptalmacenSalida', compact('data'));
    }

    public function listalmacenesSal1($id)
    {
        $almacenes = ArticleRequest::join('sisme.storages as sto', 'sisme.article_requests.storage_origin_id','=','sto.id')
                                ->join('sisme.article_request_items as item', 'sisme.article_requests.id', '=', 'item.article_request_id')
                                ->join('sisme.articles as art', 'item.article_id', '=', 'art.id')
                                ->select('sto.name as almacen', 'article_requests.id as num', 'art.code as codigo', 'art.name as articulo', 'item.quantity as cantidad', 'item.quantity_apro as cantapro')
                                ->where('article_requests.storage_origin_id',$id)
                                ->get();
        return response()->json($almacenes);
    }

    // REPORTE DE UFV EN Excel
    public function report_ufv()
    {
        // dd("REPORTE UFV");
        $ufvs = Ufv::get();
        // dd($ufvs);
        Excel::create('rptUfv', function($excel)  use ($ufvs) {
            $excel->sheet('New sheet', function($sheet)  use (&$ufvs){
                $sheet->loadView('reportExcel.rptUfv');
            });
        })->export('xls');
    }

}
