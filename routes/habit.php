<?php
    $habit = $app['controllers_factory'];

    $habit->get('/current_habits', function() use ($app) {
        return $app['twig']->render('habit/current_habits.html.twig', array('habits' => Habit::getAll()));
    });

    $habit->post('/new_habit', function() use ($app){
        $name = $_POST['name'];
        $motivation = $_POST['motivation'];
        $interval_days = $_POST['interval_days'];
        $habit = new Habit($name, $motivation, $interval_days, $id = null);
        $habit->save();
        return $app['twig']->render('habit/current_habits.html.twig', array('habits' => Habit::getAll()));
    });

    $habit->get('/new_habit', function() use ($app) {
      return $app['twig']->render('habit/new_habit_name.html.twig');
    });

    $habit->get('/habits/{id}', function($id) use ($app) {
      $habit = Habit::find($id);
      return $app['twig']->render('habit/habit_edit.html.twig', array('habit' => $habit));
    });

    $habit->patch('/habits/{id}', function($id) use ($app) {
      $habit = Habit::find($id);
      $name = $_POST['name'];
      $motivation = $_POST['motivation'];
      $interval_days = $_POST['interval_days'];
      $habit->updateName($name);
      $habit->updateMotivation($motivation);
      $habit->updateIntervalDays($interval_days);
      return $app['twig']->render('habit/habit_edit.html.twig', array('habit' => $habit));
    });





    // Place all urls in this file at /habit/*
    $app->mount('/habit', $habit);

 ?>
