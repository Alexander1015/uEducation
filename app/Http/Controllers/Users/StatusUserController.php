<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class StatusUserController extends Controller
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
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("users")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El usuario seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'status' => ['bail', 'required', 'in:0,1'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Ha ocurrido un error con el envío de información',
                            'complete' => false,
                        ]);
                    } else {
                        if (DB::update("UPDATE users SET status = ? WHERE id = ?", [
                            $request->input('status'),
                            $data->id,
                        ])) {
                            $message = "";
                            if ($request->input('status') == 0) $message = "Se ha deshabilitado al usuario exitosamente";
                            else if ($request->input('status') == 1) $message = "Se ha habilitado al usuario exitosamente";
                            return response()->json([
                                'message' => $message,
                                'complete' => true,
                            ]);
                        } else {
                            if ($data->status == $request->input("status")) {
                                return response()->json([
                                    'message' => 'El estado seleccionado no modifica al usuario, asi que no se ha actualizado',
                                    'complete' => false,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ha ocurrido un error al momento de cambiar el estado del usuario',
                                    'complete' => false,
                                ]);
                            }
                        }
                    }
                }
            } else {
                return response()->json([
                    'message' => 'El usuario actual esta deshabilitado',
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
