<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{

    /**
     * getNearByAffiliatesData get near by affiliates data for given radius.
     *
     * @param Request request
     * @param radius  by default it will take 100 km radius
     * @return view
     */

    public function getNearByAffiliatesData(Request $request,$radius=100) : View
    {
       
        $latitude = 53.3340285; // office's latitude
        $longitude = -6.2535495; // office's longitude

        $affiliates = [];

        //Get Affiliates by using model
        $affiliatesData = collect(Affiliate::getAffiliates());

        //convert array of string to array of object
        foreach($affiliatesData as $affiliate){
            $affiliates[] = json_decode($affiliate); //string to object conversion
        }

        //Filter Affiliates within given radius in KM and sort them in ascending order by affiliate_id

        $sortedAffiliates = collect($affiliates)->filter(function ($affiliate) use($latitude,$longitude,$radius){
            
            $distance = ( 6371.009  * acos( 
                                            cos( deg2rad($latitude) ) 
                                            * cos( deg2rad($affiliate->latitude) )
                                            * cos( deg2rad($affiliate->longitude) - deg2rad($longitude) ) 
                                            + sin( deg2rad($latitude) ) 
                                            *sin( deg2rad($affiliate->latitude) ) 
                                        )
                    ); //distance calculation
           
           return ($distance <= $radius);  //filter calculated distance with given (100km by default) distance
      
        })->mapWithKeys(function ($affiliate) {
            return [$affiliate->affiliate_id => $affiliate]; //map by key(affiliate_id)
        })->sortKeys();     //sorting by key in asc

        return view('affiliates',compact('sortedAffiliates'));
    }
}
