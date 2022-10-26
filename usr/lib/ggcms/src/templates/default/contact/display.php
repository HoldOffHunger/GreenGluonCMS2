<?php
		
			// Standard Requires
		
		// -------------------------------------------------------------

	ggreq('modules/spacing.php');
	
	ggreq('modules/html/text.php');
	$text = new module_text;
	
	ggreq('modules/html/form.php');
	$form = new module_form;
	
	ggreq('modules/html/divider.php');
	$divider = new module_divider;
	
	ggreq('modules/html/table.php');
	$table = new module_table;
	
	ggreq('modules/html/list/generic.php');
	$generic_list = new module_genericlist;
	
	ggreq('modules/html/languages.php');
	$languages_args = [
		'languageobject'=>$this->language_object,
		'divider'=>$divider,
		'domainobject'=>$this->domain_object,
	];
	$languages = new module_languages($languages_args);
	
	ggreq('modules/html/navigation.php');
	$navigation_args = [
		'globals'=>$this->globals,
		'languageobject'=>$this->language_object,
		'divider'=>$divider,
		'domainobject'=>$this->domain_object,
	];
	$navigation = new module_navigation($navigation_args);
	
			// Get Info Header Language
		
		// -------------------------------------------------------------
	
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
	
	ggreq('modules/html/entry-header.php');
	ggreq('modules/html/entry-index-header.php');
	$entryindexheader = new module_entryindexheader([
		'that'=>$this,
		'main_text'=>'Contact ' . $this->master_record['Title'],
		'sub_text'=>'Communicate with us! We want to hear your opinions! ' . $this->master_record['Subtitle'],
	]);
	
	$entryindexheader->Display();
	
			// Basic Divider Arguments
		
		// -------------------------------------------------------------
	
	$divider_navigation_args = [
		'class'=>'width-95percent horizontal-center margin-top-14px border-2px',
	];
	
	$divider_instruction_area_start_args = [
		'class'=>'width-50percent border-1px horizontal-center margin-top-22px',
	];
	
			// Get Contact Info Language
		
		// -------------------------------------------------------------
			
					// "Contact Us"
				
				// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$contact_us_title_text = 'Contact Us';
	}
	else
	{
		$contact_us_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesContactUs']);
		
		if($contact_us_language_translations[$this->language_object->getLanguageCode()])
		{
			$contact_us_title_text = $contact_us_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$contact_us_title_text = 'Contact Us';
		}
	}
			
					// "Site Creator"
				
				// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$site_creator_text = 'Site Creator';
	}
	else
	{
		$contact_site_creator_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesSiteCreator']);
		
		if($contact_site_creator_language_translations[$this->language_object->getLanguageCode()])
		{
			$site_creator_text = $contact_site_creator_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$site_creator_text = 'Site Creator';
		}
	}
	
	if(!$contact_creator_value) {
		$contact_creator_value = $this->globals->ContactCreator();
	}
			
					// "Site Created On"
				
				// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$site_created_on_text = 'Site Created On';
	}
	else
	{
		$contact_site_created_on_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesSiteCreatedOn']);
		
		if($contact_site_created_on_language_translations[$this->language_object->getLanguageCode()])
		{
			$site_created_on_text = $contact_site_created_on_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$site_created_on_text = 'Site Created On';
		}
	}
			
					// "Contact Creator"
				
				// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$contact_creator_text = 'Contact Creator';
	}
	else
	{
		$contact_creator_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesContactCreator']);
		
		if($contact_creator_language_translations[$this->language_object->getLanguageCode()])
		{
			$contact_creator_text = $contact_creator_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$contact_creator_text = 'Contact Creator';
		}
	}
	
			// Display About Info
		
		// -------------------------------------------------------------
	
	$divider->displaystart($divider_instruction_area_start_args);
	

	print('<center><h2 class="margin-5px font-family-tahoma">' . $contact_us_title_text . ' :</h2></center>');
	
	
	$contact_creator_value = $this->primary_host_record['Contact'];
	
	if(!$contact_creator_value) {
		$contact_creator_value = $this->globals->ContactCreator();
	}
	
	if(strpos($contact_creator_value, '@') !== false) {
		$contact_creator_value = '<a href="mailto:' . $contact_creator_value . '">' . $contact_creator_value . '</a>';
	}
	
	if(!$this->primary_host_record['Creator']) {
		$this->primary_host_record['Creator'] = $this->globals->SiteCreator();
	}
	
	if(!$this->primary_host_record['PublicReleaseDate']) {
		$this->primary_host_record['PublicReleaseDate'] = $this->globals->SiteCreatedOn();
	}
	
	print(
			'<div class="padding-5px horizontal-left font-family-arial">' .
			'<p class="margin-0px margin-top-5px"><strong>' . $site_creator_text . ' :</strong> ' . $this->primary_host_record['Creator'] . '</p>' .
			'<p class="margin-0px margin-top-5px"><strong>' . $site_created_on_text . ' :</strong> ' . $this->primary_host_record['PublicReleaseDate'] . '</p>' .
			'<p class="margin-0px margin-top-5px"><strong>' . $contact_creator_text . ' :</strong> ' . $contact_creator_value . '</p>' .
			'</div>');
	
	
	$divider->displayend($divider_end_args);
	
			// Display Final Ending Navigation
		
		// -------------------------------------------------------------
	
	$bottom_navigation_args = [
		'thispage'=>'Contact',
	];
	$navigation->DisplayBottomNavigation($bottom_navigation_args);
	
?>