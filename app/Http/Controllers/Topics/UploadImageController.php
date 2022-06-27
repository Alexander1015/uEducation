<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class UploadImageController extends Controller
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
                $data = DB::table("topics")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema al cual adjunta la imagen no existe",
                        'complete' => false,
                    ]);
                } else {
                    if ($request->file('image')) {
                        //Direccion de la imagen
                        $new_img = time() . '.' . $request->file('image')->getClientOriginalExtension();
                        $img = $request->file('image');
                        $size = Image::make($img->getRealPath())->width();
                        //original
                        $address = public_path('/img/topics') . "/" . $data->id;
                        if (!File::isDirectory($address)) {
                            File::makeDirectory($address, 0777, true, true);
                        }
                        $img = Image::make($img->getRealPath())->resize($size, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $img->save($address . '/' . $new_img, 100);
                        DB::table("images")
                            ->insert([
                                'topic_id' => $data->id,
                                'image' => $new_img,
                            ]);
                        return response()->json([
                            'url' => '/img/topics/' . $data->id . '/' . $new_img,
                            'message' => "Se ha subido exitosamente la imagen al servidor.",
                        ]);
                    } else {
                        return response()->json([
                            'message' => "No se ha obtenido ninguna imagen",
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
                'message' => "Ha ocurrido un error al subir la imagen",
            ]);
        }
    }
}
