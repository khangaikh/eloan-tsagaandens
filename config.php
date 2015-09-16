<?php

	require_once 'includes/parse/autoload.php';
	use Parse\ParseClient;
    
    $app_id = 'GBKoGmSywZrOJPdHzpZnFzfKqoPZ5nOvpfhnseIr';
    $rest_key = 'UTtaIElx7BCxftei5meS1GB8uk1T3wrKSrmEg21K';
    $master_key = 'wr6tBzUnsPEjgowX0LHaYA20ggme2KsJZH8AYjuk';
    ParseClient::initialize( $app_id, $rest_key, $master_key );

?>