<?php
				
				/* EnglishMisspellings
					
					Class for converting detecting misspellings in English.
					
				*/
				
				
	class EnglishMisspellings {
			/* __construct($args)
			
				Constructor.
				
				Load the words into the converter class for ready use.
			
			*/
		
		public function __construct($args) {
			ggreq('classes/Language/EnglishMisspellings_Words.php');
			$this->words = new EnglishMisspellings_Words([]);
			$this->misspellings = $this->words->GetEnglishMisspellings();
			return TRUE;
		}
		
		public function GetWords_Misspelled() {
			$misspelled_words = array_values($this->misspellings);
			
			$misspellings = [];
			
			foreach($misspelled_words as $misspelled_word) {
				if(is_array($misspelled_word)) {
					foreach($misspelled_word as $multi_misspelling) {
						$misspellings[] = $multi_misspelling;
					}
				} else {
					$misspellings[] = $misspelled_word;
				}
			}
			
			return $misspellings;
		}
	}
?>