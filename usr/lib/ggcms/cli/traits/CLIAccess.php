<?php

	trait CLIAccess {
			// Override Functions
		
		public function bannerMessageText() {
			die('Abstract method.  Please override to proceed.');
		}
		
		public function confirmDomainText() {
			die('Abstract method.  Please override to proceed.');
		}
		
			// Constructor
			
		public function __construct($args) {
			$this->argv = $args['argv'];
			
			return $this;
		}
		
			// Standard Functions
			
		public function formatTable($args) {
			$output = $args['output'];
			
			$output_lines = explode("\n", $output);
			
			$table_input = [];
			
			unset($output_lines[0]);
			
			foreach($output_lines as $output_line) {
				$output_line = trim($output_line);
				$output_line_pieces = explode("\t", $output_line);
				$count = $output_line_pieces[0];
				$url = $output_line_pieces[1];
				
				if(strlen($count) !== 0 && strlen($url) !== 0) {
					$output_array = [
						'Count'=>$count,
						'URL'=>$url,
					];
					
					$table_input[] = $output_array;
				}
			}
			
			return arr2textTable($table_input);
		}
		
		public function setHandle() {
			$this->handle = fopen('php://stdin', 'r');
			
			return TRUE;
		}
		
		public function bannerMessage() {
			print("\n");
			print("GGCMS - " . $this->bannerMessageText() . "\n");
			print("###################################################\n");
			print("###################################################\n");
			print("###################################################\n\n");
			
			return TRUE;
		}
		
		public function basicConfirmDialogue($args) {
			$message = $args['message'];
			
			print($message);
			print("\n\n");
			print('Proceed? (y/n)');
			
			if(property_exists($this, 'answer_type')) {
				if($this->answer_type === 'y' || $this->answer_type === 'yes') {
					return TRUE;
				}
			}
			
			if(array_key_exists(2, $this->argv) && $this->argv[2]) {
				$proceed = $this->argv[2];
				print($proceed . "\n");
			} else {
				$proceed = strtolower(trim(fgets($this->handle)));
			}
			
			print("\n");
			
			if($proceed === 'y' || $proceed === 'yes') {
				return TRUE;
			}
			
			return FALSE;
		}
		
		public function cancelAction($args) {
			print($args['message'] .  "  Exiting...\n\n");
			return exit(1);
		}
		
		public function setDomain() {
			print("Enter Fully-Qualified Domain Name (without subnet): ");
			
			if(array_key_exists(1, $this->argv) && $this->argv[1]) {
				$this->domain = $this->argv[1];
				print($this->domain . "\n");
			} else {
				$this->domain = strtolower(trim(fgets($this->handle)));
			}
			
			$domain_parts = explode('.', $this->domain);
			
			if(!$this->validateDomain(['domain_parts'=>$domain_parts])) {
				return FALSE;
			}
			
			$this->host = $domain_parts[0];
			
			print("\n");
			
			print($this->confirmDomainText());
			print($this->domain);
			
			print("\n\n");
			
			return TRUE;
		}
		
		public function validateDomain($args) {
			$domain_parts = $args['domain_parts'];
			
			if(count($domain_parts) !== 2 || strlen($domain_parts[0]) === 0 || strlen($domain_parts[1]) === 0) {
				return FALSE;
			}
			
			return TRUE;
		}
		
		public function successResults() {
			print("\033[32mPASS\033[0m");
			
			return TRUE;
		}
		
		public function failResults() {
			print("\033[31mFAIL\033[0m");
			
			return TRUE;
		}
	}

?>