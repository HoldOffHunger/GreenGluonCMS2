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
	
	ggreq('modules/html/header.php');
	$header = new module_header;
	
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
	
			// Description / Quote Languages
		
		// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$instructions_content_text = $this->master_record['description'][0]['Description'];
	}
	else
	{
		$instruction_content_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainInstructionsContent']);
		
		if($instruction_content_language_translations[$this->language_object->getLanguageCode()])
		{
			$instructions_content_text = $instruction_content_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$instructions_content_text = $this->master_record['description'][0]['Description'];
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
	
			// Share Package
		
		// -------------------------------------------------------------
	
	ggreq('modules/html/socialmediasharelinks.php');
	$social_media_share_links_args = [
		'globals'=>$this->globals,
		'languageobject'=>$this->language_object,
		'divider'=>$divider,
		'domainobject'=>$this->domain_object,
		'socialmedia'=>$this->social_media,
		'socialmediasharelinkargs'=>[
			'url'=>$this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]),
			'title'=>$this->header_title_text,
			'desc'=>$instructions_content_text,
			'provider'=>$this->domain_object->primary_domain_lowercased,
		],
	];
	$social_media_share_links = new module_socialmediasharelinks($social_media_share_links_args);
	
			// Display Header
		
		// -------------------------------------------------------------
	
	$header_primary_args = [
		'title'=>$this->header_title_text,
		'image'=>$this->primary_host_record['PrimaryImageLeft'],
		'rightimage'=>$this->primary_host_record['PrimaryImageRight'],
		'divmouseover'=>$instructions_content_text,
		'imagemouseover'=>'&quot;' . $quote_text . '&quot;',
		'level'=>1,
		'divclass'=>'horizontal-center width-100percent border-2px margin-top-5px background-color-6495ED',
		'textclass'=>'padding-0px margin-0px horizontal-center vertical-center padding-top-22px margin-bottom-10px',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>1,
		'rightimageenable'=>1,
	];
	
	$header->display($header_primary_args);
	
			// Basic Divider Arguments
		
		// -------------------------------------------------------------
	
	$divider_main_area_start_args = [
		'class'=>'width-90percent horizontal-center padding-top-22px',
	];
	
	$divider_secondary_area_start_args = [
		'class'=>'width-90percent horizontal-center',
	];
	
	$divider_instruction_area_start_args = [
		'class'=>'width-50percent border-1px horizontal-center margin-top-22px',
	];
	
	$divider_instruction_area_text_args = [
		'class'=>'width-95percent horizontal-left',
	];
	
	$divider_note_args = [
		'class'=>'width-50percent horizontal-center float-left',
	];
	
	$divider_end_args = [
	];
	
			// Get Instructions Language
		
		// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$instructions_label_text = 'Instructions';
	}
	else
	{
		$instruction_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainInstructionsHeader']);
		
		if($instruction_language_translations[$this->language_object->getLanguageCode()])
		{
			$instructions_label_text = $instruction_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$instructions_label_text = 'Instructions';
		}
	}
	
			// Display Instructions
		
		// -------------------------------------------------------------
	
	$divider->displaystart($divider_instruction_area_start_args);
	
	$divider->displaystart($divider_instruction_area_text_args);
	
print('<div class="padding-5px"><span class="font-family-tahoma"><b>' . $instructions_label_text . ' :</b> ' . $instructions_content_text . '</span></div>');
	
	
	$divider->displayend($divider_end_args);
	
	$divider->displayend($divider_end_args);
	
			// Get Status Language
		
		// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$status_label_text = 'Status';
	}
	else
	{
		$status_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainStatusHeader']);
		
		if($status_language_translations[$this->language_object->getLanguageCode()])
		{
			$status_label_text = $status_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$status_label_text = 'Status';
		}
	}
	
			// Get Status Language
		
		// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$status_content_text = 'Waiting for User';
	}
	else
	{
		$status_content_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainActivityHeader']);
		
		if($status_content_language_translations[$this->language_object->getLanguageCode()])
		{
			$status_content_text = $status_content_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$status_content_text = 'Waiting for User';
		}
	}
	
			// Display Status
		
		// -------------------------------------------------------------
	
	$divider->displaystart($divider_instruction_area_start_args);
	
	$divider->displaystart($divider_instruction_area_text_args);
	
print('<div class="padding-5px"><span class="font-family-tahoma"><b>' . $status_label_text . ' :</b> <span id="status-text">' . $status_content_text . '</span>.</span></div>');
	
	
	$divider->displayend($divider_end_args);
	
	$divider->displayend($divider_end_args);
	
			// Get Input / Output Areas Language
		
		// -------------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$list_one_main_header_text = 'Text to Find Keywords From';
	}
	else
	{
		$list_one_main_header_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainList1Header']);
		
		if($list_one_main_header_language_translations[$this->language_object->getLanguageCode()])
		{
			$list_one_main_header_text = $list_one_main_header_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$list_one_main_header_text = 'Text to Find Keywords From';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$list_two_main_header_text = 'Keywords from Provided Text';
	}
	else
	{
		$list_two_main_header_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainList2Header']);
		
		if($list_two_main_header_language_translations[$this->language_object->getLanguageCode()])
		{
			$list_two_main_header_text = $list_two_main_header_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$list_two_main_header_text = 'Keywords from Provided Text';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$list_one_content_text = 'Type or copy-and-paste your text into this text box.';
	}
	else
	{
		$list_one_main_content_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainList1Content']);
		
		if($list_one_main_content_language_translations[$this->language_object->getLanguageCode()])
		{
			$list_one_content_text = $list_one_main_content_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$list_one_content_text = 'Type or copy-and-paste your text into this text box.';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$list_two_content_text = 'Then your keywords list will appear in this text box.';
	}
	else
	{
		$list_two_main_content_language_translations = $this->getListAndItems(['ListTitle'=>'LanguagesMainList2Content']);
		
		if($list_two_main_content_language_translations[$this->language_object->getLanguageCode()])
		{
			$list_two_content_text = $list_two_main_content_language_translations[$this->language_object->getLanguageCode()];
		}
		else
		{
			$list_two_content_text = 'Then your keywords list will appear in this text box.';
		}
	}
	
			// Display Input / Output Areas
		
		// -------------------------------------------------------------
		
	print('<table class="horizontal-center padding-top-22px" border="0" style="width:80%">');
	
print('<center><h4 class="margin-0px padding-0px font-family-tahoma">' . $list_one_main_header_text . '</h4></center>');
	
	
	$separate_cells_args = [
		'cellwidth'=>'80%',
		'cellvalign'=>'top',
	];
	$table->DisplayComponent_SeparateCells($separate_cells_args);
	
print('<center><h4 class="margin-0px padding-0px font-family-tahoma">' . $list_two_main_header_text . '</h4></center>');
	
	
	$separate_cells_args = [
		'cellwidth'=>'80%',
		'cellvalign'=>'top',
	];
	$table->DisplayComponent_SeparateCellsAndRows($separate_cells_args);
	
	$type_args = [
		'type'=>'textarea',
		'name'=>'input-area',
		'id'=>'input-area',
		'class'=>'input-area',
		'value'=>$list_one_content_text,
		'cols'=>50,
		'rows'=>30,
	];
	
	$form->DisplayFormField($type_args);
	
	$separate_cells_args = [
		'cellwidth'=>'80%',
		'cellvalign'=>'top',
	];
	$table->DisplayComponent_SeparateCells($separate_cells_args);
	
	$type_args = [
		'type'=>'textarea',
		'name'=>'output-area',
		'id'=>'output-area',
		'class'=>'output-area',
		'value'=>$list_two_content_text,
		'cols'=>50,
		'rows'=>30,
	];
	
	$form->DisplayFormField($type_args);
	
	$table_end_args = array(
	);
	$table->DisplayComponent_EndTable($table_end_args);
	
			// Sort Button and Order Columns
		
		// -------------------------------------------------------------
	
				// Start Div
				// -------------------------------------------------------
	
	$divider->displaystart($divider_main_area_start_args);
	
				// Get 'Sort' Button Language
				// -------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$main_button_language_text = 'Find Keywords';
	}
	else
	{
		$main_button_language_text = $this->getListAndItems(['ListTitle'=>'LanguagesMainButtonText']);
		
		if($main_button_language_text[$this->language_object->getLanguageCode()])
		{
			$main_button_language_text = $main_button_language_text[$this->language_object->getLanguageCode()];
		}
		else
		{
			$main_button_language_text = 'Find Keywords';
		}
	}
	
				// Display 'Sort' Button
				// -------------------------------------------------------
	
	$type_args = [
		'type'=>'button',
		'id'=>'find-keywords-button',
		'class'=>'find-keywords-button margin-5px',
		'value'=>$main_button_language_text,
	];
	
	$form->DisplayFormField($type_args);
	
	print('<br>'); 
	
				// Get SortOrder Language
				// -------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$first_feature_option_text = 'Most Common to Least Common';
	}
	else
	{
		$option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainFirstFeatureOptionOneTitle']);
		
		if($option_list[$this->language_object->getLanguageCode()])
		{
			$first_feature_option_text = $option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$first_feature_option_text = 'Most Common to Least Common';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$first_feature_option_mouseover = 'List most common keywords at the top and least common keywords at the bottom.';
	}
	else
	{
		$option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainFirstFeatureOptionOneMouseover']);
		
		if($option_list[$this->language_object->getLanguageCode()])
		{
			$first_feature_option_mouseover = $option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$first_feature_option_mouseover = 'List most common keywords at the top and least common keywords at the bottom.';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$second_feature_option_text = 'Least Common to Most Common';
	}
	else
	{
		$option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainFirstFeatureOptionTwoTitle']);
		
		if($option_list[$this->language_object->getLanguageCode()])
		{
			$second_feature_option_text = $option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$second_feature_option_text = 'Least Common to Most Common';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$second_feature_option_mouseover = 'List least common keywords at the top and most common keywords at the bottom.';
	}
	else
	{
		$option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainFirstFeatureOptionTwoMouseover']);
		
		if($option_list[$this->language_object->getLanguageCode()])
		{
			$second_feature_option_mouseover = $option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$second_feature_option_mouseover = 'List least common keywords at the top and most common keywords at the bottom.';
		}
	}
	
				// Display 'Sort' Order
				// -------------------------------------------------------
	
	$extension_args = [
		'type'=>'select',
		'name'=>'sort-order',
		'id'=>'sort-order',
		'class'=>'sort-order margin-5px',
		'options'=>[
			[
				'optionvalue'=>'most-common-to-least-common',
				'optiontitle'=>$first_feature_option_text,
				'optionmouseover'=>$first_feature_option_mouseover,
			],
			[
				'optionvalue'=>'least-common-to-most-common',
				'optiontitle'=>$second_feature_option_text,
				'optionmouseover'=>$second_feature_option_mouseover,
			],
		],
	];
	
	$form->DisplayFormField($extension_args);
	
	print('<br>');
	
			// Display Strip HTML Option
		
		// -------------------------------------------------------------
	
				// Get Strip HTML Language
				// -------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$strip_html_option_content = 'Strip HTML';
	}
	else
	{
		$strip_html_option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainStripHTMLOption']);
		
		if($strip_html_option_list[$this->language_object->getLanguageCode()])
		{
			$strip_html_option_content = $strip_html_option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$strip_html_option_content = 'Strip HTML';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$strip_html_description_content = 'This option will remove html from each item in the inbound list.';
	}
	else
	{
		$strip_html_description_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainStripHTMLDescription']);
		
		if($strip_html_description_list[$this->language_object->getLanguageCode()])
		{
			$strip_html_description_content = $strip_html_description_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$strip_html_description_content = 'This option will remove html from each item in the inbound list.';
		}
	}
	
print('<span class="font-family-arial" title="' . $strip_html_description_content . '">' . $strip_html_option_content . '</span>');
	
	
	$type_args = [
		'type'=>'checkbox',
		'name'=>'strip-html',
		'id'=>'strip-html',
		'class'=>'strip-html',
		'value'=>'1',
	];
	
	$form->DisplayFormField($type_args);
	
	print('<br>');
	
			// Display Ignore Common Words Option
		
		// -------------------------------------------------------------
	
				// Get Ignore Common Words Language
				// -------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$ignore_common_option_content = 'Ignore Common Words';
	}
	else
	{
		$ignore_common_option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainIgnoreCommonWordsOption']);
		
		if($ignore_common_option_list[$this->language_object->getLanguageCode()])
		{
			$ignore_common_option_content = $ignore_common_option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$ignore_common_option_content = 'Ignore Common Words';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$ignore_common_option_description_content = 'This option will ignore common words from the input list.';
	}
	else
	{
		$ignore_common_description_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainIgnoreCommonWordsDescription']);
		
		if($ignore_common_description_list[$this->language_object->getLanguageCode()])
		{
			$ignore_common_option_description_content = $ignore_common_description_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$ignore_common_option_description_content = 'This option will ignore common words from the input list.';
		}
	}
	
print('<span class="font-family-arial" title="' . $ignore_common_option_description_content . '">' . $ignore_common_option_content . '</span>');
	
	
	$type_args = [
		'type'=>'checkbox',
		'name'=>'ignore-common-words',
		'id'=>'ignore-common-words',
		'class'=>'ignore-common-words',
		'value'=>'1',
		'checked'=>TRUE,
	];
	
	$form->DisplayFormField($type_args);
	
	print('<br>');
	
			// Display Include Phrases Option
		
		// -------------------------------------------------------------
	
				// Get Include Phrases Language
				// -------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$include_phrases_option_content = 'Include Phrases';
	}
	else
	{
		$include_phrases_option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainIncludePhrasesOption']);
		
		if($include_phrases_option_list[$this->language_object->getLanguageCode()])
		{
			$include_phrases_option_content = $include_phrases_option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$include_phrases_option_content = 'Include Phrases';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$include_phrases_option_description_content = 'This option will include phrases from the input list.';
	}
	else
	{
		$include_phrases_description_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainIncludePhrasesDescription']);
		
		if($include_phrases_description_list[$this->language_object->getLanguageCode()])
		{
			$include_phrases_option_description_content = $include_phrases_description_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$include_phrases_option_description_content = 'This option will include phrases from the input list.';
		}
	}
	
print('<span class="font-family-arial" title="' . $include_phrases_option_description_content . '">' . $include_phrases_option_content . '</span>');
	
	
	$type_args = [
		'type'=>'checkbox',
		'name'=>'include-phrases',
		'id'=>'include-phrases',
		'class'=>'include-phrases',
		'value'=>'1',
		'checked'=>TRUE,
	];
	
	$form->DisplayFormField($type_args);
	
	print('<br>');
	
			// Display Show Counts Option
		
		// -------------------------------------------------------------
	
				// Get Show Counts Language
				// -------------------------------------------------------
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$show_counts_option_content = 'Show Counts';
	}
	else
	{
		$show_counts_option_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainShowCountsOption']);
		
		if($show_counts_option_list[$this->language_object->getLanguageCode()])
		{
			$show_counts_option_content = $show_counts_option_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$show_counts_option_content = 'Show Counts';
		}
	}
	
	if($this->language_object->getLanguageCode() == 'en')
	{
		$show_counts_option_description_content = 'This option will show the counts for each keyword.';
	}
	else
	{
		$show_counts_description_list = $this->getListAndItems(['ListTitle'=>'LanguagesMainShowCountsDescription']);
		
		if($show_counts_description_list[$this->language_object->getLanguageCode()])
		{
			$show_counts_option_description_content = $show_counts_description_list[$this->language_object->getLanguageCode()];
		}
		else
		{
			$show_counts_option_description_content = 'This option will show the counts for each keyword.';
		}
	}
	
print('<span class="font-family-arial" title="' . $show_counts_option_description_content . '">' . $show_counts_option_content . '</span>');
	
	
	$type_args = [
		'type'=>'checkbox',
		'name'=>'show-counts',
		'id'=>'show-counts',
		'class'=>'show-counts',
		'value'=>'1',
		'checked'=>TRUE,
	];
	
	$form->DisplayFormField($type_args);
	
				// End Div
				// -------------------------------------------------------
	
	$divider->displayend($divider_end_args);
	
			// Display Similar Sites
		
		// -------------------------------------------------------------
	
	ggreq('modules/html/similarsites-satellites.php');
	
	$similar_site_args = [
		'site'=>$this->domain_object->primary_domain_lowercased,
		'language'=>$this->language_object,
	];
	$similar_sites = new module_similarsites_satellites($similar_site_args);
	
	$similar_sites->display();
	
			// Display Social Media Options
		
		// -------------------------------------------------------------
	
	$social_media_share_links->display();
	
			// Display Language Options
		
		// -------------------------------------------------------------
	
	$languages->display();
	
			// Display Final Ending Navigation
		
		// -------------------------------------------------------------
	
	$bottom_navigation_args = [
		'thispage'=>'Home',
	];
	$navigation->DisplayBottomNavigation($bottom_navigation_args);
	
?>