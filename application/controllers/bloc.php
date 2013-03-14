<?php

class Bloc_Controller extends Base_Controller {




	public function action_index()
	{	$per_page=4*5;

		$face=       	Input::get('face',       'N');

		$blocs=Bloc::with('location')->order_by('updated_at', 'desc')->paginate($per_page);

		return View::make('index')->with(array('blocs'=>$blocs, 'face'=>$face ));
	}





	public function action_map()
	{
		$locations=Location::with('blocs')
		//->order_by('continentCode', 'asc') ERROR IN GEONAME DATABASE SOME COUNTRY WITH 2 CONTINENTS (ie. russian federation)
		->order_by('countryCode', 'asc')
		->order_by('adminName1', 'asc')
		->order_by('adminName2', 'asc')
		->order_by('adminName3', 'asc')
		->order_by('adminName4', 'asc')
		->order_by('name', 'asc')
		->get();


		$locations_array=array();

		foreach($locations as $b)
		 	$locations_array[] = $b->presenter();

		return View::make('map')->with( array( 'locations'=>$locations, 'locations_json'=>json_encode( $locations_array ) ));
	}



	public function action_location($locname,$locationid)
	{
		$face=       	Input::get('face',       'N');

		if (!$location=Location::with('blocs')->find($locationid)) return Response::error('404');

		if (Str::slug($location->name)!=$locname)  return Redirect::to_route('location', array(Str::slug($location->name),$locationid));

		$locationPrev= Location::with('blocs')->where('id','<',$locationid)->order_by('id', 'desc')->first();
		$locationNext= Location::with('blocs')->where('id','>',$locationid)->order_by('id', 'asc')->first();


		$locationArray=$location->presenter();


		return View::make('location')->with(array('location'=>$location , 'location_json'=> json_encode( $locationArray ),'face'=>$face, 'prev'=>$locationPrev, 'next'=>$locationNext));
	}





	public function action_get($locname,$locid,$blocid, $show=null)
	{
		if ($show==null) return Redirect::to_route('getplus', array($locname,$locid,$blocid,'profil'));

		$face=       	Input::get('face',       'N');
		$vscale=     	Input::get('vscale',     1);


		if (!$bloc= Bloc::with('location')->find($blocid)) return Response::error('404');

		if (Str::slug($bloc->location->name)!=$locname)
		  return Redirect::to_route('location', array(Str::slug($bloc->location->name),$locid));

		$blocArray=$bloc->presenter();

		$blocPrev= Bloc::with('location')->where('id','<',$blocid)->order_by('id', 'desc')->first();
		$blocNext= Bloc::with('location')->where('id','>',$blocid)->order_by('id', 'asc')->first();

		$location=$bloc->location;

		$data=array(
			'show'=>$show,
			'bloc'=>$bloc,
			'bloc_json'=> json_encode( $blocArray ),
			'prev'=>$blocPrev,
			'next'=>$blocNext,
			'vscale'=>$vscale,
			'face'=>$face,
		);

		if ($show=='profil') {

			$profil_data=$bloc->profil_data($face);

			$data['style']= Input::get('style',   '');
			$data['dx']= 	Input::get('dx',      0);
			$data['dy']= 	Input::get('dy',      0);
			$data['dscale']= Input::get('dscale', 0);

			$data['svg']= self::profil_svg($profil_data, $data );
			$data['svg_hscale']= 100;
			$data['profil_data']= $profil_data;

			$data=array_merge($data, $profil_data );

		}

		return View::make('bloc_'.$show)->with($data);

	}




	private function profil_svg($profil_data, $options=array() )
    {
        if (!isset($options['vscale'])) $options['vscale']=1;
        if (!isset($options['style']))  $options['style']='';
        if (!isset($options['dx']))     $options['dx']=0;
        if (!isset($options['dy']))     $options['dy']=0;
        if (!isset($options['dscale'])) $options['dscale']=0;
        if (!isset($options['header'])) $options['header']='';
        if (!isset($options['svg_hscale'])) $options['svg_hscale']=100;

        $data=array_merge($profil_data, $options);

  		$data['svg_vscale']= $data['vscale'] * $data['svg_hscale'];



        return View::make('svg/profil')->with($data);;

    }






	public function action_getJson($id, $with_data=FALSE)
	{
		if (!$bloc=Bloc::with('location')->find($id)) return Response::error('404');
		$res=$bloc->presenter();
		$res['location']=$bloc->location->presenter();
		$res['location']['countryname']=Geoname::getISO3166($res['location']['countrycode']);

		if ($with_data) $res['coords']= $bloc->get_coords();

		return  Response::json( $res );
	}




	public function action_svg($id,$face)
	{
		$color=       Input::get('color',       '#aaa');
		if (!$bloc=Bloc::find($id)) return Response::error('404');

		$data=array('id'=>$id, 'color'=> $color );

		$data=array_merge($data, $bloc->profil_data($face) );



		 Event::override('laravel.done', function(){}); // No profiler
		 $svg=View::make(  'svgprofil' )->with($data);
		 return Response::make($svg, 200, array('Content-Type' => 'image/svg+xml'));


	}



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

		if ($validation->fails())
		{
		    return $validation->errors;
		}


		$coords= Bloc::validate_coords($input['coords']);

		if (!$coords)
		{
		    return 'INVALID COORDS'; // TODO better error message
		}


		$location= Location::getorcreate( $input['lat'], $input['lng'] );

		$bloc = new Bloc;

		$bloc->lat = $input['lat'];
		$bloc->lng = $input['lng'];

		$bloc->hSamples = $input['hSamples'];
		$bloc->vSamples = $input['vSamples'];

		$bloc->hSlices = $input['hSlices'];
		$bloc->vSlices = $input['vSlices'];

		$bloc->rotate = $input['rotate'];

		$bloc->height = $input['height'];
		$bloc->width = $input['width'];

		$bloc->min = $input['min'];
		$bloc->max = $input['max'];

		$bloc->bbox = $input['bbox'];


		$bloc= $location->blocs()->insert( $bloc );

		$bloc->save_coords( $coords );

		return self::action_getJson( $bloc->id, TRUE );

	}

}
