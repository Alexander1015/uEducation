<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
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
        $users = User::all(['id', 'firstname', 'lastname', 'user', 'email', 'password', 'avatar', 'state']);
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
        $state = auth()->user()->state;
        if ($state == 1) {
            try {
                $validator = Validator::make($request->all(), [
                    'firstname' => ['bail', 'required', 'string', 'max:250'],
                    'lastname' => ['bail', 'required', 'string', 'max:250'],
                    'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user'],
                    'email' => ['bail', 'required', 'email', 'max:250', 'unique:users,email'],
                    'password' => ['bail', 'required', 'string', 'min:8', 'max:50', 'confirmed'],
                    'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                ]);
                if ($validator->fails()) {
                    $old_user = User::where('user', $request->input('user'))->first();
                    $old_email = User::where('email', $request->input('email'))->first();
                    if ($old_user->user) {
                        return response()->json([
                            'message' => 'El usuario ingresado ya existe',
                            'complete' => false,
                        ]);
                    } else if ($old_email->email) {
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
                    $data = [
                        'firstname' => $request->input('firstname'),
                        'lastname' => $request->input('lastname'),
                        'user' => $request->input('user'),
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password')),
                        'avatar' => $new_avatar,
                        'state' => 1,
                    ];
                    $user = User::create($data);
                    if ($user) {
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
                            'message' => 'Ha ocurrido un error al crear el usuario',
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
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $state = auth()->user()->state;
        if ($state == 1) {
            try {
                if ($request->input('action') == 0 || $request->input('action') == 3) { //Actualizar información general
                    $validator = Validator::make($request->all(), [
                        'firstname' => ['bail', 'required', 'string', 'max:250'],
                        'lastname' => ['bail', 'required', 'string', 'max:250'],
                        'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user'],
                        'email' => ['bail', 'required', 'email', 'max:250', 'unique:users,email'],
                        'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                        'avatar_new' => ['bail', 'sometimes', 'boolean'],
                    ]);
                    $validate = 0;
                    if ($validator->fails()) {
                        $old = User::where('id', $user->id)->first();
                        if ($old->user == $user->user) {
                            $validate = 0;
                        } else if ($old->user != $user->user) {
                            $validate = 1;
                            return response()->json([
                                'message' => 'El usuario ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else if ($old->email == $user->email) {
                            $validate = 0;
                        } else if ($old->email != $user->email) {
                            $validate = 1;
                            return response()->json([
                                'message' => 'El correo electrónico ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else {
                            $validate = 1;
                            return response()->json([
                                'message' => 'Hay datos que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    }
                    if ($validate == 0) {
                        $user_auth = auth()->user()->id;
                        if ($user_auth == $user->id && $request->input('action') != 3) {
                            return response()->json([
                                'message' => "Debe modificar su información en el apartado de perfil de su usuario.",
                                'complete' => false,
                            ]);
                        } else if ($user_auth != $user->id && $request->input('action') == 3) {
                            return response()->json([
                                'message' => "No puede modificar la información de ese usuario en este apartado.",
                                'complete' => false,
                            ]);
                        } else {
                            $new_avatar = "";
                            // Verificamos si no se ha eliminado el avatar que ya tenia el usuario
                            if (!$request->file('avatar')) {
                                if (!$request->input('avatar_new')) {
                                    $new_avatar = $user->avatar;
                                } else {
                                    if ($user->avatar) {
                                        File::delete(public_path('/img/users') . '/' . $user->avatar);
                                        File::delete(public_path('/img/lazy_users/') . '/' . $user->avatar);
                                    }
                                }
                            } else {
                                if ($user->avatar) {
                                    File::delete(public_path('/img/users') . '/' . $user->avatar);
                                    File::delete(public_path('/img/lazy_users/') . '/' . $user->avatar);
                                }
                                //Direccion de la imagen
                                $new_avatar = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                            }
                            $data = [
                                'firstname' => $request->input('firstname'),
                                'lastname' => $request->input('lastname'),
                                'user' => $request->input('user'),
                                'email' => $request->input('email'),
                                'password' => $user->password,
                                'avatar' => $new_avatar,
                            ];
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
                            $user->fill($data)->save();
                            return response()->json([
                                'message' => 'Usuario modificado exitosamente',
                                'complete' => true,
                            ]);
                        }
                    }
                } else if ($request->input('action') == 1 || $request->input('action') == 1) { //Actualizar contraseña
                    $validator = Validator::make($request->all(), [
                        'password' => ['bail', 'required', 'string', 'min:8', 'max:50', 'confirmed'],
                    ]);
                    if ($validator->fails()) {
                        if ($request->input('password') != $request->input('password_confirmation')) {
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
                        $user_auth = auth()->user()->id;
                        if ($user_auth == $user->id && $request->input('action') != 4) {
                            return response()->json([
                                'message' => "Debe actualizar su contraseña en el apartado de perfil de su usuario.",
                                'complete' => false,
                            ]);
                        }
                        if ($user_auth != $user->id && $request->input('action') == 4) {
                            return response()->json([
                                'message' => "No puede actualizar la contraseña de ese usuario en este apartado.",
                                'complete' => false,
                            ]);
                        } else {
                            $data = [
                                'password' => Hash::make($request->input('password')),
                            ];
                            $user->fill($data)->save();
                            return response()->json([
                                'message' => 'Contraseña actualizada exitosamente',
                                'complete' => true,
                            ]);
                        }
                    }
                } else if ($request->input('action') == 2) { //Cambiar estado
                    $validator = Validator::make($request->all(), [
                        'type' => ['bail', 'required', 'string'],
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Ah ocurrido un error con el envío de información',
                            'complete' => false,
                        ]);
                    } else {
                        if ($request->input('type') == 0 || $request->input('type') == 1) {
                            $data = [
                                'state' => $request->input('type'),
                            ];
                            $user->fill($data)->save();
                            return response()->json([
                                'message' => 'Se ha cambiado el estado del usuario exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ah ocurrido un error con el envío de información',
                                'complete' => false,
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        'message' => 'Ah ocurrido un error con el envío de información',
                        'complete' => false,
                    ]);
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
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $state = auth()->user()->state;
        if ($state == 1) {
            try {
                $user_auth = auth()->user()->id;
                if ($user_auth  == $user->id) {
                    return response()->json([
                        'message' => "No puede eliminar su propio usuario.",
                        'complete' => false,
                    ]);
                } else {
                    $total = sizeof(User::all(['id']));
                    if ($total <= 1) {
                        return response()->json([
                            'message' => "No puede eliminar el ultimo usuario de la aplicación, si desea eliminarlo, cree uno nuevo y luego elimine el deseado.",
                            'complete' => false,
                        ]);
                    } else {
                        $exists = User::where('id', $user->id)->first();
                        if (!$exists->id) {
                            return response()->json([
                                'message' => "El usuario ingresado no existe",
                                'complete' => false,
                            ]);
                        } else {
                            if ($user->avatar) {
                                File::delete(public_path('/img/users') . '/' . $user->avatar);
                                File::delete(public_path('/img/lazy_users/') . '/' . $user->avatar);
                            }
                            $user->delete();
                            return response()->json([
                                'message' => 'Usuario eliminado exitosamente',
                                'complete' => true,
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
}
