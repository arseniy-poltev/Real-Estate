<?php

namespace App\Http\Controllers\TrendsAnalysis;

use App\Models\LandedTransaction;
use App\Service\GlobalConstant;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class LandedController extends Controller
{
    public function search(Request $request)
    {
        $district = LandedTransaction::groupBy('Postal District')->select('Postal District', DB::raw('count(*) as total'))->get();

        return view('TrendsAnalysis.landed', ['district' => $district]);
    }

    public function searchData(Request $request)
    {
        $search = $request->q;

        $page = $request->page;
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        $items = LandedTransaction::where('Project Name', 'like', '%' . $search . '%')->orWhere('Address', 'like', '%' . $search . '%')->groupBy('Project Name')->skip($offset)->take($resultCount)->get();
        $count = LandedTransaction::where('Project Name', 'like', '%' . $search . '%')->orWhere('Address', 'like', '%' . $search . '%')->groupBy('Project Name')->count();

        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $results = array(
            "results" => $items,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }

    public function report(Request $request)
    {
        $projct_name = $request->p;
        $project = LandedTransaction::where('Project Name', $projct_name)->first();
        return view('TrendsAnalysis.report_landed', compact('project'));
    }

    public function refresh_setting(Request $request)
    {
        $config_values = $request->all();
        unset($config_values['_token']);
        Cookie::queue(GlobalConstant::REPORT_LANDED_CONFIG_COOKIE, json_encode($config_values));
        return redirect()->back();
    }

    public function save_setting(Request $request)
    {
        $config_values = $request->all();
        unset($config_values['_token']);
        Cookie::queue(GlobalConstant::REPORT_LANDED_CONFIG_COOKIE, json_encode($config_values));
        if (Auth::User()) {
            $user = User::find(Auth::User()->id);
            $user->report_landed_config = json_encode($config_values);
            $user->save();
        }

        return 'success';
    }
}
