<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = DB::table('topics')->get();
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
        $status = auth()->user()->status;
        if ($status == 1) {
            try {
                $validator = Validator::make($request->all(), [
                    'name' => ['bail', 'required', 'string', 'max:250', 'unique:topics,name'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'El titulo solicitado no cumple con el formato',
                        'complete' => false,
                    ]);
                } else {
                    if (DB::table("topics")
                        ->insert([
                            'name' => $request->input('name'),
                        ])
                    ) {
                        return response()->json([
                            'message' => 'Tema creado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ah ocurrido un error al momento de crear el tema',
                            'complete' => false,
                        ]);
                    }
                }
            } catch (Exception $ex) {
                return response()->json([
                    // 'message' => $ex->getMessage(),
                    'message' => "Ah ocurrido un error en la aplicación",
                    'complete' => false,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'El usuario actual esta desactivado',
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
        $subject = DB::table("topics")->where("id", $id)->first();
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
        $status = auth()->user()->status;
        if ($status == 1) {
            try {
                $data = DB::table("topics")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'name' => ['bail', 'required', 'string', 'max:250', 'unique:topics,name,' . $data->id],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'El titulo solicitado no cumple con el formato',
                            'complete' => false,
                        ]);
                    } else {
                        if (DB::update("UPDATE topics SET name = ? WHERE id = ?", [
                            $request->input('name'),
                            $data->id,
                        ])) {
                            return response()->json([
                                'message' => 'Tema modificado exitosamente',
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
                                    'message' => 'Ah ocurrido un error al momento de modificar el curso',
                                    'complete' => false,
                                ]);
                            }
                        }
                    }
                }
            } catch (Exception $ex) {
                return response()->json([
                    // 'message' => $ex->getMessage(),
                    'message' => "Ah ocurrido un error en la aplicación",
                    'complete' => false,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'El usuario actual esta desactivado',
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
        $status = auth()->user()->status;
        if ($status == 1) {
            try {
                $data = DB::table("topics")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    if (DB::table("topics")->delete($data->id)) {
                        return response()->json([
                            'message' => 'Tema eliminado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ah ocurrido un error al momento de eliminar el tema',
                            'complete' => true,
                        ]);
                    }
                }
            } catch (Exception $ex) {
                return response()->json([
                    // 'message' => $ex->getMessage(),
                    'message' => "Ah ocurrido un error en la aplicación",
                    'complete' => false,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'El usuario actual esta desactivado',
                'complete' => false,
            ]);
        }
    }
}
