<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = DB::table('subjects')->get();
        return response()->json($subjects);
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
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                $validator = Validator::make($request->all(), [
                    'name' => ['bail', 'required', 'string', 'max:100', 'unique:subjects,name'],
                ]);
                if ($validator->fails()) {
                    $old_name = DB::table("subjects")->where('name', $request->input('name'))->first();
                    if ($old_name) {
                        return response()->json([
                            'message' => 'El titulo ingresado ya existe',
                            'complete' => false,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'El dato proporcionado no sigue el formato solicitado',
                            'complete' => false,
                        ]);
                    }
                } else {
                    if (DB::table("subjects")
                        ->insert([
                            'name' => $request->input('name'),
                        ])
                    ) {
                        return response()->json([
                            'message' => 'Curso creado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ha ocurrido un error al momento de crear el curso',
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = DB::table("subjects")->where("id", $id)->first();
        return response()->json($subject);
    }

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
                $data = DB::table("subjects")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El curso seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'name' => ['bail', 'required', 'string', 'max:100', 'unique:subjects,name,' . $data->id],
                    ]);
                    if ($validator->fails()) {
                        $old_name = DB::table("subjects")->where('name', $request->input('name'))->first();
                        if ($data->id != $old_name->id) {
                            return response()->json([
                                'message' => 'El titulo ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'El dato proporcionado no sigue el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    } else {
                        if (DB::update("UPDATE subjects SET name = ? WHERE id = ?", [
                            $request->input('name'),
                            $data->id,
                        ])) {
                            return response()->json([
                                'message' => 'Curso modificado exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            if (
                                $data->name == $request->input('name')
                            ) {
                                return response()->json([
                                    'message' => 'La información proporcionada no modifica el curso, asi que no se ha actualizado',
                                    'complete' => false,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ha ocurrido un error al momento de modificar el curso',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("subjects")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El curso seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $topics = DB::table("topics")->where("subject_id", $id)->get();
                    if (sizeof($topics) > 0) {
                        DB::update("UPDATE topics SET subject_id = ? WHERE subject_id = ?", [
                            null,
                            $id,
                        ]);
                    }
                    if (DB::table("subjects")->delete($data->id)) {
                        return response()->json([
                            'message' => 'Curso eliminado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ha ocurrido un error al momento de eliminar el curso',
                            'complete' => true,
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
