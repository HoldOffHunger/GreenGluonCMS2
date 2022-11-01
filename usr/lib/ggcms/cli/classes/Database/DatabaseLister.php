<?php

	depreq('arr2textTable/arr2textTable.php');
	
	clireq('traits/DBAccess.php');
	clireq('traits/DNSRecords.php');
	clireq('traits/CLIAccess.php');
	clireq('traits/GlobalsTrait.php');
	
	class DatabaseLister {
		use DBAccess;
		use DNSRecords;
		use CLIAccess;
		use GlobalsTrait;
		
		public function listDatabases() {
			$this->setGlobals();
			$this->setMySQLArgs();
			
			$create_database_command = 'mysql ' . $this->base_sql_args . '-e "SHOW DATABASES;"';
				
			$output = shell_exec($create_database_command);
			
			print($this->formatTable(['output'=>$output]));
			
			return TRUE;
		}
		
		public function skipDatabasesHash() {
			$skip_databases = $this->skipDatabases();
			
			$skip_database_hash = [];
			
			foreach($skip_databases as $skip_index => $skip_database) {
				$skip_database_hash[$skip_database] = TRUE;
			}
			
			return $skip_database_hash;
		}
		
		public function skipDatabases() {
			return [
				'Database',
			];
		}
	}
	
?>