<?php

		# UNFINISHED!!!!!!!!!!!!!!!!!!!!!!
		
		# https://console.developers.google.com/apis/credentials/consent/edit?newAppInternalUser=false&project=defiance-art&duration=P1D

	class globals extends defaultglobals {
		public function SetAPIData() {
			return [
				'google'=>[
					'client_id'=>'739139935625-ircmgu7pm74ve5keacvfbbsnaqe987o0.apps.googleusercontent.com',
					'client_secret'=>'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',
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
		
		public function TitleAutoIncrement() {
			return TRUE;
		}
		
		public function IndexMaxRandomChildren() {
			return 25;
		}
		
		public function IndexPullChildRecordStats() {
			return TRUE;
		}
	}
	
?>