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

    function calculatePayment($price, $down, $term)
    {
    $loan = $price - $down;
    $rate = (2.5/100) / 12;
    $month = $term * 12;
    $payment = floor(($loan*$rate/(1-pow(1+$rate,(-1*$month))))*100)/100;
    return $payment;
    }

    $new = $_POST['loan'];

    $query = new ParseQuery("customer");
    $query->equalTo("objectId",$new['customer_id']);
    $customer = $query->first();
   // $customer = new ParseObject("customer");  
    $customer -> set("name",$new['name']);
    $customer -> set("surname",$new['surname']);
    $customer -> set("family_name",$new['family_name']);
    $customer -> set("register_no",$new['register_number']);
    $customer -> set("email",$new['email']);
    $customer -> set("mobile_number",$new['mobile']);
    $customer -> set("gender",$new['gender']);
    $customer -> set("city",$new['city']);
    $customer -> set("address",$new['address']);
    $customer -> set("status","Шинэ");
    $customer -> set("more_info",$new['more_info']);

    try {
      $customer->save();
      //echo 'Шинэ зээлийн бүртгэлийн код ' . $customer->getObjectId();
    } catch (ParseException $ex) {  
      // Execute any logic that should take place if the save fails.
      // error is a ParseException object with an error code and message.
      echo 'Failed to create new object, with error message: ' . $ex->getMessage();
    }
    
    //$loan = new ParseObject("loan");
    $query = new ParseQuery("loan");
    $query->equalTo("objectId",$new['loan_id']);
    $loan = $query->first();

    $loan -> set("rate",(double)$new['rate']);
    $loan -> set("type", $new['loan_type']);
    $loan -> set("start", $new['start']);
    $loan -> set("duration", (int)$new['duration']);
    $loan -> set("loan_amount", (double) $new['loan_total']);
    $loan -> set("status", (int)$new['status']);
    $loan -> set("customer", $customer);
    $loan -> set("mortage_type", $new['mortage_type']);
    $loan -> set("mortage_name", $new['mortage_name']);
    $loan -> set("mortage_number", $new['mortage_no']);
    $loan -> set("mortage_more", $new['mortage_more']);
    $loan -> set("mortage_owner", $new['mortage_owner']);
    $loan -> set("more_info", $new['more_info']);
    $loan -> set("mortage_extra", $new['mortage_extra']);
    $loan -> set("name", $new['name']);
    $loan -> set("surname", $new['surname']);
    $loan -> set("customer_register", $new['register_number']);
    $loan -> set("phone_number", $new['mobile']);

    try {
      $loan->save();
    } catch (ParseException $ex) {  
      // Execute any logic that should take place if the save fails.
      // error is a ParseException object with an error code and message.
      echo 'Зээлийн хэсгийн алдаа: ' . $ex->getMessage();
    }
    if($_POST['plot']==1){
        $princ = (double)$new['loan_total'];
        $term  = (int)$new['duration'];
        $rate_n = (double)$new['rate'];
        $intr   = ($rate_n / 1200)*12;
        $pmt = $princ * $intr / (1 - (pow(1/(1 + $intr), $term)));
        $pmt = round($pmt,2);
        for($i=0; $i<$term; $i++){
            $j=$i+1;
            //Adding more months
            $monthadd = "P".$j."M";
            $date = new DateTime($new['start']);
            $date->add(new DateInterval($monthadd));
            $date1 = $date->format('Y-m-d');

            $grafik = new ParseObject("grafiks");
            $grafik -> set("date",$date1);
            $grafik -> set("step",$j);
            $grafik -> set("pay_amount",$pmt);
            $grafik -> set("due_pay",$pmt);
            $rate_amount = round(($princ * ($rate_n/100)),2);
            $grafik -> set("rate_amount",$rate_amount);
            $normal_amount = $pmt - $rate_amount;
            $grafik -> set("normal_amount",$normal_amount);
            $left_amount = round($princ - $normal_amount);
            $grafik -> set("left_amount",$left_amount);
            $grafik -> set("loss_day",0);
            $grafik -> set("loss_amount",0);
            $grafik -> set("loan",$loan);
            $princ = $left_amount;
            try {
                $grafik->save();
              //echo 'Шинэ зээлийн бүртгэлийн код ' . $l->getObjectId();
            } catch (ParseException $ex) {  
              // Execute any logic that should take place if the save fails.
              // error is a ParseException object with an error code and message.
              echo 'Failed to create new object, with error message: ' . $ex->getMessage();
            }
            $grafik = null;
    }
    }
   
    echo 'Эээлийн бүртгэл шинэчлэгдлээ ' . $loan->getObjectId();
?>