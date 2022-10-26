<?php

	class globals extends defaultglobals {
		public function SetAPIData() {
			return [
				'google'=>[
					'client_id'=>'136839050412-8fm5jc31ah5i9m2kr5q0j9btfdum23gt.apps.googleusercontent.com',
					'client_secret'=>'u440o2tOAv5BXtgUuB-q6nrw',
				],
			];
		}
		
						// Config
						// -------------------------------------------------------------------
						
							// Add
							// -------------------------------------------------------------------
		
		public function AutoGenerateTitleDefault() {
			return TRUE;
		}
						
							// Add
							// -------------------------------------------------------------------
		
		public function AddEntryHTMLFormatting() {
			return TRUE;
		}
		
		public function DBInfo() {
			return [
				'username'=>'ouruprising',
				'password'=>'oe08fu%$%o0e8345231',
				'hostname'=>'mysql.revoltsource.com',
			];
		}
		
		public function DictionaryEnabled() {
			return FALSE;
		}
	}
	
?>