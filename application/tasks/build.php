<?php

class Build_Task {



	public function run($arguments)
	{
        
        self::blocs($arguments);
        self::view($arguments);
        self::blocs_png($arguments);
        self::view_png($arguments);

        echo PHP_EOL.'DONE!'.PHP_EOL;
	}

     




    public function blocs($arguments)
    {
        $force= in_array('force', $arguments);

    	$blocs=Bloc::all();
    	$n=0;

        $dir= path('public').'./svg/';
        if (!is_dir($dir)) mkdir($dir, 0777);

    	foreach ($blocs as $bloc) {
    		$n++;
    		echo "rebuild SVG bloc " . $n .' / ' . count($blocs) . "\r";

            
        	if ( (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'N.svg')) || ($force) ) profil::save_svg($bloc, 'N');
        	if ( (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'E.svg')) || ($force) ) profil::save_svg($bloc, 'E');
        	if ( (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'W.svg')) || ($force) ) profil::save_svg($bloc, 'W');
        	if ( (!file_exists($bloc->getDirectory('svg').'bloc'.$bloc->id.'S.svg')) || ($force) ) profil::save_svg($bloc, 'S');
  

    		ob_flush();
    	}    	

    	echo PHP_EOL;
    
    }







    public function blocs_png($arguments)
    {
        $force= in_array('force', $arguments);

        $blocs=Bloc::all();
        $n=0;

        $dir= path('public').'./png/';
        if (!is_dir($dir)) mkdir($dir, 0777);

        foreach ($blocs as $bloc) {
            $n++;
            echo "rebuild PNG bloc " . $n .' / ' . count($blocs) . "\r";

            
            if ( (!file_exists($bloc->getDirectory('png').'bloc'.$bloc->id.'N_'.Str::slug($bloc->location->name).'.png')) || ($force) ) 
                exec('inkscape -z -e '.$bloc->getDirectory('png').'bloc'.$bloc->id.'N_'.Str::slug($bloc->location->name).'.png -w 256 '.$bloc->getDirectory('svg').'bloc'.$bloc->id.'N.svg'); 

            if ( (!file_exists($bloc->getDirectory('png').'bloc'.$bloc->id.'E_'.Str::slug($bloc->location->name).'.png')) || ($force) ) 
                exec('inkscape -z -e '.$bloc->getDirectory('png').'bloc'.$bloc->id.'E_'.Str::slug($bloc->location->name).'.png -w 256 '.$bloc->getDirectory('svg').'bloc'.$bloc->id.'E.svg'); 
            
             if ( (!file_exists($bloc->getDirectory('png').'bloc'.$bloc->id.'W_'.Str::slug($bloc->location->name).'.png')) || ($force) ) 
                exec('inkscape -z -e '.$bloc->getDirectory('png').'bloc'.$bloc->id.'W_'.Str::slug($bloc->location->name).'.png -w 256 '.$bloc->getDirectory('svg').'bloc'.$bloc->id.'W.svg'); 
            
             if ( (!file_exists($bloc->getDirectory('png').'bloc'.$bloc->id.'S_'.Str::slug($bloc->location->name).'.png')) || ($force) ) 
                exec('inkscape -z -e '.$bloc->getDirectory('png').'bloc'.$bloc->id.'S_'.Str::slug($bloc->location->name).'.png -w 256 '.$bloc->getDirectory('svg').'bloc'.$bloc->id.'S.svg'); 
                                     
            ob_flush();
        }       

        echo PHP_EOL;    
    
    }






    public function view($arguments)
    {
        $force= in_array('force', $arguments);

    	$views=Savedview::all();
    	$n=0;

        $dir= path('public').'./svg/view/';
        if (!is_dir($dir)) mkdir($dir, 0777);

    	foreach ($views as $saved) {
    		$n++;
    		echo "rebuild SVG saved view " . $n .' / ' . count($views) . "\r";

            if ( (!file_exists($saved->getDirectory('svg').'view'.$saved->id.'.svg')) || ($force)) {

        		$params=json_decode($saved->params, true);
        		profil::save_view($saved->bloc, $saved->bloc->profil_data($params['face']), $params);    

            }

    		ob_flush();
    	}    

    	echo PHP_EOL;
    
    }







    public function view_png($arguments)
    {
        $force= in_array('force', $arguments);

        $views=Savedview::where('show', '=', true)->get();        
        $n=0;

        $dir= path('public').'./png/view/';
        if (!is_dir($dir)) mkdir($dir, 0777);

        foreach ($views as $saved) {
            $n++;
            echo "rebuild PNG view " . $n .' / ' . count($views) . "\r";

            if ( (!file_exists($saved->getDirectory('png').'view'.$saved->id.'_'.Str::slug($saved->bloc->location->name).'.png')) || ($force)) {

                $params=json_decode($saved->params, true);
                $params['header']=      true;
                $params['crop']=      false;
                $params['reduce']=      false;
                $svg= profil::profil_svg($saved->bloc->profil_data($params['face']), $params);
                File::put( path('public').'./png/view/temp.svg', $svg );
               
                exec('inkscape -z -e '.$saved->getDirectory('png').'view'.$saved->id.'_'.Str::slug($saved->bloc->location->name).'.png -w 2048 '.path('public').'./png/view/temp.svg');                       
            }

            ob_flush();
        }    

        echo PHP_EOL;
    
    }
     
     


     
}