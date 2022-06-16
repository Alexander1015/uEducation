<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = DB::table('tags')->get();
        return response()->json($tags);
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
            if ($auth_user && $auth_user->status == 1) {
                $validator = Validator::make($request->all(), [
                    'name' => ['bail', 'required', 'string', 'max:25', 'unique:tags,name'],
                    'background_color' => ['bail', 'sometimes', 'string'],
                    'background_text' => ['bail', 'sometimes', 'string'],
                ]);
                if ($validator->fails()) {
                    $old_name = DB::table("tags")->where('name', $request->input('name'))->first();
                    if ($old_name) {
                        return response()->json([
                            'message' => 'El título ingresado ya existe',
                            'complete' => false,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                            'complete' => false,
                        ]);
                    }
                } else {
                    if (DB::table("tags")
                        ->insert([
                            'name' => $request->input('name'),
                            'background_color' => $request->input('background_color') ? $request->input('background_color') : "#E0E0E0",
                            'text_color' => $request->input('text_color') ? $request->input('text_color') : "#676767",
                        ])
                    ) {
                        return response()->json([
                            'message' => 'Etiqueta creada exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ha ocurrido un error al momento de crear la etiqueta',
                            'complete' => false,
                        ]);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = DB::table("tags")->where("id", $id)->first();
        return response()->json($tag);
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
        try {
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                $data = DB::table("tags")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La etiqueta seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'name' => ['bail', 'required', 'string', 'max:250', 'unique:tags,name,' . $data->id],
                        'background_color' => ['bail', 'sometimes', 'string'],
                        'background_text' => ['bail', 'sometimes', 'string'],
                    ]);
                    if ($validator->fails()) {
                        $old_name = DB::table("tags")->where('name', $request->input('name'))->first();
                        if ($data->id != $old_name->id) {
                            return response()->json([
                                'message' => 'El título ingresado ya existe',
                                'complete' => false,
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'Hay datos proporcionados que no siguen el formato solicitado',
                                'complete' => false,
                            ]);
                        }
                    } else {
                        if (DB::update("UPDATE tags SET name = ?, background_color = ?, text_color = ? WHERE id = ?", [
                            $request->input('name'),
                            $request->input('background_color') ? $request->input('background_color') : "#E0E0E0",
                            $request->input('text_color') ? $request->input('text_color') : "#676767",
                            $data->id,
                        ])) {
                            return response()->json([
                                'message' => 'Etiqueta modificada exitosamente',
                                'complete' => true,
                            ]);
                        } else {
                            if (
                                $data->name == $request->input('name') &&
                                $data->background_color == $request->input('background_color') &&
                                $data->text_color == $request->input('text_color')
                            ) {
                                return response()->json([
                                    'message' => 'La información proporcionada no modifica la etiqueta, asi que no se ha actualizado',
                                    'complete' => false,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Ha ocurrido un error al momento de modificar la etiqueta',
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
                $data = DB::table("tags")->where("id", $id)->first();
                if (!$data) {
                    return response()->json([
                        'message' => "La etiqueta seleccionada no existe",
                        'complete' => false,
                    ]);
                } else {
                    if (DB::table("tags")->delete($data->id)) {
                        return response()->json([
                            'message' => 'Etiqueta eliminada exitosamente',
                            'complete' => true,
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Ha ocurrido un error al momento de eliminar la etiqueta',
                            'complete' => true,
                        ]);
                    }
                }
            } catch (Exception $ex) {
                return response()->json([
                    // 'message' => $ex->getMessage(),
                    'message' => "Ha ocurrido un error en la aplicación",
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
