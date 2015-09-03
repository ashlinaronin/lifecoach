<?php

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
  require_once "src/Journal.php";
  $server = 'mysql:host=localhost:3306;dbname=lifecoach_test';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server, $username, $password);
  class JournalTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Journal::deleteAll();
        }

        function testGetContent()
        {
            $content = "Today I walked the dog.";
            $date  = "2012-01-17";


            $test_journal = new Journal($content, $date);
            $result = $test_journal->getContent();

            $this->assertEquals($content, $result);

        }

        function testSetContent()
        {
            $content = "Today I walked the dog.";
            $date  = "2012-01-17";


            $test_journal = new Journal($content, $date);
            $result = $test_journal->getContent();

            $this->assertEquals("Today I walked the dog.", $result);
        }

        function testGetDate()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");

            

            $test_journal = new Journal($content, $date);
            $result = $test_journal->getDate();

            $this->assertEquals($date, $result);

        }

        // function testSetDate()
        // {
        //     $content = "Today I walked the dog.";
        //     $date  = "2012-01-17";
        //
        //     $test_journal = new Journal($content, $date);
        //     $result = $test_journal->getDate();
        //
        //     $this->assertEquals("2012-01-17", $result);
        // }


        function testGetId()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");
            $id = 4;

            $test_journal = new Journal($content, $date, $id);

            $result = $test_journal->getId();

            $this->assertEquals(4, $result);
        }

        function testSave()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");
            $test_journal = new Journal($content, $date);


            $test_journal->save();

            $this->assertEquals(is_numeric($test_journal->getId()), true);


        }

        function testGetAll()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");
            $test_journal = new Journal($content, $date);
            $test_journal->save();

            $content2 = "What is the meaning of life?";
            $date2 = date("Y-m-d");
            $test_journal2 = new Journal($content2, $date2);
            $test_journal2->save();


            $result = Journal::getAll();




            $this->assertEquals([$test_journal, $test_journal2], $result);

        }

        function testDeleteAll()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");
            $test_journal = new Journal($content, $date);
            $test_journal->save();

            $content2 = "What is the meaning of life?" ;
            $date2 = date("Y-m-d");

            $test_journal2 = new Journal($content2, $date2);
            $test_journal2->save();

            Journal::deleteAll();
            $result = Journal::getAll();

            $this->assertEquals([], $result);
        }

        function testFind()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");
            $test_journal = new Journal($content, $date);
            $test_journal->save();

            $content2 = "What is the meaning of life?";
            $date2 = date("Y-m-d");
            $test_journal2 = new Journal($content2, $date2);
            $test_journal2->save();

            $result = Journal::find($test_journal->getId());

            $this->assertEquals($test_journal, $result);
        }

        function testUpdateContent()
        {
            $content = "Today I walked the dog.";
            $date  = "2012-01-17";
            $test_journal = new Journal($content, $date);
            $test_journal->save();

            $new_content = "Nevermind, I did not walk the dog";

            $result = $test_journal->updateContent($new_content);

            $this->assertEquals("Nevermind, I did not walk the dog", $test_journal->getContent());
        }

        function testUpdateDate()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");
            $test_journal = new Journal($content, $date);
            $test_journal->save();

            $new_date  = date("Y-m-d");

            $result = $test_journal->updateDate($new_date);

            $this->assertEquals(date("Y-m-d"), $test_journal->getDate());
        }


        function testDelete()
        {
            $content = "Today I walked the dog.";
            $date  = date("Y-m-d");
            $test_journal = new Journal($content, $date);
            $test_journal->save();

            $content2 = "What is the meaning of life?" ;
            $date2 = date("Y-m-d");
            $test_journal2 = new Journal($content2, $date2);
            $test_journal2->save();

            $test_journal->delete();

            $this->assertEquals([$test_journal2], Journal::getAll());


        }

        function testCheckDate()
        {
          $content = "What is the meaning of life?" ;
          $date = date("Y-m-d");
          $test_journal = new Journal($content, $date);
          $test_journal->save();

          $result = $test_journal->checkDate();

          $this->assertEquals($content, $result);

        }

        function testLastEntry()
        {
          $content = "What is the meaning of life?" ;
          $date = date("Y-m-d");
          $test_journal = new Journal($content, $date);
          $test_journal->save();

          $content2 = "I hate my dad";
          $date2 = "2012-09-13";
          $test_journal2 = new Journal($content2, $date2);
          $test_journal2->save();

          $result = Journal::lastEntry();

          $this->assertEquals($date, $result);

        }
    }
?>
