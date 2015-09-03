<?php

    class User
    {
        private $name;
        private $email;
        private $password;
        private $id;


        function __construct($name, $email, $password, $id=null)
        {
            $this->name = $name;
            $this->email = $email;
            $this->password = $password;
            $this->id = (int) $id;

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

        // function getId ()
        // {
        //     return $this->id;
        // }


        function save()
        {
            array_push($_SESSION['user'], $this);
        }

        static function getAll()
        {
            return $_SESSION['user'];
        }





    }

?>
