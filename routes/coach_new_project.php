<?php
    $coach_new_project = $app['controllers_factory'];


    // FYI Right now this coach workflow doesn't deal with priority at all....




    /* 1. First page in new project coach flow.
    ** We don't need to pass Twig any data to display yet.
    ** The rest of the pages will be a linear sequence of post http methods.
    ** Each does the action from the previous page.
    ** The Twig templates will be responsive to display only the aspects
    ** of an in-progress project which have already been defined. */
    $coach_new_project->get('/', function() use ($app) {
        return $app['twig']->render('coach/new_project/1name.html.twig');
    });



    /* 2. Create a new project with name from last form and dummy values for other properties.
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



    /* 3. Add motivation from last form to project.
    ** Show project as is so far.
    ** Prompt user to brain dump pre-reqs.     */
    $coach_new_project->post('/{id}/prereqs', function($id) use ($app) {
        $project = Project::find($id);

        if (!empty($_POST['motivation'])) {
            $project->updateMotivation($_POST['motivation']);
        } else {
            // some kind of error here
        }

        return $app['twig']->render('coach/new_project/3prereqs.html.twig', array(
            'project' => $project
        ));
    });



    /* 4. Add a new step if we have $_POST for it at the appropriate position.
    ** Display the user's braindump so they can refer to it when creating steps.
    ** Store dump from last page by passing it as a secret input in the form. */
    $coach_new_project->post('/{id}/step', function($id) use ($app) {
        $project = Project::find($id);
        $dump = $_POST['dump'];
        $steps = $project->getSteps();

        // If we have a $_POST for the step description, add it now.
        if (!empty($_POST['step_description'])) {

            $step_position = 1;
            if (sizeof($steps) == 0) {
                // There are no steps yet. This step should be added at pos 1.
                $step_position = 1;
            } else {
                // If we have 3 steps, should add new one at pos 4.
                // Start counting steps from 1.
                $step_position = sizeof($steps) + 1;
            }

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



    /* 5. Steps should all be complete now.
    ** We only go to this page if user said they are done adding steps.
    ** Here we don't take any $_POST data, just ask for due_date and
    ** process that on the next page. */
    $coach_new_project->get('/{id}/due_date', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/new_project/5due_date.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });





    /* 6. Add due date from previous page.
    ** Give user option to edit the project as they have entered it. */
    $coach_new_project->post('/{id}/update', function($id) use ($app) {
        $project = Project::find($id);
        $project->updateDueDate($_POST['due_date']);

        return $app['twig']->render('coach/new_project/6update.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });



    /* 7. If anything was edited, update it here.
    ** Display congratulations, redirect to dashboard. */
    $coach_new_project->post('/{id}/finished', function($id) use ($app) {
        $project = Project::find($id);

        // logic to do updating here

        return $app['twig']->render('coach/new_project/7finished.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });



    // Place all urls in this file at /coach/new_project/*
    $app->mount('/coach/new_project', $coach_new_project);

 ?>
