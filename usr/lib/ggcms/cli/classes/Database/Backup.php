<?php

	#depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/Apache.php');
	clireq('traits/DBAccess.php');
	clireq('traits/DBTest.php');
	clireq('traits/CLIAccess.php');
	
	class DatabaseBackup {
		use Apache;
		use DBAccess;
		use DBTest;
		use CLIAccess;
		
		public function backup() {
			$this->setHandle();
			$this->bannerMessage();
			
			if(!$this->setDomain()) {
				return $this->cancelAction(['message'=>'Invalid domain.  Please submit a FQDN in the form of `example.com`.']);
			}
			
			if($this->userConfirm()) {
				$this->setMySQLArgs();
				
				if($this->confirmArchiveOrBackup()) {
					$this->runMySQLTest(['display'=>'short']);
					$this->verifyBackupFolderLocation();
				}
			}
			
#			print("\n\nSOYBEANS!!!!");
			
			return TRUE;
		}
		
		public function confirmArchiveOrBackup() {
			return $this->abstractConfirmDialogue([
				'message'=>'Archive is for removing data.  Backup is for data you may need to immediately restore.',
				'prompt'=>'Archive or Backup?',
				'index'=>3,
			]);
		}
		
		public function abstractConfirmDialogue($args) {
			$message = $args['message'];
			$prompt = $args['prompt'];
			$index = $args['index'];
			
			print($message);
			print("\n\n");
			print($prompt);
			
			/*
			if(property_exists($this, 'answer_type')) {
				if($this->answer_type === 'y' || $this->answer_type === 'yes') {
					return TRUE;
				}
			}
			*/
			
			if(array_key_exists($index, $this->argv) && $this->argv[$index]) {
				$answer = $this->argv[$index];
				print($answer . "\n");
			} else {
				$answer = strtolower(trim(fgets($this->handle)));
			}
			
			if(array_key_exists('internal_key', $args)) {
				$argv_internal_key = $args['internal_key'];
			} else {
				$argv_internal_key = 'argv' . $index;
			}
			$this->$argv_internal_key = $answer;
			
			print("\n");
			
			$answer_length = strlen($answer);
			
			if($answer_length !== 0) {
				return TRUE;
			}
			
			return FALSE;
		}
		
		public function verifyBackupFolderLocation() {
			print("Verify Backup Location(s) -- \n\n");
			
			$log_base_dir = '/var/log/ggcms/' . $this->domain . '/';
			
			$sql_dir = $log_base_dir . 'sql/';
			
			$backup_dir = $sql_dir . 'backup/';
			$archive_dir = $sql_dir . 'archive/';
			
			$new_dirs = [
				$backup_dir,
				$archive_dir,
			];
			
			$errors = [];
			
			foreach ($new_dirs AS $new_dir) {
				if(!is_dir($new_dir)) {
					if(!mkdir(
						$new_dir,
						$this->defaultWebServerUserMode(),
						TRUE
					)) {
						$errors[] = 'could not make new dir, ' . $new_dir;
					}
					
					if(!chown($new_dir, $this->defaultWebServerUser())) {
						$errors[] = 'could not assign new dir to default web server user, ' . $new_dir;
					}
				}
			}
			
			$error_count = count($errors);
			
			if($error_count === 0) {
				print("\t" . 'Backup Directory: ' . $backup_dir);
				print("\n");
				print("\t" . 'Archive Directory: ' . $archive_dir);
				print("\n\n");
				
				print("Verify Backup Location(s): ");
				$this->successResults();
			} else {
				print("Verify Backup Location(s): ");
				$this->failResults();
				print(' (errors: ' . implode(', ', $errors) . ')');
			}
			
			print("\n");
			
		#	print("BT: BACKUP DIR!" . $backup_dir . "|\n\n");
			
		#	print("BT: ARCHIVE DIR!" . $archive_dir . "|\n\n");
			
			// /var/log/ggcms/holdoffhunger.com/stats/
			
			return TRUE;
		}
		
					// Script-Level Functions
		
		public function userConfirm() {
			return $this->basicConfirmDialogue([
				'message'=>'Database backup beginning.',
			]);
		}
		
		public function bannerMessageText() {
			return 'Database Backup Utility';
		}
		
		public function confirmDomainText() {
			return 'Backing Up MySQL For Domain: ';
		}
	}
	
?>