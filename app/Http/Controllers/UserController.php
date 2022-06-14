<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
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
        $users = DB::table('users')->get();
        return response()->json($users);
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
                    'firstname' => ['bail', 'required', 'string', 'max:50'],
                    'lastname' => ['bail', 'required', 'string', 'max:50'],
                    'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user'],
                    'email' => ['bail', 'required', 'email', 'max:100', 'unique:users,email'],
                    'password' => ['bail', 'required', 'string', 'min:8', 'max:50', 'confirmed'],
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
                            'message' => 'Hay datos que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    }
                } else {
                    /*
                    * Si da error 500 para guardar la imagen, se debe cambiar el archivo php.ini del servidor
                    * y cambiar la linea: ;extension=gd a: extension=gd
                    * o: ;extension=gd2 a: extension=gd2
                    */
                    $new_avatar = "";
                    if ($request->file('avatar')) {
                        //Direccion de la imagen
                        $new_avatar = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                    }
                    if (DB::table("users")
                        ->insert([
                            'firstname' => $request->input('firstname'),
                            'lastname' => $request->input('lastname'),
                            'user' => $request->input('user'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                            'avatar' => $new_avatar,
                        ])
                    ) {
                        if ($request->file('avatar')) {
                            $avatar = $request->file('avatar');
                            $size = Image::make($avatar->getRealPath())->width();
                            //lazy
                            $address_l = public_path('/img/lazy_users');
                            if (!File::isDirectory($address_l)) {
                                File::makeDirectory($address_l, 0777, true, true);
                            }
                            $img_l = Image::make($avatar->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_l->save($address_l . '/' . $new_avatar, 100);
                            //original
                            $address_o = public_path('/img/users');
                            if (!File::isDirectory($address_o)) {
                                File::makeDirectory($address_o, 0777, true, true);
                            }
                            $img_o = Image::make($avatar->getRealPath())->resize($size, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_o->save($address_o . '/' . $new_avatar, 100);
                        }
                        return response()->json([
                            'message' => 'Usuario creado exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ah ocurrido un error al momento de crear el usuario',
                            'complete' => false,
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table("users")->where("id", $id)->first();
        return response()->json($user);
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
                $data = DB::table("users")->where("id", $id)->first();
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
                                'message' => 'Hay datos que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    } else {
                        $user_auth = auth()->user()->id;
                        if ($user_auth == $data->id) {
                            return response()->json([
                                'message' => "Debe modificar su información en el apartado de perfil de su usuario.",
                                'complete' => false,
                            ]);
                        } else {
                            $new_avatar = "";
                            //Direccion de la imagen
                            if (!$request->file('avatar')) {
                                if (!$request->input('avatar_new')) {
                                    $new_avatar = $data->avatar;
                                }
                            } else {
                                $new_avatar = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                            }
                            if (DB::update("UPDATE users SET firstname = ?, lastname = ?, user = ?, email = ?, avatar = ? WHERE id = ?", [
                                $request->input('firstname'),
                                $request->input('lastname'),
                                $request->input('user'),
                                $request->input('email'),
                                $new_avatar,
                                $data->id,
                            ])) {
                                // Verificamos si no se ha eliminado el avatar que ya tenia el usuario
                                if (!$request->file('avatar')) {
                                    if ($request->input('avatar_new') && $data->avatar) {
                                        File::delete(public_path('/img/users') . '/' . $data->avatar);
                                        File::delete(public_path('/img/lazy_users/') . '/' . $data->avatar);
                                    }
                                } else {
                                    if ($data->avatar) {
                                        File::delete(public_path('/img/users') . '/' . $data->avatar);
                                        File::delete(public_path('/img/lazy_users/') . '/' . $data->avatar);
                                    }
                                    $avatar = $request->file('avatar');
                                    $size = Image::make($avatar->getRealPath())->width();
                                    //lazy
                                    $address_l = public_path('/img/lazy_users');
                                    if (!File::isDirectory($address_l)) {
                                        File::makeDirectory($address_l, 0777, true, true);
                                    }
                                    $img_l = Image::make($avatar->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img_l->save($address_l . '/' . $new_avatar, 100);
                                    //original
                                    $address_o = public_path('/img/users');
                                    if (!File::isDirectory($address_o)) {
                                        File::makeDirectory($address_o, 0777, true, true);
                                    }
                                    $img_o = Image::make($avatar->getRealPath())->resize($size, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img_o->save($address_o . '/' . $new_avatar, 100);
                                }
                                return response()->json([
                                    'message' => 'Usuario modificado exitosamente',
                                    'complete' => true,
                                ]);
                            } else {
                                if (
                                    $data->firstname == $request->input('firstname') && $data->lastname == $request->input('lastname') &&
                                    $data->user == $request->input('user') && $data->email == $request->input('email') &&
                                    $data->avatar == $request->input('avatar')
                                ) {
                                    return response()->json([
                                        'message' => 'La información proporcionada no modifica el usuario, asi que no se ha actualizado',
                                        'complete' => false,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Ah ocurrido un error al momento de modificar el usuario',
                                        'complete' => false,
                                    ]);
                                }
                            }
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
                $data = DB::table("users")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "El usuario seleccionado no existe",
                        'complete' => false,
                    ]);
                } else {
                    $user_auth = auth()->user()->id;
                    if ($user_auth  == $data->id) {
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

                            if (DB::table("users")->delete($data->id)) {
                                if ($data->avatar) {
                                    File::delete(public_path('/img/users') . '/' . $data->avatar);
                                    File::delete(public_path('/img/lazy_users/') . '/' . $data->avatar);
                                }
                                return response()->json([
                                    'message' => 'Usuario eliminado exitosamente',
                                    'complete' => true,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ah ocurrido un error al momento de eliminar el usuario',
                                    'complete' => true,
                                ]);
                            }
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
}
