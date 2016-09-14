<?php

namespace App\Http\Controllers;

use App\SintegraES;
use Illuminate\Support\Facades\Session;
use Request;

use App\Http\Requests;

class CnpjController extends Controller
{
    public function index()
    {
        if (auth()->guest()) {
            return view('welcome');
        }
        $consultas = SintegraES::all();

        return view('index', compact('consultas'));
    }

    public function consultar()
    {
        if (auth()->guest()) {
            return view('welcome');
        }
        return view('cnpj_consultar');
    }

    public function show()
    {
        $cnpj = Request::get('cnpj');

        $html = $this->getSintegraEsData($cnpj);

        $cnpjData = $this->stripCnpjData($html);

        if (is_null($cnpjData)) {
            Session::flash('flash_message', 'CNPJ inválido ou não existe na base do Sintegra ES');
            return redirect()->back();
        }

        // We send 'cnpj' to the view too, because in that view we can save search.
        return view('show_cnpj', compact('cnpj', 'cnpjData'));
    }

    public function store()
    {
        $user = auth()->user()->id;

        $cnpj = Request::get('cnpj');

        // Removes punctuation to save only numbers.
        $cnpj = preg_replace("/[^0-9]/", "", $cnpj);

        $cnpjData = json_decode(Request::get('json_cnpj'));

        SintegraES::create([
            'idusuario' => $user,
            'cnpj' => $cnpj,
            'resultado_json' => $cnpjData
        ]);

        return redirect()->route('index');
    }

    public function destroy($id)
    {
        $consulta = SintegraES::findOrFail($id);

        $consulta->delete();

        return redirect()->route('index');
    }

    /**
     * Get data from Sintegra ES webservice.
     *
     * @param $cnpj
     * @return mixed
     */
    private function getSintegraEsData($cnpj)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://www.sintegra.es.gov.br/resultado.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = array('num_cnpj' => $cnpj, 'num_ie' => '', 'botao' => 'Consultar');

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $html = curl_exec($ch);

        curl_close($ch);

        return ($html);
    }

    /**
     * Build Json with CNPJ data from Sintegra ES html page.
     *
     * @param $html
     * @return string
     */
    private function stripCnpjData($html)
    {
        $re = "/<div id=\"conteudo\"[^>]*>(.*?)<\\/div>/si";

        preg_match_all($re, $html, $matches);

        $html = $matches[0][0];

        // Identificação - pessoa jurídica
        $re = "/<table width=\"100%\" border=\"0\" cellspacing=\"[12]\" cellpadding=\"[12]\">[^>]*>(.*?)<\\/table>/si";

        preg_match_all($re, $html, $identificacao);

        // Verifica se CNPJ existe no Sintegra ES
        if (empty($identificacao[0][0])) {
            return null;
        }

        $re = "/<td class=\"valor\"[^>]*>(.*?)<\\/td>/si";

        preg_match_all($re, $identificacao[0][0], $valores);

        $valores_identificacao = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

        // "Array identidade"
        $identidade = [
            'cnpj' => $valores_identificacao[0],
            'inscricao_estadual' => $valores_identificacao[1],
            'razao_social' => $valores_identificacao[2]
        ];

        // "Endereço"
        $re = "/<td (class=\"valor\"|width=\"30%\")[^>]*>(.*?)<\\/td>/si";

        preg_match_all($re, $identificacao[0][1], $valores);

        $valores_endereco = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

        // Array endereco
        $endereco = [
            'logradouro' => $valores_endereco[0],
            'numero' => $valores_endereco[1],
            'complemento' => $valores_endereco[2],
            'bairro' => $valores_endereco[3],
            'municipio' => $valores_endereco[4],
            'uf' => $valores_endereco[5],
            'cep' => $valores_endereco[6],
            'telefone' => $valores_endereco[7]
        ];

        // "Informações complementares"
        $re = "/<td class=\"valor\"[^>]*>(.*?)<\\/td>/si";

        preg_match_all($re, $identificacao[0][2], $valores);

        $valores_infs_complementares = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

        preg_match_all($re, $identificacao[0][3], $valores);

        $valores_infs_complementares2 = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

        preg_match_all($re, $identificacao[0][5], $valores);

        $valores_infs_complementares3 = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

        // Array info_complementares
        $info_complementares = [
            'atividade_economica' => $valores_infs_complementares[0],
            'data_inicio_atividade' => $valores_infs_complementares[1],
            'situacao_cadastral_vigente' => $valores_infs_complementares[2],
            'data_desta_situacao_cadastral' => $valores_infs_complementares[3],
            'regime_apuracao' => $valores_infs_complementares2[0],
            'emitente_nfe_desde' => $valores_infs_complementares3[0]
        ];

        // Create array with cnpj data
        $dataCnpj = ['identidade' => $identidade,
            'endereco' => $endereco,
            'info_complementares' => $info_complementares
        ];

        return $dataCnpj;
    }

}
