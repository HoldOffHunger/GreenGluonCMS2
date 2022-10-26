<?php

	depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/DBAccess.php');
	clireq('traits/DomainValidation.php');
	clireq('traits/CLIAccess.php');
	clireq('traits/GlobalsTrait.php');
	
	class List500 {
		use DBAccess;
		use DomainValidation;
		use CLIAccess;
		use GlobalsTrait;
		
		public function list500Errors() {
			$this->setHandle();
			$this->bannerMessage();
			
			if(!$this->setDomain()) {
				return $this->cancelAction(['message'=>'Invalid domain.  Please submit a FQDN in the form of `example.com`.']);
			}
			
			$this->userInputType();
			$this->setGlobals();
			$this->setMySQLArgs();
			$this->getAndList500Errors();
			
			return $this->cancelAction(['message'=>'User cancelled.']);
		}
		
		public function getAndList500Errors(){
			$sql_command = 'SELECT COUNT(URL) as Count, URL from ' . $this->host . '.InternalServerError GROUP BY URL ORDER BY Count DESC LIMIT ' . $this->answer_type . ';';
			print("Getting 500's for " . $this->domain . ".\n\n");
			
			$create_database_command = 'mysql ' . $this->base_sql_args . '-e "' . $sql_command . '"';
				
			$output = shell_exec($create_database_command);
			
			if(strlen($output) === 0) {
				print("\n" . 'No 500\'s.  Hooray!' . "\n\n");
			} else {
				print($this->formatTable(['output'=>$output]));
			}
		#	print_r($output);
		#	$output_pieces = explode("\t", $output);
		#	print("BT: COUNT!" . count($output_pieces) . "|");
			
			print("500's successfully retrieved for " . $this->domain . ".\n\n");
			
			return TRUE;
		}
		
		public function userInputType() {
			print("\n\n");
			print('How many?  (Default is 10.)');
			
			$this->answer_type = strtolower(trim(fgets($this->handle)));
			
			if(!$this->answer_type) {
				$this->answer_type = 10;
			}
			
			return true;
		}
		
		public function userConfirm() {
			return $this->basicConfirmDialogue([
				'message'=>'Overall installation beginning.',
			]);
		}
		
		public function bannerMessageText() {
			return 'List 500\'s';
		}
		
		public function confirmDomainText() {
			return 'Getting 500\'s For: ';
		}
	}
	
?>