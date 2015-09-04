<?php

    class Project
    {
        private $name;
        private $motivation;
        private $due_date;
        private $priority;
        private $id;
        private $complete;


        function __construct($name, $motivation, $due_date, $priority, $id = null, $complete=0)
        {
            $this->name = $name;
            $this->motivation = $motivation;
            $this->due_date = $due_date;
            $this->priority = (int)$priority;
            $this->id = (int) $id;
            $this->complete = (int)$complete;
        }



        // Get and Set Methods ==============================================



        function setName ($new_name)
        {
            $this->name = $new_name;
        }

        function getName ()
        {
            return $this->name;
        }

        function setMotivation ($new_motivation)
        {
            $this->motivation = $new_motivation;
        }

        function getMotivation ()
        {
            return $this->motivation;
        }

        function setDueDate ($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        function getDueDate ()
        {
            return $this->due_date;
        }

        function setPriority ($new_priority)
        {
            $this->priority = $new_priority;
        }

        function getPriority ()
        {
            return $this->priority;
        }

        function setComplete ($new_complete)
        {
            $this->complete = $new_complete;
        }

        function getComplete ()
        {
            return $this->complete;
        }

        function getId()
        {
            return $this->id;
        }



        // BASIC DB Altering Methods ========================================


        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO projects (name,motivation,due_date,priority,complete) VALUES(
                '{$this->getName()}',
                '{$this->getMotivation()}',
                '{$this->getDueDate()}',
                 {$this->getPriority()},
                 {$this->getComplete()}
            );");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }


        function delete()
        {
            // Deletes all Steps associated to project before deletion of the Project
            $project_id_to_delete = $this->getId();
            $GLOBALS['DB']->exec("DELETE FROM steps WHERE project_id = {$project_id_to_delete};");
            $GLOBALS['DB']->exec("DELETE FROM projects WHERE id = {$project_id_to_delete};");
        }


        function updateName($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE projects SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }


        function updateMotivation($new_motivation)
        {
            $GLOBALS['DB']->exec("UPDATE projects SET motivation = '{$new_motivation}' WHERE id = {$this->getId()};");
            $this->setMotivation($new_motivation);
        }


        function updateDueDate($new_due_date)
        {
            $GLOBALS['DB']->exec("UPDATE projects SET due_date = '{$new_due_date}' WHERE id = {$this->getId()};");
            $this->setDueDate($new_due_date);
        }


        function updateComplete($new_complete)
        {
            $GLOBALS['DB']->exec("UPDATE projects SET complete = {$new_complete} WHERE id = {$this->getId()};");
            $this->setComplete($new_complete);
        }


        // Methods involving other tables ===================================



        function getSteps ()
        {
            // Order by position for updating functionality elsewhere
            $steps_query = $GLOBALS['DB']->query(
                "SELECT * FROM steps WHERE project_id = {$this->getId()} ORDER BY position;"
            );

            $matching_steps = array();
            foreach($steps_query as $step) {
                $description = $step['description'];
                $project_id = $step['project_id'];
                $position = $step['position'];
                $id = $step['id'];
                $complete = $step['complete'];
                $new_step = new Step($description,$project_id,$position,$id,$complete);
                array_push($matching_steps, $new_step);
            }
            return $matching_steps;
        }


        function getIncompleteSteps ()
        {
            $steps_query = $GLOBALS['DB']->query(
                "SELECT * FROM steps WHERE complete=0 AND project_id = {$this->getId()} ORDER BY position;"
            );

            $matching_steps = array();
            foreach($steps_query as $step) {
                $description = $step['description'];
                $project_id = $step['project_id'];
                $position = $step['position'];
                $id = $step['id'];
                $complete = $step['complete'];
                $new_step = new Step($description,$project_id,$position,$id,$complete);
                array_push($matching_steps, $new_step);
            }
            return $matching_steps;
        }


        function getNextStep()
        {
            // Returns one row with the lowest priority value for matching project_id rows with complete values of 0
            $step_query = $GLOBALS['DB']->query(
                "SELECT id,description,project_id,complete,min(position) as position FROM steps WHERE complete = 0 AND project_id = {$this->getId()};");

            foreach($step_query as $step) {
                $next_step = new Step( $step['description'],
                                       $step['project_id'],
                                       $step['position'],
                                       $step['id'],
                                       $step['complete'] );
            }
            return $next_step;
        }


        function deleteStep ($step_to_delete)
        {
            $GLOBALS['DB']->exec("DELETE FROM steps WHERE id = {$step_to_delete->getId()};");
        }



        // STATIC Methods ===================================================



        static function find($search_id)
        {
            $found_project = null;
            $projects = Project::getAll();

            foreach($projects as $project) {
                if($project->getId() == $search_id ) {
                    $found_project = $project;
                }
            }
            return $found_project;
        }


        static function getAll()
        {
            $returned_projects = $GLOBALS['DB']->query("SELECT * FROM projects");

            $projects = array();

            foreach($returned_projects as $project){
                $name = $project['name'];
                $motivation = $project['motivation'];
                $due_date = $project['due_date'];
                $priority = $project['priority'];
                $id = (int)$project['id'];
                $new_project = new Project($name,$motivation,$due_date,$priority,$id);
                array_push($projects, $new_project);
            }
            return $projects;
        }

        static function countProjects()
        {
            $returned_projects = $GLOBALS['DB']->query("SELECT * FROM projects");

            $count = 0;
            foreach($returned_projects as $project){
                $count++;
            }
            return $count;
        }


        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM projects");
            // steps
        }

    }

?>
