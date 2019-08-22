<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArticleIncome;
use App\ArticleIncomeItem;
use App\RequestChangeIncome;
use App\RequestChangeIncomeItem;
use App\RequestChangeOut;
use App\RequestChangeOutItem;
use App\Article;
use App\ArticleRequest;
use App\Stock;
use App\UserHistory;
use App\ArticleHistory;
use App\ArticleRequestItem;
use DB;
use Auth;
use Log;
class RequestChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //primero ver  request de solo la sucursal
        $request_incomes = [];
        $request_outs = [];

        $request_all_in = RequestChangeIncome::where('storage_id',Auth::user()->getStorage()->id)->get();
        $request_all_out = RequestChangeOut::where('storage_id',Auth::user()->getStorage()->id)->get();
       // return $request_all_out;


        if(Auth::user()->hasRole('Encargado de Almacen'))
        {
            $request_incomes = RequestChangeIncome::where('storage_id',Auth::user()->getStorage()->id)
                                                    ->where('state','Pendiente Aprobacion')
                                                    ->get();

            $request_outs = RequestChangeOut::where('storage_id',Auth::user()->getStorage()->id)
                                            ->where('state','Pendiente Aprobacion')
                                            ->get();
        }

        if(Auth::user()->hasRole('Administrador'))
        {
            $request_incomes = RequestChangeIncome::where('storage_id',Auth::user()->getStorage()->id)
                                                   // ->where('state','Pendiente Aprobacion' or 'Pendiente')
                                                    //->->where('state','Pendiente')
                                                    ->get();

            $request_outs = RequestChangeOut::where('storage_id',Auth::user()->getStorage()->id)
                                            //->where('state','Pendiente Aprobacion')
                                            ->get();
        }

        if(Auth::user()->hasRole('Encargado de Oficina Central'))
        {
            $request_incomes = RequestChangeIncome::where('storage_id',Auth::user()->getStorage()->id)
                                                    ->where('state','Pendiente')
                                                    ->get();

            $request_outs = RequestChangeOut::where('storage_id',Auth::user()->getStorage()->id)
                                                    ->where('state','Pendiente')
                                                    ->get();
        }


        return view('request_change.index',compact('request_incomes','request_all_in','request_all_out','request_outs'));

    }

    public function create_change_income($article_income_id)
    {
        $article_income = ArticleIncome::with('provider')//join('sisme.providers as prov','sisme.article_incomes.provider_id', '=', 'prov.id')
                                        ->with('article_income_items')->find($article_income_id);
        $articles = Article::with('unit')->get();//cambiar articulos por los articulos de inventario

        // return $articles;
        return view('request_change.create_income',compact('article_income','articles'));
    }

    public function create_change_out($article_request_id)//solicitud de salida
    {
        $article_request = ArticleRequest::with('article_request_items')->find($article_request_id);
        // return $article_request;
        //$articles = Article::with('unit')->get();
        $articles = array();
        $stocks = Stock::with('article')->where('storage_id',Auth::user()->getStorage()->id)->select('article_id',DB::raw('sum(stocks.quantity) as quantity'))->groupBy('stocks.article_id')->get();

        // $articles = json_encode($articles);
        // return $articles;
        return view('request_change.create_out',compact('article_request','stocks', 'article_request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //neto para el income
    {
        //
    //    return $request->all();
        // $arti=null;
        $request_change = new RequestChangeIncome;
        $request_change->article_income_id = $request->article_income_id;
        $request_change->type = $request->type;
        $request_change->description = $request->observation;
        $request_change->remision_number = "";
        $request_change->user_id = Auth::user()->usr_id;
        $request_change->storage_id = Auth::user()->getStorage()->id;
        $request_change->save();

        $request_change_income_items = json_decode($request->request_income_items);
        // return $request_change_income_items;
       // return $request_change_income_items;
        foreach($request_change_income_items as $request_income_item)
        {
            //return json_encode($request_income_item);

                    $request_change_income_item = new RequestChangeIncomeItem;
                    $request_change_income_item->article_income_item_id = $request_income_item->id>0?$request_income_item->id:null;
                    $request_change_income_item->request_change_income_id = $request_change->id;
                    $request_change_income_item->article_id =  $request_income_item->article->id;
                    $request_change_income_item->quantity = $request_income_item->quantity;
                    $request_change_income_item->cost = $request_income_item->cost;
                    $request_change_income_item->save();

        }
        //return $request_change->request_change_income_items;
        return redirect('request_change');

    }

    public function store_out(Request $request) //creacion de solicitudes de cambio XD
    {

        if($request->type == 'Eliminacion')
        {
            $request_change_out = new RequestChangeOut;
            $request_change_out->type = $request->type;
            $request_change_out->article_request_id = $request->article_request_id;
            $request_change_out->description = $request->observation;
            $request_change_out->user_id = Auth::user()->usr_id;
            $request_change_out->storage_id = Auth::user()->getStorage()->id;
            $request_change_out->save();

            $request_change_out_items = json_decode($request->request_out_items);
            //return $request_change_out_items;
            foreach($request_change_out_items as $request_out_item)
            {

                    $request_change_out_item = new RequestChangeOutItem;
                    $request_change_out_item->request_change_out_id = $request_change_out->id;
                    $request_change_out_item->article_id = $request_out_item->article_id;//revisar
                    $request_change_out_item->article_request_item_id = $request_out_item->id;
                    $request_change_out_item->quantity = $request_out_item->quantity_apro;
                    $request_change_out_item->save();

            }

        }else // por modificacion
        {
            $request_change_out = new RequestChangeOut;
            $request_change_out->type = $request->type;
            $request_change_out->article_request_id = $request->article_request_id;
            $request_change_out->description = $request->observation;
            $request_change_out->user_id = Auth::user()->usr_id;
            $request_change_out->storage_id = Auth::user()->getStorage()->id;
            $request_change_out->save();

            //tipo de modificacion en items de articulos
            $request_change_out_items = json_decode($request->request_out_items);
            // return $request_change_out_items;

            switch ($request->change) {
                case 'Articulo':
                    # code...
                    foreach($request_change_out_items as $request_change_out_item)
                    {
                        // return json_encode($request_change_out_item->new_article);
                        // return $request_change_out_item->new_article->id;
                        $new_request_change_out_item = new RequestChangeOutItem;
                        $new_request_change_out_item->request_change_out_id = $request_change_out->id;
                        $new_request_change_out_item->article_request_item_id = $request_change_out_item->id;
                        $new_request_change_out_item->quantity = $request_change_out_item->quantity_apro;
                        if(isset($request_change_out_item->new_article))
                        {
                            $new_request_change_out_item->article_id = $request_change_out_item->new_article->id;//solicitando el cambiazo XD
                        }else{
                            $new_request_change_out_item->article_id = $request_change_out_item->article_id;
                        }
                        $new_request_change_out_item->save();

                    }


                    break;
                case 'Cantidad':
                    # code...
                    break;

            }

            // return $request->all();

            // return $request_change_out;

             //return $request_change_out_items;

        }



        return redirect('request_change');
        // return back()->withInput();
    }

    public function firstConfirmation(Request $request) //confirmacion de solocitudes de modificacion
    {
        //return $request;
        $request_change_income = RequestChangeIncome::find($request->request_change_income_id);
        // return $request_change_income;
        $article_income = ArticleIncome::find($request_change_income->article_income_id);
        // return $article_income;
        // return $request_change_income->request_change_income_items;
        foreach($request_change_income->request_change_income_items as $income_change_item)
        {
           //punto de aprobacion

            // return $request_change_income;
            $request_change_income_item = RequestChangeIncomeItem::find($income_change_item->id);
            // return $request_change_income_item;

            $article_income_item = ArticleIncomeItem::find($income_change_item->article_income_item_id);
            if($article_income_item)
            {
                if($article_income_item->cost != $request_change_income_item->cost )
                {
                    //logica para el cambio ? este cambio afectara de manera recursiva a todas las salidas que existan con este cambio
                    //ver opciones
                }
                if($article_income_item->quantity != $request_change_income_item->quantity )
                {
                    if($request_change_income_item->quantity > $article_income_item->quantity )
                    {
                        //caso 2 aumento de producto
                        $increment = $request_change_income_item->quantity - $article_income_item->quantity;
                        Log::info($increment);
                        // $article_income_item->update(["quantity" => $request_change_income_item->quantity]);
                        $article_income_item->quantity = $request_change_income_item->quantity;
                        $article_income_item->save();

                      // actualizando article history en entrada no hay quantity lol XD
                        // $article_history = ArticleHistory::where('type','Entrada')->where('article_income_item_id',$article_income_item->id)->first();
                        // $article_history->quantity = $article_income_item->quantity;
                        // $article_history->save();

                        //actualizando el stock
                        $stock = Stock::where('article_income_item_id',$article_income_item->id)->first();
                        $stock->quantity += $increment;
                        $stock->save();
                        Log::info($stock);
                        // $stock->quan

                    }else
                    {
                        // caso 1
                        // decrease product
                        $stock = Stock::where('article_income_item_id',$article_income_item->id)->first();
                        if($stock->quantity >= $request_change_income_item->quantity )
                        {
                            $decrease = $article_income_item->quantity - $request_change_income_item->quantity ;
                            $article_income_item->quantity = $request_change_income_item->quantity;
                            $article_income_item->save();
                            $stock = Stock::where('article_income_item_id',$article_income_item->id)->first();
                            $stock->quantity -= $decrease;
                            $stock->save();
                            Log::info($stock);

                        }else
                        {
                            session()->flash('error','No se puede realizar el cambio al producto '.$request_change_income_item->article->name.' debido a que este genera un stock negativo verifique sus salidas');

                        }

                        //mandar mensaje de error por un tema de negativo XD no se puede si es negativoXD XD XD

                    }

                }
                //$new_article_income_item = ArticleIncomeItem::find() ;


            }else
            {
//                registrando nuevos items //revisar
                $new_article_income_item = new ArticleIncomeItem;
                $new_article_income_item->article_income_id = $request_change_income->article_income_id;
                $new_article_income_item->article_id = $request_change_income_item->article_id;
                $new_article_income_item->quantity = $request_change_income_item->quantity;
                $new_article_income_item->cost = $request_change_income_item->cost;
                $new_article_income_item->save();

                $stock = new Stock;
                $stock->article_id = $new_article_income_item->article_id;
                $stock->storage_id = $article_income->storage_id;
                $stock->article_income_item_id = $new_article_income_item->id;
                $stock->quantity = $new_article_income_item->quantity;
                $stock->cost = $new_article_income_item->cost;
                $stock->save();

            }

        }

    //     return $request_change_income ;
    //    // return $request_change_income;
        switch ($request_change_income->state) {
            case 'Pendiente Aprobacion':
                # code...
                    $request_change_income->state = 'Aprobado';
                break;
            // case 'Pendiente':
            //     # code...
            //         $request_change_income->state = 'Pendiente1';
            //     break;
            // case 'Pendiente1':
            //     # code...
            //         $request_change_income->state = 'Pendiente2';
            //     break;
            // case 'Pendiente2':
            //     # code...
            //         $request_change_income->state = 'Aprobado';
            //         # colocar logica de codigo.
            //     break;
        }
       $request_change_income->save();

        return back()->withInput();
        // return $request->all();
    }

    public function firstDeneged(Request $request)
    {
        return $request;
        $request_change_income = RequestChangeIncome::find($request->request_change_income_id);
       // return $request_change_income;
        switch ($request_change_income->state) {
            case 'Pendiente Aprobacion':
                # code...
                    $request_change_income->state = 'Rechazado';
                break;
            case 'Pendiente':
                # code...
                    $request_change_income->state = 'Rechazado';
                break;
            case 'Pendiente1':
                # code...
                    $request_change_income->state = 'Rechazado';
                break;
            case 'Pendiente2':
                # code...
                    $request_change_income->state = 'Rechazado';
                break;
        }
        $request_change_income->save();

        return back()->withInput();
        // return $request->all();
    }


    public function  confirmOut(Request $request) //confirmacion de salida
    {
        // return $request->all();
        $request_change_out = RequestChangeOut::find($request->request_change_out_id);
        // return $request_change_out;
        switch ($request_change_out->state) {
            case 'Pendiente Aprobacion':
                # code...
                   $request_change_out->state = 'Pendiente';
               break;
            case 'Pendiente':
                # code...
                    $request_change_out->state = 'Aprobado';
                    # colocar logica de codigo
                    if($request_change_out->type =='Eliminacion')
                    {
                        $request_change_out_items = RequestChangeOutItem::where('request_change_out_id',$request_change_out->id)->get();
                        foreach($request_change_out_items as $request_change_out_item)
                        {
                            $article_histories = ArticleHistory::where('article_request_item_id',$request_change_out_item->article_request_item_id)->get();
                            foreach($article_histories as $article_history)
                            {
                                $stock = Stock::where('article_income_item_id',$article_history->article_income_item_id)->first();
                                $stock->quantity += $article_history->quantity_desc;
                                $stock->save();

                                $article_history->delete();
                            }

                        }

                        //return $request_change_out_items;

                    }


                   // return $request_change_out;
                    // foreach($request_change_out->request_change_out_items as $out_change_item)
                    // {

                    //     $request_change_out_item = RequestChangeOutItem::find($out_change_item->id);
                    //     if($request_change_out_item->article_request_item) //si existe su entrada si no es nuevo en la modificacion
                    //     {

                    //         $article_request_item = ArticleRequestItem::find($request_change_out_item->article_request_item->id);

                    //         $article_request_item->quantity = $request_change_out_item->quantity;
                    //         $article_request_item->save();

                    //         //para el stock verificar el flujo a seguir
                    //         $stock = Stock::where('article_income_item_id',$article_request_item->article_request_item_id)->first();
                    //         //return $stock;
                    //         $stock->quantity += $article_request_item->quantity;
                    //         //$stock->cost += $article_request_item->cost;
                    //         $stock->save();

                    //     }else //en caso de que sea nuevo item en la modificacion
                    //     {

                    //         $article_request_item = new ArticleRequestItem;
                    //         $article_request_item->article_income_id = $request_change_out->article_request_id;
                    //         $article_request_item->article_id = $request_change_out_item->article_id;
                    //         $article_request_item->quantity = 0;
                    //         $article_request_item->quantity_apro = $request_change_out_item->quantity;
                    //        // $article_request_item->cost = $request_change_out_item->cost;
                    //         $article_request_item->save();



                    //         $stocks = Stock::where('article_id','=',$article_request_item->article_id)
                    //         ->where('storage_id',$request_change_out->storage_destiny_id)
                    //         ->where('quantity','>',0)
                    //         ->orderBy('created_at','Asc')
                    //         ->get();

                    //         $quantity=$article_request_item->quantity_apro;
                    //         Log::warning('cantidad aprobada :'.$quantity);

                    //         //Modulo delicado tener cuidado con este algoritmo  XD

                    //         foreach($stocks as $stock)
                    //         {
                    //             Log::warning('stock: '.$stock->article->name.'  cant:'.$stock->quantity);

                    //                 if($quantity>0)
                    //                 {
                    //                     if($quantity >= $stock->quantity)
                    //                     {
                    //                         Log::info($quantity.'>='.$stock->quantity);
                    //                         $quantity = $quantity - $stock->quantity;
                    //                         $descuento = $stock->quantity;//descuento que se realizo
                    //                         $stock->quantity = 0;
                    //                     }else
                    //                     {
                    //                         Log::info($quantity.'<'.$stock->quantity);
                    //                         $stock->quantity = $stock->quantity - $quantity;
                    //                         $descuento = $quantity;
                    //                         $quantity = 0;
                    //                     }
                    //                     Log::info('new cant :'.$quantity);
                    //                     Log::info('new stock:'.$stock->quantity);

                    //                     $article_history = new ArticleHistory;
                    //                     $article_history->article_request_item_id =$article_request_item->id;//para salida
                    //                     $article_history->article_income_item_id =$stock->article_income_item_id;//para costo de ingreso
                    //                     $article_history->article_id =$article_request_item->article_id;
                    //                     $article_history->type ='Salida';
                    //                     $article_history->quantity_desc =$descuento;
                    //                     $article_history->storage_id = Auth::user()->getStorage()->id;
                    //                     $article_history->save();
                    //                 }
                    //         }


                    //     }

                    // }
                break;
        }
        $request_change_out->save();

        return back()->withInput();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) //para entradas
    {
        //$request_change_income = RequestChangeIncome::with('request_change_income_items','article_income')
                                                   // ->where('request_change_incomes->quantity','=',700)
                                                   // ->find($id);
       $request_change_income = RequestChangeIncome::with('request_change_income_items','article_income')
                                                    ->find($id);
        return response()->json($request_change_income);
    }

    public function show_out($id) //para salidas
    {
        $request_change_out = RequestChangeOut::with('request_change_out_items','article_request')->find($id);
        return response()->json($request_change_out);
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
        //
    }
}
