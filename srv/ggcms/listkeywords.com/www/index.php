<?php
	
	define('GGCMS_DIR', '../');
	require(GGCMS_DIR . 'classes/StandardLibraries.php');
	
	$handler = new Handler();
	
	try {
		error_reporting(0);
		$handler->HandleRequest();
	} catch (Exception $exception) {
	//	print("My caught exception is...|" . $exception->getMessage() . "|");
	}
	
	exit(1);

?>