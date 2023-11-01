<?php

namespace App\Http\Controllers;

use App\Models\Location;

class LocationsController extends Controller
{
    public function countries(Location $location){
        $countries = $location->get('countries');

        $data = array(
            'total' => count($countries),
            'list' => $countries
        );
        return response()->json($data);
    }

    public function states(Location $location){
        $country = request('country');
        
        $states = $location->get('states');

        $foundStates = array_filter($states, function ($item) use ($country) {
            return $item->country_code === $country;
        });

        $firstMatch = array_values($foundStates);

        $data = array(
            'total' => count($firstMatch),
            'list' => $firstMatch
        );
        return response()->json($data);
    }

    public function cities(Location $location){
        $country = request('country');
        
        $cities = $location->get('cities');
        
        if(request('state') !== null){
            $foundCities = array_filter($cities, function ($item) use ($country) {
                return $item->country_code === $country && $item->state_code === request('state');
            });
        } else {
            $foundCities = array_filter($cities, function ($item) use ($country) {
                return $item->country_code === $country;
            });

        }

        $firstMatch = array_values($foundCities);



        $data = array(
            'total' => count($firstMatch),
            'list' => $firstMatch
        );
        return response()->json($data);
    }
}
