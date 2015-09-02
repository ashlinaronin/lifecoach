<?php
    $journal = $app['controllers_factory'];

    $journal->get('/new_journal_entry', function() use ($app) {
        $time_zone = date_default_timezone_set('America/Los_Angeles');
        $todays_date = date("M-d-Y");
        $entries = Journal::getAll();
        $existing_entry = "";

        foreach($entries as $entry) {
            if ($entry->getDate() == date("Y-m-d")) {
                $existing_entry .= $entry->getContent();
            }
        }
            return $app['twig']->render('journal/new_journal_entry.html.twig', array('date' => $todays_date, 'existing_entry' => $existing_entry));
    });

    $journal->post('/save_journal_entry', function() use ($app) {
        $entry = $_POST['entry'];
        $time_zone = date_default_timezone_set('America/Los_Angeles');
        $entry_date = date("Y-m-d");
        $todays_entry = new Journal($entry, $entry_date);
        $todays_entry->save();


        return $app['twig']->render('journal/edit_journal_entry.html.twig', array('journal' => $todays_entry));
    });

    $journal->get('/entries/{id}', function($id) use ($app) {
      $entry = Journal::find($id);
      return $app ['twig']->render('journal/entry.html.twig', array('entry' => $entry));
    });

    $journal->patch('/entries/{id}', function($id) use ($app) {
      $entry = Journal::find($id);
      $entry_content = $entry->getContent();
      $latest_entry = $entry_content . " " . $_POST['content'];
      $entry->updateContent($latest_entry);
      return $app ['twig']->render('journal/entry.html.twig', array('entry' => $entry));
    });

    $journal->patch('/entries_edit/{id}', function($id) use ($app) {
    $entry = Journal::find($id);
    $entry_content = $entry->getContent();
    $latest_entry = $entry_content . " " . $_POST['content'];
    $entry->updateContent($latest_entry);
    return $app ['twig']->render('journal/entry.html.twig', array('entry' => $entry));
  });

    $journal->get('/entries_archive', function() use ($app){
      $entries = Journal::getAll();
      return $app['twig']->render('journal/entries_archive.html.twig', array('entries' => $entries));
    });




    // Place all urls in this file at /journal/*
    $app->mount('/journal', $journal);

 ?>
