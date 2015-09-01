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

    //Home page
    $app->get('/dashboard', function() use ($app) {
        return $app['twig']->render('dashboard.html.twig');
    });

    $app->get('/current_habits', function() use ($app) {
        return $app['twig']->render('current_habits.html.twig', array('habits' => Habit::getAll()));
    });

    $app->post('/current_habits', function() use ($app){
        $name = $_POST['name'];
        $motivation = $_POST['motivation'];
        $interval_days = $_POST['interval_days'];
        $habit = new Habit($name, $motivation, $interval_days, $id = null);
        $habit->save();
        return $app['twig']->render('current_habits.html.twig', array('habits' => Habit::getAll()));
    });

    $app->get('/current_projects', function() use ($app) {
        return $app['twig']->render('current_projects.html.twig', array('projects' => Project::getAll()));
    });

    $app->get('/new_habit', function() use ($app) {
      return $app['twig']->render('new_habit.html.twig');
    });


    // Include Coach Routes
    require_once __DIR__."/../routes/coach.php";


    return $app;
?>
