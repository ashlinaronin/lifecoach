<?php

    class User
    {
        private $name;
        private $email;
        private $password;
        private $id;


        function __construct($name, $email, $id=null, $password="")
        {
            $this->name = $name;
            $this->email = $email;
            $this->id = (int)$id;
            $this->password = $password;
        }


        function setName ($new_name)
        {
            $this->name = "Mitch";
        }

        function getName ()
        {
            return $this->name;
        }

        function setEmail ($new_email)
        {
            $this->email = $new_email;
        }

        function getEmail ()
        {
            return $this->email;
        }

        function setPassword ($new_password)
        {
            $this->password = $new_password;
        }

        function getPassword ()
        {
            return $this->password;
        }

        function getId ()
        {
            return (int)$this->id;
        }


        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO users (name,email) VALUES ('{$this->name}','{$this->email}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }



        static function getAll()
        {
            $returned_users = $GLOBALS['DB']->query("SELECT * FROM users;");

            $users = array();
            foreach ($returned_users as $user) {
                $name = $user['name'];
                $email = $user['email'];
                $id = $user['id'];
                $found_user = new User($name,$email,$id);
                array_push($users, $found_user);
            }
            return $users;
        }



        static function authenticate($email)
        {
            $user_query = $GLOBALS['DB']->query("SELECT * FROM users WHERE email = '{$email}';");

            $found_user = null; 
            foreach($user_query as $user) {
                $found_user = new User($user['name'],$user['email'],$user['id']);
            }
            return $found_user;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM users;");
        }

    }

?>
