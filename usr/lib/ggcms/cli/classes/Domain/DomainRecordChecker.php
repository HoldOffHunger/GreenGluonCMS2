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
		
		public function formatDomainRecords($args) {
			$total_records = $args['total_records'];
			
			$formatted_records = [];
			
			foreach($total_records as $total_record) {
				if(!array_key_exists($total_record['Type'], $formatted_records)) {
					$formatted_records[$total_record['Type']] = [];
				}
				
				$formatted_records[$total_record['Type']][] = $total_record;
			}
			
			return $formatted_records;
		}
		
		public function checkDomainRecordsForDomain() {
			$total_records = $this->getDigitalOceanDNSRecords([
				'quiet'=>TRUE,
			]);
			
			$formatted_records = $this->formatDomainRecords([
				'total_records'=>$total_records,
			]);
			
		#	print_r($formatted_records);
			
			$check_records_args = [
				'formatted_records'=>$formatted_records,
			];
			
			$this->checkDomainRecordsForDomain_SOA($check_records_args);
			$this->checkDomainRecordsForDomain_A($check_records_args);
			
			print(PHP_EOL);
			
			return TRUE;
		}
		
		public function checkDomainRecordsForDomain_A($args) {
			$this->checkDomainRecordsForDomain_A_2Records($args);
			$this->checkDomainRecordsForDomain_A_Type($args);
			$this->checkDomainRecordsForDomain_A_Data($args);
		}
		
		public function getIPv4AddressLookup() {
			$ipv4_addresses = $this->globals->IPv4Addresses();
			
			$ipv4_address_lookup = [];
			$ipv4_addresses_count = count($ipv4_addresses);
			
			for($i = 0; $i < $ipv4_addresses_count; $i++) {
				$ipv4_address = $ipv4_addresses[$i];
				
				$ipv4_address_lookup[$ipv4_address] = TRUE;
			}
			
			return $ipv4_address_lookup;
		}
		
		public function checkDomainRecordsForDomain_A_Data($args) {
			$formatted_records = $args['formatted_records'];
			
			print('Check A DNS Record - Data: ');
			$specific_records = $formatted_records['A'];
			$specific_records_count = count($specific_records);
			
			$ipv4_addresses = $this->getIPv4AddressLookup();
			
			$invalid_a_records = [];
			
			for($i = 0; $i < $specific_records_count; $i++) {
				$specific_record = $specific_records[$i];
				
				if(!array_key_exists($specific_record['Data'], $ipv4_addresses)) {
					$invalid_a_records[] = $specific_record;
				}
			}
			
			$invalid_a_records_count = count($invalid_a_records);
			
			if($invalid_a_records_count !== 0) {
				$this->failResults();
			} else {
				$this->successResults();
			}
			
			print(PHP_EOL);
			
			return TRUE;
		}
		
		public function checkDomainRecordsForDomain_A_Type($args) {
			$formatted_records = $args['formatted_records'];
			
			print('Check A DNS Record - Type: ');
			
			$specific_records = $formatted_records['A'];
			$specific_records_count = count($specific_records);
			
			$invalid_a_records = [];
			
			for($i = 0; $i < $specific_records_count; $i++) {
				$specific_record = $specific_records[$i];
				
				if($specific_record['Type'] !== 'A') {
					$invalid_a_records[] = $specific_record;
				}
			}
			
			$invalid_a_records_count = count($invalid_a_records);
			
			if($invalid_a_records_count !== 0) {
				$this->failResults();
			} else {
				$this->successResults();
			}
			
			print(PHP_EOL);
			
			return TRUE;
		}
		
		public function checkDomainRecordsForDomain_A_2Records($args) {
			$formatted_records = $args['formatted_records'];
			
			print('Check A DNS Record - 2 Records: ');
			
			$specific_records = $formatted_records['A'];
			
			$specific_records_count = count($specific_records);
			
			if($specific_records_count !== 2) {
				$this->failResults();
			} else {
				$this->successResults();
			}
			
			print(PHP_EOL);
			
			return TRUE;
		}
		
		public function checkDomainRecordsForDomain_SOA($args) {
			$this->checkDomainRecordsForDomain_SOA_1Record($args);
			$this->checkDomainRecordsForDomain_SOA_Type($args);
			
			return TRUE;
		}
		
		public function checkDomainRecordsForDomain_SOA_1Record($args) {
			$formatted_records = $args['formatted_records'];
			
			print('Check SOA (Start of Authority) DNS Record - 1 Record: ');
			
			$specific_records = $formatted_records['SOA'];
			
			$specific_records_count = count($specific_records);
			
			if($specific_records_count !== 1) {
				$this->failResults();
			} else {
				$this->successResults();
			}
			
			print(PHP_EOL);
			
			return TRUE;
		}
		
		public function checkDomainRecordsForDomain_SOA_Type($args) {
			$formatted_records = $args['formatted_records'];
			
			print('Check SOA (Start of Authority) DNS Record - Type: ');
			
			$specific_records = $formatted_records['SOA'];
			$specific_record = $specific_records[0];
			
			if($specific_record['Type'] !== 'SOA') {
				$this->failResults();
			} else {
				$this->successResults();
			}
			
			print(PHP_EOL);
			
			return TRUE;
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