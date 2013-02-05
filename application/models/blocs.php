<?php

class Blocs extends Eloquent {

	public static $timestamps = true;



	private function getDirectory() {

		$dir= path('public').'../data/'.floor( $this->id/100 ).'/';
		if (!is_dir($dir)) mkdir($dir, 0777);
		return $dir;
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



    public function update_geoname()
    {

        File::put($this->getDirectory().$this->id.'.json', json_encode($coords));

        return json_encode($coords);
    }





	public function get_coords()
    {
       $contents = File::get($this->getDirectory().$this->id.'.json');

       return json_decode($contents);
    }



}