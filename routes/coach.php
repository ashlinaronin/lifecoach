<?php
    $coach = $app['controllers_factory'];

    // First page in new project coach flow
    // Don't need to pass Twig any data to display
    $coach->get('/', function() use ($app) {
        return $app['twig']->render('coach/new_project/name.html.twig');
    });

    /* The rest of the pages will be a linear sequence of post http methods.
    ** Each does the action from the previous page.
    ** The Twig templates will be responsive to display only the aspects
    ** of a project which have already been defined. */
    $coach->post('/motivation', function() use ($app) {
        //create new project with name from last form
        // We don't have an id yet, so can't use it in URL.
        // show project as is so far
        $name = $_POST['name'];
        $motivation = null;
        $due_date = null;
        $priority = null;
        $project = new Project($name, $motivation, $due_date, $priority);
        $project->save();

        return $app['twig']->render('coach/new_project/motivation.html.twig', array(
            'project' => $project
        ));
    });

    $coach->post('/{id}/prereqs', function($id) use ($app) {
        //add motivation from last form to project
        // show project as is so far
        // prompt user to brain dump pre-reqs
        $project = Project::find($id);

        if (!empty($_POST['motivation'])) {
            $project->updateMotivation($_POST['motivation']);
        } else {
            // Some kind of error here
        }

        return $app['twig']->render('coach/new_project/prereqs.html.twig', array(
            'project' => $project
        ));
    });

    $coach->post('/{id}/step', function($id) use ($app) {
        //store dump from last page
        //prompt user to parse the dump and

        // need to check if this is the first step or just a middle step
        // if user didn't click on "final step", keep going back to this route
        // until they have made all the steps

        // as of now, dump is just passed in post and not saved in db

        $project = Project::find($id);
        $dump = $_POST['dump'];

        if (!empty($_POST['step_description'])) {
            // this method doesn't exist yet, need to implement it
            $project->addStep();
        }

        return $app['twig']->render('coach/new_project/prereqs.html.twig', array(
            'project' => $project,
            'dump' => $dump
        ));

    });

    $coach->get('/{id}/due_date', function($id) use ($app) {
        // steps should all be complete now
        // only go to this page if they said last step was the final step
        // on this page ask for due date

        $project = Project::find($id);

        return $app['twig']->render('coach/new_project/due_date.html.twig', array(
            'project' => $project
        ));
    });


    $coach->post('/{id}/update', function($id) use ($app) {
        //add due date from previous page
        //give user option to edit project as they have entered it
        // this could redirect to pre-existing edit page,
        // but maybe better to have it integrated into the coach workflow
        $project = Project::find($id);

        return $app['twig']->render('coach/new_project/update.html.twig', array(
            'project' => $project
        ));
    });

    $coach->post('/{id}/finished', function($id) use ($app) {
        // if anything was edited, update it here

        // display congratulations, redirect to dashboard
        $project = Project::find($id);

        return $app['twig']->render('coach/new_project/finished.html.twig', array(
            'project' => $project
        ));
    });

    $








    // Place all urls in this file at /coach/*
    $app->mount('/coach', $coach);

 ?>
