<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Exception;

class ProfileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user && $user->status == 1) {
                $validator = Validator::make($request->all(), [
                    'firstname' => ['bail', 'required', 'string', 'max:50'],
                    'lastname' => ['bail', 'required', 'string', 'max:50'],
                    'user' => ['bail', 'required', 'string', 'max:50', 'unique:users,user,' . $user->id],
                    'email' => ['bail', 'required', 'email', 'max:100', 'unique:users,email,' . $user->id],
                    'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                    'avatar_new' => ['bail', 'sometimes', 'boolean'],
                ]);
                if ($validator->fails()) {
                    $old_user = DB::table("users")->where('user', $request->input('user'))->first();
                    $old_email = DB::table("users")->where('email', $request->input('email'))->first();
                    if ($user->id != $old_user->id) {
                        return response()->json([
                            'message' => 'El usuario ingresado ya existe',
                            'complete' => false,
                        ]);
                    } else if ($user->email != $old_email->id) {
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
                    $new_avatar = "";
                    //Direccion de la imagen
                    if (!$request->file('avatar')) {
                        if (!$request->input('avatar_new')) {
                            $new_avatar = $user->avatar;
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
                        $user->id,
                    ])) {
                        // Verificamos si no se ha eliminado el avatar que ya tenia el usuario
                        if (!$request->file('avatar')) {
                            if ($request->input('avatar_new') && $user->avatar) {
                                File::delete(public_path('/img/users') . '/' . $user->avatar);
                                File::delete(public_path('/img/lazy_users/') . '/' . $user->avatar);
                            }
                        } else {
                            if ($user->avatar) {
                                File::delete(public_path('/img/users') . '/' . $user->avatar);
                                File::delete(public_path('/img/lazy_users/') . '/' . $user->avatar);
                            }
                            $avatar = $request->file('avatar');
                            $size = Image::make($avatar->getRealPath())->width();
                            //lazy
                            $address_l = public_path('/img/lazy_users');
                            $img_l = Image::make($avatar->getRealPath())->resize($size * 0.01, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_l->save($address_l . '/' . $new_avatar, 100);
                            //original
                            $address_o = public_path('/img/users');
                            $img_o = Image::make($avatar->getRealPath())->resize($size, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $img_o->save($address_o . '/' . $new_avatar, 100);
                        }
                        return response()->json([
                            'message' => 'Ha modificado exitosamente su información',
                            'complete' => true,
                        ]);
                    } else {
                        if (
                            $user->firstname == $request->input('firstname') && $user->lastname == $request->input('lastname') &&
                            $user->user == $request->input('user') && $user->email == $request->input('email') &&
                            $user->avatar == $request->input('avatar')
                        ) {
                            return response()->json([
                                'message' => 'La información proporcionada no modifica su información, asi que no sera actualizada',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Ha ocurrido un error al momento de modificar su información',
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
}
