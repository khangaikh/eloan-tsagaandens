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
    
    $new = $_POST['loan'];
    $query = new ParseQuery("loan");
    $query->equalTo("objectId",$new);
    $loan = $query->first();
    $loan -> set("status", 0);

    try {
      $loan->save();
    } catch (ParseException $ex) {  
      // Execute any logic that should take place if the save fails.
      // error is a ParseException object with an error code and message.
      echo 'Failed to create new object, with error message: ' . $ex->getMessage();
    }
    echo 'Эээлийн бүртгэл идэвхгүй боллоо ' . $loan->getObjectId();
?>