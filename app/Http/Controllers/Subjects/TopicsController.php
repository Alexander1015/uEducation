<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class TopicsController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("subjects")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La materia seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'topics' => ['bail', 'required'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    } else {
                        if (!is_array($request->input('topics')) || sizeof($request->input('topics')) < 1) {
                            return response()->json([
                                'message' => 'La lista proporcionada no posee el formato solicitado',
                                'complete' => false,
                            ]);
                        } else {
                            $size = 1;
                            $fail = 0;
                            foreach ($request->input('topics') as $data) {
                                if (!DB::update("UPDATE topics SET sequence = ? WHERE id = ?", [
                                    $size,
                                    $data
                                ])) {
                                    $fail++;
                                }
                                $size++;
                            }
                            if ($fail == ($size - 1)) {
                                return response()->json([
                                    'message' => 'La información proporcionada no modifica el tema, asi que no se ha actualizado',
                                    'complete' => false,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Lista de la materia modificada exitosamente',
                                    'complete' => true,
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
                'message' => $ex->getMessage(),
                // 'message' => "Ha ocurrido un error en la aplicación",
                'complete' => false,
            ]);
        }
    }
}
