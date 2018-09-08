<?php

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

// resource recibe nos parámetros(URI del recurso, Controlador que gestionará las peticiones)
Route::resource('rol','RolController');

// Si queremos dar  la funcionalidad de ver todos los usuarios tendremos que crear una ruta específica.
// Pero de usuarios solamente necesitamos solamente los métodos index y show.
// Lo correcto sería hacerlo así:
Route::resource('usuario','UsuarioController',[ 'only'=>['index','show'] ]);

// Como la clase principal es rol y un usuario no se puede crear si no le indicamos el rol, 
// entonces necesitaremos crear lo que se conoce como  "Recurso Anidado" de rol con usuarios.
// Definición del recurso anidado:
Route::resource('rol.usuario','RolUsuarioController',[ 'except'=>['show','edit','create'] ]);



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/roles', 'CrudRolController@mostrar');
//Route::post('/roles', 'CrudRolController@almacenar');

Route::get('/usuarios', 'CrudRolController@mostrarUsuarios');
//Route::post('/usuarios', 'CrudRolController@almacenarUsuario');

//Route::get('/', 'HomeController@index')->name('home');
Route::get('/edit-rol/{param}', 'CrudRolController@editrol');

Route::get('/edit-user/{param}', 'CrudRolController@edituser');