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
	
	ggreq('modules/html/navigation.php');
	$navigation_args = [
		'globals'=>$this->handler->globals,
		'languageobject'=>$this->language_object,
		'divider'=>$divider,
		'domainobject'=>$this->domain_object,
	];
	$navigation = new module_navigation($navigation_args);
	
	ggreq('modules/html/entry-sort.php');
	$entrysort = new module_entrysort(['that'=>$this]);
	
			// Mouseover Values
		
		// -------------------------------------------------------------
	
	$div_mouseover = '';
	$image_mouseover = '';
	
	if($this->entry['quote'] && $this->entry['quote'][0])
	{
		$random_quote = $this->entry['quote'][array_rand($this->entry['quote'], 1)];
		
		$div_mouseover = '&quot;' . str_replace('"', '\'', $random_quote['Quote']) . '&quot; -- ' . str_replace('"', '\'', $random_quote['Source']);
	}
	
	if($this->entry['description'] && $this->entry['description'][0])
	{
		$description = $this->entry['description'][0];
		
		$image_mouseover = str_replace('"', '\'', $description['Description']);
	}
	
			// Display Header
		
		// -------------------------------------------------------------
	
	$primary_image = implode('/', str_split($this->entry['image'][0]['FileDirectory'])) . '/' . $this->entry['image'][0]['IconFileName'];
	$header_primary_args = [
		'title'=>'Browsing ' . $this->header_title_text,
		'image'=>$primary_image,
		'divmouseover'=>$div_mouseover,
		'imagemouseover'=>$image_mouseover,
		'level'=>1,
		'divclass'=>'horizontal-center width-100percent border-2px margin-top-5px background-color-gray13',
		'textclass'=>'padding-0px margin-0px horizontal-center vertical-center padding-top-22px',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>1,
	];
	
	$header->display($header_primary_args);
	
			// Basic Divider Arguments
		
		// -------------------------------------------------------------
	
	$divider_one_third_start_args = [
		'class'=>'width-33percent float-left',
	];
	
	$divider_case_start_args = [
		'class'=>'width-100percent height-400px overflow-auto',
	];
	
	$divider_frame_start_args = [
		'class'=>'width-100percent border-2px',
	];
	
	$divider_padding_start_args = [
		'class'=>'margin-5px padding-5px',
	];
	
	$divider_end_args = [
	];
		
				// Start Top Bar
			
			// -------------------------------------------------------------
		
		print('<div class="horizontal-center width-95percent margin-top-5px">');
		
				// Breadcrumbs Info
			
			// -------------------------------------------------------------
		
		ggreq('modules/html/breadcrumbs.php');
		$breadcrumbs = new module_breadcrumbs(['that'=>$this, 'title'=>'Browsing']);
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
	
			// View Browsing Numbers
		
		// -------------------------------------------------------------
	
	$header_secondary_args = [
		'title'=>'Browsing : ' . $this->child_record_start_index . ' to ' . $this->child_record_end_index . ' of ' . $this->children_count ,
		'imagemouseover'=>$this->total_pages . ' Total Pages Available',
		'divmouseover'=>$this->total_children_viewed . ' Items Viewed, ' . $this->total_children_left . ' Remaining to Be Viewed',
		'level'=>3,
		'divclass'=>'width-33percent border-2px background-color-gray13 margin-5px float-left',
		'textclass'=>'font-family-arial padding-0px margin-5px horizontal-center vertical-center',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>0,
		'rightimageenable'=>0,
	];
	$header->display($header_secondary_args);
	
			// View Browsing Per-Page Setting
		
		// -------------------------------------------------------------
	
	$start_form_args = [
		'action'=>0,
		'method'=>'post',
		'files'=>1,
		'formclass'=>'margin-0px',
	];
	
	$form->StartForm($start_form_args);
	
	$browsing_options = 'Results Per Page : <select id="perpage" name="perpage">';
	
	for($i = 10; $i <= 200; $i += 10)
	{
		$browsing_options .= '<option value="' . $i . '"';
		
		if($i == $this->perpage && !$this->custom_per_page_selected)
		{
			$browsing_options .= ' SELECTED="SELECTED"';
		}
		
		$browsing_options .= '>' . $i . '</option>';
	}
	
	$browsing_options .= '<option value="custom"';
	if($this->custom_per_page_selected)
	{
		$browsing_options .= ' SELECTED="SELECTED"';
	}
	$browsing_options .= '>Custom</option>';
	$browsing_options .= '</select> ';
	$browsing_options .= '<input id="CustomPerPage" name="CustomPerPage" type="text" size="5" value="' . $this->perpage . '"> ';
	$browsing_options .= '<input type="submit" value="Update"> ';
	$browsing_options .= '<input type="hidden" name="page" value="1">';
	
	$header_secondary_args = [
		'title'=>$browsing_options,
		'divmouseover'=>'Adjust results per page here.',
		'level'=>3,
		'divclass'=>'width-33percent border-2px background-color-gray13 margin-5px float-right',
		'textclass'=>'font-family-arial padding-0px margin-5px horizontal-center vertical-center',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>0,
		'rightimageenable'=>0,
	];
	$header->display($header_secondary_args);
	
	$end_form_args = [];
	$form->EndForm($end_form_args);
	
			// Finish Top Two Displays
		
		// -------------------------------------------------------------
							
	$clear_float_divider_start_args = [
		'class'=>'clear-float',
	];
	
	$divider->displaystart($clear_float_divider_start_args);
	
	$clear_float_divider_end_args = [
	];
	
	$divider->displayend($clear_float_divider_end_args);
	
			// View Selected Record List Pages
		
		// -------------------------------------------------------------
	
	$total_page_urls = '';
	
	for($i = 1; $i <= $this->total_pages; $i++) {
		if($i != $this->page) {
			$total_page_urls .= ' <a href="view.php?action=browse&page=' . $i . '&perpage=' . $this->perpage . '">';
		}
		
		$total_page_urls .= $i;
		
		if($i != $this->page) {
			$total_page_urls .= '</a>' . ' ';
		}
	}
	
	print('<div class="horizontal-center width-95percent">');
	
	$header_secondary_args = [
		'title'=>$total_page_urls,
		'divmouseover'=>'Navigate to another page of results by using these links.',
		'level'=>3,
		'divclass'=>'width-100percent border-2px background-color-gray13',
		'textclass'=>'padding-0px margin-5px horizontal-center vertical-center font-family-arial',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>0,
		'rightimageenable'=>0,
	];
	$header->display($header_secondary_args);
	
	print('</div>');
	
			// View Selected Record List Pages
		
		// -------------------------------------------------------------
	
	$child_display = $entrysort->Sort(['entries'=>$this->children]);
	
	print('<div class="horizontal-center width-90percent">');
	
	foreach($child_display as $child) {
		print('<div class="horizontal-center width-100percent background-color-gray14 border-2px margin-top-5px">');
		
		unset($display_image);
		
		if($child['image']) {
			$child_images = $child['image'];
			$child_image_count = count($child_images);
			if($child_image_count) {
				shuffle($child_images);
				$child_image = $child_images[0];
				$display_image = $child_image;
			}
		}
		
		$linkable_url = '';
		$non_linkable_url = '';
		$other_linkable_url = '';
		
		foreach($child['link'] as $link) {
			if(!$link['Title']) {
				if($non_linkable_url) {
					$non_linkable_url .= ' ~ ';
				}
				$non_linkable_url .= $link['URL'];
			} elseif($link['Title'] == 'Archives') {
				$other_linkable_url = $link['URL'];
			} elseif($link['Title'] == 'Archive') {
				$linkable_url = $link['URL'];
			}
		}
		
		$child_title = '';
		
		if($linkable_url) {
			$child_title .= '<a href="' . $linkable_url . '" target="_blank">';
		}
		
		$child_title .= $child['Title'];
		
		if($linkable_url) {
			$child_title .= '</a>';
		}
		
		if($child['textbody']) {
			$text_bodies = $child['textbody'];
			
			$text_body_count = count($text_bodies);
			if($text_body_count) {
				$first_textbody = $text_bodies[0];
				
				$div_mouseover .= number_format($first_textbody['WordCount']) . ' Words / ' . number_format($first_textbody['CharacterCount']) . ' Characters';
			}
		}
		
		$header_secondary_args = [
			'title'=>$child_title,
			'divmouseover'=>$div_mouseover,
			'level'=>2,
			'divclass'=>'border-2px background-color-gray15 margin-5px float-left',
			'textclass'=>'padding-0px margin-5px horizontal-left font-family-tahoma',
			'imagedivclass'=>'border-2px margin-5px background-color-gray10',
			'imageclass'=>'border-1px',
			'domainobject'=>$this->domain_object,
			'leftimageenable'=>0,
			'rightimageenable'=>0,
		];
		$header->display($header_secondary_args);
		
		print('<p class="horizontal-left margin-5px font-family-arial">');
		
		if($this->authentication_object->user_session['UserAdmin.id']) {
			print(' <a href="' . $child['Code'] . '/modify.php?action=Edit"><b>EDIT</b></a> ');
		}
		
		$time_frame = '';
		
		if($child['eventdate']) {
			$child_event_count = count($child['eventdate']);
			for($i = 0; $i < $child_event_count; $i++) {
				$child_event = $child['eventdate'][$i];
				
				if($child_event['Title'] == 'Birth Day') {
					$birth_event = $child_event;
				} elseif($child_event['Title'] == 'Death Day') {
					$death_event = $child_event;
				}
				
				if($birth_event && $death_event) {
					$i = $child_event_count;
				}
			}
			
			if($birth_event || $death_event) {
				$time_frame .= ' (';
				
				if($birth_event && $birth_event['id']) {
					if($birth_event['EventDateTime'] != '0000-00-00 00:00:00') {
						$birth_event_date_pieces = explode('-', $birth_event['EventDateTime']);
						$birth_year = $birth_event_date_pieces[0];
						$time_frame .= $birth_year;
					} else {
						$time_frame .= '?';
					}
				}
				
				$time_frame .= ' - ';
				
				if($death_event && $death_event['id']) {
					if($death_event['EventDateTime'] !== '0000-00-00 00:00:00') {
						$death_event_date_pieces = explode('-', $death_event['EventDateTime']);
						$death_year = $death_event_date_pieces[0];
						$time_frame .= $death_year;
					} else {
						$time_frame .= '?';
					}
				}
				
				$time_frame .= ') ';
				
				unset($birth_event);
				unset($death_event);
			}
		}
		
		if($time_frame) {
			print($time_frame);
		}
		
		if($child['Subtitle']) {
			if($time_frame) {
				print(' ~ ');
			}
			
			print('<strong>');
			print($child['Subtitle']);
			print('</strong>');
		}
		
		if($child['description']) {
			$description = $child['description'][0];
			
			if($description && $description['Description']) {
				print('<em>');
				if($time_frame || $child['Subtitle']) {
					print(' : ');
				}
				
				print($description['Description']);
				print(' ');
				print('</em>');
				
				if($description['Source']) {
					$source = $description['Source'];
					
					if(strlen($source) > 50) {
						$source = substr($source, 0, 50) . '...';
					}
					
					print(' (From : ' . $source . '.)');
				}
			}
		}
		
		if($other_linkable_url) {
			print(' ');
			print('<a href="' . $other_linkable_url . '" target="_blank">');
			print('<strong>(Other Sources)</strong>');
			print('</a>');
			print(' ~ ');
		}
		
		if($child['quote']) {
			$child_quotes = $child['quote'];
			$child_quotes_count = count($child_quotes);
			$max_limit = $child_quotes_count;
			if($max_limit > 3)
			{
				$max_limit = 3;
			}
			shuffle($child_quotes);
			for($i = 0; $i < $max_limit; $i++)
			{
				$quote = $child_quotes[$i];
				if($quote && $quote['Quote'])
				{
					print('<em>');
					
					print('"');
					print(str_replace('"', '\'', $quote['Quote']));
					print('"');
					
					if($quote['Source'])
					{
						$source = $quote['Source'];
						
						if(strlen($source) > 50)
						{
							$source = substr($source, 0, 50) . '...';
						}
						
						print(' (From : ' . $source . '.)');
					}
					
					print('</em>');
				}
			}
		}
		else
		{
			if($child['textbody'])
			{
				$text_bodies = $child['textbody'];
				
				$text_body_count = count($text_bodies);
				if($text_body_count)
				{
					$text_display = $text_bodies[0]['FirstThousandCharacters'];
					
					$text_display = strip_tags($text_display);
					
					if(strlen($text_display) > 750)
					{
						$text_display = substr($text_display, 0, 750) . '...';
					}
					
					if($text_display)
					{
						print('<br>');
						print($text_display);
						
						if($text_bodies[0]['Source'])
						{
							$source = $text_bodies[0]['Source'];
							
							if(strlen($source) > 50)
							{
								$source = substr($source, 0, 50) . '...';
							}
							
							print(' (From : ' . $source . '.)');
						}
					}
				}
			}
		}
			
			print('<div class="float-right font-family-courier word-break-break-all margin-left-20px margin-right-20px">');
			print($non_linkable_url);
			print('</div>');
		
		print('</p>');
		
				// Finish Float
			
			// -------------------------------------------------------------
								
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
		
				// Tags
			
			// -------------------------------------------------------------
		
		if($child['tag'])
		{
			$tag_count = count($child['tag']);
			
			if($tag_count)
			{
				$tags = $child['tag'];
				$max_limit = $tag_count;
				if($max_limit > 10)
				{
					$max_limit = 10;
				}
				shuffle($tags);
				
				for($i = 0; $i < $max_limit; $i++)
				{
					$tag = $tags[$i];
					print('<div class="border-2px background-color-gray15 margin-left-5px margin-bottom-5px float-left">');
					print('<span class="horizontal-left margin-5px font-family-arial">');
					print('<a href="view.php?action=browseByTag&tag=' . urlencode($tag['Tag']) . '">');
					print($tag['Tag']);
					print('</a>');
					print('</span>');
					print('</div>');
				}
						// Finish Float
					
					// -------------------------------------------------------------
										
				$clear_float_divider_start_args = [
					'class'=>'clear-float',
				];
				
				$divider->displaystart($clear_float_divider_start_args);
				
				$clear_float_divider_end_args = [
				];
				
				$divider->displayend($clear_float_divider_end_args);
			}
		}
		
		print('</div>');
	}
	
	print('</div>');
	
			// View Selected Record List Pages
		
		// -------------------------------------------------------------
	
	print('<div class="horizontal-center width-95percent">');
	
	$header_secondary_args = [
		'title'=>$total_page_urls,
		'divmouseover'=>'Navigate to another page of results by using these links.',
		'level'=>3,
		'divclass'=>'width-100percent border-2px background-color-gray13 margin-top-5px',
		'textclass'=>'padding-0px margin-5px horizontal-center vertical-center font-family-arial',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>0,
		'rightimageenable'=>0,
	];
	$header->display($header_secondary_args);
	
	print('</div>');
			// Display Final Ending Navigation
		
		// -------------------------------------------------------------
	
	$bottom_navigation_args = [
		'thispage'=>'',
	];
	$navigation->DisplayBottomNavigation($bottom_navigation_args);
	
	/*
	print("BT: view.php script, display.php template<BR><BR>");
	print("<PRE>RECORD LIST:");
	print_r($this->record_list);
	print("\n\nMASTER RECORD:\n\n");
	print_r($this->master_record);
	print("\n\nPARENT:\n\n");
	print_r($this->parent);
	print("\n\nENTRY:\n\n");
	print_r($this->entry);
	print("\n\nCHILDREN:\n\n");
	print_r($this->children);
	print("</PRE>");
	*/
	
?>