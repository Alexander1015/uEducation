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
                    'user' => ['bail', 'sometimes', 'string', 'max:50', 'unique:users,user,' . $user->id],
                    'email' => ['bail', 'required', 'email', 'max:100', 'unique:users,email,' . $user->id],
                    'avatar' => ['bail', 'sometimes', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:25600'],
                    'avatar_new' => ['bail', 'sometimes', 'boolean'],
                ]);
                if ($validator->fails()) {
                    $old_user = DB::table("users")->where('user', strtolower($request->input('user')))->first();
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
                    // Verificamos que se ha ingresado al usuario segun el tipo del mismo
                    if ($user->type == "2" && $request->input("user")) {
                        return response()->json([
                            'message' => 'No tiene permitido cambiar el usuario del sistema, contacte a un administrador para llevar a cabo la acción',
                            'complete' => false,
                        ]);
                    } else if (($user->type == "0" || $user->type == "1") && !$request->input("user")) {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    } else {
                        $time_avatar = "";
                        if (!$request->file('avatar')) {
                            if ($request->input('avatar_new') && trim($user->avatar) != "") {
                                // Eliminamos las imagenes del user no usadas
                                $directory = public_path('img/users') . "/" . $user->avatar;
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
                            } else $time_avatar = $user->avatar;
                        } else {
                            if (trim($user->avatar) != "") {
                                // Eliminamos las imagenes del user antiguas
                                $directory = public_path('img/users') . "/" . $user->avatar;
                                if (File::isDirectory($directory)) {
                                    $imgs = File::allFiles($directory);
                                    if (sizeof($imgs) > 0) {
                                        foreach ($imgs as $item) {
                                            File::delete($directory . '/' . $item->getRelativePathname());
                                        }
                                    }
                                }
                                // Eliminamos la carpeta ya que no se usara
                                rmdir($directory);
                            }
                            $time_avatar = time();
                        }
                        if (DB::update("UPDATE users SET firstname = ?, lastname = ?, user = ?, email = ?, avatar = ? WHERE id = ?", [
                            $request->input('firstname'),
                            $request->input('lastname'),
                            strtolower($request->input('user') ? $request->input('user') : $user->user),
                            $request->input('email'),
                            $time_avatar,
                            $user->id,
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
                                'message' => 'Ha modificado exitosamente su información',
                                'complete' => true,
                            ]);
                        } else {
                            if (
                                $user->firstname == $request->input('firstname') && $user->lastname == $request->input('lastname') &&
                                $user->user == strtolower($request->input('user')) && $user->email == $request->input('email') &&
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
