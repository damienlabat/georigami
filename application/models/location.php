<?php

class Location extends Eloquent {

	public static $timestamps = true;

	public function blocs()
    {
        return $this->has_many('Bloc');
    }

    public static function get_byLatLng($lat,$lng) {

    	return self::where('lat', '=', $lat)->where('lng', '=', $lng)->first();

    }



    public function update_geoname()
    { 

        $data=FALSE;
        $geoname=Geoname::findNearby($this->lat,$this->lng, 'T');
        if (count($geoname['geonames']==0))	$geoname=Geoname::findNearby($this->lat,$this->lng);


        if (count($geoname['geonames']>0)) {
        	$data=$geoname['geonames'][0];

            

        	$this->distance 	= $data['distance'];
        	$this->geonameId 	= $data['geonameId'];
        	$this->countryName 	= $data['countryName'];
        	$this->adminCode1 	= $data['adminCode1'];
        	$this->fclName 		= $data['fclName'];
        	$this->countryCode 	= $data['countryCode'];
        	$this->fcodeName 	= $data['fcodeName'];
        	$this->toponymName 	= $data['toponymName'];
        	$this->fcl 			= $data['fcl'];
        	$this->name 		= $data['name'];
        	$this->fcode 		= $data['fcode'];
        	$this->adminName1 	= $data['adminName1'];
        	$this->feature 		= $data['feature'][0];
        	$this->featureDetail = $data['feature'][1];
        	
        	$this->save();        	
        }
        
        
        return $this;
    }

}
