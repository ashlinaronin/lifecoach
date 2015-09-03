<?php
    $project = $app['controllers_factory'];



    // Function for escaping special characters on form input
    function formatFormInput ($input_array)
    {
        $output_array = array();
        foreach($input_array as $key => $value) {
            $output_array[$key] = preg_quote($value, "'");
        }
        return $output_array;
    }



    // Project page routes ===================================================


    // List all current Projects
    $project->get('/current_projects', function() use ($app) {
        return $app['twig']->render('project/current_projects.html.twig',
            array('projects' => Project::getAll()));
    });


    // Display new Project input page
    $project->get('/new_project', function() use ($app) {
        return $app['twig']->render('project/new_project.html.twig');
    });


    // Add Project
    $project->post('/new_project', function() use ($app) {
        $new_project_input = formatFormInput($_POST);
        $new_project = new Project( $new_project_input['name'],
                                    $new_project_input['motivation'],
                                    $new_project_input['due_date'],
                                    $new_project_input['priority']
                                  );
        $new_project->save();
        $new_project_id = $new_project->getId();
        return $app['twig']->render('project/project.html.twig',
            array('project' => Project::find($new_project_id), 'steps' => $new_project->getSteps()));
    });


    // Display a single Project page
    $project->get('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        return $app['twig']->render('project/project.html.twig',
            array('project' => $project, 'steps' => $project->getSteps()));
    });

    // Update a singel Project and re-diplay the page
    $project->patch('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        if(!empty($new_name = $_POST['name'])) {
            $project->updateName(preg_quote($new_name,"'"));
        }
        if(!empty($new_motivation = $_POST['motivation'])) {
            $project->updateMotivation(preg_quote($new_motivation));
        }
        if(!empty($new_due_date = $_POST['due_date'])) {
            $project->updateDueDate(preg_quote($new_due_date));
        }
        if(!empty($new_priority = $_POST['priority'])) {
            $project->updatePriority(preg_quote($new_priority));
        }
        return $app['twig']->render('project/project.html.twig',
            array('project' => $project, 'steps' => $project->getSteps())
        );
    });

    // Delete a single Project and display current Projects list page
    $project->delete('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        $project->delete();
        return $app['twig']->render('project/current_projects.html.twig', array('projects' => Project::getAll() ));
    });




    // Step page routes =====================================================


    // Add Step
    $project->post('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        $step_input = formatFormInput($_POST);
        $new_step = new Step( $step_input['description'], $id, $step_input['position']);
        return $app['twig']->render('project/project.html.twig',
            array('project' => $project, 'steps' => $project->getSteps())
        );
    });

    // $project->patch('/project/{id}', function($id) use ($app) {
    //     $project = Project::find($id);
    //
    //
    //
    // })







    // Place all urls in this file at /project/*
    $app->mount('/project', $project);

 ?>
