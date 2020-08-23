<?php
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





Route::get('/test', function () {
    //buscamos el usuario
    //$user = User::find(1);
    //con attach guardamos en un array varios roles attach se usa para muchos a muchos
    //$user->roles()->attach([1,3]);
    //detach me elimina los registros
    // $user->roles()->detach([1]);
    //sync $user->roles()->sync([1]);busca y elimna lo que no este en el array dado y sino existe lo crea
    //retormanos los datos del usuario juto con los roles
    //return $user->roles;

    // return Role::create([
    //     'name'=>'Test',
    //     'slug'=>'test',
    //     'description'=>'Test',
    //     'full-access'=>'no'
    // ]);

    // return Role::create([
    //     'name'=>'Test',
    //     'slug'=>'test',
    //     'description'=>'Test',
    //     'full-access'=>'no'
    // ]);

    // return Role::create([
    //     'name'=>'Guest',
    //     'slug'=>'guest',
    //     'description'=>'Guest',
    //     'full-access'=>'no'
    // ]);


    // $role = Role::create([
    //     'name'=>'Admin',
    //     'slug'=>'admin',
    //     'description'=>'Administrator',
    //     'full-access'=>'yes'
    // ]);
    // return $role;

    //$user = User::find(1);

    //en: create new record
    //es: crear un nuevo registro

    //$user->roles()->aync([1,2]);
    //return $user->roles;

    //

    $role = Role::find(2);

    $role->permissions()->sync([1,2]);

    return $role->permissions;


});
