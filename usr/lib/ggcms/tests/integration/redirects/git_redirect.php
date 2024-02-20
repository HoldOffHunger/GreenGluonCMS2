<?php
	use PHPUnit\Framework\TestCase;

	require('/var/www/ggcms_install_directories.php');
	require(GGCMS_DIR . 'classes/System/GlobalFunctions.php');
	
	depreq('vendor/autoload.php');
	
	use GuzzleHttp\Client;
	require('/usr/lib/ggcms/tests/traits/Files/TestFile.php');
	testreq('traits/Redirects/GitRedirect.php');
	
	ggreq('classes/Development/Version.php');
	
	class GitRedirect extends TestCase {
		use GitRedirectTrait;
	}
?>