<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";

    use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseInstallation;
    use Parse\ParseException;
    use Parse\ParseClient;
    use Parse\ParseSessionStorage;


    //register autoloader
    Twig_Autoloader::register();
    //loader for template files
    $loader = new Twig_Loader_Filesystem('templates');
    //twig instance
    $twig = new Twig_Environment($loader, array(
        'cache' => 'cache',
    ));
    //load template file
    function fixObject (&$object)
    {
      if (!is_object ($object) && gettype ($object) == 'object')
        return ($object = unserialize (serialize ($object)));
      return $object;
    }

    $twig->setCache(false);

    if ($_SESSION['parseData']) { 
         $_SESSION = "Array ( [parseData] => Array ( [user] => Parse\ParseUser [_sessionToken:protected] => r:LAy74EsFuobzvlAQgmRdF00LV [serverData:protected] => Array ( [username] => admin ) [operationSet:protected] => Array ( ) [estimatedData:Parse\ParseObject:private] => Array ( [username] => admin ) [dataAvailability:Parse\ParseObject:private] => Array ( [sessionToken] => 1 [username] => 1 ) [className:Parse\ParseObject:private] => _User [objectId:Parse\ParseObject:private] => D6C1mdK86t [createdAt:Parse\ParseObject:private] => DateTime Object ( [date] => 2015-08-28 18:56:27.592000 [timezone_type] => 2 [timezone] => Z ) [updatedAt:Parse\ParseObject:private] => DateTime Object ( [date] => 2015-08-28 18:56:27.592000 [timezone_type] => 2 [timezone] => Z ) [hasBeenFetched:Parse\ParseObject:private] => 1 ) ) [count] => 1 ) ";
        
        if(!empty($_GET["cusId"])){
            $cus_id = $_GET["cusId"];
            $template = $twig->loadTemplate('customer_find.html');
            $query = new ParseQuery("customer");
            $query->equalTo("objectId",$cus_id);
            $cus = $query->first();
            $name = $cus->get("register_no");
            //render a template
            echo $template->render(array('title' => $name, 'cus' => $cus)); 
        }
        else if(!empty($_GET["loanId"])){
            $template = $twig->loadTemplate('dashboard_find.html');
            $cusId = $_GET["loanId"];
            $query = new ParseQuery("customer");
            $query->equalTo("objectId", $cusId);
            $customer = $query->first();
            $register = $customer->get("register_no");
            $title = $register;
            $query = new ParseQuery("loan");
            $query->equalTo("customer",$customer);
            $loans = $query->find();
            //render a template
            echo $template->render(array('title' => $title, 'loans' => $loans)); 
        }
        else{
            if(empty($_GET["cid"])){
            $template = $twig->loadTemplate('dashboard.html');
            $query = new ParseQuery("loan");
            $query->notEqualTo("status",0);
            $results = $query->find();
            //render a template
            echo $template->render(array('title' => 'Самбар', 'loans' => $results)); 
        }else if($_GET["cid"]==2){
            $query = new ParseQuery("loan_types");
            $query->descending("createdAt");
            $results = $query->find();
            $template = $twig->loadTemplate('add_loan.html');
           
            //render a template
            echo $template->render(array('title' => 'Шинэ зээл','loan_types'=>$results));  
        }else if($_GET["cid"]==3){
           $template = $twig->loadTemplate('setting.html');
            $query = new ParseQuery("loss");
            $query->equalTo("status",1);
            $loss = $query->first();
            $query = new ParseQuery("loan_types");
            $query->equalTo("status",1);
            $types = $query->find();
            $query = new ParseQuery("loan_rates");
            $query->equalTo("status",1);
            $rates = $query->find();
    
            //render a template
            echo $template->render(array('title' => 'Үндсэн тохиргоо','loss' => $loss,"types" => $types, "rates" => $rates));  
        }
        else if($_GET["cid"]==4){
           $template = $twig->loadTemplate('customer.html');
            $query = new ParseQuery("customer");
            $customers = $query->find();    
            //render a template
            echo $template->render(array('title' => 'Үйлчлүүлэгч','customers' => $customers));  
        }
        else if($_GET["cid"]==5){
            session_destroy();
            session_unset();
            $template = $twig->loadTemplate('login.html');
            //render a template
            echo $template->render(array('title' => 'Нэвтрэх'));  
        }
        else{
            $template = $twig->loadTemplate('edit_loan.html');
            $query = new ParseQuery("loan");
            $query->equalTo("objectId",$_GET["cid"]);
            $results = $query->first();
            $id=$results->get("customer")->getObjectId();
            $query = new ParseQuery("customer");
            $query->equalTo("objectId", $id);
            $customer = $query->first();

            $query = new ParseQuery("grafiks");
            $query->equalTo("loan", $results);
            $query->ascending("step");
            $graphics = $query->find();

            //render a template
            echo $template->render(array('title' => 'Зээл дэлгэрэнгүй', 'loan' => $results, 'customer' => $customer, 'graphics'=> $graphics));  
        }
        }
    }else{
         $template = $twig->loadTemplate('login.html');
         //render a template
         echo $template->render(array('title' => 'Нэвтрэх'));
    }
?>
    
