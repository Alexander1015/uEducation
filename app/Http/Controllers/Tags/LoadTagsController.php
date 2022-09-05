<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

class LoadTagsController extends Controller
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
                    'name' => ['bail', 'required'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    if (!is_array($request->input('name')) || sizeof($request->input('name')) < 1) {
                        return response()->json([
                            'message' => 'Faltan datos en la información proporcionada',
                            'complete' => false,
                        ]);
                    } else {
                        if (sizeof($request->input('name')) != sizeof($request->input('code'))) {
                            return response()->json([
                                'message' => 'La información proporcionada no concuerda entre si',
                                'complete' => false,
                            ]);
                        } else {
                            // Comenzamos a ingresar los datos
                            // Si algunas de los datos ya existen solo se ignoraran y se daran por exitosas
                            $array_error = array();
                            for ($i = 0; $i < sizeof($request->input('name')); $i++) {
                                $slug = Str::slug($request->input('name')[$i]);
                                $old_slug = DB::table("tags")->where('name', $slug)->first();
                                if (!$old_slug) {
                                    if ($request->input('name')[$i] != "") {
                                        if (!DB::table("tags")
                                            ->insert([
                                                'slug' => $slug,
                                                'name' => $request->input('name')[$i],
                                                'background_color' => "#E0E0E0",
                                                'text_color' => "#676767",
                                            ])) {
                                            array_push($array_error, [
                                                'name' => $request->input('name')[$i] ? $request->input('name')[$i] : ""
                                            ]);
                                        }
                                    } else {
                                        array_push($array_error, [
                                            'name' => $request->input('name')[$i] ? $request->input('name')[$i] : ""
                                        ]);
                                    }
                                }
                            }
                            $message = "";
                            if (sizeof($array_error) == sizeof($request->input('name'))) {
                                return response()->json([
                                    'message' => "Verifique que la información proporcionada este correcta debido a que no se pudo ingresar ninguna materia",
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
