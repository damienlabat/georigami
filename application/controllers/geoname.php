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
        return Response::json( Geoname::search(  Input::get('q'), 'T', 10, 'short' ) );
    }

    /**
     * return geoname startwith search
     * @return Response geoname startwith JSON
     */
    public function action_startwith()
    {
        return Response::json( Geoname::startwith(  Input::get('q'), 'T', 10, 'short' ) );
    }

}
