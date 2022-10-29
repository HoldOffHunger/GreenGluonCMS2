<?php

	depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/Base64.php');
	clireq('traits/DataArrays.php');
	clireq('traits/DBAccess.php');
	clireq('traits/DBTest.php');
	clireq('traits/DNSRecords.php');
	clireq('traits/CLIAccess.php');
	clireq('traits/GlobalsTrait.php');
	clireq('traits/SSL.php');
	clireq('traits/VersionNumber.php');
	
	class DomainChecker {
		use Base64;
		use DataArrays;
		use DBAccess;
		use DBTest;
		use DNSRecords;
		use CLIAccess;
		use GlobalsTrait;
		use SSL;
		use VersionNumber;
		
		public function checkDomain() {
			$this->setHandle();
			$this->bannerMessage();
			
			if(!$this->setDomain()) {
				return $this->cancelAction(['message'=>'Invalid domain.  Please submit a FQDN in the form of `example.com`.']);
			}
			
			if($this->userConfirm()) {
				$this->setGlobals();
				$this->setMySQLArgs();
				
				$this->runMySQLTest(['display'=>'short']);
				
				$this->buildDatabaseCheck();
				$this->tableDatabaseCheck();
				$this->fieldsDatabaseCheck();
				$this->userAdminDatabaseCheck();
				$this->primaryEntryCheck();
				$this->primaryEntryCheckPublic();
				$this->SSLCertifications();
				
				/*
				if($this->userConfirmCertBot()) {
					$this->certBot();
				}
				
				$this->recommendDNSRecords();
				*/
				return TRUE;
			}
			
			return $this->cancelAction(['message'=>'User cancelled.']);
		}
		
		public function recommendDNSRecords() {
			print("\n\n");
			
			print('Please install the following DNS records for your domain with your namehost:' . "\n\n");
			
			$standard_ttl = 3600;
			
			$name_servers = $this->globals->NameServers();
			
			$default_server = '(lamp-server)';
			
			$dns_records = $this->getDNSRecords();
			
			foreach($name_servers as $name_server) {
				$dns_records[] = [
					'Record Type'=>'NS',
					'Hostname'=>$this->domain,
					'Value'=>$name_server,
					'TTL'=>$standard_ttl,
				];
			}
			
			print(arr2textTable($dns_records));
			
			return TRUE;
		}
		
		public function userInputType() {
			print("\n\n");
			print('Answer type?  (y)es to all, (o)ne at a time?');
			
			$this->answer_type = strtolower(trim(fgets($this->handle)));
			
			return true;
		}
		
		public function reloadApache() {
			print("Reloading apache...\n\n");
			
			$reload_apache_line = 'systemctl reload apache2';
			
			shell_exec($reload_apache_line);
			
			print("Successfully reloaded apache.\n\n");
			
			return TRUE;
		}
		
		public function userConfirmApacheReload() {
			return $this->basicConfirmDialogue([
				'message'=>'Restart apache?',
			]);
		}
		
		public function userConfirmBuildCheck() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to check database named `' . $this->host . '`?',
			]);
		}
		
		public function domainDirectories() {
			$domain = $this->domain;
			
			return array_merge(
				$this->webServingDirectories(),
				$this->statDirectories()
			);
		}
		
		public function webServingDirectories() {
			$domain = $this->domain;
			
			return [
				'/srv/ggcms/' . $domain . '/',
				'/srv/ggcms/' . $domain . '/www/',
				'/srv/ggcms/' . $domain . '/www/image/',
			];
		}
		
		public function statDirectories() {
			$domain = $this->domain;
			
			return [
				'/var/log/ggcms/' . $domain . '/',
				'/var/log/ggcms/' . $domain . '/stats/',
			];
		}
		
		public function buildDatabaseCheck() {
			print("Check Database " . $this->host . " : ");
			
			$this->db_link->select_db($this->host);
			
			$full_mysql = 'SELECT count(SCHEMA_NAME) AS db_count FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = \'' . $this->host . '\';';
			
			$host_count_records = $this->runQuery([
				'query'=>$full_mysql,
			]);
			
			$count = $host_count_records[0]['db_count'];
			
			if($count === 1) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function fieldsDatabaseCheck() {
			print("Check Database Fields " . $this->host . " : ");
			
			$clonefrom_check_sql = "use clonefrom;";
			$host_check_sql = "use " . $this->host . ";";
			
			$host_tables = $this->host_tables;
			
			$bad_tables = [];
			
			foreach($host_tables as $host_table) {
				$full_mysql = 'DESC ' . $host_table . ';';
				
				$this->db_link->select_db('clonefrom');
				
				$clonefrom_field_description = $this->runQuery([
					'query'=>$full_mysql,
				]);
				
				$this->db_link->select_db($this->host);
				
				$host_field_description = $this->runQuery([
					'query'=>$full_mysql,
				]);
				
				if($clonefrom_field_description !== $host_field_description) {
					$bad_tables[] = $host_table;
				}
			}
			
			$bad_tables_count = count($bad_tables);
			
			if($bad_tables_count === 0) {
				$this->successResults();
			} else {
				$this->failResults();
				print(' (Failed Table Checks: ' . implode(', ', $bad_tables) . '.)');
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function tableDatabaseCheck() {
			print("Check Database Tables " . $this->host . ": ");
			
			$full_mysql = 'show tables;';
			
			$this->db_link->select_db('clonefrom');
			
			$clonefrom_table_records = $this->runQuery([
				'query'=>$full_mysql,
			]);
			
			$this->db_link->select_db($this->host);
			
			$host_table_records = $this->runQuery([
				'query'=>$full_mysql,
			]);
			
			$clonefrom_table_records_count = count($clonefrom_table_records);
			$host_table_records_count = count($host_table_records);
			
			if($clonefrom_table_records_count !== $host_table_records_count) {
				$this->failResults();
				print(' (mismatched tables count -- ');
				print('Clonefrom Tables: ' . $clonefrom_table_records_count . '; ');
				print('Host Tables: ' . $host_table_records_count . '.');
				print(')');
				return FALSE;
			}
			
			# make hashes!!!!!!!!!!!
			
			$clonefrom_table_hash = [];
			$host_table_hash = [];
			
			#print_r($clonefrom_table_records);
			
			for($i = 0; $i < $clonefrom_table_records_count; $i++) {
				$clonefrom_table_record = $clonefrom_table_records[$i];
				$clonefrom_table = array_values($clonefrom_table_record)[0];
				
				$clonefrom_table_hash[$clonefrom_table] = TRUE;
			}
			
		#	print_r($clonefrom_table_hash);
			
			for($i = 0; $i < $host_table_records_count; $i++) {
				$host_table_record = $host_table_records[$i];
				$host_table = array_values($host_table_record)[0];
				
				$host_table_hash[$host_table] = TRUE;
			}
			
			$this->host_tables = array_keys($host_table_hash);
			
		#	print_r($host_table_hash);
			
			$bad_clonefrom_tables = [];
			$bad_host_tables = [];
			
			foreach($clonefrom_table_hash as $clonefrom_table_record => $truth) {
				if(!$host_table_hash[$clonefrom_table_record]) {
					$bad_clonefrom_tables[] = $clonefrom_table_record;
				}
			}
			
			foreach($host_table_hash as $host_table_record => $truth) {
				if(!$clonefrom_table_hash[$host_table_record]) {
					$bad_host_tables[] = $host_table_record;
				}
			}
			
			$bad_clonefrom_tables_count = count($bad_clonefrom_tables);
			$bad_host_tables_count = count($bad_host_tables);
			
			if($bad_clonefrom_tables_count !== 0 && $bad_host_tables_count !== 0) {
				$this->failResults();
				print(' (failed counts; clonefrom: ' . $bad_clonefrom_tables_count . '/ host: ' . $bad_host_tables_count . ')');
			} else {
				$this->successResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function userAdminDatabaseCheck() {
			print("Check Admin Records on: ");
			
			$user_table = 
				[
					'id',
					'Username',
					'Password',
					'EmailAddress',
				]
			;
			
			$full_mysql = 'SELECT ' . implode(',', $user_table) . ' FROM User ORDER BY id ASC LIMIT 1;';
			
			$this->db_link->select_db('clonefrom');
			
			$clonefrom_users = $this->runQuery([
				'query'=>$full_mysql,
			]);
			
			$this->db_link->select_db($this->host);
			
			$host_users = $this->runQuery([
				'query'=>$full_mysql,
			]);
			
			$user_diff = array_diff($clonefrom_users[0], $host_users[0]);
			
			$user_diff_count = count($user_diff);
			
			if($user_diff_count === 0) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function primaryEntryCheck() {
			print("Check Primary Entry Records Count: ");
			
			$full_mysql = 'SELECT Entry.* FROM Entry JOIN Assignment ON Assignment.Parentid = Entry.id AND Assignment.Childid = 0;';
			
			$this->db_link->select_db($this->host);
			
			$host_primary_entries = $this->runQuery([
				'query'=>$full_mysql,
			]);
			
		#	print("\n\nBT: primary entries???\n\n");
			
		#	print_r($host_primary_entries);
		
			$host_primary_entries_count = count($host_primary_entries);
			
			$this->primary_entries = $host_primary_entries;
			$this->primary_entries_count = $host_primary_entries_count;
			
			if($host_primary_entries_count === 0) {
				$this->failResults();
				print(' (unable to locate primary host record for ' . $this->host . ')');
			} elseif($host_primary_entries_count === 1) {
				$this->successResults();
			} else {
				$this->failResults();
				print(' (located multiple primary host records [count: ' . $host_primary_entries_count . ']' . ' for ' . $this->host . ')');
			}
			
			/*
			
				SELECT Entry.* FROM Entry JOIN Assignment ON Assignment.Parentid = Entry.id AND Assignment.Childid = 0;
			
			*/
			
		#	$clonefrom_get_user_sql = 'use clonefrom;' . $base_sql . ';';
		#	$host_get_user_sql = 'use ' . $this->host . ';' . $base_sql . ';';
			
		#	$clonefrom_database_command = 'mysql ' . $this->base_sql_args . '-e "' . $clonefrom_get_user_sql . ';"';
		#	$host_database_command = 'mysql ' . $this->base_sql_args . '-e "' . $host_get_user_sql . ';"';
			
		#	$host_primary_record_results = trim(shell_exec($host_database_command));
		#	$host_primary_records = explode("\n", $host_primary_record_results);
			
		#	print("\n\n");
		#	print("BT: HOST DATABASE!");
		#	print_r($host_primary_records);
			
			print("\n");
			
			return TRUE;
		}
		
		public function primaryEntryCheckPublic() {
			print("Check Primary Entry Records Public: ");
			
			if($this->primary_entries_count === 1) {
				$primary_entry = $this->primary_entries[0];
				
				//print_r($primary_entry);
				
				if($primary_entry['Publish']) {
					$this->successResults();
				} else {
					$this->failResults();
					print(' (Primary Host has Publish = 0, should be = 1)');
				}
			} else {
				$this->failResults();
				print(' (could not perform if primary entries count is not 1)');
			}
			
			print("\n");
			
			return TRUE;
		}
		
			/*
			
					./etc/letsencrypt/live/holdoffhunger.com
					./etc/letsencrypt/renewal/holdoffhunger.com.conf
					
					./etc/apache2/sites-enabled/holdoffhunger.com-le-ssl.conf
					./etc/apache2/sites-available/holdoffhunger.com-le-ssl.conf
			
			*/
			
			// doctl compute domain records create --help
			
			/*
					
						//-------- File List

				cert1.pem
				chain1.pem
				fullchain1.pem
				privkey1.pem
				
						//-------- File List Meaning
			
				Private Key --------> privkey.pem
					private key
				Public Key ---------> cert.pem
					public key
					
				Fullchain --------> fullchain.pem
					a concatenation of cert.pem and chain.pem in one file
				Certificate Chain --> chain.pem
					chain.pem is the rest of the chain; in this case, it�s only LetsEncrypt�s root certificate; contains your intermediate certificates
			
			*/
		
		public function SSLCertifications() {
			$this->SSLCertifications_letsEncrypt_folderChecks();
			$this->SSLCertifications_letsEncrypt_fileChecks();
			
			$this->SSLCertifications_apache_fileChecks();
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_folderChecks() {
			$this->SSLCertifications_letsEncrypt_folderChecks_liveFolderCheck();
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_folderChecks_liveFolderCheck() {
			print("SSL Cert Checks, LetsEncrypt Live Directory: ");
			
			$live_dir_location = '/etc/letsencrypt/live/' . $this->host . '.com/';
			
			$this->live_dir_location = $live_dir_location;
			
			if(is_dir($live_dir_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks() {
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_certPem();
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_chainPem();
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_fullchainPem();
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_privkeyPem();
			
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_certPem_formatCheck();
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_chainPem_formatCheck();
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_fullchainPem_formatCheck();
			$this->SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_privkeyPem_formatCheck();
			
			$this->SSLCertifications_letsEncrypt_fileChecks_renewalFileCheck();
			
			$this->SSLCertifications_letsEncrypt_fileChecks_renewalFileCheck_formatCheck();
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_certPem_formatCheck() {
			print("SSL Cert Checks, LetsEncrypt Live File, Format Check, cert.pem: ");
			
			$local_file_location = $this->live_dir_location . 'cert.pem';
			
			if(is_file($local_file_location)) {
				$errors = $this->validateSSLCertPem([
					'file_location'=>$local_file_location,
				]);
				
				$error_count = count($errors);
				
				if($error_count === 0) {
					$this->successResults();
				} else {
					$this->failResults();
					print(' (errors: ' . implode(', ', $errors) . ')');
				}
			} else {
				$this->failResults();
				print(' (file does not exist: ' . $local_file_location . ')');
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_chainPem_formatCheck() {
			print("SSL Cert Checks, LetsEncrypt Live File, Format Check, chain.pem: ");
			
			$local_file_location = $this->live_dir_location . 'chain.pem';
			
			if(is_file($local_file_location)) {
				$errors = $this->validateSSLChainPem([
					'file_location'=>$local_file_location,
				]);
				
				$error_count = count($errors);
				
				if($error_count === 0) {
					$this->successResults();
				} else {
					$this->failResults();
					print(' (errors: ' . implode(', ', $errors) . ')');
				}
			} else {
				$this->failResults();
				print(' (file does not exist: ' . $local_file_location . ')');
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_fullchainPem_formatCheck() {
			print("SSL Cert Checks, LetsEncrypt Live File, Format Check, fullchain.pem: ");
			
			$local_file_location = $this->live_dir_location . 'fullchain.pem';
			
			if(is_file($local_file_location)) {
				$errors = $this->validateSSLFullChainPem([
					'file_location'=>$local_file_location,
				]);
				
				$error_count = count($errors);
				
				if($error_count === 0) {
					$this->successResults();
				} else {
					$this->failResults();
					print(' (errors: ' . implode(', ', $errors) . ')');
				}
			} else {
				$this->failResults();
				print(' (file does not exist: ' . $local_file_location . ')');
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_privkeyPem_formatCheck() {
			print("SSL Cert Checks, LetsEncrypt Live File, Format Check, privkey.pem: ");
			
			$local_file_location = $this->live_dir_location . 'privkey.pem';
			
			if(is_file($local_file_location)) {
				$errors = $this->validateSSLPrivKeyPem([
					'file_location'=>$local_file_location,
				]);
				
				$error_count = count($errors);
				
				if($error_count === 0) {
					$this->successResults();
				} else {
					$this->failResults();
					print(' (errors: ' . implode(', ', $errors) . ')');
				}
			} else {
				$this->failResults();
				print(' (file does not exist: ' . $local_file_location . ')');
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_certPem() {
			print("SSL Cert Checks, LetsEncrypt Live File, cert.pem: ");
			
			$local_file_location = $this->live_dir_location . 'cert.pem';
			
			if(is_file($local_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_chainPem() {
			print("SSL Cert Checks, LetsEncrypt Live File, chain.pem: ");
			
			$local_file_location = $this->live_dir_location . 'chain.pem';
			
			if(is_file($local_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_fullchainPem() {
			print("SSL Cert Checks, LetsEncrypt Live File, fullchain.pem: ");
			
			$local_file_location = $this->live_dir_location . 'fullchain.pem';
			
			if(is_file($local_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_mainFileCheck_privkeyPem() {
			print("SSL Cert Checks, LetsEncrypt Live File, privkey.pem: ");
			
			$local_file_location = $this->live_dir_location . 'privkey.pem';
			
			if(is_file($local_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_renewalFileCheck() {
			print("SSL Cert Checks, LetsEncrypt Renewal File: ");
			
			$live_file_location = '/etc/letsencrypt/renewal/' . $this->host . '.com.conf';
			
			if(is_file($live_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_letsEncrypt_fileChecks_renewalFileCheck_formatCheck() {
			print("SSL Cert Checks, LetsEncrypt Renewal File, Format Check: ");
			
			$live_file_location = '/etc/letsencrypt/renewal/' . $this->host . '.com.conf';
			
			if(is_file($live_file_location)) {
				$errors = $this->validateLetsEncryptRenewal([
					'file_location'=>$live_file_location,
				]);
				
				$error_count = count($errors);
				
				if($error_count === 0) {
					$this->successResults();
				} else {
					$this->failResults();
					print(' (errors: ' . implode(', ', $errors) . ')');
				}
			} else {
				$this->failResults();
				print(' (file does not exist: ' . $live_file_location . ')');
			}
			
			print("\n");
			
			return TRUE;
		}
		
		/*
		
					./etc/apache2/sites-enabled/holdoffhunger.com-le-ssl.conf
					./etc/apache2/sites-available/holdoffhunger.com-le-ssl.conf
		
		*/
		
		
		public function SSLCertifications_apache_fileChecks() {
			$this->SSLCertifications_apache_fileChecks_enabledFileCheck();
			$this->SSLCertifications_apache_fileChecks_availableFileChecks();
			
			return TRUE;
		}
		
		public function SSLCertifications_apache_fileChecks_enabledFileCheck() {
			print("SSL Cert Checks, Apache Enabled Folder: ");
			
			$enabled_file_location = '/etc/apache2/sites-enabled/' . $this->host . '.com-le-ssl.conf';
			
			if(is_file($enabled_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_apache_fileChecks_availableFileChecks() {
			$this->SSLCertifications_apache_fileChecks_availableFileChecks_80();
			$this->SSLCertifications_apache_fileChecks_availableFileChecks_443();
			
			$this->SSLCertifications_apache_fileChecks_availableFileChecks_80_format();
		#	$this->SSLCertifications_apache_fileChecks_availableFileChecks_443_format();
			
			return TRUE;
		}
		
		public function SSLCertifications_apache_fileChecks_availableFileChecks_80() {
			print("SSL Cert Checks, Apache Available File, 80: ");
			
			$enabled_file_location = '/etc/apache2/sites-available/' . $this->domain . '.conf';
			
			if(is_file($enabled_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_apache_fileChecks_availableFileChecks_443() {
			print("SSL Cert Checks, Apache Available File, 443: ");
			
			$enabled_file_location = '/etc/apache2/sites-available/' . $this->domain . '-le-ssl.conf';
			
			if(is_file($enabled_file_location)) {
				$this->successResults();
			} else {
				$this->failResults();
			}
			
			print("\n");
			
			return TRUE;
		}
		
		public function SSLCertifications_apache_fileChecks_availableFileChecks_80_format() {
			print("SSL Cert Checks, Apache Available File, 80, Format Check: ");
			
			$available_file_location = '/etc/apache2/sites-available/' . $this->domain . '.conf';
			
			if(is_file($available_file_location)) {
				$errors = $this->validateLetsEncryptRenewal80_formatCheck([
					'file_location'=>$available_file_location,
				]);
				
				$error_count = count($errors);
				
				if($error_count === 0) {
					$this->successResults();
				} else {
					$this->failResults();
					print(' (errors: ' . implode(', ', $errors) . ')');
				}
			} else {
				$this->failResults();
				print(' (file does not exist: ' . $live_file_location . ')');
			}
			
			print("\n");
			
			return TRUE;
		}
		
					// Script-Level Functions
		
		public function userConfirm() {
			return $this->basicConfirmDialogue([
				'message'=>'Overall checking beginning.',
			]);
		}
		
		public function bannerMessageText() {
			return 'Installed Domain Checker';
		}
		
		public function confirmDomainText() {
			return 'Checking Domain: ';
		}
	}
	
?>