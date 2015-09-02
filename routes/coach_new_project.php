<?php
    $coach_new_project = $app['controllers_factory'];

    // 1. First page in new project coach flow
    // Don't need to pass Twig any data to display
    $coach_new_project->get('/', function() use ($app) {
        return $app['twig']->render('coach/new_project/1name.html.twig');
    });

    /* The rest of the pages will be a linear sequence of post http methods.
    ** Each does the action from the previous page.
    ** The Twig templates will be responsive to display only the aspects
    ** of a project which have already been defined. */




    /* 2. Create a new project with name from last form.
    ** We don't have an id yet, so can't use it in URL.
    ** Then, display project as is so far. */
    $coach_new_project->post('/motivation', function() use ($app) {
        $name = $_POST['name'];
        $motivation = null;
        $due_date = "0000-00-00";
        $priority = 0;
        $project = new Project($name, $motivation, $due_date, $priority);
        $project->save();

        return $app['twig']->render('coach/new_project/2motivation.html.twig', array(
            'project' => $project
        ));
    });



    /* 3. Add motivation from last form to project
    ** show project as is so far
    ** prompt user to brain dump pre-reqs     */
    $coach_new_project->post('/{id}/prereqs', function($id) use ($app) {

        $project = Project::find($id);


        if (!empty($_POST['motivation'])) {
            $project->updateMotivation($_POST['motivation']);


        } else {

        }

        return $app['twig']->render('coach/new_project/3prereqs.html.twig', array(
            'project' => $project
        ));
    });



    /* 4. store dump from last page
    ** prompt user to parse the dump and

    ** need to check if this is the first step or just a middle step
    ** if user didn't click on "final step", keep going back to this route
    ** until they have made all the steps

    ** as of now, dump is just passed in post and not saved in db*/
    $coach_new_project->post('/{id}/step', function($id) use ($app) {


        $project = Project::find($id);
        $dump = $_POST['dump'];

        // figure out what position this step should go to
        // need logic here!!!!!!!!
        $step_position = 0;

        if (!empty($_POST['step_description'])) {
            $new_step = new Step(
                $_POST['step_description'],
                $project->getId(),
                $step_position
            );
            $new_step->save();
        }

        return $app['twig']->render('coach/new_project/4step.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps(),
            'dump' => $dump
        ));

    });



    /* 5. steps should all be complete now
    ** only go to this page if they said last step was the final step
    ** on this page ask for due date
    */
    $coach_new_project->get('/{id}/due_date', function($id) use ($app) {


        $project = Project::find($id);

        return $app['twig']->render('coach/new_project/5due_date.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });





    /* 6. add due date from previous page
    ** give user option to edit project as they have entered it
    ** this could redirect to pre-existing edit page,
    ** but maybe better to have it integrated into the coach workflow
    */
    $coach_new_project->post('/{id}/update', function($id) use ($app) {

        $project = Project::find($id);

        return $app['twig']->render('coach/new_project/6update.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });



    /* 7. if anything was edited, update it here
    **
    ** display congratulations, redirect to dashboard */
    $coach_new_project->post('/{id}/finished', function($id) use ($app) {

        $project = Project::find($id);

        return $app['twig']->render('coach/new_project/7finished.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });



    // Place all urls in this file at /coach/new_project/*
    $app->mount('/coach/new_project', $coach_new_project);

 ?>
