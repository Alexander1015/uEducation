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
            if ($auth_user && $auth_user->status == 1) {
                $subjects = DB::table('subjects')->get();
                $tags = DB::table('tags')->get();
                return response()->json([
                    'subjects' => $subjects,
                    'tags' => $tags,
                ]);
            } else {
                return response()->json([
                    'subjects' => [],
                    'tags' => [],
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'subjects' => [],
                'tags' => [],
            ]);
        }
    }
}
