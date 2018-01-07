<?php
/**
 * @var \Laravel\Lumen\Routing\Router $router
 */

$router->get(   '/click/',                      'ClickController@click');

$router->post(  '/bad_domains/',                'BadDomainController@post');

$router->delete('/bad_domains/{badDomainId}',   'BadDomainController@delete');

$router->get(   '/bad_domains/{badDomainId}',   'BadDomainController@get');