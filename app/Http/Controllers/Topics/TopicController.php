<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
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
        $topics = DB::select(
            'SELECT
                T.id, T.name, T.slug, T.img, T.status, T.sequence, Uc.user AS user, Uu.user AS user_update, S.name AS subject, T.created_at, T.updated_at
            FROM 
                topics AS T
            LEFT JOIN subjects AS S ON T.subject_id = S.id
            LEFT JOIN users AS Uc ON T.user_id = Uc.id
            LEFT JOIN users AS Uu ON T.user_update_id = Uu.id
            ORDER BY T.name ASC'
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
                        $exist_subject = DB::table("subjects")->where("name", $request->input('subject'))->first();
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
                            $slug = Str::slug($request->input('name'));
                            $new_sequence = 1;
                            $before_sequence = DB::table("topics")->where('subject_id', $exist_subject->id)->orderBy('sequence', 'desc')->first();
                            if ($before_sequence && (int) $before_sequence->sequence) {
                                // Convertimos a entero
                                $new_sequence +=  (int) $before_sequence->sequence;
                            }
                            if (DB::table("topics")
                                ->insert([
                                    'subject_id' => $exist_subject->id,
                                    'name' => $request->input('name'),
                                    'slug' => $slug,
                                    'abstract' => $request->input('abstract') ? $request->input('abstract') : "",
                                    'img' => $new_img,
                                    'user_id' => auth()->user()->id,
                                    'user_update_id' => null,
                                    'body' => "",
                                    'sequence' => $new_sequence,
                                    'created_at' => now(),
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
                                    'topic' => $data->slug,
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
    public function show($slug)
    {
        // Topic
        $topic = DB::table("topics")->where("slug", $slug)->first();
        // Subject
        $subject = DB::table("subjects")->where("id", $topic->subject_id)->first();
        // Tags
        $tags_ids = DB::table("topic_tag")->where("topic_id", $topic->id)->get();
        $tags = array();
        if (sizeof($tags_ids) > 0) {
            foreach ($tags_ids as $tag) {
                $data = DB::table("tags")->where("id", $tag->tag_id)->first();
                array_push($tags, $data->name);
            }
        }
        // Liberar imagenes sin usar
        $directory = public_path('/img/topics') . "/" . $topic->id;
        $files = array();
        if (File::isDirectory($directory)) {
            // Obtenemos todos los datos
            $images_db = DB::table("images")->where("topic_id", $topic->id)->get();
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
        return response()->json([
            'topic' => $topic,
            'subject' => $subject,
            'tags' => $tags,
        ]);
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
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("topics")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
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
                            $exist_subject = DB::table("subjects")->where("name", $request->input('subject'))->first();
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
                                $slug = Str::slug($request->input('name'));
                                if (DB::update("UPDATE topics SET slug = ?, subject_id = ?, name = ?, abstract = ?, img = ?, user_id = ? WHERE id = ?", [
                                    $slug,
                                    $exist_subject->id,
                                    $request->input('name'),
                                    $request->input('abstract') ? $request->input('abstract') : "",
                                    $new_img,
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
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("topics")->where("id", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El tema seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $topic_tag = DB::table("topic_tag")->where("topic_id", $data->id)->get();
                    if (sizeof($topic_tag) > 0) {
                        DB::table("topic_tag")->where("topic_id", $topic_tag->topic_id)->delete();
                    }
                    // Eliminamos las imagenes del body
                    $directory = public_path('/img/topics') . "/" . $data->id;
                    if (File::isDirectory($directory)) {
                        // Tabla Images
                        DB::table("images")->where("topic_id", $data->id)->delete();
                        // Carpeta en topics
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
