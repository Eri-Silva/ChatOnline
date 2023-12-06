<?php

require 'credenciais.php';
require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;


const URL_SUAP_API = 'https://suap.ifrn.edu.br/api/v2';

$cliente_http = new GuzzleClient(['cookies' => true]);

# PROGRAMA PRINCIPAL

$token = login_SUAP($usuario, $senha, $cliente_http);
$dados = acessar_dados($token, $cliente_http);

// Função para exibir as opções e obter a escolha do usuário
function exibirOpcoes($dados) {
    echo "\n-------------------------\n";
    echo "\033[1;34m      Chat Online\033[0m\n"; // Cor azul brilhante
    echo "-------------------------";
    echo "\n Olá {$dados['nome_usual']}, escolha uma opção:\n";
    echo "1. Entrar no chat\n";
    echo "2. Ver mensagem específica por ID\n";
    echo "3. Enviar uma mensagem\n";
    echo "4. Editar uma mensagem\n";
    echo "5. Apagar uma mensagem\n";
    echo "0. Sair do chat\n\n";
}

do { 
// Obtém a escolha do usuário
exibirOpcoes($dados);
$opcao = readline("Digite o número da opção desejada: ");

// Processa a escolha do usuário
switch ($opcao) {
    case 1:
        // entrarNoChat($cliente_http, $token);

        // Lógica para entrar no chat
        $curl = curl_init();

        $url = 'http://localhost:8000/api/chat';

        // $response = $cliente_http->get($url, [
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . $token,
        //     ],
        // ]);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);

        // Exibe as mensagens formatadas
        $mensagens = json_decode($response, true);

        echo "\n Chat Online \n";
        foreach ($mensagens as $mensagem) {
            echo "\033[1;36mUsuário:\033[0m " . $mensagem['usuario'] . "\n"; // Cor ciano brilhante
                echo "\033[1;36mMensagem:\033[0m " . $mensagem['mensagem'] . "\n"; // Cor ciano brilhante
                echo "\033[1;36mID:\033[0m " . $mensagem['id'] . "\n"; // Cor ciano brilhante
                echo "\033[1;36mHora da Última Edição:\033[0m " . $mensagem['updated_at'] . "\n"; // Cor ciano brilhante
            echo "-------------------------\n";
        }

        break;
    case 2:
        // Lógica para ver mensagem por ID
        $mensagemId = readline("Digite o ID da mensagem que deseja ver: ");
        $urlMensagem = "http://localhost:8000/api/chat/$mensagemId";

        $curlMensagem = curl_init();

        curl_setopt($curlMensagem, CURLOPT_URL, $urlMensagem);
        curl_setopt($curlMensagem, CURLOPT_RETURNTRANSFER, true);

        $responseMensagem = curl_exec($curlMensagem);

        curl_close($curlMensagem);
       
        echo "\033[1;32mMensagem específica por ID:\033[0m\n";
        echo $responseMensagem . "\n";
        break;

    case 3:
        // Lógica para enviar uma mensagem
        $usuario = readline("Digite o nome de usuário: ");
        $mensagemTexto = readline("Digite a mensagem: ");

        $curlEnviarMensagem = curl_init();
        $urlEnviarMensagem = 'http://localhost:8000/api/chat';
        $dataEnviarMensagem = [
            'usuario' => $usuario,
            'mensagem' => $mensagemTexto,
        ];

        curl_setopt($curlEnviarMensagem, CURLOPT_URL, $urlEnviarMensagem);
        curl_setopt($curlEnviarMensagem, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlEnviarMensagem, CURLOPT_POST, true);
        curl_setopt($curlEnviarMensagem, CURLOPT_POSTFIELDS, $dataEnviarMensagem);

        $responseEnviarMensagem = curl_exec($curlEnviarMensagem);

        curl_close($curlEnviarMensagem);

        echo "\033[1;32mMensagem enviada:\033[0m\n";
        echo $responseEnviarMensagem . "\n";
        break;


        case 4:
    // Lógica para editar uma mensagem
    $mensagemIdEditar = readline("Digite o ID da mensagem que deseja editar: ");

    // Verifica se a mensagem com o ID fornecido existe
    $curlVerificarMensagem = curl_init();
    $urlVerificarMensagem = "http://localhost:8000/api/chat/$mensagemIdEditar";

    curl_setopt($curlVerificarMensagem, CURLOPT_URL, $urlVerificarMensagem);
    curl_setopt($curlVerificarMensagem, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlVerificarMensagem, CURLOPT_CUSTOMREQUEST, 'GET');

    $responseVerificarMensagem = curl_exec($curlVerificarMensagem);

    curl_close($curlVerificarMensagem);

    if (strpos($responseVerificarMensagem, "Não encontramos essa mensagem") !== false) {
        echo "Essa mensagem não foi encontrada para edição.\n";
    } else {
        // A mensagem existe, então solicitamos os dados para edição
        $usuarioEditar = readline("Digite o nome de usuário: ");
        $mensagemTextoEditar = readline("Digite a nova mensagem: ");

        // Cria um array associativo com os dados
        $dataEditarMensagem = [
            'usuario' => $usuarioEditar,
            'mensagem' => $mensagemTextoEditar,
        ];

        // Converte o array em formato JSON
        $dataEditarMensagemJson = json_encode($dataEditarMensagem);

        // Inicializa a requisição PUT
        $curlEditarMensagem = curl_init();
        $urlEditarMensagem = "http://localhost:8000/api/chat/$mensagemIdEditar";

        curl_setopt($curlEditarMensagem, CURLOPT_URL, $urlEditarMensagem);
        curl_setopt($curlEditarMensagem, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlEditarMensagem, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curlEditarMensagem, CURLOPT_POSTFIELDS, $dataEditarMensagemJson);
        curl_setopt($curlEditarMensagem, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dataEditarMensagemJson),
        ]);

        // Executa a requisição
        $responseEditarMensagem = curl_exec($curlEditarMensagem);

        // Fecha a requisição
        curl_close($curlEditarMensagem);

        // Exibe o resultado
        echo "\033[1;32mMensagem editada:\033[0m\n";
        echo $responseEditarMensagem . "\n";
    }
    break;

       
    case 5:
        // Lógica para apagar uma mensagem
        $mensagemIdApagar = readline("Digite o ID da mensagem que deseja apagar: ");

        $curlApagarMensagem = curl_init();
        $urlApagarMensagem = "http://localhost:8000/api/chat/$mensagemIdApagar";

        curl_setopt($curlApagarMensagem, CURLOPT_URL, $urlApagarMensagem);
        curl_setopt($curlApagarMensagem, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlApagarMensagem, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $responseApagarMensagem = curl_exec($curlApagarMensagem);

        curl_close($curlApagarMensagem);

        echo "\033[1;32mResposta:\033[0m\n";
        echo $responseApagarMensagem . "\n";
        break;
    case 0:
        // Lógica para sair do chat
        echo "Saindo do chat...\n";
        // Adicione aqui qualquer lógica adicional necessária para sair do chat
        break;
    default:
        echo "Opção inválida.\n";
        break;
}

} while ($opcao != 0);

// FUNÇÕES DO SUAP

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


// FUNÇÕES  DO CHAT

function entrarNoChat($cliente_http, $token) {
    
}

function verMensagemPorId() {
    // Lógica para ver mensagem por ID
    // ...
}

function enviarMensagem($cliente_http, $token) {
    // Lógica para enviar uma mensagem
    // ...
}

function editarMensagem($cliente_http, $token) {
    // Lógica para editar uma mensagem
    // ...
}

function apagarMensagem($cliente_http, $token) {
    // Lógica para apagar uma mensagem
    // ...
}
?>
