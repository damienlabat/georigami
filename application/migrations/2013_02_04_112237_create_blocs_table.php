<?php

class Create_Blocs_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blocs',function($table){
            
            $table->increments('id');

            $table->integer('width');
            $table->integer('height');

            $table->float('lat');
            $table->float('lng');

            $table->float('min');
            $table->float('max');

            $table->integer('hSlices');
            $table->integer('vSlices');

            $table->integer('hSamples');
            $table->integer('vSamples');

            $table->string('geoname');
            $table->string('bbox');
            
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
	}

}