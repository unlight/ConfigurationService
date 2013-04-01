<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new ConfigurationServiceProvider('settings'));

// $app['config']->set('xxx', 33);
$app['config']->set('.debug', '11111111111111');
// $app['config']->set('application.title', '9magXXX');

$app->run();