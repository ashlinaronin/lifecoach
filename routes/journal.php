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
            return $app['twig']->render('new_journal_entry.html.twig', array('date' => $todays_date, 'existing_entry' => $existing_entry));
    });

    $journal->post('/save_journal_entry', function() use ($app) {
        $entry = $_POST['entry'];
        $time_zone = date_default_timezone_set('America/Los_Angeles');
        $todays_date = date("M-d-Y");

        $entry_date = date("Y-m-d");
        $todays_entry = new Journal($entry, $entry_date);
        $todays_entry->save();
        if ($todays_entry->getDate() == date("Y-m-d")) {
            $existing_entry = $todays_entry->getContent();
        }
        return $app['twig']->render('new_journal_entry.html.twig', array('date' => $todays_date, 'existing_entry' => $existing_entry, 'todays_entry' => $todays_entry));
    });




    // Place all urls in this file at /journal/*
    $app->mount('/journal', $journal);

 ?>
