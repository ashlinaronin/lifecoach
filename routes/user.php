<?php
    $user = $app['controllers_factory'];


    // Stringent authentication

    // Sign in
    $user->get('/sign_in', function() use ($app) {
        return $app['twig']->render('sign_in.html.twig');
    });


    $user->post("/dashboard", function() use ($app) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = new User(
            "Mitch",
            // preg_quote($_POST['name'], "'"),
            preg_quote($_POST['email'], "'"),
            preg_quote($_POST['password'], "'")
        );
        $user->save();
        return $app['twig']->render('dashboard.html.twig', array('user' => $user)); // Add array
    });



    // Sign up
    // $user->get('/sign_up', function() use ($app) {
    //     return $app['twig']->render('sign_up.html.twig');
    // });
    //
    // $user->post("/sign_up", function() use ($app) {
    //     $user = new User(
    //         preg_quote($_POST['name'], "'"),
    //         preg_quote($_POST['email'], "'"),
    //         preg_quote($_POST['password'], "'")
    //     );
    //     $user->save();
    //     return $app['twig']->render('dashboard.html.twig'); // Add array
    // });


    $app->mount('/user', $user);

?>
