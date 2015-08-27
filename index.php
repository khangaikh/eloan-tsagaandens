<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseObject;
    use Parse\ParseQuery;
    
    //register autoloader
    Twig_Autoloader::register();
    //loader for template files
    $loader = new Twig_Loader_Filesystem('templates');
    //twig instance
    $twig = new Twig_Environment($loader, array(
        'cache' => 'cache',
    ));
    //load template file
        $twig->setCache(false);
    
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
    
    
    
?>
    
