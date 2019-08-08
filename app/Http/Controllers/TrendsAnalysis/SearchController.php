<?php

namespace App\Http\Controllers\TrendsAnalysis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function residential(Request $request)
    {
        return view('TrendsAnalysis.residential');
    }

    public function report(Request $request)
    {
        return view('TrendsAnalysis.report');
    }
}
