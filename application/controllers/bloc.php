<?php

class Bloc_Controller extends Base_Controller {




	public function action_index()
	{	$per_page=4*4;

		$face=       	Input::get('face',       'N');

		$blocs=Bloc::with('location')->order_by('updated_at', 'desc')->paginate($per_page);

		return View::make('index')->with(array('blocs'=>$blocs, 'face'=>$face ));
	}





	public function action_map()
	{
		$locations=Location::with('blocs')
		//->where('name','<>','')		
		//->order_by('continentCode', 'asc') ERROR IN GEONAME DATABASE SOME COUNTRY WITH 2 CONTINENTS (ie. russian federation) 	
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
			$data['url']= $b->get_url();
		 	$locations_array[] = $data;
		}
		
		return View::make('map')->with( array( 'locations'=>$locations, 'locations_json'=>json_encode( $locations_array ) ));
	}



	public function action_location($locname,$locationid)
	{	
		$face=       	Input::get('face',       'N');

		if (!$location=Location::with('blocs')->find($locationid)) return Response::error('404');

		if (Str::slug($location->name)!=$locname)  return Redirect::to_route('location', array(Str::slug($location->name),$locationid));

		$locationPrev= Location::with('blocs')->where('id','<',$locationid)->order_by('id', 'desc')->first();
		$locationNext= Location::with('blocs')->where('id','>',$locationid)->order_by('id', 'asc')->first();


		$locationArray=$location->to_array();
		$locationArray['blocs']=array();

		foreach($location->blocs as $b){			
			$data=$b->to_array();
			$data['coords']=$b->get_coords();
		 	$locationArray['blocs'][] = $data;
		}



		return View::make('location')->with(array('location'=>$location , 'location_json'=> json_encode( $locationArray ),'face'=>$face, 'prev'=>$locationPrev, 'next'=>$locationNext));
	}





	public function action_get($locname,$locid,$blocid, $show=null)
	{
		if ($show==null) return Redirect::to_route('getplus', array($locname,$locid,$blocid,'profil'));

		$face=       	Input::get('face',       'N');
		$vscale=     	Input::get('vscale',     1);
		$strokewidth= 	Input::get('strokewidth', '2');
		$color=       	Input::get('color',       'black');

		if (!$bloc= Bloc::with('location')->find($blocid)) return Response::error('404');

		if (Str::slug($bloc->location->name)!=$locname) 
		  return Redirect::to_route('location', array(Str::slug($bloc->location->name),$locid));

		$blocArray=$bloc->to_array();
		$blocArray['coords']=$bloc->get_coords();

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
			'face'=>$face 
		);

		if ($show=='profil') {

			$data['strokewidth']= $strokewidth/10;
			$data=array_merge($data, $bloc->profil_data($face) );

		}

		return View::make('bloc_'.$show)->with($data);

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




	public function action_svg($id,$face)
	{
		$color=       Input::get('color',       'black');
		$strokewidth= Input::get('strokewidth', '2');

		if (!$bloc=Bloc::find($id)) return Response::error('404');

		$data=array('strokewidth'=> $strokewidth/1000 );

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
