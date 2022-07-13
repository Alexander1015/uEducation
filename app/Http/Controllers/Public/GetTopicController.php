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
        try {
            // Topic
            $topic = DB::select(
                'SELECT
                T.id, T.name, T.slug, T.abstract, T.body, T.img, T.status, T.sequence, CONCAT(Uc.firstname, " ", Uc.lastname) AS user, Uc.email As user_email, Uc.avatar AS user_avatar, Uc.status AS user_status, CONCAT(Uu.firstname, " ", Uu.lastname) AS user_update, Uu.email As user_update_email, Uu.avatar AS user_update_avatar, Uu.status AS user_update_status, S.name AS subject, T.subject_id, S.slug AS subject_slug, T.created_at, T.updated_at
            FROM 
                topics AS T
            LEFT JOIN subjects AS S ON T.subject_id = S.id
            LEFT JOIN users AS Uc ON T.user_id = Uc.id
            LEFT JOIN users AS Uu ON T.user_update_id = Uu.id
            WHERE
                T.slug = ? AND
                T.status = ? AND
                S.status = ?
            ORDER BY T.sequence ASC',
                [
                    $slug,
                    1,
                    1
                ]
            );
            // Obtenemos el anterior y el siguiente
            $all_topics = DB::select(
                'SELECT
                id, name, slug, status, subject_id
            FROM 
                topics AS T
            WHERE
                subject_id = ? AND status = ?
            ORDER BY sequence ASC',
                [
                    $topic[0]->subject_id,
                    1
                ]
            );
            $previous = array();
            $next = array();
            $total = sizeof($all_topics);
            for ($x = 0; $x < $total; $x++) {
                if ($all_topics[$x]->id == $topic[0]->id) {
                    if ($x == 0 && $total == 1) {
                        break;
                    } else if ($x == 0 && $total > 1) {
                        array_push($next, $all_topics[$x + 1]);
                        break;
                    } else if ($x == ($total - 1)) {
                        array_push($previous, $all_topics[$x - 1]);
                        break;
                    } else {
                        array_push($previous, $all_topics[$x - 1]);
                        array_push($next, $all_topics[$x + 1]);
                        break;
                    }
                }
            }
            // Tags
            $tags = DB::select(
                'SELECT
                Ta.id, Ta.slug, Ta.name, Ta.background_color, Ta.text_color, Ta.status
            FROM 
                topic_tag AS Tt
            LEFT JOIN tags AS Ta ON Tt.tag_id = Ta.id
            WHERE
                Tt.topic_id = ? AND Ta.status = ?
            ORDER BY Ta.name ASC',
                [
                    $topic[0]->id,
                    1
                ]
            );
            return response()->json([
                'topic' => $topic[0],
                'previous' => sizeof($previous) == 1 ? $previous[0] : $previous,
                'next' => sizeof($next) == 1 ? $next[0] : $next,
                'tags' => $tags,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'topic' => [],
                'previous' => [],
                'next' => [],
                'tags' => [],
            ]);
        }
    }
}
