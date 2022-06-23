<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
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
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tags_ids = DB::table("topic_tag")->where("topic_id", $id)->get();
        $tags = array();
        if (sizeof($tags_ids) > 0) {
            foreach ($tags_ids as $tag) {
                $data = DB::table("tags")->where("id", $tag->tag_id)->first();
                array_push($tags, $data->name);
            }
        }
        return response()->json($tags);
    }
}
