<?php
    $coach = $app['controllers_factory'];

    $coach->get('/', function() use ($app) {
        return $app['twig']->render('dashboard.html.twig');
    });





    // Place all urls in this file at /coach/*
    $app->mount('/coach', $coach);

 ?>
