<?php

class Geoname_Controller extends Base_Controller
{
    public function action_search()
    {
        return Response::json( Geoname::search(  Input::get('q'), 'T', 10, 'short' ) );
    }

    public function action_startwith()
    {
        return Response::json( Geoname::startwith(  Input::get('q'), 'T', 10, 'short' ) );
    }

}
