<?php
    $project = $app['controllers_factory'];

    // Project page routes ===================================================

    $project->get('/current_projects', function() use ($app) {
        return $app['twig']->render('project/current_projects.html.twig',
            array('projects' => Project::getAll()));
    });

    $project->get('/new_project', function() use ($app) {
        return $app['twig']->render('project/new_project.html.twig');
    });

    // ADD Project
    $project->post('/new_project', function($id) use ($app) {
        $new_project_input = formatFormInput($_POST);
        $new_project = new Project( $new_project_input['name'],
                                    $new_project_input['motivation'],
                                    $new_project_input['due_date'],
                                    $new_project_input['position']
                                  );
        $new_project->save();

        return $app['twig']->render('project/new_project.html.twig', array('projects' => Project::getAll()));
    });


    // $project->get('/project/{id}', function($id) use ($app) {
    //     $project = Project::find($id);
    //
    //     return $app['twig']->render('project/project.html.twig',
    //         array('project' => $project, 'steps' => Project::getSteps()));
    // });

    //
    $project->patch('/project/{id}', function($id) use ($app) {
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

        return $app['twig']->render('project/project.html.twig',
            array('project' => $project, 'steps' => $project->getSteps())
        );
    });

    // Delete Project
    $project->delete('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        $project->delete();

        return $app['twig']->render('project/current_projects.html.twig', array('projects' => Project::getAll() ));
    });



    // Add Step
    $project->post('/project/{id}', function($id) use ($app) {
        $project = Project::find($id);
        $step_input = formatFormInput($_POST);
        $new_step = new Step( $step_input['description'], $id, $step_input['position']);

        return $app['twig']->render('project/project.html.twig',
            array('project' => $project, 'steps' => $project->getSteps())
        );
    });







    // Place all urls in this file at /project/*
    $app->mount('/project', $project);

 ?>
