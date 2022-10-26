<?php

		/*
		
				// Standard Defaults
		
			define('GGCMS_DIR', '/usr/lib/ggcms/src/');
			define('GGCMS_DEP_DIR', '/usr/lib/ggcms/dep/');
			define('GGCMS_LOG_DIR', '/var/log/ggcms/');
			define('GGCMS_DATA_DIR', '/srv/ggcms/');
			define('GGCMS_CONFIG_DIR', '/etc/ggcms/');
			define('GGCMS_DOC_ROOT', '/var/www/html/');
		
		*/
	
	function ggreq($filename) {
		return require(GGCMS_DIR . $filename);
	}

	function depreq($filename) {
		return require(GGCMS_DEP_DIR . $filename);
	}

	function gglog($filename, $mode) {
		return fopen(GGCMS_LOG_DIR . $filename, $mode);
	}

	function datareq($filename) {
		return require(GGCMS_DATA_DIR . $filename);
	}

	function confreq($filename) {
		return require(GGCMS_CONFIG_DIR . $filename);
	}
	
	function conf_isfile($filename) {
		return is_file(GGCMS_CONFIG_DIR . $filename);
	}
	
	function webroot_isfile($filename) {
		return is_file(GGCMS_DOC_ROOT . $filename);
	}
	
	function data_isfile($filename, $handler) {
		$primary_domain = $handler->domain->primary_domain_lowercased;
		$file_location = GGCMS_DATA_DIR . $primary_domain . '/www/' . $filename;
	#	print("<!-- FILE ----- BT:! " . $file_location . "|||| -->");
		return is_file($file_location);
	}
	
	function data_reqfile($filename, $handler) {
		$primary_domain = $handler->domain->primary_domain_lowercased;
		$file_location = GGCMS_DATA_DIR . $primary_domain . '/www/' . $filename;
	#	print("<!-- FILE ----- BT:! " . $file_location . "|||| -->");
		return print(file_get_contents($file_location));
	}

?>