<?php

require 'credenciais.php';
require 'vendor/autoload.php';


use GuzzleHttp\Client;



# VARIÁVEIS E CONSTANTES


const URL_SUAP_API = 'https://suap.ifrn.edu.br/api/v2';

$cliente_http = new Client(['cookies' => true]);

# PROGRAMA PRINCIPAL


$token = login_SUAP($usuario, $senha, $cliente_http);

$dados = acessar_dados($token, $cliente_http);


// echo "Usuário Logado
// --------------
// Nome: {$dados['nome_usual']}
// Matrícula: {$dados['matricula']}
// Vínculo: {$dados['tipo_vinculo']}
// ";

// // Inicializa a sessão cURL
// $curl = curl_init();

// // Define a URL da API
// //$url = 'http://{host}:{porta}/api/chat';
// $url = 'http://localhost:8000/api/chat';

// // Define as opções da requisição
// curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// // Envia a requisição e obtém a resposta
// $response = curl_exec($curl);

// // Fecha a sessão cURL
// curl_close($curl);

// // Exibe a resposta
// echo $response;



// Função para exibir as opções e obter a escolha do usuário
function exibirOpcoes() {
    echo "Escolha uma opção:\n";
    echo "1. Entrar no chat\n";
    echo "2. Ver mensagem específica por ID\n";
}


// Obtém a escolha do usuário
exibirOpcoes();
$opcao = readline("Digite o número da opção desejada: ");

// Processa a escolha do usuário
switch ($opcao) {
    case 1:
        // Inicializa a sessão cURL
$curl = curl_init();

// URL da API
$url = 'http://localhost:8000/api/chat';

// Define as opções da requisição
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Envia a requisição e obtém a resposta
$response = curl_exec($curl);

// Fecha a sessão cURL
curl_close($curl);

// Exibe a resposta
echo $response;

//json_decode para formatar a mensagem
//assosiative true
//prog-orientada-a-servicos/formatos/json/ler_json.php

        break;
    case 2:
        // Lógica para ver mensagem por ID
        $mensagemId = readline("Digite o ID da mensagem que deseja ver: ");
        $urlMensagem = "http://localhost:8000/api/chat/$mensagemId";
        
        // Inicializa a sessão cURL para a segunda requisição
        $curlMensagem = curl_init();
        curl_setopt($curlMensagem, CURLOPT_URL, $urlMensagem);
        curl_setopt($curlMensagem, CURLOPT_RETURNTRANSFER, true);

        // Envia a segunda requisição e obtém a resposta
        $responseMensagem = curl_exec($curlMensagem);

        // Fecha a sessão cURL da segunda requisição
        curl_close($curlMensagem);

        // Exibe a mensagem específica
        echo "Mensagem específica por ID:\n";
        echo $responseMensagem . "\n";
        break;
    default:
        echo "Opção inválida.\n";
        break;
}



# FUNÇÕES


function login_SUAP($usuario, $senha, $cliente_http): string {
    // Prepara os dados da requisição
    $url = URL_SUAP_API . '/autenticacao/token/';
    $params = [
        'form_params' => [
            'username' => $usuario,
            'password' => $senha
        ]
    ];
    // Envia a requisição usando o cliente Guzzle
    $res = $cliente_http->post($url, $params);
    // Decodifica os dados da resposta JSON
    $dados = json_decode($res->getBody());
    // Pega o token de acesso
    $token = $dados->access;

    return $token;
}


function acessar_dados($token, $cliente_http): array {
    $res = $cliente_http->get(
        URL_SUAP_API . '/minhas-informacoes/meus-dados/',
        [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]
    );
    $dados = json_decode($res->getBody(), associative: true);
    return $dados;
}