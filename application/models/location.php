<?php

class Location extends Eloquent {

	public static $timestamps = true;

    public function blocs()
    {
        return $this->has_many('Bloc')
            ->order_by('updated_at', 'desc');
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

            

        	if (isset($data['distance']))    $this->distance 	    = $data['distance'];
        	if (isset($data['geonameId']))   $this->geonameId 	= $data['geonameId'];
        	if (isset($data['countryName'])) $this->countryName 	= $data['countryName'];
        	if (isset($data['adminCode1']))  $this->adminCode1 	= $data['adminCode1'];
        	if (isset($data['fclName']))     $this->fclName 		= $data['fclName'];
        	if (isset($data['countryCode'])) $this->countryCode 	= $data['countryCode'];
        	if (isset($data['fcodeName']))   $this->fcodeName 	= $data['fcodeName'];
        	if (isset($data['toponymName'])) $this->toponymName 	= $data['toponymName'];
        	if (isset($data['fcl']))         $this->fcl 			= $data['fcl'];
        	if (isset($data['name']))        $this->name 	   	    = $data['name'];
        	if (isset($data['fcode']))       $this->fcode 		= $data['fcode'];
        	if (isset($data['adminName1']))  $this->adminName1 	= $data['adminName1'];
        	if (isset($data['feature']))     $this->feature 		= $data['feature'][0];
        	if (isset($data['feature']))     $this->featureDetail = $data['feature'][1];
        	
        	$this->save();        	
        }
        
        
        return $this;
    }

}
