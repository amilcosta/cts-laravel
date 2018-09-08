<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

# Necesitaremos el modelo Rol y Usuario para ciertas tareas.
use App\Rol;
use App\Usuario;

class CrudRolController extends Controller
{
    public function mostrar()
    {
        return View('rol-form');
    }

    public function mostrarUsuarios()
    {
        return View('usuario-form');
    }

    public function editrol($idr)
    {
    	$rol=Rol::find($idr);

        # Si no existe ese rol devolvemos un error.
        if (!$rol)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        }

        //return response()->json(['status'=>'ok','data'=>$rol],200);
        return View('roledit-form')->with('idr',$rol);
    }

    public function edituser($idu)
    {
    	$usuario=Usuario::find($idu);

        # Si no existe ese usuario devolvemos un error.
        if (!$usuario)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        }

        //return response()->json(['status'=>'ok','data'=>$rol],200);
        return View('useredit-form')->with('idu',$usuario);
    }

}

?>