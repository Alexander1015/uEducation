<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Exception;


class ProfileController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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
                        'firstname' => ['bail', 'required', 'string', 'max:250'],
                        'lastname' => ['bail', 'required', 'string', 'max:250'],
                        'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user,' . $data->id],
                        'email' => ['bail', 'required', 'email', 'max:250', 'unique:users,email,' . $data->id],
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
                        if ($user_auth != $data->id) {
                            return response()->json([
                                'message' => "No puede modificar la información del usuario en este apartado",
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
                                        'message' => 'La información proporcionada no modifica su información, asi que no se ha actualizado',
                                        'complete' => false,
                                    ]);
                                } else {
                                    return response()->json([
                                        'message' => 'Ah ocurrido un error al momento de modificar su información',
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
}
