<?php

class geoname
{
    /**
     * geoname API url
     * @var string
     */
    protected static $_baseUrl= 'http://api.geonames.org/';

    public static function search($req,$featureClass=null,$maxRows=50,$style='short')
    {

        $url = self::$_baseUrl.'searchJSON?name='.urlencode(utf8_encode($req));
        if ($maxRows!=null) $url.= '&maxRows='.$maxRows;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        if ($style!=null) $url.= '&style='.$style;
        $url.= '&username='.Config::get('geoname.username');

        $res = json_decode(file_get_contents($url), TRUE);

        if (isset($res['geonames'])) {

            foreach ($res['geonames'] as &$o) {
                if (isset($o['countryCode']))   $o['country']= Geoname::getISO3166($o['countryCode']);
                    else $o['country']='';
                if (isset($o['fcode']))         $o['feature']= Geoname::getFCode($o['fcode']);
                    else $o['feature']='';
            }
        }

        return $res;
    }

    public static function startwith($req,$featureClass=null,$maxRows=5,$style='short')
    {

        $url = self::$_baseUrl.'searchJSON?name_startsWith='.utf8_encode($req);
        if ($maxRows!=null) $url.= '&maxRows='.$maxRows;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        if ($style!=null) $url.= '&style='.$style;
        $url.= '&username='.Config::get('geoname.username');

        $res = json_decode(file_get_contents($url), TRUE);

        foreach ($res['geonames'] as &$o) {
            if (isset($o['countryCode']))   $o['country']= Geoname::getISO3166($o['countryCode']);
                else $o['country']='';
            if (isset($o['fcode']))         $o['feature']= Geoname::getFCode($o['fcode']);
                else $o['feature']='';
        }

        return $res;
    }

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

    public static function findCountrySubdivision($lat,$lng)
    {
        $url = self::$_baseUrl.'countrySubdivisionJSON?lat='.$lat.'&lng='.$lng;
        $url.= '&username='.Config::get('geoname.username');
        $res = json_decode(file_get_contents($url), TRUE);

        if (isset($res['status']))
            if($res['status']['value']==15) return false;

        return $res;
    }

    public static function findOcean($lat,$lng)
    {
        $url = self::$_baseUrl.'oceanJSON?lat='.$lat.'&lng='.$lng;
        $url.= '&username='.Config::get('geoname.username');
        $res = json_decode(file_get_contents($url), TRUE);

        return $res;
    }

    public static function findTheBest($lat,$lng)
    {
        $res= self::findNearby($lat, $lng, 'T', 5);
        if (!$res) $res= self::findNearby($lat, $lng, null, 5);
        if (!$res) $res= self::findCountrySubdivision($lat, $lng);
        if ($res===null) {
            $ocean= self::findOcean($lat, $lng);
            if (isset($ocean['ocean'])) $res=  array('adminName1'=>$ocean['ocean']['name']);
                else return false;
        }

        return $res;

    }

    public static function getISO3166( $code )
    {
        $list= json_decode(Config::get('geoname.iso_countries'), TRUE);

        if (isset($list[$code])) return $list[$code];
            return $code;
    }

    public static function getFCode( $code )
    {
        $list= json_decode(Config::get('geoname.fcodes'), TRUE);

        if (isset($list[$code])) return $list[$code];
            return array($code,'');
    }

    public static function getFcl( $code )
    {
        $list= json_decode(Config::get('geoname.fcl'), TRUE);

        if (isset($list[$code])) return $list[$code];
            return $code;
    }

    public static function continentCode( $code )
    {
        $list= json_decode(Config::get('geoname.continentcodes'), TRUE);

        if (isset($list[$code])) return $list[$code];
            return $code;
    }

    public static function getIcon( $code, $code2=false, $code3='default' )
    {
        $list= json_decode(Config::get('geoname.icons'), TRUE);

        if (isset($list[$code])) return $list[$code];
            elseif (isset($list[$code2])) return $list[$code2];
                elseif (isset($list[$code3])) return $list[$code3];
        return false;
    }

}
