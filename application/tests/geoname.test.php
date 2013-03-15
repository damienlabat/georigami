<?php

class TestGeoname extends PHPUnit_Framework_TestCase
{
    public function testFindTheBest()
    {
        for ($i=0; $i < 1; $i++) {
            $lat= rand(-90*1000,90*1000)/1000;
            $lng= rand(-180*1000,180*1000)/1000;
            $res= Geoname::findTheBest(  $lat, $lng );
            echo $res['name'].PHP_EOL;
            $this->assertTrue( isset($res['name']) );
        }

    }

    public function testSearch()
    {
        $q='something';
        $res=Geoname::search(  $q, 'T', 10, 'short' );
        $this->assertTrue( $res!==null );
    }

}
