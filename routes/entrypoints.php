<?php
/**
 * @var \Laravel\Lumen\Routing\Router $router
 */

$router->get('/click/',                       'ClickController@click');

$router->get('/bad_domains/',                 'BadDomainController@getList');

$router->post('/bad_domains/',                'BadDomainController@post');

$router->delete('/bad_domains/{badDomainId}', 'BadDomainController@delete');