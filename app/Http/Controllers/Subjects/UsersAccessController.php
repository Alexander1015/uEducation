<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class UsersAccessController extends Controller
{
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
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $data_subject = DB::table("subjects")->where("slug", $slug)->first();
                if (!$data_subject) {
                    return response()->json([
                        'message' => "La materia seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'user' => ['bail', 'required', 'string'],
                        'type' => ['bail', 'required', 'in:0,1'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    } else {
                        $data_user = DB::table("users")->where("type", "1")->where("slug", $request->input("user"))->first();
                        if (!$data_user) {
                            return response()->json([
                                'message' => "El usuario seleccionado no existe",
                                'complete' => false,
                            ]);
                        } else {
                            if (DB::update("UPDATE user_subject SET type = ? WHERE user_id = ? AND subject_id = ?", [
                                $request->input('type'),
                                $data_user->id,
                                $data_subject->id,
                            ])) {
                                $message = "";
                                if ($request->input('type') == 0) $message = "Se ha deshabilitado el acceso coordinador al usuario seleccionado";
                                else if ($request->input('type') == 1) $message = "Se ha habilitado el acceso coordinador al usuario seleccionado";
                                return response()->json([
                                    'message' => $message,
                                    'complete' => true,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ha ocurrido un error al momento de cambiar el acceso del usuario',
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
}
