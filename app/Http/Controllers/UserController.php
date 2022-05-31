<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all(['id', 'firstname', 'lastname', 'user', 'email', 'password']);
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['bail', 'required', 'string', 'max:250'],
            'lastname' => ['bail', 'required', 'string', 'max:250'],
            'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user'],
            'email' => ['bail', 'required', 'email', 'max:250', 'unique:users,email'],
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
            $data = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'user' => $request->input('user'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ];
            $user = User::create($data);
            if ($user) {
                return response()->json([
                    'message' => 'Usuario creado exitosamente',
                    'complete' => true,
                ]);
            } else {
                return response()->json([
                    'message' => 'Ha ocurrido un error al crear el usuario',
                    'complete' => false,
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['bail', 'required', 'string', 'max:200'],
            'lastname' => ['bail', 'required', 'string', 'max:200'],
            'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user'],
            'email' => ['bail', 'required', 'email', 'unique:users,email'],
            'password' => ['bail', 'required', 'string', 'min:8', 'max:50', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Hay datos que no siguen el formato solicitado'
            ]);
        } else {
            $data = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'user' => $request->input('user'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ];
            $user->fill($data)->save();
            $user = User::create($data);
            if ($user) {
                return response()->json([
                    'mensaje' => 'Usuario actualizado exitosamente'
                ]);
            } else {
                return response()->json([
                    'mensaje' => 'Ha ocurrido un error al actualizar el usuario'
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'mensaje' => 'Usuario eliminado exitosamente'
        ]);
    }
}
