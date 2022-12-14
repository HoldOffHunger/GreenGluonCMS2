$(document).ready(function(e) {
	var languagecode;
	var utterance = new SpeechSynthesisUtterance();
	var voices = [];
	
	window.speechSynthesis.onvoiceschanged = function () {
		if(voices.length > 0) {
			return false;
		}
		
		updateLanguage();
		setLanguages();
		
		return true;
	}
	
	function updateLanguage() {
		voices = window.speechSynthesis.getVoices();
		for(i = 0; i < voices.length; i++) {
			voice = voices[i];
			if(voice.voiceURI == languagecode) {
				utterance.voice = voice;
				i = voices.length;
			}
		}
	}
	
	function setLanguages() {
		const voiceoptions = [];
		voices = window.speechSynthesis.getVoices();
		
		for(i = 0; i < voices.length; i++) {
			voice = voices[i];
			
			voiceoptions.push({
				'value':voice.voiceURI,
				'display':voice.name,
			});
		}
		
		htmlvoiceoptions = [];
		
		for(i = 0; i < voiceoptions.length; i++) {
			const voiceoption = voiceoptions[i];
			htmlvoiceoptions.push(
				'<option value="' + voiceoption.value + '" data-lang="' + voiceoption.value + '" data-name="' +  voiceoption.display + '">' + voiceoption.display + '</option>'
			);
		}
		
		$('#language').html(htmlvoiceoptions.join(''));
	}

	var clicked = 0;
	
	$('.input-area').click(function(e) {
		if(!clicked) {
			$(this).val('');
			clicked = 1;
		}
	});
	
	$('#pronounce-it').click(function(e) {
		listenToPhrase();
	});
	
	function listenToPhrase() {
		utterance = new SpeechSynthesisUtterance();
		var phrase = $('.input-area').val();
		
		languagecode = $('#language').val();
		
		updateLanguage();
		
		if(languagecode.match(' UK ')) {
			utterance.lang = 'en-UK';
		} else {
			utterance.lang = 'en-US';
		}
		
		utterance.text = phrase;
		window.speechSynthesis.speak(utterance);
	}
});