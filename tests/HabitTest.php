<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
    require_once "src/Habit.php";


    // require_once "src/DailyCompleted.php";
    $server = 'mysql:host=localhost;dbname=lifecoach_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class HabitTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Habit::deleteAll();
        }
        function testGetName()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = False;

            $test_habit = new Habit($name, $motivation, $interval_days, $completed);
            $result = $test_habit->getName();

            $this->assertEquals($name, $result);

        }

        function testSetName()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = False;

            $test_habit = new Habit($name, $motivation, $interval_days, $completed);
            $result = $test_habit->getName();

            $this->assertEquals("Meditate", $result);
        }

        function testGetMotivation()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = False;

            $test_habit = new Habit($name, $motivation, $interval_days, $completed);
            $result = $test_habit->getMotivation();

            $this->assertEquals($motivation, $result);

        }

        function testSetMotivation()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = False;

            $test_habit = new Habit($name, $motivation, $interval_days, $completed);
            $result = $test_habit->getMotivation();

            $this->assertEquals("Clarity", $result);
        }

        function testGetIntervalDays()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = False;


            $test_habit = new Habit($name, $motivation, $interval_days, $completed);
            $result = $test_habit->getIntervalDays();

            $this->assertEquals($interval_days, $result);

        }

        function testSetIntervalDays()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = False;

            $test_habit = new Habit($name, $motivation, $interval_days, $completed);
            $result = $test_habit->getIntervalDays();

            $this->assertEquals(3, $result);
        }

        function testGetId()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $id = 4;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed, $id);

            $result = $test_habit->getId();

            $this->assertEquals(4, $result);
        }

        function testSave()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = false;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed);



            $test_habit->save();

            $this->assertEquals(is_numeric($test_habit->getId()), true);


        }

        function testGetAll()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed);
            $test_habit->save();

            $name2 = "Go running";
            $motivation2 = "Fitness";
            $interval_days2 = 35;
            $completed2 = true;
            $test_habit2 = new Habit($name2, $motivation2, $interval_days2, $completed2);
            $test_habit2->save();



            $result = Habit::getAll();



            $this->assertEquals([$test_habit, $test_habit2], $result);

        }

        function testDeleteAll()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $id = 4;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed, $id);
            $test_habit->save();

            $name2 = "Go running";
            $motivation2 = "Fitness";
            $interval_days2 = 35;
            $id2 = 5;
            $completed2 = False;
            $test_habit2 = new Habit($name2, $motivation2, $interval_days2, $completed2, $id2);
            $test_habit2->save();

            Habit::deleteAll();
            $result = Habit::getAll();

            $this->assertEquals([], $result);
        }

        function testFind()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $id = 4;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed, $id);
            $test_habit->save();

            $name2 = "Go running";
            $motivation2 = "Fitness";
            $interval_days2 = 35;
            $id2 = 5;
            $completed2 = False;
            $test_habit2 = new Habit($name2, $motivation2, $interval_days2, $completed2, $id2);
            $test_habit2->save();

            $result = Habit::find($test_habit->getId());

            $this->assertEquals($test_habit, $result);
        }

        function testUpdateName()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $id = 4;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed, $id);
            $test_habit->save();

            $new_name = "Dance";

            $result = $test_habit->updateName($new_name);

            $this->assertEquals("Dance", $test_habit->getName());
        }

        function testUpdateMotivation()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $id = 4;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed, $id);
            $test_habit->save();

            $new_motivation = "Hate my dad";

            $result = $test_habit->updateMotivation($new_motivation);

            $this->assertEquals("Hate my dad", $test_habit->getMotivation());
        }

        function testUpdateIntervalDays()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $id = 4;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed, $id);
            $test_habit->save();

            $new_interval_days = 12;

            $result = $test_habit->updateIntervalDays($new_interval_days);

            $this->assertEquals(12, $test_habit->getIntervalDays());
        }

        function testDelete()
        {
            $name = "Meditate";
            $motivation = "Clarity";
            $interval_days = 3;
            $id = 4;
            $completed = False;
            $test_habit = new Habit($name, $motivation, $interval_days, $completed, $id);
            $test_habit->save();

            $name2 = "Go running";
            $motivation2 = "Fitness";
            $interval_days2 = 35;
            $id2 = 5;
            $completed2 = False;
            $test_habit2 = new Habit($name2, $motivation2, $interval_days2, $completed2, $id2);
            $test_habit2->save();

            $test_habit->delete();

            $this->assertEquals([$test_habit2], Habit::getAll());


        }

      function testCountHabitLength()
      {
        $name = "Meditate";
        $motivation = "Clarity";
        $interval_days = 3;
        $completed = False;
        $test_habit = new Habit($name, $motivation, $interval_days, $completed);
        $test_habit->save();

        $test_habit_id = $test_habit->getId();
        $test_habit->countHabitLength($test_habit_id);


        $result = $GLOBALS['DB']->query("SELECT * FROM daily_completed WHERE habit_id = {$test_habit_id};");
        $days_array = array();
        foreach ($result as $row) {
          array_push($days_array, $row['day_id']);
        }


        $this->assertEquals([0, 1, 2], $days_array);
      }

    function testCompleteTodayOnDayId()
    {
      $name = "Meditate";
      $motivation = "Clarity";
      $interval_days = 3;
      $completed = False;
      $test_habit = new Habit($name, $motivation, $interval_days, $completed);
      $test_habit->save();

      $test_habit_id = $test_habit->getId();
      $test_habit->countHabitLength($test_habit_id);

      $test_habit->completeOnDayId($test_habit_id);

      $days = $GLOBALS['DB']->query("SELECT * FROM daily_completed WHERE habit_id = {$test_habit_id} AND complete_today = true;");
      $days_array = array();
      foreach ($days as $day) {
        array_push($days_array, $day['day_id']);
        }

        $found_day_id = min($days_array);
        $result = $found_day_id;

        $this->assertEquals(0, $result);

    }

    function testCompleteOnDayId2()

    function testGetDaysCompleted()
    {
      $name = "Meditate";
      $motivation = "Clarity";
      $interval_days = 5;
      $completed = False;
      $test_habit = new Habit($name, $motivation, $interval_days, $completed);
      $test_habit->save();

      $test_habit_id = $test_habit->getId();
      $test_habit->countHabitLength($test_habit_id);

      $test_habit->completeOnDayId($test_habit_id);


      $result = $test_habit->getDaysCompleted($test_habit_id);

      $this->assertEquals(1, $result);

    }

    function testGet2DaysCompleted()
    {
      $name = "Meditate";
      $motivation = "Clarity";
      $interval_days = 5;
      $completed = False;
      $test_habit = new Habit($name, $motivation, $interval_days, $completed);
      $test_habit->save();

      $test_habit_id = $test_habit->getId();
      $test_habit->countHabitLength($test_habit_id);

      $test_habit->completeOnDayId($test_habit_id);


      $result = $test_habit->getDaysCompleted($test_habit_id) + $test_habit->getDaysCompleted($test_habit_id);

      $this->assertEquals(2, $result);

    }


    function testGetActiveHabitCount()
    {
      $name = "Meditate";
      $motivation = "Clarity";
      $interval_days = 5;
      $completed = False;
      $test_habit = new Habit($name, $motivation, $interval_days, $completed);
      $test_habit->save();

      $result = Habit::getActiveHabitCount();

      $this->assertEquals(1, $result);


    }

    function testGetActiveHabitCount2()
    {
      $name = "Meditate";
      $motivation = "Clarity";
      $interval_days = 5;
      $completed = False;
      $test_habit = new Habit($name, $motivation, $interval_days, $completed);
      $test_habit->save();

      $name2 = "Go running";
      $motivation2 = "Fitness";
      $interval_days2 = 35;
      $id2 = 5;
      $completed2 = False;
      $test_habit2 = new Habit($name2, $motivation2, $interval_days2, $completed2, $id2);
      $test_habit2->save();

      $result = Habit::getActiveHabitCount();

      $this->assertEquals(2, $result);


    }
  }
?>
