<?php

	class globals extends defaultglobals {
		public function MainMenu_Enabled_Search() {
			return TRUE;
		}
		
		public function SetAPIData() {
			return [
				'google'=>[
					'client_id'=>'31222432483-8qvk3uhd27ce8ct5hdhfqb41hh34ihls.apps.googleusercontent.com',
					'client_secret'=>'FB4dPeTj_uCg1Jn_LJPUIlOH',
				],
			];
		}
		
						// Styling Info
						// -------------------------------------------------------------------
		
		public function Styling() {
			return [
				'PrimaryColor'=>'DDDDDD',
				'SecondaryColor'=>'AAAAAA',
			];
		}
		
		public function MainMenu_Enabled_News() {
			return TRUE;
		}
		
		public function MainMenu_Enabled_Feeds() {
			return TRUE;
		}
		
		public function SiteCategory() {
			return 'Education';
		}
		
		public function RequiredFieldDepths() {
			return [
				'Association'=>[
					0=>0,
					1=>0,
					2=>1,
					3=>0,
				],
			];
		}
		
		public function SiteLinks_ExtraURL() {
			return '<a href="https://www.revoltsource.com/">RevoltSource</a>';
		}
	}
	
?>