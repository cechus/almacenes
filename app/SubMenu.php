<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    //
    protected $table = "sisme.sub_menus";

    public function permission(){
        return $this->belongsTo('Spatie\Permission\Models\Permission');
    }

    public function menu(){
        return $this->belongsTo('App\Menu');
    }
}
