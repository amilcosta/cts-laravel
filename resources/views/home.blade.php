@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!--You are logged in!-->
                    <h2>Bienvenido a la Administracion de Usuarios</h2>
                    <button class="btn btn-info" onclick="location.href='{{ url('/roles') }}'">Lista de Roles</button>
                    <button class="btn btn-info" onclick="location.href='{{ url('/usuarios') }}'">Lista de Usuarios</button>

                    <?php //$json = json_decode(file_get_contents('http://test-cts/rol'), true); 
                    //print_r($json);
                    
                    /*$request = Request::create('http://test-cts/rol', 'GET');
                    $instance = json_decode(Route::dispatch($request)->getContent());
                    //print_r($instance);
                    foreach ($instance->data as $key ) {
                        echo $key->nombre;
                    }*/
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
