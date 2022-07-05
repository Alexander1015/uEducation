<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class GetTopicController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // Topic
        $topic = DB::select(
            'SELECT
                T.id, T.name, T.slug, T.abstract, T.body, T.img, T.status, T.sequence, Uc.user AS user, Uu.user AS user_update, S.name AS subject, T.created_at, T.updated_at
            FROM 
                topics AS T
            LEFT JOIN subjects AS S ON T.subject_id = S.id
            LEFT JOIN users AS Uc ON T.user_id = Uc.id
            LEFT JOIN users AS Uu ON T.user_update_id = Uu.id
            WHERE
                T.slug = ? AND T.status = ?
            ORDER BY T.sequence ASC',
            [
                $slug,
                1
            ]
        );
        // Tags
        $tags_ids = DB::table("topic_tag")->where("topic_id", $topic[0]->id)->get();
        $tags = array();
        if (sizeof($tags_ids) > 0) {
            foreach ($tags_ids as $tag) {
                $data = DB::table("tags")->where("id", $tag->tag_id)->first();
                array_push($tags, $data);
            }
        }
        return response()->json([
            'topic' => $topic[0],
            'tags' => $tags,
        ]);
    }
}
