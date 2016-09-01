<?php
   

	require_once 'includes/Twig/Autoloader.php';
    require_once ('includes/parse/autoload.php');

    use Parse\ParseClient;
    use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseACL;
    use Parse\ParsePush;
    use Parse\ParseUser;
    use Parse\ParseInstallation;
    use Parse\ParseException;
    use Parse\ParseAnalytics;
    use Parse\ParseFile;
    use Parse\ParseCloud;
    use Parse\ParseSessionStorage;

    session_start();
    $app_id = 'GBKoGmSywZrOJPdHzpZnFzfKqoPZ5nOvpfhnseIr';
    $rest_key = 'UTtaIElx7BCxftei5meS1GB8uk1T3wrKSrmEg21K';
    $master_key = 'wr6tBzUnsPEjgowX0LHaYA20ggme2KsJZH8AYjuk';

    ParseClient::initialize( $app_id, $rest_key, $master_key );
    ParseClient::setStorage( new ParseSessionStorage() );

    $user = $_POST['user'];
    $email = $user['uemail'];
    $pw = $user['upw'];
    
    try {
        $user1 = ParseUser::logIn($email, $pw);
        $_SESSION['user'] = $user;
    } catch (ParseException $ex) {  
        echo $ex;
    }
?>