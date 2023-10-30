<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Chat;

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
        $chat = Chat::all();
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        return response()->json(
            $chat
        );
        
    }
);

Route::post('chat',
    function(Request $request) {
        $call = Chat::create([
            'usuario' => 'alice',
            'mensagem' => 'Minha primeira publicação.',
            
        ]
    );
        return response()->json(
            $call
        );
    }
);

Route::get('chat/{id}',
    function(Request $request, $id) {



        return response()->json(
            $chat,
            404
        );
    }
);


Route::put('chat/{id}',
    function(Request $request, $id) {
        $request->validate(['mensagem' => 'max:350']);
        
        $mensagem = Chat::find($id);
        // $mensagem->usuario = $request->post('usuario');
        // $mensagem->mensagem = $request->post('mensagem');

        $mensagem->save();

        return response()->json(
            $mensagem
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
                    'conteudo' => "Mensagem apagada",
                ]
            );
        }

        return response()->json(
            [
                'tipo' => 'Erro',
                'conteudo' => 'Mensagem não encontrada'
            ],
            404
        );
    }
);