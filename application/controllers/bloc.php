<?php

class Bloc_Controller extends Base_Controller {

	public function action_index()
	{
		$blocs=Blocs::all();
		$blocs_array=[];
		foreach($blocs as $b){
		 $blocs_array[] = $b->to_array();
		}
		return View::make('index')->with('data',array( 'blocs'=>$blocs, 'blocs_json'=>json_encode( $blocs_array ) ));
	}



	public function action_get($id)
	{
		if (!$bloc=Blocs::find($id)) return Response::error('404');
		$blocArray=$bloc->to_array();
		$blocArray['coords']=$bloc->get_coords();
		return View::make('bloc')->with('data',array( 'bloc'=>$bloc, 'bloc_json'=>json_encode( $blocArray ) ));
	}



	public function action_getJson($id, $with_data=FALSE)
	{
		if (!$bloc=Blocs::find($id)) return Response::error('404');
		$res= $bloc->attributes;
		if ($with_data) $res['coords']= $bloc->get_coords();
		
		return  Response::json( $res );
	}




	public function action_image($id,$view)
	{
		if (!$bloc=Blocs::find($id)) return Response::error('404');

		return 'image '.$id.$view;
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


		$coords= Blocs::validate_coords($input['coords']);

		if (!$coords)
		{
		    return 'INVALID COORDS'; // TODO better error message
		}


		$bloc = new Blocs;

		$bloc->hSamples = $input['hSamples'];
		$bloc->vSamples = $input['vSamples'];

		$bloc->hSlices = $input['hSlices'];
		$bloc->vSlices = $input['vSlices'];

		$bloc->height = $input['height'];
		$bloc->width = $input['width'];

		$bloc->lat = $input['lat'];
		$bloc->lng = $input['lng'];


		$bloc->min = $input['min'];
		$bloc->max = $input['max'];	

		$bloc->bbox = $input['bbox'];

		$bloc->save();

		$bloc->save_coords( $coords );

		return self::action_getJson( $bloc->id, TRUE );
	}

}