<?php

class Create_Blocs_Table
{
    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('locations',function($table){

            $table->increments('id')->unsigned();

            $table->decimal('lat', 18, 14);
            $table->decimal('lng', 18, 14);

            $table->integer('geonameId');

            $table->string('name');

            $table->string('adminName1');
            $table->string('adminName2');
            $table->string('adminName3');
            $table->string('adminName4');

            $table->string('countryCode', 2);

            $table->string('fcl', 1);
            $table->string('fcode', 5);
            $table->string('continentCode', 2);

            $table->timestamps();
        });

        Schema::create('blocs',function($table){

            $table->increments('id')->unsigned();

            $table->decimal('lat',18,14);
            $table->decimal('lng',18,14);

            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();

            $table->integer('rotate');

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



        Schema::create('savedviews',function($table){

            $table->increments('id')->unsigned();

            $table->integer('bloc_id')->unsigned();
            $table->foreign('bloc_id')->references('id')->on('blocs');
            $table->string('params');

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
        Schema::drop('savedviews');
        Schema::drop('blocs');
        Schema::drop('locations');

    }

}
