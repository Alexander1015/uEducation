<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class StatusTopicController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("topics")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'status' => ['bail', 'required', 'in:0,1'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Ha ocurrido un error con el envío de información',
                            'complete' => false,
                        ]);
                    } else {
                        if ($data->status == "0" && $request->input('status') == "1" && $data->body == "") {
                            return response()->json([
                                'message' => 'No se puede activar el tema, debido a que no tiene contenido para mostrar',
                                'complete' => false,
                            ]);
                        } else {
                            if (DB::update("UPDATE topics SET status = ? WHERE id = ?", [
                                $request->input('status'),
                                $data->id,
                            ])) {
                                $message = "";
                                if ($request->input('status') == 0) $message = "El estado del tema seleccionado se ha cambiado a borrador exitosamente";
                                else if ($request->input('status') == 1) $message = "Se ha activado el tema exitosamente";
                                return response()->json([
                                    'message' => $message,
                                    'complete' => true,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ha ocurrido un error al momento de cambiar el estado del tema',
                                    'complete' => false,
                                ]);
                            }
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
                // 'message' => $ex->getMessage(),
                'message' => "Ha ocurrido un error en la aplicación",
                'complete' => false,
            ]);
        }
    }
}
