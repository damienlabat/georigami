<?php

class Build_Task {

	public function run($arguments)
	{
		echo "help:".PHP_EOL.PHP_EOL;
		echo "php artisan build:blocs --env='local'".PHP_EOL;
		echo "php artisan build:view  --env='local'".PHP_EOL.PHP_EOL;
		echo "php artisan build:blocs --env='server'".PHP_EOL;
		echo "php artisan build:view  --env='server'".PHP_EOL.PHP_EOL;
	}

     
    public function blocs($arguments)
    {

    	$blocs=Bloc::all();
    	$n=0;

    	foreach ($blocs as $bloc) {
    		$n++;
    		echo "rebuild SVG bloc " . $n .' / ' . count($blocs) . "\r";

    		profil::save_svg($bloc, 'N');
    		profil::save_svg($bloc, 'E');
    		profil::save_svg($bloc, 'W');
    		profil::save_svg($bloc, 'S');
    		ob_flush();
    	}    	

    	echo PHP_EOL.'DONE !';
    
    }


    public function view($arguments)
    {

    	$views=Savedview::all();
    	$n=0;

    	foreach ($views as $saved) {
    		$n++;
    		echo "rebuild SVG saved view " . $n .' / ' . count($views) . "\r";

    		$params=json_decode($saved->params, true);
    		profil::save_view($saved->bloc, $saved->bloc->profil_data($params['face']), $params);

    		ob_flush();
    	}    

    	echo PHP_EOL.'DONE !';
    
    }
     
     
}