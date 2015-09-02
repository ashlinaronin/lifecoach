<?php
    $coach_active_project = $app['controllers_factory'];

    // First page in active project coach flow
    // Display progress & positive reinforcement
    $coach_active_project->get('/{id}', function($id) use ($app) {
        $project = Project::find($id);
        return $app['twig']->render('coach/active_project/progress.html.twig', array(
            'project' => $project
        ));
    });

    // Show next step
    // Allow user to check it off or choose that they don't have time
    $coach_active_project->post('/{id}/next_step', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/next_step.html.twig', array(
            'project' => $project
        ));
    });

    // ask user if they are confident they can complete the step today
    //
    $coach_active_project->post('/{id}/today', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/today.html.twig', array(
            'project' => $project
        ));
    });

    $coach_active_project->post('/{id}/confidence', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/confidence.html.twig', array(
            'project' => $project
        ));
    });









    // Place all urls in this file at /coach/new_project/*
    $app->mount('/coach/active_project', $coach_active_project);

 ?>
