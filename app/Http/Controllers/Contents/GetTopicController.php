<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
            $auth_user = auth()->user();
            if ($auth_user && $auth_user->status == 1) {
                // Topic
                $topic = DB::select(
                    'SELECT
                    T.id, T.name, T.slug, T.abstract, T.img, T.status, T.sequence, T.body, CONCAT(Uc.firstname, " ", Uc.lastname) AS user, Uc.email As user_email, Uc.avatar AS user_avatar, Uc.status AS user_status, CONCAT(Uu.firstname, " ", Uu.lastname) AS user_update, Uu.email As user_update_email, Uu.avatar AS user_update_avatar, Uu.status AS user_update_status, S.name AS subject, S.code, T.subject_id, S.slug AS subject_slug, T.created_at, T.updated_at
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
                $review = DB::table("user_subject")->where("user_id", $auth_user->id)->where("subject_id", $topic[0]->subject_id)->first();
                if ($auth_user->type == "0" || $review) {
                    // Verificamos que existe la carpeta para mostrar el body
                    $file = public_path('data') . "/" . $topic[0]->body . "/body.html";
                    if (!file_exists($file)) {
                        $path = public_path('data');
                        if (!File::isDirectory($path)) {
                            mkdir($path, 0777, true);
                        }
                        $path .= "/" . $topic[0]->body;
                        if (!File::isDirectory($path)) {
                            mkdir($path, 0777, true);
                        }
                        $path .= "/body.html";
                        fopen($path, "w");
                    }
                    $topic[0]->data = file_get_contents($file);
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
                } else {
                    return response()->json([]);
                }
            } else {
                return response()->json([]);
            }
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }
}
