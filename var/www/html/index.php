<?php

	require('/var/www/ggcms_install_directories.php');
	require(GGCMS_DIR . 'classes/System/GlobalFunctions.php');
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	try {
		error_reporting(0);
		require(GGCMS_DIR . 'classes/StandardLibraries.php');
		$handler = new Handler();
		$handler->HandleRequest();
	} catch (Exception $exception) {
		print("My caught exception is...|" . $exception->getMessage() . "|");
	}
	
	exit(1);

?>