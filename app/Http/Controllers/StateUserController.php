<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Exception;

class StateUserController extends Controller
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
        $state = auth()->user()->state;
        if ($state == 1) {
            try {
                $data = DB::table("users")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El usuario seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'type' => ['bail', 'required', 'boolean'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Ah ocurrido un error con el envío de información',
                            'complete' => false,
                        ]);
                    } else {
                        if (DB::update("UPDATE users SET state = ? WHERE id = ?", [
                            $request->input('type'),
                            $data->id,
                        ])) {
                            return response()->json([
                                'message' => 'Se ha cambiado el estado del usuario exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ah ocurrido un error al momento de cambiar el estado del usuario',
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
