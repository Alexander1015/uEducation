<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;


class PasswordUserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
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
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    } else {
                        $user_auth = auth()->user()->id;
                        if ($user_auth == $data->id) {
                            return response()->json([
                                'message' => "Debe modificar su contraseña en el perfil de su usuario",
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
                                    'message' => 'Ha ocurrido un error al momento de modificar la contraseña usuario',
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
