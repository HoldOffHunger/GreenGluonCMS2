<?php

	ggreq('traits/scripts/DBFunctions.php');
	ggreq('traits/scripts/SimpleErrors.php');
	ggreq('traits/scripts/SimpleForms.php');
	ggreq('traits/scripts/SimpleLookupLists.php');
	ggreq('traits/scripts/SimpleORM.php');
	
	class robots extends basicscript {
						// Traits
						// ---------------------------------------------
		
		use DBFunctions;
		use SimpleErrors;
		use SimpleForms;
		use SimpleLookupLists;
		use SimpleORM;
		
						// Security Data
						// ---------------------------------------------
		
		public function IsSecure() {
			return FALSE;
		}
		
		public function RequiresLogin() {
			return FALSE;
		}
		
						// Functionality
						// ---------------------------------------------
		
		public function display() {
			$this->SetORM();
			$this->SetRecordTree();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			$this->SetMasterRecord();
			$this->SetAllDomains();
			
			$this->SetRobotsTXTAttributes();
			
			if($this->script_format_lower !== 'xml') {
				$this->SetRobotsTXTFile();
				$this->SetRobotsForHTML();
			} else {
				$this->SetRobotsForXML();
				
				$this->humanreadable = $this->Param('humanreadable');
			}
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
		public function SetRobotsForXML() {
			$domains = [];
			
			$all_domains = $this->all_domains;
			
			foreach($all_domains as $domain) {
				$domains[] = [
					'domain'=>$domain
				];
			}
			
			$this->robots = [
				[
					'domains'=>$domains,
				],
				$this->robots_attributes,
			];
		}
		
		public function SetRobotsForHTML() {
			$robots = [];
			
			$all_domains = $this->all_domains;
			
			$robots[] = ['Domain :', implode(', ', $all_domains)];
			
			$robots_attributes = $this->robots_attributes;
			
			foreach($robots_attributes as $robots_attribute_name => $robots_attribute_value) {
				$robots[] = ['<nobr>' . $robots_attribute_name . ' :</nobr>', $robots_attribute_value];
			}
			
			return $this->robots = $robots;
		}
		
		public function SetAllDomains() {
			$all_domains = [
				$this->handler->domain->primary_domain_lowercased,
			];
			
			$alternate_domains = $this->primary_host_record['AlternateDomain'];
			
			if($alternate_domains) {
				if(is_array($alternate_domains)) {
					sort($alternate_domains);
					foreach($alternate_domains as $alternate_domain) {
						$all_domains[] = $alternate_domain;
					}
				} else {
					$all_domains[] = $alternate_domains;
				}
			}
			
			$this->all_domains = $all_domains;
		}
		
		public function SetRobotsTXTFile() {
			$displayable_domains = [];
			
			foreach($this->all_domains as $domain) {
				$displayable_domains[] = "#   * " . $domain . "\n";
			}
			
			$printable_domains = implode('', $displayable_domains);
			
			$first_domain = $this->handler->domain->primary_domain_lowercased;
			$sitemap_html = 'http://www.' . $first_domain . '/sitemap.php';
			$sitemap_humanreadable = 'http://www.' . $first_domain . '/sitemap.xml?humanreadable=1';
			$robots_html = 'http://www.' . $first_domain . '/robots.php';
			$robots_xml = 'http://www.' . $first_domain . '/robots.xml';
			$robots_xml_humanreadable = 'http://www.' . $first_domain . '/robots.xml?humanreadable=1';
			
			$robots_attributes = $this->robots_attributes;
			$robots_attributes_to_display = [];
			
			foreach($robots_attributes as $robots_attribute_key => $robots_attribute_value) {
				$robots_attribute_to_display = '';
				
				if($robots_attribute_key !== 'Disallow') {
					$robots_attribute_to_display = $robots_attribute_key .  ':';
				}
				
				if($robots_attribute_value) {
					$robots_attribute_to_display .= ' ' . $robots_attribute_value;
				}
				
				$robots_attributes_to_display[] = $robots_attribute_to_display;
			}
			
			$robots_attributes_to_display_imploded = implode("\n", $robots_attributes_to_display);
			
			$comment_line = '######################################################################';
			$copyright_policy_language = '';
			
			if($this->handler->language->getLanguageCode() === 'en') {
					// Welcome to the Hello123 File for :
				
				$welcome_opening_line = 'Welcome to the';
				$welcome_closing_line = 'Robots.txt File for :';
				$copyright_policy = 'Copyright Policy';
				$please_crawl_line = 'Please crawl the website.';
				
					// Code supporting the content is provided by Hello123.
				
				$code_supporting_the_content_line = 'Code supporting the content is provided by Punkerslut.';
				
					// Please crawl using the sitemap below.  You may also find these sitemap links useful, if you want a human-readable sitemap, available here :
				$crawl_alternate_links_first_line = 'Please crawl using the sitemap below.  You may also';
				$crawl_alternate_links_second_line = 'find these sitemap links useful, if you want a human-readable';
				$crawl_alternate_links_third_line = 'sitemap, available here :';
					
					// If you would like a computer readable version of the Hello123 file, then try here :
				
				$computer_readable_opening_line = 'If you would like a computer readable version of the robots.txt';
				$computer_readable_closing_line = 'file, then try here :';
					
					// If you would like something more human-readable, try here :
					
				$human_readable_line = 'If you would like something more human-readable, try here :';
			} else {
				switch($this->handler->language->getLanguageCode()) {
					case 'de':	# German
						$welcome_opening_line = 'Willkommen in der';
						$welcome_closing_line = 'Robots.txt Datei F??r :';
						$copyright_policy = 'Copyright-Richtlinien';
						$please_crawl_line = 'Bitte kriechen die Website.';
						$code_supporting_the_content_line = '-Code den Inhalt tr??gt, wird von Punkerslut vorgesehen.';
						$crawl_alternate_links_first_line = 'Bitte kriechen die Sitemap unten.  Sie k??nnen auch';
						$crawl_alternate_links_second_line = 'diese Sitemap Links n??tzlich finden, wenn Sie einen Menschen lesbaren';
						$crawl_alternate_links_third_line = 'Sitemap m??chten, finden Sie hier :';
						$computer_readable_opening_line = 'Wenn Sie einen Computer lesbare Version der robots.txt';
						$computer_readable_closing_line = 'Datei m??chten, dann versuchen Sie hier :';
						$human_readable_line = 'Wenn Sie etwas mehr Menschen lesbare m??chten, klicken Sie bitte hier :';
						$copyright_policy_language = 'Englisch';
						
						break;
					
					case 'es':	# Spanish
						$welcome_opening_line = 'Bienvenido al archivo';
						$welcome_closing_line = 'Robots.txt para :';
						$copyright_policy = 'pol??tica de derechos de autor';
						$please_crawl_line = 'Por favor rastrear el sitio web.';
						$code_supporting_the_content_line = 'C??digo apoyar el contenido es proporcionado por Punkerslut.';
						$crawl_alternate_links_first_line = 'Por favor rastrear usando el mapa a continuaci??n. Tambi??n puede encontrar';
						$crawl_alternate_links_second_line = 'estos enlaces mapa ??til, si quieres un mapa legible por';
						$crawl_alternate_links_third_line = 'humanos, disponible aqu?? :';
						$computer_readable_opening_line = 'Si desea una versi??n legible por ordenador del archivo Robots.txt,';
						$computer_readable_closing_line = 'a continuaci??n, tratar aqu?? :';
						$human_readable_line = 'Si desea algo m??s legible, probar aqu?? :';
						$copyright_policy_language = 'Ingl??s';
						
						break;
						
					case 'fr':	# French
						$welcome_opening_line = 'Bienvenue sur le fichier';
						$welcome_closing_line = 'Robots.txt pour :';
						$copyright_policy = 'Politique du droit d\'auteur';
						$please_crawl_line = 'S\'il vous pla??t explorer le site.';
						$code_supporting_the_content_line = 'Code de soutenir le contenu est fourni par Punkerslut.';
						$crawl_alternate_links_first_line = 'S\'il vous pla??t ramper en utilisant le plan du site ci-dessous. Vous pouvez ??galement';
						$crawl_alternate_links_second_line = 'trouver ces liens sitemap utile, si vous voulez un plan du site, disponible lisible';
						$crawl_alternate_links_third_line = 'par l\'homme ici :';
						$computer_readable_opening_line = 'Si vous voulez un ordinateur version lisible du fichier robots.txt,';
						$computer_readable_closing_line = 'puis essayez ici :';
						$human_readable_line = 'Si vous voulez quelque chose de plus lisible par l\'homme, essayez ici :';
						$copyright_policy_language = 'Anglais';
						
						break;
						
					case 'ja':	# Japanese
						$welcome_opening_line = '??????????????????';
						$welcome_closing_line = 'robots.txt??????????????????????????? ???';
						$copyright_policy = '?????????????????????';
						$please_crawl_line = '??????????????????????????????????????????????????????';
						$code_supporting_the_content_line = '????????????????????????????????????????????????Punkerslut???????????????????????????????????????';
						$crawl_alternate_links_first_line = '???????????????????????????????????????????????????????????????????????????????????????????????????';
						$crawl_alternate_links_second_line = '???????????????????????????????????????????????????????????????????????????????????????????????????';
						$crawl_alternate_links_third_line = '??????????????????????????????????????????????????? :';
						$computer_readable_opening_line = '???????????????robots.txt???????????????????????????????????????????????????????????????';
						$computer_readable_closing_line = '???????????????????????????????????????????????????????????????????????? :';
						$human_readable_line = '????????????????????????????????????????????????????????????????????????????????????????????????????????? :';
						$copyright_policy_language = '??????';
						
						break;
						
					case 'it':	# Italian
						$welcome_opening_line = 'Benvenuti al file';
						$welcome_closing_line = 'Robots.txt per :';
						$copyright_policy = 'Informativa sul copyright';
						$please_crawl_line = 'Si prega di eseguire la scansione del sito.';
						$code_supporting_the_content_line = 'Codice sostenere il contenuto ?? fornito da Punkerslut.';
						$crawl_alternate_links_first_line = 'Si prega di eseguire la scansione utilizzando la mappa del sito in basso. ?? inoltre possibile';
						$crawl_alternate_links_second_line = 'trovare questi collegamenti sitemap utile, se si desidera una mappa del sito leggibile,';
						$crawl_alternate_links_third_line = 'disponibile qui :';
						$computer_readable_opening_line = 'Se si desidera un computer la versione leggibile del file Robots.txt,';
						$computer_readable_closing_line = 'quindi provare qui :';
						$human_readable_line = 'Se volete qualcosa di pi?? leggibile, provare qui :';
						$copyright_policy_language = 'Inglese';
						
						break;
						
					case 'nl':	# Dutch
						$welcome_opening_line = 'Welkom op de';
						$welcome_closing_line = 'Robots.txt File voor :';
						$copyright_policy = 'beleid van het auteursrecht';
						$please_crawl_line = 'Gelieve kruipen de website.';
						$code_supporting_the_content_line = 'Code ondersteunen van de inhoud door Punkerslut.';
						$crawl_alternate_links_first_line = 'Gelieve te kruipen via het onderstaande sitemap. U kunt ook';
						$crawl_alternate_links_second_line = 'vinden deze sitemap koppelingen handig, als je een leesbare';
						$crawl_alternate_links_third_line = 'sitemap hier beschikbaar willen :';
						$computer_readable_opening_line = 'Als u graag een computer leesbare versie van het bestand ';
						$computer_readable_closing_line = 'Robots.txt, probeer dan hier :';
						$human_readable_line = 'Als u graag iets meer leesbare, probeer hier :';
						$copyright_policy_language = 'Engels';
						
						break;
						
					case 'pl':	# Polish
						$welcome_opening_line = 'Zapraszamy do pliku';
						$welcome_closing_line = 'Robots.txt dla :';
						$copyright_policy = 'Zasady ochrony praw autorskich';
						$please_crawl_line = 'Prosz?? indeksowa?? strony internetowej.';
						$code_supporting_the_content_line = 'Kod wspieranie tre???? jest dostarczana przez Punkerslut.';
						$crawl_alternate_links_first_line = 'Prosz?? indeksowa?? przy u??yciu mapa poni??ej. Mo??na r??wnie?? znale????';
						$crawl_alternate_links_second_line = 'te linki mapa serwisu przydatna, je??li chcesz mapa, dost??pne';
						$crawl_alternate_links_third_line = 'tutaj postaci czytelnej dla cz??owieka :';
						$computer_readable_opening_line = 'Je??li chcieliby Pa??stwo otrzyma?? czytelny dla komputera, wersj??';
						$computer_readable_closing_line = 'pliku Robots.txt, a nast??pnie spr??buj tutaj :';
						$human_readable_line = 'Je??li chcesz co?? bardziej czytelny dla cz??owieka, spr??buj tutaj :';
						$copyright_policy_language = 'Angielski';
						
						break;
						
					case 'pt':	# Portuguese
						$welcome_opening_line = 'Bem-vindo ao arquivo';
						$welcome_closing_line = 'Hello123 para :';
						$copyright_policy = 'pol??tica de direitos autorais';
						$please_crawl_line = 'Por favor rastrear o site.';
						$code_supporting_the_content_line = 'C??digo apoiar o conte??do ?? fornecido por Punkerslut.';
						$crawl_alternate_links_first_line = 'Por favor, rastejar usando o mapa do site abaixo. Voc?? tamb??m pode';
						$crawl_alternate_links_second_line = ' encontrar esses links mapa do site ??til, se voc?? quer um mapa do site';
						$crawl_alternate_links_third_line = 'leg??vel, dispon??vel aqui :';
						$computer_readable_opening_line = 'Se voc?? gostaria de uma vers??o de computador leg??vel do arquivo Robots.txt,';
						$computer_readable_closing_line = 'e depois tentar aqui :';
						$human_readable_line = 'Se voc?? gostaria de algo mais leg??vel, tente aqui :';
						$copyright_policy_language = 'Ingl??s';
						
						break;
					
					case 'ru':	# Russian
						$welcome_opening_line = '?????????? ???????????????????? ?? ????????';
						$welcome_closing_line = 'Robots.txt ?????? :';
						$copyright_policy = '???????????????? ???????????? ?????????????????? ????????';
						$please_crawl_line = '????????????????????, ?????????????????? ??????-????????.';
						$code_supporting_the_content_line = '?????? ?????????????????? ???????????????? ???????????????????????????? Punkerslut.';
						$crawl_alternate_links_first_line = '????????????????????, ?????????????? ?? ?????????????? ?????????? ?????????? ????????. ???? ?????????? ????????????';
						$crawl_alternate_links_second_line = '?????????? ?????? ???????????? ?????????? ?????????? ??????????????, ???????? ???? ????????????, ???????????????? ??????????';
						$crawl_alternate_links_third_line = '??????????, ?????????? ???????????????????? ?????????? :';
						$computer_readable_opening_line = '???????? ???? ???????????? ???????????????????????????? ???????????? ?????????? Robots.txt,';
						$computer_readable_closing_line = '???? ???????????????????? ?????????? :';
						$human_readable_line = '???????? ???? ???????????? ??????-???? ?????????? ?????????????? ?????? ???????????????????? ??????????????????, ???????????????????? ?????????? :';
						$copyright_policy_language = '????????????????????';
						
						break;
						
					case 'tr':	# Turkish
						$welcome_opening_line = 'i??in Robots.txt Dosya';
						$welcome_closing_line = 'ho?? geldiniz :';
						$copyright_policy = 'Telif Hakk?? Politikas??';
						$please_crawl_line = 'web tarama ediniz.';
						$code_supporting_the_content_line = 'i??eri??i destekleyen kod Punkerslut taraf??ndan sa??lan??r.';
						$crawl_alternate_links_first_line = 'A??a????daki site haritas?? kullanarak tarama ediniz. Burada, mevcut';
						$crawl_alternate_links_second_line = 'okunabilir site haritas?? istiyorsan??z da, kullan????l?? bu site haritas??';
						$crawl_alternate_links_third_line = 'ba??lant??lar?? bulabilirsiniz :';
						$computer_readable_opening_line = 'E??er robots.txt dosyas??n??n bir bilgisayar taraf??ndan okunabilen s??r??m??n??';
						$computer_readable_closing_line = 'isterseniz, o zaman burada deneyin :';
						$human_readable_line = 'E??er bir ??ey daha insan taraf??ndan okunabilir istiyorsan??z, burada deneyin :';
						$copyright_policy_language = '??ngilizce';
						
						break;
						
					case 'zh':	# Chinese
						$welcome_opening_line = '????????????';
						$welcome_closing_line = 'Robots.txt?????? ???';
						$copyright_policy = '????????????';
						$please_crawl_line = '??????????????????';
						$code_supporting_the_content_line = '?????????????????????Punkerslut?????????';
						$crawl_alternate_links_first_line = '?????????????????????????????????????????????????????????????????????????????????????????????';
						$crawl_alternate_links_second_line = '????????????????????????????????????????????????????????? ???';
						$crawl_alternate_links_third_line = '';
						$computer_readable_opening_line = '???????????????Robots.txt??????????????????????????????????????????????????? ???';
						$computer_readable_closing_line = '';
						$human_readable_line = '????????????????????????????????????????????? ???';
						$copyright_policy_language = '??????';
						
						break;
						
					case 'en':	# English
					default:
						$welcome_opening_line = 'Welcome to the';
						$welcome_closing_line = 'Robots.txt File for :';
						$copyright_policy = 'Copyright Policy';
						$please_crawl_line = 'Please crawl the website.';
						$code_supporting_the_content_line = 'Code supporting the content is provided by Punkerslut.';
						$crawl_alternate_links_first_line = 'Please crawl using the sitemap below.  You may also';
						$crawl_alternate_links_second_line = 'find these sitemap links useful, if you want a human-readable';
						$crawl_alternate_links_third_line = 'sitemap, available here :';
						$computer_readable_opening_line = 'If you would like a computer readable version of the robots.txt';
						$computer_readable_closing_line = 'file, then try here :';
						$human_readable_line = 'If you would like something more human-readable, try here :';
						$copyright_policy_language = '';
						
						break;
				}
			}
			
			if($copyright_policy_language) {
				$copyright_policy_language = ' (' . $copyright_policy_language . ')';
			}
			
			if($this->primary_host_record['Copyright']) {
				$displayable_copyright =
					"#    " . $copyright_policy . $copyright_policy_language . " :\n" .
					"#    " . wordwrap($this->primary_host_record['Copyright'], 60, "\n#    ") .  ".\n" .
					"#\n";
			}
			
			$welcome_text_block = '';
			
			if($welcome_opening_line) {
				$welcome_text_block .=
					"#          " . $welcome_opening_line . "\n" ;
			}
			
			if($welcome_closing_line) {
				$welcome_text_block .=
					"#       " . $welcome_closing_line . "\n" ;
			}
			
			$crawl_alternate_links_block = '';
			
			if($crawl_alternate_links_first_line) {
				$crawl_alternate_links_block .=
					"#    " . $crawl_alternate_links_first_line . "\n" ;
			}
			
			if($crawl_alternate_links_second_line) {
				$crawl_alternate_links_block .=
					"#    " . $crawl_alternate_links_second_line . "\n" ;
			}
			
			if($crawl_alternate_links_third_line) {
				$crawl_alternate_links_block .=
					"#    " . $crawl_alternate_links_third_line . "\n" ;
			}
			
			$computer_readable_block = '';
			
			if($computer_readable_opening_line) {
				$computer_readable_block .=
					"#    " . $computer_readable_opening_line . "\n" ;
			}
			
			if($computer_readable_closing_line) {
				$computer_readable_block .=
					"#    " . $computer_readable_closing_line . "\n" ;
			}
			
			return $this->robots_txt_file =
				$comment_line . "\n" .
				"#\n" .
				$welcome_text_block .
				"#\n" .
				$printable_domains .
				"#\n" .
				$comment_line . "\n" .
				"#\n" .
				"#    " . $please_crawl_line . "\n" .
				"#\n" .
				"#    " . $code_supporting_the_content_line . "\n" .
				"#\n" .
				$displayable_copyright .
				$comment_line . "\n" .
				"#\n" .
				$crawl_alternate_links_block .
				"#\n" .
				"#   * " . $sitemap_html . "\n" .
				"#   * " . $sitemap_humanreadable . "\n" .
				"#\n" .
				$comment_line . "\n" .
				"#\n" .
				$computer_readable_block .
				"#\n" .
				"#   * " . $robots_xml . "\n" .
				"#\n" .
				"#    " . $human_readable_line . "\n" .
				"#\n" .
				"#   * " . $robots_html . "\n" .
				"#   * " . $robots_xml_humanreadable . "\n" .
				"#\n" .
				$comment_line . "\n\n" .
				
				$robots_attributes_to_display_imploded .
			"";
		}
		
		public function SetRobotsTXTAttributes() {
			$first_domain = $this->handler->domain->primary_domain_lowercased;
			
			$sitemap = 'http://www.' . $first_domain . '/sitemap.xml';
			
			return $this->robots_attributes = [
				'Crawl-delay'=>'1',
				'User-agent'=>'*',
				'Disallow'=>$this->getDisallowed(),
				'Sitemap'=>$sitemap,
			];
		}
		
		public function getDisallowed() {
			$disallowed = $this->getDisallowedList();
			$new_disallowed = [];
			
			foreach($disallowed as $disallow_item) {
				$new_disallowed[] = 'Disallow: ' . $disallow_item;
			}
			
			return "\n" . implode("\n", $new_disallowed) . "\n";
		}
		
		public function getDisallowedList() {
			return [
				'/image/events/',
				'/image/flags/',
				'/image/formats/',
				'/image/master-c/',
				'/image/media-controls/',
				'/image/privacy/',
				'/image/social-media/',
				'/image/social-media-logo-icons-opaque-background/',
				'/image/terms/',
				
				'/image/thumbs-up-right.jpg',		# TODO: improve this, move images to own dir
				'/image/thumbs-down-right.jpg',
				
				'/*.pdf$',
				'/*.tex$',
				'/*.txt$',
				'/*.txt?wrapped=1$',
				'/*.rtf$',
				'/*.epub$',
				'/*.daisy$',
				'/*.sgml$',
				'/*.json$',
				'/*.xml$',
				'/*.csv$',
				'/*.latex$',
				'/*.opds$',
				'/*.rdf$',
			];
		}
		
		public function GetHTMLFormatData_Title() {
			if(!$this->parent['id'] && $this->master_record && $this->master_record['id']) {
				if($this->handler->language->getLanguageCode() === 'en') {
					$header_title_text = 'Robots File For ' . $this->master_record['Title'] . ' : ' . $this->master_record['Subtitle'];
				} else {
					$robots_header_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesRobotsHeader']);
					
					if($robots_header_language_translations[$this->handler->language->getLanguageCode()]) {
						$header_title_text = $robots_header_language_translations[$this->handler->language->getLanguageCode()];
					} else {
						$header_title_text = 'Robots File For ' . $this->master_record['Title'] . ' : ' . $this->master_record['Subtitle'];
					}
				}
				
				return $this->header_title_text = $header_title_text;
			}
			return FALSE;
		}
	}
	
?>