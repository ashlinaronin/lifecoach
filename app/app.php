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

    $app->get('/new_journal_entry', function() use ($app) {
        $time_zone = date_default_timezone_set('America/Los_Angeles');
        $todays_date = date("M-d-Y");
        $entries = Journal::getAll();
        $existing_entry = "";

        foreach($entries as $entry) {
            if ($entry->getDate() == date("Y-m-d")) {
                $existing_entry .= $entry->getContent();
            }
        }
            return $app['twig']->render('new_journal_entry.html.twig', array('date' => $todays_date, 'existing_entry' => $existing_entry));
    });

    $app->post('/save_journal_entry', function() use ($app) {
        $entry = $_POST['entry'];
        $time_zone = date_default_timezone_set('America/Los_Angeles');
        $todays_date = date("M-d-Y");

        $entry_date = date("Y-m-d");
        $todays_entry = new Journal($entry, $entry_date);
        $todays_entry->save();
        if ($todays_entry->getDate() == date("Y-m-d")) {
            $existing_entry = $todays_entry->getContent();
        }
        return $app['twig']->render('new_journal_entry.html.twig', array('date' => $todays_date, 'existing_entry' => $existing_entry, 'todays_entry' => $todays_entry));
    });

    return $app;
?>
