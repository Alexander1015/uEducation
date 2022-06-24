<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;

class BodyController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("topics")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    if (DB::update("UPDATE topics SET body = ?, user_update_id = ? WHERE id = ?", [
                        $request->input('body') ? $request->input('body') : "",
                        $auth_user->id,
                        $data->id,
                    ])) {
                        // Eliminamos de la BD las imagenes que se eliminaron
                        $images_db = DB::table("images")->where("topic_id", $id)->get();
                        foreach ($images_db as $db) {
                            $data = "<img src=\"/img/topics/" . $id . "/" . $db->image . "\">";
                            if (!Str::contains($request->input('body'), $data)) {
                                DB::table("images")->delete($db->id);
                            }
                        }
                        $directory = public_path('/img/topics') . "/" . $id;
                        $files = array();
                        if (File::isDirectory($directory)) {
                            // Obtenemos todos los datos
                            $images_db = DB::table("images")->where("topic_id", $id)->get();
                            $data = File::allFiles($directory);
                            if (sizeof($data) > 0) {
                                foreach ($data as $item) {
                                    array_push($files, $item->getRelativePathname());
                                }
                            }
                            //Eliminamos registros si no existen en la carpeta
                            if (sizeof($images_db) > 0) {
                                foreach ($images_db as $db) {
                                    $exist = false;
                                    if (sizeof($files) > 0) {
                                        foreach ($files as $dir) {
                                            if ($db->image == $dir) {
                                                $exist = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (!$exist) {
                                        DB::table("images")->delete($db->id);
                                    }
                                }
                            }
                            if (sizeof($files) > 0) {
                                // Eliminamos archivos si no existen en la BD
                                foreach ($files as $dir) {
                                    $exist = false;
                                    if (sizeof($images_db) > 0) {
                                        foreach ($images_db as $db) {
                                            if ($db->image == $dir) {
                                                $exist = true;
                                                break;
                                            }
                                        }
                                    }
                                    if (!$exist) {
                                        File::delete($directory . '/' . $dir);
                                    }
                                }
                            }
                        }
                        return response()->json([
                            'message' => 'Contenido guardado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        if (
                            $data->body == $request->input('body')
                        ) {
                            return response()->json([
                                'message' => 'La información proporcionada no modifica el contenido, asi que no se ha actualizado',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ha ocurrido un error al momento de modificar el tema',
                                'complete' => false,
                            ]);
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
