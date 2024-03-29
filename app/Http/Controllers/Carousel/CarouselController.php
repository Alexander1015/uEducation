<?php

namespace App\Http\Controllers\Carousel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Exception;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $carousel = DB::table('carousel')->orderBy('sequence', 'asc')->orderBy('id', 'asc')->get();
                $size = 1;
                foreach ($carousel as $data) {
                    $data->key = $size;
                    $size++;
                }
                return response()->json($carousel);
            } else return response()->json([]);
        } catch (Exception $ex) {
            return response()->json([]);
        }
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
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $validator = Validator::make($request->all(), [
                    'img' => ['bail', 'required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                ]);
                if ($validator->fails()) {
                    if ($request->file('img')) {
                        return response()->json([
                            'message' => 'No se ha proporcionado una imágen',
                            'complete' => false,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'La imágen proporcionada no sigue el formato solicitado',
                            'complete' => false,
                        ]);
                    }
                } else {
                    $time_img = time();
                    /*
                    * Si da error 500 para guardar la imagen, se debe cambiar el archivo php.ini del servidor
                    * y cambiar la linea: ;extension=gd a: extension=gd
                    * o: ;extension=gd2 a: extension=gd2
                    */
                    // Obtenemos el ultimo valor de la secuencia
                    $new_sequence = 1;
                    $before_sequence = DB::table("carousel")->orderBy('sequence', 'desc')->first();
                    if ($before_sequence && (int) $before_sequence->sequence) {
                        // Convertimos a entero
                        $new_sequence +=  (int) $before_sequence->sequence;
                    }
                    if (DB::table("carousel")
                        ->insert([
                            'img' => $time_img,
                            'sequence' => $new_sequence,
                        ])
                    ) {
                        if ($request->file('img')) {
                            // Creamos la carpeta que contendra las imagenes del carousel
                            $directory = public_path('img/carousel') . "/" . $time_img;
                            if (!File::isDirectory($directory)) {
                                mkdir($directory, 0777, true);
                            }
                            $img = $request->file('img');
                            $size = Image::make($img->getRealPath())->width();
                            //lazy
                            $img_l = Image::make($img->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_l->save($directory . '/lazy.png', 100);
                            //original
                            $img_o = Image::make($img->getRealPath())->resize($size, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_o->save($directory . '/index.png', 100);
                        }
                        return response()->json([
                            'message' => 'Imágen cargada',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'No se ha cargado la imágen al servidor',
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
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $data = DB::table("carousel")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La imágen seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    if (DB::table("carousel")->delete($data->id)) {
                        if ($data->img) {
                            // Eliminamos las imagenes del carousel
                            $directory = public_path('img/carousel') . "/" . $data->img;
                            if (File::isDirectory($directory)) {
                                $imgs = File::allFiles($directory);
                                if (sizeof($imgs) > 0) {
                                    foreach ($imgs as $item) {
                                        File::delete($directory . '/' . $item->getRelativePathname());
                                    }
                                }
                                rmdir($directory);
                            }
                        }
                        return response()->json([
                            'message' => 'imágen eliminada exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ha ocurrido un error al momento de eliminar la imágen',
                            'complete' => true,
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
