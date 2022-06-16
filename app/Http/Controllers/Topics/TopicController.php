<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
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
        $topics = DB::select(
            'SELECT
                T.id, T.name, T.img, T.abstract, T.body, T.status, T.sequence, U.user AS user, S.name AS subject, T.created_at, T.updated_at
            FROM 
                topics AS T
            INNER JOIN subjects AS S ON T.subject_id = S.id
            INNER JOIN users AS U ON T.user_id = U.id'
        );
        return response()->json($topics);
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
                    'subject' => ['bail', 'required', 'string', 'max:100'],
                    'name' => ['bail', 'required', 'string', 'max:100', 'unique:topics,name'],
                    'abstract' => ['bail', 'sometimes', 'string', 'max:250'],
                    'img' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                ]);
                if ($validator->fails()) {
                    $old_name = DB::table("topics")->where('name', $request->input('name'))->first();
                    if ($old_name) {
                        return response()->json([
                            'message' => 'El título ingresado ya existe',
                            'complete' => false,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    }
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
                                $img_l = Image::make($img->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                                $img_l->save($address_l . '/' . $new_img, 100);
                                //original
                                $address_o = public_path('/img/topics');
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
                                'message' => 'Ha ocurrido un error al momento de crear el tema',
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
        $topic = DB::table("topics")->where("id", $id)->first();
        return response()->json($topic);
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
                $data = DB::table("topics")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'subject' => ['bail', 'required', 'string', 'max:100'],
                        'name' => ['bail', 'required', 'string', 'max:100', 'unique:topics,name,' . $data->id],
                        'abstract' => ['bail', 'sometimes', 'string', 'max:250'],
                        'img' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                        'img_new' => ['bail', 'sometimes', 'boolean'],
                    ]);
                    if ($validator->fails()) {
                        $old_name = DB::table("topics")->where('name', $request->input('name'))->first();
                        if ($data->id != $old_name->id) {
                            return response()->json([
                                'message' => 'El título ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
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
                                    $img_l = Image::make($img->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img_l->save($address_l . '/' . $new_img, 100);
                                    //original
                                    $address_o = public_path('/img/topics');
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
                                        'message' => 'La información proporcionada no modifica el tema, asi que no se ha actualizado',
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
                            'message' => 'Ha ocurrido un error al momento de eliminar el tema',
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
