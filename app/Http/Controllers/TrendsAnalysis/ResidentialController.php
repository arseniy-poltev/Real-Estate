<?php

namespace App\Http\Controllers\TrendsAnalysis;

use App\Models\ResidentialTransaction;
use App\Service\GlobalConstant;
use App\Service\GlobalService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ResidentialController extends Controller
{
    public function search(Request $request)
    {
        $projects_1_11 = ResidentialTransaction::where('Postal District', '>=', 1)->where('Postal District', '<=', 11)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(20)->get();
        $projects_12_20 = ResidentialTransaction::where('Postal District', '>=', 12)->where('Postal District', '<=', 20)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(10)->get();
        $projects_21_24 = ResidentialTransaction::where('Postal District', '>=', 21)->where('Postal District', '<=', 24)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(10)->get();
        $projects_25_28 = ResidentialTransaction::where('Postal District', '>=', 25)->where('Postal District', '<=', 28)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(20)->get();

        $data = array(
            'projects_1_11' => $projects_1_11,
            'projects_12_20' => $projects_12_20,
            'projects_21_24' => $projects_21_24,
            'projects_25_28' => $projects_25_28,
        );


        if (Cookie::has(GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE)) {
        } else {
            if (Auth::User() && (Auth::User()->report_residential_config)) {
                Cookie::queue(GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE, Auth::User()->report_residential_config);
            }
        }

        return view('TrendsAnalysis.residential', ['projects' => $data]);
    }

    public function searchData(Request $request)
    {
        $search = $request->q;

        $page = $request->page;
        $resultCount = 25;

        $offset = ($page - 1) * $resultCount;

        $items = ResidentialTransaction::where('Project Name', 'like', '%' . $search . '%')->orWhere('Address', 'like', '%' . $search . '%')->groupBy('Project Name')->skip($offset)->take($resultCount)->get();
        $count = ResidentialTransaction::where('Project Name', 'like', '%' . $search . '%')->orWhere('Address', 'like', '%' . $search . '%')->groupBy('Project Name')->count();

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

        $project = ResidentialTransaction::where('Project Name', $projct_name)->first();
        return view('TrendsAnalysis.report_residential', compact('project'));
    }

    public function refresh_setting(Request $request) 
    {
        $config_values = $request->all();
        unset($config_values['_token']);
        Cookie::queue(GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE, json_encode($config_values));
        return redirect()->back();
    }

    public function save_setting(Request $request)
    {
        $config_values = $request->all();
        unset($config_values['_token']);
        Cookie::queue(GlobalConstant::REPORT_RESIDENTIAL_CONFIG_COOKIE, json_encode($config_values));
        if (Auth::User()) {
            $user = User::find(Auth::User()->id);
            $user->report_residential_config = json_encode($config_values);
            $user->save();
        }

        return 'success';
    }

    public function printPDF(Request $request)
    {
        $projct_name = $request->p;

        $project = ResidentialTransaction::where('Project Name', $projct_name)->first();
        return view('TrendsAnalysis.pdf_residential', compact('project'));
    }

    public function search_units(Request $request)
    {
        $units = $request->units;
        $project_name = $request->project_name;
        $address = $request->address;

        $story_stack = explode('-', $units);

        $projects_from_address = ResidentialTransaction::where('Address', 'like', '%'. GlobalService::getStreetFromAddress($address) . '%')->get();
        $projects_from_address->map(function($item){
            $item['Sale Date'] = GlobalService::getNormalDateString($item['Sale Date']);
            return $item;
        });


        if (count($story_stack) == 2) {
            $project_list = ResidentialTransaction::where('Project Name', $project_name)->get();

            $project_list = $project_list->filter(function ($item) use ($story_stack) {
                $story_address = $item['Address'];
                if (count(explode('#', $item))>1) {
                    $story_stock_item = explode('#', $story_address)[1];
                    if (count(explode('-', $story_stock_item)) > 1) {
                        $story = trim(explode('-', $story_stock_item)[0]);
                        $stack = trim(explode('-', $story_stock_item)[1]);

                        if (((int)$story == (int)$story_stack[0]) && ((int)$stack == (int)$story_stack[1])) {
                            return true;
                        }
                    }
                }
            })->values();



            $project_list = $project_list->map(function($item)  {
                $item['unit_sqft'] =  number_format(round($item['Area (sqm)'] * 10.76));
                $item['Unit Price ($ psm)'] =  number_format($item['Unit Price ($ psm)']);
                $item['Transacted Price ($)'] =  number_format($item['Transacted Price ($)']);
                $item['Area (sqm)'] =  ($item['Area (sqm)']);
                $item['Unit Price ($ psf)'] =  number_format($item['Unit Price ($ psf)']);
                $item['Sale Date'] = GlobalService::getNormalDateString($item['Sale Date']);

                return $item;
            });


            $searched_projects = collect();

            foreach ($project_list as $list) {
                $projects = $projects_from_address->filter(function ($temp) use ($list){
                    if ($temp['Area (sqm)'] > $list['Area (sqm)'] * 0.8 && $temp['Area (sqm)'] < $list['Area (sqm)'] * 1.2) {
                        return true;
                    }
                })->sortByDesc('Sale Date')->take(10)->toArray();

                $searched_projects = $searched_projects->merge($projects);
            }


            $searched_projects = $searched_projects->unique('id')->sortByDesc('Sale Date');

            $searched_projects = $searched_projects->map(function($item) {
                $item['unit_sqft'] =  number_format(round($item['Area (sqm)'] * 10.76));
                $item['Unit Price ($ psm)'] =  number_format($item['Unit Price ($ psm)']);
                $item['Transacted Price ($)'] =  number_format($item['Transacted Price ($)']);
                $item['Area (sqm)'] =  ($item['Area (sqm)']);
                $item['Unit Price ($ psf)'] =  number_format($item['Unit Price ($ psf)']);
                return $item;
            });
            return [
                'status'=> true,
                'data' => $project_list,
                'searched_projects' => $searched_projects
            ];
        } else {
            return [
                'status'=> false
            ];
        }
    }
}
