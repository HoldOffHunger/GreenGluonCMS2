<?php
	
			// HTML Displaying
		
		// -------------------------------------------------------------
		
	if(	($this->script_format_lower == 'pdf') ||
		($this->script_format_lower == 'rtf') ||
		($this->script_format_lower == 'epub') ||
		($this->script_format_lower == 'daisy') ||
		($this->script_format_lower == 'sgml') ||
		($this->script_format_lower == 'tex') ||
		($this->script_format_lower == 'json') ||
		($this->script_format_lower == 'xml') ||
		($this->script_format_lower == 'txt') ||
		($this->script_format_lower == 'csv') ||
		($this->script_format_lower == 'opds') ||
		($this->script_format_lower == 'rdf') ||
		($this->script_format_lower == 'html' && $this->Param('printerfriendly')) ||
		($this->script_format_lower == 'html' && $this->Param('invertedcolors'))
	) {
		$html_document = '';
		
		$html_paragraphs = $this->getTermsOfServiceParagraphs();
		
		$html_document = implode("\n", $html_paragraphs);
		
		$html_document = '<h1>' . $this->GetHTMLFormatData_Title() . '</h1>' . "\n" . $html_document;
		
		if($this->script_format_lower == 'pdf') {
			$this->source_content = $html_document;
		} elseif($this->script_format_lower == 'tex') {
			$this->source_content = $html_document;
		} elseif($this->script_format_lower == 'rtf') {
			$this->source_content = $html_document;
		} elseif($this->script_format_lower == 'opds' || $this->script_format_lower == 'rdf') {
			$this->record_to_use['termsofservice'] = $html_document;
		} elseif($this->script_format_lower == 'json') {
			$this->record_to_use['termsofservice'] = $html_document;
		} elseif($this->script_format_lower == 'xml' || $this->script_format_lower == 'csv') {
			$this->record_to_use['termsofservice'] = $html_document;
			$this->source_content = $this->record_to_use;
		} elseif ($this->script_format_lower == 'daisy') {
			$this->source_content = $html_document;
		} elseif ($this->script_format_lower == 'sgml') {
			$this->source_content = $html_document;
		} elseif($this->script_format_lower == 'epub') {
			$this->record_to_use = $this->primary_host_record;
			$this->source_content = $html_document;
		} elseif($this->script_format_lower == 'txt') {
			$text_document = strip_tags($html_document);
			
			if($this->Param('wrapped'))
			{
				$text_document = wordwrap($text_document, 75, "\n", FALSE);
			}
			
			print($text_document);
		} elseif($this->script_format_lower == 'html' && $this->Param('printerfriendly')) {
			print('<div class="font-family-arial">');
			
			print($html_document);
			
			print('</div>');
		} elseif($this->script_format_lower == 'html' && $this->Param('invertedcolors')) {
			print('<div class="font-family-arial">');
			
			print($html_document);
			
			print('</div>');
		}
	} elseif($this->script_format_lower == 'html') {
	
				// Standard Requires
			
			// -------------------------------------------------------------
	
		ggreq('modules/spacing.php');
		
		ggreq('modules/' . $this->script_format_lower . '/text.php');
		$text = new module_text;
		
		ggreq('modules/' . $this->script_format_lower . '/form.php');
		$form = new module_form;
		
		ggreq('modules/' . $this->script_format_lower . '/divider.php');
		$divider = new module_divider;
		
		ggreq('modules/' . $this->script_format_lower . '/table.php');
		$table = new module_table;
		
		ggreq('modules/' . $this->script_format_lower . '/list/generic.php');
		$generic_list = new module_genericlist;
		
		ggreq('modules/' . $this->script_format_lower . '/header.php');
		$header = new module_header;
		
		ggreq('modules/' . $this->script_format_lower . '/languages.php');
		$languages_args = [
			'languageobject'=>$this->language_object,
			'divider'=>$divider,
			'domainobject'=>$this->domain_object,
		];
		$languages = new module_languages($languages_args);
		
		ggreq('modules/' . $this->script_format_lower . '/navigation.php');
		$navigation_args = [
			'globals'=>$this->globals,
			'languageobject'=>$this->language_object,
			'divider'=>$divider,
			'domainobject'=>$this->domain_object,
		];
		$navigation = new module_navigation($navigation_args);
		
				// Get Info Header Language
			
			// -------------------------------------------------------------
			
		switch($this->language_object->getLanguageCode()) {
			default:
			case 'en':
				$terms_header_title_text = 'Terms and Conditions';
				break;
			
			case 'de':
				$terms_header_title_text = 'Gesch??ftsbedingungen';
				break;
				
			case 'es':
				$terms_header_title_text = 'T??rminos y Condiciones';
				break;
			
			case 'fr':
				$terms_header_title_text = 'Termes et conditions';
				break;
				
			case 'ja':
				$terms_header_title_text = '???????????????';
				break;
				
			case 'it':
				$terms_header_title_text = 'Termini e condizioni';
				break;
				
			case 'nl':
				$terms_header_title_text = 'Voorwaarden';
				break;
				
			case 'pl':
				$terms_header_title_text = 'Regulamin';
				break;
			
			case 'pt':
				$terms_header_title_text = 'Termos e Condi????es';
				break;
				
			case 'ru':
				$terms_header_title_text = '?????????????? ?? ??????????????????';
				break;
			
			case 'tr':
				$terms_header_title_text = '??artlar ve ko??ullar';
				break;
				
			case 'zh':
				$terms_header_title_text = '???????????????';
				break;
		}
		
		if($this->language_object->getLanguageCode() == 'en')
		{
			$div_mouseover = $this->master_record['description'][0]['Description'];
		}
		else
		{
			$instructions_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainInstructionsContent']);
			
			if($instructions_language_translations[$this->language_object->getLanguageCode()])
			{
				$div_mouseover = $instructions_language_translations[$this->language_object->getLanguageCode()];
			}
			else
			{
				$div_mouseover = $this->master_record['description'][0]['Description'];
			}
		}
		
		if($this->language_object->getLanguageCode() == 'en')
		{
			$quote_text = $this->master_record['quote'][0]['Quote'];
		}
		else
		{
			$image_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainImageQuote']);
			
			if($image_language_translations[$this->language_object->getLanguageCode()])
			{
				$quote_text = $image_language_translations[$this->language_object->getLanguageCode()];
			}
			else
			{
				$quote_text = $this->master_record['quote'][0]['Quote'];
			}
		}
		
				// Display Header
			
			// -------------------------------------------------------------
			
		if($this->primary_host_record['PrimaryColor'])
		{
			$primary_color = $this->primary_host_record['PrimaryColor'];
		}
		else
		{
			$primary_color = '6495ED';
		}
		
		if($this->primary_host_record['ThirdColor'])
		{
			$third_color = $this->primary_host_record['ThirdColor'];
		}
		else
		{
			$third_color = 'B7CEEC';
		}
		
		$header_primary_args = [
			'title'=>$this->header_title_text,
			'image'=>$this->primary_host_record['PrimaryImageLeft'],
			'rightimage'=>$this->primary_host_record['PrimaryImageRight'],
			'divmouseover'=>$div_mouseover,
			'imagemouseover'=>'&quot;' . $quote_text . '&quot;',
			'level'=>1,
			'divclass'=>'horizontal-center width-100percent border-2px margin-top-5px background-color-' . $primary_color,
			'textclass'=>'padding-0px margin-0px horizontal-center vertical-center padding-top-22px',
			'imagedivclass'=>'border-2px margin-5px background-color-gray10',
			'imageclass'=>'border-1px',
			'domainobject'=>$this->domain_object,
			'leftimageenable'=>1,
			'rightimageenable'=>1,
		];
		
		$header->display($header_primary_args);
		
		/*
		
		ggreq('modules/html/entry-header.php');
		ggreq('modules/html/entry-index-header.php');
		$entryindexheader = new module_entryindexheader([
			'that'=>$this,
			'main_text'=>$this->header_title_text,
		]);
		
		$entryindexheader->Display();
		
		*/
		
				// Basic Divider Arguments
			
			// -------------------------------------------------------------
		
		$divider_navigation_args = [
			'class'=>'width-95percent horizontal-center margin-top-14px border-2px',
		];
		
		$file_divider = [
			'class'=>'width-100percent horizontal-left margin-5px font-family-arial',
		];
		
		$divider_instruction_area_start_args = [
			'class'=>'width-50percent border-1px horizontal-center margin-top-22px',
		];
		
				// Get Info Header Language
			
			// -------------------------------------------------------------
		
		switch($this->language_object->getLanguageCode()) {
			default:
			case 'en':
				$terms_instructions_text = 'These are the terms and conditions for ' . $this->master_record['Code'] . '.';
				break;
				
			case 'de':
				$terms_instructions_text = 'Dies sind die Bedingungen f??r ' . $this->master_record['Code'] . '.';
				break;
				
			case 'es':
				$terms_instructions_text = 'Estos son los t??rminos y condiciones de ' . $this->master_record['Code'] . '.';
				break;
			
			case 'fr':
				$terms_instructions_text = 'Ce sont les termes et conditions pour ' . $this->master_record['Code'] . '.';
				break;
				
			case 'ja':
				$terms_instructions_text = '????????????' . $this->master_record['Code'] . '????????????????????????';
				break;
				
			case 'it':
				$terms_instructions_text = 'Questi sono i termini e le condizioni per ' . $this->master_record['Code'] . '.';
				break;
				
			case 'nl':
				$terms_instructions_text = 'Dit zijn de algemene voorwaarden voor ' . $this->master_record['Code'] . '.';
				break;
				
			case 'pl':
				$terms_instructions_text = 'S?? to warunki ' . $this->master_record['Code'] . '.';
				break;
			
			case 'pt':
				$terms_instructions_text = 'Estes s??o os termos e condi????es para ' . $this->master_record['Code'] . '.';
				break;
				
			case 'ru':
				$terms_instructions_text = '?????? ?????????????? ' . $this->master_record['Code'] . '.';
				break;
			
			case 'tr':
				$terms_instructions_text = 'Bunlar ' . $this->master_record['Code'] . ' i??in ??artlar ve ko??ullar.';
				break;
				
			case 'zh':
				$terms_instructions_text = '?????????' . $this->master_record['Code'] . '?????????????????????';
				break;
		}
		
				// Privacy Policy Instructions
			
			// -------------------------------------------------------------
		
		$primary_url = $this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]);
		
		$version_list_display_args = [
			'options'=>[
				'tableheaders'=>0,
				'tableclass'=>'width-70percent horizontal-center border-2px background-color-' . $third_color . ' margin-top-14px',
				'rowclass'=>'border-1px horizontal-left',
				'cellclass'=>[
					'border-1px vertical-top',
					'border-1px width-100percent vertical-top',
				],
			],
			'list'=>[[
				$terms_instructions_text,
			]],
		];
		$generic_list->Display($version_list_display_args);
		
				// Start Top Bar
			
			// -------------------------------------------------------------
		
		print('<div class="horizontal-center width-95percent margin-top-5px">');
		
				// Breadcrumbs Info
			
			// -------------------------------------------------------------
		
		ggreq('modules/html/breadcrumbs.php');
		$breadcrumbs = new module_breadcrumbs(['that'=>$this, 'title'=>'Terms of Service']);
		$breadcrumbs->Display();
		
				// Login Info
			
			// -------------------------------------------------------------
			
		ggreq('modules/html/auth.php');
		$auth = new module_auth(['that'=>$this]);
		$auth->Display();
		
				// End Top Bar
			
			// -------------------------------------------------------------
		
		print('</div>');
	
			// Finish Breadcrumb Trails
		
		// -------------------------------------------------------------
							
	$clear_float_divider_start_args = [
		'class'=>'clear-float',
	];
	
	$divider->displaystart($clear_float_divider_start_args);
	
	$clear_float_divider_end_args = [
	];
	
	$divider->displayend($clear_float_divider_end_args);
			
				// Alternate Formats Info
			
			// -------------------------------------------------------------
			
		ggreq('modules/html/alternateformats.php');
		$auth = new module_alternateformats(['that'=>$this]);
		$auth->Display();
			
				// Get Info Header Language
			
			// -------------------------------------------------------------
			
		switch($this->language_object->getLanguageCode()) {
			default:
			case 'en':
				$terms_instructions_header = $this->master_record['Code'] . ' Terms and Conditions';
				break;
				
			case 'de':
				$terms_instructions_header = $this->master_record['Code'] . '-Nutzungsbedingungen';
				break;
				
			case 'es':
				$terms_instructions_header = 'T??rminos y condiciones de ' . $this->master_record['Code'];
				break;
			
			case 'fr':
				$terms_instructions_header = 'Conditions g??n??rales ' . $this->master_record['Code'];
				break;
				
			case 'ja':
				$terms_instructions_header = $this->master_record['Code'] . '???????????????';
				break;
				
			case 'it':
				$terms_instructions_header = 'Termini e condizioni ' . $this->master_record['Code'];
				break;
				
			case 'nl':
				$terms_instructions_header = $this->master_record['Code'] . ' Algemene voorwaarden';
				break;
				
			case 'pl':
				$terms_instructions_header = 'Warunki ' . $this->master_record['Code'];
				break;
			
			case 'pt':
				$terms_instructions_header = 'Termos e Condi????es ' . $this->master_record['Code'];
				break;
				
			case 'ru':
				$terms_instructions_header = '?????????????? ?????????????????????????? ' . $this->master_record['Code'];
				break;
			
			case 'tr':
				$terms_instructions_header = $this->master_record['Code'] . ' ??artlar ve Ko??ullar';
				break;
				
			case 'zh':
				$terms_instructions_header = $this->master_record['Code'] . '???????????????';
				break;
		}
		
				// Display Terms Policy File
			
			// -------------------------------------------------------------
		
		$divider->displaystart($divider_instruction_area_start_args);
		
print('<center><h2 class="margin-5px font-family-tahoma">' . $terms_instructions_header . ' :</h2></center>');
		
		
		$divider->displaystart($file_divider);
		
		$privacy_policy_text = $this->getTermsOfService();
		$privacy_policy_text = str_replace('<p>', '<p class="margin-top-10px margin-bottom-0px text-indent-25px">', $privacy_policy_text);
		print('<div class="text-to-play-as-audio">');
		print($privacy_policy_text);
		print('</div>');
		
print('<center><h2 class="margin-5px font-family-tahoma">' . $terms_instructions_header . ' :</h2></center>');
		
		$divider->displayend($divider_end_args);
		
		$divider->displayend($divider_end_args);
	
				// Display Language Options
			
			// -------------------------------------------------------------
		
		$languages->display();
		
				// Display Final Ending Navigation
			
			// -------------------------------------------------------------
		
		$bottom_navigation_args = [
			'thispage'=>'Privacy',
		];
		$navigation->DisplayBottomNavigation($bottom_navigation_args);
	}
	
?>