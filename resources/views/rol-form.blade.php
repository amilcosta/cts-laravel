@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="title-box">Roles </div> 
                    <div style="display: inline;">
                        <button class="btn " onclick="location.href='{{ url('/home') }}'">regresar</button>
                    </div>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="/rol" novalidate>

                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" id="name" class="form-control" name="name" placeholder="Nombre de Rol" required/>
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <input type="text" id="tipo" class="form-control" name="tipo" placeholder="Tipo de Rol" required/>
                        </div>

                        

                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <!--
         Si queremos que escriba el campo token completo podemos usar {{ csrf_field() }}
        y genera automáticamente el input con todos los datos del token.
        En otro caso pondremos <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        -->
                        <button id="save-rol" type="button" class="btn btn-success">Guardar!</button>
                        <div class="error-message" style="display: none;"></div>
                        <div class="success-message" style="display: none;"></div>
                    </form>

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
                <br>

                <div class="panel-heading">Lista de roles</div>
                <div class="panel-body">
                    <div class="area-lista titulo-border">
                        <div class="textos-lista titulos-lista">Nombre de rol</div>
                        <div class="textos-lista titulos-lista">Tipo de rol</div>
                    </div>
                    <?php
                        $request = Request::create('http://test-cts/rol', 'GET');
                        $instance = json_decode(Route::dispatch($request)->getContent());
                        //print_r($instance);
                        
                        foreach ($instance->data as $key ) {
                            //echo $key->nombre;
                            ?>
                            <div class="area-lista texto-border">
                                <div class="textos-lista "><?php echo $key->nombre?></div>
                                <div class="textos-lista"><?php echo $key->tipo?> </div>
                                <div style="width:32px;float: left;"><a href="/edit-rol/<?php echo $key->id?>">Edit</a></div>
                                <div style="width:32px;float: left;">
                                    <a id="<?php echo $key->id?>" class="delrol" >Delete</a>
                                    <input id="idrol-<?php echo $key->id?>" type="hidden" value="<?php echo $key->id?>"  />
                                </div>    
                                
                            </div>
                            <?php
                        }
                    ?>
                    <div id="error-message-del" class="error-message" style="display: none;float:left;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection