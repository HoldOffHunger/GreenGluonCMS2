<?php

	class globals extends defaultglobals {
		public function MainMenu_Enabled_Search() {
			return TRUE;
		}
		
		public function SetAPIData() {
			return [
				'google'=>[
					'client_id'=>'179322526535-d84kjhr0fevm0atc1gi3k7ldabr1e7jt.apps.googleusercontent.com',
					'client_secret'=>'Kg7-EclXcfpgBoOqgvBvgM5z',
				],
			];
		}
		
						// About Info
						// -------------------------------------------------------------------
		
		public function SiteCreator() {
			return 'HoldOffHunger';
		}
		
		public function SiteCreatedOn() {
			return 'August 5, 2020';
		}
		
		public function ContactCreator() {
			return 'holdoffhunger@gmail.com';
		}
	}
	
?>