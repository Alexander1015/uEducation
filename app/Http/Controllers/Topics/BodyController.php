<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use DateTime;
use DateTimeZone;
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
    public function update(Request $request, $slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $data = DB::table("topics")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $data->subject_id)->first();
                    if ($auth_user->type == "0" || $review) {
                        $query = null;
                        $zone = new DateTimeZone('America/El_Salvador');
                        $now = new DateTime();
                        $now->setTimezone($zone);
                        // Obtenemos el body 
                        $file = public_path('data') . "/" . $data->body . "/body.html";
                        if (!file_exists($file)) {
                            $path = public_path('data');
                            if (!File::isDirectory($path)) {
                                mkdir($path, 0777, true);
                            }
                            $path .= "/" . $data->body;
                            if (!File::isDirectory($path)) {
                                mkdir($path, 0777, true);
                            }
                            $path .= "/body.html";
                            fopen($path, "w");
                        }
                        if (file_get_contents($file) != $request->input('body')) {
                            // Adjuntamos los datos al archivo
                            $handle = fopen($file, "w+");
                            fwrite($handle, $request->input('body'));
                            fclose($handle);
                            if (!$data->user_id) {
                                $query = DB::update("UPDATE topics SET user_id = ?, user_update_id = ?, created_at = ?, updated_at = ? WHERE id = ?", [
                                    $auth_user->id,
                                    null,
                                    $now->format("Y-m-d h:i:s"),
                                    null,
                                    $data->id,
                                ]);
                            } else {
                                $query = DB::update("UPDATE topics SET user_update_id = ?, updated_at = ? WHERE id = ?", [
                                    $auth_user->id,
                                    $now->format("Y-m-d h:i:s"),
                                    $data->id,
                                ]);
                            }
                            if ($query) {
                                // Eliminamos de la BD las imagenes que se eliminaron
                                $images_db = DB::table("images")->where("topic_id", $data->id)->get();
                                foreach ($images_db as $db) {
                                    $txtimg = "src=\"/data/" . $data->body . "/img/" . $db->image . "\"";
                                    if (!Str::contains($request->input('body'), $txtimg)) {
                                        DB::table("images")->delete($db->id);
                                    }
                                }
                                $directory = public_path('/data') . "/" . $data->body . "/img";
                                $files = array();
                                if (File::isDirectory($directory)) {
                                    // Obtenemos todos los datos
                                    $images_db = DB::table("images")->where("topic_id", $data->id)->get();
                                    $filedir = File::allFiles($directory);
                                    for ($a = 0; $a < sizeof($filedir); $a++) {
                                        array_push($files, $filedir[$a]->getRelativePathname());
                                    }
                                    //Eliminamos registros si no existen en la carpeta
                                    for ($x = 0; $x < sizeof($images_db); $x++) {
                                        $exist = false;
                                        for ($y = 0; $y < sizeof($files); $y++) {
                                            if ($images_db[$x]->image === $files[$y]) {
                                                $exist = true;
                                                break;
                                            }
                                        }
                                        if (!$exist) {
                                            DB::table("images")->delete($images_db[$x]->id);
                                        }
                                    }
                                    //Eliminamos archivos si no existen en la bd
                                    for ($y = 0; $y < sizeof($files); $y++) {
                                        $exist = false;
                                        for ($x = 0; $x < sizeof($images_db); $x++) {
                                            if ($images_db[$x]->image == $files[$y]) {
                                                $exist = true;
                                                break;
                                            }
                                        }
                                        if (!$exist) {
                                            File::delete($directory . '/' . $files[$y]);
                                        }
                                    }
                                }
                                if (trim($request->input('body')) == "" && $data->status == 1) {
                                    $query = DB::update("UPDATE topics SET status = ? WHERE id = ?", [
                                        0,
                                        $data->id,
                                    ]);
                                    return response()->json([
                                        'message' => 'Contenido guardado exitosamente, pero cambiado a borrador por falta de información',
                                        'complete' => true,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Contenido guardado exitosamente',
                                        'complete' => true,
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    'message' => 'Ha ocurrido un error al momento de modificar el tema',
                                    'complete' => false,
                                ]);
                            }
                        } else {
                            return response()->json([
                                'message' => 'La información proporcionada no modifica el contenido, asi que no se ha actualizado',
                                'complete' => false,
                            ]);
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
