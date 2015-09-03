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

    $server = 'mysql:host=localhost:3306;dbname=lifecoach';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


    // Create default Project for all users for adding Chores
    // populate it with (description,motivation,due_date,priority)
    if(empty(Project::getAll())) {
        $default_project = new Project("Chores",null,"0000-00-00",0);
        $default_project->save();
    }


    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));


    //Home page
    $app->get('/', function() use ($app){
      return $app['twig']->render('index.html.twig');
    });

    $app->get('/dashboard', function() use ($app) {
        return $app['twig']->render('dashboard.html.twig');
    });





    // Include Other Routes
    require_once __DIR__."/../routes/coach_new_project.php";
    require_once __DIR__."/../routes/coach_active_project.php";
    require_once __DIR__."/../routes/coach_new_habit.php";
    require_once __DIR__."/../routes/habit.php";
    require_once __DIR__."/../routes/journal.php";
    require_once __DIR__."/../routes/project.php";



    return $app;
?>
