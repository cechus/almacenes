<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArticleRequest;
use App\ArticleRequestItem;
use Illuminate\Support\Facades\Auth;
use App\Stock;
use App\Article;
use App\Storage;
use App\Provider;
use App\ArticleIncome;
use App\ArticleIncomeItem;
use App\Person;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\ArticleHistory;
use App\UserHistory;
use App\Employee;
use Carbon\Carbon;
use Util;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // para articulos de almacen
        $request_articles = ArticleRequest::where('storage_destiny_id',Auth::user()->getStorage()->id)
                                            ->where('type','Funcionario')
                                            ->orderBy('id','DESC')
                                            ->get();
        // return $request_articles;
        $count = 1;

        return view('request.index',compact('request_articles','count'));
    }

    public function index_storage()
    {
        // para articulos de almacen
        $request_articles = ArticleRequest::where('storage_destiny_id',Auth::user()->getStorage()->id)
                                            ->where('type','Almacen')
                                            ->where('state','!=','Entregado')
                                            ->get();
        $count = 1;
        return view('request.index_storage',compact('request_articles','count'));
    }
    public function index_storage_done()
    {
        $request_articles = ArticleRequest::where('storage_origin_id',Auth::user()->getStorage()->id)
                                            ->where('type','Almacen')
                                            //->where('state','Entregado')
                                            ->get();
        $title = "Solicitudes de Traspaso Realizadas ".Auth::user()->getStorage()->name;
        $count = 1;
        return view('request.index_storage_done',compact('request_articles','count','title'));
    }

    public function index_person()
    {
        $request_articles = ArticleRequest::join('sisme.article_request_items as req', 'sisme.article_requests.id','=','req.article_request_id')
                                            ->join('sisme.storages as stores', 'sisme.article_requests.storage_origin_id','=','stores.id')
                                            ->select('article_requests.id', 'article_requests.created_at', 'stores.name', 'article_requests.state','article_requests.correlative', DB::raw('sum(req.quantity) as quantity'))
                                            ->where('employee_id',Auth::user()->employee->id)
                                            ->where('storage_destiny_id',Auth::user()->getStorage()->id)
                                            ->groupBy('article_requests.id', 'article_requests.created_at', 'stores.name', 'article_requests.state','article_requests.correlative')
                                            ->orderBydesc('id')
                                            // ->where('type','=','Funcionario')
                                            // ->orderBy('id','DESC')
                                            ->get();
        $histories = ArticleRequestItem::with('article')->join('sisme.article_requests','sisme.article_requests.id','=','article_request_items.article_request_id')
                                            ->where('article_requests.employee_id','=',Auth::user()->employee->id)
                                            ->where('article_requests.state','=','Aprobado')
                                            ->orWhere('article_requests.state','=','Entregado')
                                            ->select('article_request_items.*')
                                            ->get();
        // return $request_articles;
         // return $request_articles;
        $count = 1;
        return view('request.index_person',compact('request_articles','count','histories'));
    }

    public function transfer()
    {
        // return 'test';
        $request_articles = ArticleRequest::where('storage_origin_id',Auth::user()->getStorage()->id)
                                            ->where('type','=','Almacen')
                                            ->get();
        return view('request.storage.index',compact('request_articles'));
    }

    public function create_transfer(){
      //  return Auth::user()->usr_prs_id;
        $user = Employee::where('id','=',Auth::user()->usr_prs_id)->get();
        $storages = Storage::where('id','!=',Auth::user()->getStorage()->id)->get();
        return view('request.storage.create',compact('storages','user'));
    }

    public function check_transfer($article_request_id){

        $article_request = ArticleRequest::with('person')->find($article_request_id);
        // return $article_request;
        $article_request_items = $article_request->article_request_items;

        foreach($article_request_items as $items)
        {
            $items->stock = Stock::where('article_id',$items->article->id)
                            ->where('storage_id',Auth::user()->getStorage()->id)
                            ->select(DB::raw('sum(quantity) as stock'))
                            ->groupBy('article_id')
                            ->first();
        }
        // return $article_request_items;
        $providers = Provider::all();

        return view('request.storage.check',compact('article_request','article_request_items','providers'));
    }

    public function store_transfer_confirm(Request $request){

        // return $request->all();
        $articles = json_decode($request->articles);
        $article_request = ArticleRequest::find($request->article_request_id);
        $article_request->state = "Aprobado";
        $article_request->save();
        // return $request->all();
        //se asume que todo esta ingresando a a almacen
        Log::info("inegresando modo almacen");
        //realizar solicitud de ingreso
        $last_income = ArticleIncome::where('storage_id',$article_request->storage_origin_id)->max('correlative');
        $counter=0;
        // return $counter;
        if(!$last_income){
            $counter=1;
        }
        else{
            $counter=$last_income+1;
        }

        $article_income = new ArticleIncome;

        $article_income->provider_id = $request->provider_id;
        $article_income->correlative = $counter;
        $article_income->employee_id =$article_request->employee_id;
        $article_income->storage_id = $article_request->storage_origin_id;
        $article_income->type = $request->type;
        $article_income->total_cost = $request->total_cost;
        // return $article_income;
        $article_income->save();
        // return $article_income;
        //hasta aqui el registro del nuevo ingreso

        // return $article_request;
        foreach($articles as $article){

            // return json_encode($article);
            $article_request_item = ArticleRequestItem::find($article->id);
            $article_request_item->quantity_apro =$article->quantity_apro;
            //obtener lista de los productos en stock de este almacen

            $stocks = Stock::where('article_id','=',$article->article_id)
                            ->where('storage_id',$article_request->storage_destiny_id)
                            ->where('quantity','>',0)
                            ->orderBy('created_at','Asc')
                            ->get();

            $quantity=$article->quantity_apro;
            Log::warning('cantidad aprobada :'.$quantity);

            //Modulo delicado tener cuidado con este algoritmo  XD

            foreach($stocks as $stock)
            {
                 Log::warning('stock: '.$stock->article->name.'  cant:'.$stock->quantity);

                    if($quantity>0)
                    {
                        if($quantity >= $stock->quantity)
                        {
                            Log::info($quantity.'>='.$stock->quantity);
                            $quantity = $quantity - $stock->quantity;
                            $descuento = $stock->quantity;//descuento que se realizo
                            $stock->quantity = 0;
                        }else
                        {
                            Log::info($quantity.'<'.$stock->quantity);
                            $stock->quantity = $stock->quantity - $quantity;
                            $descuento = $quantity;
                            $quantity = 0;

                        }
                        Log::info('new cant :'.$quantity);
                        Log::info('new stock:'.$stock->quantity);

                        $article_history = new ArticleHistory;
                        $article_history->article_request_item_id =$article_request_item->id;//para salida
                        $article_history->article_income_item_id =$stock->article_income_item_id;//para costo de ingreso
                        $article_history->article_id =$article_request_item->article_id;
                        $article_history->type ='Salida';
                        $article_history->quantity_desc =$descuento;
                        $article_history->storage_id = Auth::user()->getStorage()->id;
                        $article_history->save();
                    }
            }

            // return $stocks;
            $article_request_item->save();


                Log::info("generando solicitud item");
                //realizar solicitud con items e ingresar lo aprobado al almacen destino XD
                $article_income_item = new ArticleIncomeItem;
                $article_income_item->article_income_id = $article_income->id;
                $article_income_item->article_id = $article->article->id;
                $article_income_item->cost = $article->cost;
                $article_income_item->quantity = $article->quantity_apro;
                $article_income_item->save();
                Log::info(json_encode($article_income_item));
                $stock = new Stock;
                $stock->article_id = $article_income_item->article_id;
                $stock->storage_id = $article_income->storage_id;
                $stock->article_income_item_id = $article_income_item->id;
                $stock->quantity = $article->quantity_apro;
                $stock->cost = $article->cost;
                $stock->save();

        }


        return redirect('transfer_request');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article_request_id = Auth::user()->getStorage()->id;
        $article_request = Auth::user()->employee;
        // $article_request = ArticleRequest::with('person')->find($article_request_id);
         // return ($article_request);
        $articles = Article::with('category','unit')
                            ->join('sisme.stocks','stocks.article_id','=','articles.id')
                            ->where('storage_id',Auth::user()->getStorage()->id)
                            ->select('article_id','articles.name','articles.category_id','articles.unit_id',DB::raw('sum(stocks.quantity) as quantity_stock'))
                            ->groupBy('stocks.article_id','articles.name','articles.category_id','articles.unit_id')
                            ->get();
        return view('request.create',compact('articles','article_request'));
    }

    public function storageArticles($storage_id){

        $articles = Article::with('category','unit')
                            ->join('sisme.stocks','sisme.stocks.article_id','=','articles.id')
                            ->where('storage_id',$storage_id)
                            ->select('article_id','articles.name','articles.category_id','articles.unit_id',DB::raw('sum(sisme.stocks.quantity) as quantity_stock'))
                            ->groupBy('sisme.stocks.article_id','articles.name','articles.category_id','articles.unit_id')
                            ->get();

        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        logger($request->all());
        if($request->has('storage_destiny_id'))
        {
            $storage_destiny_id = $request->storage_destiny_id;
            $storage_origin_id = Auth::user()->getStorage()->id;

        }else{

            $storage_destiny_id =  Auth::user()->getStorage()->id;
            $storage_origin_id = Auth::user()->getStorage()->id;
        }

        $last_income = ArticleRequest::where('storage_destiny_id',$storage_destiny_id)->max('correlative');
        $counter=0;

        if(!$last_income)
        {
            $counter=1;
        }
        else{
            $counter=$last_income+1;
        }

        $articles = json_decode($request->articles);
        // return $articles;
        $article_request = new ArticleRequest;
        $article_request->storage_origin_id = $storage_origin_id;
        $article_request->storage_destiny_id = $storage_destiny_id;
        $article_request->employee_id = Auth::user()->employee->id;
        $article_request->correlative = $counter;

        if($request->has('type')){
            $article_request->type = $request->type;
        }

        $article_request->save();


        foreach($articles as $article)
        {
            $article_request_item = new ArticleRequestItem;
            $article_request_item->article_request_id = $article_request->id;
            $article_request_item->article_id = $article->article->article_id;
            $article_request_item->quantity = $article->quantity;
            $article_request_item->quantity_apro = $article->quantity;
            $article_request_item->save();
        }

        session()->flash('message','Se realizo la solicitud '.$article_request->correlative);
        session()->flash('url',url('request_note/'.$article_request->id));

        return redirect('request_person');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $article_request = ArticleRequest::with('employee')->find($id);
        $article_request_items = $article_request->article_request_items;
        $article_request->full_name = $article_request->employee->getFullName();
        // dd($article_request->full_name);
        // $article_request->full_name = User::find(Auth::user()->usr_id)->employee->getFullName();
        foreach($article_request_items as $items)
        {
            $items->stock = Stock::where('article_id',$items->article->id)
                            ->where('storage_id',Auth::user()->getStorage()->id)
                            ->select(DB::raw('sum(quantity) as stock'))
                            ->groupBy('article_id')
                            ->first();

            // array_push($articles,array('article'))
        }
        // return $article_request_items;
        $providers = Provider::all();
        // $articles;
       return view('request.check_request',compact('article_request','article_request_items','providers'));

    }

    public function approve($id)
    {
        $article_request = ArticleRequest::with('employee','article_request_items')->find($id);
        $article_request->full_name = $article_request->employee->getFullName();
                           // ->get();
        //historial solo de salidas
        $histories = ArticleRequestItem::with('article')->join('sisme.article_requests','sisme.article_requests.id','=','article_request_items.article_request_id')
                                        ->where('article_requests.employee_id','=',Auth::user()->employee->id)
                                        ->where('article_requests.state','=','Aprobado')
                                        ->orWhere('article_requests.state','=','Entregado')
                                        ->select('article_request_items.*')
                                        ->get();
        //return $histories;
      //  return $histories;

        foreach($article_request->article_request_items as $items)
        {
            $items->stock = Stock::where('article_id',$items->article->id)
                            ->where('storage_id',Auth::user()->getStorage()->id)
                            ->select(DB::raw('sum(quantity) as stock'))
                            ->groupBy('article_id')
                            ->first();

            // array_push($articles,array('article'))
        }
        // return $article_request_items;
        $providers = Provider::all();
        // $articles;
      //  return $histories;
        return view('request.approve_request',compact('article_request','providers', 'histories'));

    }

    //confirmacion del funcionario hdp
    public function confirmRequest(Request $request)
    {
        $articles = json_decode($request->articles);
        $article_request = ArticleRequest::find($request->article_request_id);
        $article_request->state = "Aprobado";
        $article_request->save();
        // return $request->all();
               // return $article_request;
        foreach($articles as $article){
            $article_request_item = ArticleRequestItem::find($article->id);
            $article_request_item->quantity_apro =$article->quantity_apro;
            //obtener lista de los productos en stock de este almacen
            // return $article;
            // return json_encode($article);
            $stocks = Stock::where('article_id','=',$article->article_id)
                            ->where('storage_id',$article_request->storage_destiny_id)
                            ->where('quantity','>',0)
                            ->orderBy('created_at','Asc')
                            ->get();
            // return $stocks;
            $quantity=$article->quantity_apro;
            Log::warning('cantidad aprobada funcionario:'.$quantity);
            // return $quantity;
            foreach($stocks as $stock)
            {
                 Log::warning('stock: '.$stock->article->name.'  cant:'.$stock->quantity);

                    if($quantity>0)
                    {
                        if($quantity >= $stock->quantity)
                        {
                            Log::info($quantity.'>='.$stock->quantity);
                            $quantity = $quantity - $stock->quantity;
                            $descuento = $stock->quantity;//descuento que se realizo
                            $stock->quantity =0;
                        }else
                        {
                            Log::info($quantity.'<'.$stock->quantity);
                            $stock->quantity = $stock->quantity - $quantity;
                            $descuento = $quantity;
                            $quantity = 0;

                        }
                        Log::info('new cant :'.$quantity);
                        Log::info('new stock:'.$stock->quantity);

                        $stock->save();
                        //registro de history manual XD
                        $article_history = new ArticleHistory;
                        $article_history->article_request_item_id =$article_request_item->id;//para salida
                        $article_history->article_income_item_id =$stock->article_income_item_id;//para costo de ingreso
                        $article_history->article_id =$article_request_item->article_id;
                        $article_history->type ='Salida';
                        $article_history->quantity_desc =$descuento;
                        $article_history->storage_id = Auth::user()->getStorage()->id;
                        $article_history->save();

                        $article_user = new UserHistory;
                        $article_user->article_request_item_id =$article_request_item->id;//para salida
                        $article_user->storage_id = Auth::user()->getStorage()->id;
                        $article_user->user_usr_id = Auth::user()->usr_id;
                        $article_user->type ='Salida';
                        $article_user->state ='Aprobado';
                        $article_user->save();
                    }
            }
            //el descuento al stock se puede dar con mas de 2 request_income_items
            //decidir cual tomar el primero o el ultimo o ningun

            // return $stocks;
            $article_request_item->save();

        }
        $article_request = ArticleRequest::find($request->article_request_id);
        $article_request->state = "Aprobado";
        // $number_correlative_out = ArticleRequest::select(DB::raw('MAX(correlative_out) as correlative_out_final'))->first();
        // $article_request->correlative_out = $number_correlative_out->correlative_out_final+1;
        $last_correlative_out = ArticleRequest::where('storage_origin_id',Auth::user()->getStorage()->id)->max('correlative_out');
        $counter_out=0;
        if(!$last_correlative_out){
            $counter_out=1;
        }
        else{
            $counter_out=$last_correlative_out+1;
        }
        $article_request->correlative_out = $counter_out;
        $article_request->save();

        session()->flash('message','Se aprobo la solicitud '.$article_request->correlative);
        session()->flash('url',url('out_note/'.$article_request->id));

        return redirect('request');
        // return $articles;
    }

    public function confirmApprove(Request $request)
    {
        $articles = json_decode($request->articles);
        $article_request1 = ArticleRequest::join('sisme.article_request_items as items','sisme.article_requests.id','=', 'items.article_request_id')->find($request->article_request_id);
        $article_request = ArticleRequest::find($request->article_request_id);
        $article_request->state = "Pendiente";
        $article_request->save();
      //  $article_request->state;
      //  return $article_request;

        $article_user = new UserHistory;
        $article_user->article_request_item_id =$article_request1->id;//para salida
        $article_user->storage_id = Auth::user()->getStorage()->id;
        $article_user->user_usr_id = Auth::user()->usr_id;
        $article_user->type ='Salida';
        $article_user->state ='Pendiente';
        $article_user->save();

        session()->flash('message','Solicitud Pendiente'.$article_request->correlative);
        return redirect('request');
        // return $articles;
    }

    public function delivery(Request $request){
        //
        $article_request = ArticleRequest::find($request->data['id']);
        $article_request->state ="Entregado";
        $article_request->save();
        // return $article_request;

        $article_user = new UserHistory;
        $article_user->article_request_item_id =$article_request->id;//para salida
        $article_user->storage_id = Auth::user()->getStorage()->id;
        $article_user->user_usr_id = Auth::user()->usr_id;
        $article_user->type ='Salida';
        $article_user->state ='Entregado';
        $article_user->save();
    }

    public function confirmDisApprove(Request $request)
    {
        $articles = json_decode($request->articles);
        $article_request1 = ArticleRequest::join('sisme.article_request_items as items','sisme.article_requests.id','=', 'items.article_request_id')->find($request->article_request_id);
        $article_request = ArticleRequest::find($request->article_request_id);
        $article_request->state = "Rechazado";
        $article_request->save();

        $article_user = new UserHistory;
        $article_user->article_request_item_id =$article_request1->id;//para salida
        $article_user->storage_id = Auth::user()->getStorage()->id;
        $article_user->user_usr_id = Auth::user()->usr_id;
        $article_user->type ='Salida';
        $article_user->state ='Rechazado';
        $article_user->save();


        session()->flash('message','Rechazado'.$article_request->correlative);
        return redirect('request');
        // return $articles;
    }

    public function disApprove(Request $request)
    {
        $articles = json_decode($request->articles);
        $article_request = ArticleRequest::find($request->article_request_id);
        $article_request->state = "Rechazado";
        $article_request->save();

        $article_user = new UserHistory;
        $article_user->article_request_item_id =$article_request->id;//para salida
        $article_user->storage_id = Auth::user()->getStorage()->id;
        $article_user->user_usr_id = Auth::user()->usr_id;
        $article_user->type ='Salida';
        $article_user->state ='Rechazado';
        $article_user->save();

        session()->flash('message','Rechazado'.$article_request->correlative);
        return redirect('request');
        // return $articles;
    }

    public function consume_record(Request $request)
    {
        // return $request->all();
        $article_requests = ArticleRequestItem::all();
        $storages = Storage::all();
        $record_quantity = [];
        $record_cost = [];
        $labels =[];
        $first_date = Carbon::createFromFormat('d/m/Y', $request->first_date) ;
        $first_date->hour = 0;
        $first_date->minute = 0;
        $first_date->second = 0;
        $second_date = Carbon::createFromFormat('d/m/Y', $request->second_date) ;
        $second_date->hour = 23;
        $second_date->minute = 59;
        $second_date->second = 59;
        // return $first_date;
        foreach ($storages as $storage){
            $quantity = 0;
            $cost = 0;
            $article_histories = ArticleHistory::where('storage_id',$storage->id)
                                                ->whereBetween('created_at', [$first_date, $second_date])
                                                ->where('type','Salida')
                                                ->get();
            // return $article_histories;
            foreach($article_histories as $article_history)
            {
                $quantity += $article_history->quantity_desc;
                $cost +=  ($article_history->quantity_desc * $article_history->article_income_item->cost);
            }

            // return $quantity;
            //usar en casos extremos pero de momento nos salvamos con article histories gracias yo del pasado XD
            // $article_requests = ArticleRequest::where('storage_destiny_id',$storage->id)
            //                                   ->where('state','Aprobado')
            //                                   ->orWhere('state','Engregado')
            //                                   ->get();
            // foreach($article_requests as $article_request)
            // {
            //     foreach($article_request->article_request_items as $artile_request_item)
            //     {
            //         $quantity += $artile_request_item->quantity;
            //         $cost +=  ($artile_request_item->quantity * $artile_request_item->article_income_item->cost);
            //     }
            // }

            array_push($record_quantity,$quantity);
            array_push($record_cost,$cost);
            array_push($labels,$storage->name);

        }

        // foreach($article_requests as $article_request)
        // {

        // }
        return response()->json(compact('record_quantity','record_cost','labels'));
        // return $request->all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
