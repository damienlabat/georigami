<?php
/**
 *  Main controler for geoname ajax call
 */
class Geoname_Controller extends Base_Controller
{

    /**
     * return geoname searchJson
     *
     * @return Response geoname search JSON
     */
    public function action_search()
    {
        $res=Geoname::search(  Input::get('q'), 'T', 50, 'long' );
        if ($res['totalResultsCount']<50)      $res2=Geoname::search(  Input::get('q'), null, 50-$res['totalResultsCount'], 'short' );
            else  $res2=Geoname::search(  Input::get('q'), null, 50, 'short' );
        $res['totalResultsCount']=$res['totalResultsCount']+$res2['totalResultsCount'];
        $res['geonames']=array_merge($res['geonames'],$res2['geonames']);


        return Response::json( $res );
    }

    /**
     * return geoname startwith search
     * @return Response geoname startwith JSON
     */
    public function action_startwith()
    {
        return Response::json( Geoname::startwith(Input::get('q'), 'T', 10, 'short') );
    }

}
