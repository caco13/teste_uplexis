@extends('layouts.app')

@section('content')
    @if ($consultas->isEmpty())
        <div class="row">
            <p>Você não tem consultas salvas. Clique em "Consultar CNPJ" no menu para realizar uma nova consulta.</p>
        </div>
    @else
        <h1>Consultas salvas</h1>

        <table class="table">
            <thead>
            <tr>
                <th>Cnpj</th>
                <th>Inscrição Estadual</th>
                <th>Razão Social</th>
                <th>Remover</th>
            </tr>
            </thead>
            <tbody>
            @foreach($consultas as $row)
                <tr>
                    <td>{{ $row->resultado_json['identidade']['cnpj'] }}</td>
                    <td>{{ $row->resultado_json['identidade']['inscricao_estadual'] }}</td>
                    <td>{{ $row->resultado_json['identidade']['razao_social'] }}</td>
                    <td>
                        {!! Form::open(['route' => ['destroy', $row->id], 'method' => 'delete']) !!}
                        {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-default glyphicon glyphicon-remove'])
                        !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection