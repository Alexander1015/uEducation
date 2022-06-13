<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class GetSubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = DB::table('subjects')->pluck('name');
        return response()->json($subjects);
    }
}
