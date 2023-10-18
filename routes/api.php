<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('chat',
    function(Request $request) {
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        return response()->json([
            [
                'id' => 1,
                'usuario' => 'alice',
                'mensagem' => 'Minha primeira publicação.',
                'enviada' => '2023-10-07T15:30:00',
            ],
            [
                'id' => 2,
                'usuario' => 'bob',
                'mensagem' => 'Publicação exemplo.',
                'enviada' => '2023-10-08T07:30:00',
            ]
        ]);
    }
);

Route::post('chat',
    function(Request $request) {
        // o certo seria implementar
        // $request->validate([
        //     'usuario' => 'required',
        //     'mensagem' => 'required|max:300',
        // ]);
        return response()->json(
            [
                'id' => '02',
                'usuario' => 'ERI',
                'mensagem' => "IFRN minha casa",
                'enviada' => '2023-10-08T09:40:00'
            ],
            201
        );
    }
);

Route::get('chat/{id}',
    function(Request $request, $id) {
        if ($id == 1) {
            return response()->json([
                'id' => 1,
                'usuario' => 'alice',
                'mensagem' => 'Minha primeira publicação.',
                'enviada' => '2023-10-07T15:30:00',
            ]);
        }

        if ($id == 2) {
            return response()->json([
                'id' => 2,
                'usuario' => 'bob',
                'mensagem' => 'Publicação exemplo.',
                'enviada' => '2023-10-08T07:30:00',
            ]);
        }

        return response()->json(
            [
                'id' => 1,
                'usuario' => 'alice',
                'mensagem' => 'Minha primeira publicação.',
                'enviada' => '2023-10-07T15:30:00',
            ],
            [
                'id' => 2,
                'usuario' => 'bob',
                'mensagem' => 'Publicação exemplo.',
                'enviada' => '2023-10-08T07:30:00',
            ],
            404
        );
    }
);


Route::put('chat/{id}',
    function(Request $request, $id) {
        $request->validate(['texto' => 'max:144']);
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        if (in_array($id, [1,2])) {
            return response()->json(
                [
                    'tipo' => 'info',
                    'conteudo' => "Publicação alterada (mentira, falta implementar isso).",
                ]
            );
        }

        return response()->json(
            [
                'tipo' => 'erro',
                'conteudo' => 'Não encontrado.'
            ], 404
        );
    }
);



Route::delete('chat/{id}',
    function(Request $request, $id) {
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        if (in_array($id, [1,2])) {
            return response()->json(
                [
                    'tipo' => 'info',
                    'conteudo' => "Publicação apagada (mentira, falta implementar isso).",
                ]
            );
        }

        return response()->json(
            [
                'tipo' => 'erro',
                'conteudo' => 'Publicação não encontrada.'
            ],
            404
        );
    }
);