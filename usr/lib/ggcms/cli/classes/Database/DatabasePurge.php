<?php

	depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/Apache.php');
	clireq('traits/BackupTrait.php');
	clireq('traits/DBAccess.php');
	clireq('traits/DBTest.php');
	clireq('traits/CLIAccess.php');
	
	class DatabasePurge {
		use Apache;
		use BackupTrait;
		use DBAccess;
		use DBTest;
		use CLIAccess;
		
		public function purge() {
			$this->setHandle();
			$this->bannerMessage();
			
			if($this->userConfirm()) {
				$this->identifyArchivesToPurge();
			}
			
			return TRUE;
		}
		
		public function identifyArchivesToPurge() {
			print("Archives to Purge --");
			
			print("\n\n");
			
			$purge_files = [];
			
			$backup_directory = scandir(GGCMS_LOG_DIR);
			
			$domains = array_diff($backup_directory, ['.', '..']);
			
			foreach($domains as $domain) {
				$domain_dir = GGCMS_LOG_DIR . $domain . '/';
				
				$domain_applications_directory = scandir($domain_dir);
				
				$domain_applications = array_diff($domain_applications_directory, ['.', '..']);
				
			#	print("BT: content dir::: " . $domain_dir . "\n");
			#	print_r($domain_applications);
			#	print("\n\n");
				
				foreach($domain_applications as $domain_application) {
					$domain_application_location = $domain_dir . $domain_application . '/archive/';
					
					if(is_dir($domain_application_location)) {
						#print("BT: PURGE!" . $domain_application_location . "|\n\n");
						
						$domain_application_archive_directories = scandir($domain_application_location);
						
						$domain_application_archives = array_diff($domain_application_archive_directories, ['.', '..']);
						
					#	print_r($domain_application_archive_directories);
						
						$domain_application_archives_count = count($domain_application_archives);
						
						for($i = 2; $i < $domain_application_archives_count; $i++) {
							$domain_application_archive = $domain_application_archives[$i];
							
							$domain_application_archive_location = $domain_application_location . $domain_application_archive;
							
							$purge_files[] = $domain_application_archive_location;
							
						#	print("BT: killable archive????" . $domain_application_archive_location . "|\n\n\n");
						}
					}
					
				#	print("BT: possible archive????" . $domain_application_location . "|\n");
					/*
					$domain_subapplications_directory = scandir($domain_application_location);
					
					$domain_subapplications = array_diff($domain_subapplications_directory, ['.', '..']);
					
					print("BT: application dir::: " . $domain_application . "\n");
					print_r($domain_application);
					print("\n\n");
					
					if($domain_application === 'archive') {
						$archive_location = $domain_application_location . 'archive/';
						
						print("FOUND!!!!" . $archive_location . "\n\n");
					}
					*/
				}
			}
			
		#	print_r($contents);
		
			$this->purge_files = $purge_files;
			$purge_files_count = count($purge_files);
			
			print("Identified Archives to Purge?: ");
			
			if($purge_files_count === 0) {
				$this->failResults();
			} else {
				#print_r($purge_files);
				
				$purge_files_displayable = [];
				
				foreach($purge_files as $purge_file) {
					$purge_files_displayable[] = [
						'purge-file'=>$purge_file,
					];
				}
				
				print(arr2textTable($purge_files_displayable));
				
				foreach($purge_files as $purge_file) {
					if(is_file($purge_file)) {
						print('Removing: ' . $purge_file . "\n");
						
						unlink($purge_file);
					}
				}
				
				$this->successResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
					// Script-Level Functions
		
		public function userConfirm() {
			return $this->basicConfirmDialogue([
				'message'=>'Database archive purging beginning.',
				'argv_index'=>'1',
			]);
		}
		
		public function bannerMessageText() {
			return 'Database Purge Utility';
		}
		
		public function confirmDomainText() {
			return 'Purging MySQL Archive For Domain: ';
		}
	}
	
?>