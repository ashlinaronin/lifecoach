<?php

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

    require_once "src/Project.php";
    // require_once "src/Step";


    $server = 'mysql:host=localhost;dbname=lifecoach_test';
    $username = 'root';
    $password = '';
    $DB = new PDO($server, $username, $password);


    class ProjectTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Project::deleteAll();
            // Steps::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);

            //Act
            $result = $test_project->getName();

            //Assert
            $this->assertEquals($name, $result);

        }

        function test_save()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);

            //Act
            $test_project->save();

            //Assert
            $result = Project::getAll();
            $this->assertEquals([$test_project], $result);

        }


        function test_getAll()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $name2 = "Learn French";
            $motivation2 = "To travel";
            $test_project2 = new Project($name2,$motivation2,$due_date,$priority);
            $test_project2->save();

            //Act
            $result = Project::getAll();

            //Assert
            $this->assertEquals([$test_project,$test_project2],$result);

        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $name2 = "Learn French";
            $motivation2 = "To travel";
            $test_project2 = new Project($name2,$motivation2,$due_date,$priority);
            $test_project2->save();

            //Act
            Project::deleteAll();
            $result = Project::getAll();

            //Assert
            $this->assertEquals([],$result); 
        }

        function test_delete()
        {
             //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $name2 = "Learn French";
            $motivation2 = "To travel";
            $test_project2 = new Project($name2,$motivation2,$due_date,$priority);
            $test_project2->save();  

            //Act
            $test_project->delete();
            $result = Project::getAll();

            //Assert
            $this->assertEquals([$test_project2],$result);

        }

        function test_find()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $name2 = "Learn French";
            $motivation2 = "To travel";
            $test_project2 = new Project($name2,$motivation2,$due_date,$priority);
            $test_project2->save(); 
            
            //Act 
            $result = Project::find($test_project2->getId()); 

            //Assert
            $this->assertEquals($test_project2,$result); 

        }

        // test addStep

        // test getSteps


    }


?>
