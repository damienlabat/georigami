<?php

class location extends BaseModel
{
    public static $timestamps = true;

    public function blocs()
    {
        return $this->has_many('Bloc')
            ->order_by('updated_at', 'desc');
    }

    public function get_url()
    {
        return URL::to_route('location', array( Str::slug($this->name),  $this->id ));
    }

    public function get_countryname()
    {
        return Geoname::getISO3166($this->countrycode);
    }

    public function get_icon()
    {
        return Geoname::getIcon($this->fcode, $this->fcl);
    }

    public function continentname()
    {
        return Geoname::continentCode($this->continentcode);
    }

    public function fcodename()
    {
        $fcode=Geoname::getFCode($this->fcode);

        return $fcode[0];
    }

    public function fcodedetail()
    {
        $fcode=Geoname::getFCode($this->fcode);

        return $fcode[1];
    }

    public function fclassname()
    {
        return Geoname::getFcl($this->fcl);
    }

    public static function getorcreate($lat,$lng)
    {
        $data_json=Geoname::findTheBest( $lat,$lng) ;

        if (!isset($data_json['lat'])) {
            $data_json['lat']=number_format($lat, 5, '.', '');
            $data_json['lng']=number_format($lng, 5, '.', '');
        }

        if ($location= self::where('lat', '=', $data_json['lat'])->where('lng', '=', $data_json['lng'])->first() )
            return $location;
        else return self::create( $data_json );

    }

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
