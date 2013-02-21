<?php

class Bloc extends Eloquent {

	public static $timestamps = true;


    public function location()
    {
        return $this->belongs_to('Location');
    }



	private function getDirectory() {

		$dir= path('public').'../data/'.floor( $this->id/100 ).'/';
		if (!is_dir($dir)) mkdir($dir, 0777);
		return $dir;
	}


    public function get_url($show=null) {

        if ($show==null)
            return URL::to_route('get', array( Str::slug($this->location->name),  $this->location->id, $this->id ));
        else 
            return URL::to_route('getplus', array( Str::slug($this->location->name),  $this->location->id, $this->id, $show ));
    }



    public function profil_data($face) 
    {
        $data=array();

        $coords=$this->get_coords();


        if ($face=='S') $data['coords']=$coords->h;
        if ($face=='N') $data['coords']=array_reverse($coords->h);

        if ($face=='W') $data['coords']=$coords->v;
        if ($face=='E') $data['coords']=array_reverse($coords->v);

        if (($face=='N')||($face=='S')) $data['dim']=$this->width/max($this->height,$this->width);
        else    $data['dim']=$this->height/max($this->height,$this->width);

        $data['max']=0;
        
        foreach ($data['coords'] as &$slice) {
            if ($slice->m > $data['max']) $data['max']=$slice->m;

            if (($face=='N')||($face=='E')) {
                $slice->c=array_reverse($slice->c);
                foreach ($slice->c as &$cpt) $cpt[0]=$data['dim']-$cpt[0];
            }

        }

        $data['max']=(floor($data['max']*1000)+1 ) / 1000;

        return $data;
    }



	static function validate_coords($coordsJson)
    {
    	$coords=json_decode($coordsJson);   

    	if ( (!is_array($coords->v)) || (!is_array($coords->h)) ) return FALSE;
    	$res=array('v'=>array(), 'h'=>array() );

    	foreach ($coords->v as $key => $value) {

    		if ($key==count($coords->v)-1) $slice['t']='West';
    		else $slice['t']='W'.(count($coords->v)-$key);    		

    		$slice['m']=-9999999;
    		$slice['c']=array();

    		foreach ($value as $pt) {
    			if ($pt[1]>$slice['m']) $slice['m']=$pt[1];
    			if ( (!is_numeric($pt[0])) || (!is_numeric($pt[1])) ) return FALSE;
    			$slice['c'][]=array($pt[0],$pt[1]);
    		}

    		$res['v'][]=$slice;
		}



		foreach ($coords->h as $key => $value) {

			$slice=array();

    		if ($key==0) $slice['t']='North';
    		else $slice['t']='N'.($key+1);   

    		$slice['m']=-9999999;
    		$slice['c']=array();

    		foreach ($value as $pt) {
    			if ($pt[1]>$slice['m']) $slice['m']=$pt[1];
    			if ( (!is_numeric($pt[0])) || (!is_numeric($pt[1])) ) return FALSE;
    			$slice['c'][]=array($pt[0],$pt[1]);
    		}

    		$res['h'][]=$slice;
		}

        return $res;
    }





	public function save_coords($coords)
    {

        File::put($this->getDirectory().$this->id.'.json', json_encode($coords));

        return json_encode($coords);
    }











	public function get_coords()
    {
       $contents = File::get($this->getDirectory().$this->id.'.json');

       return json_decode($contents);
    }



    public static function count_with($array_field)
    {


        $count=0;
        $locations = Location::with('blocs');
        foreach ($array_field as $key => $value) 
            $locations = $locations->where( $key,'=',$value );
        
        $locations = $locations->get();

        foreach ($locations as $location) 
            $count+= count($location->blocs);
    
        return $count;
    }



}