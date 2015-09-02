<?php

    class Project
    {
        private $name;
        private $motivation;
        private $due_date;
        private $priority;
        private $id;


        function __construct($name, $motivation, $due_date, $priority, $id = null)
        {
            $this->name = $name;
            $this->motivation = $motivation;
            $this->due_date = $due_date;
            $this->priority = $priority;
            $this->id = (int)$id;
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

        function getId()
        {
            return $this->id;
        }



        // BASIC DB Altering Methods ========================================


        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO projects (name,motivation,due_date,priority) VALUES(
                '{$this->getName()}',
                '{$this->getMotivation()}',
                '{$this->getDueDate()}',
                {$this->getPriority()}
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
            $GLOBALS['DB']->exec("UPDATE project SET motivation = '{$new_motivation}' WHERE id = {$this->getId()};");
            $this->setMotivation($new_motivation);
        }


        function updateDueDate($new_due_date)
        {
            $GLOBALS['DB']->exec("UPDATE project SET due_date = '{$new_due_date}' WHERE id = {$this->getId()};");
            $this->setDueDate($new_due_date);
        }



        // Methods involving other tables ===================================



        function getSteps ()
        {
            $steps_query = $GLOBALS['DB']->query(
                "SELECT steps.* FROM
                    projects JOIN steps ON (projects.id = steps.project_id)
                 WHERE projects.id = {$this->getId()} ORDER BY priority;"
            );

            $matching_steps = array();
            foreach($steps_query as $step) {
                $description = $step['description'];
                $project_id = $step['project_id'];
                $position = $step['position'];
                $id = $step['id'];
                $new_step = new Step($description,$project_id,$position,$id);
                array_push($matching_steps, $new_step);
            }
            return $matching_steps;
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


        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM projects");
            // steps
        }

    }

?>
