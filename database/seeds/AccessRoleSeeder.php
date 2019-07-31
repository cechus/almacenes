<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\SubMenu;

class AccessRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $menus = [
        //     ["label"=>"Traspaso","icon"=>"fa fa-people-carry"],
        //     ["label"=>"Datos","icon"=>"fa fa-window-restore"],
        //     ["label"=>"Reportes","icon"=>"fa fa-window-restore"],
        // ];

        // foreach($menus as $menu)
        // {
        //     App\Menu::create($menu);
        // }

        $permission = Permission::create(['name' => 'Inicio']);
        $sub_menu = SubMenu::create(['icon'=>'fa fa-tachometer-alt','route'=>'/','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Stock']);
        $sub_menu = SubMenu::create(['icon'=>'fa fa-cubes','route'=>'stock','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Ingresos']);
        $sub_menu = SubMenu::create(['icon'=>'fa fa-parachute-box','route'=>'income','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Mis Solicitudes']);
        $sub_menu = SubMenu::create(['icon'=>'fa fa-boxes','route'=>'request_person','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Solicitudes Recibidas']);
        $sub_menu = SubMenu::create(['icon'=>'fa fa-truck-loading','route'=>'request_change','type'=>'Menu','permission_id'=>$permission->id ]);

        // $accesos = [
        //     ['']
        // ];

        // $permission = Permission::where('name','Administrador')->first();
        // if(!$permission){
        //     $permission = Permission::create(['name' => 'Administrador']);
        // }


    }
}
