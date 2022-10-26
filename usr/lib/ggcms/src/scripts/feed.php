<?php

	require(GGCMS_DIR . 'scripts/view.php');

	class feed extends view {
		use DBFunctions;
		use SimpleForms;
		use SimpleORM;
		
			// Security Data
		
		public function IsSecure() {
			return TRUE;
		}
		
		public function RequiresLogin() {
			return FALSE;
		}
		
			// Login
			//
			// Just show the login screen, i.e., username/password.
			//
			// ---------------------------------------------
		
		public function Display() {
			$this->SetORMBasics();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			$this->redirect_script = 'news';
			
			return FALSE;
		}
	}

?>