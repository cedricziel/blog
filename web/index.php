<?php

include(__DIR__.'/../vendor/autoload.php');

$app = new \CedricZiel\Blog\BlogApplication();

$app['debug'] = true;

$app->register(new \CedricZiel\Blog\Provider\BlogApplicationServiceProvider());

$app->run();
