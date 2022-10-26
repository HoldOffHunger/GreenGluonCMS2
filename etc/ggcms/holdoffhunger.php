<?php

	class globals extends defaultglobals {
						// DB Info
						// -------------------------------------------------------------------
		
		public function SetAPIData() {
			return [
				'google'=>[
					'client_id'=>'17782956300-goka34cojti203t52koo1asdrju76peh.apps.googleusercontent.com',
					'client_secret'=>'_tr9WRXPNqS3672nkR3tVM-u',
				],
			];
		}
		
		public function SiteCreator() {
			return 'HoldOffHunger';
		}
		
		public function SiteCreatedOn() {
			return 'Sept. 5th, 2022';
		}
		
		public function ContactCreator() {
			return 'holdoffhunger@gmail.com';
		}
	}
	
?>