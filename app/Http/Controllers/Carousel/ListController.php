<?php

namespace App\Http\Controllers\Carousel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class ListController extends Controller
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
            if ($auth_user && $auth_user->status == 1 && ($auth_user->type == 0 || $auth_user->type == 1)) {
                $validator = Validator::make($request->all(), [
                    'carousel' => ['bail', 'required'],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                        'complete' => false,
                    ]);
                } else {
                    if (!is_array($request->input('carousel')) || sizeof($request->input('carousel')) < 1) {
                        return response()->json([
                            'message' => 'La lista proporcionada no posee el formato solicitado',
                            'complete' => false,
                        ]);
                    } else {
                        $size = 1;
                        $fail = 0;
                        foreach ($request->input('carousel') as $data) {
                            if (!DB::update("UPDATE carousel SET sequence = ? WHERE id = ?", [
                                $size,
                                $data
                            ])) {
                                $fail++;
                            }
                            $size++;
                        }
                        if ($fail == ($size - 1)) {
                            return response()->json([
                                'message' => 'La información proporcionada no modifica el carousel, asi que no se ha actualizado',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Carousel modificado exitosamente',
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
