<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Exception;

class UserController extends Controller
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
                $users = DB::table('users')->where("type", "0")->orWhere("type", "1")->orderBy('user', 'asc')->get();
                return response()->json($users);
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
                    'firstname' => ['bail', 'required', 'string', 'max:50'],
                    'lastname' => ['bail', 'required', 'string', 'max:50'],
                    'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user'],
                    'email' => ['bail', 'required', 'email', 'max:100', 'unique:users,email'],
                    'password' => ['bail', 'required', 'string', 'min:8', 'max:50', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()],
                    'type' => ['bail', 'sometimes', 'in:0,1'],
                    'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                ]);
                if ($validator->fails()) {
                    $old_user = DB::table("users")->where('user', $request->input('user'))->first();
                    $old_email = DB::table("users")->where('email', $request->input('email'))->first();
                    if ($old_user) {
                        return response()->json([
                            'message' => 'El usuario ingresado ya existe',
                            'complete' => false,
                        ]);
                    } else if ($old_email) {
                        return response()->json([
                            'message' => 'El correo electrónico ingresado ya existe',
                            'complete' => false,
                        ]);
                    } else if ($request->input('password') != $request->input('password_confirmation')) {
                        return response()->json([
                            'message' => 'Las contraseñas ingresadas no son iguales',
                            'complete' => false,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    }
                } else if ($auth_user->type == "0" && trim($request->input("type")) == "") {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    $time_avatar = "";
                    if ($request->file('avatar')) {
                        $time_avatar = time();
                    }
                    /*
                    * Si da error 500 para guardar la imagen, se debe cambiar el archivo php.ini del servidor
                    * y cambiar la linea: ;extension=gd a: extension=gd
                    * o: ;extension=gd2 a: extension=gd2
                    */
                    $slug = Str::slug(Str::random(20));
                    while (DB::table("users")->where('slug', $slug)->first()) {
                        $slug = Str::random(20);
                    }
                    $user_type = "1";
                    if ($auth_user->type == "0") {
                        $user_type = (string) $request->input("type");
                    }
                    if (DB::table("users")
                        ->insert([
                            'slug' => $slug,
                            'firstname' => $request->input('firstname'),
                            'lastname' => $request->input('lastname'),
                            'user' => strtoupper($request->input('user')),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                            'type' => $user_type,
                            'avatar' => $time_avatar,
                        ])
                    ) {
                        if ($request->file('avatar')) {
                            // Creamos la carpeta que contendra las imagenes del usuario
                            $directory = public_path('img/users') . "/" . $time_avatar;
                            if (!File::isDirectory($directory)) {
                                mkdir($directory, 0777, true);
                            }
                            $avatar = $request->file('avatar');
                            $size = Image::make($avatar->getRealPath())->width();
                            //lazy
                            $img_l = Image::make($avatar->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_l->save($directory . '/lazy.png', 100);
                            //original
                            $img_o = Image::make($avatar->getRealPath())->resize($size, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_o->save($directory . '/index.png', 100);
                        }
                        return response()->json([
                            'message' => 'Usuario creado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ha ocurrido un error al momento de crear el usuario',
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $user = DB::table("users")->where("slug", $slug)->where("type", "0")->orWhere("type", "1")->first();
                return response()->json($user);
            } else  return response()->json([]);
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }

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
                $data = DB::table("users")->where("slug", $slug)->where('type', "0")->orWhere('type', '1')->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El usuario seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'firstname' => ['bail', 'required', 'string', 'max:50'],
                        'lastname' => ['bail', 'required', 'string', 'max:50'],
                        'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user,' . $data->id],
                        'email' => ['bail', 'required', 'email', 'max:100', 'unique:users,email,' . $data->id],
                        'type' => ['bail', 'sometimes', 'in:0,1,2'],
                        'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                        'avatar_new' => ['bail', 'sometimes', 'boolean'],
                    ]);
                    if ($validator->fails()) {
                        $old_user = DB::table("users")->where('user', $request->input('user'))->first();
                        $old_email = DB::table("users")->where('email', $request->input('email'))->first();
                        if ($data->id != $old_user->id) {
                            return response()->json([
                                'message' => 'El usuario ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else if ($data->email != $old_email->id) {
                            return response()->json([
                                'message' => 'El correo electrónico ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    } else if ($auth_user->type == "0" && trim($request->input("type")) == "") {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    } else {
                        if ($auth_user->id == $data->id) {
                            return response()->json([
                                'message' => "Debe modificar su información en el perfil de su usuario",
                                'complete' => false,
                            ]);
                        } else {
                            // Obtenemos el tipo de usuario que estamos editando
                            $type = (string) $data->type;
                            if ($auth_user->type == "0") {
                                $type = (string) $request->input("type");
                            }
                            $time_avatar = "";
                            if (!$request->file('avatar')) {
                                if ($request->input('avatar_new') && trim($data->avatar) != "") {
                                    // Eliminamos las imagenes del user no usadas
                                    $directory = public_path('img/users') . "/" . $data->avatar;
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
                                } else $time_avatar = $data->avatar;
                            } else {
                                if (trim($data->avatar) != "") {
                                    // Eliminamos las imagenes del user antiguas
                                    $directory = public_path('img/users') . "/" . $data->avatar;
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
                                $time_avatar = time();
                            }
                            if (DB::update("UPDATE users SET firstname = ?, lastname = ?, user = ?, email = ?, type = ?, avatar = ? WHERE id = ?", [
                                $request->input('firstname'),
                                $request->input('lastname'),
                                strtoupper($request->input('user')),
                                $request->input('email'),
                                $type,
                                $time_avatar,
                                $data->id,
                            ])) {
                                // Verificamos si no se ha eliminado el avatar que ya tenia el usuario
                                if ($request->file('avatar')) {
                                    $directory = public_path('img/users') . "/" . $time_avatar;
                                    if (!File::isDirectory($directory)) {
                                        mkdir($directory, 0777, true);
                                    }
                                    $avatar = $request->file('avatar');
                                    $size = Image::make($avatar->getRealPath())->width();
                                    //lazy
                                    $img_l = Image::make($avatar->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img_l->save($directory . '/lazy.png', 100);
                                    //original
                                    $img_o = Image::make($avatar->getRealPath())->resize($size, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img_o->save($directory . '/index.png', 100);
                                }
                                return response()->json([
                                    'message' => 'Usuario modificado exitosamente',
                                    'complete' => true,
                                ]);
                            } else {
                                if (
                                    $data->firstname == $request->input('firstname') && $data->lastname == $request->input('lastname') &&
                                    $data->user == $request->input('user') && $data->email == $request->input('email') &&
                                    $data->avatar == $request->input('avatar') &&
                                    ($auth_user->type == "0" && $data->type == $request->input('type'))
                                ) {
                                    return response()->json([
                                        'message' => 'La información proporcionada no modifica el usuario, asi que no se ha actualizado',
                                        'complete' => false,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Ha ocurrido un error al momento de modificar el usuario',
                                        'complete' => false,
                                    ]);
                                }
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $data = DB::table("users")->where("slug", $slug)->where('type', "0")->orWhere('type', '1')->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El usuario seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    if ($auth_user->id  == $data->id) {
                        return response()->json([
                            'message' => "No puede eliminar su propio usuario.",
                            'complete' => false,
                        ]);
                    } else {
                        $total = sizeof(DB::table('users')->get());
                        if ($total <= 1) {
                            return response()->json([
                                'message' => "No puede eliminar el ultimo usuario de la aplicación, si desea eliminarlo, cree uno nuevo y luego elimine el deseado.",
                                'complete' => false,
                            ]);
                        } else {
                            $total = DB::table('users')->where("type", "0")->get();
                            if (sizeof($total) <= 1 && $data->type == "0") {
                                return response()->json([
                                    'message' => "No puede eliminar el ultimo usuario administrador, si desea eliminarlo, cree uno nuevo y luego elimine el deseado.",
                                    'complete' => false,
                                ]);
                            } else {
                                // Sacamos los temas atribuidos al usuario, tanto si el lo creo o si fue actualizado
                                $topics_c = DB::table("topics")->where("user_id", $data->id)->get();
                                if (sizeof($topics_c) > 0) {
                                    DB::update("UPDATE topics SET user_id = ? WHERE user_id = ?", [
                                        null,
                                        $data->id,
                                    ]);
                                }
                                $topics_u = DB::table("topics")->where("user_update_id", $data->id)->get();
                                if (sizeof($topics_u) > 0) {
                                    DB::update("UPDATE topics SET user_update_id = ? WHERE user_update_id = ?", [
                                        null,
                                        $data->id,
                                    ]);
                                }
                                // Eliminamos las relaciones de la materia con un usuario
                                $subjects_user = DB::table("user_subject")->where("user_id", $data->id)->get();
                                if (sizeof($subjects_user) > 0) {
                                    DB::table("user_subject")->where("user_id", $data->id)->delete();
                                }
                                if (DB::table("users")->delete($data->id)) {
                                    if ($data->avatar) {
                                        // Eliminamos las imagenes del user
                                        $directory = public_path('img/users') . "/" . $data->avatar;
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
                                        'message' => 'Usuario eliminado exitosamente',
                                        'complete' => true,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Ha ocurrido un error al momento de eliminar el usuario',
                                        'complete' => true,
                                    ]);
                                }
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
}
