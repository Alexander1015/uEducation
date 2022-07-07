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
            $validator = Validator::make($request->all(), [
                'search' => ['bail', 'required', 'in:0,1'],
            ]);
            if ($validator->fails()) {
                $search = 0;
            }
            if ($search == 0) {
                $data = DB::table('subjects')->where('status', 1)->orderBy('name', 'asc')->get();
                $total = sizeof($data) - 1;
                while($total >= 0) {
                    $topic = DB::table('topics')->where('status', 1)->where("subject_id", $data[$total]->id)->orderBy('sequence', 'asc')->first();
                    if ($topic) {
                        $data[$total]->first = $topic->slug;
                    } else {
                        unset($data[$total]);
                    }
                    $total--;
                }
            } else {
                $data = DB::table('topics')->where('status', 1)->orderBy('name', 'asc')->get();
            }
            return response()->json([
                'data' => $data,
                'search' => $search,
                'complete' => true,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                // 'message' => "Ha ocurrido un error en la aplicación",
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
    }
}
