<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/



Route::get('/', function(){
        return View::make('welcome_'.Config::get('application.language'));
     });


Route::get('/last', array(
        'as'     => 'index',
        'uses'   => 'bloc@index'
     ));


Route::get('/saved', array(
        'as'     => 'saved',
        'uses'   => 'bloc@saved'
     ));

Route::get('/saved/(:any)_(:num)', array(
        'as'     => 'saved_show',
        'uses'   => 'bloc@saved_show'
     ));






Route::get('/map', array(
        'as'     => 'map',
        'uses'   => 'bloc@map'
     ));





Route::get('/new', array('as' => 'new', function() {
    $lat=       Input::get('lat',       0);
    $lng=       Input::get('lng',       0);
    $width=     Input::get('width',     5000);
    $height=    Input::get('height',    5000);
    $rotate=    Input::get('rotate',    0);

    $hslices=   Input::get('hslices',   9);
    $vslices=   Input::get('vslices',   9);
    $hsampling=  Input::get('hsampling',  3);
    $vsampling=  Input::get('vsampling',  3);



    $data=array(
        'lat'       =>$lat,
        'lng'       =>$lng,
        'width'     =>$width,
        'height'    =>$height,
        'rotate'    =>$rotate,

        'hSlices'   =>$hslices,
        'vSlices'   =>$vslices,
        'hsampling' =>$hsampling,
        'vsampling' =>$vsampling,

        );

    return View::make('new')->with($data);
}));




Route::POST('/new', array(
        'as'     => 'store',
        'uses'   => 'bloc@store'
     ));




Route::get('/(:any)_(:num)', array(
        'as'     => 'location',
        'uses'   => 'bloc@location'
     ));






Route::get('/(:any)_(:num)/bloc(:num)', array(
        'as'     => 'get',
        'uses'   => 'bloc@get'
     ));

Route::get('/(:any)_(:num)/bloc(:num)_(profil|print|3d|download)', array(
        'as'     => 'getplus',
        'uses'   => 'bloc@get'
     ));





Route::get('/bloc(:num).json', array(
        'as'     => 'getJson',
        'uses'   => 'bloc@getJson'
     ));






Route::POST('/search', array(
        'as'     => 'search',
        'uses'   => 'geoname@search'
     ));





/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function() {
    return Response::error('404');
});

Event::listen('500', function() {
    return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function() {
    // Do stuff before every request to your application...
});

Route::filter('after', function($response) {
    // Do stuff after every request to your application...
});

Route::filter('csrf', function() {
    if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function() {
    if (Auth::guest()) return Redirect::to('login');
});
