<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
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
        $subjects = DB::table('subjects')->pluck('name');
        $tags = DB::table('tags')->get();
        return response()->json([
            'subjects' => $subjects,
            'tags' => $tags,
        ]);
    }
}
