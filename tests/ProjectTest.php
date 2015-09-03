<?php

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

    require_once "src/Project.php";
    require_once "src/Step.php";


    $server = 'mysql:host=localhost:3306;dbname=lifecoach_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class ProjectTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Project::deleteAll();
            Step::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $complete = 0;
            $test_project = new Project($name,$motivation,$due_date,$priority,$complete);

            //Act
            $result = $test_project->getName();

            //Assert
            $this->assertEquals($name, $result);

        }

        function test_getComplete()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);

            //Act
            $result = $test_project->getComplete();

            //Assert
            $this->assertEquals(0, $result);

        }

        function test_setComplete()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);

            //Act
            $test_project->setComplete(1);
            $result = $test_project->getComplete();

            //Assert
            $this->assertEquals(1, $result);

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

        function test_updateName()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $new_name = "Tear down a shed";

            //Act
            $test_project->updateName($new_name);
            $result = Project::getAll();

            //Assert
            $this->assertEquals($new_name,$result[0]->getName());

        }

        function test_updateMotivation()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $new_motivation = "save frustration";

            //Act
            $test_project->updateMotivation($new_motivation);
            $result = Project::getAll();

            //Assert
            $this->assertEquals($new_motivation,$result[0]->getMotivation());

        }

        function test_updateDueDate()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $new_due_date = "2015-10-10";

            //Act
            $test_project->updateDueDate($new_due_date);
            $result = Project::getAll();

            //Assert
            $this->assertEquals($new_due_date,$result[0]->getDueDate());

        }



        function test_getSteps()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $description = "Buy a beret";
            $project_id = $test_project->getId();
            $position = 1;
            $test_step = new Step($description, $project_id, $position);
            $test_step->save();

            $description2 = "Eat French bread";
            $position2 = 2;
            $test_step2 = new Step($description2, $project_id, $position2);
            $test_step2->save();

            //Act
            $result = $test_project->getSteps();

            //Assert
            $this->assertEquals([$test_step,$test_step2],$result);

        }


        function test_deleteStep()
        {
            //Arrange
            $name = "Build a shed";
            $motivation = "have storage";
            $due_date = "2015-09-09";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $description = "Buy a beret";
            $project_id = $test_project->getId();
            $position = 1;
            $test_step = new Step($description, $project_id, $position);
            $test_step->save();

            $description2 = "Eat French bread";
            $position2 = 2;
            $test_step2 = new Step($description2, $project_id, $position2);
            $test_step2->save();

            //Act
            $test_project->deleteStep($test_step);
            $result = $test_project->getSteps();

            //Assert
            $this->assertEquals([$test_step2],$result);

        }

        function test_getNextStep()
        {
            //Arrange
            $name = "Learn French";
            $motivation = "To travel";
            $due_date = "2015-10-10";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $description = "Buy a beret";
            $project_id = $test_project->getId();
            $position = 1;
            $test_step = new Step($description, $project_id, $position);
            $test_step->save();
            $test_step->setComplete(1);

            $description2 = "Eat French bread";
            $position2 = 2;
            $test_step2 = new Step($description2, $project_id, $position2);
            $test_step2->save();

            //Act
            $test_step->updateComplete(1);
            $result = Project::getAll();

            //Assert
            $this->assertEquals($test_step2,$result[0]->getNextStep());

        }

        function test_getIncompleteStep()
        {
            //Arrange
            $name = "Learn to speak French";
            $motivation = "To travel";
            $due_date = "2015-10-10";
            $priority = 1;
            $test_project = new Project($name,$motivation,$due_date,$priority);
            $test_project->save();

            $description = "Buy a beret";
            $project_id = $test_project->getId();
            $position = 1;
            $test_step = new Step($description, $project_id, $position);
            $test_step->save();

            $description2 = "Eat French bread";
            $position2 = 2;
            $test_step2 = new Step($description2, $project_id, $position2);
            $test_step2->save();

            $description3 = "Watch Julia Childs";
            $position3 = 3;
            $test_step3 = new Step($description3, $project_id, $position3);
            $test_step3->save();

            //Act
            $test_step2->updateComplete(1);
            $result = Project::getAll();

            //Assert
            $this->assertEquals([$test_step,$test_step3],$result[0]->getIncompleteSteps());


        }




    }


?>
