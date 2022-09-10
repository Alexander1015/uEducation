<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $topics = DB::select(
                    'SELECT
                    T.id, T.name, T.slug, T.img, T.status, T.sequence, Uc.user AS user, Uu.user AS user_update, S.id AS subject_id, S.code AS subject, S.status AS subject_status, T.created_at, T.updated_at
                FROM 
                    topics AS T
                LEFT JOIN subjects AS S ON T.subject_id = S.id
                LEFT JOIN users AS Uc ON T.user_id = Uc.id
                LEFT JOIN users AS Uu ON T.user_update_id = Uu.id
                ORDER BY T.name ASC'
                );
                $data = array();
                foreach ($topics as $item) {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $item->subject_id)->first();
                    if ($auth_user->type == "0" || $review) {
                        if (!$item->subject) {
                            $item->subject = "";
                            $item->subject_id = "";
                        }
                        array_push($data, $item);
                    }
                }
                $subjects = array();
                $review = DB::table("user_subject")->where("user_id", $auth_user->id)->get();
                if ($auth_user->type == "0") {
                    $subjects = DB::table("subjects")->get();
                } else {
                    foreach ($review as $item) {
                        array_push($subjects, DB::table("subjects")->where("id", $item->subject_id)->first());
                    }
                }
                return response()->json([
                    'data' => $data,
                    'subjects' => $subjects
                ]);
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
                    'subject' => ['bail', 'required', 'string', 'max:100'],
                    'tags' => ['bail', 'required'],
                    'name' => ['bail', 'required', 'string', 'max:100', 'unique:topics,name'],
                    'abstract' => ['bail', 'nullable', 'string', 'max:250'],
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
                        $slug = Str::slug($request->input('name'));
                        $old_slug = DB::table("topics")->where('slug', $slug)->first();
                        if ($old_slug) {
                            return response()->json([
                                'message' => 'El titulo ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    }
                } else {
                    if (!is_array($request->input('tags')) || sizeof($request->input('tags')) < 1) {
                        return response()->json([
                            'message' => 'Las etiquetas proporcionadas no poseen el formato solicitado',
                            'complete' => false,
                        ]);
                    } else if (sizeof($request->input('tags')) > 5) {
                        return response()->json([
                            'message' => 'Solo se pueden seleccionar un máximo de 5 etiquetas',
                            'complete' => false,
                        ]);
                    } else {
                        $exist_subject = DB::table("subjects")->where("code", $request->input('subject'))->first();
                        if (!$exist_subject) {
                            return response()->json([
                                'message' => 'El curso seleccionado para el tema no existe',
                                'complete' => false,
                            ]);
                        } else {
                            $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $exist_subject->id)->first();
                            if ($auth_user->type == "0" || $review) {
                                $time_img = "";
                                if ($request->file('img')) {
                                    $time_img = time();
                                }
                                // Nombramos el directory del body
                                $body_data = time();
                                /*
                                * Si da error 500 para guardar la imagen, se debe cambiar el archivo php.ini del servidor
                                * y cambiar la linea: ;extension=gd a: extension=gd
                                * o: ;extension=gd2 a: extension=gd2
                                */
                                $slug = Str::slug($request->input('name'));
                                // Obtenemos el ultimo valor de la secuencia
                                $new_sequence = 1;
                                $before_sequence = DB::table("topics")->where('subject_id', $exist_subject->id)->orderBy('sequence', 'desc')->first();
                                if ($before_sequence && (int) $before_sequence->sequence) {
                                    // Convertimos a entero
                                    $new_sequence +=  (int) $before_sequence->sequence;
                                }
                                $zone = new DateTimeZone('America/El_Salvador');
                                $now = new DateTime();
                                $now->setTimezone($zone);
                                if (DB::table("topics")
                                    ->insert([
                                        'subject_id' => $exist_subject->id,
                                        'name' => $request->input('name'),
                                        'slug' => $slug,
                                        'abstract' => $request->input('abstract') ? $request->input('abstract') : "",
                                        'img' => $time_img,
                                        'body' => $body_data,
                                        'user_id' => auth()->user()->id,
                                        'user_update_id' => null,
                                        'sequence' => $new_sequence,
                                        'created_at' => $now->format("Y-m-d h:i:s"),
                                        'status' => "0",
                                    ])
                                ) {
                                    // Guardamos las etiquetas
                                    $new_id = DB::table("topics")
                                        ->where('name', $request->input('name'))
                                        ->where('user_id', auth()->user()->id)
                                        ->where('status', "0")
                                        ->first();
                                    foreach ($request->input('tags') as $tag) {
                                        $data = DB::table("tags")->where("name", $tag)->first();
                                        DB::table("topic_tag")->insert([
                                            'topic_id' => $new_id->id,
                                            'tag_id' => $data->id,
                                        ]);
                                    }
                                    if ($request->file('img')) {
                                        // Creamos la carpeta que contendra las imagenes del subject
                                        $directory = public_path('img/topics') . "/" . $time_img;
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
                                    // Creamos la carpeta que contendra la data del body
                                    $path = public_path('data');
                                    if (!File::isDirectory($path)) {
                                        mkdir($path, 0777, true);
                                    }
                                    $path .= "/" . $body_data;
                                    if (!File::isDirectory($path)) {
                                        mkdir($path, 0777, true);
                                    }
                                    // Creamos el archivo del body
                                    $path .= "/body.html";
                                    fopen($path, "w");
                                    return response()->json([
                                        'message' => 'Tema creado exitosamente en modo borrador',
                                        'topic' => $slug,
                                        'complete' => true,
                                    ]);
                                } else {
                                    $old_slug = DB::table("topics")->where('slug', $slug)->first();
                                    if ($old_slug) {
                                        return response()->json([
                                            'message' => 'El slug generado ya existe, genere uno nuevo',
                                            'complete' => false,
                                        ]);
                                    } else {
                                        return response()->json([
                                            'message' => 'Ha ocurrido un error al momento de crear el tema',
                                            'complete' => false,
                                        ]);
                                    }
                                }
                            } else {
                                return response()->json([
                                    'message' => 'El usuario actual no tiene los permisos necesarios',
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
                // Topic
                $topic = DB::table("topics")->where("slug", $slug)->first();
                $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $topic->subject_id)->first();
                if ($auth_user->type == "0" || $review) {
                    // Tags
                    $tags_ids = DB::select(
                        'SELECT
                            Ta.name
                        FROM 
                            topic_tag AS Tt
                        LEFT JOIN tags AS Ta ON Tt.tag_id = Ta.id
                        WHERE
                            Tt.topic_id = ?
                        ORDER BY Ta.name ASC',
                        [
                            $topic->id
                        ]
                    );
                    // Subject
                    $subject = DB::table("subjects")->where("id", $topic->subject_id)->first();
                    // Obtenemos el archivo que contiene el body
                    $file = public_path('data') . "/" . $topic->body . "/body.html";
                    if (!file_exists($file)) {
                        $path = public_path('data');
                        if (!File::isDirectory($path)) {
                            mkdir($path, 0777, true);
                        }
                        $path .= "/" . $topic->body;
                        if (!File::isDirectory($path)) {
                            mkdir($path, 0777, true);
                        }
                        $path .= "/body.html";
                        fopen($path, "w");
                    }
                    $topic->data = file_get_contents($file);
                    $tags = array();
                    for ($x = 0; $x < sizeof($tags_ids); $x++) {
                        array_push($tags, $tags_ids[$x]->name);
                    }
                    // Liberar imagenes sin usar
                    $directory = public_path('data') . "/" . $topic->body . "/img";
                    $files = array();
                    if (File::isDirectory($directory)) {
                        // Obtenemos todos los datos
                        $images_db = DB::table("images")->where("topic_id", $topic->id)->get();
                        foreach ($images_db as $db) {
                            $txtimg = "src=\"/data/" . $topic->body . "/img/" . $db->img . "\"";
                            if (!Str::contains($topic->data, $txtimg)) {
                                DB::table("images")->delete($db->id);
                            }
                        }
                        $filedir = File::allFiles($directory);
                        for ($a = 0; $a < sizeof($filedir); $a++) {
                            array_push($files, $filedir[$a]->getRelativePathname());
                        }
                        //Eliminamos registros si no existen en la carpeta
                        for ($x = 0; $x < sizeof($images_db); $x++) {
                            $exist = false;
                            for ($y = 0; $y < sizeof($files); $y++) {
                                if ($images_db[$x]->img === $files[$y]) {
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
                                if ($images_db[$x]->img == $files[$y]) {
                                    $exist = true;
                                    break;
                                }
                            }
                            if (!$exist) {
                                File::delete($directory . '/' . $files[$y]);
                            }
                        }
                    }
                    return response()->json([
                        'topic' => $topic,
                        'subject' => $subject,
                        'tags' => $tags,
                    ]);
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
                $data = DB::table("topics")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $data->subject_id)->first();
                    if ($auth_user->type == "0" || $review) {
                        $validator = Validator::make($request->all(), [
                            'subject' => ['bail', 'required', 'string', 'max:100'],
                            'tags' => ['bail', 'required'],
                            'name' => ['bail', 'required', 'string', 'max:100', 'unique:topics,name,' . $data->id],
                            'abstract' => ['bail', 'nullable', 'string', 'max:250'],
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
                                $slug = Str::slug($request->input('name'));
                                $old_slug = DB::table("topics")->where('slug', $slug)->first();
                                if ($data->id != $old_slug->id) {
                                    return response()->json([
                                        'message' => 'El titulo ingresado ya existe',
                                        'complete' => false,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                        'complete' => false,
                                    ]);
                                }
                            }
                        } else {
                            if (!is_array($request->input('tags')) || sizeof($request->input('tags')) < 1) {
                                return response()->json([
                                    'message' => 'Las etiquetas proporcionadas no poseen el formato solicitado',
                                    'complete' => false,
                                ]);
                            } else if (sizeof($request->input('tags')) > 5) {
                                return response()->json([
                                    'message' => 'Solo se pueden seleccionar un máximo de 5 etiquetas',
                                    'complete' => false,
                                ]);
                            } else {
                                $exist_subject = DB::table("subjects")->where("code", $request->input('subject'))->first();
                                if (!$exist_subject) {
                                    return response()->json([
                                        'message' => 'El curso seleccionado para el tema no existe',
                                        'complete' => false,
                                    ]);
                                } else {
                                    $time_img = "";
                                    if (!$request->file('img')) {
                                        if ($request->input('img_new') && trim($data->img) != "") {
                                            // Eliminamos las imagenes del topic no usadas
                                            $directory = public_path('img/topics') . "/" . $data->img;
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
                                            // Eliminamos las imagenes del topic antiguas
                                            $directory = public_path('img/topics') . "/" . $data->img;
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
                                    if (DB::update("UPDATE topics SET slug = ?, subject_id = ?, name = ?, abstract = ?, img = ?, user_id = ? WHERE id = ?", [
                                        $slug,
                                        $exist_subject->id,
                                        $request->input('name'),
                                        $request->input('abstract') ? $request->input('abstract') : "",
                                        $time_img,
                                        auth()->user()->id,
                                        $data->id,
                                    ])) {
                                        // Actualizamos las etiquetas
                                        $topic_tag = DB::table("topic_tag")->where("topic_id", $data->id)->get();
                                        if (sizeof($topic_tag) > 0) {
                                            foreach ($topic_tag as $item) {
                                                $exist = false;
                                                foreach ($request->input('tags') as $tag) {
                                                    $data_tag = DB::table("tags")->where("name", $tag)->first();
                                                    if ($item->tag_id == $data_tag->id) $exist = true;
                                                }
                                                if (!$exist) {
                                                    $modify = true;
                                                    DB::table("topic_tag")
                                                        ->where("topic_id", $item->topic_id)
                                                        ->where("tag_id", $item->tag_id)
                                                        ->delete();
                                                }
                                            }
                                        }
                                        foreach ($request->input('tags') as $tag) {
                                            $data_tag = DB::table("tags")->where("name", $tag)->first();
                                            if (!DB::table("topic_tag")
                                                ->where("topic_id", $data->id)
                                                ->where("tag_id", $data_tag->id)
                                                ->first()) {
                                                DB::table("topic_tag")->insert([
                                                    'topic_id' => $data->id,
                                                    'tag_id' => $data_tag->id,
                                                ]);
                                            }
                                        }
                                        // Verificamos si no se ha eliminado el img que ya tenia el tema
                                        if ($request->file('img')) {
                                            $directory = public_path('img/topics') . "/" . $time_img;
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
                                                'message' => 'Tema modificado exitosamente',
                                                'complete' => true,
                                                'reload' => $slug,
                                            ]);
                                        } else {
                                            return response()->json([
                                                'message' => 'Tema modificado exitosamente',
                                                'complete' => true,
                                            ]);
                                        }
                                    } else {
                                        if (
                                            $data->subject_id == $exist_subject->id &&
                                            $data->name == $request->input('name') &&
                                            $data->abstract == $request->input('abstract') &&
                                            $data->img == $request->input('img')
                                        ) {
                                            $modify = false;
                                            // Actualizamos las etiquetas
                                            $topic_tag = DB::table("topic_tag")->where("topic_id", $data->id)->get();
                                            if (sizeof($topic_tag) > 0) {
                                                foreach ($topic_tag as $item) {
                                                    $exist = false;
                                                    foreach ($request->input('tags') as $tag) {
                                                        $data_tag = DB::table("tags")->where("name", $tag)->first();
                                                        if ($item->tag_id == $data_tag->id) $exist = true;
                                                    }
                                                    if (!$exist) {
                                                        $modify = true;
                                                        DB::table("topic_tag")
                                                            ->where("topic_id", $item->topic_id)
                                                            ->where("tag_id", $item->tag_id)
                                                            ->delete();
                                                    }
                                                }
                                            }
                                            foreach ($request->input('tags') as $tag) {
                                                $data_tag = DB::table("tags")->where("name", $tag)->first();
                                                if (!DB::table("topic_tag")
                                                    ->where("topic_id", $data->id)
                                                    ->where("tag_id", $data_tag->id)
                                                    ->first()) {
                                                    $modify = true;
                                                    DB::table("topic_tag")->insert([
                                                        'topic_id' => $data->id,
                                                        'tag_id' => $data_tag->id,
                                                    ]);
                                                }
                                            }
                                            if ($modify) {
                                                return response()->json([
                                                    'message' => 'Tema modificado exitosamente',
                                                    'complete' => true,
                                                ]);
                                            } else {
                                                return response()->json([
                                                    'message' => 'La información proporcionada no modifica el tema, asi que no se ha actualizado',
                                                    'complete' => false,
                                                ]);
                                            }
                                        } else {
                                            $old_slug = DB::table("topics")->where('slug', $slug)->first();
                                            if ($old_slug) {
                                                return response()->json([
                                                    'message' => 'El slug generado ya existe, genere uno nuevo',
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
                'message' => $ex->getMessage(),
                // 'message' => "Ha ocurrido un error en la aplicación",
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
                        $topic_tag = DB::table("topic_tag")->where("topic_id", $data->id)->get();
                        if (sizeof($topic_tag) > 0) {
                            DB::table("topic_tag")->where("topic_id", $data->id)->delete();
                        }
                        // Eliminamos las imagenes del body
                        DB::table("images")->where("topic_id", $data->id)->delete(); // Tabla Images
                        $directory = public_path('/img/data') . "/" . $data->body . "/img";
                        if (File::isDirectory($directory)) {
                            // Carpeta de img
                            $data = File::allFiles($directory);
                            if (sizeof($data) > 0) {
                                foreach ($data as $item) {
                                    File::delete($directory . '/' . $item->getRelativePathname());
                                }
                            }
                            rmdir($directory);
                        }
                        $directory = public_path('/img/data') . "/" . $data->body;
                        if (File::isDirectory($directory)) {
                            // Carpeta del body
                            $data = File::allFiles($directory);
                            if (sizeof($data) > 0) {
                                foreach ($data as $item) {
                                    File::delete($directory . '/' . $item->getRelativePathname());
                                }
                            }
                            rmdir($directory);
                        }
                        if (DB::table("topics")->delete($data->id)) {
                            if ($data->img) {
                                // Eliminamos las imagenes del topic
                                $directory = public_path('img/topics') . "/" . $data->img;
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
                                'message' => 'Tema eliminado exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ha ocurrido un error al momento de eliminar el tema',
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
