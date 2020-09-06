<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Permission\Models\Role;
use App\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class JhosagidPermissionInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //limpiar las tablas antes de llenarlas (truncate)
        //primero desactivamos las restricciones de llaves foranias
        DB::statement("SET foreign_key_checks=0");
        //usamos DB para porder truncar las tablas que no tienen un modelo creado
        DB::table('role_user')->truncate();
        DB::table('permission_role')->truncate();
        //hacemos truncate a las tablas que tienen modelos pero con eloquent
        Permission::truncate();
        Role::truncate();
        //ahor habilitamos los freignKey
        DB::statement("SET foreign_key_checks=1");

        //user admin
        $useradmin = User::where('email', 'admin@admin.com')->first();
        //buscamos si existe ese correo enla tabla user para poder crear el registro sin duplicar
        if($useradmin){
            $useradmin->delete();
        }

        $superuseradmin = User::create([
            'name' => 'Jhonny Sagid Pirela Pineda',
            'email' => 'jhosagid77@gmail.com',
            'password' => Hash::make('jhosagid')
        ]);

        $useradmin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin')
        ]);

        //creamos nuestro rol admin
        //rol admin
        $roladmin=Role::create([
            'name'=>'Admin',
            'slug'=>'admin',
            'description'=>'Administrator',
            'full-access'=>'yes'
        ]);


        //creamos nuestro rol Registered User
        //rol Registered User
        $roluser=Role::create([
            'name'=>'Registered User',
            'slug'=>'registereduser',
            'description'=>'Registered User',
            'full-access'=>'no'
        ]);

        //tabla role_user
        //pasar rol unico al usuario relacionamos dos tablas admin y usuario
        $superuseradmin->roles()->sync([$roladmin->id]);
        $useradmin->roles()->sync([$roladmin->id]);

        //tabla role_permission
        //Permission
        $permission_all = [];

        //permission role
        $permission = Permission::create([
            'name'=>'List role',
            'slug'=>'role.index',
            'description'=>'A user can list role'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Show role',
            'slug'=>'role.show',
            'description'=>'A user can see role'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Create role',
            'slug'=>'role.create',
            'description'=>'A user can create role'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Edit role',
            'slug'=>'role.edit',
            'description'=>'A user can edit role'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Destroy role',
            'slug'=>'role.destroy',
            'description'=>'A user can destroy role'
        ]);

        $permission_all[] = $permission->id;





        //permission user
        $permission = Permission::create([
            'name'=>'List user',
            'slug'=>'user.index',
            'description'=>'A user can list user'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Show user',
            'slug'=>'user.show',
            'description'=>'A user can see user'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Edit user',
            'slug'=>'user.edit',
            'description'=>'A user can edit user'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Destroy user',
            'slug'=>'user.destroy',
            'description'=>'A user can destroy user'
        ]);

        $permission_all[] = $permission->id;

        // $permission = Permission::create([
        //     'name'=>'Create user',
        //     'slug'=>'user.create',
        //     'description'=>'A user can create user'
        // ]);

        // $permission_all[] = $permission->id;

       //tabla permission_role
       //con esta instruccion creamos los permisos del admin
       //pero como le pasamos full-access yes ya el admin tiene todos los permisos
       //asignado por defecto
       //$roladmin->permissions()->sync($permission_all);

       //creamos el permiso para que el admin pueda ver los registros de todos

       $permission = Permission::create([
        'name'=>'Show own user',
        'slug'=>'userown.show',
        'description'=>'A user can see own user'
        ]);

        $permission_all[] = $permission->id;

        $permission = Permission::create([
            'name'=>'Edit own user',
            'slug'=>'userown.edit',
            'description'=>'A user can edit own user'
        ]);

        $permission_all[] = $permission->id;

    }
}
