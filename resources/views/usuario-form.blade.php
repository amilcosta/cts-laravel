@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="title-box">Usuario </div> 
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

                    <form method="POST"  novalidate>

                        <div class="form-group">
                            <label for="rut">Rut</label>
                            <input type="text" id="rut" class="form-control" name="rut" placeholder="Rut" required/>
                        </div>

                        <div class="form-group">
                            <label for="use_name">Nombre</label>
                            <input type="text" id="use_name" class="form-control" name="use_name" placeholder="Nombre" required/>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" class="form-control" name="apellido" placeholder="Apellido" required/>
                        </div>
                        <div class="form-group">
                            <label for="nick">Seudónimo</label>
                            <input type="text" id="nick" class="form-control" name="nick" placeholder="Seudónimo" required/>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo Rol</label>
                            <select id="tipo" name="tipo" class="form-control">
                                <option value="">Ninguno</option>
                            <?php
                                $request = Request::create('http://test-cts/rol', 'GET');
                                $instance = json_decode(Route::dispatch($request)->getContent());
                                
                                foreach ($instance->data as $key ) {
                                    //echo $key->nombre;
                            ?>
                                <option value="<?php echo $key->id?>"><?php echo $key->nombre?></option>
                                
                            <?php
                                }
                            ?>    
                            </select>
                        </div>


                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        
                        <button id="save-usu"  type="button" class="btn btn-success" >Guardar!</button>
                        <div class="error-message" style="display: none;"></div>
                        <div class="success-message" style="display: none;"></div>

                    </form>

                </div>
                <br>

                <div class="panel-heading">Lista de Usuarios</div>
                <div class="panel-body">
                    <div class="area-lista titulo-border">
                        <div class="textos-usuarios titulos-lista">Rut</div>
                        <div class="textos-usuarios titulos-lista">Nombre</div>
                        <div class="textos-usuarios titulos-lista">Apellido</div>
                        <div class="textos-usuarios titulos-lista">Seudónimo</div>
                    </div>
                    <?php
                        $request = Request::create('http://test-cts/usuario', 'GET');
                        $instance = json_decode(Route::dispatch($request)->getContent());
                        
                        foreach ($instance->data as $key ) {
                            //echo $key->nombre;
                    ?>
                            <div class="area-lista texto-border">
                                <div class="textos-usuarios "><?php echo $key->rut?></div>
                                <div class="textos-usuarios"><?php echo $key->nombre?></div>
                                <div class="textos-usuarios"><?php echo $key->apellido?></div>
                                <div class="textos-usuarios"><?php echo $key->usuario?></div>
                                <div style="width:32px;float: left;"><a href="/edit-user/<?php echo $key->id_usuario?>">Edit</a></div>
                                <div style="width:32px;float: left;">
                                    <a id="<?php echo $key->id_usuario?>" name="<?php echo $key->rol_id?>" class="deluser" >Delete</a>
                                    
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