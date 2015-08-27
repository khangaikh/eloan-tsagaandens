<?php
	require_once 'index.php';
  
    
   
       $template = $twig->loadTemplate('add_loan.html');
        $query = new ParseQuery("loan");
        $query->equalTo("status",1);
        $results = $query->find();
        //render a template
        echo $template->render(array('title' => 'Шинэ зээл'));  
    
?>