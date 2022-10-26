<?php

	trait DBAccess {
		public function setMySQLArgs() {
			$port = $this->globals->OverrideMySQLPort();
			$db_credentials = $this->globals->DBInfo();
			
			$username = $db_credentials['username'];
			$password = $db_credentials['password'];
			$hostname = $db_credentials['hostname'];
			
			$base_sql_args = '';
			
		#	$base_sql_args .= '--login-path=local ';		# we iz not local =\
		#	$base_sql_args .= '-u ' . $username . ' ';
		#	$base_sql_args .= '-p' . $password . ' ';	# diff from others, no space between "-p" and password
		#	$base_sql_args .= '-h ' . $hostname . ' ';
		#	$base_sql_args .= '-P ' . $port . ' ';
			
		#	print("BT:!"  . ini_get("mysqli.default_host") . "||||");
			
			$this->db_link = new mysqli(
				ini_get("mysqli.default_host"),
				ini_get("mysqli.default_user"),
				ini_get("mysqli.default_pw"),
				'',
				ini_get("mysqli.default_port")
/*				$hostname,
				$username,
				$password,
				$this->host,
				$port*/
			);
			
			$this->base_sql_args = $base_sql_args;
			
			return $this->base_sql_args;
		}
		
		public function runQuery($args) {
			$query = $args['query'];
			
			$statement = $this->db_link->prepare($query);
			$objects = [];
			
#			print_r($query);
			$statement->execute();
			$result = $statement->get_result();
			
			if($result) {
				while ($row = $result->fetch_assoc()) {
					$format_row_args = [
						'row'=>$row,
					];
					$objects[] = $this->FillArraysFromDB_FormatRow($format_row_args);
				}
			} else {
				print("ERROR ON QUERY!");
			}
			
			return $objects;
		}
		
		public function FillArraysFromDB_FormatRow($args) {
			$row = $args['row'];
			
			$sub_tables = [];
			
			foreach ($row as $field_name => $field_value) {
				$first_row_field_name_char = substr($field_name, 0, 1);
				
				if($first_row_field_name_char === '.') {
					$row_explosion = explode('.', $field_name);
					
					$joined_table_name = $row_explosion[1];
					$joined_table_field = $row_explosion[2];
					
					if(!$sub_tables[$joined_table_name]) {
						$sub_tables[$joined_table_name] = [];
					}
					
					$sub_tables[$joined_table_name][$joined_table_field] = $field_value;
					unset($row[$field_name]);
				}
			}
			
			foreach ($sub_tables as $sub_table => $sub_table_fields) {
				$row[strtolower($sub_table)] = $sub_table_fields;
			}
			
			return $row;
		}
	}

?>