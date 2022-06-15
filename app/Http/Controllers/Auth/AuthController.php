<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user' => ['bail', 'required'],
                'password' => ['bail', 'required'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Hay datos solicitados que no han sido ingresados',
                    'complete' => false,
                ]);
            } else {
                $user_state = DB::table('users')->where('user', $request->input('user'))->first();
                if ($user_state->user) {
                    if ($user_state->status == 1) {
                        if (Auth::attempt($request->only('user', 'password'))) {
                            return response()->json([
                                'message' => 'Bienvenido',
                                'complete' => true,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Las credenciales proporcionadas son incorrectas',
                                'complete' => false,
                            ]);
                        }
                    } else {
                        return response()->json([
                            'message' => 'El usuario esta desactivado y no tiene permitido el ingreso al sistema',
                            'complete' => false,
                        ]);
                    }
                } else {
                    return response()->json([
                        'message' => 'Las credenciales proporcionadas son incorrectas',
                        'complete' => false,
                    ]);
                }
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
