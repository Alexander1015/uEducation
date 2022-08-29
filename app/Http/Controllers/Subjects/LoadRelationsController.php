<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoadRelationsController extends Controller
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
                    'users' => ['bail', 'required'],
                    'subjects' => ['bail', 'required'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    if (!is_array($request->input('users')) || sizeof($request->input('users')) < 1) {
                        return response()->json([
                            'message' => 'No existen usuarios en la información proporcionada',
                            'complete' => false,
                        ]);
                    } else {
                        if (!is_array($request->input('subjects')) || sizeof($request->input('subjects')) < 1) {
                            return response()->json([
                                'message' => 'No existen materias en la información proporcionada',
                                'complete' => false,
                            ]);
                        } else {
                            if (sizeof($request->input('users')) != sizeof($request->input('subjects'))) {
                                return response()->json([
                                    'message' => 'La información proporcionada no concuerda entre si',
                                    'complete' => false,
                                ]);
                            } else {
                                // Comenzamos a ingresar los datos
                                // Si algunas de los datos ya existen solo se ignoraran y se daran por exitosas
                                $array_error = array();
                                for ($i = 0; $i < sizeof($request->input('users')); $i++) {
                                    $user = DB::table("users")->where("user", $request->input('users')[$i])->first();
                                    $subject = DB::table("subjects")->where("code", $request->input('subjects')[$i])->first();
                                    if ($user && $subject) {
                                        $review = DB::table("user_subject")->where("subject_id", $subject->id)->where("user_id", $user->id)->first();
                                        if ($user->type != 0 && !$review) {
                                            if (!DB::table("user_subject")
                                                ->insert([
                                                    'user_id' => $user->id,
                                                    'subject_id' => $subject->id
                                                ])) {
                                                array_push($array_error, [
                                                    'user' => $request->input('users')[$i] ? $request->input('users')[$i] : "",
                                                    'subject' => $request->input('subjects')[$i] ? $request->input('subjects')[$i] : ""
                                                ]);
                                            }
                                        }
                                    } else {
                                        array_push($array_error, [
                                            'user' => $request->input('users')[$i] ? $request->input('users')[$i] : "",
                                            'subject' => $request->input('subjects')[$i] ? $request->input('subjects')[$i] : ""
                                        ]);
                                    }
                                }
                                $message = "";
                                if (sizeof($array_error) == sizeof($request->input('users'))) {
                                    return response()->json([
                                        'message' => "Verifique que la información proporcionada este correcta debido a que no se pudo ingresar ninguna suscripción",
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
