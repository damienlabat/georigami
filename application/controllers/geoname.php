<?php

class Geoname_Controller extends Base_Controller {

	protected static $base_url = 'http://api.geonames.org/';

	public function action_search()
	{
		$url=self::$base_url.'searchJSON?name='.utf8_encode( Input::get('q') ).'&maxRows=50&style=short&featureClass=T&username='.Config::get('geoname.username');
		
		$res = json_decode(file_get_contents($url), TRUE );

		foreach ($res['geonames'] as &$o) {
			$o['country']= self::getISO3166( $o['countryCode'] );
			$o['feature']= self::getFCode( $o['fcode'] );
		}

		return Response::json( $res );
	}


	public function action_findNearby($lat,$lng) // plus bbox ?
	{
		//todo
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
