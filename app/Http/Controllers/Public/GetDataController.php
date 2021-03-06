<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class GetDataController extends Controller
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
            $search = $request->input('search');
            $search_tags = $request->input('tags');
            $validator = Validator::make($request->all(), [
                'search' => ['bail', 'required', 'in:0,1'],
                'tags' => ['bail', 'sometimes'],
            ]);
            if ($validator->fails()) {
                $search = 0;
            }
            $all_tags = [];
            if ($search == 0) { // Por Materias
                $new_data = array();
                $new_data_all = array();
                //Corto
                $data = DB::select(
                    'SELECT
                        id, name
                    FROM 
                        subjects AS T
                    WHERE
                        status = ?
                    ORDER BY name ASC',
                    [
                        1
                    ]
                );
                $total = sizeof($data) - 1;
                while ($total >= 0) {
                    $topic = DB::table('topics')->where('status', 1)->where("subject_id", $data[$total]->id)->orderBy('sequence', 'asc')->first();
                    if (!$topic) {
                        unset($data[$total]);
                    }
                    else {
                        array_push($new_data, $data[$total]);
                    }
                    $total--;
                }
                $position = 0;
                foreach ($data as $item) {
                    $item->key = $position;
                    $position++;
                }
                // Largo
                $data_all = DB::table('subjects')->where('status', 1)->orderBy('name', 'asc')->get();
                $total = sizeof($data_all) - 1;
                while ($total >= 0) {
                    $topic = DB::table('topics')->where('status', 1)->where("subject_id", $data_all[$total]->id)->orderBy('sequence', 'asc')->first();
                    if ($topic) {
                        $data_all[$total]->first = $topic->slug;
                        array_push($new_data_all, $data_all[$total]);
                    } else {
                        unset($data_all[$total]);
                    }
                    $total--;
                }
            } else if ($search == 1) { // Por temas
                // Corto
                $data = DB::select(
                    'SELECT
                        T.id, T.name, S.name AS subject
                    FROM 
                        topics AS T
                    LEFT JOIN subjects AS S ON T.subject_id = S.id
                    WHERE
                        T.status = ? AND
                        S.status = ?
                    ORDER BY T.name ASC',
                    [
                        1,
                        1
                    ]
                );
                // Completo
                $data_all = DB::select(
                    'SELECT
                        T.id, T.name, T.slug, T.abstract, T.img, T.status, S.name AS subject, S.slug AS subject_slug
                    FROM 
                        topics AS T
                    LEFT JOIN subjects AS S ON T.subject_id = S.id
                    WHERE
                        T.status = ? AND
                        S.status = ?
                    ORDER BY T.name ASC',
                    [
                        1,
                        1
                    ]
                );
                // Si se filtraron tags aqui se buscan
                $new_data = array();
                $new_data_all = array();
                for ($x = sizeof($data) - 1; $x >= 0; $x--) {
                    $tags = DB::select(
                        'SELECT
                            Ta.name, Ta.background_color, Ta.text_color
                        FROM 
                            topic_tag AS Tt
                        LEFT JOIN tags AS Ta ON Tt.tag_id = Ta.id
                        WHERE
                            Tt.topic_id = ? AND Ta.status = ?
                        ORDER BY Ta.name ASC',
                        [
                            $data[$x]->id,
                            1
                        ]
                    );
                    $exist = 0;
                    if ($search_tags && is_array($search_tags) && sizeof($search_tags) > 0) {
                        foreach ($search_tags as $input_tag) {
                            foreach ($tags as $get_tag) {
                                if ($input_tag == $get_tag->name) $exist++;
                            }
                        }
                        // Eliminamos el registro de la lista que obtuvimos que no tenga todos los tags
                        if ($exist < sizeof($search_tags)) {
                            unset($data[$x]);
                            unset($data_all[$x]);
                        } else {
                            $data[$x]->tags = $tags;
                            array_push($new_data, $data[$x]);
                            array_push($new_data_all, $data_all[$x]);
                        }
                        // Obtenemos solo los que sirven
                    } else {
                        $data[$x]->tags = $tags;
                        array_push($new_data, $data[$x]);
                        array_push($new_data_all, $data_all[$x]);
                    }
                }
                // Agregamos la key de posici??n
                for ($x = 0; $x < sizeof($new_data); $x++) {
                    $new_data[$x]->key = $x;
                }
                $all_tags = DB::table('tags')->where('status', 1)->get();
            } else {
                $new_data = [];
                $new_data_all = [];
            }
            if (sizeof($new_data) != sizeof($new_data_all)) {
                $new_data = [];
                $new_data_all = [];
            }
            return response()->json([
                'data' => $new_data,
                'data_all' => $new_data_all,
                'tags' => $all_tags,
                'search' => $search,
                'complete' => true,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                // 'message' => $ex->getMessage(),
                'message' => "Ha ocurrido un error en la aplicaci??n",
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
    public function show($slug)
    {
        try {
            // Obtenemos el subject
            $subject = DB::table("subjects")->where("slug", $slug)->where("status", 1)->first();
            //Obtenemos los topics atribuidos
            $topics = DB::select(
                'SELECT
                id, name, slug, sequence
            FROM 
                topics
            WHERE
                subject_id = ? AND status = ?
            ORDER BY sequence ASC',
                [
                    $subject->id,
                    1
                ]
            );
            $size = 1;
            foreach ($topics as $data) {
                $data->key = $size;
                $size++;
            }
            return response()->json([
                'subject' => $subject,
                'topics' => $topics,
            ]);
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }
}
