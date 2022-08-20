<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class StudentsController extends Controller
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
                    'students' => ['bail', 'required'],
                    'subject' => ['bail', 'required', 'string'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    if (!is_array($request->input('students')) || sizeof($request->input('students')) < 1) {
                        return response()->json([
                            'message' => 'Los estudiantes proporcionados no poseen el formato solicitado',
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
                            if ($auth_user->type == "0" || $review) {
                                $total = 0;
                                foreach ($request->input('students') as $user) {
                                    $data_user = DB::table("users")->where("type", "2")->where("slug", $user)->first();
                                    if ($data_user) {
                                        $review = DB::table("user_subject")->where("subject_id", $data_subject->id)->where("user_id", $data_user->id)->first();
                                        if ($review) {
                                            DB::table("user_subject")->delete($review->id);
                                        }
                                    } else $total++;
                                }
                                if ($total == (sizeof($request->input('students')))) { // Si ninguno se ingreso
                                    return response()->json([
                                        'message' => 'No se pudieron desuscribir todos los estudiantes seleccionados',
                                        'complete' => true,
                                    ]);
                                } else if ($total > 0 && $total < (sizeof($request->input('students')))) { // Si No todos se pudieron ingresar
                                    return response()->json([
                                        'message' => 'No se pudieron desuscribir algunos de los estudiantes seleccionados',
                                        'complete' => true,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Se han desuscrito exitosamente todos los estudiantes seleccionados',
                                        'complete' => true,
                                    ]);
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
                'message' => $ex->getMessage(),
                // 'message' => "Ha ocurrido un error en la aplicación",
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
                // Obtenemos los estudiantes que no esten suscritos a la materia
                $users = DB::table('users')->where("type", "2")->orderBy('user', 'asc')->get();
                $subjects = DB::table('subjects')->where("slug", $slug)->first();
                if ($subjects) {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $subjects->id)->first();
                    if ($auth_user->type == "0" || $review) {
                        $data_all = array();
                        $data_subject = array();
                        foreach ($users as $item) {
                            if ($auth_user->id != $item->id) {
                                $user_subject = DB::table('user_subject')->where("user_id", $item->id)->where("subject_id", $subjects->id)->get();
                                if (sizeof($user_subject) == 0 && $item->status == "1") {
                                    array_push($data_all, $item);
                                } else if (sizeof($user_subject) > 0) {
                                    array_push($data_subject, $item);
                                }
                            }
                        }
                        return response()->json([
                            "all_students" => $data_all,
                            "subject_students" => $data_subject
                        ]);
                    } else {
                        return response()->json([
                            "all_students" => [],
                            "subject_students" => []
                        ]);
                    }
                } else {
                    return response()->json([
                        "all_students" => [],
                        "subject_students" => []
                    ]);
                }
            } else {
                return response()->json([
                    "all_students" => [],
                    "subject_students" => []
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                "all_students" => [],
                "subject_students" => []
            ]);
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
                    if ($auth_user->type == "0" || $review) {
                        $validator = Validator::make($request->all(), [
                            'students' => ['bail', 'required'],
                        ]);
                        if ($validator->fails()) {
                            return response()->json([
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        } else {
                            if (!is_array($request->input('students')) || sizeof($request->input('students')) < 1) {
                                return response()->json([
                                    'message' => 'Los estudiantes proporcionados no poseen el formato solicitado',
                                    'complete' => false,
                                ]);
                            } else {
                                $total = 0;
                                foreach ($request->input('students') as $user) {
                                    $data_user = DB::table("users")->where("type", "2")->where("slug", $user)->first();
                                    if ($data_user) {
                                        $review = DB::table("user_subject")->where("subject_id", $data_subject->id)->where("user_id", $data_user->id)->first();
                                        if (!$review) {
                                            DB::table("user_subject")->insert([
                                                'subject_id' => $data_subject->id,
                                                'user_id' => $data_user->id,
                                            ]);
                                        }
                                    } else $total++;
                                }
                                if ($total == (sizeof($request->input('students')))) { // Si ninguno se ingreso
                                    return response()->json([
                                        'message' => 'No se pudieron suscribir todos los estudiantes seleccionados',
                                        'complete' => true,
                                    ]);
                                } else if ($total > 0 && $total < (sizeof($request->input('students')))) { // Si No todos se pudieron ingresar
                                    return response()->json([
                                        'message' => 'No se pudieron suscribir algunos de los estudiantes seleccionados',
                                        'complete' => true,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Se han suscrito exitosamente todos los estudiantes seleccionados',
                                        'complete' => true,
                                    ]);
                                }
                            }
                        }
                    } else {
                        return response()->json([
                            'message' => 'El usuario actualno tiene los permisos necesarios',
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
