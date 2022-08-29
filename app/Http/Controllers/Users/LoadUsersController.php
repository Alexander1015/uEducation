<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;

class LoadUsersController extends Controller
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
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $validator = Validator::make($request->all(), [
                    'firstname' => ['bail', 'required'],
                    'lastname' => ['bail', 'required'],
                    'email' => ['bail', 'required'],
                    'user' => ['bail', 'required'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    if (!is_array($request->input('firstname')) || sizeof($request->input('firstname')) < 1) {
                        return response()->json([
                            'message' => 'Faltan datos en la información proporcionada',
                            'complete' => false,
                        ]);
                    } else {
                        if (!is_array($request->input('lastname')) || sizeof($request->input('lastname')) < 1) {
                            return response()->json([
                                'message' => 'Faltan datos en la información proporcionada',
                                'complete' => false,
                            ]);
                        } else {
                            if (!is_array($request->input('email')) || sizeof($request->input('email')) < 1) {
                                return response()->json([
                                    'message' => 'Faltan datos en la información proporcionada',
                                    'complete' => false,
                                ]);
                            } else {
                                if (!is_array($request->input('user')) || sizeof($request->input('user')) < 1) {
                                    return response()->json([
                                        'message' => 'Faltan datos en la información proporcionada',
                                        'complete' => false,
                                    ]);
                                } else {
                                    if (sizeof($request->input('firstname')) != sizeof($request->input('lastname')) && sizeof($request->input('email')) != sizeof($request->input('user')) && sizeof($request->input('firstname')) != sizeof($request->input('user'))) {
                                        return response()->json([
                                            'message' => 'La información proporcionada no concuerda entre si',
                                            'complete' => false,
                                        ]);
                                    } else {
                                        // Comenzamos a ingresar los datos
                                        // Si algunas de los datos ya existen solo se ignoraran y se daran por exitosas
                                        $array_error = array();
                                        for ($i = 0; $i < sizeof($request->input('firstname')); $i++) {
                                            $old_user = DB::table("users")->where('user', $request->input('user')[$i])->first();
                                            if (!$old_user) {
                                                $old_email = DB::table("users")->where('email', $request->input('email')[$i])->first();
                                                if (!$old_email) {
                                                    if ($request->input('firstname')[$i] != "" && $request->input('lastname')[$i] != "" && $request->input('email')[$i] != "" && $request->input('user')[$i] != "") {
                                                        $slug = Str::slug(Str::random(20));
                                                        while (DB::table("users")->where('slug', $slug)->first()) {
                                                            $slug = Str::random(20);
                                                        }
                                                        if (!DB::table("users")
                                                            ->insert([
                                                                'slug' => $slug,
                                                                'firstname' => $request->input('firstname')[$i],
                                                                'lastname' => $request->input('lastname')[$i],
                                                                'email' => $request->input('email')[$i],
                                                                'user' => strtolower($request->input('user')[$i]),
                                                                'password' => Hash::make($request->input('user')[$i]),
                                                                'type' => "1",
                                                                'avatar' => ""
                                                            ])) {
                                                            array_push($array_error, [
                                                                'firstname' => $request->input('firstname')[$i] ? $request->input('firstname')[$i] : "",
                                                                'lastname' => $request->input('lastname')[$i] ? $request->input('lastname')[$i] : "",
                                                                'email' => $request->input('email')[$i] ? $request->input('email')[$i] : "",
                                                                'user' => $request->input('user')[$i] ? $request->input('user')[$i] : ""
                                                            ]);
                                                        }
                                                    } else {
                                                        array_push($array_error, [
                                                            'firstname' => $request->input('firstname')[$i] ? $request->input('firstname')[$i] : "",
                                                            'lastname' => $request->input('lastname')[$i] ? $request->input('lastname')[$i] : "",
                                                            'email' => $request->input('email')[$i] ? $request->input('email')[$i] : "",
                                                            'user' => $request->input('user')[$i] ? $request->input('user')[$i] : ""
                                                        ]);
                                                    }
                                                }
                                            }
                                        }
                                        $message = "";
                                        if (sizeof($array_error) == sizeof($request->input('firstname'))) {
                                            return response()->json([
                                                'message' => "Verifique que la información proporcionada este correcta debido a que no se pudo ingresar ningún usuario",
                                                'complete' => false,
                                            ]);
                                        } else {
                                            if (sizeof($array_error) > 0) {
                                                $message = "No todos los datos proporcionados se pudieron cargar, a continuación se verán los antes mencionados para su revisión:";
                                            } else {
                                                $message = "Todos los datos han sido cargados exitosamente";
                                                $array_error = array();
                                            }
                                            return response()->json([
                                                'message' => $message,
                                                'fail' => $array_error,
                                                'complete' => true,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                return response()->json([
                    'message' => 'El usuario actual esta deshabilitado ó no tiene los permisos necesarios',
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
