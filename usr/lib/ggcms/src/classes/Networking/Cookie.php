<?php

	class Cookie {
		public function __construct($args) {
			$this->handler = $args['handler'];
			$this->cookie = [];
			foreach ($_COOKIE as $cookie_key => $cookie_value) {
				$cleanser_args = [
					'input'=>$cookie_key,
				];
				
				$cleansed_key = $this->handler->cleanser->CleanseInput_EscapeBitVariableChars($cleanse_input_utf8_args)['cleansedinput'];
				
				$cleanser_args = [
					'input'=>$cookie_value,
				];
				
				$cleansed_value = $this->handler->cleanser->CleanseInput_EscapeBitVariableChars($cleanse_input_utf8_args)['cleansedinput'];
				
				$this->cookie[$cleansed_key] = $cleansed_value;
			}
			
			$this->cookie = $_COOKIE;
			
			return TRUE;
		}
		
		public function SetCookie($args) {
			$cookie_key = $args['key'];
			$cookie_value = $args['value'];
			$secure = $args['secure'];
			$permanent = $args['permanent'];
			
			if(isset($cookie_value)) {
				if(!$secure && !$permanent) {
					$cookie_time = $this->TemporaryCookieExpirationTime();
				} else {
					$cookie_time = $this->PermanentCookieExpirationTime();
				}
			} else {
				$cookie_time = $this->DeleteCookieExpirationTime();
			}
			
			setcookie(
				$cookie_key,
				$cookie_value,
				$cookie_time,
				$this->CookiePath(),
				$this->handler->domain->primary_domain_lowercased,
				$secure,
				$this->CookieHTTPOnlyOption()
			);
			
			return TRUE;
		}
		
		public function GetCookie($args) {
			$cookie_key = $args['cookie'];
			return $this->cookie[$cookie_key];
		}
		
		public function PermanentCookieExpirationTime() {
			return $this->handler->time->time + (10 * 365 * 24 * 60 * 60);
		}
		
		public function TemporaryCookieExpirationTime() {
			return $this->handler->time->time + (4* 60 * 60);
		}
		
		public function DeleteCookieExpirationTime() {
			return (-1) * ($this->time->time + (10 * 365 * 24 * 60 * 60));
		}
		
		public function CookiePath() {
			return '/';
		}
		
		public function CookieHTTPOnlyOption() {
			return FALSE;
		}
	}

?>