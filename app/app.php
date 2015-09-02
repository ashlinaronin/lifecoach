<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Habit.php";
    require_once __DIR__."/../src/Project.php";
    require_once __DIR__."/../src/Journal.php";
    require_once __DIR__."/../src/Step.php";

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=lifecoach';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));


    // Function for escaping special characters on form input
    function formatFormInput ($input_array)
    {
        $output_array = array();
        foreach($input_array as $key => $value) {
            $output_array[$key] = preg_quote($value, "'");
        }
        return $output_array;
    }


    // Routes ================================================================

    //Home page
    $app->get('/dashboard', function() use ($app) {
        return $app['twig']->render('dashboard.html.twig');
    });









    // Include Coach Routes
    require_once __DIR__."/../routes/coach.php";
    require_once __DIR__."/../routes/habit.php";
    require_once __DIR__."/../routes/journal.php";
    require_once __DIR__."/../routes/project.php";



    return $app;
?>
