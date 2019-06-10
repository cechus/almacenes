<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArticleIncome;
use App\RequestChangeIncome;
use App\RequestChangeIncomeItem;
use App\Article;
use Auth;
class RequestChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return "estamos en el index de la hueva esta";

    }

    public function create_change_income($article_income_id)
    {
        $article_income = ArticleIncome::with('article_income_items')->find($article_income_id);
        $articles = Article::with('unit')->get();
        // return $articles;
        return view('request_change.create_income',compact('article_income','articles'));
    }

    public function create_change_out()
    {

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
    public function store(Request $request)
    {
        //
        $request_change = new RequestChangeIncome;
        $request_change->article_income_id = $request->article_income_id;
        $request_change->type = $request->type;
        $request_change->description = $request->observation;
        $request_change->user_id = Auth::user()->usr_id;
        $request_change->save();

        $request_change_income_items = json_decode($request->request_income_items);
        // return $request_change_income_items;
        foreach($request_change_income_items as $request_income_item)
        {
            // if($request_income_item->id>0) //para los que son distintos de nuevos donde id nuevo = 0
            // {
                // $object = (object) json_encode ( $request_income_item);
                // return $request_income_item->article_income_id;
                // return $object;
                // return json_encode($request_income_item) ;
                $request_change_income_item = new RequestChangeIncomeItem;
                $request_change_income_item->request_change_income_id = $request_change->id;
                $request_change_income_item->article_income_item_id = $request_income_item->article_income_id;
                $request_change_income_item->cost = $request_income_item->new_cost;
                $request_change_income_item->quantity = $request_income_item->new_quantity;
                $request_change_income_item->save();
                // return json_encode($request_income_item) ;
            // }
        }
        // return $request_change;
        return redirect('request_change');
        // $request_change->
        return $request->all();
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