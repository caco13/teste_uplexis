@extends('layouts.app')

@section('content')
    <h1>Resultados da busca</h1>
    <section>
        <h3>Identificação - Pessoa Jurídica</h3>
        <table class="table">
            <thead>
            <tr>
                <th>CNPJ</th>
                <th>Razão Social</th>
                <th>Inscrição Estadual</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $cnpjData['identidade']['cnpj'] }}</td>
                <td>{{ $cnpjData['identidade']['inscricao_estadual'] }}</td>
                <td>{{ $cnpjData['identidade']['razao_social'] }}</td>
            </tr>
            </tbody>
        </table>

        <h3>Endereço</h3>
        <table class="table">
            <thead>
            <tr>
                <th>Logradouro</th>
                <th>Número</th>
                <th>Complemento</th>
                <th>Bairro</th>
                <th>CEP</th>
                <th>Município</th>
                <th>UF</th>
                <th>Telefone</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $cnpjData['endereco']['logradouro'] }}</td>
                <td>{{ $cnpjData['endereco']['numero'] }}</td>
                <td>{{ $cnpjData['endereco']['complemento'] }}</td>
                <td>{{ $cnpjData['endereco']['bairro'] }}</td>
                <td>{{ $cnpjData['endereco']['cep'] }}</td>
                <td>{{ $cnpjData['endereco']['municipio'] }}</td>
                <td>{{ $cnpjData['endereco']['uf'] }}</td>
                <td>{{ $cnpjData['endereco']['telefone'] }}</td>
            </tr>
            </tbody>
        </table>

        <h3>Informações complementares</h3>
        <table class="table">
            <thead>
            <tr>
                <th>Atividade econômica</th>
                <th>Data de início da atividade</th>
                <th>Situação cadastral vigente</th>
                <th>Data da situação cadastral</th>
                <th>Regime de apuração</th>
                <th>Emitente nfe desde</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $cnpjData['info_complementares']['atividade_economica'] }}</td>
                <td>{{ $cnpjData['info_complementares']['data_inicio_atividade'] }}</td>
                <td>{{ $cnpjData['info_complementares']['situacao_cadastral_vigente'] }}</td>
                <td>{{ $cnpjData['info_complementares']['data_desta_situacao_cadastral'] }}</td>
                <td>{{ $cnpjData['info_complementares']['regime_apuracao'] }}</td>
                <td>{{ $cnpjData['info_complementares']['emitente_nfe_desde'] }}</td>
            </tr>
            </tbody>
        </table>

        {{ Form::open(['url' => 'salvar']) }}
            {{ Form::hidden('cnpj', $cnpj) }}
            {{ Form::hidden('json_cnpj', json_encode($cnpjData)) }}
            <div class="form-group button-right">
                {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
            </div>
        {{ Form::close() }}

        <div class="form-group">
            {{ link_to_route('consultar', 'Nova Consulta', null, ['class' => 'btn btn-primary']) }}
        </div>
    </section>
@endsection