<?php

    class Habit
    {
      private $name;
      private $motivation;
      private $interval_days;
      private $completed;
      private $id;

      function __construct($name, $motivation, $interval_days, $completed, $id = null)
      {
        $this->name = $name;
        $this->motivation = $motivation;
        $this->interval_days = $interval_days;
        $this->completed = (int) $completed;
        $this->id = $id;
      }

      function getName()
      {
        return $this->name;
      }

      function setName($new_name)
      {
        $this->name = $new_name;
      }

      function getMotivation()
      {
          return $this->motivation;
      }

      function setMotivation($new_motivation)
      {
          $this->motivation = $new_motivation;
      }

      function getIntervalDays()
      {
          return $this->interval_days;
      }

      function setIntervalDays($new_interval_days)
      {
          $this->interval_days = $new_interval_days;
      }

      function getCompleted()
      {
          return $this->completed;
      }

      function setCompleted($new_completed)
      {
          $this->completed = (int) $new_completed;
      }

      function getId()
      {
          return $this->id;
      }

      function save()
      {
          $GLOBALS['DB']->exec("INSERT INTO habits (name, motivation, interval_days, completed)
          VALUES ('{$this->getName()}', '{$this->getMotivation()}', {$this->getIntervalDays()}, {$this->getCompleted()});");
          $this->id = $GLOBALS['DB']->lastInsertId();
      }

      static function getAll()
      {
          $returned_habits = $GLOBALS['DB']->query("SELECT * FROM habits;");

          $habits = array();
          foreach($returned_habits as $habit) {
              $habit_name = $habit['name'];
              $habit_motivation = $habit['motivation'];
              $habit_interval_days = $habit['interval_days'];
              $habit_completed = $habit['completed'];
              $habit_id = $habit['id'];
              $new_habit = new Habit($habit_name, $habit_motivation, $habit_interval_days, $habit_completed, $habit_id);
              array_push($habits, $new_habit);
          }

         return $habits;
      }

      static function deleteAll()
      {
          $GLOBALS['DB']->exec("DELETE FROM habits;");
      }

      static function find($search_id)
      {
          $found_habit = null;
          $habits = Habit::getAll();
          foreach($habits as $habit) {
              $habit_id = $habit->getId();
              if ($habit_id == $search_id) {
                  $found_habit = $habit;
              }
          }

          return $found_habit;
      }

      function updateName($new_habit_name)
      {
          $GLOBALS['DB']->exec("UPDATE habits SET name = '{$new_habit_name}' WHERE id = {$this->getId()};");
          $this->setName($new_habit_name);
      }

      function updateMotivation($new_habit_motivation)
      {
          $GLOBALS['DB']->exec("UPDATE habits SET motivation = '{$new_habit_motivation}' WHERE id = {$this->getId()};");
          $this->setMotivation($new_habit_motivation);
      }

      function updateIntervalDays($new_habit_interval_days)
      {
          $GLOBALS['DB']->exec("UPDATE habits SET interval_days = {$new_habit_interval_days} WHERE id = {$this->getId()};");
          $this->setIntervalDays($new_habit_interval_days);
      }

      function delete()
      {
          $GLOBALS['DB']->exec("DELETE FROM habits WHERE id = {$this->getId()};");
      }


      // edit for boolean
      // this is an attempt to count the number of habits that have not been completely completed
      static function getActiveHabitCount()
      {
        $returned_habits = $GLOBALS['DB']->query("SELECT * FROM habits WHERE completed = false;");

        $count = 0;

        foreach($returned_habits as $habit) {
          $count++;
      }
      return $count;

      }

      //find the habit from the habit_id
      //get the interval_days saved with that habit
      //insert a day_id one by one into the daily_completed join table
      //for every day in the user specified interval
      function countHabitLength($habit_id)
      {
        $this_habit = Habit::find($habit_id);
        $habit_length = $this_habit->getIntervalDays();


        for ( $count = 0; $count < $habit_length; $count++) {
          $GLOBALS['DB']->exec("INSERT INTO daily_completed (day_id, complete_today, habit_id) VALUES (
            {$count},
            false,
            {$this_habit->getId()});"
          );
        }
      }



    //update the complete_today column
    function completeOnDayId($habit_id)
    {
      $this_habit = Habit::find($habit_id);

      $days = $GLOBALS['DB']->query("SELECT day_id FROM daily_completed WHERE complete_today = false AND habit_id = {$habit_id};");
      $days_array = array();
      foreach ($days as $day) {
        array_push($days_array, $day['day_id']);
      }

      if ($days_array == []) {
        $found_day_id = 0;
      } else {
      $found_day_id = min($days_array);
      }
      $GLOBALS['DB']->exec("UPDATE daily_completed SET complete_today = true WHERE day_id = {$found_day_id};");
    }

    function getDaysCompleted($habit_id)
    {
      $this_habit = Habit::find($habit_id);

      $days = $GLOBALS['DB']->query("SELECT day_id FROM daily_completed WHERE complete_today = true AND habit_id = {$habit_id};");

      $count = 0;

      foreach ($days as $day) {
        $count++;
      }

      return $count;

    }

    // still need a method to complete a habit
    }

?>
