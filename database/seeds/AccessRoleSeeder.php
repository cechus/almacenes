<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\SubMenu;
use App\Menu;

class AccessRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        $permission = Permission::create(['name' => 'Inicio']);
        SubMenu::create(['icon'=>'fa fa-tachometer-alt','route'=>'/','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Stock']);
        SubMenu::create(['icon'=>'fa fa-cubes','route'=>'stock','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Ingresos']);
        SubMenu::create(['icon'=>'fa fa-parachute-box','route'=>'income','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Mis Solicitudes']);
        SubMenu::create(['icon'=>'fa fa-boxes','route'=>'request_person','type'=>'Menu','permission_id'=>$permission->id ]);

        $permission = Permission::create(['name' => 'Solicitudes Recibidas']);
        SubMenu::create(['icon'=>'fa fa-truck-loading','route'=>'request_change','type'=>'Menu','permission_id'=>$permission->id ]);

        $menu = Menu::create(["label"=>"Traspaso","icon"=>"fa fa-people-carry"]);

        $permission = Permission::create(['name' => 'Solicitudes Recibidas']);
        SubMenu::create(['icon'=>'fa fa-file-import','route'=>'request_change','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Solicitudes Realizadas']);
        SubMenu::create(['icon'=>'fa fa-inbox','route'=>'request_storage_done','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $menu = Menu::create(["label"=>"Datos","icon"=>"fa fa-window-restore"]);

        $permission = Permission::create(['name' => 'Almacenes']);
        SubMenu::create(['icon'=>'fa fa-store-alt','route'=>'storages','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Proveedores']);
        SubMenu::create(['icon'=>'fa fa-address-book','route'=>'provider','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Categorias']);
        SubMenu::create(['icon'=>'fa fa-boxes','route'=>'category','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Unidades']);
        SubMenu::create(['icon'=>'fa fa-ruler','route'=>'unit','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Partidas']);
        SubMenu::create(['icon'=>'fa fa-archive','route'=>'budge_item','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Articulos']);
        SubMenu::create(['icon'=>'fa fa-box','route'=>'article','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Ufv']);
        SubMenu::create(['icon'=>'fa fa-coins','route'=>'ufv','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $menu = Menu::create(["label"=>"Reportes","icon"=>"fa fa-window-restore"]);

        $permission = Permission::create(['name' => 'Reportes Generales']);
        SubMenu::create(['icon'=>'fa fa-file-excel','route'=>'report_excel','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Reporte Alamacen Ingresos']);
        SubMenu::create(['icon'=>'fa fa-file-excel','route'=>'listalmacenes','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);

        $permission = Permission::create(['name' => 'Reporte Alamacen Salidas']);
        SubMenu::create(['icon'=>'fa fa-file-excel','route'=>'listalmacenesSal','type'=>'SubMenu','permission_id'=>$permission->id,'menu_id'=>$menu->id ]);


    }
}
