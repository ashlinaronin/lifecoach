<?php
    $coach_active_habit = $app['controllers_factory'];


    $coach_active_habit->get('/progress/{id}', function($id) use ($app) {
      $habit = Habit::find($id);
      $days_complete = $habit->getDaysCompleted($habit->getId());
      return $app['twig']->render('coach/active_habit/1progress.html.twig', array(
        'habit' => $habit,
        'days' => $days_complete
      ));
    });

    $coach_active_habit->post('/success/{id}', function($id) use ($app) {
      $habit = Habit::find($id);
      $habit->completeOnDayId($habit->getId());

      $days_complete = $habit->getDaysCompleted($habit->getId());
      return $app['twig']->render('coach/active_habit/2success.html.twig', array(
        'habit' => $habit,
        'days' => $days_complete
      ));
    });

    $coach_active_habit->get('/fail/{id}', function($id) use ($app) {
      $habit = Habit::find($id);
      $motivation = $habit->getMotivation();
      $days_complete = $habit->getDaysCompleted($habit->getId());
      return $app['twig']->render('coach/active_habit/3failure.html.twig', array(
        'habit' => $habit,
        'motivation' => $motivation,
        'days' => $days_complete
      ));
    });


    // Place all urls in this file at /coach/active_habit/*
    $app->mount('/coach/active_habit', $coach_active_habit);

 ?>
