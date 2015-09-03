<?php
    $habit = $app['controllers_factory'];

    $habit->get('/current_habits', function() use ($app) {
        $count = 0;
        return $app['twig']->render('habit/current_habits.html.twig', array('habits' => Habit::getAll()));
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

    $habit->delete('/habits/{id}', function($id) use ($app) {
      $habit = Habit::find($id);
      $habit->delete();
      return $app['twig']->render('habit/current_habits.html.twig', array('habits' => Habit::getAll()));
    });

    // Place all urls in this file at /habit/*
    $app->mount('/habit', $habit);

 ?>
