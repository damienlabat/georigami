<?php

class location extends BaseModel
{
     /**
     * enable timestamps
     */
    public static $timestamps = true;

    /**
     * location blocs
     * @return array blocs array
     */
    public function blocs()
    {
        return $this->has_many('Bloc')
            ->order_by('updated_at', 'desc');
    }

    /**
     * return location url (autoloaded with presenter)
     * @return string
     */
    public function get_url()
    {
        return URL::to_route('location', array( Str::slug($this->name),  $this->id ));
    }

    /**
     * return localized country name  (autoloaded with presenter)
     * @return string
     */
    public function get_countryname()
    {
        return Lang::line('geoname.'.$this->countrycode)->get();
    }

    /**
     * return localized feature class (autoloaded with presenter)
     * @return string
     */
    public function get_fclname()
    {
        return Lang::line('geoname.fcl_'.$this->fcl)->get();
    }

    /**
     * return location icon (autoloaded with presenter)
     * @return string
     */
    public function get_icon()
    {
        return Geoname::getIcon($this->fcode, $this->fcl);
    }


    /**
     * get location or create if not found
     * @param  number $lat latitude
     * @param  number $lng longitude
     * @return Location
     */
    public static function getorcreate($lat,$lng)
    {
        $dataJson=Geoname::findTheBest($lat, $lng);

        if (!isset($dataJson['lat'])) {
            $dataJson['lat']=number_format($lat, 5, '.', '');
            $dataJson['lng']=number_format($lng, 5, '.', '');
        } else {
            $dataJson['lat']=number_format($dataJson['lat'], 5, '.', '');
            $dataJson['lng']=number_format($dataJson['lng'], 5, '.', '');
        }

        if ($location= self::where('geonameId', '=', $dataJson['geonameId'])->first() )
            return $location;

        elseif ($location= self::where('lat', '=', $dataJson['lat'])->where('lng', '=', $dataJson['lng'])->first() )
            return $location;

        else return self::create($dataJson);
    }

    /**
     * create new location from data
     * @param  array $data geoname array
     * @return Location
     */
    public static function create($data)
    {

        $location = new Location;

        $location->lat=$data['lat'];
        $location->lng=$data['lng'];

        if (isset($data['geonameId']))      $location->geonameId 	    = $data['geonameId'];

        if (isset($data['name']))           $location->name             = $data['name'];
            else                                   $location->name      = 'unknown place';

        if (isset($data['adminName1']))     $location->adminName1       = $data['adminName1'];
        if (isset($data['adminName2']))     $location->adminName2       = $data['adminName2'];
        if (isset($data['adminName3']))     $location->adminName3       = $data['adminName3'];
        if (isset($data['adminName4']))     $location->adminName4       = $data['adminName4'];
        if (isset($data['countryCode']))    $location->countryCode      = $data['countryCode'];
        if (isset($data['fcl']))            $location->fcl              = $data['fcl'];
        if (isset($data['fcode']))          $location->fcode            = $data['fcode'];
        if (isset($data['continentCode']))  $location->continentCode    = $data['continentCode'];

        $location->save();

        return $location;
    }
}
