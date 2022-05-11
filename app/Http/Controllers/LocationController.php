<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function search (Request $request) {
        $query = $request->input('location');

        //positionstack api
        $locationResponse = Http::get('http://api.positionstack.com/v1/forward', [
            'access_key' => '64cf4796d42c6fc38f79220918c0de17',
            'query' => $query,
        ]);

        $parsed = json_decode($locationResponse);

        $location = new Location();
        $location->query = $query;
        if(is_array($parsed->data)){
            $location->country = $parsed->data[0]->country;
            $location->country_code = $parsed->data[0]->country_code;
            $location->latitude = $parsed->data[0]->latitude;
            $location->longitude = $parsed->data[0]->longitude;
        }

        //weather api
        $weatherResponse = Http::get('http://api.weatherapi.com/v1/forecast.json', [
            'key' => 'ebfcf7fc64e04c888e8100320221005',
            'q' => "$location->latitude,$location->longitude",
            'days' => 1,
        ]);
        $location->local_time = json_decode($weatherResponse)->location->localtime;


        //country api (capital city)
        $capitalResponse = Http::get('http://api.worldbank.org/v2/country/'.($location->country_code).'?format=json');
        $parse = json_decode($capitalResponse);
        //dd($parse[1][0]->capitalCity);
        //return $parse;
        $location->capital = $parse[1][0]->capitalCity;

        
        $location->save();

        $var = DB::select('SELECT country, country_code, COUNT(*) AS pocet FROM locations GROUP BY country, country_code');

        $time0 = DB::table('locations')->whereTime('local_time', '>', '00:00:00')->whereTime('local_time', '<', '06:00:00')->count();
        $time1 = DB::table('locations')->whereTime('local_time', '>', '06:00:00')->whereTime('local_time', '<', '15:00:00')->count();
        $time2 = DB::table('locations')->whereTime('local_time', '>', '15:00:00')->whereTime('local_time', '<', '21:00:00')->count();
        $time3 = DB::table('locations')->whereTime('local_time', '>', '21:00:00')->whereTime('local_time', '<', '00:00:00')->count();

        //return view('Welcome')->with(['location' => $location, 'weather' => json_decode($weatherResponse)->forecast->forecastday[0]->day]);
        return view('Welcome')->with(['location' => $location, 'weather' => json_decode($weatherResponse)->forecast->forecastday[0]->day, 'var' => $var, 'time0' => $time0, 'time1' => $time1, 'time2' => $time2, 'time3' => $time3]);


    }   

    // public function pocet(){
    //     $var = DB::select('SELECT country, country_code, COUNT(*) AS pocet 
    //     from locations
    //     GROUP BY country, country_code');
    //     //dd($var);
    // }

}
?>