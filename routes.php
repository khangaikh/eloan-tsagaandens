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
    
   
       $template = $twig->loadTemplate('add_loan.html');
        $query = new ParseQuery("loan");
        $query->equalTo("status",1);
        $results = $query->find();
        //render a template
        echo $template->render(array('title' => 'Шинэ зээл'));  
    
?>