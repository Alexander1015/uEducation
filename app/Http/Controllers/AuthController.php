<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json($user);
    }


    public function store(Request $request)
    {
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
            if(Auth::attempt($request->only('user', 'password'))) {
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
        }
    }
}
