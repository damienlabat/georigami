<?php
/**
 * use geonames.org API
 */
class geoname
{
    /**
     * geoname API url
     * @var string
     */
    protected static $_baseUrl= 'http://api.geonames.org/';

    /**
     * search loacation by name see http://www.geonames.org/export/geonames-search.html
     * @param  string  $req          searchname
     * @param  string  $featureClass character A,H,L,P,R,S,T,U,V see http://www.geonames.org/export/codes.html
     * @param  integer $maxRows      the maximal number of rows in the document returned by the service. Default is 100, the maximal allowed value is 1000.
     * @param  string  $style        verbosity of returned datas
     * @return array
     */
    public static function search($req,$featureClass=null,$maxRows=50,$style='short')
    {

        $url = self::$_baseUrl.'searchJSON?name='.urlencode(utf8_encode($req));
        if ($maxRows!=null) $url.= '&maxRows='.$maxRows;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        if ($style!=null) $url.= '&style='.$style;
        $url.= '&username='.Config::get('geoname.username');

        $res = json_decode(file_get_contents($url), TRUE);

        return $res;
    }


    /**
     * search location starting with
     * @param  string  $req          searchname
     * @param  string  $featureClass character A,H,L,P,R,S,T,U,V see http://www.geonames.org/export/codes.html
     * @param  integer $maxRows      the maximal number of rows in the document returned by the service. Default is 100, the maximal allowed value is 1000.
     * @param  string  $style        verbosity of returned datas
     * @return [type]                [description]
     */
    public static function startwith($req,$featureClass=null,$maxRows=5,$style='short')
    {

        $url = self::$_baseUrl.'searchJSON?name_startsWith='.utf8_encode($req);
        if ($maxRows!=null) $url.= '&maxRows='.$maxRows;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        if ($style!=null) $url.= '&style='.$style;
        $url.= '&username='.Config::get('geoname.username');

        $res = json_decode(file_get_contents($url), TRUE);

        return $res;
    }

    /**
     * find nearby  locations
     * @param  number  $lat          latitude
     * @param  number  $lng          longitude
     * @param  string  $featureClass character A,H,L,P,R,S,T,U,V see http://www.geonames.org/export/codes.html
     * @param  number  $radius       search radius in km
     * @return array
     */
    public static function findNearby($lat,$lng,$featureClass=null,$radius=1) // plus bbox ?
    {
        $url = self::$_baseUrl.'findNearbyJSON?lat='.$lat.'&lng='.$lng;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        $url.= '&style=full&localCountry=false&radius='.$radius.'&username='.Config::get('geoname.username');

        $res = json_decode(file_get_contents($url), TRUE);

        if (!isset($res['geonames'])) return false;
        if (count($res['geonames'])==0) return false;
            else return $res['geonames'][0];
    }

    /**
     * return country and subdivision
     * @param  number  $lat          latitude
     * @param  number  $lng          longitude
     * @return array
     */
    public static function findCountrySubdivision($lat,$lng)
    {
        $url = self::$_baseUrl.'countrySubdivisionJSON?lat='.$lat.'&lng='.$lng;
        $url.= '&username='.Config::get('geoname.username');
        $res = json_decode(file_get_contents($url), TRUE);

        if (isset($res['status']))
            if($res['status']['value']==15) return false;

        return $res;
    }

    /**
     * find ocean name
     * @param  number  $lat          latitude
     * @param  number  $lng          longitude
     * @return array
     */
    public static function findOcean($lat,$lng)
    {
        $url = self::$_baseUrl.'oceanJSON?lat='.$lat.'&lng='.$lng;
        $url.= '&username='.Config::get('geoname.username');
        $res = json_decode(file_get_contents($url), TRUE);

        return $res;
    }

    /**
     * return best info location > country > ocean
     * @param  number  $lat          latitude
     * @param  number  $lng          longitude
     * @return array
     */
    public static function findTheBest($lat,$lng)
    {
        //$res= self::findNearby($lat, $lng, 'T', 5);
        $res=false;
        if (!$res) $res= self::findNearby($lat, $lng, null, 5);
        if (!$res) $res= self::findCountrySubdivision($lat, $lng);
        if ($res===null) {
            $ocean= self::findOcean($lat, $lng);
            if (isset($ocean['ocean'])) $res=  array('adminName1'=>$ocean['ocean']['name']);
                else return false;
        }

        return $res;

    }

    /**
     * return location icon
     * @param  string  $code
     * @param  string  $code2
     * @param  string  $code3
     * @return string  icon name
     */
    public static function getIcon( $code, $code2=false, $code3='default' )
    {
        $list= json_decode(Config::get('geoname.icons'), TRUE);

        if (isset($list[$code])) return $list[$code];
            elseif (isset($list[$code2])) return $list[$code2];
                elseif (isset($list[$code3])) return $list[$code3];
        return false;
    }

}
