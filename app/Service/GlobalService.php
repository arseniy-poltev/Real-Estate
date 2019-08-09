<?php

namespace App\Service;


use Auth;
use App\Models\Projects;
use Carbon\Carbon;

class GlobalService
{
    public static function getStreetFromAddress($str)
    {
        $str = preg_split('#\s+#', explode('#', $str)[0], 2);
        return trim($str[1]);
    }

    public static function getNormalDateString($str)
    {
        $dateArray = explode('/', $str);
        return "20" . $dateArray[2] . '-' . str_pad($dateArray[1],2, '0', STR_PAD_LEFT) . '-' . str_pad($dateArray[0],2, '0', STR_PAD_LEFT);
    }

    public static function quarterDateToNormalDate($str)
    {
        $year = explode('Q', $str)[0];
        $month = explode('Q', $str)[1] * 3;
        return $year . '-' . str_pad($month,2, '0', STR_PAD_LEFT);
    }

    public static function getProject($project_name)
    {
        $project = Projects::where('Project Name', $project_name)->first();
        return $project;
    }

    public static function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return round($angle * $earthRadius, 2);
    }

    public static function getDistanceAndMarker($nearby_items, $source)
    {
        $i = 0;
        $nearby_items = $nearby_items->map(function ($item) use ($source){
           $item_detail = GlobalService::getProject($item['Project Name']);
           if ($item_detail) {
               $item['distance'] = self::vincentyGreatCircleDistance($source['Latitude'], $source['Longitude'], $item_detail['Latitude'], $item_detail['Longitude']);
               $item['Latitude'] = $item_detail['Latitude'];
               $item['Longitude'] = $item_detail['Longitude'];
           } else {
               $item['distance'] = null;
               $item['Latitude'] = null;
               $item['Longitude'] = null;
           }
            return $item;
        })->sortBy('distance');
        return $nearby_items;
    }

    public static function checkUserPermission()
    {
        if (Auth::guest()) {
            return false;
        } else {
            $user = Auth::User();
            if (!$user->hasVerifiedEmail()) {
                return false;
            } else {
                if ($user->payment_verified) {
                    $subscription_expired = Carbon::now()->addYears(1);
                    if ($subscription_expired < Carbon::now()) {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    $register_datediff = Carbon::now()->diffInHours($user->created_at);
                    if ($register_datediff > 24) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }
    }
}
