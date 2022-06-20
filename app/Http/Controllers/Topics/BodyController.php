<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class BodyController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("topics")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    if (DB::update("UPDATE topics SET body = ? WHERE id = ?", [
                        $request->input('body') ? $request->input('body') : "",
                        $data->id,
                    ])) {
                        return response()->json([
                            'message' => 'Contenido guardado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        if (
                            $data->body == $request->input('body')
                        ) {
                            return response()->json([
                                'message' => 'La información proporcionada no modifica el contenido, asi que no se ha actualizado',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ha ocurrido un error al momento de modificar el tema',
                                'complete' => false,
                            ]);
                        }
                    }
                }
            } else {
                return response()->json([
                    'message' => 'El usuario actual esta desactivado',
                    'complete' => false,
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                // 'message' => "Ha ocurrido un error en la aplicación",
                'complete' => false,
            ]);
        }
    }
}
