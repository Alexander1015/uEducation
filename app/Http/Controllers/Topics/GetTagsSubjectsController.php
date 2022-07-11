<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
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
            $subjects = DB::table('subjects')->pluck('name');
            $tags = DB::table('tags')->get();
            return response()->json([
                'subjects' => $subjects,
                'tags' => $tags,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'subjects' => [],
                'tags' => [],
            ]);
        }
    }
}
