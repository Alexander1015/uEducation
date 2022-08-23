<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ListRecordController extends Controller
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
                    return response()->json([]);
                } else {
                    if ($request->input("period") == "01" || $request->input("period") == "02" || $request->input("period") == "03") {
                        if ((is_int($request->input("year")) || ctype_digit($request->input("year"))) && strlen($request->input("year")) == 4) {
                            $records = DB::table("records")->where("period", $request->input("period"))->where("year", $request->input("year"))->get();
                            $subjects = array();
                            foreach ($records as $item) {
                                array_push($subjects, ["code" => $item->code, "name" => $item->subject]);
                            }
                            return response()->json([
                                'data' => $records,
                                'subjects' => $subjects
                            ]);
                        } else {
                            return response()->json([]);
                        }
                    } else {
                        return response()->json([]);
                    }
                }
            } else return response()->json([]);
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }
}
