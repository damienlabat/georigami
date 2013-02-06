<?php

class Geoname extends Eloquent {

    protected static $base_url = 'http://api.geonames.org/';


    static function search($req,$featureClass=null,$maxRows=50,$style='short')
    {        

        $url = self::$base_url.'searchJSON?name='.utf8_encode( $req );
        if ($maxRows!=null) $url.= '&maxRows='.$maxRows;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        if ($style!=null) $url.= '&style='.$style;
        $url.= '&username='.Config::get('geoname.username');
        
        $res = json_decode(file_get_contents($url), TRUE );

        foreach ($res['geonames'] as &$o) {
            if (isset($o['countryCode']))   $o['country']= Geoname::getISO3166( $o['countryCode'] );
                else $o['country']='';
            if (isset($o['fcode']))         $o['feature']= Geoname::getFCode( $o['fcode'] );
                else $o['feature']='';
        }

        return $res;
    }


	static function findNearby($lat,$lng,$featureClass=null) // plus bbox ?
    {
        $url = self::$base_url.'findNearbyJSON?lat='.$lat.'&lng='.$lng;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        $url.= '&username='.Config::get('geoname.username');

        $res = json_decode(file_get_contents($url), TRUE );

        foreach ($res['geonames'] as &$o) {
            if (isset($o['countryCode']))   $o['country']= self::getISO3166( $o['countryCode'] );
                else $o['country']='';
            if (isset($o['fcode']))         $o['feature']= self::getFCode( $o['fcode'] );
                else $o['feature']='';
        }

        return $res;
    }


    static  function getISO3166( $code ) {
        $list= json_decode( Config::get('geoname.iso_countries'), TRUE );

        if (isset($list[$code])) return $list[$code]; 
            return FALSE;
    }


    static  function getFCode( $code ) {
        $list= json_decode( Config::get('geoname.fcodes'), TRUE );

        if (isset($list[$code])) return $list[$code]; 
            return FALSE;
    }



}