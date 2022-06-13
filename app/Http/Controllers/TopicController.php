<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
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
                    'subject' => ['bail', 'required', 'string', 'max:250'],
                    'name' => ['bail', 'required', 'string', 'max:250', 'unique:topics,name'],
                    'abstract' => ['bail', 'sometimes', 'string', 'max:300'],
                    'img' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'El titulo solicitado no cumple con el formato',
                        'complete' => false,
                    ]);
                } else {
                    $exist_subject = DB::table("subjects")->where("name", $request->input('subject'),)->first();
                    if (!$exist_subject) {
                        return response()->json([
                            'message' => 'El curso seleccionado para el tema no existe',
                            'complete' => false,
                        ]);
                    } else {
                        /*
                        * Si da error 500 para guardar la imagen, se debe cambiar el archivo php.ini del servidor
                        * y cambiar la linea: ;extension=gd a: extension=gd
                        * o: ;extension=gd2 a: extension=gd2
                        */
                        $new_img = "";
                        if ($request->file('img')) {
                            //Direccion de la imagen
                            $new_img = time() . '.' . $request->file('img')->getClientOriginalExtension();
                        }
                        if (DB::table("topics")
                            ->insert([
                                'subject_id' => $exist_subject->id,
                                'name' => $request->input('name'),
                                'abstract' => $request->input('abstract'),
                                'img' => $new_img,
                                'user_id' => auth()->user()->id,
                                'body' => "",
                                'status' => "0",
                            ])
                        ) {
                            if ($request->file('img')) {
                                $img = $request->file('img');
                                $size = Image::make($img->getRealPath())->width();
                                //lazy
                                $address_l = public_path('/img/lazy_topics');
                                if (!File::isDirectory($address_l)) {
                                    File::makeDirectory($address_l, 0777, true, true);
                                }
                                $img_l = Image::make($img->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                                $img_l->save($address_l . '/' . $new_img, 100);
                                //original
                                $address_o = public_path('/img/topics');
                                if (!File::isDirectory($address_o)) {
                                    File::makeDirectory($address_o, 0777, true, true);
                                }
                                $img_o = Image::make($img->getRealPath())->resize($size, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                                $img_o->save($address_o . '/' . $new_img, 100);
                            }
                            $data = DB::table("topics")->where("name", $request->input('name'))->first();
                            return response()->json([
                                'message' => 'Tema creado exitosamente en modo borrador',
                                'topic' => $data->id,
                                'complete' => true,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ah ocurrido un error al momento de crear el tema',
                                'complete' => false,
                            ]);
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
                        'subject' => ['bail', 'required', 'string', 'max:250'],
                        'name' => ['bail', 'required', 'string', 'max:250', 'unique:topics,name,' . $data->id],
                        'abstract' => ['bail', 'sometimes', 'string', 'max:300'],
                        'img' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                        'img_new' => ['bail', 'sometimes', 'boolean'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'El titulo solicitado no cumple con el formato',
                            'complete' => false,
                        ]);
                    } else {
                        $exist_subject = DB::table("subjects")->where("name", $request->input('subject'),)->first();
                        if (!$exist_subject) {
                            return response()->json([
                                'message' => 'El curso seleccionado para el tema no existe',
                                'complete' => false,
                            ]);
                        } else {
                            $new_img = "";
                            //Direccion de la imagen
                            if (!$request->file('img')) {
                                if (!$request->input('img_new')) {
                                    $new_img = $data->img;
                                }
                            } else {
                                $new_img = time() . '.' . $request->file('img')->getClientOriginalExtension();
                            }
                            if (DB::update("UPDATE topics SET subject_id = ?, name = ?, abstract = ?, img = ? WHERE id = ?", [
                                $exist_subject->id,
                                $request->input('name'),
                                $request->input('abstract'),
                                $new_img,
                                $data->id,
                            ])) {
                                // Verificamos si no se ha eliminado el img que ya tenia el usuario
                                if (!$request->file('img')) {
                                    if ($request->input('img_new') && $data->img) {
                                        File::delete(public_path('/img/topics') . '/' . $data->img);
                                        File::delete(public_path('/img/lazy_topics/') . '/' . $data->img);
                                    }
                                } else {
                                    if ($data->img) {
                                        File::delete(public_path('/img/topics') . '/' . $data->img);
                                        File::delete(public_path('/img/lazy_topics/') . '/' . $data->img);
                                    }
                                    $img = $request->file('img');
                                    $size = Image::make($img->getRealPath())->width();
                                    //lazy
                                    $address_l = public_path('/img/lazy_topics');
                                    if (!File::isDirectory($address_l)) {
                                        File::makeDirectory($address_l, 0777, true, true);
                                    }
                                    $img_l = Image::make($img->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img_l->save($address_l . '/' . $new_img, 100);
                                    //original
                                    $address_o = public_path('/img/topics');
                                    if (!File::isDirectory($address_o)) {
                                        File::makeDirectory($address_o, 0777, true, true);
                                    }
                                    $img_o = Image::make($img->getRealPath())->resize($size, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img_o->save($address_o . '/' . $new_img, 100);
                                }
                                return response()->json([
                                    'message' => 'Tema modificado exitosamente',
                                    'complete' => true,
                                ]);
                            } else {
                                if (
                                    $data->subject_id == $exist_subject->id &&
                                    $data->name == $request->input('name') &&
                                    $data->abstract == $request->input('abstract') &&
                                    $data->img == $request->input('img')
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
                }
            } catch (Exception $ex) {
                return response()->json([
                    'message' => $ex->getMessage(),
                    // 'message' => "Ah ocurrido un error en la aplicación",
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
                        if ($data->img) {
                            File::delete(public_path('/img/topics') . '/' . $data->img);
                            File::delete(public_path('/img/lazy_topics/') . '/' . $data->img);
                        }
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
