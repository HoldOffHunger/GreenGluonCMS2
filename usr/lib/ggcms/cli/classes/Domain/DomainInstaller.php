<?php

	depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/Apache.php');
	clireq('traits/DBAccess.php');
	clireq('traits/DBTest.php');
	clireq('traits/Directories.php');
	clireq('traits/DNSRecords.php');
	clireq('traits/CLIAccess.php');
	clireq('traits/GlobalsTrait.php');
	ggreq('traits/ReverseDNSNotation.php');
	clireq('traits/DigitalOceanDNSRecords.php');
	clireq('traits/DomainValidation.php');
	
	class DomainInstaller {
		use Apache;
		use DBAccess;
		use DBTest;
		use Directories;
		use DNSRecords;
		use CLIAccess;
		use GlobalsTrait;
		use ReverseDNSNotation;
		use DigitalOceanDNSRecords;
		use DomainValidation;
		
		public function installDomain() {
			$this->setHandle();
			$this->bannerMessage();
			$this->setSourceFileName();
			
			if(!$this->setDomain()) {
				return $this->cancelAction(['message'=>'Invalid domain.  Please submit a FQDN in the form of `example.com`.']);
			}
			
			$this->ReverseThisDomainName();
			
			if($this->userConfirm()) {
				$this->userInputType();
				$this->setGlobals();
				$this->setMySQLArgs();
				
				$this->runMySQLTest([]);
				
				if($this->userConfirmBuildDatabase()) {
					$this->buildDatabase();
				}
				
				if($this->userConfirmSource()) {
					$this->rebuildCloneFromSource();
				}
				
				if($this->userConfirmImport()) {
					$this->importCloneFromSource();
				}
				
				if($this->userConfirmDirectories()) {
					$this->buildDirectories();
				}
				
				if($this->userConfirmApacheConf()) {
					$this->buildApacheConf();
				}
				
				if($this->userConfirmGGCMSConfig()) {
					$this->buildGGCMSConfig();
				}
				
				if($this->userConfirmDNSRecords()) {
					$this->buildDNSRecords();
				}
				
				if($this->userConfirmApacheEnable()) {
					$this->enableApacheConf();
				}
				
				if($this->userConfirmApacheReload()) {
					$this->reloadApache();
				}
				
				if($this->userConfirmCertBot()) {
					$this->certBot();
				}
				
				if($this->userConfirmApacheReload()) {
					$this->reloadApache();
				}
				
				$this->recommendDNSRecords();
				
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
					'Type'=>'~',
				];
			}
			
			print(arr2textTable($dns_records));
			
			return TRUE;
		}
		
		// IPv4Addresses()
		// IPv6Addresses()
		
		public function userInputType() {
			print("\n\n");
			print('Answer type?  (y)es to all, (o)ne at a time?');
			
			if(array_key_exists(3, $this->argv) && $this->argv[3]) {
				$this->answer_type = $this->argv[3];
				print($this->answer_type);
				print(PHP_EOL);
			} else {
				$this->answer_type = strtolower(trim(fgets($this->handle)));
			}
			
			return true;
		}
		
		public function certBot() {
			print("CertBot SSL installing..." . PHP_EOL . PHP_EOL);
			
			$stop_line = '/etc/init.d/apache2 stop';
			shell_exec($stop_line);
			
			$cert_line = 'sudo certbot --apache -d ' . $this->domain;
			shell_exec($cert_line);
			
			$start_line = '/etc/init.d/apache2 start';
			shell_exec($start_line);	// double start needed?
			shell_exec($start_line);
			
			print("Successfully installed SSL Cert.\n\n");
			
			return TRUE;
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
				'argv_index'=>3,
			]);
		}
		
		public function userConfirmCertBot() {
			return $this->basicConfirmDialogue([
				'message'=>'Run CertBot? (Note: This will bring down the server.)',
				'argv_index'=>3,
			]);
		}
		
		public function enableApacheConf() {
			$apache_conf_location = '/etc/apache2/sites-available/' . $this->domain . '.conf';
			
			print("Enabling Apache Config file `" . $file_location . "`.\n\n");
			
			$enable_apache_line = 'a2ensite ' . $apache_conf_location;
			
			shell_exec($enable_apache_line);
			
			$change_ownership_line = 'chown -R ' . $this->defaultWebServerUser() . ' ' . $apache_conf_location;
			
			shell_exec($change_ownership_line);
			
			print("Successfully enabled Apache Config file `" . $file_location . "`.\n\n");
			
			return TRUE;
		}
		
		public function userConfirmApacheEnable() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to enable apache config file `' . $this->domain . '.conf`?',
				'argv_index'=>3,
			]);
		}
		
		public function buildApacheConf() {
			$file_location = '/etc/apache2/sites-available/' . $this->domain . '.conf';
			
			print("Building Apache Config file `" . $file_location . "`.\n\n");
			
			$apache_conf =
				'<VirtualHost *:80>' . "\n" .
				"\t" . 'ServerAdmin holdoffhunger@gmail.com' . "\n" .
				"\t" . 'ServerName ' . $this->domain . "\n" .
				"\t" . 'ServerAlias www.' . $this->domain . "\n" .
				"\t" . 'DocumentRoot /var/www/html' . "\n" .
				"\t" . 'ErrorLog ${APACHE_LOG_DIR}/error.log' . "\n" .
				"\t" . 'CustomLog ${APACHE_LOG_DIR}/access.log combined' . "\n" .
				'</VirtualHost>';
			
			file_put_contents($file_location, $apache_conf, LOCK_EX);
			
			print("Successfully built Apache Config file `" . $file_location . "`." . PHP_EOL . PHP_EOL);
			
			return TRUE;
		}
		
		public function buildGGCMSConfig() {
			$this->buildGGCMSConfigFile();
			$this->buildGGCMSConfigFolder();
			
			return TRUE;
		}
		
		public function buildGGCMSConfigFile() {
			$file_location = GGCMS_CONFIG_DIR . $this->reversed_domain . '.php';
			
			print("Building GGCMS Config file `" . $file_location . "`.\n\n");
			
			$ggcms_conf =
				'<?php' . PHP_EOL . PHP_EOL .
				'	class globals extends defaultglobals {' . PHP_EOL .
				'	}' . PHP_EOL . PHP_EOL .	
				'?>';
			
			file_put_contents($file_location, $ggcms_conf, LOCK_EX);
			
			print("Successfully built GGCMS Config file `" . $file_location . "`." . PHP_EOL . PHP_EOL);
			
			return TRUE;
		}
		
		public function buildGGCMSConfigFolder() {
			$folder_location = GGCMS_CONFIG_DIR . $this->reversed_domain . '/';
			
			$mkdir_command_base = 'mkdir --mode=744 ' . $folder_location;
			$chown_command_base = 'chown -R ' . $this->defaultWebServerUser() . ' ' . $folder_location;
			
			shell_exec($mkdir_command_base);
			shell_exec($chown_command_base);
			
			print("Successfully built GGCMS Config folder `" . $folder_location . "`." . PHP_EOL . PHP_EOL);
			
			return TRUE;
		}
		
		/*

			doctl compute domain records create pronouncethat.com --record-type "NS" --record-name "pronouncethat.com" --record-ttl 1800 --record-data "ns1.digitalocean.com"
			doctl compute domain records create pronouncethat.com --record-type "NS" --record-name "pronouncethat.com" --record-ttl 1800 --record-data "ns2.digitalocean.com"
			doctl compute domain records create pronouncethat.com --record-type "NS" --record-name "pronouncethat.com" --record-ttl 1800 --record-data "ns3.digitalocean.com"
			
			doctl compute domain records create pronouncethat.com --record-type "AAAA" --record-name "pronouncethat.com" --record-ttl 3600 --record-data "2604:a880:400:d0::188f:3001"
			doctl compute domain records create pronouncethat.com --record-type "AAAA" --record-name "*.pronouncethat.com" --record-ttl 3600 --record-data "2604:a880:400:d0::188f:3001"
			
			doctl compute domain records create pronouncethat.com --record-type "A" --record-name "pronouncethat.com" --record-ttl 3600 --record-data "198.199.120.211"
			doctl compute domain records create pronouncethat.com --record-type "A" --record-name "*.pronouncethat.com" --record-ttl 3600 --record-data "198.199.120.211"
			
					DIZ IZ BROKEN!!!!
			
			doctl compute domain records create pronouncethat.com --record-type "CAA" --record-name "pronouncethat.com" --record-ttl 3600 --record-tag "iodef" --record-data "mailto:holdoffhunger@gmail.com"
			doctl compute domain records create pronouncethat.com --record-type "CAA" --record-name "pronouncethat.com" --record-ttl 3600 --record-tag "issue" --record-data "letsencrypt.org"
			doctl compute domain records create pronouncethat.com --record-type "CAA" --record-name "pronouncethat.com" --record-ttl 3600 --record-tag "issuewild" --record-data "letsencrypt.org"
		
		*/
		
		public function buildDNSRecords() {
			$this->formatted_records = $this->getFormattedDomainRecords();
			
			$this->buildDNSRecords_NameServers();
			$this->buildDNSRecords_IPv4Addresses();
			$this->buildDNSRecords_IPv6Addresses();
			$this->buildDNSRecords_CertificateAuthorities();
			
			return TRUE;
		}
		
		public function buildDNSRecords_NameServers() {
			// doctl compute domain records create pronouncethat.com --record-type "NS" --record-name "pronouncethat.com" --record-ttl 1800 --record-data "ns1.digitalocean.com"
			$name_servers = $this->globals->NameServers();
			$name_server_lookup = [];
			
			foreach($name_servers as $name_server) {
				$name_server_lookup[$name_server] = $name_server;
			}
			
			foreach($this->formatted_records['NS'] as $ns_server) {
				if(array_key_exists($ns_server['Data'], $name_server_lookup)) {
					unset($name_server_lookup[$ns_server['Data']]);
				}
			}
			
			return TRUE;
		}
		
		public function buildDNSRecords_IPv4Addresses() {
			$name_server_missing = ['*'=>TRUE, '@'=>TRUE,];
			
			foreach($this->formatted_records['A'] as $ns_server) {
				if(array_key_exists($ns_server['Name'], $name_server_missing)) {
					unset($name_server_missing[$ns_server['Name']]);
				}
			}
			
			$ip_addresses = $this->globals->IPv4Addresses();
			
			foreach($name_server_missing as $name_server_missing_id => $truth) {
				$command = 'doctl compute domain records create ' . $this->domain . ' --record-type "A" --record-name "' . $name_server_missing_id . '" --record-ttl 3600 --record-data "' . $ip_addresses[0] . '"';
				
				shell_exec($command);
			}
			
			return TRUE;
		}
		
		public function buildDNSRecords_IPv6Addresses() {
			$name_server_missing = ['*'=>TRUE, '@'=>TRUE,];
			
			foreach($this->formatted_records['AAAA'] as $ns_server) {
				if(array_key_exists($ns_server['Name'], $name_server_missing)) {
					unset($name_server_missing[$ns_server['Name']]);
				}
			}
			
			$ip_addresses = $this->globals->IPv6Addresses();
			
			foreach($name_server_missing as $name_server_missing_id => $truth) {
				$command = 'doctl compute domain records create ' . $this->domain . ' --record-type "AAAA" --record-name "' . $name_server_missing_id . '" --record-ttl 3600 --record-data "' . $ip_addresses[0] . '"';
				
				shell_exec($command);
			}
			
			return TRUE;
		}
		
		public function buildDNSRecords_CertificateAuthorities() {
				// do this by hand, sigh =\
			
			return TRUE;
		}
		
		public function userConfirmApacheConf() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to build apache config file `' . $this->domain . '.conf`?',
				'argv_index'=>3,
			]);
		}
		
		public function userConfirmGGCMSConfig() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to build GGCMS config file `' . GGCMS_CONFIG_DIR . $this->domain . '.php`?',
				'argv_index'=>3,
			]);
		}
		
		public function userConfirmDNSRecords() {
			$web_host_services = implode(', ', $this->globals->WebHostServices());
			
			return $this->basicConfirmDialogue([
				'message'=>'Ready to commit DNS records to ' . $web_host_services . '?',
				'argv_index'=>3,
			]);
		}
		
		public function userConfirmDirectories() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to create directories for `' . $this->host . '`?',
				'argv_index'=>3,
			]);
		}
		
		public function userConfirmBuildDatabase() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to create a new database named `' . $this->host . '`.',
				'argv_index'=>3,
			]);
		}
		
		public function buildDirectories() {
			print("Building " . $this->host . " directories.\n\n");
			$mkdir_command_base = 'mkdir --mode=744 ';
			$chown_command_base = 'chown -R ' . $this->defaultWebServerUser() . ' ';
			
			$directories = $this->domainDirectories();
			
			foreach($directories as $directory) {
				$directory_make_command = $mkdir_command_base . $directory;
				$directory_chown_command = $chown_command_base . $directory;
				
				shell_exec($directory_make_command);
				shell_exec($directory_chown_command);
				
				print('Created directory owned by ' . $this->defaultWebServerUser() . ': ' . $directory . "\n");
			}
			
			print("Successfully built " . $this->host . " directories.\n\n");
			
			return TRUE;
		}
		
		public function buildDatabase() {
			print("Building " . $this->host . " database.\n\n");
			
			$create_database_command = 'mysql -e "CREATE DATABASE ' . $this->host . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci"';
				
			shell_exec($create_database_command);
			
			print("Successfully built " . $this->host . " database.\n\n");
			
			return TRUE;
		}
		
		public function importCloneFromSource() {
			print("Importing clonefrom.sql template database.\n\n");
			
			$import_sql_command = 'mysql ' . $this->host . ' < ' . GGCMS_CLI_DIR . $this->source_filename;
			
			$output = shell_exec($import_sql_command);
			
			print("\n\n");
			
			print("Successfully imported clonefrom.sql template database into " . $this->host . ".\n\n");
			
			return TRUE;
		}
		
		public function userConfirmImport() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to import clonefrom.sql database.',
				'argv_index'=>3,
			]);
		}
		
		public function setSourceFileName() {
			return $this->source_filename = 'sql/clonefrom.sql';
		}
		
		public function rebuildCloneFromSource() {
			print($this->source_filename . ' regeneration started.');
			
			if(cli_isfile($this->source_filename)) {
				unlink(GGCMS_CLI_DIR . $this->source_filename);
			}
			
			$mysql_dump_args = '--default-character-set=latin1 --skip-set-charset --no-tablespaces -N --routines --skip-triggers --set-gtid-purged=OFF';
			$mysql_dump_command = 'mysqldump ' . $mysql_dump_args . ' clonefrom > ' . GGCMS_CLI_DIR . $this->source_filename;
			
			$output = shell_exec($mysql_dump_command);
			
			print("\n\n");
			print($this->source_filename . ' regenerated successfully.' . "\n\n");
			
			return TRUE;
		#	print("BT: DUMP!|" . $mysql_dump_command . "|");
		}
		
		public function userConfirm() {
			return $this->basicConfirmDialogue([
				'message'=>'Overall installation beginning.',
				'argv_index'=>2,
			]);
		}
		
		public function userConfirmSource() {
			$source_filename = $this->source_filename;
			
			print('Rebuild template database (`' . $source_filename . '`)?' . "\n");
			
			if(!cli_isfile($source_filename)) {
				print("\n" . 'Source file does not exist!  Install will crash if you attempt to proceed with "n"!' . "\n");
			}
			
			$creation_time = filectime(GGCMS_CLI_DIR . $source_filename);
			$modification_time = filemtime(GGCMS_CLI_DIR . $source_filename);
			$access_time = fileatime(GGCMS_CLI_DIR . $source_filename);
			
			print($source_filename .
				' - Created: ' .
				date('Y-m-d H:i:s', $creation_time) .
				'; Modified: ' .
				date('Y-m-d H:i:s', $modification_time) .
				'; Last Access: ' .
				date('Y-m-d H:i:s', $access_time)
			. "\n");
			
			return $this->basicConfirmDialogue([
				'message'=>'Rebuild?',
				'argv_index'=>3,
			]);
		}
		
		public function bannerMessageText() {
			return 'New Domain Installation';
		}
		
		public function confirmDomainText() {
			return 'Installing Domain: ';
		}
	}
	
?>