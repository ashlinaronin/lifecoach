<?php

	class Step
	{

		private $description;
		private $project_id;
		private $position;
		private $id;
		private $complete;


		function __construct($description, $project_id, $position, $id=null, $complete=0)
		{
			$this->description = $description;
			$this->project_id  = (int)$project_id;
			$this->position    = (int)$position;
			$this->id          = (int)$id;
			$this->complete    = (int)$complete;
		}


		// Get and Set Methods ====================================================


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

		function setComplete($new_complete_boolean)
		{
			$this->complete = $new_complete_boolean;
		}

		function getComplete()
		{
			return $this->complete;
		}


		function getId()
		{
			return $this->id;
		}


		// Basic Database Methods =================================================



		function save()
		{
			$GLOBALS['DB']->exec("INSERT INTO steps (description,project_id,position,complete) VALUES (
				'{$this->getDescription()}',
				 {$this->getProjectId()},
				 {$this->getPosition()},
				 {$this->getComplete()}
			);");
			$this->id = $GLOBALS['DB']->lastInsertId();
		}


		function updateDescription($new_description)
		{
			$GLOBALS['DB']->exec("UPDATE steps SET description = '{$new_description}' WHERE id = {$this->getId()};");
			$this->setDescription($new_description);
		}


		function updateProjectId($new_project_id)
		{
			$GLOBALS['DB']->exec("UPDATE steps SET project_id = '{$new_project_id}' WHERE id = {$this->getId()};");
			$this->setProjectId($new_project_id);
		}


		function updatePosition($new_position)
		{
			$GLOBALS['DB']->exec("UPDATE steps SET position = '{$new_position}' WHERE id = {$this->getId()};");
			$this->setPosition($new_position);
		}


		function updateComplete($new_complete)
		{
			$GLOBALS['DB']->exec("UPDATE steps SET complete = '{$new_complete}' WHERE id = {$this->getId()};");
			$this->setComplete($new_complete);
		}


		function delete()
		{
			$GLOBALS['DB']->exec("DELETE FROM steps WHERE id = {$this->getId()};");
		}




		// STATIC Methods =========================================================



		static function getAll()
		{
			$returned_steps = $GLOBALS['DB']->query("SELECT * FROM steps;");

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


		static function find($search_id)
		{
			$found_step = null;
			$steps = Step::getAll();
			foreach($steps as $step) {
				if($step->getId() == $search_id) {
					$found_step = $step;
				}
			}
			return $found_step;
		}


		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM steps;");
		}


	}


?>
