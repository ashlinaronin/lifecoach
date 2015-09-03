<?php
    $coach_new_habit = $app['controllers_factory'];


    // FYI Right now this coach workflow doesn't deal with priority at all....




    /* 1. First page in new habit coach flow.
    ** We don't need to pass Twig any data to display yet.
    ** The rest of the pages will be a linear sequence of post http methods.
    ** Each does the action from the previous page.
    ** The Twig templates will be responsive to display only the aspects
    ** of an in-progress habit which have already been defined. */


    $coach_new_habit->get('/new_habit_name', function() use ($app) {
      return $app['twig']->render('coach/new_habit/1new_habit_name.html.twig');
    });


    /* 2. Create a new project with name from last form and dummy values for other properties.
    ** We don't have an id yet, so can't use it in URL.
    ** Then, display project as is so far. */

    $coach_new_habit->post('/motivation', function() use ($app) {
        $name = $_POST['name'];
        $motivation = null;
        $interval_days = 0;
        $completed = false;
        $habit = new Habit($name, $motivation, $interval_days, $completed);
        $habit->save();
        return $app['twig']->render('coach/new_habit/2new_habit_motivation.html.twig', array('habits' => Habit::getAll(), 'habit' => $habit));
    });


    /* 3. Add motivation from last form to habit.
    ** Show habit as is so far.
    ** Prompt user to brain dump pre-reqs.     */
    $coach_new_habit->post('/{id}/prereqs', function($id) use ($app) {
        $habit = Habit::find($id);

        if (!empty($_POST['motivation'])) {
            $habit->updateMotivation($_POST['motivation']);
        } else {
            // some kind of error here
        }

        return $app['twig']->render('coach/new_habit/3prereqs.html.twig', array(
            'habit' => $habit
        ));
    });



    /* 4. Add a new step if we have $_POST for it at the appropriate position.
    ** Display the user's braindump so they can refer to it when creating steps.
    ** Store dump from last page by passing it as a secret input in the form. */
    $coach_new_habit->post('/{id}/intervaldays', function($id) use ($app) {
        $habit = Habit::find($id);
        $dump = $_POST['dump'];



        return $app['twig']->render('coach/new_habit/4interval_days.html.twig', array(
            'habit' => $habit,
            'dump' => $dump
        ));
    });

    $coach_new_habit->post('/{id}/update_intervaldays', function($id) use ($app) {
      $habit = Habit::find($id);
      $interval_days = $_POST['intervaldays'];
      $habit->updateIntervalDays($interval_days);
      $habit->countHabitLength($habit->getId());
      return $app['twig']->render('coach/new_habit/5finished_habit.html.twig', array('habit' => $habit));
    });





    // Place all urls in this file at /coach/new_project/*
    $app->mount('/coach/new_habit', $coach_new_habit);

 ?>
