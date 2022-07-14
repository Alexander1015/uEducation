<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;

class GetCarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $carousel = DB::table('carousel')->orderBy('sequence', 'asc')->orderBy('id', 'asc')->get();
            return response()->json($carousel);
        } catch (Exception $ex) {
            return response()->json([]);
        }
    }
}
