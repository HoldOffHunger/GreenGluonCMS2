<?php

	#depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/DBAccess.php');
	clireq('traits/CLIAccess.php');
	
	class DatabaseBackup {
		use DBAccess;
		use CLIAccess;
		
		public function backup() {
			$this->setHandle();
			$this->bannerMessage();
			
			if(!$this->setDomain()) {
				return $this->cancelAction(['message'=>'Invalid domain.  Please submit a FQDN in the form of `example.com`.']);
			}
			
			if($this->userConfirm()) {
				$this->setGlobals();
				$this->setMySQLArgs();
				
				$this->runMySQLTest(['display'=>'short']);
			}
			
			print("\n\nSOYBEANS!!!!");
		}
	}
	
?>