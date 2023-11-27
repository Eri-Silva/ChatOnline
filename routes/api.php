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

// Route::post('chat',
//     function(Request $request) {
//         $call = Chat::create([
//             'usuario' => $request->post('usuario'),
//             'mensagem' => $request->post('mensagem'),
            
//         ]
//     );

//     $call->save();
//         return response()->json(
//             $call
//         );
//     }
// );

Route::post('chat', function(Request $request) {
    $chat = new Chat;
    $chat->usuario = $request->input('usuario');
    $chat->mensagem = $request->input('mensagem');
    $chat->save();

    $chatId = $chat->id;
    $uri = url('/chat/' . $chatId);

    //resposta JSON com o status 201 e o cabeçalho Location
    return response()->json($chat, 201)->header('Location', $uri);
});


Route::get('chat/{id}',
    function(Request $request, $id) {
        $mensagem = Chat::find($id);

        if ($mensagem != null) {
            return response($mensagem ,200);
        }

        return response("Não encontramos essa mensagem", 404);
    }
);


// Route::put('chat/{id}',
//     function(Request $request, $id) {
//          $mensagem = Chat::find($id);

//          if ($mensagem != null) {
//              $mensagem->usuario = $request->post('usuario');
//         $mensagem->mensagem = $request->post('mensagem');

//         $mensagem->save();
//             return response($mensagem ,200);
//         }
       


//         return response("Essa mensagem não foi encontrada para edição", 404);
//     }
// );

Route::put('chat/{id}', function(Request $request, $id) {
    $mensagem = Chat::find($id);

    if ($mensagem != null) {
        $mensagem->usuario = $request->input('usuario');
        $mensagem->mensagem = $request->input('mensagem');

        $mensagem->save();
        
        // Retornando uma resposta com status 204 (No Content) sem corpo (sem mensagem alterada)
        return response("Mensagem editada", 200);
    }

    return response("Essa mensagem não foi encontrada para edição", 404);
});


Route::delete('chat/{id}',
    function(Request $request, $id) {
        $mensagem = Chat::find($id);
        if ($mensagem != null) {
            $mensagem->delete();
            return response("Mensagem deletada",200);
        }
        

        return response("mensagem não encontrada", 404);
    }
);