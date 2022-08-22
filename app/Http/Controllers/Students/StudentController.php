<?php

namespace App\Http\Controllers\Students;

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

class StudentController extends Controller
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
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $users = DB::table('users')->where("type", "2")->orderBy('user', 'asc')->get();
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
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $validator = Validator::make($request->all(), [
                    'firstname' => ['bail', 'required', 'string', 'max:50'],
                    'lastname' => ['bail', 'required', 'string', 'max:50'],
                    'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user'],
                    'email' => ['bail', 'required', 'email', 'max:100', 'unique:users,email'],
                    'password' => ['bail', 'required', 'string', 'min:8', 'max:50', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()],
                    'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                ]);
                if ($validator->fails()) {
                    $old_user = DB::table("users")->where('user', strtolower($request->input('user')))->first();
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
                } else {
                    $time_avatar = "";
                    if ($request->file('avatar')) {
                        $time_avatar = time();
                    }
                    $slug = Str::slug(Str::random(20));
                    while (DB::table("users")->where('slug', $slug)->first()) {
                        $slug = Str::random(20);
                    }
                    if (DB::table("users")
                        ->insert([
                            'slug' => $slug,
                            'firstname' => $request->input('firstname'),
                            'lastname' => $request->input('lastname'),
                            'user' => strtolower($request->input('user')),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                            'type' => "2",
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
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $user = DB::table("users")->where("slug", $slug)->where("type", "2")->first();
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
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $data = DB::table("users")->where("slug", $slug)->where("type", "2")->first();
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
                        'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                        'avatar_new' => ['bail', 'sometimes', 'boolean'],
                    ]);
                    if ($validator->fails()) {
                        $old_user = DB::table("users")->where('user', strtolower($request->input('user')))->first();
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
                    } else {
                        if ($auth_user->id == $data->id) {
                            return response()->json([
                                'message' => "Debe modificar su información en el perfil de su usuario",
                                'complete' => false,
                            ]);
                        } else {
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
                            if (DB::update("UPDATE users SET firstname = ?, lastname = ?, user = ?, email = ?, avatar = ? WHERE id = ?", [
                                $request->input('firstname'),
                                $request->input('lastname'),
                                strtolower($request->input('user')),
                                $request->input('email'),
                                $time_avatar,
                                $data->id,
                            ])) {
                                // Si tiene algun record actualizamos los nombres y el usuario si es posible
                                DB::update("UPDATE records SET firstname = ?, lastname = ?, user = ? WHERE user = ? AND status_user = ?", [
                                    $request->input('firstname'),
                                    $request->input('lastname'),
                                    strtolower($request->input('user')),
                                    $data->user,
                                    "1"
                                ]);
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
                                    $data->user == strtolower($request->input('user')) && $data->email == $request->input('email') &&
                                    $data->avatar == $request->input('avatar')
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
                $data = DB::table("users")->where("slug", $slug)->where("type", "2")->first();
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
                        // Eliminamos las relaciones de la materia con un usuario
                        $subjects_user = DB::table("user_subject")->where("user_id", $data->id)->get();
                        if (sizeof($subjects_user) > 0) {
                            DB::table("user_subject")->where("user_id", $data->id)->delete();
                        }
                        if (DB::table("users")->delete($data->id)) {
                            // Si tiene algun record actualizamos el usuario si es posible
                            DB::update("UPDATE records SET status_user = ? WHERE user = ? AND status_user = ?", [
                                "0",
                                $data->user,
                                "1",
                            ]);
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
