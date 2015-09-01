<?php

	class Step
	{

		private $description;
		private $project_id; 
		private $position;
		private $id; 

		function __construct($description, $project_id, $position, $id=null)
		{
			$this->description = $description;
			$this->project_id = $project_id;
			$this->position = $position;
			$this->id = (int)$id; 
		}


		// Get and Set Methods

		function setDescription($new_description)
		{
			$this->description = $new_description;
		}

		function getDescription()
		{
			return $this->description;
		}

		function setProjectId($new_project_id)
		{
			$this->project_id = $new_project_id;
		}

		function getProjectId()
		{
			return $this->project_id; 
		}

		function setPosition($new_position)
		{
			$this->position = $new_position; 
		}

		function getPosition()
		{
			return $this->position;
		}

		function getId()
		{
			return $this->id; 
		}


		// Basic Database Methods

		function save()
		{
			$GLOBALS['DB']->exec("INSERT INTO steps (description,project_id,position) VALUES (
				'{$this->getDescription()}',
				 {$this->getProjectId()},
				 {$this->getPosition()}
			);");
			$this->id = $GLOBALS['DB']->lastInsertId(); 
		}





		// STATIC Methods



		static function getAll()
		{
			$returned_steps = $GLOBALS['DB']->exec("SELECT * FROM steps;");

			$steps = array();

			foreach($returned_steps as $step) {
				$description = $step['description'];
				$project_id = $step['project_id'];
				$position = $step['position'];
				$id = $step['id'];

				$new_step = new Step($description,$project_id,$position,$id);
				array_push($steps, $new_step);
			}
			return $steps; 
		}

		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM steps;");
		}


	}


?>