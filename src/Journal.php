<?php

    class Journal
    {
      private $content;
      private $date;
      private $id;

      function __construct($content, $date, $id = null)
      {
        $this->content = $content;
        $this->date = $date;
        $this->id = $id;
      }

      function getContent()
      {
        return $this->content;
      }

      function setContent($new_content)
      {
        $this->content = $new_content;
      }

      function getDate()
      {
          return $this->date;
      }

      function setDate($new_date)
      {
          $this->date = $new_date;
      }

      function getId()
      {
          return $this->id;
      }

      function save()
      {
          $GLOBALS['DB']->exec("INSERT INTO journals (content, date)
          VALUES ('{$this->getContent()}', '{$this->getDate()}')");
          $this->id = $GLOBALS['DB']->lastInsertId();
      }

      static function getAll()
      {
          $returned_journals = $GLOBALS['DB']->query("SELECT * FROM journals;");

          $journals = array();

          foreach($returned_journals as $journal) {
              $journal_content = $journal['content'];
              $journal_date = $journal['date'];
              $journal_id = $journal['id'];
              $new_journal = new Journal($journal_content, $journal_date, $journal_id);
              array_push($journals, $new_journal);
          }

         return $journals;
      }

      static function deleteAll()
      {
          $GLOBALS['DB']->exec("DELETE FROM journals;");
      }

      static function find($search_id)
      {
          $found_journal = null;
          $journals = Journal::getAll();
          foreach($journals as $journal) {
              $journal_id = $journal->getId();
              if ($journal_id == $search_id) {
                  $found_journal = $journal;
              }
          }

          return $found_journal;
      }

      function updateContent($new_journal_content)
      {
          $GLOBALS['DB']->exec("UPDATE journals SET content = '{$new_journal_content}' WHERE id = {$this->getId()};");
          $this->setContent($new_journal_content);
      }

      function updateDate($new_journal_date)
      {
          $GLOBALS['DB']->exec("UPDATE journals SET date = '{$new_journal_date}' WHERE id = {$this->getId()};");
          $this->setDate($new_journal_date);
      }

      function delete()
      {
          $GLOBALS['DB']->exec("DELETE FROM journals WHERE id = {$this->getId()};");
      }

      function checkDate()
      {
        if ($this->date == date("Y-m-d")) {
          return $this->content;
        }
      }


      static function lastEntry()
      {
        $entries = Journal::getAll();

        $dates = array();

        foreach($entries as $entry) {
          $entry_date = $entry->getDate();
          array_push($dates, $entry_date);

        }

        $last_entry = max($dates);
        return $last_entry;

      }

      static function findDate($search_date)
      {
          $found_journal = null;
          $journals = Journal::getAll();
          foreach($journals as $journal) {
              $entry_date = $journal->getDate();
              if ($entry_date == $search_date) {
                  $found_journal = $journal;
              }
          }

          return $found_journal;
      }
    }

?>
