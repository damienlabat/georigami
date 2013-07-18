<?php
/**
 *  Main controler for bloc
 */
class Bloc_Controller extends Base_Controller
{

    /**
     * return  index page with last blocs
     *
     * @return View
     */
    public function action_index()
    {	$perPage=24;

        $face= Input::get('face', 'N');

        $blocs=Bloc::with('location')->order_by('updated_at', 'desc')->paginate($perPage);

        return View::make('index')->with(array('blocs'=>$blocs, 'face'=>$face ));
    }


    /**
     * return  index page with last saved profiles
     *
     * @return View
     */
    public function action_saved()
    {   $perPage=12;

        $saved=Savedview::with('bloc')->where('show', '=', true)->order_by('updated_at', 'desc')->paginate($perPage);

        return View::make('saved')->with(array('savedviews'=>$saved));
    }


    /**
     * show last saved views
     * @param  string $locname location name
     * @param  integer $id      bloc id
     * @return View
     */
    public function action_saved_show($locname,$id)
    {
        if (!$saved= Savedview::with('bloc')->find($id)) return Response::error('404');
        if (Str::slug($saved->bloc->location->name)!=$locname) return Response::error('404');

        $png_exist= file_exists($saved->getDirectory('png').'view'.$saved->id.'_'.Str::slug($saved->bloc->location->name).'.png');
        $url=$saved->bloc->get_url('profil').'?'.http_build_query(json_decode($saved->params));

        $locationArray=$saved->bloc->location->presenter();
        $locationArray['blocs']=array($saved->bloc->presenter());

        if (!$png_exist) {
            $params=json_decode($saved->params, true);
            $params['header']=      false;
            $params['crop']=      false;
            $params['reduce']=      false;
            $svg= profil::profil_svg($saved->bloc->profil_data($params['face']), $params);
        }
        else {
            $svg=false;
        }

       
        return View::make('saved_view')->with(array('saved'=>$saved, 'location_json'=> json_encode($locationArray), 'url'=>$url, 'svg'=>$svg, 'png_exist'=>$png_exist));

        
        //return Redirect::to($url);
    }


    /**
     * return map page
     *
     * @return View
     */
    public function action_map()
    {
        $locations=Location::with('blocs')
        /*->order_by('continentCode', 'asc')
        ERROR IN GEONAME DATABASE SOME COUNTRY WITH 2 CONTINENTS (ie. russian federation) */
        ->order_by('countryCode', 'asc')
        ->order_by('adminName1', 'asc')
        ->order_by('adminName2', 'asc')
        ->order_by('adminName3', 'asc')
        ->order_by('adminName4', 'asc')
        ->order_by('name', 'asc')
        ->get();

        $locationsArray=array();

        foreach($locations as $b)
             $locationsArray[] = $b->presenter();

        return View::make('map')->with(array('locations'=>$locations, 'locations_json'=>json_encode($locationsArray) ));
    }

    /**
     * return location page
     *
     * @param  string  $locname    location name
     * @param  int $locationid location id
     * @return View
     */
    public function action_location($locname,$locationid)
    {
        $face= Input::get('face', 'N');

        if (!$location=Location::with('blocs')->find($locationid)) return Response::error('404');

        if (Str::slug($location->name)!=$locname)
            return Redirect::to_route('location', array(Str::slug($location->name),$locationid));

        $locationPrev= Location::with('blocs')->where('id', '<', $locationid)->order_by('id', 'desc')->first();
        $locationNext= Location::with('blocs')->where('id', '>', $locationid)->order_by('id', 'asc')->first();

        $locationArray=$location->presenter();

        return View::make('location')->with(
            array(
                'location'=>$location,
                'location_json'=> json_encode($locationArray),
                'face'=>$face,
                'prev'=>$locationPrev,
                'next'=>$locationNext
            )
        );
    }



    /**
     * return bloc page
     *
     * @param  string  $locname location name
     * @param  int $locid   location id
     * @param  int $blocid  bloc id
     * @param  string  $show    tab to show (profil|3d|print)
     * @return View
     */
    public function action_get($locname,$locid,$blocid, $show=null)
    {
        if ($show==null) return Redirect::to_route('getplus', array($locname,$locid,$blocid,'profil'));

        $face= Input::get('face', 'N');
        $vscale= Input::get('vscale', 1);

        if (!$bloc= Bloc::with('location')->find($blocid)) return Response::error('404');

        if (Str::slug($bloc->location->name)!=$locname)
          return Redirect::to_route('get', array(Str::slug($bloc->location->name),$locid,$blocid));

        $blocArray=$bloc->presenter();

        $blocPrev= Bloc::with('location')->where('id', '<', $blocid)->order_by('id', 'desc')->first();
        $blocNext= Bloc::with('location')->where('id', '>', $blocid)->order_by('id', 'asc')->first();

        $location=$bloc->location;

        $data=array(
            'show'=>       $show,
            'bloc'=>       $bloc,
            'bloc_json'=>  json_encode($blocArray),
            'prev'=>       $blocPrev,
            'next'=>       $blocNext,
            'vscale'=>     $vscale,
            'face'=>       $face,
        );

        $locationArray=$bloc->location->presenter();
        $locationArray['blocs']=array($bloc->presenter());
        $data['location_json']= json_encode($locationArray);

        if ($show=='print') {
            $data['hidecut']=       Input::get('hidecut', null);
            $data['hidetext']=      Input::get('hidetext', null);
            $data['showvslice']=      Input::get('showvslice', 'west');
            $data['showhslice']=      Input::get('showhslice', 'north');
        }

        if (($show=='profil')||($show=='download')) {

            $profilData=$bloc->profil_data($face);

            $data['style']=       Input::get('style', '');
            $data['dx']=          Input::get('dx', 0);
            $data['dy']=          Input::get('dy', 0);
            $data['dscale']=      Input::get('dscale', 0);

            $data['svg']=         profil::profil_svg($profilData, $data);
            $data['svg_hscale']=  100;
            $data['profil_data']= $profilData;

            $data=array_merge($data, $profilData);

        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'N.svg')) profil::save_svg($bloc, 'N');
        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'E.svg')) profil::save_svg($bloc, 'E');
        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'S.svg')) profil::save_svg($bloc, 'S');
        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'W.svg')) profil::save_svg($bloc, 'W');

        }

        if ($show=='download') {

            Event::override(
                'laravel.done', function(){
                    // No profiler
                }
            );

            $params=Input::get();
            if ( !Agent::is_robot() ) $showinsaved=true;
                else  $showinsaved=false;

            profil::save_view($bloc, $profilData, $params, $showinsaved);

            return Response::make(
                $data['svg'], 200, array(
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => 'attachment; filename="'.$locname.$locid.$data['face'].'.svg"'
                )
            );

        }

        return View::make('bloc_'.$show)->with($data);

    }



 



    /**
     * return bloc json data
     *
     * @param  int  $id       bloc id
     * @param  boolean  $withData add profil data to json
     * @return Response Json response
     */
    public function action_getJson($id, $withData=FALSE)
    {   if (!$bloc=Bloc::with('location')->find($id)) return Response::error('404');
        $res=$bloc->presenter();
        $res['location']=$bloc->location->presenter();

        if ($withData) $res['coords']= $bloc->get_coords();
        return Response::json($res);
    }




    /**
     * Validate and add new bloc to database
     *
     * @return Response Bloc Json response
     */
    public function action_store()
    {
        $input = Input::all();

        $rules = array(

            'hSamples'  => 'required|integer|min:2|max:500',
            'vSamples'  => 'required|integer|min:2|max:500',

            'hSlices'   => 'required|integer|min:1',
            'vSlices'   => 'required|integer|min:1',

            'height'    => 'required|integer|min:1',
            'width'     => 'required|integer|min:1',
            'rotate'    => 'required|integer|min:-45|max:45',

            'lat'       => 'required|numeric|min:-90|max:90',
            'lng'       => 'required|numeric|min:-180|max:180',

            'min'       => 'required|numeric',
            'max'       => 'required|numeric',

        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            return $validation->errors;
        }

        $coords= Bloc::validate_coords($input['coords']);

        if (!$coords) {
            return json_encode(array('error'=>'INVALID COORDS'));
        }

        $location= Location::getorcreate($input['lat'], $input['lng']);

        $bloc = new Bloc;

        $bloc->lat =      $input['lat'];
        $bloc->lng =      $input['lng'];

        $bloc->hsamples = $input['hSamples'];
        $bloc->vsamples = $input['vSamples'];

        $bloc->hslices =  $input['hSlices'];
        $bloc->vslices =  $input['vSlices'];

        $bloc->rotate =   $input['rotate'];

        $bloc->height =   $input['height'];
        $bloc->width =    $input['width'];

        $bloc->min =      $input['min'];
        $bloc->max =      $input['max'];

        $bloc->bbox =     $input['bbox'];

        $bloc= $location->blocs()->insert($bloc);

        $bloc->save_coords($coords);

        profil::save_svg($bloc,'N');
        profil::save_svg($bloc,'E');
        profil::save_svg($bloc,'S');
        profil::save_svg($bloc,'W');

        return Redirect::to_route('getJson', array($bloc->id));

       // return self::action_getJson($bloc->id, TRUE);

    }

}
