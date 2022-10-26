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
		
		public function formatTable($args) {
			$output = $args['output'];
			
			$output_lines = explode("\n", $output);
			
			$table_input = [];
			
			$skip_databases = $this->skipDatabasesHash();
			
			foreach($output_lines as $output_line) {
				$output_line = trim($output_line);
				
				if(!array_key_exists($output_line, $skip_databases)) {
					if(strlen($output_line) !== 0) {
						$output_array = [
							'Database'=>$output_line,
						];
						
						$table_input[] = $output_array;
					}
				}
			}
			
			return arr2textTable($table_input);
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