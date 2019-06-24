<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function (){
    return 'The things that still do not have number, is because they have not been discovered';
});

//get all planets
$router->get( '/api/planets', 'PlanetController@getAll' );

//converter a star api in JSON, and formatted all values to my API.
$router->post( '/api/converter', 'PlanetController@converterJsonfile' );

$router->group(['prefix' => '/api/planet'], function() use ($router){

    //get planet by Id and Name
    $router->get( '/{term}', 'PlanetController@getSingle' );
    //add a planet
    $router->post( '/', 'PlanetController@store' );
    //delete a planet
    $router->delete( '/{id}', 'PlanetController@destroy' );

});
