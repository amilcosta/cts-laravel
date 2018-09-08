@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="title-box">Edit Usuario </div> 
                    <div style="display: inline;">
                        <button class="btn " onclick="location.href='{{ url('/usuarios') }}'">regresar</button>
                    </div>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    

                    <form method="POST" action="/rol/{{ $idu->rol_id}}/usuario/{{ $idu->id_usuario}}" novalidate>

                        <div class="form-group">
                            <label for="rut">Rut</label>
                            <input type="text" id="rut" class="form-control" name="rut" placeholder="{{ $idu->rut }}" required/>
                        </div> 
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" class="form-control" name="nombre" placeholder="{{ $idu->nombre }}" required/>
                        </div>
                        <div class="form-group">
                            <label for="name_edit">apellido</label>
                            <input type="text" id="apellido" class="form-control" name="apellido" placeholder="{{ $idu->apellido }}" required/>
                        </div>

                        <div class="form-group">
                            <label for="seudonimo">Seudonimo</label>
                            <input type="text" id="seudonimo" class="form-control" name="seudonimo" placeholder="{{ $idu->usuario }}" required/>
                        </div>
                        <input name="_method" type="hidden" value="PATCH">
                        
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
 
                        <button id="edit-rol" type="submit" class="btn btn-success">Actualizar!</button>
                        <div class="error-message" style="display: none;"></div>
                        <div class="success-message" style="display: none;"></div>
                    </form>

                    
                </div>
                <br>

            </div>
        </div>
    </div>
</div>

@endsection