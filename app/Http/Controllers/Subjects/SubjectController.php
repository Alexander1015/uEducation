<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Exception;

class SubjectController extends Controller
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
                $subjects = DB::table('subjects')->orderBy('name', 'asc')->get();
                $data = array();
                foreach ($subjects as $item) {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $item->id)->first();
                    if ($auth_user->type == "0" || $review) {
                        $item->access = $review ? $review->type : "1";
                        array_push($data, $item);
                    }
                }
                return response()->json($data);
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
                if ($auth_user->type == "0") {
                    $validator = Validator::make($request->all(), [
                        'name' => ['bail', 'required', 'string', 'max:100', 'unique:subjects,name'],
                        'img' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                    ]);
                    if ($validator->fails()) {
                        $old_name = DB::table("subjects")->where('name', $request->input('name'))->first();
                        if ($old_name) {
                            return response()->json([
                                'message' => 'El titulo ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else {
                            $slug = Str::slug($request->input('name'));
                            $old_slug = DB::table("subjects")->where('slug', $slug)->first();
                            if ($old_slug) {
                                return response()->json([
                                    'message' => 'El titulo ingresado ya existe',
                                    'complete' => false,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'El dato proporcionado no sigue el formato solicitado',
                                    'complete' => false,
                                ]);
                            }
                        }
                    } else {
                        $time_img = "";
                        if ($request->file('img')) {
                            $time_img = time();
                        }
                        /*
                        * Si da error 500 para guardar la imagen, se debe cambiar el archivo php.ini del servidor
                        * y cambiar la linea: ;extension=gd a: extension=gd
                        * o: ;extension=gd2 a: extension=gd2
                        */
                        $slug = Str::slug($request->input('name'));
                        if (DB::table("subjects")
                            ->insert([
                                'name' => $request->input('name'),
                                'slug' => $slug,
                                'img' => $time_img,
                            ])
                        ) {
                            if ($request->file('img')) {
                                // Creamos la carpeta que contendra las imagenes del subject
                                $directory = public_path('img/subjects') . "/" . $time_img;
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
                                'message' => 'Curso creado exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            $old_slug = DB::table("subjects")->where('slug', $slug)->first();
                            if ($old_slug) {
                                return response()->json([
                                    'message' => 'El slug generado ya existe, genere uno nuevo',
                                    'complete' => false,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ha ocurrido un error al momento de crear la materia',
                                    'complete' => false,
                                ]);
                            }
                        }
                    }
                } else {
                    return response()->json([
                        'message' => 'El usuario actual no tiene los permisos necesarios',
                        'complete' => false,
                    ]);
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
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $subject = DB::table("subjects")->where("slug", $slug)->first();
                if ($subject) {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $subject->id)->first();
                    if ($auth_user->type == "0" || $review) {
                        $subject->access = $review ? $review->type : "1";
                        $topics = DB::select(
                            'SELECT
                            id, name
                        FROM 
                            topics
                        WHERE
                            subject_id = ?
                        ORDER BY sequence ASC, id ASC',
                            [
                                $subject->id,
                            ]
                        );
                        $size = 1;
                        foreach ($topics as $data) {
                            $data->key = $size;
                            $size++;
                        }
                        return response()->json([
                            'subject' => $subject,
                            'topics' => $topics,
                        ]);
                    } else {
                        return response()->json([]);
                    }
                } else {
                    return response()->json([]);
                }
            } else {
                return response()->json([]);
            }
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }

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
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $data = DB::table("subjects")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La materia seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $data->id)->first();
                    if ($auth_user->type == "0" || $review) {
                        $validator = Validator::make($request->all(), [
                            'name' => ['bail', 'required', 'string', 'max:100', 'unique:subjects,name,' . $data->id],
                            'img' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                            'img_new' => ['bail', 'sometimes', 'boolean'],
                        ]);
                        if ($validator->fails()) {
                            $old_name = DB::table("subjects")->where('name', $request->input('name'))->first();
                            if ($data->id != $old_name->id) {
                                return response()->json([
                                    'message' => 'El titulo ingresado ya existe',
                                    'complete' => false,
                                ]);
                            } else {
                                $slug = Str::slug($request->input('name'));
                                $old_slug = DB::table("subjects")->where('slug', $slug)->first();
                                if ($data->id != $old_slug->id) {
                                    return response()->json([
                                        'message' => 'El titulo ingresado ya existe',
                                        'complete' => false,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'El dato proporcionado no sigue el formato solicitado',
                                        'complete' => false,
                                    ]);
                                }
                            }
                        } else {
                            $time_img = "";
                            if (!$request->file('img')) {
                                if ($request->input('img_new') && trim($data->img) != "") {
                                    // Eliminamos las imagenes del user no usadas
                                    $directory = public_path('img/subjects') . "/" . $data->img;
                                    if (File::isDirectory($directory)) {
                                        $imgs = File::allFiles($directory);
                                        if (sizeof($imgs) > 0) {
                                            foreach ($imgs as $item) {
                                                File::delete($directory . '/' . $item->getRelativePathname());
                                            }
                                        }
                                        // Eliminamos la carpeta ya que no se usara
                                        rmdir($directory);
                                    }
                                } else $time_img = $data->img;
                            } else {
                                if (trim($data->img) != "") {
                                    // Eliminamos las imagenes del user antiguas
                                    $directory = public_path('img/subjects') . "/" . $data->img;
                                    if (File::isDirectory($directory)) {
                                        $imgs = File::allFiles($directory);
                                        if (sizeof($imgs) > 0) {
                                            foreach ($imgs as $item) {
                                                File::delete($directory . '/' . $item->getRelativePathname());
                                            }
                                        }
                                        // Eliminamos la carpeta ya que no se usara
                                        rmdir($directory);
                                    }
                                }
                                $time_img = time();
                            }
                            $slug = Str::slug($request->input('name'));
                            if (DB::update("UPDATE subjects SET slug = ?, name = ?, img = ? WHERE id = ?", [
                                $slug,
                                $request->input('name'),
                                $time_img,
                                $data->id,
                            ])) {
                                // Verificamos si no se ha eliminado el img que ya tenia el tema
                                if ($request->file('img')) {
                                    $directory = public_path('img/subjects') . "/" . $time_img;
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
                                if ($data->slug != $slug) {
                                    return response()->json([
                                        'message' => 'Curso modificado exitosamente',
                                        'complete' => true,
                                        'reload' => $slug,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Curso modificado exitosamente',
                                        'complete' => true,
                                    ]);
                                }
                            } else {
                                if (
                                    $data->name == $request->input('name')
                                ) {
                                    return response()->json([
                                        'message' => 'La información proporcionada no modifica la materia, asi que no se ha actualizado',
                                        'complete' => false,
                                    ]);
                                } else {
                                    $old_slug = DB::table("subjects")->where('slug', $slug)->first();
                                    if ($old_slug) {
                                        return response()->json([
                                            'message' => 'El slug generado ya existe, genere uno nuevo',
                                            'complete' => false,
                                        ]);
                                    } else {
                                        return response()->json([
                                            'message' => 'Ha ocurrido un error al momento de modificar la materia',
                                            'complete' => false,
                                        ]);
                                    }
                                }
                            }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $data = DB::table("subjects")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La materia seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $data->id)->first();
                    if ($auth_user->type == "0") {
                        // Sacamos los temas atribuidos a la materia
                        $topics = DB::table("topics")->where("subject_id", $data->id)->get();
                        if (sizeof($topics) > 0) {
                            DB::update("UPDATE topics SET subject_id = ? WHERE subject_id = ?", [
                                null,
                                $data->id,
                            ]);
                        }
                        // Eliminamos las relaciones de la materia con un usuario
                        $users_subject = DB::table("user_subject")->where("subject_id", $data->id)->get();
                        if (sizeof($users_subject) > 0) {
                            DB::table("user_subject")->where("subject_id", $data->id)->delete();
                        }
                        if (DB::table("subjects")->delete($data->id)) {
                            if ($data->img) {
                                // Eliminamos las imagenes del user
                                $directory = public_path('img/subjects') . "/" . $data->img;
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
                                'message' => 'Curso eliminado exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ha ocurrido un error al momento de eliminar la materia',
                                'complete' => true,
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
