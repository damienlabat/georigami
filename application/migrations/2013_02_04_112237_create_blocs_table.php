<?php

class Create_Blocs_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{


        Schema::create('locations',function($table){
            
            $table->increments('id')->unsigned();

            $table->decimal('lat',18,14);
            $table->decimal('lng',18,14);

            $table->decimal('distance',8,5)->unsigned();

            $table->integer('geonameId');
 
            $table->string('countryName');
            $table->string('adminCode1');
            $table->string('fclName');
            $table->string('countryCode');
            $table->string('fcodeName');
            $table->string('toponymName');
            $table->string('fcl');
            $table->string('name');
            $table->string('fcode');            
            $table->string('adminName1');
            $table->string('feature');
            $table->string('featureDetail');

            $table->timestamps();
        });


        Schema::create('blocs',function($table){
            
            $table->increments('id')->unsigned();

            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();

            $table->float('min');
            $table->float('max');

            $table->integer('hSlices')->unsigned();
            $table->integer('vSlices')->unsigned();

            $table->integer('hSamples')->unsigned();
            $table->integer('vSamples')->unsigned();

            $table->string('bbox'); // virer ?
            $table->integer('location_id')->unsigned();

            $table->foreign('location_id')->references('id')->on('locations'); 
            
            $table->timestamps();
        });

    }

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('blocs');        
		Schema::drop('locations');

	}

}