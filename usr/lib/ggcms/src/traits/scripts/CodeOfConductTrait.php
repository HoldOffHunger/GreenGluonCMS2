<?php

	trait CodeOfConductTrait {
		public function getCodeOfConduct() {
			$code_of_conduct_paragraphs = $this->getCodeOfConductParagraphs();
			
			$code_of_conduct = implode("\n\n", $code_of_conduct_paragraphs);
			
			return $code_of_conduct;
		}
		
		public function getCodeOfConductImages() {
			$images = [
							# image 1
				'preface'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/coffee-maker_701320?term=prepare&page=1&position=67',
					'original-source'=>'',
					'filename'=>'preface.jpg',
				],
							# image 2
				'introduction'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/reunion_1006080?term=introduction&page=1&position=1',
					'original-source'=>'',
					'filename'=>'introduction.jpg',
				],
							# image 3
				'table-of-contents'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/list_130291',
					'original-source'=>'https://www.freepik.com/free-icon/list_942266.htm',
					'filename'=>'table-of-contents.jpg',
				],
							# image 3
				'what-is-encouraged'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/leaves_113860',
					'original-source'=>'',
					'filename'=>'what-is-encouraged.jpg',
				],
							# image 4
				'what-is-unacceptable'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/disabled_2089699?term=prohibited&page=1&position=64',
					'original-source'=>'',
					'filename'=>'what-is-unacceptable.jpg',
				],
							# image 5
				'what-is-unaccountable'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/relationship_1189177?term=accountable&page=1&position=34',
					'original-source'=>'',
					'filename'=>'what-is-unaccountable.jpg',
				],
							# image 6
				'how-we-enforce-community-standards'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/balance_994414?term=law&page=1&position=1',
					'original-source'=>'',
					'filename'=>'how-we-enforce-community-standards.jpg',
				],
							# image 7
				'licensing'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/open-hanging-signal_44957',
					'original-source'=>'https://www.freepik.com/free-icon/open-hanging-signal_724429.htm',
					'filename'=>'code-of-conduct-license.jpg',
				],
							# image 8
				'inspiration'=>[
					'creator'=>'Freepik from Flaticon',
					'license'=>'Flaticon Basic License',
					'source'=>'https://www.flaticon.com/free-icon/lightbulb_2010820?term=inspire&page=1&position=5',
					'original-source'=>'',
					'filename'=>'inspiration.jpg',
				],
			];
			
			return $images;
		}
		
		
						// Code of Conduct Paragraphs
						// ---------------------------------------------
		
		public function getCodeOfConductParagraphs() {
			$header_index = 1;
			
			$images = $this->getCodeOfConductImages();
			
			$code_of_conduct_paragraphs = [];
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getPrefaceSection([
						'image'=>$images['preface'],
					])
				);
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getIntroductionSection([
						'image'=>$images['introduction'],
						'headerindex'=>$header_index++,
					])
				);
				
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getTableOfContentsSection([
						'image'=>$images['table-of-contents'],
						'headerindex'=>$header_index++,
					])
				);
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getWhatIsEncouragedSection([
						'image'=>$images['what-is-encouraged'],
						'headerindex'=>$header_index++,
					])
				);
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getWhatIsUnacceptableSection([
						'image'=>$images['what-is-unacceptable'],
						'headerindex'=>$header_index++,
					])
				);
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getWhatIsUnaccountableSection([
						'image'=>$images['what-is-unaccountable'],
						'headerindex'=>$header_index++,
					])
				);
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getHowWeEnforceCommunityStandardsSection([
						'image'=>$images['how-we-enforce-community-standards'],
						'headerindex'=>$header_index++,
					])
				);
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getLicensingSection([
						'image'=>$images['licensing'],
						'headerindex'=>$header_index++,
					])
				);
			
			$code_of_conduct_paragraphs =
				array_merge($code_of_conduct_paragraphs,
					$this->getInspirationSection([
						'image'=>$images['inspiration'],
						'headerindex'=>$header_index++,
					])
				);
			
			return $code_of_conduct_paragraphs;
		}

		
						// Individual Code of Conduct Sections
						// ---------------------------------------------
		
		public function getPrefaceSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$preface_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'Preface' .
						'</h3>';
					
					$preface_section[] =
						'<p>Version 1.0, May 22, 2018, by HoldOffHunger</p>';
					break;
					
				case 'de':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>Version 1.0, 22. Mai 2018, von HoldOffHunger</p>';
					break;
					
				case 'es':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>Versi??n 1.0, 22 de mayo de 2018, por HoldOffHunger</p>';
					break;
					
				case 'fr':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>Version 1.0, 22 mai 2018, par HoldOffHunger</p>';
					break;
					
				case 'ja':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>???????????????1.0???2018???5???22??????HoldOffHunger???</p>';
					break;
					
				case 'it':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>Versione 1.0, 22 maggio 2018, di HoldOffHunger</p>';
					break;
					
				case 'nl':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>Versie 1.0, 22 mei 2018, door HoldOffHunger</p>';
					break;
					
				case 'pl':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>Wersja 1.0, 22 maja 2018 roku, przez HoldOffHunger</p>';
					break;
					
				case 'pt':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>Vers??o 1.0, 22 de maio de 2018, por HoldOffHunger</p>';
					break;
					
				case 'ru':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>???????????? 1.0, 22 ?????? 2018 ??., ?????????? HoldOffHunger</p>';
					break;
					
				case 'tr':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>S??r??m 1.0, 22 May??s 2018, HoldOffHunger taraf??ndan</p>';
					break;
					
				case 'zh':
					$preface_section[] =
						'<h3><a name="preface"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						'</h3>';
					
					$preface_section[] =
						'<p>??????1.0??????2018???5???22?????????HoldOffHunger??????</p>';
					break;
			}
			
			return $preface_section;
		}
		
		public function getIntroductionSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$introduction_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introduction</h3>';
					
					$introduction_section[] =
						'<p>Everyone expects a standard of behavior. And everyone will react to the behavior of others.</p>' .
						'<p>This is always true -- whether or not people are working together under a common agreement.</p>' .
						'<p>But by having an agreement, at least discussion should be easier.</p>';
					break;
					
				case 'de':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Einf??hrung</h3>';
					
					$introduction_section[] =
						'<p>Jeder erwartet einen Verhaltensstandard. Und jeder wird auf das Verhalten anderer reagieren.</p>' .
						'<p>Dies trifft immer zu - unabh??ngig davon, ob Menschen im Rahmen einer gemeinsamen Vereinbarung zusammenarbeiten.</p>' .
						'<p>Aber durch eine Vereinbarung sollte zumindest die Diskussion einfacher sein.</p>';
					break;
					
				case 'es':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introducci??n</h3>';
					
					$introduction_section[] =
						'<p>Todo el mundo espera un est??ndar de comportamiento. Y todos reaccionar??n al comportamiento de los dem??s.</p>' .
						'<p>Esto siempre es cierto, ya sea que las personas trabajen juntas o no bajo un acuerdo com??n.</p>' .
						'<p>Pero al tener un acuerdo, al menos la discusi??n deber??a ser m??s f??cil.</p>';
					break;
					
				case 'fr':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introduction</h3>';
					
					$introduction_section[] =
						'<p>Tout le monde attend une norme de comportement. Et tout le monde r??agira au comportement des autres.</p>' .
						"<p>Cela est toujours vrai, que des personnes travaillent ensemble ou non dans le cadre d'un accord commun.</p>" .
						'<p>Mais en ayant un accord, au moins la discussion devrait ??tre plus facile.</p>';
					break;
					
				case 'ja':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ?????????</h3>';
					
					$introduction_section[] =
						'<p>??????????????????????????????????????????????????? ??????????????????????????????????????????????????????????????????</p>' .
						'<p>??????????????????????????? - ????????????????????????????????????????????????????????????????????????</p>' .
						'<p>??????????????????????????????????????????????????????????????????????????????????????????</p>';
					break;
					
				case 'it':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introduzione</h3>';
					
					$introduction_section[] =
						'<p>Tutti si aspettano uno standard di comportamento. E tutti reagiranno al comportamento degli altri.</p>' .
						'<p>Questo ?? sempre vero - indipendentemente dal fatto che le persone lavorino o meno in base a un accordo comune.</p>' .
						'<p>Ma avendo un accordo, almeno la discussione dovrebbe essere pi?? facile.</p>';
					break;
					
				case 'nl':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Invoering</h3>';
					
					$introduction_section[] =
						'<p>Iedereen verwacht een gedragsnorm. En iedereen zal reageren op het gedrag van anderen.</p>' .
						'<p>Dit is altijd waar - ongeacht of mensen samenwerken onder een gemeenschappelijk akkoord.</p>' .
						'<p>Maar door een overeenkomst te hebben, zou tenminste de discussie eenvoudiger moeten zijn.</p>';
					break;
					
				case 'pl':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Wprowadzenie</h3>';
					
					$introduction_section[] =
						'<p>Wszyscy oczekuj?? standardu zachowania. I ka??dy zareaguje na zachowanie innych.</p>' .
						'<p>Jest to zawsze prawdziwe - niezale??nie od tego, czy ludzie pracuj?? razem w ramach wsp??lnej umowy.</p>' .
						'<p>Ale dzi??ki porozumieniu przynajmniej dyskusja powinna by?? ??atwiejsza.</p>';
					break;
					
				case 'pt':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introdu????o</h3>';
					
					$introduction_section[] =
						'<p>Todos esperam um padr??o de comportamento. E todos v??o reagir ao comportamento dos outros.</p>' .
						'<p>Isso ?? sempre verdade - se as pessoas est??o ou n??o trabalhando juntas sob um acordo comum.</p>' .
						'<p>Mas, por ter um acordo, pelo menos a discuss??o deve ser mais f??cil.</p>';
					break;
					
				case 'ru':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ????????????????????</h3>';
					
					$introduction_section[] =
						'<p>???????????? ?????????????? ???????????????? ??????????????????. ?? ???????????? ?????????? ?????????????????????? ???? ?????????????????? ????????????.</p>' .
						'<p>?????? ???????????? ?????????? - ???????????????? ???? ???????? ???????????? ???? ???????????? ????????????????????.</p>' .
						'<p>???? ?????? ?????????????? ????????????????????, ???? ?????????????? ????????, ???????????????????? ???????????? ???????? ??????????.</p>';
					break;
					
				case 'tr':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Giri??</h3>';
					
					$introduction_section[] =
						'<p>Herkes standart bir davran???? bekler. Ve herkes ba??kalar??n??n davran????lar??na tepki verecektir.</p>' .
						'<p>Bu her zaman do??rudur - insanlar??n ortak bir anla??ma ??er??evesinde birlikte ??al??????p ??al????mad??klar??n??.</p>' .
						'<p>Ancak bir anla??ma yaparak, en az??ndan tart????ma daha kolay olmal??.</p>';
					break;
					
				case 'zh':
					$introduction_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ??????</h3>';
					
					$introduction_section[] =
						'<p>?????????????????????????????????????????? ????????????????????????????????????????????????</p>' .
						'<p>?????????????????? - ???????????????????????????????????????????????????</p>' .
						'<p>??????????????????????????????????????????????????????</p>';
					break;
			}
			
			return $introduction_section;
		}
		
		public function getTableOfContentsSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			$images = $this->getCodeOfConductImages();
			
			$toc_paragraphs = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Table of Contents</h3>',
						
						'<p>The Anarchist Code of Conduct is organized into the following sections :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Preface</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Introduction</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Table of Contents</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : What Is Encouraged</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : What Is Unacceptable</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : What Is Unaccountable</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : How We Enforce Community Standards</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Licensing of this Document</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inspiration for this Document</a></li>',
						'</ul>',
					];
					break;
					
				case 'de':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inhaltsverzeichnis</h3>',
						
						'<p>Der anarchistische Verhaltenskodex ist in folgende Abschnitte gegliedert :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Vorwort</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Einf??hrung</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inhaltsverzeichnis</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Was wird gef??rdert</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Was ist inakzeptabel</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Was ist nicht rechenschaftspflichtig</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Wie wir Gemeinschaftsstandards durchsetzen</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Lizenzierung dieses Dokuments</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inspiration f??r dieses Dokument</a></li>',
						'</ul>',
					];
					break;
					
				case 'es':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Tabla de contenido</h3>',
						
						'<p>TEl C??digo de Conducta Anarquista est?? organizado en las siguientes secciones :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Prefacio</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Introducci??n</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Tabla de contenido</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Lo que se anima</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Lo que es inaceptable</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Lo que es irresponsable</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : C??mo hacemos cumplir los est??ndares de la comunidad</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Licenciamiento de este documento</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inspiraci??n para este documento</a></li>',
						'</ul>',
					];
					break;
					
				case 'fr':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Table des mati??res</h3>',
						
						'<p>Le code de conduite anarchiste est organis?? dans les sections suivantes :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Pr??face</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Introduction</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Table des mati??res</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Ce qui est encourag??</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . " : Qu'est-ce qui est inacceptable</a></li>",
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Quel est inexplicable</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Comment nous appliquons les normes communautaires</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Licence de ce document</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inspiration pour ce document</a></li>',
						'</ul>',
					];
					break;
					
				case 'ja':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ??????</h3>',
						
						'<p>???????????????????????????????????????????????????????????????????????????????????????</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ?????????</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ?????????????????????</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ????????????????????????</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ????????????????????????</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ???????????????????????????????????????</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ???????????????????????????</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????????????????????????????????????????</a></li>',
						'</ul>',
					];
					break;
					
				case 'it':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Sommario</h3>',
						
						'<p>Il codice di condotta anarchico ?? organizzato nelle seguenti sezioni :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Prefazione</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Introduzione</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Sommario</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Cosa ?? incoraggiato</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Cosa ?? inaccettabile</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Cosa ?? irresponsabile</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Come applichiamo gli standard comunitari</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Licenza di questo documento</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Ispirazione per questo documento</a></li>',
						'</ul>',
					];
					break;
					
				case 'nl':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inhoudsopgave</h3>',
						
						'<p>De anarchistische gedragscode is georganiseerd in de volgende secties :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Voorwoord</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Invoering</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inhoudsopgave</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Wat wordt aangemoedigd</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Wat is onacceptabel</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Wat is onverklaarbaar</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Hoe wij de communautaire normen naleven</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Licenti??ring van dit document</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inspiratie voor dit document</a></li>',
						'</ul>',
					];
					break;
					
				case 'pl':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Spis tre??ci</h3>',
						
						'<p>Anarchistyczny kodeks post??powania jest podzielony na nast??puj??ce sekcje :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Przedmowa</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Wprowadzenie</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Spis tre??ci</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Co zach??ca</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Co jest niedopuszczalne</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Co jest nie do opisania</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Jak egzekwujemy standardy wsp??lnotowe</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Licencjonowanie tego dokumentu</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inspiracja do tego dokumentu</a></li>',
						'</ul>',
					];
					break;
					
				case 'pt':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ??ndice</h3>',
						
						'<p>O C??digo de Conduta Anarquista est?? organizado nas seguintes se????es :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Pref??cio</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Introdu????o</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??ndice</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : O que ?? encorajado?</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : O que ?? inaceit??vel</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : O que ?? inexplic??vel</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Como n??s refor??amos os padr??es da comunidade</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Licenciamento deste documento</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Inspira????o para este documento</a></li>',
						'</ul>',
					];
					break;
					
				case 'ru':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ????????????????????</h3>',
						
						'<p>???????????????????????? ???????????? ?????????????????? ?????????????? ???? ?????????????????? ???????????????? :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????????????????????</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ????????????????????</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ????????????????????</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ?????? ????????????????????</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ?????? ??????????????????????</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ?????? ???? ???????????????? ??????????????????????????????</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ?????? ???? ?????????????????? ?????????????????? ????????????????????</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ???????????????????????????? ?????????? ??????????????????</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ?????????????????????? ?????? ?????????? ??????????????????</a></li>',
						'</ul>',
					];
					break;
					
				case 'tr':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ????indekiler</h3>',
						
						'<p>Anar??ist Davran???? Kurallar?? a??a????daki b??l??mlerde d??zenlenmi??tir :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??ns??z</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Giri??</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ????indekiler</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Te??vik Nedir</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Kabul Edilemez Nedir</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Hesaplanamayan Nedir</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Topluluk Standartlar??n?? Nas??l Zorlar??z</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Bu Belgenin Lisanslanmas??</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : Bu Belge i??in ??lham</a></li>',
						'</ul>',
					];
					break;
					
				case 'zh':
					$toc_paragraphs = [
						'<h3><a name="table-of-contents"></a>' .
						$this->getImageMarkup(['image'=>$images['table-of-contents']]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ??????</h3>',
						
						'<p>??????????????????????????????????????????????????? :</p>' ,
						
						'<ul>',
							'<li><a href="#preface">' .
								$this->getImageIconMarkup(['image'=>$images['preface']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????</a></li>',
							'<li><a href="#introduction">' .
								$this->getImageIconMarkup(['image'=>$images['introduction']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????</a></li>',
							'<li><a href="#table-of-contents">' .
								$this->getImageIconMarkup(['image'=>$images['table-of-contents']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????</a></li>',
							'<li><a href="#what-is-encouraged">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-encouraged']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ???????????????</a></li>',
							'<li><a href="#what-is-unacceptable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unacceptable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ????????????????????????</a></li>',
							'<li><a href="#what-is-unaccountable">' .
								$this->getImageIconMarkup(['image'=>$images['what-is-unaccountable']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ????????????????????????</a></li>',
							'<li><a href="#how-we-enforce-community-standards">' .
								$this->getImageIconMarkup(['image'=>$images['how-we-enforce-community-standards']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????????????????????????????</a></li>',
							'<li><a href="#licensing">' .
								$this->getImageIconMarkup(['image'=>$images['licensing']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????????????????</a></li>',
							'<li><a href="#inspiration">' .
								$this->getImageIconMarkup(['image'=>$images['inspiration']]) .
								$this->getSectionText(['headerindex'=>$toc_index++]) . ' : ??????????????????</a></li>',
						'</ul>',
					];
					break;
			}
			
			return $toc_paragraphs;
		}
		
		public function getWhatIsEncouragedSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$what_is_encouraged_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$what_is_encouraged_section[] =
						'<h3><a name="whatisencouraged"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What Is Encouraged</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Cooperative participation, especially when it is :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inclusive,</li>' .
							'<li>Cooperative,</li>' .
							'<li>Community-oriented,</li>' .
							'<li>Active,</li>' .
							'<li>Considerate,</li>' .
							'<li>Respectful,</li>' .
							'<li>Collaborative,</li>' .
							'<li>Authentic,</li>' .
							'<li>And when all of these approaches are attempted before conflict.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Voluntary interaction, especially when it includes :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inclusive language and behavior,</li>' .
							'<li>Welcoming attitude and approach,</li>' .
							'<li>Rational debate and discussion,</li>' .
							'<li>Genuine exchanges of ideas,</li>' .
							'<li>Spreading useful or enlightening information,</li>' .
							'<li>Community-driven and built projects,</li>' .
							'<li>Political, social, or economic progress,</li>' .
							'<li>Identifying with a community-oriented cause,</li>' .
							'<li>Solidarity and a sense of interest in the community.</li>' .
						'</ul>';
					break;
					
				case 'de':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Was wird gef??rdert?</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Kooperative Beteiligung, insbesondere wenn :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inklusive,</li>' .
							'<li>Genossenschaft,</li>' .
							'<li>Gemeinschaftsorientiert,</li>' .
							'<li>Aktiv,</li>' .
							'<li>R??cksichtsvoll,</li>' .
							'<li>Respektvoll,</li>' .
							'<li>Kollaborativ,</li>' .
							'<li>Authentisch,</li>' .
							'<li>Und wenn all diese Ans??tze vor dem Konflikt versucht werden.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Freiwillige Interaktion, insbesondere wenn es umfasst :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inklusive Sprache und Verhalten,</li>' .
							'<li>Einladende Haltung und Herangehensweise,</li>' .
							'<li>Rationale Debatte und Diskussion,</li>' .
							'<li>Echter Gedankenaustausch,</li>' .
							'<li>N??tzliche oder aufschlussreiche Informationen verbreiten,</li>' .
							'<li>Community-getriebene und gebaute Projekte,</li>' .
							'<li>Politischer, sozialer oder wirtschaftlicher Fortschritt,</li>' .
							'<li>Sich mit einer gemeindenahen Sache identifizieren,</li>' .
							'<li>Solidarit??t und Interesse an der Gemeinschaft.
</li>' .
						'</ul>';
					break;
					
				case 'es':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Lo que se alienta</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Participaci??n cooperativa, especialmente cuando es :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inclusiva,</li>' .
							'<li>Cooperativa,</li>' .
							'<li>Orientado a la comunidad,</li>' .
							'<li>Activa,</li>' .
							'<li>Considerada,</li>' .
							'<li>Respetuosa,</li>' .
							'<li>Colaborativa,</li>' .
							'<li>Aut??ntica,</li>' .
							'<li>Y cuando se intentan todos estos enfoques antes del conflicto.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Interacci??n voluntaria, especialmente cuando incluye :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Lenguaje y comportamiento inclusivo,</li>' .
							'<li>Actitud de acogida y enfoque,</li>' .
							'<li>Debate y discusi??n racional,</li>' .
							'<li>Intercambios genuinos de ideas,</li>' .
							'<li>Difundir informaci??n ??til o esclarecedora,</li>' .
							'<li>Proyectos construidos y dirigidos por la comunidad,</li>' .
							'<li>Progreso pol??tico, social o econ??mico,</li>' .
							'<li>Identificarse con una causa orientada a la comunidad,</li>' .
							'<li>Solidarity and a sense of interest in the community.</li>' .
						'</ul>';
					break;
					
				case 'fr':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introduction</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Participation coop??rative, surtout quand il s???agit de :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Compris,</li>' .
							'<li>Coop??ratif,</li>' .
							'<li>Orient?? vers la communaut??,</li>' .
							'<li>Actif,</li>' .
							'<li>Pr??venant,</li>' .
							'<li>Respectueux,</li>' .
							'<li>Collaboratif,</li>' .
							'<li>Authentique,</li>' .
							'<li>Et quand toutes ces approches sont tent??es avant le conflit.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Interaction volontaire, notamment lorsqu\'elle comprend :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Langage et comportement inclusifs,</li>' .
							'<li>Attitude accueillante et approche,</li>' .
							'<li>D??bat et discussion rationnelle,</li>' .
							'<li>??changes authentiques d\'id??es,</li>' .
							'<li>Diffuser des informations utiles ou ??clairantes,</li>' .
							'<li>Projets construits et dirig??s par la communaut??,</li>' .
							'<li>Progr??s politique, social ou ??conomique,</li>' .
							'<li>S\'identifier ?? une cause ax??e sur la communaut??,</li>' .
							'<li>Solidarit?? et int??r??t pour la communaut??.</li>' .
						'</ul>';
					break;
					
							// BT : HERE!
					
				case 'ja':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ?????????</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>??????????????????????????????????????? :</h3>';
					
						// BT: HERE!
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>????????????</li>' .
							'<li>???????????????</li>' .
							'<li>???????????????????????????</li>' .
							'<li>??????????????????</li>' .
							'<li>????????????????????????</li>' .
							'<li>?????????????????????</li>' .
							'<li>???????????????</li>' .
							'<li>????????????</li>' .
							'<li>?????????????????????????????????????????????????????????????????????????????????????????????</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>?????????????????????????????????????????????????????????</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>??????????????????????????????</li>' .
							'<li>????????????????????????????????????</li>' .
							'<li>??????????????????????????????</li>' .
							'<li>???????????????????????????</li>' .
							'<li>???????????????????????????????????????????????????</li>' .
							'<li>????????????????????????????????????????????????</li>' .
							'<li>???????????????????????????????????????????????????</li>' .
							'<li>????????????????????????????????????????????????</li>' .
							'<li>??????????????????????????????????????????</li>' .
						'</ul>';
					break;
					
				case 'it':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introduzione</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Partecipazione cooperativa, specialmente quando ?? :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inclusiva,</li>' .
							'<li>Cooperativa,</li>' .
							'<li>Orientata alla comunit??,</li>' .
							'<li>Attiva,</li>' .
							'<li>Premurosa,</li>' .
							'<li>Rispettosa,</li>' .
							'<li>Collaborative,</li>' .
							'<li>Autentica,</li>' .
							'<li>E quando tutti questi approcci vengono tentati prima del conflitto.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Interazione volontaria, specialmente quando include:</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Linguaggio e comportamento inclusivi,</li>' .
							'<li>Atteggiamento e approccio di benvenuto,</li>' .
							'<li>Dibattito e discussione razionali,</li>' .
							'<li>Scambi genuini di idee,</li>' .
							'<li>Diffondere informazioni utili o illuminanti,</li>' .
							'<li>Progetti guidati e realizzati dalla comunit??,</li>' .
							'<li>Progresso politico, sociale o economico,</li>' .
							'<li>Identificarsi con una causa orientata alla comunit??,</li>' .
							'<li>Solidariet?? e senso di interesse per la comunit??.</li>' .
						'</ul>';
					break;
					
				case 'nl':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Invoering</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Co??peratieve deelname, vooral als het gaat om :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inclusief,</li>' .
							'<li>Co??peratieve,</li>' .
							'<li>Gemeenschapsgericht,</li>' .
							'<li>Actief,</li>' .
							'<li>Attent,</li>' .
							'<li>Eerbiedig,</li>' .
							'<li>Samenwerkend,</li>' .
							'<li>Authentiek,</li>' .
							'<li>En wanneer al deze benaderingen worden geprobeerd v????r het conflict.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Vrijwillige interactie, vooral wanneer deze omvat:</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inclusief taal en gedrag,</li>' .
							'<li>Gastvrije houding en aanpak,</li>' .
							'<li>Rationeel debat en discussie,</li>' .
							'<li>Echte idee??nuitwisseling,</li>' .
							'<li>Nuttige of verhelderende informatie verspreiden,</li>' .
							'<li>Gemeenschapgestuurde en gebouwde projecten,</li>' .
							'<li>Politieke, sociale of economische vooruitgang,</li>' .
							'<li>Zich identificeren met een gemeenschapsgerichte oorzaak,</li>' .
							'<li>Solidariteit en een gevoel van interesse in de gemeenschap.</li>' .
						'</ul>';
					break;
					
				case 'pl':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Wprowadzenie</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Wsp????praca, szczeg??lnie gdy :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>W????cznie,</li>' .
							'<li>Sp????dzielnia,</li>' .
							'<li>Zorientowany na spo??eczno????,</li>' .
							'<li>Aktywny,</li>' .
							'<li>Uwa??ny,</li>' .
							'<li>Pe??en szacunku,</li>' .
							'<li>Wsp????pracy,</li>' .
							'<li>Autentyczny,</li>' .
							'<li>A kiedy wszystkie te podej??cia s?? podejmowane przed konfliktem.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Dobrowolna interakcja, szczeg??lnie gdy obejmuje:</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>W????czaj??cy j??zyk i zachowanie,</li>' .
							'<li>Przyjazne nastawienie i podej??cie,</li>' .
							'<li>Racjonalna debata i dyskusja,</li>' .
							'<li>Prawdziwa wymiana pomys????w,</li>' .
							'<li>Rozpowszechnianie przydatnych lub pouczaj??cych informacji,</li>' .
							'<li>Projekty realizowane przez spo??eczno???? i budowane,</li>' .
							'<li>Post??p polityczny, spo??eczny lub gospodarczy,</li>' .
							'<li>Identyfikacja z przyczyn?? zorientowan?? na spo??eczno????,</li>' .
							'<li>Solidarno???? i zainteresowanie wsp??lnot??.</li>' .
						'</ul>';
					break;
					
				case 'pt':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Introdu????o</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Participa????o cooperativa, especialmente quando ?? :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Inclusiva,</li>' .
							'<li>Cooperativa,</li>' .
							'<li>Orientado para a comunidade,</li>' .
							'<li>Ativa,</li>' .
							'<li>Atenciosa,</li>' .
							'<li>Respeitosa,</li>' .
							'<li>Colaborativa,</li>' .
							'<li>Aut??ntica,</li>' .
							'<li>E quando todas essas abordagens s??o tentadas antes do conflito.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>Intera????o volunt??ria, especialmente quando inclui:</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Linguagem e comportamento inclusivos,</li>' .
							'<li>Atitude e abordagem acolhedoras,</li>' .
							'<li>Debate e discuss??o racional,</li>' .
							'<li>Trocas genu??nas de id??ias,</li>' .
							'<li>Divulga????o de informa????es ??teis ou esclarecedoras,</li>' .
							'<li>Projetos constru??dos e dirigidos pela comunidade,</li>' .
							'<li>Progresso pol??tico, social ou econ??mico,</li>' .
							'<li>Identificando-se com uma causa orientada para a comunidade,</li>' .
							'<li>Solidariedade e senso de interesse na comunidade.</li>' .
						'</ul>';
					break;
					
				case 'ru':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ????????????????????</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>???????????????????? ??????????????, ???????????????? ?????????? ?????? :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>????????????????????????,</li>' .
							'<li>??????????????????????????,</li>' .
							'<li>c??????????????????-??????????????????????????????,</li>' .
							'<li>????????????????,</li>' .
							'<li>??????????????????,</li>' .
							'<li>????????????????????????,</li>' .
							'<li>????????????????????,</li>' .
							'<li>??????????????????????,</li>' .
							'<li>?? ?????????? ?????? ?????? ?????????????? ?????????????????????????????? ???? ??????????????????.</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>???????????????????????? ????????????????????????????, ???????????????? ?????????? ?????? ???????????????? ?? ????????:</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>?????????????????????? ???????? ?? ??????????????????,</li>' .
							'<li>???????????????????????????? ?????????????????? ?? ????????????,</li>' .
							'<li>???????????????????????? ?????????????????? ?? ??????????????????,</li>' .
							'<li>?????????????????? ?????????? ????????????,</li>' .
							'<li>?????????????????????????????? ???????????????? ?????? ???????????????? ????????????????????,</li>' .
							'<li>???????????????????????? ?? ?????????????????????? ??????????????,</li>' .
							'<li>????????????????????????, ???????????????????? ?????? ?????????????????????????? ????????????????,</li>' .
							'<li>?????????????????????????? ?? ?????????????????????????????? ???? ???????????????????? ????????????????,</li>' .
							'<li>???????????????????????? ?? ?????????????? ???????????????? ?? ????????????????????.</li>' .
						'</ul>';
					break;
					
				case 'tr':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Giri??</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>Kooperatif kat??l??m??, ??zellikle :</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>dahil,</li>' .
							'<li>kooperatif,</li>' .
							'<li>Toplum odakl??,</li>' .
							'<li>Aktif,</li>' .
							'<li>D??????nceli,</li>' .
							'<li>Sayg??l??,</li>' .
							'<li>????birlik??i,</li>' .
							'<li>Otantik,</li>' .
							'<li>Ve t??m bu yakla????mlar ??at????madan ??nce denendi??inde..</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>G??n??ll?? etkile??im, ??zellikle a??a????dakileri i??erdi??inde:</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>Kapsay??c?? dil ve davran????,</li>' .
							'<li>Ho?? tutum ve yakla????m,</li>' .
							'<li>Ak??lc?? tart????ma ve tart????ma,</li>' .
							'<li>Orijinal fikir al????veri??i,</li>' .
							'<li>Yararl?? veya ayd??nlat??c?? bilgi yayma,</li>' .
							'<li>Topluluk odakl?? ve in??a edilmi?? projeler,</li>' .
							'<li>Politik, sosyal veya ekonomik ilerleme,</li>' .
							'<li>Topluma y??nelik bir nedenle ??zde??le??mek,</li>' .
							'<li>Toplumda dayan????ma ve ilgi duygusu.</li>' .
						'</ul>';
					break;
					
				case 'zh':
					$what_is_encouraged_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ??????</h3>';
					
					$what_is_encouraged_section[] =
						'<h3>????????????????????????????????? ???</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>?????????,</li>' .
							'<li>?????????,</li>' .
							'<li>????????????,</li>' .
							'<li>??????,</li>' .
							'<li>??????,</li>' .
							'<li>?????????,</li>' .
							'<li>????????????,</li>' .
							'<li>??????,</li>' .
							'<li>???????????????????????????????????????????????????</li>' .
						'</ul>';
					
					$what_is_encouraged_section[] =
						'<h3>???????????????????????????????????????</h3>';
					
					$what_is_encouraged_section[] =
						'<ul>' .
							'<li>????????????????????????,</li>' .
							'<li>????????????????????????,</li>' .
							'<li>?????????????????????,</li>' .
							'<li>?????????????????????,</li>' .
							'<li>?????????????????????????????????,</li>' .
							'<li>??????????????????????????????,</li>' .
							'<li>??????????????????????????????,</li>' .
							'<li>?????????????????????????????????,</li>' .
							'<li>??????????????????????????????</li>' .
						'</ul>';
					break;
			}
			
			return $what_is_encouraged_section;
		}
		
		public function getWhatIsUnacceptableSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$what_is_unacceptable_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unacceptable</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>Degrading, disrespecting, or insulting another person or group of people, because of their :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'de':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Was ist inakzeptabel</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>Eine andere Person oder Gruppe von Menschen herabsetzen, respektlos behandeln oder beleidigen, weil:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Geschlecht oder Geschlechtsidentit??t,</li>' .
							'<li>Ethnische Zugeh??rigkeit,</li>' .
							'<li>Zuwandererstatus,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexualit??t,</li>' .
							'<li>Sprache,</li>' .
							'<li>Aussehen oder K??rpergr????e,</li>' .
							'<li>Politische Meinung,</li>' .
							'<li>Substanz oder medizinische Verwendung,</li>' .
							'<li>Behinderung,</li>' .
							'<li>Alter,</li>' .
							'<li>Akzeptanz jeder ung??nstigen oder ung??nstigen Gruppe, unabh??ngig davon, ob es sich um eine politische, wirtschaftliche, soziale oder kulturelle Gruppe handelt.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Bel??stigung :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Missbrauch jeglicher Art,</li>' .
							'<li>Mobbing, Stalking, Einsch??chterung, Folgen,</li>' .
							'<li>Bel??stigung beim Fotografieren oder Filmen,</li>' .
							'<li>Unangemessener k??rperlicher Kontakt,</li>' .
							'<li>Unangemessene sexualisierte Bilder,</li>' .
							'<li>Unerw??nschte sexuelle Aufmerksamkeit,</li>' .
							'<li>Jede anhaltende und unerw??nschte Handlung, die den pers??nlichen Bereich eines anderen einbezieht.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unerw??nschtes Eindringen in ??ffentliche oder private Bereiche :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam und Kommerz,</li>' .
							'<li>Unterbrechungen aufgrund von nicht verwandtem oder ablenkendem Material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Politische, soziale oder wirtschaftliche Vorherrschaft, z. B. die Drohung oder nicht einvernehmliche Offenlegung personenbezogener Daten eines anderen f??r Folgendes:</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Ein Arbeitgeber oder potenzieller Arbeitgeber.</li>' .
							'<li>Eine Regierung, eine von der Regierung erm??chtigte Beh??rde oder ein von der Regierung erm??chtigter Agent oder Polizeibeamter.</li>' .
							'<li>Jede zensierende Organisation wie religi??se, soziale, kulturelle, wirtschaftliche oder politische Institutionen wie Familie, Freunde, Kirchen, Zeitungen, Verlage, Radiosender usw..</li>' .
							'<li>Dritte ohne deren direkte Zustimmung.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Autoritarismus oder die Verbreitung von Verhaltensweisen, die dazu dienen sollen, die bisher beschriebenen Standards zu ??berschreiten, z. B. Versuche, Folgendes voranzutreiben, zu unterst??tzen oder zu unterst??tzen:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexismus,' .
							'Rassismus,' .
							'Wei??e Vormachtstellung (oder jede ethnische Vormachtstellung),' .
							'Homophobie (oder eine Sexualphobie),' .
							'Faschismus,' .
							'V??lkermord,' .
							'Drogenphobie,' .
							'Ethnische, geschlechtsspezifische, sexuelle, ableistungs- usw.' .
							'Eid- oder Pfand??bernahme.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Bef??rwortung oder Ermutigung der oben genannten Punkte.</h3>';
					break;
					
				case 'es':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Lo que es inaceptable</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>Degradar, faltar al respeto o insultar a otra persona o grupo de personas, debido a su:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>G??nero o identidad de g??nero,</li>' .
							'<li>Etnicidad,</li>' .
							'<li>Estado de inmigrante,</li>' .
							'<li>Religi??n,</li>' .
							'<li>Sexualidad,</li>' .
							'<li>Idioma,</li>' .
							'<li>Apariencia f??sica o tama??o del cuerpo,</li>' .
							'<li>Opini??n pol??tica,</li>' .
							'<li>Sustancia o uso medicinal,</li>' .
							'<li>Invalidez,</li>' .
							'<li>A??os,</li>' .
							'<li>Aceptaci??n de cualquier grupo desfavorable o desfavorable, ya sea este grupo pol??tico, econ??mico, social o cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Acoso :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuso de cualquier tipo,</li>' .
							'<li>Intimidaci??n, acoso, intimidaci??n, seguimiento,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'fr':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Ce qui est inacceptable</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>D??grader, manquer de respect ou insulter une autre personne ou un groupe de personnes, en raison de:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'ja':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ??????????????????????????????</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>????????????????????????????????????????????????????????????De???????????????or???????????????</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'it':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Cosa ?? inaccettabile</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>Degradare, mancare di rispetto o insultare un\'altra persona o un gruppo di persone, a causa della loro:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'nl':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Wat is onaanvaardbaar</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>Een andere persoon of groep mensen vernederen, respectloos maken of beledigen vanwege hun:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'pl':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Co jest niedopuszczalne</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>Obra??anie, brak szacunku lub obra??anie innej osoby lub grupy os??b z powodu:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'pt':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : O que ?? inaceit??vel</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>Degradando, desrespeitando ou insultando outra pessoa ou grupo de pessoas, devido a:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'ru':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ?????? ??????????????????????</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>????????????????, ???????????????????? ?????? ?????????????????????? ?????????????? ???????????????? ?????? ???????????? ?????????? ????-???? ????:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'tr':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Kabul Edilemez</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>A??a????dakiler nedeniyle ba??ka bir ki??iyi veya bir grup insan?? a??a????lamak, sayg??s??zl??k etmek veya a??a????lamak:</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
					
				case 'zh':
					$what_is_unacceptable_section[] =
						'<h3><a name="what-is-unacceptable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : ????????????????????????</h3>';
					
					$what_is_unacceptable_section[] =
						'<h3>????????????????????????????????????????????????????????????</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Gender or Gender Identity,</li>' .
							'<li>Ethnicity,</li>' .
							'<li>Immigrant status,</li>' .
							'<li>Religion,</li>' .
							'<li>Sexuality,</li>' .
							'<li>Language,</li>' .
							'<li>Physical appearance or body size,</li>' .
							'<li>Political opinion,</li>' .
							'<li>Substance or medicinal use,</li>' .
							'<li>Disability,</li>' .
							'<li>Age,</li>' .
							'<li>Acceptance of any unfavorable or disfavorable group, whether this group is political, economic, social, or cultural.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Harassment :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Abuse of any type,</li>' .
							'<li>Bullying, stalking, intimidating, following,</li>' .
							'<li>Harassing photographing or filming,</li>' .
							'<li>Inappropriate physical contact,</li>' .
							'<li>Inappropriate sexualized imagery,</li>' .
							'<li>Unwelcome sexual attention,</li>' .
							'<li>Any persistent and undesired action involving the personal space of another.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Unwanted intrusion in public or private atmospheres :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>Spam and commercialism,</li>' .
							'<li>Interruptions based on unrelated or distracting material.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						"<h3>Political, social, or economic domination, such as threatening to or non-consensually revealing another's personal information to the following :</h3>";
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'<li>An employer or potential employer.</li>' .
							'<li>A government, an agency empowered by the government, or an agent or police officer empowered by the government.</li>' .
							'<li>Any censuring organization, such as religious, social, cultural, economic, or political institutions, such as family, friends, churches, newspapers, publishers, radio stations, etc..</li>' .
							'<li>Any third party without their direct consent.</li>' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Authoritarianism, or the spread of behavior that is designed to overturn the standards described so far, such as attempts to advance, support, or aid any of the following :</h3>';
					
					$what_is_unacceptable_section[] =
						'<ul>' .
							'Sexism,' .
							'Racism,' .
							'White Supremacy (or any ethnic-supremacy),' .
							'Homophobia (or any sexuality-phobia),' .
							'Fascism,' .
							'Genocide,' .
							'Drug-phobia,' .
							'Ethnic-, gender-, sexuality-, ableist-, etc., based slurs,' .
							'Oath-taking or pledge-taking.' .
						'</ul>';
					
					$what_is_unacceptable_section[] =
						'<h3>Advocating or encouraging any of the above.</h3>';
					break;
			}
			
			return $what_is_unacceptable_section;
		}
		
		public function getWhatIsUnaccountableSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$what_is_unaccountable_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'de':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'es':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'fr':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'ja':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'it':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'nl':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'pl':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'pt':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'ru':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'tr':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
					
				case 'zh':
					$what_is_unaccountable_section[] =
						'<h3><a name="what-is-unaccountable"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : What is Unaccountable</h3>';
					
					$what_is_unaccountable_section[] =
						'<p>We are one community organization. If your complaint is severe enough, you may need to elevate it beyond what this agreement provides for. You are authorized and encouraged to this if you do not think your complaint can be met with satisfaction.</p>';
					
					$what_is_unaccountable_section[] =
						'<h3>You may not cite this document as reason, when :</h3>';
					
					$what_is_unaccountable_section[] =
						'<ul>' .
							'<li>Passing or repealing government laws,</li>' .
							'<li>Hiring or discharging workers,</li>' .
							'<li>Refusing to cooperate with the investigation of a legitimate, Humans Rights, Non-Government Organization.</li>' .
						'</ul>';
					break;
			}
			
			return $what_is_unaccountable_section;
		}
		
		public function getHowWeEnforceCommunityStandardsSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$how_we_enforce_community_standards_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'de':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'es':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'fr':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'ja':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'it':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'nl':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'pl':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'pt':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'ru':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'tr':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
					
				case 'zh':
					$how_we_enforce_community_standards_section[] =
						'<h3><a name="introduction"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : How we Enforce Community Standards</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<p>If you witness or experience any violation of this Code of Conduct, you are urged to immediately contact official organizers within the community.</p>';
					
					$how_we_enforce_community_standards_section[] =
						'<h3>In response to violations of unacceptable behavior, we may respond to the offender as follows :</h3>';
					
					$how_we_enforce_community_standards_section[] =
						'<ul>' .
							'<li>Warning,</li>' .
							'<li>Temporary ban,</li>' .
							'<li>Permanent expulsion.</li>' .
						'</ul>';
					break;
			}
			
			return $how_we_enforce_community_standards_section;
		}
		
		public function getLicensingSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$licensing_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'de':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'es':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'fr':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'ja':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'it':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'nl':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'pl':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'pt':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'ru':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'tr':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
					
				case 'zh':
					$licensing_section[] =
						'<h3><a name="licensing"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Licensing of this Document</h3>';
					
					$licensing_section[] =
						'<p>This document is released under the Creative Commons Attribution 3.0 License.  You may find these terms here...</p>';
						
					$licensing_section[] =
						'<blockquote><a href="http://www.CopyLeftLicense.com/?id=440" target="_blank">Creative Commons Attribution 3.0 License (CC-BY License)</a></blockquote>';
					break;
			}
			
			return $licensing_section;
		}
		
		public function getInspirationSection($args) {
			$header_index = $args['headerindex'];
			$image = $args['image'];
			
			$inspiration_section = [];
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'de':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'es':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'fr':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'ja':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'it':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'nl':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'pl':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'pt':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'ru':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'tr':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
					
				case 'zh':
					$inspiration_section[] =
						'<h3><a name="inspiration"></a>' .
						$this->getImageMarkup(['image'=>$image]) .
						$this->getSectionText(['headerindex'=>$header_index]) . ' : Inspiration for this Document</h3>';
					
					$inspiration_section[] =
						'<p>This document was inspired by a wide number of other materials, and I owe them all my gratitude! Thank you :</p>';
					
					$inspiration_section[] =
						'<ul>' .
							'<li>Reddit Anarchism Rules</li>' .
							'<li>Anti-Authoritarian Academic Code of Conduct</li>' .
							'<li>Geek Feminism Conference Anti-Harassment Policy</li>' .
							'<li>The Constitution of the Rojava Cantons : Modern, Anarchist Constitution (2014)</li>' .
							"<li>Workers' Solidarity Movement : WSM Code of Conduct</li>" .
							'<li>Industrial Workers of the World : Code of Ethics</li>' .
							'<li>The French Declaration of Rights of Man : Classical, Socialist Declaration (1789)</li>' .
							'<li>The Citizen Code of Conduct</li>' .
							'<li>The Contributor Covenant</li>' .
							'<li>The Tao Teh Ching, by Lao Tzu (400-600 BCE)</li>' .
						'</ul>';
					break;
			}
			
			return $inspiration_section;
		}
		
						// Helper Functions
						// ---------------------------------------------
		
		public function getSectionText($args) {
			$header_index = $args['headerindex'];
			
			$section_text = '';
			
			switch($this->language_object->getLanguageCode()) {
				default:
				case 'en':
					$section_text = 'Section ' . $header_index;
					break;
					
				case 'de':
					$section_text = 'Abschnitt ' . $header_index;
					break;
					
				case 'es':
					$section_text = 'Secci??n ' . $header_index;
					break;
				
				case 'fr':
					$section_text = 'Section ' . $header_index;
					break;
					
				case 'ja':
					$section_text = '???' . $header_index . '???';
					break;
					
				case 'it':
					$section_text = 'Sezione ' . $header_index;
					break;
					
				case 'nl':
					$section_text = 'Sectie ' . $header_index;
					break;
					
				case 'pl':
					$section_text = 'Sekcja ' . $header_index;
					break;
				
				case 'pt':
					$section_text = 'Se????o ' . $header_index;
					break;
					
				case 'ru':
					$section_text = '???????????? ' . $header_index;
					break;
				
				case 'tr':
					$section_text = 'B??l??m ' . $header_index;
					break;
					
				case 'zh':
					$section_text = '???' . $header_index . '???';
					break;
			}
			
			if(	($this->script_format_lower != 'pdf')) {
				$section_text = ' ' . $section_text;
			}
			
			return $section_text;
		}
		
		public function getImageMarkup($args) {
			$image = $args['image'];
			
			if($this->mobile_friendly) {
				return '';
			}
			
			$base_directory = $this->getImageBaseDirectory();
			
			$markup =
				'<img src="' . $base_directory .
				$image['filename'] .
				'" title="Icon designed by ' .
				$image['creator'] . ', ' .
				$image['license'] . ' License, from ' .
				$image['source'] .
				'"height="50" width="50" class="border-2px"> '
			;
			$markup .= "\n";
			
			return $markup;
		}
		
		public function getImageIconMarkup($args) {
			$image = $args['image'];
			
			if($this->mobile_friendly || ($this->script_format_lower == 'pdf')) {
				return '';
			}
			
			$base_directory = $this->getImageBaseDirectory();
			
			$markup =
				'<img src="' . $base_directory .
				$image['filename'] .
				'" title="Icon designed by ' .
				$image['creator'] . ', ' .
				$image['license'] . ' License, from ' .
				$image['source'] .
				'"height="15" width="15" class="border-2px"> '
			;
			
			return $markup;
		}
		
		public function getImageBaseDirectory() {
			$base_directory = '/image/code-of-conduct/';
			
			if(	($this->script_format_lower == 'pdf') ||
				($this->script_format_lower == 'rtf')) {
				$base_directory = 'https://www.' . $this->domain_object->host . '.com' . $base_directory;
			}
			
			return $base_directory;
		}
	}
	
?>