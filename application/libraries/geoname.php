<?php

class Geoname {

    protected static $base_url = 'http://api.geonames.org/';


    static function search($req,$featureClass=null,$maxRows=50,$style='short')
    {        

        $url = self::$base_url.'searchJSON?name='.urlencode( utf8_encode( $req ) );
        if ($maxRows!=null) $url.= '&maxRows='.$maxRows;
        if ($featureClass!=null) $url.= '&featureClass='.$featureClass;
        if ($style!=null) $url.= '&style='.$style;
        $url.= '&username='.Config::get('geoname.username');
        
        $res = json_decode(file_get_contents($url), TRUE );

        if (isset($res['geonames'])) {

            foreach ($res['geonames'] as &$o) {
                if (isset($o['countryCode']))   $o['country']= Geoname::getISO3166( $o['countryCode'] );
                    else $o['country']='';
                if (isset($o['fcode']))         $o['feature']= Geoname::getFCode( $o['fcode'] );
                    else $o['feature']='';
            }
        }

        return $res;
    }






    static function startwith($req,$featureClass=null,$maxRows=5,$style='short')
    {        

        $url = self::$base_url.'searchJSON?name_startsWith='.utf8_encode( $req );
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
        $url.= '&style=full&localCountry=false&username='.Config::get('geoname.username');

        $res = json_decode(file_get_contents($url), TRUE );
       
        if (!isset($res['geonames'])) return false;
        if (count($res['geonames'])==0) return false;
            else return $res['geonames'][0];
    }







    static function findCountrySubdivision($lat,$lng)
    {
        $url = self::$base_url.'countrySubdivisionJSON?lat='.$lat.'&lng='.$lng;  
        $url.= '&username='.Config::get('geoname.username');
        $res = json_decode(file_get_contents($url), TRUE );       

        if (isset($res['status']))
            if($res['status']['value']==15) return false;

        return $res;
    }








    static function findOcean($lat,$lng)
    {
        $url = self::$base_url.'oceanJSON?lat='.$lat.'&lng='.$lng;
        $url.= '&username='.Config::get('geoname.username');
        $res = json_decode(file_get_contents($url), TRUE );       

        return $res;
    }




    static function findTheBest($lat,$lng) {

        $res= self::findNearby(  $lat,$lng, 'T' );        
        if (!$res) $res= self::findNearby(  $lat,$lng );
        if (!$res) $res= self::findCountrySubdivision(  $lat,$lng );
        if ($res==null) {
            $ocean= self::findOcean(  $lat,$lng );
            $res=   array('name'=>$ocean['ocean']['name']);
        }

        return $res;

    }

    


    static  function getISO3166( $code ) {
        $list= json_decode( Config::get('geoname.iso_countries'), TRUE );

        if (isset($list[$code])) return $list[$code]; 
            return $code;
    }


    static  function getFCode( $code ) {
        $list= json_decode( Config::get('geoname.fcodes'), TRUE );

        if (isset($list[$code])) return $list[$code]; 
            return $code;
    }

    static  function getFcl( $code ) {
        $list= json_decode( Config::get('geoname.fcl'), TRUE );

        if (isset($list[$code])) return $list[$code]; 
            return $code;
    }


    static  function continentCode( $code ) {
        $list= json_decode( Config::get('geoname.continentcodes'), TRUE );

        if (isset($list[$code])) return $list[$code]; 
            return $code;
    }



}