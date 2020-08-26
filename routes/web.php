<?php

use Illuminate\Support\Facades\Route;

use App\User;
use App\Permission\Models\Role;
use App\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false
]);

Route::get('/home', 'HomeController@index')->name('home');



// Route::get('/test', function () {
//     $user = User::find(2);

//     //$user->roles()->sync([2]);
//     // return $user->roles;
//     // return $user->havePermission('role.create');
//     Gate::authorize('haveaccess', 'role.index');
//     return $user;
// });

Route::get('/test', function () {
    $user = User::find(2);
    $empre = App\Empresa::orderBy('id','Desc')->get();
    $empresa = App\Empresa::find(1)->cajas()->orderBy('nombre')->get();
    $cajas = App\Sucursal::find(1)->cajas()->orderBy('nombre')->get();
    $tasa1 = App\Tasa::get();
    $tasa2 = App\Empresa::orderBy('id','Desc')->get();
    $tasa1 = App\Tasa::get();
    $tasa = App\Tasa::find(1);


    return $tasa;
});

Route::resource('/role', 'RoleController')->names('role');

Route::resource('/user', 'UserController', ['except'=>[
    'create', 'store'
]])->names('user');


Route::resource('almacen/categoria', 'CategoriaController');
Route::resource('almacen/articulo', 'ArticuloController');
Route::resource('ventas/cliente', 'ClienteController');
Route::resource('compras/proveedor', 'ProveedorController');
Route::resource('compras/ingreso', 'IngresoController');
Route::resource('ventas/venta', 'VentaController');



Route::resource('ventas/tasa', 'TasaController');


