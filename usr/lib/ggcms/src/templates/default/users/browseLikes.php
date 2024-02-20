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
		
	ggreq('modules/html/entry-header.php');
	ggreq('modules/html/entry-index-header.php');
	$entryindexheader = new module_entryindexheader([
		'that'=>$this,
		'main_text'=>'Browsing Likes by ' . $this->user['Username'],
	]);
	
	$entryindexheader->Display();
	
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
		
		$breadcrumbs_title = '<a href="users.php?action=viewuser';
		if(!$this->Param('userid')) {
			$breadcrumbs_title .= '&user=' . urlencode($this->user['Username']);
		} else {
			$breadcrumbs_title .= '&userid=' . $this->user['id'];
		}
		$breadcrumbs_title .= '">';
		$breadcrumbs_title .= $this->user['Username'];
		$breadcrumbs_title .= '</a>';
		$breadcrumbs_title .= ' &gt;&gt; ';
		$breadcrumbs_title .= 'Browsing Likes';
		
		ggreq('modules/html/breadcrumbs.php');
		$breadcrumbs = new module_breadcrumbs(['that'=>$this, 'title'=>$breadcrumbs_title]);
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
		'title'=>'Browsing : ' . $this->child_record_start_index . ' to ' . $this->child_record_end_index . ' of ' . $this->likes_count ,
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
	
	$end_form_args = [
	];
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
	
	for($i = 1; $i <= $this->total_pages; $i++)
	{
		if($i != $this->page)
		{
			$total_page_urls .= ' <a href="users.php?action=browseLikes&user=' . urlencode($this->user['Username']) . '&page=' . $i . '&perpage=' . $this->perpage . '">';
		}
		
		$total_page_urls .= $i;
		
		if($i != $this->page)
		{
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
	
#	print_r($this->children);
#	print_r($this->comments);
	
	$child_display = $entrysort->Sort(['entries'=>$this->children]);
	
	foreach(array_reverse($child_display) as $entry) {
			print('<div class="horizontal-center width-90percent">');
			print('<div class="horizontal-center width-100percent background-color-gray14 border-2px margin-top-5px">');
			
			unset($display_image);
			
			if($entry['image']) {
				$entry_images = $entry['image'];
				$entry_image_count = count($entry_images);
				if($entry_image_count) {
					shuffle($entry_images);
					$entry_image = $entry_images[0];
					$display_image = $entry_image;
				}
			}
			
			$parent_codes = [];
			foreach($entry['parents'] as $parent) {
				$parent_codes[] = $parent['Code'];
			}
			
			$last_parent = $child['parents'][count($child['parents']) - 2];
			
			$parent_code_url = implode('/', $parent_codes);
			
			if(count($parent_codes) == 1) {
				$extra_action = 'index';
			} else {
				$extra_action = '';
			}
			$new_parent_codes = $parent_codes;
			unset($new_parent_codes[count($new_parent_codes) - 1]);
			$parents_parent_code_url = implode('/', $new_parent_codes);
			
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<div class="height-100px width-100px background-color-gray0">');
			print('<div class="vertical-specialcenter">');
			print('<a href="' . $parent_code_url . '/view.php');
			if($extra_action) {
				print('&action=' . $extra_action);
			}
			print('">');
			print('<img width="');
			print(ceil($display_image['IconPixelWidth'] / 2));
			print('" height="');
			print(ceil($display_image['IconPixelHeight'] / 2));
			print('" src="');
			print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
			print('/image/');
			print(implode('/', str_split($display_image['FileDirectory'])));
			print('/');
			print($display_image['IconFileName']);
			print('">');
			print('</a>');
			print('</div>');
			print('</div>');
			print('</div>');
			print('</div>');
			
			$entry_title = '<a href="';
			$entry_title .= $parent_code_url;
			$entry_title .= '/view.php';
			
			if($extra_action) {
				$entry_title .= '&action=' . $extra_action;
			}
			
			$entry_title .= '">';
			
			if($entry['Title']) {
				$entry_title .= $entry['Title'];
			} else {
				$entry_title .= $entry['ListTitle'];
			}
			
			if(count($parent_codes) > 1) {
				$last_parent = $entry['parents'][count($parent_codes) - 2];
				
				if($last_parent && $last_parent['Title']) {
					$entry_title .= ' (From ' . $last_parent['Title'] . ')';
				}
			}
			
			$entry_title .= '</a>';
			
			$div_mouseover = '';
			
			if($entry['textbody']) {
				$text_bodies = $entry['textbody'];
				$text_body_count = count($text_bodies);
				if($text_body_count) {
					$first_textbody = $text_bodies[0];
					
					$div_mouseover .= number_format($first_textbody['WordCount']) . ' Words / ' . number_format($first_textbody['CharacterCount']) . ' Characters';
				}
			}
			
			$header_secondary_args = [
				'title'=>$entry_title,
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
			
			print('<div class="border-2px background-color-gray15 margin-5px float-right">');
			print('<p class="horizontal-right margin-5px font-family-arial">');
			print('Liked On : ');
			$date_epoch_time = strtotime($entry['LikeDislikeOriginalCreationDate']);
			$full_date = date("F d, Y; H:i:s", $date_epoch_time);
			print($full_date);
			print('</p>');
			print('</div>');
			
			print('<p class="horizontal-left margin-5px font-family-arial">');
			
			$time_frame = '';
			
			if($entry['eventdate']) {
				$entry_event_count = count($entry['eventdate']);
				for($i = 0; $i < $entry_event_count; $i++) {
					$entry_event = $entry['eventdate'][$i];
						
					if($entry_event['Title'] == 'Publication') {
						$publication_event = $entry_event;
					} elseif($entry_event['Title'] == 'Birth Day') {
						$birth_event = $entry_event;
					} elseif($entry_event['Title'] == 'Death Day') {
						$death_event = $entry_event;
					}
					
					if($publication_event || ($birth_event && $death_event)) {
						$i = $entry_event_count;
					}
				}
				
				if($publication_event) {
					if($publication_event['EventDateTime'] != '0000-00-00 00:00:00') {
						$event_date_pieces = explode('-', $publication_event['EventDateTime']);
						$year = $event_date_pieces[0];
						$time_frame .= $year;
					} else {
						$time_frame .= '?';
					}
					
					unset($publication_event);
				} elseif($birth_event || $death_event) {
					$time_frame .= '(';
					
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
						if($death_event['EventDateTime'] != '0000-00-00 00:00:00') {
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
			
			print('<strong>');
			
			if($time_frame) {
				print($time_frame);
			}
			
			if($entry['textbody'] && count($entry['textbody'])) {
				if($time_frame) {
					print(' ~ ');
				}
				
				$first_textbody = $entry['textbody'][0];
				
				print('(');
				print(number_format($first_textbody['WordCount']));
				print(' Words / ');
				print(number_format($first_textbody['CharacterCount']));
				print(' Characters');
				print(')');
			}
			print('</strong> ');
			
			if($entry['description']) {
				$description = $entry['description'][0];
				
				if($description && $description['Description']) {
					print('<em>');
					if($time_frame || $entry['Subtitle']) {
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
			
			if($entry['quote']) {
				$entry_quotes = $entry['quote'];
				$entry_quotes_count = count($entry_quotes);
				$max_limit = $entry_quotes_count;
				if($max_limit > 3) {
					$max_limit = 3;
				}
				shuffle($entry_quotes);
				for($i = 0; $i < $max_limit; $i++) {
					$quote = $entry_quotes[$i];
					if($quote && $quote['Quote']) {
						print(' <br>&bull; ');
						print('"');
						print(str_replace('"', '\'', $quote['Quote']));
						print('"');
						
						if($quote['Source']) {
							$source = $quote['Source'];
							
							if(strlen($source) > 50) {
								$source = substr($source, 0, 50) . '...';
							}
							
							print(' (From : ' . $source . '.)');
						}
					}
				}
			} else {
				$printed = 0;
				if($entry['textbody']) {
					$text_bodies = $entry['textbody'];
					
					$text_body_count = count($text_bodies);
					if($text_body_count) {
						$text_display = $text_bodies[0]['FirstThousandCharacters'];
						if(!$text_display) {
							$text_display = $text_bodies[0]['Text'];
						}
						$text_display = strip_tags($text_display);
						
						if(strlen($text_display) > 750) {
							$text_display = substr($text_display, 0, 750) . '...';
						}
						
						if($text_display) {
							$printed = 1;
							print('<br>');
							print($text_display);
							
							if($text_bodies[0]['Source']) {
								$source = $text_bodies[0]['Source'];
								
								if(strlen($source) > 50) {
									$source = substr($source, 0, 50) . '...';
								}
								
								print(' (From : ' . $source . '.)');
							}
						}
					}
				}
				
				if(!$printed) {
					$grand_children = $entry['children'];
					
					if($grand_children && is_array($grand_children))
					{
						$grand_children_count = count($grand_children);
						
						if($grand_children_count)
						{
							$grand_child_display = $entrysort->Sort(['entries'=>$grand_children]);
							
							unset($grand_child);
							foreach($grand_child_display as $single_grand_child)
							{
								if(!$grand_child)
								{
									$grand_child = $single_grand_child['textbody'][0];
								}
							}
							
							$text_display = $grand_child['FirstThousandCharacters'];
							if(!$text_display)
							{
								$text_display = $grand_child[0]['Text'];
							}
							$text_display = strip_tags($text_display);
							
							if(strlen($text_display) > 750)
							{
								$text_display = substr($text_display, 0, 750) . '...';
							}
							print($text_display);
						}
					}
				}
			}
			
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
			
			if($entry['tag'])
			{
				$tag_count = count($entry['tag']);
				
				if($tag_count)
				{
					$tags = $entry['tag'];
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
						print('<a href="/view.php?action=browseByTag&tag=' . urlencode($tag['Tag']) . '">');
						print($tag['Tag']);
						print(' (');
						print($this->tag_counts[$tag['Tag']]);
						print(')');
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
			print('</div>');
	}
		
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