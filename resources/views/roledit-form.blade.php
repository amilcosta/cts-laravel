@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="title-box">Edit Role </div> 
                    <div style="display: inline;">
                        <button class="btn " onclick="location.href='{{ url('/roles') }}'">regresar</button>
                    </div>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    

                    <form method="POST" action="/rol/{{ $idr->id}}" novalidate>

                        <div class="form-group">
                            <label for="name_edit">Nombre</label>
                            <input type="text" id="nombre" class="form-control" name="nombre" placeholder="{{ $idr->nombre }}" required/>
                        </div>

                        <div class="form-group">
                            <label for="tipo_edit">Tipo</label>
                            <input type="text" id="tipo" class="form-control" name="tipo" placeholder="{{ $idr->tipo }}" required/>
                        </div>
                        <input name="_method" type="hidden" value="PATCH">
                        <!--<input id="id_edit" type="hidden" value="{{ $idr->id}}" />-->

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