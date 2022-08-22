<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class RecordController extends Controller
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
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1 && $auth_user->type == 0) {
                $validator = Validator::make($request->all(), [
                    'period' => ['bail', 'required', 'string'],
                    'year' => ['bail', 'required', 'string'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    if ($request->input("period") == "01" || $request->input("period") == "02" || $request->input("period") == "03") {
                        if ((is_int($request->input("year")) || ctype_digit($request->input("year"))) && strlen($request->input("year")) == 4) {
                            $records = DB::table("user_subject")->get();
                            if (sizeof($records) > 0) {
                                $total = 0;
                                foreach ($records as $rec) {
                                    $user = DB::table("users")->where("id", $rec->user_id)->first();
                                    $subject = DB::table("subjects")->where("id", $rec->subject_id)->first();
                                    if ($user && $subject) {
                                        if (DB::table("records")
                                            ->insert([
                                                'firstname' => $user->firstname,
                                                'lastname' => $user->lastname,
                                                'user' => $user->user,
                                                'status_user' => "1",
                                                'subject' => $subject->name,
                                                'status_subject' => "1",
                                                'period' => $request->input("period"),
                                                'year' => $request->input("year")
                                            ])) {
                                                DB::table("user_subject")->delete($rec->id);
                                            }
                                            else $total++;
                                    } else $total++;
                                }
                                if (sizeof($records) == $total) {
                                    return response()->json([
                                        'message' => 'No se pudieron crear exitosamente todos los registros de las suscripciones',
                                        'complete' => false,
                                    ]);
                                }
                                else if (sizeof($records) > $total && $total > 0) {
                                    return response()->json([
                                        'message' => 'No todos los registros de las suscripciones se pudieron crear exitosamente',
                                        'complete' => true,
                                    ]);
                                }
                                else if ($total == 0) {
                                    return response()->json([
                                        'message' => 'Se crearon exitosamente todos los registros de las suscripciones',
                                        'complete' => true,
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    'message' => 'No hay subscripciones actualmente que se puedan registrar',
                                    'complete' => false,
                                ]);
                            }
                        } else {
                            return response()->json([
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    } else {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
