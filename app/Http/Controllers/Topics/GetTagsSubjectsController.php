<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class GetTagsSubjectsController extends Controller
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
                $subjects = DB::table('subjects')->get();
                $data = array();
                foreach ($subjects as $item) {
                    $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $item->id)->first();
                    if ($auth_user->type == "0" || $review) {
                        array_push($data, $item);
                    }
                }
                $tags = DB::table('tags')->get();
                return response()->json([
                    'subjects' => $data,
                    'tags' => $tags,
                ]);
            } else {
                return response()->json([]);
            }
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }
}
