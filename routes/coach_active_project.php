<?php
    $coach_active_project = $app['controllers_factory'];


    /* If user somehow accidentally went to the url /coach/active_project/
    ** without a project id, just show the current projects page. */
    $coach_active_project->get('/', function() use ($app) {
        return $app['twig']->render('project/current_projects.html.twig', array(
            'projects' => Project::getAll()
        ));
    });



    /* 1. First page in active project coach flow.
    ** Display progress & positive reinforcement. */
    $coach_active_project->get('/{id}', function($id) use ($app) {
        $project = Project::find($id);

        $all_steps_count = sizeof($project->getSteps());

        if ($all_steps_count != 0) {
            $incomplete_steps_count = sizeof($project->getIncompleteSteps());
            $complete_steps_count = $all_steps_count - $incomplete_steps_count;
            $progress_percent = (int) (($complete_steps_count / $all_steps_count) * 100);
        } else {
            // If there are no steps, then progress percent is definitely 0.
            $progress_percent = 0;
        }

        return $app['twig']->render('coach/active_project/1progress.html.twig', array(
            'project' => $project,
            'progress_percent' => $progress_percent
        ));
    });

    /* 2. Show next un-completed step in this project.
    ** Allow user to check it off or choose that they don't have time.
    ** Ask user, are you confident that you can complete this step today? */
    $coach_active_project->get('/{id}/next_step', function($id) use ($app) {
        $project = Project::find($id);

        // If we got some updated step position values from JS, use 'em'
        if (!empty($_GET)) {
            foreach($_GET as $step_id => $new_position) {
                $step = Step::find($step_id);
                $step->updatePosition($new_position);
            }
        }

        // Something about Rick's next step method is messing up.
        // So we bypass it in a hacky way.
        // Set order by in get incomplete steps.
        $next_step = $project->getIncompleteSteps()[0];


        // get percent complete on this project
        $all_steps_count = sizeof($project->getSteps());
        $incomplete_steps_count = sizeof($project->getIncompleteSteps());
        $complete_steps_count = $all_steps_count - $incomplete_steps_count;
        $progress_percent = (int) (($complete_steps_count / $all_steps_count) * 100);

        return $app['twig']->render('coach/active_project/2next_step.html.twig', array(
            'project' => $project,
            'next_step' => $next_step,
            'progress_percent' => $progress_percent
        ));
    });


    /* 3. Give user option to re-order steps.
    ** How does that look? Do you feel confident you can complete the first step now?
    ** If no, link to add a step. If yes, link to /{id}/next_step.
    ** This route will handle its own post data.
    **/
    $coach_active_project->get('/{id}/reorder_steps', function($id) use ($app) {
        $project = Project::find($id);

        // Update button now links to next step page for clarity
        // So we're not using this logic for now
        // // Get updated step position values from JS
        // foreach($_GET as $step_id => $new_position) {
        //     $step = Step::find($step_id);
        //     $step->updatePosition($new_position);
        // }

        return $app['twig']->render('coach/active_project/3reorder_steps.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });


    /* 4. Give user option to add steps.
    ** "Does your project still seem daunting? Maybe you haven't broken
    ** it into small enough steps. Try adding another step or two." */
    $coach_active_project->get('/{id}/add_step', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/4add_step.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });

    $coach_active_project->post('/{id}/add_step', function($id) use ($app) {
        $project = Project::find($id);
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

        return $app['twig']->render('coach/active_project/4add_step.html.twig', array(
            'project' => $project,
            'steps' => $project->getSteps()
        ));
    });



    /* 5. Great. Glad you feel confident you can complete this step.
    ** Tell me when you've finished it. Wait....
    ** Let user choose if they'd like to see the next step -> /next_step
    ** or had enough for the day -> /enough
    */
    $coach_active_project->get('/{id}/complete', function($id) use ($app) {
        $project = Project::find($id);

        $all_steps_count = sizeof($project->getSteps());

        if ($all_steps_count != 0) {
            $incomplete_steps_count = sizeof($project->getIncompleteSteps());
            $complete_steps_count = $all_steps_count - $incomplete_steps_count;
            $progress_percent = (int) (($complete_steps_count / $all_steps_count) * 100);
        } else {
            // If there are no steps, then progress percent is definitely 0.
            $progress_percent = 0;
        }


        // get next step should work here
        return $app['twig']->render('coach/active_project/5complete.html.twig', array(
            'project' => $project,
            'step' => $project->getNextStep(),
            'progress_percent' => $progress_percent
        ));
    });

    // Duplicate for post, sent here if user checks that they have finished step
    // on get page
    $coach_active_project->post('/{id}/complete', function($id) use ($app) {
        $project = Project::find($id);

        // Pass step id to here through hidden form input
        $step = Step::find($_POST['step_id']);

        if (!empty($_POST['complete']) && $_POST['complete'] == 'true') {
            $step->updateComplete(1);
        } else {
            $step->updateComplete(0);
        }

        // get percent complete on this project
        $all_steps_count = sizeof($project->getSteps());
        $incomplete_steps_count = sizeof($project->getIncompleteSteps());
        $complete_steps_count = $all_steps_count - $incomplete_steps_count;
        $progress_percent = (int) (($complete_steps_count / $all_steps_count) * 100);


        // If finishing this step completes the project, then update complete in project
        // and re-direct on twig page to project complete page.
        //if length of project get incomplete steps = 0 then update complete true
        if ($incomplete_steps_count == 0) {
            $project->updateComplete(1);
        }


        return $app['twig']->render('coach/active_project/5complete.html.twig', array(
            'project' => $project,
            'progress_percent' => $progress_percent,
            'step' => $step
        ));
    });

    /* 6. Project complete. Great job! Balloons, fiesta, music...
    ** Go back to dashboard. */
    $coach_active_project->get('/{id}/project_complete', function($id) use ($app) {
        $project = Project::find($id);

        return $app['twig']->render('coach/active_project/6project_complete.html.twig', array(
            'project' => $project
        ));
    });



    /* 7. Enough for today. More positive reinforcement, progress bar.
    ** Redirect to dashboard. */
    $coach_active_project->get('/{id}/enough', function($id) use ($app) {
        $project = Project::find($id);

        // get percent complete on this project
        $all_steps_count = sizeof($project->getSteps());
        $incomplete_steps_count = sizeof($project->getIncompleteSteps());
        $complete_steps_count = $all_steps_count - $incomplete_steps_count;
        $progress_percent = (int) (($complete_steps_count / $all_steps_count) * 100);

        return $app['twig']->render('coach/active_project/7enough.html.twig', array(
            'project' => $project,
            'progress_percent' => $progress_percent
        ));
    });








    // Place all urls in this file at /coach/active_project/*
    $app->mount('/coach/active_project', $coach_active_project);

 ?>
