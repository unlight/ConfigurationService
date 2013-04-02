<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new ConfigurationServiceProvider('../settings'));

// $app['config']->set('xxx', 33);
$app['config']->set('.debug', '11111111111111');
// $app['config']->set('application.title', '9magXXX');

$x = $app['config']->get('.debug', 0);
$x = $app['config']->get('debug', 0);

var_dump($app['config']->get('pictures.perpage', -1));
var_dump($app['config']->get('costing.pack', -1));

die;

$app->run();