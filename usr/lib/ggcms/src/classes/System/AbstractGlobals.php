<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


	class AbstractGlobals {
		use ReverseDNSNotation;
		public function __construct($args) {
			$this->setHandler($args);
			$this->buildAbstractGlobals_Scripts();
			$this->buildAbstractGlobals_Language_Scripts();
			
			return $this;
		}
		
		public function setHandler($args) {
			$this->handler = $args['handler'];
			
			return TRUE;
		}
		
		public function buildAbstractGlobals_Scripts() {
			$shared_scripts_dir = 'clonefrom/scripts/' . $this->handler->script_file . '.php';
			
			$domain_scripts_dir = $this->ReverseDomainName(['domain'=>$this->handler->domain->primary_domain_lowercased]) . '/scripts/' . $this->handler->script_file . '.php';
			
			if(conf_isfile($shared_scripts_dir)) {
				confreq($shared_scripts_dir);
				
				$classname = 'AbstractGlobals_' . $this->handler->script_file;
				if(conf_isfile($domain_scripts_dir)) {
					confreq($domain_scripts_dir);
					$classname = 'local' . $classname;
				}
				
				$this->script = new $classname;
			}
			
			return TRUE;
		}
		
		public function buildAbstractGlobals_Language_Scripts() {
			$shared_default_language_scripts_dir = 'clonefrom/language_scripts/' . $this->handler->script_file . '/en.php';
			
			$shared_language_scripts_dir = 'clonefrom/language_scripts/' . $this->handler->script_file . '/' . $this->handler->language->language_code . '.php';
			
			$domain_language_scripts_dir = $this->ReverseDomainName(['domain'=>$this->handler->domain->primary_domain_lowercased]) . '/language_scripts/' . $this->handler->script_file . '/' . $this->handler->language->language_code . '.php';
			
			if(conf_isfile($domain_language_scripts_dir)) {
				confreq($domain_language_scripts_dir);
				$classname = 'AbstractGlobals_languagescript_' . $this->handler->script_file . '_' . $this->handler->language->language_code;
			} elseif(conf_isfile($shared_language_scripts_dir)) {
				confreq($shared_language_scripts_dir);
				$classname = 'AbstractGlobals_languagescript_' . $this->handler->script_file . '_' . $this->handler->language->language_code;
			} elseif(conf_isfile($shared_default_language_scripts_dir)) {
				confreq($shared_default_language_scripts_dir);
				$classname = 'AbstractGlobals_languagescript_' . $this->handler->script_file . '_en';
			}
		
		#	$classname = 'AbstractGlobals_languagescript_' . $this->handler->language->language_code;
			/*
			if(conf_isfile($domain_scripts_dir)) {
				confreq($domain_scripts_dir);
				$classname = 'AbstractGlobals_languagescript_' . $this->handler->script_file . '_' . $this->handler->language->language_code;
			} else {
				$domain_language_scripts_dir = $this->ReverseDomainName(['domain'=>$this->handler->domain->primary_domain_lowercased]) . '/language_scripts/' . $this->handler->script_file . '/en.php';
				
				if(conf_isfile($domain_scripts_dir)) {
					confreq($domain_scripts_dir);
					$classname = 'AbstractGlobals_languagescript_' . $this->handler->script_file . '_en';
				}
			}
			*/
			
			if($classname) {
				$this->language_script = new $classname;
			}
			
			return TRUE;
		}
	}

?>