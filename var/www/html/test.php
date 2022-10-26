<?php
 
 
 
	class AbstractGlobals_about {
		public function GetAboutSubHeader() {
			return 'More Information About Us :';
		}
 
		public function GetAboutContent() {
			print("SOY");
			return 'About content will go here.';
		}
	}
 
	class localAbstractGlobals_about extends AbstractGlobals_about {
		public function GetAboutContent() {
			return 'About456456645456 content will go here.';
		}
	}
 
	$hey = new localAbstractGlobals_about;
	print($hey->GetAboutContent());

?>