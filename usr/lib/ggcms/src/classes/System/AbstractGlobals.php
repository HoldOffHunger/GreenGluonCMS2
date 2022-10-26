<?php

	class AbstractGlobals {
		public function __construct($args) {
			$this->setHandler($args);
			$this->prepareAbstractGlobals();
			$this->buildAbstractGlobals();
			
			return $this;
		}
		
		public function setHandler($args) {
			$this->handler = $args['handler'];
			
			return TRUE;
		}
		
		public function prepareAbstractGlobals() {
			$this->domain = $this->handler->domain->primary_domain_lowercased;
			$this->action = $this->handler->desired_action;
			$this->script = $this->handler->script_file;
			
			return TRUE;
		}
		
		public function buildAbstractGlobals() {
			$shared_scripts_dir = 'clonefrom/scripts/' . $this->script . '.php';
			$domain_scripts_dir = $this->domain . '/scripts/' . $this->script . '.php';
			
		#	print("BT:" . $script . "|");
			if(conf_isfile($shared_scripts_dir)) {
				confreq($shared_scripts_dir);
				
				$classname = 'AbstractGlobals_' . $this->script;
				if(conf_isfile($domain_scripts_dir)) {
					confreq($domain_scripts_dir);
					$classname = 'local' . $classname;
				}
				
				$this->script = new $classname;
				
			#	$script_action = $this->action;
			#	$this->script->$script_action();	# inheritance is broken on PHP?
			}
#			print($domain_scripts_dir);
	#		print("SOYBEANS!");
	#		print($this->handler->domain->primary_domain_lowercased);
		}
	}

?>