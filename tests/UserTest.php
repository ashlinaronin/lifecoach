<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/User.php";
    require_once "src/Project.php";

    $server = 'mysql:host=localhost:3306;dbname=lifecoach_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO ($server, $username, $password);

    class UserTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            User::deleteAll();
            //Project::deleteAll();
        }



        function test_save ()
        {
            //Arrange
            $name = "Bob";
            $email = "test@email.com";
            $test_user = new User($name,$email);
            $test_user->save();

            //Act
            $result = User::getAll();

            //Assert
            $this->assertEquals([$test_user],$result);

        }



        function test_authenticate ()
        {
            //Arrange
            $name = "Bob";
            $email = "test@email.com";
            $test_user = new User($name,$email);
            $test_user->save();

            //Act
            $result = User::authenticate($email);

            //Assert
            $this->assertEquals($test_user,$result);

        }




    }


?>
