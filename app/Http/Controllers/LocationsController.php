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

    public function country($country, Location $location){
        $countries = $location->get('countries');

        $foundCountry = array_filter($countries, function ($item) use ($country) {
            return $item->iso2 === $country;
        });

        $firstMatch = reset($foundCountry);

        return response()->json($firstMatch);
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

    public function state($country, $state, Location $location){
        $states = $location->get('states');

        $foundStates = array_filter($states, function ($item) use ($country) {
            return $item->country_code === $country;
        });

        $firstMatchStates = array_values($foundStates);

        $foundState = array_filter($firstMatchStates, function ($item) use ($state) {
            return $item->state_code === $state;
        });

        $firstMatch = reset($foundState);

        return response()->json($firstMatch);
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

    public function city($city, Location $location){
        $cities = $location->get('cities');

        $foundCity = array_filter($cities, function ($item) use ($city) {
            return $item->id == $city;
        });

        $firstMatch = reset($foundCity);

        return response()->json($firstMatch);
    }
}
