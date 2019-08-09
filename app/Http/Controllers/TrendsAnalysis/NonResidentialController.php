<?php

namespace App\Http\Controllers\TrendsAnalysis;

use App\Models\CommonTransaction;
use App\Service\GlobalConstant;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class NonResidentialController extends Controller
{
    public function search(Request $request)
    {
//      $projects = CommonTransaction::groupBy('Postal District')->select('Postal District', DB::raw('count(*) as total'))->get();
        $projects_1_11 = CommonTransaction::where('Postal District', '>=', 1)->where('Postal District', '<=', 11)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(20)->get();
        $projects_12_20 = CommonTransaction::where('Postal District', '>=', 12)->where('Postal District', '<=', 20)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(10)->get();
        $projects_21_24 = CommonTransaction::where('Postal District', '>=', 21)->where('Postal District', '<=', 24)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(10)->get();
        $projects_25_28 = CommonTransaction::where('Postal District', '>=', 25)->where('Postal District', '<=', 28)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(20)->get();

        $data = array(
            'projects_1_11' => $projects_1_11,
            'projects_12_20' => $projects_12_20,
            'projects_21_24' => $projects_21_24,
            'projects_25_28' => $projects_25_28,
        );

        return view('TrendsAnalysis.none_residential', ['projects' => $data]);
    }

    public function searchData(Request $request)
    {
        $search = $request->q;

        $page = $request->page;
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        $items = CommonTransaction::where('Project Name', 'like', '%' . $search . '%')->orWhere('Address', 'like', '%' . $search . '%')->groupBy('Project Name')->skip($offset)->take($resultCount)->get();
        $count = CommonTransaction::where('Project Name', 'like', '%' . $search . '%')->orWhere('Address', 'like', '%' . $search . '%')->groupBy('Project Name')->count();

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
        $project = CommonTransaction::where('Project Name', $projct_name)->first();
        return view('TrendsAnalysis.report_non_residential', compact('project'));
    }

    public function refresh_setting(Request $request)
    {
        $config_values = $request->all();
        unset($config_values['_token']);
        Cookie::queue(GlobalConstant::REPORT_NON_RESIDENTIAL_COOKIE, json_encode($config_values));
        return redirect()->back();
    }

    public function save_setting(Request $request)
    {
        $config_values = $request->all();
        unset($config_values['_token']);
        Cookie::queue(GlobalConstant::REPORT_NON_RESIDENTIAL_COOKIE, json_encode($config_values));
        if (Auth::User()) {
            $user = User::find(Auth::User()->id);
            $user->report_nonresidential_config = json_encode($config_values);
            $user->save();
        }

        return 'success';
    }
}
