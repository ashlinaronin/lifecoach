<?php

	/**
	* @backupGlobals disabled
	* @backupStaticAttributes disabled
	*/

	require_once "src/Step.php";
	// require_once "src/Project.php";

	$server = 'mysql:host=localhost:3306;dbname=lifecoach_test';
	$username = 'root';
	$password = 'root';
	$DB = new PDO ($server, $username, $password);

	class StepTest extends PHPUnit_Framework_TestCase
	{

		protected function tearDown()
		{
			Step::deleteAll();
		}

		function test_getDescription()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);

			//Act
			$result = $test_step->getDescription();

			//Assert
			$this->assertEquals($description,$result);

		}

		function test_save()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);

			//Act
			$test_step->save();

			//Assert
			$result = Step::getAll();
			$this->assertEquals($test_step, $result[0]);

		}


		function test_getAll()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);
			$test_step->save();

			$description2 = "Buy French bread";
			$project_id2 = 1;
			$position2 = 2;
			$test_step2 = new Step($description2, $project_id2, $position2);
			$test_step2->save();

			//Act
			$result = Step::getAll();

			//Assert
			$this->assertEquals([$test_step,$test_step2], $result);

		}


		function test_deleteAll()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);
			$test_step->save();

			$description2 = "Buy French bread";
			$project_id2 = 1;
			$position2 = 2;
			$test_step2 = new Step($description2, $project_id2, $position2);
			$test_step2->save();

			//Act
			Step::deleteAll();
			$result = Step::getAll();

			//Assert
			$this->assertEquals([], $result);

		}


		function test_delete()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);
			$test_step->save();

			$description2 = "Buy French bread";
			$project_id2 = 1;
			$position2 = 2;
			$test_step2 = new Step($description2, $project_id2, $position2);
			$test_step2->save();

			//Act
			$test_step->delete();
			$result = Step::getAll();

			//Assert
			$this->assertEquals([$test_step2], $result);

		}


		function test_find()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);
			$test_step->save();

			$description2 = "Buy French bread";
			$project_id2 = 1;
			$position2 = 2;
			$test_step2 = new Step($description2, $project_id2, $position2);
			$test_step2->save();

			//Act
			$result = Step::find($test_step2->getId());

			//Assert
			$this->assertEquals($test_step2, $result);

		}


		function test_updateDescription()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);
			$test_step->save();

			//Act
			$new_description = "Get a beret";
			$test_step->updateDescription($new_description);

			//Assert
			$result = $test_step->getDescription();
			$this->assertEquals($new_description, $result);

		}

		function test_updateProjectId()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);
			$test_step->save();

			//Act
			$new_project_id = 3;
			$test_step->updateProjectId($new_project_id);

			//Assert
			$result = $test_step->getProjectId();
			$this->assertEquals($new_project_id, $result);

		}

		function test_updatePosition()
		{
			//Arrange
			$description = "Buy book on learning French";
			$project_id = 1;
			$position = 1;
			$test_step = new Step($description, $project_id, $position);
			$test_step->save();

			//Act
			$new_position = 3;
			$test_step->updatePosition($new_position);

			//Assert
			$result = $test_step->getPosition();
			$this->assertEquals($new_position, $result);

		}



	}


?>
