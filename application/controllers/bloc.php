<?php

class Bloc_Controller extends Base_Controller {

	public function action_index()
	{	$per_page=4*4;

		$blocs=Bloc::with('location')->order_by('updated_at', 'desc')->paginate($per_page);
		return View::make('index')->with('data',array('blocs'=>$blocs ));
	}


	public function action_map()
	{
		$locations=Location::with('blocs')
		//->where('name','<>','')		
		->order_by('continentCode', 'asc')	
		->order_by('countryCode', 'asc')	
		->order_by('adminName1', 'asc')		
		->order_by('adminName2', 'asc')		
		->order_by('adminName3', 'asc')		
		->order_by('adminName4', 'asc')		
		->order_by('name', 'asc')	
		->get();

		//$counts=array('countryName','adminName1','name');		

		$locations_array=array();
		foreach($locations as $b){			
			$data=$b->to_array();
			$data['countryname']=Geoname::getISO3166($data['countrycode']);
		 	$locations_array[] = $data;
		}
		return View::make('map')->with('data',array( 'locations'=>$locations, 'locations_json'=>json_encode( $locations_array ) ));
	}



	public function action_location($locationid)
	{	

		if (!$location=Location::with('blocs')->find($locationid)) return Response::error('404');

		$locationPrev= Location::with('blocs')->where('id','<',$locationid)->order_by('id', 'desc')->first();
		$locationNext= Location::with('blocs')->where('id','>',$locationid)->order_by('id', 'asc')->first();


		$locationArray=$location->to_array();
		$locationArray['blocs']=array();

		foreach($location->blocs as $b){			
			$data=$b->to_array();
			$data['coords']=$b->get_coords();
		 	$locationArray['blocs'][] = $data;
		}



		return View::make('location')->with('data',array('location'=>$location , 'location_json'=> json_encode( $locationArray ),'prev'=>$locationPrev, 'next'=>$locationNext));
	}





	public function action_get($blocid, $show='3D')
	{

		if (!$bloc= Bloc::with('location')->find($blocid)) return Response::error('404');

		$blocArray=$bloc->to_array();
		$blocArray['coords']=$bloc->get_coords();

		$blocPrev= Bloc::with('location')->where('id','<',$blocid)->order_by('id', 'desc')->first();
		$blocNext= Bloc::with('location')->where('id','>',$blocid)->order_by('id', 'asc')->first();

		$location=$bloc->location;

		return View::make('bloc')->with('data',array( 'show'=>$show, 'location'=>$location, 'bloc'=>$blocArray, 'bloc_json'=> json_encode( $blocArray ), 'prev'=>$blocPrev, 'next'=>$blocNext ));

	}



	public function action_getJson($id, $with_data=FALSE)
	{
		if (!$bloc=Bloc::with('location')->find($id)) return Response::error('404');
		$res=$bloc->to_array();
		$res['location']=$bloc->location->to_array();
		$res['location']['countryname']=Geoname::getISO3166($res['location']['countrycode']);

		if ($with_data) $res['coords']= $bloc->get_coords();
		
		return  Response::json( $res );
	}




	public function action_svg($id,$view)
	{
		if (!$bloc=Bloc::find($id)) return Response::error('404');

		$data=array('strokewidth'=>0.005);

		

		$coords=$bloc->get_coords();


		if ($view=='S') $data['coords']=$coords->h;
		if ($view=='N') $data['coords']=array_reverse($coords->h);

		if ($view=='W') $data['coords']=$coords->v;
		if ($view=='E') $data['coords']=array_reverse($coords->v);

		if (($view=='N')||($view=='S'))	$data['dim']=$bloc->width/max($bloc->height,$bloc->width);
		else	$data['dim']=$bloc->height/max($bloc->height,$bloc->width);

		$data['max']=0;
		
		foreach ($data['coords'] as &$slice) {
			if ($slice->m > $data['max']) $data['max']=$slice->m;

			if (($view=='N')||($view=='E'))	{
				$slice->c=array_reverse($slice->c);
				foreach ($slice->c as &$cpt) $cpt[0]=$data['dim']-$cpt[0];
			}

		}

		$data['max']=(floor($data['max']*1000)+1 ) / 1000;


		 Event::override('laravel.done', function(){}); // No profiler
		 $svg=View::make(  'svgprofil' )->with('data',$data);
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
