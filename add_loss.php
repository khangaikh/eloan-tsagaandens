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