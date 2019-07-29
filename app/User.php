<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use App\Article;
use Carbon\Carbon;
use App\SubMenu;
use Session;
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    // protected $connection = 'public';
    protected $table = '_bp_usuarios';
    protected $primaryKey = "usr_id";
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee','usr_prs_id','id')->with('management');
    }

    public function getPermissions()
    {
        $permissions = $this->getPermissionsViaRoles();
        $permissions_menu = [];
        foreach($permissions as $permission){
            if($permission->name != "SAE")
            {
                $sub_menu = SubMenu::where('permission_id',$permission->id)->first();
                if($sub_menu){
                    $permission->sub_menu = $sub_menu;
                    //$permission_menu= $permission;
                    array_push($permissions_menu,$permission);

                }
                // $role->sub_menu
                // if($role->)
            }
        }
        return response()->json($permissions_menu);
    }
    // public function person()
    // {
    //     return $this->belongsTo('App\Person','usr_prs_id','prs_id');
    // }

    // public function person(){
    //     $person = DB::table('_bp_personas')
    //             ->where('prs_id','=',$this->usr_prs_id)
    //             ->first();
    //     return $person;
    // }

    public function getFullName(){
        $person = DB::table('rrhh.employees')
                ->where('id','=',$this->usr_prs_id)
                ->first();
        return $person->first_name.' '.$person->second_name.' '.$person->last_name.' '.$person->mother_last_name;
       // return $person->prs_nombres;
    }

    public function getArticles()
    {
        $articles = Article::all();//validar de acuerdo a tabla de user_storage
        return $articles;
    }

    public function getStorages(){
        $storages = Storage::all();
        return $storages;
    }

    public function getStorage(){
        //en caso de no existir session se genera uno trabajar esto a mas detalle
        if(!session()->exists('storage_id')){
            session()->put('storage_id', $this->storages[0]->id);
        }
        // return session('storage_id');
        $storage = Storage::find(session('storage_id'));
        // if(!$storage){
        //     $storage = Storage::find(session('storage_id'));
        // }
        return $storage;
    }

    public function getGerencia(){
        $gerencia = DB::table('sia_gerencia_area')->where('ga_id','=',$this->usr_ga_id)->first();
        return $gerencia?$gerencia->ga_nombre:'';
    }

    public function storages()
    {
        return $this->belongsToMany('App\Storage','sisme.user_storage');
    }
    public function getUfv()
    {
        $carbon = new Carbon();
        $date = $carbon->now();
        $day = Carbon::parse($date)->day;
        $month = Carbon::parse($date)->month;
        $year = Carbon::parse($date)->year;
        $extracto = null;

        $ufv = "";
        // $content = file_get_contents('https://www.bcb.gob.bo/librerias/indicadores/ufv/gestion.php?sdd=04&smm=07&saa=2019&Button=++Ver++&reporte_pdf=07*04*2019**07*04*2019*&edd=04&emm=07&eaa=2019&qlist=1');
        try {
            //code...
            $content = file_get_contents('https://www.bcb.gob.bo/librerias/indicadores/ufv/gestion.php?sdd=' . $day . '&smm=' . $month . '&saa=' . $year . '&Button=++Ver++&reporte_pdf=' . $month . '*' . $day . '*' . $year . '**' . $month . '*' . $day . '*' . $year . '*&edd=' . $day . '&emm=' . $month . '&eaa=' . $year . '&qlist=1');

            $patron = '|<div align="center">(.*?)</div>|is';
            if (preg_match_all($patron, $content, $extracto) > 0) {
                $ufv_exist = Ufv::where('date',$date)->first();
                if ($ufv_exist) {
                    $ufv= $ufv_exist->price;

                }else{
                    $new_ufv = new Ufv();
                    $numero = trim($extracto[1][1]);
                    $numero_dos= floatval(str_replace(',', '.', str_replace('.', '', $numero)));
                    $new_ufv->price = $numero_dos;
                    $new_ufv->date = $date;
                    $new_ufv->save();
                    $ufv= $ufv_exist->price;

                }

            } else {
                return false;
            }
        } catch (\Throwable $th) {

            //Session::put('UFV',"");
            session()->flash('error','No se pudo Sincronizar el UFV con el BCB ');

        }
        return $ufv;
    }

}
