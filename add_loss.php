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

    $daily = (double)$_POST['daily'];
    $monthly = (double)$_POST['monthly'];
    $loss_max = (double)$_POST['loss_max'];

    $query = new ParseQuery("loss");
    $query->equalTo("status",1);
    $loss = $query->first();  
    
    $loss->set("daily_loss",$daily);
    $loss->set("monthly_loss",$monthly);
    $loss->set("loss_max",$loss_max);

    try {
      $loss->save();
      //echo 'Шинэ зээлийн бүртгэлийн код ' . $customer->getObjectId();
    } catch (ParseException $ex) {  
      // Execute any logic that should take place if the save fails.
      // error is a ParseException object with an error code and message.
      echo 'Failed to create new object, with error message: ' . $ex->getMessage();
    }
    
    echo 'Амжилттай ' . $loss->getObjectId();
?>