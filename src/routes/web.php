<?php
/**
 * @var \Laravel\Lumen\Routing\Router $router
 */

$router->get('/success/{clickId}', "ClickController@success");

$router->get('/error/{clickId}',   "ClickController@error");

$router->get('/',                  "ClickController@getList");

$router->get('/bad_domains/',      "BadDomainController@getFrontList");