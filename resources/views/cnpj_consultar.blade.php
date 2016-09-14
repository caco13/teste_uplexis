@extends('layouts.app')

@section('content')
    <section class="consulta_cnpj">
        <h1>Consultar CNPJ</h1>
        <hr>
        {!! Form::open(['url' => 'consultar']) !!}
            <div class="form-group">
                {!! Form::label('cnpj', 'CNPJ') !!}
                {!! Form::text('cnpj', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Consultar', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        {!! Form::close() !!}
    </section>

    @if (Session::has('flash_message'))
        <br><br><br>
        <div class="alert alert-danger">
            {{Session::get('flash_message')}}
        </div>
    @endif

@endsection