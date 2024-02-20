<?php

	class DBFileCache {
		
			// Construction
			// -------------------------------------------------
		
		public function __construct($args) {
			$this->handler = $args['handler'];
			
			return $this;
		}
		
		public function DBFileCacheLocation() {
			return '/mnt/nyc01/ggcms_cache/mysql_db_file_cache';
		}
		
		public function DomainFileCacheLocation() {
			if($this->db_file_cache_location) {
				return $this->db_file_cache_location;
			}
			
			$domain_folder_name = $this->handler->ReverseDomainName([
				'domain'=>$this->handler->domain->primary_domain_lowercased,
			]);
		
			return $this->db_file_cache_location = $this->DBFileCacheLocation() . '/' . $domain_folder_name;
		}
		
		public function CacheLocation($args) {
			$type = $args['type'];
			$subtype = $args['subtype'];
			
			if($args['directory']) {
				return $this->DBFileCacheLocation() . '/' . $args['directory'];
			}
			
			$domain_directory = $this->DomainFileCacheLocation();
			
			if(strlen($subtype) === 0) {
				$full_directory = $domain_directory . '/' . $type;
			} else {			
				$full_directory = $domain_directory . '/' . $type . '/' . $subtype;
			}
			
			return $full_directory;
		}
		
		public function WriteCache($args) {
			$arguments = $args['arguments'];
			
			$full_directory = $this->CacheLocation($args);
			$blanks_data = $this->ReadCache_blanks(['full_directory'=>$full_directory]);
			$full_directory .= '/';
			
			$args['blanks'] = $blanks_data;
			
			if($blanks_data) {
				$new_blanks_data = $blanks_data;
			} else {
				$new_blanks_data = [];
			}
			
			if(is_dir($full_directory)) {
				$args['full_directory'] = $full_directory;
				
				if($args['directory']) {
					$args['argument'] = $args['arguments'][0];
					$args['argument_data'] = $args['data'];
					$this->WriteCache_Item($args);
				} else {
					$arguments = array_unique($arguments);
					sort($arguments);
					$field = $args['field'];
					
					$argument_cache = [];
					
					$data = $args['data'];
					$field = $args['field'];
					
					if(!$field) {
						$field = 'id';
					}
					
					foreach($data as $datum) {
						$field_value = $datum[$field];
						
						if(strlen($field_value) === 0) {
							$field_value = $arguments[0];
						}
						
						if(!$argument_cache[$field_value]) {
							$argument_cache[$field_value] = [];
						}
						
						$argument_cache[$field_value][] = $datum;
					}
					
					foreach($arguments as $argument) {
						$args['argument'] = $argument;
						#if(is_array($argument_cache[$argument])) {
							$args['argument_data'] = $argument_cache[$argument];
							$this->WriteCache_Item($args);
					#	} else {
							$new_blanks_data = $this->WriteCache_Blank($args);
					#	}
					}
				}
			}
			
			if($_GET['soybeans']) {
				if($new_blanks_data) {
					$new_blanks_data_count = count($new_blanks_data);
					
					if($new_blanks_data_count !== 0) {
					}
					print_r($new_blanks_data);
				}
			}
			
			return TRUE;
		}
		
		public function WriteCache_Blank($args) {
			$blanks = $args['blanks'];
			
			if(!$blanks) {
				$blanks = [
					$args['argument_data'],
				];
			}
			
			return $blanks;
		}
		
		public function WriteCache_Item($args) {
			$type = $args['type'];
			$subtype = $args['subtype'];
			$field = $args['field'];
			$data = $args['data'];
			
			$full_directory = $args['full_directory'];
			$argument = $args['argument'];
			$argument_data = $args['argument_data'];
			
			$file_name = $argument;
			
			if(strlen($file_name) < $this->FileNameMax()) {
				$file_location = $full_directory . $file_name;
				
				if(!is_file($file_location)) {
					$json_data = json_encode($argument_data);
					
					$file_handle_for_writing = fopen($file_location, 'a+');
					
					if($file_handle_for_writing) {
						while (!flock($file_handle_for_writing, LOCK_EX)) {
							usleep(round(rand(0, 100)*1000)); //0-100 milliseconds
						}
						
						fwrite($file_handle_for_writing, $json_data);
						flock($file_handle_for_writing, LOCK_UN);
						fclose($file_handle_for_writing);
					}
				}
			}
			
			return TRUE;
		}
		
		public function ReadCache_blanks($args) {
			$full_directory = $args['full_directory'];
			
			$full_directory_blanks = $full_directory . '_blanks.txt';
			
			if(is_file($full_directory_blanks)) {
				$blanks = file_get_contents($full_directory_blanks);
				
				if(strlen($blanks) === 0) {
					$blanks_data = FALSE;
				} else {
					$blanks_data = explode("\n", $blanks);
				}
				/*
				print($full_directory_blanks);
				print("<BR><BR>");
				print_r($blanks_data);
				print("<BR><BR>");
				*/
				return $blanks_data;
			}
			
			return FALSE;
		}
		
		public function ReadCache($args) {
			$type = $args['type'];
			$subtype = $args['subtype'];
			$sub2type = $args['sub2type'];
			$arguments = $args['arguments'];
			$max_age = $args['max_age'];
			
			$full_directory = $this->CacheLocation($args);
			
			if(!is_dir($full_directory)) {
				return FALSE;
			}
			
			$args['full_directory'] = $full_directory;
			
			$arguments = array_unique($arguments);
			sort($arguments);
			
			$arguments_length = count($arguments);
			
			$all_data = [];
			
			$blanks_data = $this->ReadCache_blanks($args);
			
			$args['full_directory'] .= '/';
			$args['blanks'] = $blanks_data;
			
			for($i = 0; $i < $arguments_length; $i++) {
				$argument = $arguments[$i];
				$args['argument'] = $argument;
				$datum = $this->ReadCache_Item($args);
				
				if(!is_array($datum)) {
					return FALSE;
				}
				
				foreach($datum as $datum_piece) {
					if(is_array($datum_piece)) {
						$all_data[$datum_piece['id']] = $datum_piece;
					}
				}
			}
			
			if(count($all_data) === 0) {
				return [];
			}
			
			$all_data_values = array_values($all_data);
			
			return $all_data_values;
		}
		
		public function ReadCache_Item($args) {
			$type = $args['type'];
			$subtype = $args['subtype'];
			$sub2type = $args['sub2type'];
			$arguments = $args['arguments'];
			$max_age = $args['max_age'];
			
			$full_directory = $args['full_directory'];
			$argument = $args['argument'];
			
			$file_name = $argument;
			
			if(strlen($file_name) < $this->FileNameMax()) {
				$file_location = $full_directory . $file_name;
				
				if(is_file($file_location)) {
					$read_valid = TRUE;
					if($max_age) {
						$age_difference = time() - filemtime($file_location);
						if($age_difference > $max_age) {
							unlink($file_location);
							$read_valid = FALSE;
						}
					}
					
					if($read_valid) {
						$data = json_decode(file_get_contents($file_location), TRUE);
						
						return $data;
					}
				}
			}
			
			return FALSE;
		}
		
		public function FileNameMax() {
			return 128;
		}
	}

?>