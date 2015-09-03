<?php
    $coach_active_project = $app['controllers_factory'];

    /* 1. First page in active project coach flow.
    ** Display progress & positive reinforcement. */
    $coach_active_project->get('/{id}', function($id) use ($app) {
        $project = Project::find($id);
        return $app['twig']->render('coach/active_project/1progress.html.twig', array(
            'project' => $project
        ));
    });

    /* 2. Show next un-completed step in this project.
    ** Allow user to check it off or choose that they don't have time.
    ** Ask user, are you confident that you can complete this step today? */
    $coach_active_project->get('/{id}/next_step', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/2next_step.html.twig', array(
            'project' => $project
        ));
    });


    /* 3. Give user option to re-order steps.
    ** How does that look? Do you feel confident you can complete the first step now?
    ** If no, link to add a step. If yes, link to /{id}/next_step.
    ** This route will handle its own post data.
    **/
    $coach_active_project->get('/{id}/reorder_steps', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/3reorder_steps.html.twig', array(
            'project' => $project
        ));
    });


    /* 4. Give user option to add steps.
    ** "Does your project still seem daunting? Maybe you haven't broken
    ** it into small enough steps. Try adding another step or two." */
    $coach_active_project->post('/{id}/add_step', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/4add_step.html.twig', array(
            'project' => $project
        ));
    });



    /* 5. Great. Glad you feel confident you can complete this step.
    ** Tell me when you've finished it. Wait....
    ** Let user choose if they'd like to see the next step -> /next_step
    ** or had enough for the day -> /enough
    */
    $coach_active_project->get('/{id}/complete', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/5complete.html.twig', array(
            'project' => $project
        ));
    });

    // Duplicate for post, sent here if user checks that they have finished step
    // on get page
    $coach_active_project->post('/{id}/complete', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/5complete.html.twig', array(
            'project' => $project
        ));
    });


    /* 6. Enough for today. More positive reinforcement, progress bar.
    ** Redirect to dashboard.
    **
    */
    $coach_active_project->post('/{id}/enough', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/6enough.html.twig', array(
            'project' => $project
        ));
    });






    // Place all urls in this file at /coach/active_project/*
    $app->mount('/coach/active_project', $coach_active_project);

 ?>
