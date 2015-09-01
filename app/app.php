<?php

    require_once __DIR__."/../vendor/autoload.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();

    $app['debug'] = true;

    // $server = 'mysql:host=localhost;dbname=lifecoach';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password, $DB);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    //Home page
    $app->get('/dashboard', function() use ($app) {
        return $app['twig']->render('dashboard.html.twig');
    });

    return $app;
?>
