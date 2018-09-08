<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

# Necesita los dos modelos Rol y Usuario
use App\Rol;
use App\Usuario;

# Necesitamos la clase Response para crear la respuesta especial con la cabecera de localización en el método Store()
use Response;

# Activamos uso de caché.
use Illuminate\Support\Facades\Cache; 

class RolUsuarioController extends Controller
{

    ## Configuramos en el constructor del controlador la autenticación usando el Middleware auth.basic,
    # pero solamente para los métodos de crear, actualizar y borrar.
    public function __construct()
    {
        $this->middleware('auth.basic',['only'=>['store','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idRol)
    {
        # Devolverá todos los usuarios.
        #return "Mostrando los usuarios del rol con Id $idRol";
        $rol=Rol::find($idRol);

        if (! $rol)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un Rol con ese código.'])],404);
        }

        # Activamos la caché de los resultados.
        # Como el closure necesita acceder a la variable $ rol tenemos que pasársela con use($rol)
        # Para acceder a los modelos no haría falta puesto que son accesibles a nivel global dentro de la clase.
        #  Cache::remember('tabla', $minutes, function()
        $usuariosRol=Cache::remember('claveUsuarios',2, function() use ($rol)
        {
            // Caché válida durante 2 minutos.
            return $rol->usuarios()->get();
        });

        // Respuesta con caché:
        return response()->json(['status'=>'ok','data'=>$usuariosRol],200);
        //return response()->json(['status'=>'ok','data'=>$rol->usuarios()->get()],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return "Se muestra formulario para crear un usuario del rol $idRol.";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$idRol)
    {
        //
        /* Necesitaremos el rol_id que lo recibimos en la ruta
         #id_usuario (auto incremental)
        rut
        Nombre
        Apellido
        usuario*/

        // Primero comprobaremos si estamos recibiendo todos los campos.
        if ( !$request->input('rut') || !$request->input('nombre') || !$request->input('apellido') || !$request->input('usuario')  )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
        }

        # Buscamos el Rol.
        $rol= Rol::find($idRol);

        # Si no existe el rol que le hemos pasado mostramos otro código de error de no encontrado.
        if (!$rol)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        }


        # Si el rol existe entonces lo almacenamos.
        # Insertamos una fila en Usuarios con create pasándole todos los datos recibidos.
        $nuevoUsuario=$rol->usuarios()->create($request->all());

        # Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        $response = Response::make(json_encode(['data'=>$nuevoUsuario]), 201)->header('Location', 'http://test-cts/usuario/'.$nuevoUsuario->id_usuario)->header('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idRol,$idUsuario)
    {
        //
        return "Se muestra usuario $idUsuario del rol $idRol";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($idRol,$idUsuario)
    {
        //
        return "Se muestra formulario para editar el usuario $idUsuario del rol $idRol";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idRol,$idUsuario)
    {
        //
        # Comprobamos si el rol que nos están pasando existe o no.
        $rol=Rol::find($idRol);

        # Si no existe ese rol devolvemos un error.
        if (!$rol)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        } 

        # El rol existe entonces buscamos el usuario que queremos editar asociado a ese rol.
        $usuario = $rol->usuarios()->find($idUsuario);

        # Si no existe ese usuario devolvemos un error.
        if (!$usuario)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un usuario con ese código asociado al rol.'])],404);
        }   

        // Listado de campos recibidos teóricamente.
        $rut        =$request->input('rut');
        $nombre     =$request->input('nombre');
        $apellido   =$request->input('apeliido');
        $usu        =$request->input('usuario');
        //$pass       =$request->input('password');

        # Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        # El método de la petición se sabe a través de $request->method();
        /*  rut      nombre        apellido       usuario       password */
        if ($request->method() === 'PATCH')
        {
            # Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            # Actualización parcial de campos.
            if ($rut)
            {
                $usuario->rut = $rut;
                $bandera=true;
            }

            if ($nombre)
            {
                $usuario->nombre = $nombre;
                $bandera=true;
            }

            if ($apellido)
            {
                $usuario->apellido = $apellido;
                $bandera=true;
            }

            if ($usu)
            {
                $usuario->usuario = $usu;
                $bandera=true;
            }

            /*if ($pass)
            {
                $usuario->password = $pass;
                $bandera=true;
            }*/

            if ($bandera)
            {
                # Almacenamos en la base de datos el registro.
                $usuario->save();
                //return response()->json(['status'=>'ok','data'=>$usuario], 200);
                sleep(1);
                //return View('home')->with('message','Usuario Actualizado');
                return redirect('home');
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato del usuario.'])],304);
            }

        }


        # Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$rut || !$nombre || !$apellido || !$usu )
        {
            # Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }

        $usuario->rut       = $rut;
        $usuario->nombre    = $nombre;
        $usuario->apellido  = $apellido;
        $usuario->usuario   = $usu;
        //$usuario->password  = $pass;

        $usuario->save();

        //return response()->json(['status'=>'ok','data'=>$usuario], 200);
        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idRol,$idUsuario)
    {
        # Comprobamos si el rol que nos están pasando existe o no.
        $rol=Rol::find($idRol);

        # Si no existe ese rol devolvemos un error.
        if (!$rol)
        {
            # Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            # En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        }

        # Si el rol existe entonces buscamos el usuario que queremos borrar asociado a ese rol.
        $usuario = $rol->usuarios()->find($idUsuario);

        # Si no existe ese usuario devolvemos un error.
        if (!$usuario)
        {
            # Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            # En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un usuario con ese código asociado a ese rol.'])],404);
        }

        # Procedemos por lo tanto a eliminar el usuario.
        $usuario->delete();

        # Se usa el código 204 No Content – [Sin Contenido] Respuesta a una petición exitosa que no devuelve un body (como una petición DELETE)
        # Este código 204 no devuelve body así que si queremos que se vea el mensaje tendríamos que usar un código de respuesta HTTP 200.
        return response()->json(['code'=>204,'message'=>'Se ha eliminado el usuario correctamente.'],204);

    }
}
