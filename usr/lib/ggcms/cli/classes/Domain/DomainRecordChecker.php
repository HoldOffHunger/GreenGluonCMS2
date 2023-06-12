<?php

	depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/Apache.php');
	clireq('traits/Base64.php');
	clireq('traits/CLIAccess.php');
	clireq('traits/DataArrays.php');
	clireq('traits/DBAccess.php');
	clireq('traits/DBTest.php');
	clireq('traits/Directories.php');
	clireq('traits/DNSRecords.php');
	clireq('traits/ErrorCLI.php');
	clireq('traits/FileSystem.php');
	clireq('traits/GlobalsTrait.php');
	clireq('traits/SSL.php');
	clireq('traits/VersionNumber.php');
	ggreq('traits/ReverseDNSNotation.php');
	clireq('traits/DigitalOceanDNSRecords.php');
	
	class DomainRecordChecker {
		use Apache;
		use Base64;
		use CLIAccess;
		use DataArrays;
		use DBAccess;
		use DBTest;
		use Directories;
		use DNSRecords;
		use ErrorCLI;
		use FileSystem;
		use GlobalsTrait;
		use SSL;
		use VersionNumber;
		use ReverseDNSNotation;
		use DigitalOceanDNSRecords;
		
		public function checkDomainRecords() {
			$this->setHandle();
			$this->bannerMessage();
			
			if(!$this->setDomain()) {
				return $this->cancelAction(['message'=>'Invalid domain.  Please submit a FQDN in the form of `example.com`.']);
			}
			
			if($this->userConfirm()) {
				$this->setGlobals();
				
				$this->checkDomainRecordsForDomain();
				
				return TRUE;
			}
			
			return $this->cancelAction(['message'=>'User cancelled.']);
		}
		
		public function checkDomainRecordsForDomain() {
			$total_records = $this->getDigitalOceanDNSRecords([
				'quiet'=>TRUE,
			]);
			
			$formatted_records = $this->formatDomainRecords([
				'total_records'=>$total_records,
			]);
			
			print_r($formatted_records);
			
			$this->checkDomainRecordsForDomain_SOA();
		}
		
		public function formatDomainRecords($args) {
			$total_records = $args['total_records'];
			
			$formatted_records = [];
			
			foreach($total_records as $total_record) {
				if(!array_key_exists($total_record['Type'], $formatted_records)) {
					$formatted_records[$total_record['Type']] = [];
				}
				
				$formatted_records[$total_record['Type']][$total_record['Data']] = $total_record;
			}
			
			return $formatted_records;
		}
		
		public function checkDomainRecordsForDomain_SOA() {
		}
		
					// Script-Level Functions
		
		public function userConfirm() {
			return $this->basicConfirmDialogue([
				'message'=>'Overall checking beginning.',
			]);
		}
		
		public function bannerMessageText() {
			return 'Domain DNS-Record Checker';
		}
		
		public function confirmDomainText() {
			return 'Checking Domain DNS-Records: ';
		}
	}
	
?>