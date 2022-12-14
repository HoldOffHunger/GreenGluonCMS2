<?php

	class Language {
		public function __construct($args) {
			$this->handler = $args['handler'];
			
			$this->SetLanguage();
			
			return $this;
		}
		
		public function SetLanguage() {
			$language = $this->handler->query->Parameter(['parameter'=>'language']);
			
			if(!$language) {
				$language = $this->handler->query->Parameter(['parameter'=>'lang']);
			}
			
			$language_codes = $this->GetListOfLanguageCodes();
			
			if($language) {
				if($language_codes[$language]) {
					$set_cookie_args = [
						'key'=>'language',
						'value'=>$language,
						'secure'=>0,
					];
					$this->handler->cookie->SetCookie($set_cookie_args);
					
					$this->language_code = $language;
					$this->language = $language_codes[$language];
				}
			} else {
				$get_cookie_args = [
					'cookie'=>'language',
				];
				
				$language = $this->handler->cookie->GetCookie($get_cookie_args);
				
				if($language && $language_codes[$language]) {
					$this->language_code = $language;
					$this->language = $language_codes[$language];
				}
			}
			
			if(!$this->language) {
				$this->language_code = 'en';
				$this->language = 'English';
			}
			
			header('language: ' . $this->language_code);
			
			return $this->language;
		}
		
		public function GetLanguage() {
			return $this->language;
		}
		
		public function GetLanguageCode() {
			return $this->language_code;
		}
		
					// Lists
					// --------------------------------------------------------
		
				// Country Code List
				// --------------------------------------------------------
		
		public function GetOnlyCountryCodeList() {
			return [
				'de',		// German
				'en',		// English
				'es',		// Spanish
				'fr',		// French
				'ja',		// Japanese
				'it',		// Italian
				'nl',		// Dutch
				'pl',		// Polish
				'pt',		// Portuguese
				'ru',		// Russian
				'tr',		// Turkish
				'zh',		// Chinese
			];
		}
		
		public function GetCountryCodeList() {
			return [
				'de'=>
					[
						'de',		# 639-1
						'deu',		# 639-2/T
						'ger',		# 639-2/B
					#	'deu',		# 639-3
						'deus',		# 639-6
					#	'de',		# 3166-1 2-Code
					#	'deu',		# 3166-1 3-Code
					],
				'en'=>
					[
						'en',		# 639-1
						'eng',		# 639-2/T
					#	'eng',		# 639-2/B
					#	'eng',		# 639-3
						'engs',		# 639-6
						'us',		# 3166-1 2-Code
						'usa',		# 3166-1 3-Code
					],
				'es'=>
					[
						'es',		# 639-1
						'spa',		# 639-2/T
					#	'spa',		# 639-2/B
					#	'spa',		# 639-3
					#	'',		# 639-6
					#	'es',		# 3166-1 2-Code
						'esp',		# 3166-1 3-Code
					],
				'fr'=>
					[
						'fr',		# 639-1
						'fra',		# 639-2/T
						'fre',		# 639-2/B
					#	'fra',		# 639-3
						'fras',		# 639-6
					#	'fr',		# 3166-1 2-Code
					#	'fra',		# 3166-1 3-Code
					],
				'ja'=>
					[
						'ja',		# 639-1
						'jpn',		# 639-2/T
					#	'jpn',		# 639-2/B
					#	'jpn',		# 639-3
					#	'jpn',		# 639-6	
						'jp',		# 3166-1 2-Code
					#	'jpn',		# 3166-1 3-Code
					],
				'it'=>
					[
						'it',		# 639-1
						'ita',		# 639-2/T
					#	'ita',		# 639-2/B
					#	'ita',		# 639-3
						'itas',		# 639-6
					#	'it',		# 3166-1 2-Code
					#	'ita',		# 3166-1 3-Code
					],
				'nl'=>
					[
						'nl',		# 639-1
						'nld',		# 639-2/T
						'dut',		# 639-2/B
					#	'nld',		# 639-3
					#	'',		# 639-6
					#	'nl',		# 3166-1 2-Code
					#	'nld',		# 3166-1 3-Code
					],
				'pl'=>
					[
						'pl',		# 639-1
						'pol',		# 639-2/T
					#	'pol',		# 639-2/B
					#	'pol',		# 639-3
						'pols',		# 639-6
					#	'pl',		# 3166-1 2-Code
					#	'pols',		# 3166-1 3-Code
					],
				'pt'=>
					[
						'pt',		# 639-1
						'por',		# 639-2/T
					#	'por',		# 639-2/B
					#	'por',		# 639-3
					#	'',		# 639-6
					#	'pt',		# 3166-1 2-Code
						'prt',		# 3166-1 3-Code
					],
				'ru'=>
					[
						'ru',		# 639-1
						'rus',		# 639-2/T
					#	'rus',		# 639-2/B
					#	'rus',		# 639-3
					#	'',		# 639-6
					#	'ru',		# 3166-1 2-Code
					#	'rus',		# 3166-1 3-Code
					],
				'tr'=>
					[
						'tr',		# 639-1
						'tur',		# 639-2/T
					#	'tur',		# 639-2/B
					#	'tur',		# 639-3
					#	'',		# 639-6
						'tr',		# 3166-1 2-Code
						'tur',		# 3166-1 3-Code
					],
				'zh'=>
					[
						'zh',		# 639-1
						'zho',		# 639-2/T
						'chi',		# 639-2/B
					#	'zho',		# 639-3
					#	'',		# 639-6
						'cn',		# 3166-1 2-Code
						'chn',		# 3166-1 3-Code
					],
			];
		}
		
				// Filename Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageFlags() {
			return [
				'de'=>'de_Germany.png',
				'en'=>'us_United_States.png',
				'es'=>'es_Spain.png',
				'fr'=>'fr_France.png',
				'ja'=>'jp_Japan.png',
				'it'=>'it_Italy.png',
				'nl'=>'nl_Netherlands.png',
				'pl'=>'pl_Poland.png',
				'pt'=>'pt_Portugal.png',
				'ru'=>'ru_Russian_Federation.png',
				'tr'=>'tr_Turkey.png',
				'zh'=>'cn_China.png',
			];
		}
		
				// Multi-Language Lists
				// --------------------------------------------------------
		
		public function GetListOfNativeLanguageNames() {
			return [
				'de'=>'Deutsch',
				'en'=>'English',
				'es'=>'Espa??ol',
				'fr'=>'Fran??ais',
				'ja'=>'?????????',
				'it'=>'Italiano',
				'nl'=>'Nederlands',
				'pl'=>'Polskie',
				'pt'=>'Portugu??s',
				'ru'=>'??????????????',
				'tr'=>'T??rk??e',
				'zh'=>'??????',
			];
		}
		
				// English (EN) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_any($args) {
			$language_code = $args['languagecode'];
			$function_name = 'GetListOfLanguageCodes_' . $language_code;
			return $this->$function_name();
		}
		
		public function GetListOfLanguageCodes_en() {
			return $this->GetListOfLanguageCodes();
		}
		
		public function GetListOfLanguageCodes() {
			return [
				'de'=>'German',
				'en'=>'English',
				'es'=>'Spanish',
				'fr'=>'French',
				'ja'=>'Japanese',
				'it'=>'Italian',
				'nl'=>'Dutch',
				'pl'=>'Polish',
				'pt'=>'Portuguese',
				'ru'=>'Russian',
				'tr'=>'Turkish',
				'zh'=>'Chinese',
			];
		}
		
				// German (DE) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_de() {
			return $this->GetListOfLanguageCodes_German();
		}
		
			# recheck for "language" versus "nationality"
		
		public function GetListOfLanguageCodes_German() {
			return [
				'de'=>'Deutsch',
				'en'=>'Englisch',
				'es'=>'Spanisch',
				'fr'=>'Franz??sisch',
				'ja'=>'Japanisch',
				'it'=>'Italienisch',
				'nl'=>'Niederl??nder',
				'pl'=>'Polieren',
				'pt'=>'Portugiesisch',
				'ru'=>'Russisch',
				'tr'=>'T??rkisch',
				'zh'=>'Chinesisch',
			];
		}
		
				// Spanish (ES) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_es() {
			return $this->GetListOfLanguageCodes_Spanish();
		}
		
			# recheck for "language" versus "nationality"
		
		public function GetListOfLanguageCodes_Spanish() {
			return [
				'de'=>'Alem??n',
				'en'=>'Ingl??s',
				'es'=>'Espa??ol',
				'fr'=>'Franc??s',
				'ja'=>'Japon??s',
				'it'=>'Italiano',
				'nl'=>'Holand??s',
				'pl'=>'Polaco',
				'pt'=>'Portugu??s',
				'ru'=>'Ruso',
				'tr'=>'Turco',
				'zh'=>'Chino',
			];
		}
		
				// French (FR) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_fr() {
			return $this->GetListOfLanguageCodes_French();
		}
		
			# recheck for "language" versus "nationality"
		
		public function GetListOfLanguageCodes_French() {
			return [
				'de'=>'Allemand',
				'en'=>'Anglais',
				'es'=>'Espanol',
				'fr'=>'Fran??ais',
				'ja'=>'Japonais',
				'it'=>'Italien',
				'nl'=>'N??erlandais',
				'pl'=>'Polonais',
				'pt'=>'Portugais',
				'ru'=>'Russe',
				'tr'=>'Turc',
				'zh'=>'Chinois',
			];
		}
		
				// Japanese (JA) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_ja() {
			return $this->GetListOfLanguageCodes_Japanese();
		}
		
		public function GetListOfLanguageCodes_Japanese() {
			return [
				'de'=>'????????????',
				'en'=>'??????',
				'es'=>'???????????????',
				'fr'=>'???????????????',
				'ja'=>'?????????',
				'it'=>'???????????????',
				'nl'=>'???????????????',
				'pl'=>'??????????????????',
				'pt'=>'??????????????????',
				'ru'=>'????????????',
				'tr'=>'????????????',
				'zh'=>'?????????',
			];
		}
		
				// Italian (IT) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_it() {
			return $this->GetListOfLanguageCodes_Italian();
		}
		
		public function GetListOfLanguageCodes_Italian() {
			return [
				'de'=>'Tedesco',
				'en'=>'Inglese',
				'es'=>'Spagnolo',
				'fr'=>'Francese',
				'ja'=>'Giapponese',
				'it'=>'Italiano',
				'nl'=>'Olandese',
				'pl'=>'Polacco',
				'pt'=>'Portoghese',
				'ru'=>'Russo',
				'tr'=>'Turco',
				'zh'=>'Cinese',
			];
		}
		
				// Dutch (NL) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_nl() {
			return $this->GetListOfLanguageCodes_Dutch();
		}
		
		public function GetListOfLanguageCodes_Dutch() {
			return [
				'de'=>'Duits',
				'en'=>'Engels',
				'es'=>'Spaans',
				'fr'=>'Frans',
				'ja'=>'Japans',
				'it'=>'Italiaans',
				'nl'=>'Nederlands',
				'pl'=>'Pools',
				'pt'=>'Portugees',
				'ru'=>'Russisch',
				'tr'=>'Turks',
				'zh'=>'Chinese',
			];
		}
		
				// Polish (PL) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_pl() {
			return $this->GetListOfLanguageCodes_Polish();
		}
		
		public function GetListOfLanguageCodes_Polish() {
			return [
				'de'=>'Niemiecki',
				'en'=>'Angielski',
				'es'=>'Hiszpa??ski',
				'fr'=>'Francuski',
				'ja'=>'Japo??ski',
				'it'=>'W??oski',
				'nl'=>'Holenderski',
				'pl'=>'Polskie',
				'pt'=>'Portugalski',
				'ru'=>'Rosyjski',
				'tr'=>'Turecki',
				'zh'=>'Chi??ski',
			];
		}
		
				// Portuguese (PT) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_pt() {
			return $this->GetListOfLanguageCodes_Portuguese();
		}
		
		public function GetListOfLanguageCodes_Portuguese() {
			return [
				'de'=>'Alem??o',
				'en'=>'Ingl??s',
				'es'=>'Espanhol',
				'fr'=>'Franc??s',
				'ja'=>'Japon??s',
				'it'=>'Italiano',
				'nl'=>'Holand??s',
				'pl'=>'Polon??s',
				'pt'=>'Portugu??s',
				'ru'=>'Russo',
				'tr'=>'Turco',
				'zh'=>'Chin??s',
			];
		}
		
				// Russian (RU) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_ru() {
			return $this->GetListOfLanguageCodes_Russian();
		}
		
		public function GetListOfLanguageCodes_Russian() {
			return [
				'de'=>'????????????????',
				'en'=>'????????????????????',
				'es'=>'??????????????????',
				'fr'=>'??????????????????????',
				'ja'=>'????????????????',
				'it'=>'??????????????????????',
				'nl'=>'??????????????????????',
				'pl'=>'????????????????',
				'pt'=>'??????????????????????????',
				'ru'=>'??????????????',
				'tr'=>'????????????????',
				'zh'=>'??????????????????',
			];
		}
		
				// Turkish (TR) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_tr() {
			return $this->GetListOfLanguageCodes_Turkish();
		}
		
		public function GetListOfLanguageCodes_Turkish() {
			return [
				'de'=>'Almanca',
				'en'=>'??ngilizce',
				'es'=>'??spanyol',
				'fr'=>'Frans??zca',
				'ja'=>'Japonca',
				'it'=>'??talyan',
				'nl'=>'Flemenk??e',
				'pl'=>'Leh??e',
				'pt'=>'Portekizce',
				'ru'=>'Rus??a',
				'tr'=>'T??rk??e',
				'zh'=>'??ince',
			];
		}
		
				// Chinese (ZH) Lists
				// --------------------------------------------------------
		
		public function GetListOfLanguageCodes_zh() {
			return $this->GetListOfLanguageCodes_Chinese();
		}
		
		public function GetListOfLanguageCodes_Chinese() {
			return [
				'de'=>'??????',
				'en'=>'??????',
				'es'=>'????????????',
				'fr'=>'??????',
				'ja'=>'??????',
				'it'=>'????????????',
				'nl'=>'?????????',
				'pl'=>'??????',
				'pt'=>'????????????',
				'ru'=>'??????',
				'tr'=>'?????????',
				'zh'=>'??????',
			];
		}
				
/*		
German.
English.
Spanish.
French.
Japanese.
Italian.
Dutch.
Polish.
Portuguese.
Russian.
Turkish.
Chinese.
*/
	}

?>