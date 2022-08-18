<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class UsersController extends Controller
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
                $users = DB::table('users')->where("type", "1")->where("status", "1")->orderBy('user', 'asc')->get();
                return response()->json($users);
            } else return response()->json([]);
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
                $data = DB::table("subjects")->where("slug", $slug)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La materia seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'users' => ['bail', 'required'],
                    ]);
                    if ($validator->fails()) {
                                return response()->json([
                                    'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                    'complete' => false,
                                ]);
                    } else {
                        if (!is_array($request->input('users')) || sizeof($request->input('users')) < 1) {
                            return response()->json([
                                'message' => 'Los usuarios proporcionados no poseen el formato solicitado',
                                'complete' => false,
                            ]);
                        } else {
                            $total = 0;
                            foreach ($request->input('users') as $user) {
                                $data = DB::table("users")->where("slug", $user)->first();
                                if ($data) {
                                    //
                                }
                                else $total++;
                            }
                            if($total == (sizeof($request->input('users')))) {// Si ninguno se ingreso
                                return response()->json([
                                    'message' => 'No se pudieron suscribir todos los usuarios seleccionados',
                                    'complete' => true,
                                ]);
                            } else if($total > 0 && $total < (sizeof($request->input('users')))) {// Si No todos se pudieron ingresar
                                return response()->json([
                                    'message' => 'No se pudieron suscribir algunos de los usuarios seleccionados',
                                    'complete' => true,
                                ]);
                            }
                            else {
                                return response()->json([
                                    'message' => 'Se han suscrito exitosamente todos los docentes seleccionados',
                                    'complete' => true,
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
                'message' => $ex->getMessage(),
                // 'message' => "Ha ocurrido un error en la aplicación",
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
    public function destroy($id)
    {
        //
    }
}
