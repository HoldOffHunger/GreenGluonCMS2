<?php

		// https://console.developers.google.com/apis/credentials?project=earthfluent

	class globals extends defaultglobals {
		public function SetAPIData() {
			return [
				'google'=>[
					'client_id'=>'413599668789-7q6lpe6ut0uoied3lq9ec76g4buu6jt1.apps.googleusercontent.com',
					'client_secret'=>'he_nQIqXMg0ndLpkNOETx_FX',
				],
			];
		}
		
		public function MainMenu_Enabled_News() {
			return TRUE;
		}
		
		public function TitleAutoIncrement() {
			return TRUE;
		}
		
		public function SiteCategory() {
			return 'Education';
		}
		
		public function OverrideDatabaseName() {
			return 'earthfluent2';
		}
	}
	
?>