<?php

class Bloc_Controller extends Base_Controller {

	public function action_index()
	{
		$locations=Location::with('blocs')->order_by('updated_at', 'desc')->get();

		$locations_array=array();
		foreach($locations as $b){			
			$data=$b->to_array();
		 	$locations_array[] = $data;
		}
		return View::make('index')->with('data',array( 'locations'=>$locations_array, 'locations_json'=>json_encode( $locations_array ) ));
	}


	public function action_map()
	{
		$locations=Location::with('blocs')->order_by('updated_at', 'desc')->get();
		$locations_array=array();
		foreach($locations as $b){			
			$data=$b->to_array();
		 	$locations_array[] = $data;
		}
		return View::make('map')->with('data',array( 'locations'=>$locations_array, 'locations_json'=>json_encode( $locations_array ) ));
	}



	public function action_get($locationid,$pos=1)
	{
		if (!$location=Location::with('blocs')->find($locationid)) return Response::error('404');
		$locationArray=$location->to_array();

		if (!isset($locationArray['blocs'][$pos-1])) return Response::error('404');

		$blocArray=$locationArray['blocs'][$pos-1];
		$blocArray['coords']=$location->blocs[$pos-1]->get_coords();

		return View::make('bloc')->with('data',array( 'pos'=>$pos-1, 'location'=>$locationArray, 'location_json'=>json_encode( $locationArray ), 'bloc_json'=> json_encode( $blocArray )));
	}



	public function action_getJson($id, $with_data=FALSE)
	{
		if (!$bloc=Bloc::with('location')->find($id)) return Response::error('404');
		$res=$bloc->to_array();
		$res['location']=$bloc->location->to_array();
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

		//print_r($data);

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


		$location= Location::get_byLatLng( $input['lat'], $input['lng'] );

		if (!$location) {
			$location= new Location;
			$location->lat = $input['lat'];
			$location->lng = $input['lng'];
			$location->save();
			$location->update_geoname();
		}

		$bloc = new Bloc;

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
