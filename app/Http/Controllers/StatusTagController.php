<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class StatusTagController extends Controller
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
        $status = auth()->user()->status;
        if ($status == 1) {
            try {
                $data = DB::table("tags")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La etiqueta seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'status' => ['bail', 'required', 'in:0,1'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Ah ocurrido un error con el envío de información',
                            'complete' => false,
                        ]);
                    } else {
                        if (DB::update("UPDATE tags SET status = ? WHERE id = ?", [
                            $request->input('status'),
                            $data->id,
                        ])) {
                            return response()->json([
                                'message' => 'Se ha cambiado el estado de la etiqueta exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ah ocurrido un error al momento de cambiar el estado de la etiqueta',
                                'complete' => false,
                            ]);
                        }
                    }
                }
            } catch (Exception $ex) {
                return response()->json([
                    // 'message' => $ex->getMessage(),
                    'message' => "Ah ocurrido un error en la aplicación",
                    'complete' => false,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'El usuario actual esta desactivado',
                'complete' => false,
            ]);
        }
    }
}