<?php

namespace App\Http\Controllers\Slug;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Exception;

class SlugController extends Controller
{
    public function store(Request $request)
    {
        try {
            return response()->json([
                'slug' => Str::slug($request->input('name'))
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'slug' => "n/a",
            ]);
        }
    }
}
