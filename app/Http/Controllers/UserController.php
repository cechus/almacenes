<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Storage;
use DB;
use Log;
use Auth;
use App\Menu;
use App\SubMenu;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::with('employee')->get();
        // return $users;
        return view('user.index',compact('users'));
    }

    public function system()
    {
        $permissions = Permission::where('id','>',7)->get();
        $permission = Permission::where('name','SAE')->first();
        // return json_encode($permission);
        $roles = [];
        foreach($permission->getRoleNames() as $role_name)
        {
            $role = Role::where('name',$role_name)->first();
            array_push($roles,$role);
        }

        foreach($permissions as $permission)
        {
            $permission->sub_menu = SubMenu::where('permission_id',$permission->id)->first();
        }
        // return $permissions;
        $menus  = Menu::all();
        // return $roles;
        // return $permission->getRoleNames();

        // $roles = Role::all();
        // return $roles;
        return view('system.index',compact('roles','permissions','menus'));
    }

    public function changeStore(Request $request)
    {
        session()->put('storage_id', $request->storage_id);
        // return $request->all();
        return back()->withInput();
    }

    public function storeRole(Request $request)
    {
        //creando roles y adicionando permisos
        if($request->has('id'))
        {
            $role = Role::find($request->id);

        }else {

            $role = Role::create(['name' => $request->name]);

        }
        $role->givePermissionTo('SAE');
        // return $request->all();
        $permissions = json_decode($request->permissions);
        foreach ($permissions as $permission)
        {
            # code...
            if($permission->enabled)
            {
                $role->givePermissionTo($permission->id);
            }else {
                $role->revokePermissionTo($permission->id);
            }
        }

        return back()->withInput();
    }

    public function storeMenu(Request $request)
    {
        // return $request->all();

        if($request->id != null)
        {
            $menu = Menu::find($request->id);


        }else
        {
            $menu = new Menu;

        }
        //return $menu;
        $menu->label = $request->label;
        $menu->icon = $request->icon;

        $menu->save();


        return back()->withInput();

    }

    public function storePermission(Request $request)
    {
        // return $request->all();
        if($request->has('id'))
        {
            $permission = Permission::find($request->id);
            $exist = true;
        }else
        {
            $permission = new Permission;
            $exist = false;
            // $permission = Permission::create(['name' => $request->name]);
        }
        // return $permission;
        $permission->name = $request->name;
        $permission->guard_name = "web";
        $permission->save();



        if(!$exist){
            $sub_menu = new SubMenu;
            $sub_menu->permission_id = $permission->id;
            $sub_menu->icon = $request->icon;
            $sub_menu->route = $request->route;
            $sub_menu->type = $request->type;
            $sub_menu->menu_id = $request->menu_id;
            $sub_menu->save();

        }
        // $permission->name = $request->name;

        return back()->withInput();
    }

    public function getPermission($permission_id){
        $permission = Permission::find($permission_id);
        return response()->json($permission);
    }

    public function getMenus(){
        $menus = Menu::all();
        return $menus;
    }

    public function getPermissionsRole($role_id){

        $role = Role::find($role_id);
        $permissions = Permission::where('id','>',7)->get();
        foreach($permissions as $permission)
        {
            $permission->enabled = $role->hasPermissionTo($permission->id)?true:false;
        }
        // $role->permissions = $role->getPermissionNames();
        return response()->json(compact('role','permissions'));
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


        $user = User::find($request->id);
        // return $user;
        // $permissions = json_decode($request->permissions);
        $user->givePermissionTo('SAE');
        $roles = json_decode($request->roles);

        foreach($roles as $role){

            if($role->enabled)
            {
                $user->assignRole($role->name);
            }else{
                $user->removeRole($role->name);
            }
        }

        $storages = json_decode($request->storages);
        $almacenes=[];
        foreach($storages as $storage){
            if($storage->enabled){
                array_push($almacenes,$storage->id);
                // $almacenes+=$storage->id;
            }
        }
        $user->storages()->sync($almacenes);
        return redirect('user');
        //return $request->all();
    }

    public function storeSystem(Request $request)
    {
        //
        $system = Permission::find($request->id);
        $system->name = $request->name;
        $system->save();
        return back()->withInput();
    }

    public function getPermissionRol($role_id)
    {
        if($role_id > 0)
        {
            $role = Role::find($role_id);
        }else
        {
            $role = null;
        }

        $permissions = Permission::all();
        Log::info($permissions->count());
        foreach ($permissions as $permission) {

           if($role){

               if($role->hasPermissionTo($permission->name)){
                   $permission->enabled = true;
               }else
               {
                   $permission->enabled = false;
               }
           }else
           {
                $permission->enabled = false;
           }

        }

        return response()->json(compact('role','permissions'));
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
        $storages = Storage::all();

        $user = User::with('employee')->find($id);

        foreach($storages as $storage)
        {
            $st = DB::table('sisme.user_storage')
                    ->where('user_usr_id',$user->usr_id)
                    ->where('storage_id',$storage->id)
                    ->first();
            if($st)
            {
                $storage->enabled = true;
            }
            else
            {
                $storage->enabled = false;
            }
        }


        $roles = Role::all();
        foreach ($roles as $role)
        {
            # code...
            if($user->hasRole($role->name))
            {
                $role->enabled = true;
            }
            else
            {
                $role->enabled = false;
            }
            $role->permissions = $role->getPermissionNames();
        }

        $permissions = Permission::all();

        foreach($permissions as $permission)
        {
            if($user->hasPermissionTo($permission->name))
            {
                $permission->enabled = true;
            }else{
                $permission->enabled = false;
            }
        }
        return view('user.edit',compact('user','roles','permissions','storages'));
        // return response()->json(compact('user','roles','permissions'));
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

    public function byuri($user_id)
    {
        $user = User::find($user_id);
        Auth::login($user);
        return redirect('/');
    }
}
