<?php

	class module_text extends module_spacing {
				// Script-Level Components
			
			// -------------------------------------------------------------
		
		//header, span, p, etc.?
		
		public function Display ($args) {
			
			$text = $args['text'];
			
			print($text);
			
			$this->DisplayDoubleLineBreak();
		}
	} 

?>