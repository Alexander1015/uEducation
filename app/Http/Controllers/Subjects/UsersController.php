<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class UsersController extends Controller
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
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $validator = Validator::make($request->all(), [
                    'user' => ['bail', 'required', 'string'],
                    'subject' => ['bail', 'required', 'string'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    if ($auth_user->slug == $request->input("user")) {
                        return response()->json([
                            'message' => 'No puede desuscribirse usted mismo de la materia seleccionada',
                            'complete' => false,
                        ]);
                    } else {
                        $data_subject = DB::table("subjects")->where("slug", $request->input("subject"))->first();
                        if (!$data_subject) {
                            return response()->json([
                                'message' => "La materia seleccionada no existe",
                                'complete' => false,
                            ]);
                        } else {
                            $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $data_subject->id)->first();
                            if ($auth_user->type == "0" || ($review && $review->type == "1")) {
                                $data_user = DB::table("users")->where("slug", $request->input("user"))->first();
                                if (!$data_user) {
                                    return response()->json([
                                        'message' => "El usuario seleccionado no existe",
                                        'complete' => false,
                                    ]);
                                } else {
                                    $review = DB::table("user_subject")->where("subject_id", $data_subject->id)->where("user_id", $data_user->id)->first();
                                    if (!$review) {
                                        return response()->json([
                                            'message' => "Esa relación de materia y docente no existe",
                                            'complete' => false,
                                        ]);
                                    } else {
                                        if (DB::table("user_subject")->delete($review->id)) {
                                            return response()->json([
                                                'message' => 'Se ha desuscrito exitosamente el docente seleccionado',
                                                'complete' => true,
                                            ]);
                                        } else {
                                            return response()->json([
                                                'message' => 'Ha ocurrido un error al momento de eliminar el usuario',
                                                'complete' => true,
                                            ]);
                                        }
                                        DB::table("user_subject")->insert([
                                            'subject_id' => $data_subject->id,
                                            'user_id' => $data_user->id,
                                        ]);
                                    }
                                }
                            } else {
                                return response()->json([
                                    'message' => 'El usuario actual no tiene los permisos necesarios',
                                    'complete' => false,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                // Obtenemos los docentes que no esten suscritos a la materia
                $users = DB::table('users')->where("type", "1")->orderBy('user', 'asc')->get();
                $subjects = DB::table('subjects')->where("slug", $slug)->first();
                if ($subjects) {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $subjects->id)->first();
                    if ($auth_user->type == "0" || $review) {
                        $data_all = array();
                        $data_subject = array();
                        foreach ($users as $item) {
                            $user_subject = DB::table('user_subject')->where("user_id", $item->id)->where("subject_id", $subjects->id)->first();
                            if (!$user_subject && $item->status == "1") { // Si entra aqui es para la lista sin importar la materia
                                if ($item->id != $auth_user->id) {
                                    array_push($data_all, $item);
                                }
                            } else if ($user_subject) { // Si entra aqui es para la lista de la materia seleccionada
                                $item->access = $user_subject->type;
                                array_push($data_subject, $item);
                            }
                        }
                        if ($auth_user->type == "0" || $review->type == "1") {
                            return response()->json([
                                "all_users" => $data_all,
                                "subject_users" => $data_subject
                            ]);
                        } else {
                            return response()->json([
                                "all_users" => [],
                                "subject_users" => $data_subject
                            ]);
                        }
                    } else {
                        return response()->json([]);
                    }
                } else {
                    return response()->json([]);
                }
            } else {
                return response()->json([]);
            }
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $data_subject = DB::table("subjects")->where("slug", $slug)->first();
                if (!$data_subject) {
                    return response()->json([
                        'message' => "La materia seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $data_subject->id)->first();
                    if ($auth_user->type == "0" || ($review && $review->type == "1")) {
                        $validator = Validator::make($request->all(), [
                            'users' => ['bail', 'required'],
                        ]);
                        if ($validator->fails()) {
                            return response()->json([
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        } else {
                            if (!is_array($request->input('users')) || sizeof($request->input('users')) < 1) {
                                return response()->json([
                                    'message' => 'Los docentes proporcionados no poseen el formato solicitado',
                                    'complete' => false,
                                ]);
                            } else {
                                $total = 0;
                                foreach ($request->input('users') as $user) {
                                    $data_user = DB::table("users")->where("type", "1")->where("slug", $user)->first();
                                    if ($data_user && $data_user->id != $auth_user->id) {
                                        $review = DB::table("user_subject")->where("subject_id", $data_subject->id)->where("user_id", $data_user->id)->first();
                                        if (!$review) {
                                            DB::table("user_subject")->insert([
                                                'subject_id' => $data_subject->id,
                                                'user_id' => $data_user->id,
                                            ]);
                                        }
                                    } else $total++;
                                }
                                if ($total == (sizeof($request->input('users')))) { // Si ninguno se ingreso
                                    return response()->json([
                                        'message' => 'No se pudieron suscribir todos los docentes seleccionados',
                                        'complete' => true,
                                    ]);
                                } else if ($total > 0 && $total < (sizeof($request->input('users')))) { // Si No todos se pudieron ingresar
                                    return response()->json([
                                        'message' => 'No se pudieron suscribir algunos de los docentes seleccionados',
                                        'complete' => true,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Se han suscrito exitosamente todos los docentes seleccionados',
                                        'complete' => true,
                                    ]);
                                }
                            }
                        }
                    } else {
                        return response()->json([
                            'message' => 'El usuario actual no tiene los permisos necesarios',
                            'complete' => false,
                        ]);
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
