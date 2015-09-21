<?php
	require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
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
    
    $_SESSION = "Array ( [parseData] => Array ( [user] => Parse\ParseUser [_sessionToken:protected] => r:LAy74EsFuobzvlAQgmRdF00LV [serverData:protected] => Array ( [username] => admin ) [operationSet:protected] => Array ( ) [estimatedData:Parse\ParseObject:private] => Array ( [username] => admin ) [dataAvailability:Parse\ParseObject:private] => Array ( [sessionToken] => 1 [username] => 1 ) [className:Parse\ParseObject:private] => _User [objectId:Parse\ParseObject:private] => D6C1mdK86t [createdAt:Parse\ParseObject:private] => DateTime Object ( [date] => 2015-08-28 18:56:27.592000 [timezone_type] => 2 [timezone] => Z ) [updatedAt:Parse\ParseObject:private] => DateTime Object ( [date] => 2015-08-28 18:56:27.592000 [timezone_type] => 2 [timezone] => Z ) [hasBeenFetched:Parse\ParseObject:private] => 1 ) ) [count] => 1 ) ";

    $data = $_POST['data'];
    
    $user = new ParseUser();
    $user->set("username", $data['username']);
    $user->set("password", $data['password']);

    try {
      $user.save();
      $user->signUp();
      // Hooray! Let them use the app now.
    } catch (ParseException $ex) {
      // Show the error message somewhere and let the user try again.
      echo 0;
    }
    
    $query = new ParseQuery("_User");
    $users = $query->find();

    class Event {}

    $events = array();

    $results = $db->query("SELECT * FROM room_types ORDER BY name ASC");

    foreach ($users as $row) {
        $e = new Event();
        $e->id = $row['objectId'];
        $e->name = $row['username'];
        $e->short = $row['password'];

        $events[] = $e; 
    }

    header('Content-Type: application/json');
    echo json_encode($users);
?>