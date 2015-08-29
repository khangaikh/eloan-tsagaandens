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

    $value = $_POST['value'];
    $pk = $_POST['pk'];
    $pay = $_POST['name'];

    $query = new ParseQuery("grafiks");
    $query->equalTo("objectId",$pk);
    $graphic = $query->first();
    $due =  $graphic -> get("due_pay");
    $today = date("Y-m-d");   

    if($pay == 1){
      $left = $due - $value;
      $graphic -> set("paid_amount1",(double)$value);
      $graphic -> set("not_paid",(double)$left);
      $graphic -> set("pay_date1",$today);

      try {
        $graphic->save();
        echo $left;
      } catch (ParseException $ex) {  
        // Execute any logic that should take place if the save fails.
        // error is a ParseException object with an error code and message.
        echo 'Failed to create new object, with error message: ' . $ex->getMessage();
      }
    }else if($pay==2){
      $paid1 =  $graphic -> get("paid_amount1");
      $left = $due - ($value+$paid1);
      $graphic -> set("paid_amount2",(double)$value);
      $graphic -> set("not_paid",(double)$left);
      $graphic -> set("pay_date2",$today);

      try {
        $graphic->save();
        echo $left;
      } catch (ParseException $ex) {  
        // Execute any logic that should take place if the save fails.
        // error is a ParseException object with an error code and message.
        echo 'Failed to create new object, with error message: ' . $ex->getMessage();
      }

    }else if($pay==3){
      $paid1 =  $graphic -> get("paid_amount1");
      $paid2 =  $graphic -> get("paid_amount2");
      $left = $due - ($value+$paid1+$paid2);
      $graphic -> set("paid_amount3",(double)$value);
      $graphic -> set("not_paid",(double)$left);
      $graphic -> set("pay_date3",$today);

      try {
        $graphic->save();
        echo $left;
      } catch (ParseException $ex) {  
        // Execute any logic that should take place if the save fails.
        // error is a ParseException object with an error code and message.
        echo 'Failed to create new object, with error message: ' . $ex->getMessage();
      }
    }else if($pay==4){
      $graphic -> set("status",1);

      try {
        $graphic->save();
        echo 1;
      } catch (ParseException $ex) {  
        // Execute any logic that should take place if the save fails.
        // error is a ParseException object with an error code and message.
        echo 0;
      }
    }
    
?>