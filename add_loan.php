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

    $types = $_POST['types'];
    $rates = $_POST['rates'];
    
    //$types =  explode (",", $types1 );

    //Clean old db
    $query = new ParseQuery("loan_types");
    $query->equalTo("status",1);
    
    $results = $query->find();
    
    for ($i = 0; $i < count($results); $i++) { 
        $object = $results[$i];
        try {
              $object->destroy();
              //echo 'Шинэ зээлийн бүртгэлийн код ' . $customer->getObjectId();
        } catch (ParseException $ex) {  
              // Execute any logic that should take place if the save fails.
              // error is a ParseException object with an error code and message.
              echo 'Failed to delete new object, with error message: ' . $ex->getMessage();
        }
    }

    $query = new ParseQuery("loan_rates");
    $query->equalTo("status",1);
    
    $results = $query->find();

    for ($i = 0; $i < count($results); $i++) { 
        $object = $results[$i];
        try {
              $object->destroy();
              //echo 'Шинэ зээлийн бүртгэлийн код ' . $customer->getObjectId();
        } catch (ParseException $ex) {  
              // Execute any logic that should take place if the save fails.
              // error is a ParseException object with an error code and message.
              echo 'Failed to delete new object, with error message: ' . $ex->getMessage();
        }
    }

    //Insert new ones 
    for ($i = 0; $i < count($types); $i++) { 
        $j =$i+1;
        $loan_type = new ParseObject("loan_types");  
        $loan_type ->set("name",(string)$types[$i]);
        $loan_type ->set("value",(string)$j);
        $loan_type ->set("status",1);
        try {
            $loan_type->save();
      //echo 'Шинэ зээлийн бүртгэлийн код ' . $customer->getObjectId();
        } catch (ParseException $ex) {  
          // Execute any logic that should take place if the save fails.
          // error is a ParseException object with an error code and message.
          echo 'Failed to create new object, with error message: ' . $ex->getMessage();
        }
    }
    //Insert new ones 
    for ($i = 0; $i < count($rates); $i++) { 
        $loan_rate = new ParseObject("loan_rates");  
        $loan_rate ->set("rate",(int)$rates[$i]);
        $loan_rate ->set("status",1);
        try {
            $loan_rate->save();
      //echo 'Шинэ зээлийн бүртгэлийн код ' . $customer->getObjectId();
        } catch (ParseException $ex) {  
          // Execute any logic that should take place if the save fails.
          // error is a ParseException object with an error code and message.
          echo 'Failed to create new object, with error message: ' . $ex->getMessage();
        }
    }

    echo 'Амжилттай ';
?>