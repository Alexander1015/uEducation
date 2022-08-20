<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class PasswordProfileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user_auth = auth()->user();
            if ($user_auth && $user_auth->status == 1) {
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
                    if (DB::update("UPDATE users SET password = ? WHERE id = ?", [
                        Hash::make($request->input('password')),
                        $user_auth->id,
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
