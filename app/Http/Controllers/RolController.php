<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

# Activamos uso de caché.
use Illuminate\Support\Facades\Cache;

# Necesitaremos el modelo Rol para ciertas tareas.
use App\Rol;

# Necesitamos la clase Response para crear la respuesta especial con la cabecera de localización en el método Store()
use Response;

class RolController extends Controller
{
    # Configuramos en el constructor del controlador la autenticación usando el Middleware auth.basic,
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
    public function index()
    {
        # Activamos la caché de los resultados.
        #  Cache::remember('tabla', $minutes, function()
        $rol=Cache::remember('cacheroles',15/60, function()
        {
            // Caché válida durante 20 segundos.
            //return Rol::all();

            // Este método paginate() está orientado a interfaces gráficas. 
            // Paginator tiene un método llamado render() que permite construir
            // los enlaces a página siguiente, anterior, etc..
            // Para la API RESTFUL usaremos un método más sencillo llamado simplePaginate() que
            // aporta la misma funcionalidad
            return Rol::simplePaginate(10);  
        });

        // Devolverá todos los Roles.
        //return "Mostrando todos los roles de la base de datos.";
        // response()->json(['status'=>'ok','data'=>$rol], 200);

        # Con la paginación lo haremos de la siguiente forma:
        # Devolviendo también la URL 
        return response()->json(['status'=>'ok', 'siguiente'=>$rol->nextPageUrl(),'anterior'=>$rol->previousPageUrl(),'data'=>$rol->items()],200);
    }

    /**s
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
        return "Se muestra formulario para crear un rol.";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // Pasamos como parámetro al método store todas las variables recibidas de tipo Request
    public function store(Request $request)
    {
        //
        # Primero comprobaremos si estamos recibiendo todos los campos.
        if (!$request->input('nombre') || !$request->input('tipo') )
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso de alta.'])],422);
        }

        # Insertamos una fila en Rol con create pasándole todos los datos recibidos.
        # En $request->all() tendremos todos los campos del formulario recibidos.
        $nuevoRol=Rol::create($request->all());

        // Devolvemos el código HTTP 201 Created – [Creada] Respuesta a un POST que resulta en una creación. Debería ser combinado con un encabezado Location, apuntando a la ubicación del nuevo recurso.
        $response = Response::make(json_encode(['data'=>$nuevoRol]), 201)->header('Location', 'http://test-cts/rol/'.$nuevoRol->id)->header('Content-Type', 'application/json');
        return $response;
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
        
        # Buscamos un rol por el id.
        $rol=Rol::find($id);

        // Si no existe ese rol devolvemos un error.
        if (!$rol)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        }

        return response()->json(['status'=>'ok','data'=>$rol],200);

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
        return "Se muestra formulario para editar Fabricante con id: $id";
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
        # Comprobamos si el rol que nos están pasando existe o no.
        $rol=Rol::find($id);

        # Si no existe ese rol devolvemos un error.
        if (!$rol)
        {
            # Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            # En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        }   

        # Listado de campos recibidos teóricamente.
        $nombre =$request->input('nombre');
        $tipo   =$request->input('tipo');

        # Necesitamos detectar si estamos recibiendo una petición PUT o PATCH.
        # El método de la petición se sabe a través de $request->method();
        if ($request->method() === 'PATCH')
        {
            # Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
            $bandera = false;

            # Actualización parcial de campos.
            if ($nombre)
            {
                $rol->nombre = $nombre;
                $bandera=true;
            }

            if ($tipo)
            {
                $rol->tipo = $tipo;
                $bandera=true;
            }


            if ($bandera)
            {
                # Almacenamos en la base de datos el registro.
                $rol->save();
                sleep(1);
                //return response()->json(['status'=>'ok','data'=>$rol], 200);
                //return View('/home')->with('message','Rol Actualizado');
                return redirect('home');
            }
            else
            {
                // Se devuelve un array errors con los errores encontrados y cabecera HTTP 304 Not Modified – [No Modificada] Usado cuando el cacheo de encabezados HTTP está activo
                // Este código 304 no devuelve ningún body, así que si quisiéramos que se mostrara el mensaje usaríamos un código 200 en su lugar.
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de rol.'])],304);
            }
        }

        # Si el método no es PATCH entonces es PUT y tendremos que actualizar todos los datos.
        if (!$nombre || !$tipo)
        {
            # Se devuelve un array errors con los errores encontrados y cabecera HTTP 422 Unprocessable Entity – [Entidad improcesable] Utilizada para errores de validación.
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento "Actualizar".'])],422);
        }

        $rol->nombre = $nombre;
        $rol->tipo   = $tipo;
        
        # Almacenamos en la base de datos el registro.
        $rol->save();
        //return response()->json(['status'=>'ok','data'=>$rol], 200);
        return redirect('home');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        # Primero eliminaremos todos los usuarios de un rol y luego el rol en si mismo.
        # Comprobamos si el rol que nos están pasando existe o no.
        $rol=Rol::find($id);

        # Si no existe ese rol devolvemos un error.
        if (!$rol)
        {
            // Se devuelve un array errors con los errores encontrados y cabecera HTTP 404.
            // En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un rol con ese código.'])],404);
        }

        # Si el rol existe entonces buscamos todos los usuarios asociados a ese rol.
        $usuarios = $rol->usuarios; // Sin paréntesis obtenemos el array de todos los usuarios.

        # Comprobamos si tiene usuarios ese rol.
        if (sizeof($usuarios) > 0)
        {
            
            // Devolveremos un código 409 Conflict - [Conflicto] Cuando hay algún conflicto al procesar una petición, por ejemplo en PATCH, POST o DELETE.
            return response()->json(['code'=>409,'message'=>'Este rol posee usuarios y no puede ser eliminado.'],409);

        }

        # Procedemos por lo tanto a eliminar el rol.
        $rol->delete();

        # Se usa el código 204 No Content – [Sin Contenido] Respuesta a una petición exitosa que no devuelve un body (como una petición DELETE)
        # Este código 204 no devuelve body así que si queremos que se vea el mensaje tendríamos que usar un código de respuesta HTTP 200.
        //return response()->json(['code'=>204,'message'=>'Se ha eliminado el rol correctamente.'],204);
        return redirect('home');

    }
}
