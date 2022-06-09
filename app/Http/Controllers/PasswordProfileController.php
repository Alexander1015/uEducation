<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;


class PasswordProfileController extends Controller
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
                $data = DB::table("users")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El usuario seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'password' => ['bail', 'required', 'string', 'min:8', 'max:50', 'confirmed'],
                    ]);
                    if ($validator->fails()) {
                        if ($request->input('password') != $request->input('password_confirmation')) {
                            return response()->json([
                                'message' => 'Las contraseñas ingresadas no son iguales',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Hay datos que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    } else {
                        $user_auth = auth()->user()->id;
                        if ($user_auth != $data->id) {
                            return response()->json([
                                'message' => "No puede actualizar la contraseña del usuario en este apartado",
                                'complete' => false,
                            ]);
                        } else {
                            if (DB::update("UPDATE users SET password = ? WHERE id = ?", [
                                Hash::make($request->input('password')),
                                $data->id,
                            ])) {
                                return response()->json([
                                    'message' => 'Contraseña actualizada exitosamente',
                                    'complete' => true,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ah ocurrido un error al momento de modificar la contraseña usuario',
                                    'complete' => false,
                                ]);
                            }
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
