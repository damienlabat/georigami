<?php

class bloc extends BaseModel
{
    /**
     * enable timestamps
     */
    public static $timestamps = true;

    /**
     * return bloc location
     * @return Location
     */
    public function location()
    {
        return $this->belongs_to('Location');
    }

    /**
     * return saved views
     * @return array
     */
    public function saved_views()
    {
        return $this->has_many('SavedView')
            ->order_by('updated_at', 'desc');
    }

    /**
     * return save directory name
     * @return interger
     */
    public function getDirectoryNum()
    {
        return floor($this->id/100);
    }

    /**
     * return directory
     * @param  string $type directory type (svg-> svg preview  OR data->data json)
     * @return string       directory name
     */
    public function getDirectory($type='data')
    {
        if ($type=='svg') $dir= path('public').'./svg/'.$this->getDirectoryNum() .'/';
        elseif ($type=='png') $dir= path('public').'./png/'.$this->getDirectoryNum() .'/';
            else $dir= path('public').'../data/'.$this->getDirectoryNum() .'/';
        if (!is_dir($dir)) mkdir($dir, 0777);
        return $dir;
    }


    /**
     * return localized created date (autoloaded with presenter)
     * @return string
     */
    public function get_created_at_localized()
    {
        return  date(Lang::line('date.complete')->get(), strtotime($this->created_at));
    }

    /**
     * get url to block view (autoloaded with presenter)
     * @param  ['3d'|'profil'|'print'] $show [description]
     * @return [string]                url
     */
    public function get_url($show=null)
    {
        if ($show==null)
            return URL::to_route('get', array( Str::slug($this->location->name),  $this->location->id, $this->id ));
        else
            return URL::to_route('getplus', array( Str::slug($this->location->name),  $this->location->id, $this->id, $show ));
    }

    /**
     * return profil data
     * @param  string $face N|W|S|E
     * @return array
     */
    public function profil_data($face)
    {
        $data=array(
            'altimin' => $this->min,
            'altimax' => $this->max,
            );

        $coords=$this->get_coords();

        if ($face=='S') $data['coords']=$coords->h;
        if ($face=='N') $data['coords']=array_reverse($coords->h);

        if ($face=='W') $data['coords']=$coords->v;
        if ($face=='E') $data['coords']=array_reverse($coords->v);

        if (($face=='N')||($face=='S')) $data['dim']=$this->width/max($this->height, $this->width);
        else    $data['dim']=$this->height/max($this->height, $this->width);

        $data['max']=0;

        foreach ($data['coords'] as &$slice) {
            $smax=0;
            if ($slice->m > $data['max'])   $data['max']=$slice->m;
            if (($face=='N')||($face=='E')) {
                $slice->c=array_reverse($slice->c);
                foreach ($slice->c as &$cpt) $cpt[0]=$data['dim']-$cpt[0];
            }
        }

        //$data['max']=(floor($data['max']*1000)+1 ) / 1000;
        return $data;
    }

    /**
     * check posted data, get max val ..
     * @param  string $coordsJson posted json data
     * @return array
     */
    public static function validate_coords($coordsJson)
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

            ini_set('memory_limit','512M');

            foreach ($value as $pt) {
                if ($pt[1]>$slice['m']) $slice['m']=$pt[1];
                if ( (!is_numeric($pt[0])) || (!is_numeric($pt[1])) ) return FALSE;
                $slice['c'][]=array($pt[0],$pt[1]);
            }

            $res['h'][]=$slice;
        }

        return $res;
    }

    /**
     * save topo data in data/rep/file.json
     * @param  array $coords data
     * @return string       data json
     */
    public function save_coords($coords)
    {

        File::put($this->getDirectory().$this->id.'.json', json_encode($coords));

        return json_encode($coords);
    }

    /**
     * get coord data (autoloaded with presenter)
     * @return Object
     */
    public function get_coords()
    {
       $contents = File::get($this->getDirectory().$this->id.'.json');
        ini_set('memory_limit','512M');
       return json_decode($contents);
    }

    /**
     * get bloc count in a country or subdiv ..
     * @param  array $array_field select with in array ( fieldname => searchvalue)
     * @return integer              count
     */
    public static function count_with($arrayField)
    {
        $count=0;
        $locations = Location::with('blocs');
        foreach ($arrayField as $key => $value)
            $locations = $locations->where($key, '=', $value);

        $locations = $locations->get();

        foreach ($locations as $location)
            $count+= count($location->blocs);

        return $count;
    }

}
