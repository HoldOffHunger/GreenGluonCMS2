<?php

	class Country {
		public function __construct() {
		}
		
					// Anyone's Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames($args) {
			$language = $args['language'];
			
			$get_country_names_function = 'GetTranslatedCountryNames_' . $language;
			
			return $this->$get_country_names_function();
		}
		
					// English (EN) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_en() {
			return [
				'de'=>
					[
						'Germany',
						'Austria',
						'Switzerland',
						'Liechtenstein',
					],
				'en'=>
					[
						'United States of America',
						'India',
						'Ireland',
						'Pakistan',
						'Nigeria',
						'England',
						'Australia',
						'Canada',
						'Ghana',
						'Malta',
						'Israel',
						'New Zealand',
						'Jamaica',
					],
				'es'=>
					[
						'Spain',
						'Cuba',
						'Mexico',
						'Colombia',
						'Argentina',
						'Peru',
						'Venezuela',
						'Chile',
						'Ecuador',
						'Guatemala',
						'Bolivia',
						'Dominican Republic',
						'Honduras',
						'Paraguay',
						'El Salvador',
						'Nicaragua',
						'Costa Rica',
						'Puerto Rico',
						'Panama',
						'Uruguay',
						'Guyana',
						'Equatorial Guinea',
						'Andorra',
						'Belize',
					],
				'fr'=>
					[
						'France',
						'Canada',
						'Madagascar',
						'Cameroon',
						'Ivory Coast',
						'Burkina Faso',
						'Niger',
						'Senegal',
						'Mali',
						'Rwanda',
						'Belgium',
						'Haiti',
						'Chad',
						'Guinea',
						'Burundi',
						'Benin',
						'Switzerland',
						'Togo',
						'Central African Republic',
						'Republic of the Congo',
						'Gabon',
						'Comoros',
						'Equatorial Guinea',
						'French Polynesia',
						'French Guiana',
						'New Caledonia',
						'Aosta Valley',
						'Jersey',
						'Guernsey',
						'Monaco',
						'Saint-Martin',
						'Wallis and Futuna',
						'Saint-Barth??lemy',
					],
				'ja'=>
					[
						'Japan',
					],
				'it'=>
					[
						'Italy',
						'Malta',
						'San Marino',
						'Switzerland',
						'Croatia',
						'Slovenia',
					],
				'nl'=>
					[
						'Netherlands',
						'Belgium',
						'Suriname',
						'South Africa',
						'Aruba',
						'Cura??ao',
						'Republic of Namibia',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Poland',
					],
				'pt'=>
					[
						'Portugal',
						'Brazil',
						'Mozambique',
						'Angola',
						'Guinea-Bissau',
						'East Timor',
						'Equatorial Guinea',
						'Macau',
						'Cape Verde',
						'S??o Tom?? and Pr??ncipe',
					],
				'ru'=>
					[
						'Russia',
						'Belarus',
						'Kazakhstan',
						'Kyrgyzstan',
						'Ukraine',
						'Latvia',
						'Moldova',
						'Estonia',
						'Georgia',
						'Armenia',
						'Lithuania',
						'Uzbekistan',
					],
				'tr'=>
					[
						'Turkey',
						'Cyprus',
						'Azerbaijan',
						'Bosnia',
						'Bulgaria',
						'Greece',
						'Iraq',
						'Kosovo',
						'Macedonia',
						'Northern Cyprus',
						'Romania',
					],
				'zh'=>
					[
						'China',
						'Taiwan',
						'Singapore',
					],
			];
		}
		
					// German (DE) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_de()
		{
			return [
				'de'=>
					[
						'Deutschland',
						'??sterreich',
						'Schweiz',
						'Liechtenstein',
					],
				'en'=>
					[
						'Vereinigte Staaten von Amerika',
						'Indien',
						'Irland',
						'Pakistan',
						'Nigeria',
						'England',
						'Australia',
						'Kanada',
						'Ghana',
						'Malta',
						'Israel',
						'Neuseeland',
						'Jamaika',
					],
				'es'=>
					[
						'Spanien',
						'Kuba',
						'Mexiko',
						'Kolumbien',
						'Argentinien',
						'Peru',
						'Venezuela',
						'Chile',
						'Ecuador',
						'Guatemala',
						'Bolivien',
						'Dominikanische Republik',
						'Honduras',
						'Paraguay',
						'El Salvador',
						'Nicaragua',
						'Costa Rica',
						'Puerto Rico',
						'Panama',
						'Uruguay',
						'Guyana',
						'??quatorialguinea',
						'Andorra',
						'Belize',
					],
				'fr'=>
					[
						'Frankreich',
						'Kanada',
						'Madagaskar',
						'Kamerun',
						'Elfenbeink??ste',
						'Burkina Faso',
						'Niger',
						'Senegal',
						'Mali',
						'Ruanda',
						'Belgien',
						'Haiti',
						'Chad',
						'Guinea',
						'Burundi',
						'Benin',
						'Schweiz',
						'Gehen',
						'Zentralafrikanische Republik',
						'Republik Kongo',
						'Gabun',
						'Komoren',
						'??quatorialguinea',
						'Franz??sisch Polynesien',
						'Franz??sisch-Guayana',
						'Neu-Kaledonien',
						'Aostatal',
						'Jersey',
						'Guernsey',
						'Monaco',
						'Saint-Martin',
						'Wallis und Futuna',
						'Saint-Barth??lemy',
					],
				'ja'=>
					[
						'Japan',
					],
				'it'=>
					[
						'Italien',
						'Malta',
						'San Marino',
						'Schweiz',
						'Kroatien',
						'Slowenien',
					],
				'nl'=>
					[
						'Niederlande',
						'Belgien',
						'Suriname',
						'S??dafrika',
						'Aruba',
						'Curacao',
						'Republic of Namibia',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Polen',
					],
				'pt'=>
					[
						'Portugal',
						'Brasilien',
						'Mosambik',
						'Angola',
						'Guinea-Bissau',
						'Osttimor',
						'??quatorialguinea',
						'Macau',
						'Kap Verde',
						'S??o Tom?? und Pr??ncipe',
					],
				'ru'=>
					[
						'Russland',
						'Belarus',
						'Kasachstan',
						'Kirgisien',
						'Ukraine',
						'Lettland',
						'Moldawien',
						'Estland',
						'Georgia',
						'Armenien',
						'Litauen',
						'Usbekistan',
					],
				'tr'=>
					[
						'Truthahn',
						'Zypern',
						'Aserbaidschan',
						'Bosnien',
						'Bulgarien',
						'Griechenland',
						'Irak',
						'Kosovo',
						'Mazedonien',
						'Nord-Zypern',
						'Rum??nien',
					],
				'zh'=>
					[
						'China',
						'Taiwan',
						'Singapur',
					],
			];
		}
		
					// Spanish (ES) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_es()
		{
			return [
				'de'=>
					[
						'Alemania',
						'Austria',
						'Suiza',
						'Liechtenstein',
					],
				'en'=>
					[
						'Estados Unidos de America',
						'India',
						'Irlanda',
						'Pakist??n',
						'De Nigeria',
						'Inglaterra',
						'Australia',
						'Canad??',
						'Ghana',
						'Malta',
						'Israel',
						'Nueva Zelanda',
						'Jamaica',
					],
				'es'=>
					[
						'Espa??a',
						'Cuba',
						'M??xico',
						'Colombia',
						'Argentina',
						'Per??',
						'Venezuela',
						'Chile',
						'Ecuador',
						'Guatemala',
						'Bolivia',
						'Rep??blica Dominicana',
						'Honduras',
						'Paraguay',
						'El Salvador',
						'Nicaragua',
						'Costa Rica',
						'Puerto Rico',
						'Panam??',
						'Uruguay',
						'Guyana',
						'Guinea Ecuatorial',
						'Andorra',
						'Belice',
					],
				'fr'=>
					[
						'Francia',
						'Canad??',
						'Madagascar',
						'Camer??n',
						'Costa de Marfil',
						'Burkina Faso',
						'Niger',
						'Senegal',
						'Mali',
						'Ruanda',
						'B??lgica',
						'Hait??',
						'Chad',
						'Guinea',
						'Burundi',
						'Benin',
						'Suiza',
						'Ir',
						'Rep??blica Centroafricana',
						'Rep??blica del Congo',
						'Gab??n',
						'Comoras',
						'Guinea Ecuatorial',
						'Polinesia franc??s',
						'Guayana franc??s',
						'Nueva Caledonia',
						'Valle de Aosta',
						'Jersey',
						'Guernsey',
						'M??naco',
						'San Mart??n',
						'Wallis y Futuna',
						'San Bartolom??',
					],
				'ja'=>
					[
						'Jap??n',
					],
				'it'=>
					[
						'Italia',
						'Malta',
						'San Marino',
						'Suiza',
						'Croacia',
						'Eslovenia',
					],
				'nl'=>
					[
						'Pa??ses Bajos',
						'B??lgica',
						'Surinam',
						'Sud??frica',
						'Aruba',
						'Curazao',
						'Rep??blica de Namibia',
						'Bonaire',
						'Saba',
						'San Eustaquio',
						'San Mart??n',
					],
				'pl'=>
					[
						'Polonia',
					],
				'pt'=>
					[
						'Portugal',
						'Brasil',
						'Mozambique',
						'Angola',
						'Guinea-Bissau',
						'Timor Oriental',
						'Guinea Ecuatorial',
						'Macao',
						'Cabo Verde',
						'Santo Tom?? y Pr??ncipe',
					],
				'ru'=>
					[
						'Rusia',
						'Belar??s',
						'Kazajst??n',
						'Kirguist??n',
						'Ucrania',
						'Letonia',
						'Moldova',
						'Estonia',
						'Georgia',
						'Armenia',
						'Lituania',
						'Uzbekist??n',
					],
				'tr'=>
					[
						'Turqu??a',
						'Chipre',
						'Azerbaiy??n',
						'Bosnia',
						'Bulgaria',
						'Grecia',
						'Irak',
						'Kosovo',
						'Macedonia',
						'El norte de Chipre',
						'Rumania',
					],
				'zh'=>
					[
						'China',
						'Taiw??n',
						'Singapur',
					],
			];
		}
		
					// French (FR) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_fr()
		{
			return [
				'de'=>
					[
						'Allemagne',
						'Autriche',
						'Suisse',
						'Le Liechtenstein',
					],
				'en'=>
					[
						'Etats-Unis d\'Amerique',
						'Inde',
						'Irlande',
						'Pakistan',
						'Nigeria',
						'Angleterre',
						'Australie',
						'Canada',
						'Ghana',
						'Malte',
						'Isra??l',
						'Nouvelle Z??lande',
						'Jama??que',
					],
				'es'=>
					[
						'L\'Espagne',
						'Cuba',
						'Mexique',
						'Colombie',
						'Argentine',
						'P??rou',
						'Venezuela',
						'Chili',
						'??quateur',
						'Guatemala',
						'Bolivie',
						'R??publique Dominicaine',
						'Honduras',
						'Paraguay',
						'Le Salvador',
						'Nicaragua',
						'Costa Rica',
						'Puerto Rico',
						'Panama',
						'Uruguay',
						'Guyana',
						'Guin??e ??quatoriale',
						'Andorre',
						'Belize',
					],
				'fr'=>
					[
						'France',
						'Canada',
						'Madagascar',
						'Cameroun',
						'C??te d\'Ivoire',
						'Burkina Faso',
						'Niger',
						'S??n??gal',
						'Mali',
						'Rwanda',
						'Belgique',
						'Ha??ti',
						'Tchad',
						'Guin??e',
						'Burundi',
						'B??nin',
						'Suisse',
						'Aller',
						'R??publique centrafricaine',
						'R??publique du Congo',
						'Gabon',
						'Comores',
						'Guin??e ??quatoriale',
						'Polyn??sie fran??aise',
						'Guin??e Fran??aise',
						'Nouvelle Cal??donie',
						'Vall??e d\'Aoste',
						'Jersey',
						'Guernsey',
						'Monaco',
						'Saint Martin',
						'Wallis et Futuna',
						'Saint-Barth??lemy',
					],
				'ja'=>
					[
						'Japon',
					],
				'it'=>
					[
						'Italie',
						'Malte',
						'Saint Marin',
						'Suisse',
						'Croatie',
						'Slov??nie',
					],
				'nl'=>
					[
						'Pays-Bas',
						'Belgique',
						'Suriname',
						'Afrique du Sud',
						'Aruba',
						'Curacao',
						'R??publique de Namibie',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Pologne',
					],
				'pt'=>
					[
						'Le Portugal',
						'Br??sil',
						'Mozambique',
						'Angola',
						'Guin??e-Bissau',
						'Timor oriental',
						'Guin??e ??quatoriale',
						'Macao',
						'Cap-Vert',
						'S??o Tom?? et Pr??ncipe',
					],
				'ru'=>
					[
						'Russie',
						'Belarus',
						'Kazakhstan',
						'Kirghizistan',
						'Ukraine',
						'Lettonie',
						'Moldova',
						'Estonie',
						'G??orgie',
						'Arm??nie',
						'Lituanie',
						'Ouzb??kistan',
					],
				'tr'=>
					[
						'Dinde',
						'Chypre',
						'Azerba??djan',
						'Bosnie',
						'Bulgarie',
						'Gr??ce',
						'Irak',
						'Kosovo',
						'Mac??doine',
						'Chypre du Nord',
						'Roumanie',
					],
				'zh'=>
					[
						'Chine',
						'Ta??wan',
						'Singapour',
					],
			];
		}
		
					// Japanese (JA) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_ja()
		{
			return [
				'de'=>
					[
						'?????????',
						'??????????????????',
						'?????????',
						'???????????????????????????',
					],
				'en'=>
					[
						'?????????????????????',
						'?????????',
						'??????????????????',
						'???????????????',
						'??????????????????',
						'??????????????????',
						'?????????????????????',
						'?????????',
						'?????????',
						'?????????',
						'???????????????',
						'????????????????????????',
						'???????????????',
					],
				'es'=>
					[
						'????????????',
						'????????????',
						'????????????',
						'???????????????',
						'??????????????????',
						'?????????',
						'???????????????',
						'??????',
						'???????????????',
						'???????????????',
						'????????????',
						'?????????????????????',
						'??????????????????',
						'???????????????',
						'?????????????????????',
						'???????????????',
						'???????????????',
						'??????????????????',
						'?????????',
						'???????????????',
						'????????????',
						'???????????????',
						'????????????',
						'????????????',
					],
				'fr'=>
					[
						'????????????',
						'?????????',
						'??????????????????',
						'???????????????',
						'????????????????????????',
						'?????????????????????',
						'???????????????',
						'????????????',
						'??????',
						'????????????',
						'????????????',
						'?????????',
						'?????????',
						'?????????',
						'????????????',
						'?????????',
						'?????????',
						'??????',
						'???????????????????????????',
						'??????????????????',
						'?????????',
						'?????????',
						'???????????????',
						'??????????????????????????????',
						'???????????????',
						'????????????????????????',
						'??????????????????????????????',
						'???????????????',
						'??????????????????',
						'?????????',
						'?????????????????????',
						'?????????????????????????????????',
						'??????????????????????????????',
					],
				'ja'=>
					[
						'??????',
					],
				'it'=>
					[
						'????????????',
						'?????????',
						'??????????????????',
						'?????????',
						'???????????????',
						'???????????????',
					],
				'nl'=>
					[
						'????????????',
						'????????????',
						'????????????',
						'???????????????',
						'?????????',
						'???????????????',
						'?????????????????????',
						'???????????????',
						'??????',
						'?????????????????????????????????',
						'???????????????????????????',
					],
				'pl'=>
					[
						'???????????????',
					],
				'pt'=>
					[
						'???????????????',
						'????????????',
						'??????????????????',
						'????????????',
						'??????????????????',
						'??????????????????',
						'???????????????',
						'?????????',
						'??????????????????',
						'???????????????????????????',
					],
				'ru'=>
					[
						'?????????',
						'???????????????',
						'??????????????????',
						'??????????????????',
						'???????????????',
						'????????????',
						'????????????',
						'???????????????',
						'???????????????',
						'???????????????',
						'???????????????',
						'?????????????????????',
					],
				'tr'=>
					[
						'?????????',
						'????????????',
						'????????????????????????',
						'????????????',
						'???????????????',
						'????????????',
						'?????????',
						'?????????',
						'???????????????',
						'???????????????',
						'???????????????',
					],
				'zh'=>
					[
						'??????',
						'??????',
						'??????????????????',
					],
			];
		}
		
					// Italian (IT) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_it()
		{
			return [
				'de'=>
					[
						'Germania',
						'Austria',
						'Svizzera',
						'Liechtenstein',
					],
				'en'=>
					[
						'Stati Uniti d\'America',
						'India',
						'Irlanda',
						'Pakistan',
						'Nigeria',
						'Inghilterra',
						'Australia',
						'Canada',
						'Ghana',
						'Malta',
						'Israele',
						'Nuova Zelanda',
						'Giamaica',
					],
				'es'=>
					[
						'Spagna',
						'Cuba',
						'Messico',
						'Colombia',
						'Argentina',
						'Per??',
						'Venezuela',
						'Chile',
						'Ecuador',
						'Guatemala',
						'Bolivia',
						'Repubblica Dominicana',
						'Honduras',
						'Paraguay',
						'El Salvador',
						'Nicaragua',
						'Costa Rica',
						'Puerto Rico',
						'Panama',
						'Uruguay',
						'Guyana',
						'Guinea Equatoriale',
						'Andorra',
						'Belize',
					],
				'fr'=>
					[
						'Francia',
						'Canada',
						'Madagascar',
						'Camerun',
						'Costa d\'Avorio',
						'Burkina Faso',
						'Niger',
						'Senegal',
						'Mali',
						'Ruanda',
						'Belgio',
						'Haiti',
						'Chad',
						'Guinea',
						'Burundi',
						'Benin',
						'Svizzera',
						'Andare',
						'Repubblica Centrafricana',
						'Repubblica del Congo',
						'Gabon',
						'Comore',
						'Guinea Equatoriale',
						'Polinesia francese',
						'Guiana francese',
						'Nuova Caledonia',
						'Valle d\'Aosta',
						'Maglia',
						'Maglione',
						'Monaco',
						'Saint-Martin',
						'Wallis e Futuna',
						'Saint-Barth??lemy',
					],
				'ja'=>
					[
						'Giappone',
					],
				'it'=>
					[
						'Italia',
						'Malta',
						'San Marino',
						'Svizzera',
						'Croazia',
						'Slovenia',
					],
				'nl'=>
					[
						'Olanda',
						'Belgio',
						'Suriname',
						'Sud Africa',
						'Aruba',
						'Curacao',
						'Repubblica di Namibia',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Polonia',
					],
				'pt'=>
					[
						'Portogallo',
						'Brasile',
						'Mozambico',
						'Angola',
						'Guinea-Bissau',
						'Timor Est',
						'Guinea Equatoriale',
						'Macau',
						'Capo Verde',
						'Sao Tome e Principe',
					],
				'ru'=>
					[
						'Russia',
						'Bielorussia',
						'Kazakhstan',
						'Kyrgyzstan',
						'Ucraina',
						'Lettonia',
						'Moldova',
						'Estonia',
						'Georgia',
						'Armenia',
						'Lituania',
						'Uzbekistan',
					],
				'tr'=>
					[
						'Tacchino',
						'Cipro',
						'Azerbaijan',
						'Bosnia',
						'Bulgaria',
						'Grecia',
						'Iraq',
						'Kosovo',
						'Macedonia',
						'Cipro del Nord',
						'Romania',
					],
				'zh'=>
					[
						'Cina',
						'Taiwan',
						'Singapore',
					],
			];
		}
		
					// Dutch (NL) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_nl()
		{
			return [
				'de'=>
					[
						'Duitsland',
						'Oostenrijk',
						'Zwitserland',
						'Liechtenstein',
					],
				'en'=>
					[
						'de Verenigde Staten van Amerika',
						'Indi??',
						'Ierland',
						'Pakistan',
						'Nigeria',
						'Engeland',
						'Australi??',
						'Canada',
						'Ghana',
						'Malta',
						'Isra??l',
						'Nieuw Zeeland',
						'Jamaica',
					],
				'es'=>
					[
						'Spanje',
						'Cuba',
						'Mexico',
						'Colombia',
						'Argentini??',
						'Peru',
						'Venezuela',
						'Chili',
						'Ecuador',
						'Guatemala',
						'Bolivia',
						'Dominicaanse Republiek',
						'Honduras',
						'Paraguay',
						'El Salvador',
						'Nicaragua',
						'Costa Rica',
						'Puerto Rico',
						'Panama',
						'Uruguay',
						'Guyana',
						'Equatoriaal-Guinea',
						'Andorra',
						'Belize',
					],
				'fr'=>
					[
						'Frankrijk',
						'Canada',
						'Madagascar',
						'Kameroen',
						'Ivoorkust',
						'Burkina Faso',
						'Niger',
						'Senegal',
						'Mali',
						'Rwanda',
						'Belgi??',
						'Ha??ti',
						'Tsjaad',
						'Guinea',
						'Boeroendi',
						'Benin',
						'Zwitserland',
						'Gaan',
						'Centraal Afrikaanse Republiek',
						'Republiek Congo',
						'Gabon',
						'Comoren',
						'Equatoriaal-Guinea',
						'Frans-Polynesi??',
						'Frans-Guyana',
						'Nieuw-Caledoni??',
						'Valle d\'Aosta',
						'Jersey',
						'Guernsey',
						'Monaco',
						'Saint-Martin',
						'Wallis en Futuna',
						'Saint-Barth??lemy',
					],
				'ja'=>
					[
						'Japan',
					],
				'it'=>
					[
						'Itali??',
						'Malta',
						'San Marino',
						'Zwitserland',
						'Kroati??',
						'Sloveni??',
					],
				'nl'=>
					[
						'Nederland',
						'Belgi??',
						'Suriname',
						'Zuid-Afrika',
						'Aruba',
						'Cura??ao',
						'Republiek Namibi??',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Polen',
					],
				'pt'=>
					[
						'Portugal',
						'Brazili??',
						'Mozambique',
						'Angola',
						'Guinee-Bissau',
						'Oost Timor',
						'Equatoriaal-Guinea',
						'Macau',
						'Kaapverdi??',
						'S??o Tom?? en Pr??ncipe',
					],
				'ru'=>
					[
						'Rusland',
						'Wit-Rusland',
						'Kazachstan',
						'Kirgizi??',
						'Oekra??ne',
						'Letland',
						'Moldavi??',
						'Estland',
						'Georgia',
						'Armeni??',
						'Litouwen',
						'Oezbekistan',
					],
				'tr'=>
					[
						'Turkije',
						'Cyprus',
						'Azerbeidzjan',
						'Bosni??',
						'Bulgarije',
						'Griekenland',
						'Irak',
						'Kosovo',
						'Macedoni??',
						'Noord-Cyprus',
						'Roemeni??',
					],
				'zh'=>
					[
						'China',
						'Taiwan',
						'Singapore',
					],
			];
		}
		
					// Polish (PL) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_pl()
		{
			return [
				'de'=>
					[
						'Niemcy',
						'Austria',
						'Szwajcaria',
						'Liechtenstein',
					],
				'en'=>
					[
						'Stany Zjednoczone Ameryki',
						'Indie',
						'Irlandia',
						'Pakistan',
						'Nigeria',
						'Anglia',
						'Australia',
						'Kanada',
						'Ghana',
						'Malta',
						'Izrael',
						'Nowa Zelandia',
						'Jamaica',
					],
				'es'=>
					[
						'Hiszpania',
						'Kuba',
						'Meksyk',
						'Kolumbia',
						'Argentyna',
						'Peru',
						'Wenezuela',
						'Chile',
						'Ekwador',
						'Gwatemala',
						'Boliwia',
						'Republika Dominikany',
						'Honduras',
						'Paragwaj',
						'Salwador',
						'Nikaragua',
						'Kostaryka',
						'Portoryko',
						'Panama',
						'Urugwaj',
						'Gujana',
						'Gwinea R??wnikowa',
						'Andorra',
						'Belize',
					],
				'fr'=>
					[
						'Francja',
						'Kanada',
						'Madagaskar',
						'Kamerun',
						'Wybrze??e Ko??ci S??oniowej',
						'Burkina Faso',
						'Niger',
						'Senegal',
						'Mali',
						'Rwandy',
						'Belgia',
						'Haiti',
						'Czad',
						'Gwinea',
						'Burundi',
						'Benin',
						'Szwajcaria',
						'I????',
						'Republika ??rodkowoafryka??ska',
						'Republika Konga',
						'Gabon',
						'Komory',
						'Gwinea R??wnikowa',
						'Polinezja Francuska',
						'Gujana Francuska',
						'Nowa Kaledonia',
						'Dolina Aosty',
						'Jersey',
						'Guernsey',
						'Monaco',
						'Saint-Martin',
						'Wallis i Futuna',
						'Saint-Barth??lemy',
					],
				'ja'=>
					[
						'Japonia',
					],
				'it'=>
					[
						'W??ochy',
						'Malta',
						'San Marino',
						'Szwajcaria',
						'Chorwacja',
						'S??owenia',
					],
				'nl'=>
					[
						'Holandia',
						'Belgia',
						'Surinam',
						'Afryka Po??udniowa',
						'Aruba',
						'Curacao',
						'Republika Namibii',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Polska',
					],
				'pt'=>
					[
						'Portugalia',
						'Brazylia',
						'Mozambik',
						'Angola',
						'Gwinea Bissau',
						'Wschodni Timor',
						'Gwinea R??wnikowa',
						'Macau',
						'Wyspy Zielonego Przyl??dka',
						'??wi??tego Tomasza i Ksi??????ca',
					],
				'ru'=>
					[
						'Rosja',
						'Bia??oru??',
						'Kazachstan',
						'Kirgistan',
						'Ukraina',
						'??otwa',
						'Mo??dawia',
						'Estonia',
						'Gruzja',
						'Armenia',
						'Litwa',
						'Uzbekistan',
					],
				'tr'=>
					[
						'Indyk',
						'Cypr',
						'Azerbejd??an',
						'Bo??nia',
						'Bu??garia',
						'Grecja',
						'Irak',
						'Kosowo',
						'Macedonia',
						'Cypr P????nocny',
						'Rumunia',
					],
				'zh'=>
					[
						'Chiny',
						'Tajwan',
						'Singapur',
					],
			];
		}
		
					// Portuguese (PT) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_pt()
		{
			return [
				'de'=>
					[
						'Alemanha',
						'??ustria',
						'Su????a',
						'Liechtenstein',
					],
				'en'=>
					[
						'Estados Unidos da America',
						'??ndia',
						'Irlanda',
						'Paquist??o',
						'Nig??ria',
						'Inglaterra',
						'Austr??lia',
						'Canad??',
						'Gana',
						'Malta',
						'Israel',
						'Nova Zel??ndia',
						'Jamaica',
					],
				'es'=>
					[
						'Espanha',
						'Cuba',
						'M??xico',
						'Col??mbia',
						'Argentina',
						'Peru',
						'Venezuela',
						'Chile',
						'Equador',
						'Guatemala',
						'Bol??via',
						'Rep??blica Dominicana',
						'Honduras',
						'Paraguai',
						'El Salvador',
						'Nicar??gua',
						'Costa Rica',
						'Porto Rico',
						'Panam??',
						'Uruguai',
						'Guiana',
						'Guin?? Equatorial',
						'Andorra',
						'Belize',
					],
				'fr'=>
					[
						'Fran??a',
						'Canad??',
						'Madag??scar',
						'Camar??es',
						'Costa do Marfim',
						'Burkina Faso',
						'N??ger',
						'Senegal',
						'Mali',
						'Ruanda',
						'B??lgica',
						'Haiti',
						'Chade',
						'Guin??',
						'Burundi',
						'Benin',
						'Su????a',
						'Ir',
						'Central Africano Rep??blica',
						'Rep??blica do Congo',
						'Gab??o',
						'Comores',
						'Guin?? Equatorial',
						'Polin??sia Francesa',
						'Guiana Francesa',
						'Nova Caled??nia',
						'Vale de Aosta',
						'Camisola',
						'Guernsey',
						'Monaco',
						'Saint-Martin',
						'Wallis e Futuna',
						'Saint-Barth??lemy',
					],
				'ja'=>
					[
						'Jap??o',
					],
				'it'=>
					[
						'It??lia',
						'Malta',
						'San Marino',
						'Su????a',
						'Cro??cia',
						'Eslovenia',
					],
				'nl'=>
					[
						'Pa??ses Baixos',
						'B??lgica',
						'Suriname',
						'??frica do Sul',
						'Aruba',
						'Cura??ao',
						'Rep??blica da Nam??bia',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Pol??nia',
					],
				'pt'=>
					[
						'Portugal',
						'Brasil',
						'Mo??ambique',
						'Angola',
						'Guin??-Bissau',
						'Timor Leste',
						'Guin?? Equatorial',
						'Macau',
						'Cabo Verde',
						'S??o Tom?? e Pr??ncipe',
					],
				'ru'=>
					[
						'R??ssia',
						'Belarus',
						'Cazaquist??o',
						'Quirguist??o',
						'Ucr??nia',
						'Let??nia',
						'Moldova',
						'Est??nia',
						'Georgia',
						'Arm??nia',
						'Litu??nia',
						'Uzbequist??o',
					],
				'tr'=>
					[
						'Peru',
						'Chipre',
						'Azerbaij??o',
						'B??snia',
						'Bulg??ria',
						'Gr??cia',
						'Iraque',
						'Kosovo',
						'Maced??nia',
						'Chipre do Norte',
						'Rom??nia',
					],
				'zh'=>
					[
						'China',
						'Taiwan',
						'Cingapura',
					],
			];
		}
		
					// Russian (RU) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_ru()
		{
			return [
				'de'=>
					[
						'????????????????',
						'??????????????',
						'??????????????????',
						'??????????????????????',
					],
				'en'=>
					[
						'?????????????????????? ?????????? ??????????????',
						'??????????',
						'????????????????',
						'????????????????',
						'??????????????',
						'????????????',
						'??????????????????',
						'????????????',
						'????????',
						'????????????',
						'??????????????',
						'?????????? ????????????????',
						'????????????',
					],
				'es'=>
					[
						'??????????????',
						'????????',
						'??????????????',
						'????????????????',
						'??????????????????',
						'????????',
						'??????????????????',
						'????????',
						'??????????????',
						'??????????????????',
						'??????????????',
						'?????????????????????????? ????????????????????',
						'????????????????',
						'????????????????',
						'??????????????????',
						'??????????????????',
						'??????????-????????',
						'????????????-????????',
						'????????????',
						'??????????????',
						'????????????',
						'???????????????????????????? ????????????',
						'??????????????',
						'??????????',
					],
				'fr'=>
					[
						'??????????????',
						'????????????',
						'????????????????????',
						'??????????????',
						'??????-??\'??????????',
						'??????????????-????????',
						'??????????',
						'??????????????',
						'????????',
						'????????????',
						'??????????????',
						'??????????',
						'??????',
						'????????????',
						'??????????????',
						'??????????',
						'??????????????????',
						'????????',
						'????????????????????-?????????????????????? ????????????????????',
						'???????????????????? ??????????',
						'??????????',
						'?????????????????? ??????????????',
						'???????????????????????????? ????????????',
						'?????????????????????? ??????????????????',
						'?????????????????????? ????????????',
						'?????????? ??????????????????',
						'??\'??????????',
						'????????????',
						'?????????????????? ??????????????',
						'????????????',
						'??????-????????????',
						'???????????? ?? ????????????',
						'??????-??????????????????',
					],
				'ja'=>
					[
						'????????????',
					],
				'it'=>
					[
						'????????????',
						'????????????',
						'?????? - ????????????',
						'??????????????????',
						'????????????????',
						'????????????????',
					],
				'nl'=>
					[
						'????????????????????',
						'??????????????',
						'??????????????',
						'?????????? ????????????',
						'??????????',
						'??????????????',
						'???????????????????? ??????????????',
						'??????????????',
						'????????',
						'????????-????????????????',
						'????????-??????????????',
					],
				'pl'=>
					[
						'????????????',
					],
				'pt'=>
					[
						'????????????????????',
						'????????????????',
						'????????????????',
						'????????????',
						'????????????-??????????',
						'?????????????????? ??????????',
						'???????????????????????????? ????????????',
						'??????????',
						'????????-??????????',
						'??????-???????? ?? ????????????????',
					],
				'ru'=>
					[
						'????????????',
						'????????????????',
						'??????????????????',
						'????????????????',
						'??????????????',
						'????????????',
						'??????????????',
						'??????????????',
						'????????????',
						'??????????????',
						'??????????',
						'????????????????????',
					],
				'tr'=>
					[
						'????????????',
						'????????',
						'??????????????????????',
						'????????????',
						'????????????????',
						'????????????',
						'????????',
						'????????????',
						'??????????????????',
						'???????????????? ????????',
						'??????????????',
					],
				'zh'=>
					[
						'??????????',
						'??????????????',
						'????????????????',
					],
			];
		}
		
					// Turkish (TR) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_tr()
		{
			return [
				'de'=>
					[
						'Almanya',
						'Avusturya',
						'??svi??re',
						'Liechtenstein',
					],
				'en'=>
					[
						'Amerika Birle??ik Devletleri',
						'Hindistan',
						'??rlanda',
						'Pakistan',
						'Nijerya',
						'??ngiltere',
						'Avustralya',
						'Kanada',
						'Gana',
						'Malta',
						'??srail',
						'Yeni Zelanda',
						'Jamaika',
					],
				'es'=>
					[
						'Ispanya',
						'K??ba',
						'Meksika',
						'Kolombiya',
						'Arjantin',
						'Peru',
						'Venezuela',
						'??ili',
						'Ekvador',
						'Guatemala',
						'Bolivya',
						'Dominik Cumhuriyeti',
						'Honduras',
						'Paraguay',
						'El Salvador',
						'Nikaragua',
						'Kostarika',
						'Porto Riko',
						'Panama',
						'Uruguay',
						'Guyana',
						'Ekvator Ginesi',
						'Andorra',
						'Belize',
					],
				'fr'=>
					[
						'Fransa',
						'Kanada',
						'Madagaskar',
						'Kamerun',
						'Fildi??i Sahili',
						'Burkina Faso',
						'Nijer',
						'Senegal',
						'Mali',
						'Ruanda',
						'Bel??ika',
						'Haiti',
						'??ad',
						'Gine',
						'Burundi',
						'Benin',
						'??svi??re',
						'Gitmek',
						'Orta Afrika Cumhuriyeti',
						'Kongo Cumhuriyeti',
						'Gabon',
						'Komorlar',
						'Ekvator Ginesi',
						'Frans??z Polinezyas??',
						'Frans??z Guyanas??',
						'Yeni Kaledonya',
						'Aosta Vadisi',
						'Jersey',
						'Bir T??r Inek',
						'Monako',
						'Saint-Martin',
						'Wallis ve Futuna',
						'Saint-Barthelemy',
					],
				'ja'=>
					[
						'Japonya',
					],
				'it'=>
					[
						'??talya',
						'Malta',
						'San Marino',
						'??svi??re',
						'H??rvatistan',
						'Slovenya',
					],
				'nl'=>
					[
						'Hollanda',
						'Bel??ika',
						'Surinam',
						'G??ney Afrika',
						'Aruba',
						'Curacao',
						'Namibya Cumhuriyeti',
						'Bonaire',
						'Saba',
						'Sint Eustatius',
						'Sint Maarten',
					],
				'pl'=>
					[
						'Polonya',
					],
				'pt'=>
					[
						'Portekiz',
						'Brezilya',
						'Mozambik',
						'Angora',
						'Gine-Bissau',
						'Do??u Timor',
						'Ekvator Ginesi',
						'Makao',
						'Cape Verde',
						'Sao Tome ve Principe',
					],
				'ru'=>
					[
						'Rusya',
						'Belarus',
						'Kazakistan',
						'K??rg??zistan',
						'Ukrayna',
						'Letonya',
						'Moldova',
						'Estonya',
						'G??rcistan',
						'Ermenistan',
						'Litvanya',
						'??zbekistan',
					],
				'tr'=>
					[
						'T??rkiye',
						'K??br??s',
						'Azerbeycan',
						'Bosna',
						'Bulgaristan',
						'Yunanistan',
						'Irak',
						'Kosova',
						'Makedonya',
						'Kuzey K??br??s',
						'Romanya',
					],
				'zh'=>
					[
						'??in',
						'Tayvan',
						'Singapur',
					],
			];
		}
		
					// Chinese (ZH) Accessor
					// ------------------------------------------------------------------------
		
		public function GetTranslatedCountryNames_zh()
		{

			return [
				'de'=>
					[
						'??????',
						'?????????',
						'??????',
						'???????????????',
					],
				'en'=>
					[
						'??????',
						'??????',
						'?????????',
						'????????????',
						'????????????',
						'?????????',
						'????????????',
						'?????????',
						'??????',
						'?????????',
						'?????????',
						'?????????',
						'?????????',
					],
				'es'=>
					[
						'?????????',
						'??????',
						'?????????',
						'????????????',
						'?????????',
						'??????',
						'????????????',
						'??????',
						'????????????',
						'????????????',
						'????????????',
						'?????????????????????',
						'????????????',
						'?????????',
						'????????????',
						'????????????',
						'???????????????',
						'????????????',
						'?????????',
						'?????????',
						'?????????',
						'???????????????',
						'?????????',
						'?????????',
					],
				'fr'=>
					[
						'??????',
						'?????????',
						'???????????????',
						'?????????',
						'????????????',
						'???????????????',
						'?????????',
						'????????????',
						'??????',
						'?????????',
						'?????????',
						'??????',
						'??????',
						'?????????',
						'?????????',
						'??????',
						'??????',
						'??????',
						'???????????????',
						'?????????????????????',
						'??????',
						'?????????',
						'???????????????',
						'?????????????????????',
						'???????????????',
						'??????????????????',
						'???????????????',
						'?????????',
						'?????????',
						'?????????',
						'?????????',
						'???????????????????????????',
						'???????????????',
					],
				'ja'=>
					[
						'??????',
					],
				'it'=>
					[
						'?????????',
						'?????????',
						'????????????',
						'??????',
						'????????????',
						'???????????????',
					],
				'nl'=>
					[
						'??????',
						'?????????',
						'?????????',
						'??????',
						'?????????',
						'?????????',
						'?????????????????????',
						'?????????',
						'??????',
						'??????????????????',
						'????????????',
					],
				'pl'=>
					[
						'??????',
					],
				'pt'=>
					[
						'?????????',
						'??????',
						'????????????',
						'?????????',
						'???????????????',
						'?????????',
						'???????????????',
						'??????',
						'?????????',
						'????????????????????????',
					],
				'ru'=>
					[
						'??????',
						'????????????',
						'???????????????',
						'??????????????????',
						'?????????',
						'????????????',
						'????????????',
						'????????????',
						'????????????',
						'????????????',
						'?????????',
						'??????????????????',
					],
				'tr'=>
					[
						'??????',
						'????????????',
						'????????????',
						'????????????',
						'????????????',
						'??????',
						'?????????',
						'?????????',
						'?????????',
						'???????????????',
						'????????????',
					],
				'zh'=>
					[
						'??????',
						'??????',
						'?????????',
					],
			];
		}
	}
	
?>