<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";

    use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseInstallation;
    use Parse\ParseException;
    use Parse\ParseClient;
    use Parse\ParseSessionStorage;

    session_start();
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
            $query = new ParseQuery("_User");
            $users = $query->find();
            $query = new ParseQuery("configs");
            $inits = $query->first();
    
            //render a template
            echo $template->render(array('title' => 'Үндсэн тохиргоо','loss' => $loss,"types" => $types, "rates" => $rates, "users" => $users,'inits' =>$inits));  
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
        else if($_GET["cid"]==6){
            $template = $twig->loadTemplate('bank.html');
            //render a template
            echo $template->render(array('title' => 'Нийт гүйлгээ'));  
        }
        else{
            //Database operations
            $query = new ParseQuery("loss");
            $query->equalTo("objectId",'7hOOm7UuHu');
            $loss = $query->first();
            $daily_loss = $loss ->get('daily_loss');

            $query = new ParseQuery("loan");
            $query->equalTo("objectId",$_GET["cid"]);
            $loan = $query->first();

            $id = $loan->get("customer")->getObjectId();
            $query = new ParseQuery("customer");
            $query->equalTo("objectId", $id);
            $customer = $query->first();

            $query = new ParseQuery("grafiks");
            $query->equalTo("loan", $loan);
            $query->ascending("step");
            $graphics = $query->find();

            date_default_timezone_set('Asia/Ulaanbaatar');
            
            $today = new DateTime();
            $total_due = 0;
            $total_paid = 0;
            $total_loss = 0;
            $total_left = 0;

            $early_pay = 0;

            foreach($graphics as $graph){

                
                $pay_amount = $graph->get('pay_amount');
                $total_due = $total_due +  $pay_amount;

                $paid_amount = $graph->get('paid_amount1');
                $total_paid =  $total_paid +  $paid_amount;

                $loss = $graph->get('loss_amount');
                $total_loss =  $total_loss +  $loss;

                $date = $graph->get('date');
                $dEnd = new DateTime($date);
                
                if($graph->get('status')!=1) {
                    if($today>$dEnd){
                        $dDiff = $today->diff($dEnd);
                        $days = $dDiff->days;
                        $graph->set("loss_day",$days);
                        $loss_handler =  $graph->get("loss_handled");
                        if($loss_handler && $loss_handler != 1){
                            $left_amount = $graph->get('left_amount');
                            $loss_amount = round(($left_amount * ($daily_loss / 100) * $days),2);
                            $due_pay = $graph->get('rate_amount') + $loss_amount + $graph->get('normal_amount');
                            $graph->set("loss_amount",$loss_amount);
                            $is_due_pay_set = $graph->get("due_pay_handled");
                            if($is_due_pay_set && $is_due_pay_set!=1){
                                $graph->set("due_pay", $due_pay );
                            }
                            $graph->save();
                        }else{
                            $due_pay = $graph->get('pay_amount') + $graph->get('loss_amount');
                            $graph->set("due_pay", $due_pay );
                            $graph->save();
                        }
                    }else{
                        $graph->set("loss_day",0);
                    }
                }

                if($early_pay==1){
                    $graph->set('early_pay',1);
                    $early_pay = 0;
                    $early_rate = $graph->get("rate_amount")/2;
                    $graph->set("rate_amount", $early_rate );
                    $due_pay = ($graph->get('rate_amount')/2) + $loss_amount + $graph->get('normal_amount');
                    $graph->set("due_pay", $due_pay );
                }

                $paid_date = $graph->get('pay_date1');
                if($paid_date!=null){
                    $dStart = new DateTime($paid_date);
                    $dEarly = $dStart->diff($dEnd);
                    $isEarly =  $dEarly->days;
                    if($isEarly<=15){
                        $early_pay = 1;
                    } 
                }
                

                $paid = $graph->get('paid_amount1');
                $due = $graph->get('due_pay');
                $left = $due - $paid;
                $total_left = $total_left + $left;
                $graph->set("not_paid", $left);
                try {
                    $graph->save();
                } catch (ParseException $ex) {  
                    // Execute any logic that should take place if the save fails.
                    // error is a ParseException object with an error code and message.
                    echo 'Failed to create new object, with error message: ' . $ex->getMessage();
                }       
            }

            $loan->set('paid_amount', $total_paid);
            $loan->set('loss', $total_loss);
            $loan->set('left_amount', $total_left);
            $loan_amount = $loan->get('loan_amount');
            $rate_total = $total_due - $loan_amount;
            $loan->set('rate_total', $rate_total);
            $loan->save();
            //Render a template Twig
            $template = $twig->loadTemplate('edit_loan.html');
            echo $template->render(array('title' => 'Зээл дэлгэрэнгүй', 'loan' => $loan, 'customer' => $customer, 'graphics'=> $graphics));  
        }
        }
 
?>
    
