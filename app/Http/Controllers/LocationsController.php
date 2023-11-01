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

    public function findNearbyCities(Location $location)
    {
        $radiusKm = request('radius');
        $cityId = request('city');

        // Fetch the list of cities from the API or your database.
        $cities = $location->get('cities');

        $foundCity = array_filter($cities, function ($item) use ($cityId) {
            return $item->id == $cityId;
        });

        $firstMatch = reset($foundCity);

        // Get the latitude and longitude from the request or other source.
        $targetLatitude = $firstMatch->latitude;
        $targetLongitude = $firstMatch->longitude;

        // Create an empty array to store nearby cities.
        $nearbyCities = [];

        foreach ($cities as $city) {
            // Calculate the distance using the Haversine formula.
            $distance = $this->calculateHaversineDistance(
                $targetLatitude,
                $targetLongitude,
                $city->latitude,
                $city->longitude
            );

            // Check if the city is within the desired radius.
            if ($distance <= $radiusKm) {
                $city->distance = $distance;
                $nearbyCities[] = $city;
            }
        }

        usort($nearbyCities, function ($a, $b) {
            return $a->distance - $b->distance;
        });
        

        return response()->json([
            'total' => count($nearbyCities),
            'nearby_cities' => $nearbyCities
        ]);
    }

    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
    
        // Radius of the Earth in kilometers
        $earthRadius = 6371;
    
        // Haversine formula
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;
    
        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        // Calculate the distance
        $distance = $earthRadius * $c;
    

        return $distance;
    }

}
