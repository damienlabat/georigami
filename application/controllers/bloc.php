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
    {	$perPage=7*5;

        $face= Input::get('face', 'N');

        $blocs=Bloc::with('location')->order_by('updated_at', 'desc')->paginate($perPage);

        return View::make('index')->with(array('blocs'=>$blocs, 'face'=>$face ));
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
          return Redirect::to_route('location', array(Str::slug($bloc->location->name),$locid));

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

        if (($show=='profil')||($show=='download')) {

            $profilData=$bloc->profil_data($face);

            $data['style']=       Input::get('style', '');
            $data['dx']=          Input::get('dx', 0);
            $data['dy']=          Input::get('dy', 0);
            $data['dscale']=      Input::get('dscale', 0);

            $data['svg']=         self::profil_svg($profilData, $data);
            $data['svg_hscale']=  100;
            $data['profil_data']= $profilData;

            $data=array_merge($data, $profilData);

        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'N.svg')) self::save_svg($bloc,'N');
        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'E.svg')) self::save_svg($bloc,'E');
        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'S.svg')) self::save_svg($bloc,'S');
        if (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'W.svg')) self::save_svg($bloc,'W');

        }

        if ($show=='download') {

            Event::override(
                'laravel.done', function(){
                    // No profiler
                }
            );
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
     * return svg profil image
     *
     * @param  array $profilData see Bloc::profil_data
     * @param  array $options    option: vscale, style, dx, dy, dscale, header, svg_hscale
     * @return View
     *
     * @see Bloc::profil_data()
     */
    private function profil_svg($profilData, $options=array() )
    {
        if (!isset($options['vscale'])) $options['vscale']=1;
        if (!isset($options['style']))  $options['style']='';
        if (!isset($options['dx']))     $options['dx']=0;
        if (!isset($options['dy']))     $options['dy']=0;
        if (!isset($options['dscale'])) $options['dscale']=0;
        if (!isset($options['header'])) $options['header']=false;
        if (!isset($options['svg_hscale'])) $options['svg_hscale']=100;
        if (!isset($options['reduce'])) $options['reduce']=false;

        $data=array_merge($profilData, $options);

          $data['svg_vscale']= $data['vscale'] * $data['svg_hscale'];

        return View::make('svg/profil')->with($data);

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
        $res['location']['countryname']=Geoname::getISO3166($res['location']['countrycode']);

        if ($withData) $res['coords']= $bloc->get_coords();
        return Response::json($res);
    }



    /**save reduce profil svg image
     * @param  Bloc $bloc
     * @param  string $face face to show (N|W|S|E)
     * @return void
     */
    private function save_svg($bloc,$face)
    {

            $profilData=$bloc->profil_data($face);

            $data['style']=       'preview';
            $data['dx']=          0;

            if (($face=='N')||($face=='S'))
                $data['dy']=      30/$bloc->hslices;
            else
                $data['dy']=      30/$bloc->vslices;

            $data['dscale']=      0.2;
            $data['header']=      true;
            $data['reduce']=      true;

            $svg= self::profil_svg($profilData, $data);

            File::put($bloc->getDirectory('svg').'bloc'.$bloc->id.$face.'.svg', $svg );
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
            return 'INVALID COORDS'; // TODO better error message
        }

        $location= Location::getorcreate($input['lat'], $input['lng']);

        $bloc = new Bloc;

        $bloc->lat =      $input['lat'];
        $bloc->lng =      $input['lng'];

        $bloc->hSamples = $input['hSamples'];
        $bloc->vSamples = $input['vSamples'];

        $bloc->hSlices =  $input['hSlices'];
        $bloc->vSlices =  $input['vSlices'];

        $bloc->rotate =   $input['rotate'];

        $bloc->height =   $input['height'];
        $bloc->width =    $input['width'];

        $bloc->min =      $input['min'];
        $bloc->max =      $input['max'];

        $bloc->bbox =     $input['bbox'];

        $bloc= $location->blocs()->insert($bloc);

        $bloc->save_coords($coords);

        self::save_svg($bloc,'N');
        self::save_svg($bloc,'E');
        self::save_svg($bloc,'S');
        self::save_svg($bloc,'W');

        return Redirect::to_route('getJson', array($bloc->id));

       // return self::action_getJson($bloc->id, TRUE);

    }

}
