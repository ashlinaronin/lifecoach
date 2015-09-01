<?php

	/**
	* @backupGlobals disabled
	* @backupStaticAttributes disabled
	*/

	require_once "src/Step.php";
	// require_once "src/Project.php";

	$server = 'mysql:host=localhost;dbname=lifecoach_test';
	$username = 'root';
	$password = '';
	$DB = new PDO ($server, $username, $password);

	class StepTest extends PHPUnit_Framework_TestCase
	{

		protected function tearDown()
		{
			Step::deleteAll();
			// Project::deleteAll(); 
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
			$this->assertEquals([$test_step], $result);

		}











	}


?>