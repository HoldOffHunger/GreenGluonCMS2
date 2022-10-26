<?php

	depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/DBAccess.php');
	clireq('traits/DBTest.php');
	clireq('traits/DNSRecords.php');
	clireq('traits/CLIAccess.php');
	clireq('traits/GlobalsTrait.php');
	
	class DomainInstaller {
		use DBAccess;
		use DBTest;
		use DNSRecords;
		use CLIAccess;
		use GlobalsTrait;
		
		public function installDomain() {
			$this->setHandle();
			$this->bannerMessage();
			$this->setSourceFileName();
			
			if(!$this->setDomain()) {
				return $this->cancelAction(['message'=>'Invalid domain.  Please submit a FQDN in the form of `example.com`.']);
			}
			
			if($this->userConfirm()) {
				$this->userInputType();
				$this->setGlobals();
				$this->setMySQLArgs();
				
				$this->runMySQLTest();
				
				if($this->userConfirmBuild()) {
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
		
		public function certBot() {
			print("CertBot SSL installing...\n\n");
			
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
			]);
		}
		
		public function userConfirmCertBot() {
			return $this->basicConfirmDialogue([
				'message'=>'Run CertBot? (Note: This will bring down the server.)',
			]);
		}
		
		public function enableApacheConf() {
			$apache_conf_location = '/etc/apache2/sites-available/' . $this->domain . '.conf';
			
			print("Enabling Apache Config file `" . $file_location . "`.\n\n");
			
			$enable_apache_line = 'a2ensite ' . $apache_conf_location;
			
			shell_exec($enable_apache_line);
			
			$change_ownership_line = 'chown -R www-data ' . $apache_conf_location;
			
			shell_exec($change_ownership_line);
			
			print("Successfully enabled Apache Config file `" . $file_location . "`.\n\n");
			
			return TRUE;
		}
		
		public function userConfirmApacheEnable() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to enable apache config file `' . $this->domain . '.conf`?',
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
			
			print("Successfully built Apache Config file `" . $file_location . "`.\n\n");
			
			return TRUE;
		}
		
		public function userConfirmApacheConf() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to build apache config file `' . $this->domain . '.conf`?',
			]);
		}
		
		public function userConfirmDirectories() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to create directories for `' . $this->host . '`?',
			]);
		}
		
		public function userConfirmBuild() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to create a new database named `' . $this->host . '`.',
			]);
		}
		
		public function buildDirectories() {
			print("Building " . $this->host . " directories.\n\n");
			$mkdir_command_base = 'mkdir --mode=744 ';
			$chown_command_base = 'chown -R www-data ';
			
			$directories = $this->domainDirectories();
			
			foreach($directories as $directory) {
				$directory_make_command = $mkdir_command_base . $directory;
				$directory_chown_command = $chown_command_base . $directory;
				
				shell_exec($directory_make_command);
				shell_exec($directory_chown_command);
				
				print('Created directory owned by www-data: ' . $directory . "\n");
			}
			
			print("Successfully built " . $this->host . " directories.\n\n");
			
			return TRUE;
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
		
		public function buildDatabase() {
			print("Building " . $this->host . " database.\n\n");
			
			$create_database_command = 'mysql ' . $this->base_sql_args . '-e "CREATE DATABASE ' . $this->host . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci"';
				
			shell_exec($create_database_command);
			
			print("Successfully built " . $this->host . " database.\n\n");
			
			return TRUE;
		}
		
		public function importCloneFromSource() {
			print("Importing clonefrom.sql template database.\n\n");
			
			$import_sql_command = 'mysql ' . $this->base_sql_args . ' ' . $this->host . ' < ' . GGCMS_CLI_DIR . $this->source_filename;
			
			$output = shell_exec($import_sql_command);
			
			print("\n\n");
			
			print("Successfully imported clonefrom.sql template database into " . $this->host . ".\n\n");
			
			return TRUE;
		}
		
		public function userConfirmImport() {
			return $this->basicConfirmDialogue([
				'message'=>'Ready to import clonefrom.sql database.',
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
			$mysql_dump_command = 'mysqldump ' . $this->base_sql_args . ' ' . $mysql_dump_args . ' clonefrom > ' . GGCMS_CLI_DIR . $this->source_filename;
			
			$output = shell_exec($mysql_dump_command);
			
			print("\n\n");
			print($this->source_filename . ' regenerated successfully.' . "\n\n");
			
			return TRUE;
		#	print("BT: DUMP!|" . $mysql_dump_command . "|");
		}
		
		public function userConfirm() {
			return $this->basicConfirmDialogue([
				'message'=>'Overall installation beginning.',
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