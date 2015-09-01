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


        // Basic DB altering methods

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
            $GLOBALS['DB']->exec("DELETE FROM projects WHERE id = {$this->getId()};");
        }




        // STATIC Methods 

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
