<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Project.php";
    require_once __DIR__."/../src/Step.php";


    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=lifecoach';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password, $DB);

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


    // SILEX Routes ==========================================================

    //Home page
    $app->get('/dashboard', function() use ($app) {
        return $app['twig']->render('dashboard.html.twig');
    });



    // Project page routes ===================================================

    $app->get('/current_projects', function() use ($app) {
        return $app['twig']->render('current_projects.html.twig',
            array('projects' => Project::getAll());
    });

    // ADD Project
    $app->post('/current_projects/{id}', function($id) use ($app) {
        $new_project_input = formatFormInput($_POST);
        $new_project = new Project( $new_project_input['name'],
                                    $new_project_input['motivation'],
                                    $new_project_input['due_date'],
                                    $new_project_input['position']
                                  );
        $new_project->save();

        return $app['twig']->render('project.html.twig', array('projects' => Project::getAll());
    });


    $app->get('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('project.html.twig',
            array('project' => $project, 'steps' => Project::getSteps());
    });

    //
    $app->patch('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        if(!empty($new_name = $_POST['name'])) {
            $project->updateName(preg_quote($new_name,"'"));
        }
        if(!empty($new_motivation = $_POST['motivation'])) {
            $project->updateName(preg_quote($new_motivation));
        }
        if(!empty($new_due_date = $_POST['due_date'])) {
            $project->updateName(preg_quote($new_due_date));
        }
        if(!empty($new_priority = $_POST['priority'])) {
            $project->updateName(preg_quote($new_priority));
        }

        return $app['twig']->render('project.html.twig',
            array('project' => $project, 'steps' => $project->getSteps())
        );
    });

    // Delete Project
    $app->delete('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        $project->delete();

        return $app['twig']->render('current_projects.html.twig', array('projects' => Project::getAll() ));
    });



    // Add Step
    $app->post('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        $step_input = formatFormInput($_POST);
        $new_step = new Step( $step_input['description'], $id, $step_input['position']);

        return $app['twig']->render('project.html.twig'),
            array('project' => $project, 'steps' => $project->getSteps())
        );
    });





    return $app;
?>
