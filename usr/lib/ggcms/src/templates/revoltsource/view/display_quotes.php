<?php
	
			// Format-Universal Formatting
		
		// -------------------------------------------------------------
		
				// Child Record Counts
			
			// -------------------------------------------------------------
		
	$image_count = $this->counts['image'];
	$tag_count = $this->counts['tag'];
	$description_count = $this->counts['description'];
	$quote_count = $this->counts['quote'];
	$textbody_count = $this->counts['textbody'];
	$association_count = $this->counts['association'];
	$eventdate_count = $this->counts['eventdate'];
	$link_count = $this->counts['link'];
	$definition_count = $this->counts['definition'];
	$children_count = $this->counts['children'];
	
	$younger_sibling_count = $this->counts['younger_sibling'];
	$older_sibling_count = $this->counts['older_sibling'];
	
	ggreq('modules/spacing.php');
	
	ggreq('modules/html/entry-sort.php');
	$entrysort = new module_entrysort(['that'=>$this]);
		
				// Timeframe
			
			// -------------------------------------------------------------
	
	if($this->entry['eventdate'])
	{
		$entry_event_count = count($this->entry['eventdate']);
		for($i = 0; $i < $entry_event_count; $i++)
		{
			$entry_event = $this->entry['eventdate'][$i];
			
			if($entry_event['Title'] == 'Publication')
			{
				$publication_event = $entry_event;
			}
			
			if($publication_event)
			{
				$i = $entry_event_count;
			}
		}
		if($publication_event)
		{
			if($publication_event['EventDateTime'] != '0000-00-00 00:00:00')
			{
				$event_date_pieces = explode('-', $publication_event['EventDateTime']);
				$year = $event_date_pieces[0];
				$time_frame .= $year;
			}
			else
			{
				$time_frame .= '?';
			}
		}
	}
		
				// Simple Formats
			
			// -------------------------------------------------------------
			
	if(	($this->script_format_lower == 'pdf') ||
		($this->script_format_lower == 'rtf') ||
		($this->script_format_lower == 'epub') ||
		($this->script_format_lower == 'daisy') ||
		($this->script_format_lower == 'sgml') ||
		($this->script_format_lower == 'tex') ||
		($this->script_format_lower == 'html' && $this->Param('printerfriendly')) ||
		($this->script_format_lower == 'html' && $this->Param('invertedcolors'))
	)
	{	
		$html_document = '';
		$html_document .= '<h1>';
		$html_document .= $this->entry['Title'];
		$html_document .= '</h1>';
		
		$html_document .= "\n";
		
		if($this->entry['Subtitle'])
		{
			$html_document .= '<h2>';
			$html_document .= $this->entry['Subtitle'];
			$html_document .= '</h2>';
			$html_document .= "\n";
		}
		
		if($time_frame)
		{
			$html_document .= '<p>';
			$html_document .= $time_frame;
			$html_document .= '</p>';
			$html_document .= "\n";
		}
		
		if($this->entry['association'] && $association_count)
		{
			$html_document .= '<p><b>People :</b></p>';
			$html_document .= "\n";

			$associations = $this->entry['association'];
			
			for($i = 0; $i < $association_count; $i++)
			{
				$association = $associations[$i];
				$child = $association['entry'];
				
				$html_document .= '<p>';
				$html_document .= $association['SubType'];
				$html_document .= ' : ';
				$html_document .= $child['Title'];
				$html_document .= '</p>';
			}
			$html_document .= "\n";
		}
		
		if($this->entry['description'] && $description_count)
		{
			$description = $this->entry['description'][0];
			$html_document .= '<p><b>Description :</b> ';
			$html_document .= $description['Description'];
			
			if($description['Source'])
			{
				$html_document .= ' (From : ';
				$html_document .= $description['Source'];
				$html_document .= ')';
			}
			
			$html_document .= '</p>';
			$html_document .= "\n";
		}
		
		if($this->entry['tag'] && $tag_count)
		{
			$html_document .= '<p><b>Tags :</b> ';
			
			$tags = $this->entry['tag'];
			$display_tags = [];
			
			$tag_max = $tag_count;
			if($tag_max > 10) {
				$tag_max = 10;
			}
			
			for($i = 0; $i < $tag_max; $i++)
			{
				$tag = $tags[$i];
				
				$display_tags[] = $tag['Tag'];
			}
			
			$html_document .= implode(', ', $display_tags);
			
			$html_document .= '.</p>';
			$html_document .= "\n";
		}
		
		if($this->entry['quote'] && $quote_count)
		{
			$html_document .= '<p><b>Quotes :</b></p>';
			$html_document .= "\n";
			
			$quotes = $this->entry['quote'];
			
			for($i = 0; $i < $quote_count; $i++)
			{
				$quote = $quotes[$i];
				
				$html_document .= '<blockquote><i>"';
				$html_document .= str_replace('"', '\'', $quote['Quote']);
				$html_document .= '"</i>';
				
				if($quote['Source'])
				{
					$html_document .= ' (From : ';
					$html_document .= $quote['Source'];
					$html_document .= '.)';
				}
				
				$html_document .= '</blockquote>';
				$html_document .= "\n";
			}
		}
		
		if($this->entry['textbody'] && $textbody_count)
		{
			$html_document .= '<p><b>Text :</b></p>';
			$html_document .= "\n";
			
			$textbodies = $this->entry['textbody'];
			
			for($i = 0; $i < $textbody_count; $i++)
			{
				$textbody = $textbodies[$i];
				
				$text_formatted = $textbody['Text'];
				$text_formatted = preg_replace("/<img[^>]+\>/i", " ", $text_formatted); 
				
				$html_document .= $text_formatted;
				$html_document .= "\n";
				
				if($textbody['Source'])
				{
					$html_document .= '<p>From : ';
					$html_document .= $textbody['Source'];
					$html_document .= '.</p>';
					$html_document .= "\n";
				}
			}
		}
		
		if($this->children && $children_count)
		{
			$this->SetFullChildRecords();
			$children_count = count($this->children);
			
			if($children_count)
			{
				$html_document .= '<p><b>Sections (TOC) :</b></p>';
				$html_document .= "\n";
				
				$child_display = $entrysort->Sort(['entries'=>$this->children]);
				
				foreach($child_display as $child)
				{
					$html_document .= '    <p>&bull; ' . $child['Title'];
					
					if($child['Subtitle'])
					{
						$html_document .= ' : ' . $child['Subtitle'];
					}
					
					if($child['textbody'])
					{
						$html_document .= '<br>';
						$html_document .= "\n";
						$html_document .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						$html_document .= number_format($child['textbody'][0]['WordCount']) . ' Words; ';
						$html_document .= number_format($child['textbody'][0]['CharacterCount']) . ' Characters';
					}
					
					$html_document .= "</p>";
				}
				
				$html_document .= '<p><b>Sections (Content) :</b></p>';
				$html_document .= "\n";
				
				foreach($child_display as $child)
				{
					$html_document .= '<p>&bull; ' . $child['Title'] . "";
					
					if($child['Subtitle'])
					{
						$html_document .= ' : ' . $child['Subtitle'];
					}
					
					$html_document .= '</p>';
					
					$html_document .= $this->formatImageText([
						'text'=>$child['textbody'][0]['Text'],
						'images'=>$child['image'],
					]);
				}
			}
		}
		
		if($this->entry['eventdate'] && $eventdate_count)
		{
			$event_dates = $this->entry['eventdate'];
			
			$html_document .= '<p><b>Chronology :</b></p>';
			$html_document .= "\n";
			
			for($i = 0; $i < $eventdate_count; $i++)
			{
				$event_date = $event_dates[$i];
				
				$date_epoch_time = strtotime($event_date['EventDateTime']);
				
				$html_document .= '<blockquote><b>';
				
				$html_document .= date("F d, Y", $date_epoch_time);
				$html_document .= ' :</b> ';
				
				$html_document .= $this->entry['Title'];
				$html_document .= ' -- ';
				$html_document .= $event_date['Title'];
				
				$html_document .= '.';
				$html_document .= '</blockquote>';
				
				$html_document .= "\n";
			}
		}
		
		if($this->entry['link'] && $link_count)
		{
			$links = $this->entry['link'];
			
			$html_document .= '<p><b>Links :</b></p>';
			$html_document .= "\n";
			
			
			for($i = 0; $i < $link_count; $i++)
			{
				$link = $links[$i];
				
				$html_document .= '<blockquote> &bull; <b>';
				$html_document .= $link['Title'];
				$html_document .= '</b>';
				$html_document .= '<br>';
				$html_document .= '<a href="';
				$html_document .= $link['URL'];
				$html_document .= '">';
				$html_document .= $link['URL'];
				$html_document .= '</a>';
				$html_document .= '</blockquote>';
				$html_document .= "\n";
			}
		}
		
		$html_document .= '<p>';
		
		if($this->script_format_lower == 'pdf')
		{
			$html_document .= 'PDF';
		}
		elseif($this->script_format_lower == 'html')
		{
			$html_document .= 'HTML';
		}
		
		$html_document .= ' file generated from : ';
		$html_document .= '</p>';
		$html_document .= "\n";
		
		$html_document .= '<blockquote><b>';
		$html_document .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>0, 'www'=>1]) . '/';
		$html_document .= '</b></blockquote>';
	}
	
			// TXT Format
		
		// -------------------------------------------------------------

	if($this->script_format_lower == 'txt')
	{
		$standard_indent = '     ';
		$standard_header_underscore = '----------------------------------';
		$text_document = $this->entry['Title'];
		
		if($this->entry['Subtitle'])
		{
			$text_document .= ' : ';
			$text_document .= "\n";
			$text_document .= $this->entry['Subtitle'];
		}
		
		$text_document .= "\n";
		$text_document .= $standard_header_underscore . $standard_header_underscore;
		
		$text_document .= "\n\n";
		
		if($time_frame)
		{
			$text_document .= $time_frame;
			$text_document .= "\n\n";
		}
		
		if($this->entry['association'] && $association_count)
		{
			$text_document .= 'People :';
			$text_document .= "\n";
			$text_document .= $standard_header_underscore;
			$text_document .= "\n\n";

			$associations = $this->entry['association'];
			
			for($i = 0; $i < $association_count; $i++)
			{
				$association = $associations[$i];
				$child = $association['entry'];
				
				$text_document .= $association['SubType'];
				$text_document .= ' : ';
				$text_document .= $child['Title'];
				$text_document .= "\n\n";
			}
		}
		
		if($this->entry['description'] && $description_count)
		{
			$description = $this->entry['description'][0];
			$text_document .= "Description :";
			$text_document .= "\n";
			$text_document .= $standard_header_underscore;
			$text_document .= "\n\n";
			
			$text_document .= $description['Description'];
			$text_document .= "\n\n";
			
			if($description['Source'])
			{
				$text_document .= $standard_indent;
				$text_document .= 'From : ';
				$text_document .= $description['Source'];
				$text_document .= "\n\n";
			}
		}
		
		if($this->entry['tag'] && $tag_count)
		{
			$text_document .= 'Tags :';
			$text_document .= "\n";
			$text_document .= $standard_header_underscore;
			$text_document .= "\n\n";
			
			$tags = $this->entry['tag'];
			$tags_to_display = [];
			
			$tag_max = $tag_count;
			if($tag_max > 10) {
				$tag_max = 10;
			}
			
			for($i = 0; $i < $tag_max; $i++)
			{
				$tag = $tags[$i];
				$tags_to_display[] = $tag['Tag'];
			}
			
			$text_document .= implode(', ', $tags_to_display);
			
			$text_document .= '.';
			$text_document .= "\n\n";
		}
		
		if($this->entry['quote'] && $quote_count)
		{
			$text_document .= 'Quotes :';
			$text_document .= "\n";
			$text_document .= $standard_header_underscore;
			$text_document .= "\n\n";
			
			$quotes = $this->entry['quote'];
			
			for($i = 0; $i < $quote_count; $i++)
			{
				$quote = $quotes[$i];
				
				$text_document .= '"';
				$text_document .= str_replace('"', '\'', $quote['Quote']);
				$text_document .= '"';
				$text_document .= "\n\n";
				
				if($quote['Source'])
				{
					$text_document .= $standard_indent;
					$text_document .= 'From : ' . $quote['Source'];
					$text_document .= "\n\n";
				}
			}
		}
		
		if($this->entry['textbody'] && $textbody_count)
		{
			$text_document .= 'Text :';
			$text_document .= "\n";
			$text_document .= $standard_header_underscore;
			$text_document .= "\n\n";
			
			$text_body = $this->entry['textbody'][0];
			
			$text_document .= html_entity_decode(strip_tags($text_body['Text']));
			$text_document .= "\n\n";
			
			if($text_body['Source'])
			{
				$text_document .= $standard_indent;
				$text_document .= 'From : ' . $text_body['Source'];
				$text_document .= "\n\n";
			}
		}
		
		if($this->children && $children_count)
		{
			$this->SetFullChildRecords();
			$children_count = count($this->children);
			
			if($children_count)
			{
				$text_document .= 'Sections (TOC) :';
				$text_document .= "\n";
				$text_document .= $standard_header_underscore;
				$text_document .= "\n\n";
				
				$child_display = $entrysort->Sort(['entries'=>$this->children]);
				
				foreach($child_display as $child)
				{
					$text_document .= '    * ' . $child['Title'];
					
					if($child['Subtitle'])
					{
						$text_document .= ' : ' . $child['Subtitle'];
					}
					
					if($child['textbody'])
					{
						$text_document .= "\n";
						$text_document .= '        ';
						$text_document .= number_format($child['textbody'][0]['WordCount']) . ' Words; ';
						$text_document .= number_format($child['textbody'][0]['CharacterCount']) . ' Characters';
					}
					
					$text_document .= "\n\n";
				}
				
				$text_document .= 'Sections (Content) :';
				$text_document .= "\n";
				$text_document .= $standard_header_underscore;
				$text_document .= "\n\n";
				
				foreach($child_display as $child)
				{
					$text_document .= '* ' . $child['Title'];
					
					if($child['Subtitle'])
					{
						$text_document .= ' : ' . $child['Subtitle'];
					}
					
					$text_document .= "\n\n";
					
					$text_document .= strip_tags($child['textbody'][0]['Text']);
					
					$text_document .= "\n\n";
				}
			}
		}
		
		if($this->entry['eventdate'] && $eventdate_count)
		{
			$event_dates = $this->entry['eventdate'];
			
			$text_document .= 'Events :';
			$text_document .= "\n";
			$text_document .= $standard_header_underscore;
			
			for($i = 0; $i < $eventdate_count; $i++)
			{
				$text_document .= "\n\n";
				$text_document .= $standard_indent;
				$text_document .= $this->entry['Title'];
				$text_document .= ' -- ';
				$event_date = $event_dates[$i];
				$text_document .= $event_date['Title'];
				$text_document .= ' : ';
				$date_epoch_time = strtotime($event_date['EventDateTime']);
				$text_document .= date("F d, Y", $date_epoch_time);
			}
			
			$text_document .= "\n\n";
		}
		
		if($this->entry['link'] && $link_count)
		{
			$links = $this->entry['link'];
			
			$text_document .= 'Links :';
			$text_document .= "\n";
			$text_document .= $standard_header_underscore;
			
			for($i = 0; $i < $link_count; $i++)
			{
				$link = $links[$i];
				
				$text_document .= "\n\n";
				$text_document .= $standard_indent;
				$text_document .= $link['Title'];
				$text_document .= ' --';
				$text_document .= "\n";
				$text_document .= $link['URL'];
			}
			
			$text_document .= "\n\n";
		}
		
		$text_document .= 'About This Textfile :';
		$text_document .= "\n";
		$text_document .= $standard_header_underscore;
		$text_document .= "\n\n";
		
		$text_document .= $standard_indent;
		$text_document .= 'Text file generated from : ';
		$text_document .= "\n";
		$text_document .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>0, 'www'=>1]) . '/';
		
		if($this->Param('wrapped'))
		{
			$text_document = wordwrap($text_document, 75, "\n", FALSE);
		}
		
		print($text_document);
	}
	
			// PDF Format
		
		// -------------------------------------------------------------
			
	if ($this->script_format_lower == 'pdf')
	{
		$this->source_content = $html_document;
	}
	
			// RTF Format
		
		// -------------------------------------------------------------
			
	if ($this->script_format_lower == 'rtf')
	{
		$this->source_content = $html_document;
	}
	
			// EPub Format
		
		// -------------------------------------------------------------
			
	if ($this->script_format_lower == 'epub')
	{
		$this->source_content = $html_document;
	}
	
			// DAISY Format
		
		// -------------------------------------------------------------
			
	if ($this->script_format_lower == 'daisy')
	{
		$this->source_content = $html_document;
	}
	
			// SGML Format
		
		// -------------------------------------------------------------
			
	if ($this->script_format_lower == 'sgml')
	{
		$this->source_content = $html_document;
	}
	
			// XML Format
		
		// -------------------------------------------------------------
			
	if ($this->script_format_lower == 'xml')
	{
		$this->source_content = $this->SetRecordToUseForMetadata();
	}
	
			// TEX Format
		
		// -------------------------------------------------------------
			
	if ($this->script_format_lower == 'tex')
	{
		$this->source_content = $html_document;
	}
	
			// Printer-Friendly HTML Format
		
		// -------------------------------------------------------------

	if($this->script_format_lower == 'html' && $this->Param('printerfriendly'))
	{
		print('<div class="font-family-arial">');
		
		print($html_document);
		
		print('</div>');
	}
	
			// Inverted-Colors HTML Format
		
		// -------------------------------------------------------------

	elseif($this->script_format_lower == 'html' && $this->Param('invertedcolors'))
	{
		print('<div class="font-family-arial">');
		
		print($html_document);
		
		print('</div>');
	}
	
			// HTML Format
		
		// -------------------------------------------------------------
			
	elseif ($this->script_format_lower == 'html')
	{
				// Standard Requires
			
			// -------------------------------------------------------------
		
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
		
				// Share Package
			
			// -------------------------------------------------------------
		
		ggreq('modules/html/socialmediasharelinks.php');
		$social_media_share_links_args = [
			'globals'=>$this->handler->globals,
			'textonly'=>$this->mobile_friendly,
			'languageobject'=>$this->language_object,
			'divider'=>$divider,
			'domainobject'=>$this->domain_object,
			'socialmedia'=>$this->social_media,
			'sharewithtext'=>$this->share_with_text,
			'socialmediasharelinkargs'=>[
				'url'=>$this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]) . '/?id=' . $this->entry['assignment'][0]['id'],
				'title'=>$this->header_title_text,
				'desc'=>$instructions_content_text,
				'provider'=>$this->domain_object->primary_domain_lowercased,
			],
		];
		$social_media_share_links = new module_socialmediasharelinks($social_media_share_links_args);
		
				// Display Header
			
			// -------------------------------------------------------------
		
		$primary_image = false;
		
		if(!$this->mobile_friendly)
		{
			$width_attribute = '';
			$vertical_attribute = '';
			
			if($this->entry)
			{
				$image_count = $this->entry['image'] ? count($this->entry['image']) : 0;
				
				if($image_count)
				{
					$primary_image = implode('/', str_split($this->entry['image'][0]['FileDirectory'])) . '/' . $this->entry['image'][0]['IconFileName'];
					$primary_image_text = $this->entry['image'][0]['Title'];
					$width_attribute = ' width-200px height-200px';
					$vertical_attribute = ' vertical-specialcenter';
				} else {
					if($this->entry['association'] && $association_count) {
						$associations = $this->entry['association'];
						
						for($i = 0; $i < $association_count; $i++)
						{
							$association = $associations[$i];
							
							$child = $association['entry'];
							
							if($child['image'])
							{
								$child_images = $child['image'];
								$child_image_count = count($child_images);
								if($child_image_count)
								{
									$primary_image = implode('/', str_split($child['image'][0]['FileDirectory'])) . '/' . $child['image'][0]['IconFileName'];
									$primary_image_text = $this->child['image'][0]['Title'];
									$width_attribute = ' width-200px height-200px';
									$vertical_attribute = ' vertical-specialcenter';
								}
							}
						}
					}
				}
			}
			
					// Mouseover Values
				
				// -------------------------------------------------------------
			
			$div_mouseover = '';
			
			if($this->entry['quote'] && $this->entry['quote'][0])
			{
				$random_quote = $this->entry['quote'][array_rand($this->entry['quote'], 1)];
				
				$div_mouseover = '&quot;' . str_replace('"', '\'', $random_quote['Quote']) . '&quot; -- ' . str_replace('"', '\'', $random_quote['Source']);
			}
		}
	if($this->entry && $this->entry['image'] && $this->entry['image'][0] && $this->entry['image'][0]['FileDirectory']) {
		$image = $this->entry['image'][0];
	} elseif($this->record_list[0]['image']) {
		$image = $this->record_list[0]['image'][0];
	}
	
	$primary_image = implode('/', str_split($image['FileDirectory'])) . '/' . $image['IconFileName'];
			
	$header_primary_args = [
		'title'=>$this->header_title_text,
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
		
				// Admin Controls
			
			// -------------------------------------------------------------
		
		if($this->authentication_object->user_session['UserAdmin.id']) {
			ggreq('modules/html/entry-controls.php');
			$entry_controls = new module_entrycontrols;
			$entry_controls->Display(['that'=>$this, 'file'=>__FILE__]);
		}
		
				// Start Top Bar
			
			// -------------------------------------------------------------
		
		print('<div class="horizontal-center width-95percent margin-top-5px">');
		
				// Breadcrumbs Info
			
			// -------------------------------------------------------------
			
		ggreq('modules/html/breadcrumbs.php');
		$breadcrumbs = new module_breadcrumbs(['that'=>$this]);
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
		
				// View Navigation
			
			// -------------------------------------------------------------
		
		print('<div class="border-2px background-color-gray15 margin-5px float-left">');
		
		print('<ul type="1" class="margin-5px font-family-arial">');
		
		if($this->entry['image'] && $image_count)
		{
			print('<li><a href="#image">Images</a></li>');
		}
		
		if($this->entry['association'] && $association_count)
		{
			print('<li><a href="#association">People</a></li>');
		}
		
		if($this->entry['description'] && $description_count)
		{
			print('<li><a href="#description">Description</a></li>');
		}
		
		if($this->entry['quote'] && $quote_count)
		{
			print('<li><a href="#quote">Quotes</a></li>');
		}
		
		if($this->entry['textbody'] && $textbody_count)
		{
			print('<li><a href="#textbody">Text</a></li>');
		}
		
		if($this->children && $children_count)
		{
			print('<li><a href="#children">Sections</a></li>');
		}
		
		if(($this->entry['definition'] && $definition_count) || $this->authentication_object->user_session['UserAdmin.id']) {
			print('<li><a href="#glossary">Glossary</a></li>');
		}
		
		if($this->entry['eventdate'] && $eventdate_count)
		{
			print('<li><a href="#eventdate">Chronology</a></li>');
		}
		
		print('<li><a href="#share">Share</a></li>');
		
		if($this->entry['link'] && $link_count)
		{
			print('<li><a href="#link">Links</a></li>');
		}
		
		print('<li><a href="#comments">Comments</a></li>');
		
		if($this->entry['tag'] && $tag_count)
		{
			print('<li><a href="#tag">Tags</a></li>');
		}
		
		if($younger_sibling_count || $older_sibling_count)
		{
			print('<li><a href="#siblings">Navigation</a></li>');
		}
		
		print('</ul>');
		
		print('</div>');
		
				// View Images
			
			// -------------------------------------------------------------
			
		if($this->entry['image'])
		{
			$images = $this->entry['image'];
			
			if($image_count)
			{
				print('<a name="image"></a>');
				
				if(!$this->mobile_friendly)
				{
					for($i = 0; $i < $image_count; $i++)
					{
						$image = $images[$i];
						
						print('<div class="border-2px background-color-gray15 margin-5px float-left" ');
						print('title="');
						print($image['Title']);
						print('">');
						print('<div class="border-2px background-color-gray15 margin-5px float-left">');
						print('<div class="height-100px width-100px background-color-gray0 horizontal-center">');
						print('<div class="horizontal-center vertical-specialcenter">');
						print('<a href="' . $this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]) . '/image/' . implode('/', str_split($image['FileDirectory'])) . '/' . $image['FileName'] . '"');
						print(' target="_blank"');
						print('>');
						print('<img width="');
						print(ceil($image['IconPixelWidth'] / 2));
						print('" height="');
						print(ceil($image['IconPixelHeight'] / 2));
						print('" src="');
						print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
						print('/image/');
						print(implode('/', str_split($image['FileDirectory'])));
						print('/');
						print($image['IconFileName']);
						print('">');
						print('</a>');
						print('</div>');
						print('</div>');
						print('</div>');
						print('</div>');
					}
				}
				else
				{
					print('<div class="border-2px background-color-gray15 margin-5px float-left background-color-gray13">');
					print('<div class="border-2px background-color-gray15 margin-5px float-left">');
					print('<div class="horizontal-center">');
					
					print('<p class="font-family-arial margin-5px">');
					print('<a href="view.php">');
					print('Images available at the non-mobile-friendly site.');
					print('</a>');
					print('</p>');
							
					print('</div>');
					print('</div>');
					print('</div>');
				}
			}
		}
		
				// View Times
			
			// -------------------------------------------------------------
		
		if($time_frame)
		{
			$header_secondary_args = [
				'title'=>$time_frame,
				'divmouseover'=>'This is the date of publication or authorship.',
				'level'=>2,
				'divclass'=>'border-2px background-color-gray13 margin-5px float-right',
				'textclass'=>'padding-0px margin-5px horizontal-left font-family-tahoma',
				'imagedivclass'=>'border-2px margin-5px background-color-gray10',
				'imageclass'=>'border-1px',
				'domainobject'=>$this->domain_object,
				'leftimageenable'=>0,
				'rightimageenable'=>0,
			];
			$header->display($header_secondary_args);
		}
		
				// Finish Date and Images
			
			// -------------------------------------------------------------
								
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
		
				// Display Textbody
			
			// -------------------------------------------------------------
		
		if($this->entry['association'] && $association_count)
		{
					// Association Header
				
				// -------------------------------------------------------------
				
			print('<a name="association"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('People');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Textbody Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Author Info
				
				// -------------------------------------------------------------
			
			#print("<PRE>");
			#print_r($this->entry['association']);
			#print("</PRE>");
			
			$associations = $this->entry['association'];
			
			for($i = 0; $i < $association_count; $i++)
			{
				$association = $associations[$i];
				
				$child = $association['entry'];
				
				print('<div class="horizontal-center width-90percent">');
			
				print('<div class="horizontal-center width-100percent background-color-gray14 border-2px margin-top-5px">');
				
				unset($display_image);
				
				if($child['image'])
				{
					$child_images = $child['image'];
					$child_image_count = count($child_images);
					if($child_image_count)
					{
						shuffle($child_images);
						$child_image = $child_images[0];
						$display_image = $child_image;
					}
				}
				
				
				if(!$display_image) {
					$display_image = $this->master_record['image'][0];
				}
				
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div class="height-100px width-100px background-color-gray0">');
				print('<div class="vertical-specialcenter">');
				print('<a href="../../people/' . $child['Code'] . '/view.php">');
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
				
				$popup_title = 0;
				$mouseover_title = '';
				$title_max = 30;
				
				$first_child_title = $association['SubType'];
				
				if(strlen($first_child_title) > $title_max)
				{
					$mouseover_title = $first_child_title;
					$first_child_title = substr($first_child_title, 0, $title_max) . '...';
					$popup_title = 1;
				}
				
				$second_child_title = $child['Title'];
				
				if(strlen($second_child_title) > $title_max)
				{
					if($mouseover_title)
					{
						$mouseover_title .= ' : ';
					}
					
					$mouseover_title .= $second_child_title;
					$second_child_title = substr($second_child_title, 0, $title_max) . '...';
					$popup_title = 1;
				}
				
				$child_title = '<a href="../../people/' . $child['Code'] . '/view.php"';
				
				if($popup_title)
				{
					$child_title .= ' title="' . str_replace('"', '&quot;', $mouseover_title) . '"';
				}
				
				$child_title .= '>';
				
				$child_title .= $first_child_title;
				
				$child_title .= ' : ';
				$child_title .= $second_child_title;
				$child_title .= '</a>';
				
				$div_mouseover = '';
				
				if($child['textbody'])
				{
					$text_bodies = $child['textbody'];
					
					$text_body_count = count($text_bodies);
					if($text_body_count)
					{
						$first_textbody = $text_bodies[0];
						
						$div_mouseover .= number_format($first_textbody['WordCount']) . ' Words / ' . number_format($first_textbody['CharacterCount']) . ' Characters';
					}
				}
				
				$header_secondary_args = [
					'title'=>$child_title,
					'divmouseover'=>$div_mouseover,
					'level'=>3,
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
				
				$time_frame = '';
				
				if($child['eventdate'])
				{
					$child_event_count = count($child['eventdate']);
					for($j = 0; $j < $child_event_count; $j++)
					{
						$child_event = $child['eventdate'][$j];
						
						if($child_event['Title'] == 'Birth Day')
						{
							$birth_event = $child_event;
						}
						elseif($child_event['Title'] == 'Death Day')
						{
							$death_event = $child_event;
						}
						
						if($birth_event && $death_event)
						{
							$j = $child_event_count;
						}
					}
					
					if($birth_event || $death_event)
					{
						$time_frame .= ' (';
						
						if($birth_event && $birth_event['id'])
						{
							if($birth_event['EventDateTime'] != '0000-00-00 00:00:00')
							{
								$birth_event_date_pieces = explode('-', $birth_event['EventDateTime']);
								$birth_year = $birth_event_date_pieces[0];
								$time_frame .= $birth_year;
							}
							else
							{
								$time_frame .= '?';
							}
						}
						
						$time_frame .= ' - ';
						
						if($death_event && $death_event['id'])
						{
							if($death_event['EventDateTime'] != '0000-00-00 00:00:00')
							{
								$death_event_date_pieces = explode('-', $death_event['EventDateTime']);
								$death_year = $death_event_date_pieces[0];
								$time_frame .= $death_year;
							}
							else
							{
								$time_frame .= '?';
							}
						}
						
						$time_frame .= ') ';
						
						unset($birth_event);
						unset($death_event);
					}
				}
				
				if($time_frame)
				{
					print($time_frame);
				}
				
				if($child['Subtitle'])
				{
					if($time_frame)
					{
						print(' ~ ');
					}
					
					print('<strong>');
					print($child['Subtitle']);
					print('</strong>');
				}
				
				if($child['description'])
				{
					$description = $child['description'][0];
					
					if($description && $description['Description'])
					{
						print('<em>');
						if($time_frame || $child['Subtitle'])
						{
							print(' : ');
						}
						
						print($description['Description']);
						print(' ');
						print('</em>');
						
						if($description['Source'])
						{
							$source = $description['Source'];
							
							if(strlen($source) > 50)
							{
								$source = substr($source, 0, 50) . '...';
							}
							
							print(' (From : ' . $source . '.)');
						}
					}
				}
				
				if($child['quote'])
				{
					$child_quotes = $child['quote'];
					$child_quotes_count = count($child_quotes);
					$max_limit = $child_quotes_count;
					if($max_limit > 3)
					{
						$max_limit = 3;
					}
					shuffle($child_quotes);
					for($j = 0; $j < $max_limit; $j++)
					{
						$quote = $child_quotes[$j];
						if($quote && $quote['Quote'])
						{
							print(' <br>&bull; ');
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
							$text_display = $this->cleanser_object->FormatListOutput([
								'text'=>$text_bodies[0]['FirstThousandCharacters'],
							]);
							
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
					$person_tag_count = count($child['tag']);
					
					if($person_tag_count)
					{
						$tags = $child['tag'];
						$max_limit = $person_tag_count;
						if($max_limit > 10)
						{
							$max_limit = 10;
						}
						shuffle($tags);
						
						for($j = 0; $j < $max_limit; $j++)
						{
							$tag = $tags[$j];
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
		}
		
				// Display Description
			
			// -------------------------------------------------------------
		
		if($this->entry['description'] && $description_count)
		{
					// Description Header
				
				// -------------------------------------------------------------
				
			print('<a name="description"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Description');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Description Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Display Description
				
				// -------------------------------------------------------------
			
			$description = $this->entry['description'][0];
			print('<center>');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
			print('<div class="border-2px background-color-gray15 margin-5px horizontal-left">');
			print('<p class="horizontal-left margin-5px font-family-arial">');
			print($description['Description']);
			print('</p>');
			
			if($this->entry['tag'] && $tag_count) {
				print('<p class="horizontal-left margin-5px font-family-tahoma float-left"><b>Top Tags :</b> ');
				$entry_tags = $this->tag_counts;
				arsort($entry_tags);
				$entry_tag_keys = array_keys($entry_tags);
				$tags_max = min(10, count($entry_tags));
				for($i = 0; $i < $tags_max; $i++) {
					$tag = $entry_tag_keys[$i];
					
					print('<div class="border-2px background-color-gray13 margin-left-5px margin-top-5px margin-bottom-5px float-left">');
					print('<span class="horizontal-left margin-5px font-family-arial ">');
					print('<a href="/view.php?action=browseByTag&tag=' . urlencode($tag) . '">');
					print($tag);
					
					print(' (');
					print(number_format($this->tag_counts[$tag]));
					print(')');
					
					print('</a>');
					print('</span>');
					print('</div>');
				}
				print('</p>');
			}
			
			if($description['Source'])
			{
				print('<div class="float-right border-2px margin-5px background-color-gray13">');
				print('<p class="horizontal-left margin-5px font-family-arial">');
				print('From : ');
				print($this->HyperlinkizeText(['text'=>$description['Source']]));
				print('</p>');
				print('</div>');
			}
			
					// Finish Floated Box
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
			print('</div>');
			print('</div>');
			print('</center>');
		}
		
				// Display Quotes
			
			// -------------------------------------------------------------
		
		if($this->entry['quote'] && $quote_count)
		{
					// Quote Header
				
				// -------------------------------------------------------------
				
			print('<a name="quote"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Quotes');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Quote Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Display Quote
				
				// -------------------------------------------------------------
			
			print('<center>');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
			
			for($i = 0; $i < $quote_count; $i++)
			{
				$quote = $this->entry['quote'][$i];
				print('<div class="border-2px background-color-gray15 margin-5px horizontal-left">');
				print('<blockquote class="margin-top-0px"><p class="horizontal-left margin-5px font-family-arial"><em>"<quote>');
				print(str_replace('"', '\'', $quote['Quote']));
				print('</quote>"</em></p></blockquote>');
				
				if($quote['Source'])
				{
					print('<div class="float-right border-2px margin-5px background-color-gray13">');
					print('<p class="horizontal-left margin-5px font-family-arial">');
					print('From : ');
					print($this->HyperlinkizeText(['text'=>$quote['Source']]));
					print('</p>');
					print('</div>');
					
							// Finish Floated Box
						
						// -------------------------------------------------------------
											
					$clear_float_divider_start_args = [
						'class'=>'clear-float',
					];
					
					$divider->displaystart($clear_float_divider_start_args);
					
					$clear_float_divider_end_args = [
					];
					
					$divider->displayend($clear_float_divider_end_args);
				}
				
				print('</div>');
			}
			
			print('</div>');
			print('</center>');
		}
		
				// Display Textbody
			
			// -------------------------------------------------------------
		
		if($this->entry['textbody'] && $textbody_count)
		{
					// Textbody Header
				
				// -------------------------------------------------------------
				
			print('<a name="textbody"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Text');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Textbody Header
				
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
			
					// Display Textbody
				
				// -------------------------------------------------------------
			
			print('<center>');
			
			for($i = 0; $i < $textbody_count; $i++)
			{
				$textbody = $this->entry['textbody'][$i];
				print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
				print('<div class="border-2px background-color-gray15 margin-5px horizontal-left font-family-arial">');
				
				print('<div class="horizontal-center width-90percent">');
				
				print('<div class="float-left border-2px margin-5px background-color-gray13 margin-left-20px">');
				print('<p class="horizontal-left margin-5px font-family-arial">');
				print($this->entry['Title']);
				print('</p>');
				print('</div>');
				print('</div>');
					
							// Finish Floated Box
						
						// -------------------------------------------------------------
											
					$clear_float_divider_start_args = [
						'class'=>'clear-float',
					];
					
					$divider->displaystart($clear_float_divider_start_args);
					
					$clear_float_divider_end_args = [
					];
					
					$divider->displayend($clear_float_divider_end_args);
				
				print('<div class="margin-5px horizontal-left font-family-arial text-to-play-as-audio">');
				print($this->HyperlinkizeText(['text'=>$textbody['Text']]));
				print('</div>');
				
							// Finish Floated Box
						
						// -------------------------------------------------------------
											
					$clear_float_divider_start_args = [
						'class'=>'clear-float',
					];
					
					$divider->displaystart($clear_float_divider_start_args);
					
					$clear_float_divider_end_args = [
					];
					
					$divider->displayend($clear_float_divider_end_args);
				
				if($textbody['Source'])
				{
					print('<div class="float-right border-2px margin-5px background-color-gray13">');
					print('<p class="horizontal-left margin-5px font-family-arial">');
					print('From : ');
					print($this->HyperlinkizeText(['text'=>$textbody['Source']]));
					print('</p>');
					print('</div>');
					
							// Finish Floated Box
						
						// -------------------------------------------------------------
											
					$clear_float_divider_start_args = [
						'class'=>'clear-float',
					];
					
					$divider->displaystart($clear_float_divider_start_args);
					
					$clear_float_divider_end_args = [
					];
					
					$divider->displayend($clear_float_divider_end_args);
				}
				
				print('</div>');
				print('</div>');
			}
			
			print('</center>');
		}
		
				// Display Children
			
			// -------------------------------------------------------------
		
		if($this->children && $children_count) {
					// Textbody Header
				
				// -------------------------------------------------------------
				
			print('<a name="children"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Sections');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Textbody Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Child Record Counts
				
				// -------------------------------------------------------------
						
			print('<center>');
			print('<div class="horizontal-center width-70percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left" title="');
			
			print(' (Last Updated: ');
			$date_epoch_time = strtotime($this->child_record_stats['LastModificationDate']);
			$full_date = date("F d, Y; H:i:s", $date_epoch_time);
			print($full_date);
			print('.)');
			
			print('">');
			
			print('<strong>');
			print('<p class="horizontal-left margin-5px font-family-tahoma">');
			print('This document contains ' . number_format($this->child_record_stats['ChildRecordCount']) . ' sections, with ' . number_format($this->child_record_stats['ChildWordCount']) . ' words or ' . number_format($this->child_record_stats['ChildCharacterCount']) . ' characters.');
			print('</p>');
			print('</strong>');
			
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Textbody Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Alternates
				
				// -------------------------------------------------------------
				
			if(!$this->entry['textbody'] && !$textbody_count) {
			
				// Alternate Formats Info
			
			// -------------------------------------------------------------
			
		ggreq('modules/html/alternateformats.php');
		$auth = new module_alternateformats(['that'=>$this]);
		$auth->Display();
			}
			
					// Display Children
				
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
				
				if(!$display_image) {
					if($this->entry['association'][0]['entry']['image'] && count($this->entry['association'][0]['entry']['image'])) {
						$display_image = $this->entry['association'][0]['entry']['image'][0];
					}
				}
				
				if(!$display_image) {
					$display_image = $this->master_record['image'][0];
				}
				
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div class="height-100px width-100px background-color-gray0">');
				print('<div class="vertical-specialcenter">');
				print('<a href="' . $child['Code'] . '/view.php">');
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
				
				$title_max = 50;
				$title_popup = 0;
				
				if($child['Subtitle'])
				{
					$title_max = 30;
				}
				
				$full_child_title = $child['Title'];
				
				if(strlen($full_child_title) > $title_max)
				{
					$full_child_title = substr($full_child_title, 0, $title_max) . '...';
					$title_popup = 1;
				}
				
				if($child['Subtitle'])
				{
					$full_child_title .= ' : ';
					
					$full_child_subtitle = $child['Subtitle'];
					
					if(strlen($full_child_subtitle) > $title_max)
					{
						$full_child_subtitle = substr($full_child_subtitle, 0, $title_max) . '...';
					}
					
					$full_child_title .= $full_child_subtitle;
					$title_popup = 1;
				}
				
				$child_title = '<a href="' . $child['Code'] . '/view.php"';
				
				if($title_popup)
				{
					$popup_title = $child['Title'];
					
					if($child['Subtitle'])
					{
						$popup_title .= ' : ';
						$popup_title .= $child['Subtitle'];
					}
					$child_title .= ' title="' . str_replace('"', '&quot;', $popup_title) . '"';
				}
				
				$child_title .= '>';
				
				$child_title .= $full_child_title;
				
				$child_title .= '</a>';
				
				$div_mouseover = '';
				
				if($child['textbody'])
				{
					$text_bodies = $child['textbody'];
					
					$text_body_count = count($text_bodies);
					if($text_body_count)
					{
						$first_textbody = $text_bodies[0];
						
						$div_mouseover .= number_format($first_textbody['WordCount']) . ' Words / ' . number_format($first_textbody['CharacterCount']) . ' Characters';
					}
				}
				
				$header_secondary_args = [
					'title'=>$child_title,
					'divmouseover'=>$div_mouseover,
					'level'=>3,
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
				
				$time_frame = '';
				
				if($child['eventdate'])
				{
					$child_event_count = count($child['eventdate']);
					for($i = 0; $i < $child_event_count; $i++)
					{
						$child_event = $child['eventdate'][$i];
							
						if($child_event['Title'] == 'Publication')
						{
							$publication_event = $child_event;
						}
						
						if($publication_event)
						{
							$i = $child_event_count;
						}
					}
					
					if($publication_event)
					{
						if($publication_event['EventDateTime'] != '0000-00-00 00:00:00')
						{
							$event_date_pieces = explode('-', $publication_event['EventDateTime']);
							$year = $event_date_pieces[0];
							$time_frame .= $year;
						}
						else
						{
							$time_frame .= '?';
						}
						
						unset($publication_event);
					}
				}
				
				print('<strong>');
				
				if($time_frame)
				{
					print($time_frame);
				}
				
				if($child['textbody'] && count($child['textbody']))
				{
					if($time_frame)
					{
						print(' ~ ');
					}
					
					$first_textbody = $child['textbody'][0];
					
					print('(');
					print(number_format($first_textbody['WordCount']));
					print(' Words / ');
					print(number_format($first_textbody['CharacterCount']));
					print(' Characters');
					print(')');
				}
				print('</strong> ');
				
				if($child['description'])
				{
					$description = $child['description'][0];
					
					if($description && $description['Description'])
					{
						print('<em>');
						if($time_frame || $child['Subtitle'])
						{
							print(' : ');
						}
						
						print($description['Description']);
						print(' ');
						print('</em>');
						
						if($description['Source'])
						{
							$source = $description['Source'];
							
							if(strlen($source) > 50)
							{
								$source = substr($source, 0, 50) . '...';
							}
							
							print(' (From : ' . $source . '.)');
						}
					}
				}
				
				if($child['quote'])
				{
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
							print(' <br>&bull; ');
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
						}
					}
				} else {
					if($child['textbody'])
					{
						$text_bodies = $child['textbody'];
						
						$text_body_count = count($text_bodies);
						if($text_body_count)
						{
							$text_display = $this->cleanser_object->FormatListOutput([
								'text'=>$text_bodies[0]['FirstThousandCharacters'],
							]);
							
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
				
				if($child['tag']) {
					$tag_count = count($child['tag']);
					
					if($tag_count) {
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
							print('<a href="/view.php?action=browseByTag&tag=' . urlencode($tag['Tag']) . '">');
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
				
				$grandchildren = $child['children'];
				
				print('<div class="horizontal-center width-95percent">');
				foreach($grandchildren as $grandchild) {
					print('<div class="border-2px background-color-gray15 margin-5px font-family-tahoma horizontal-left float-left">');
					
					print('<div class="background-color-gray0 float-left" style="height:50px;width:50px;">');
					print('<div class="vertical-specialcenter">');
					print('<a href="' . $grandchild['Code'] . '/view.php">');
					print('<img width="');
					print(ceil($display_image['IconPixelWidth'] / 4));
					print('" height="');
					print(ceil($display_image['IconPixelHeight'] / 4));
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
					
					print('<p class="margin-5px float-left">');
					print('<a href="' . $child['Code'] . '/' . $grandchild['Code'] . '/view.php">');
					print($grandchild['Title']);
					print('</a>');
					print('</p>');
					
					$clear_float_divider_start_args = [
						'class'=>'clear-float',
					];
					
					$divider->displaystart($clear_float_divider_start_args);
					
					$clear_float_divider_end_args = [
					];
					
					$divider->displayend($clear_float_divider_end_args);
					
					print('</div>');
				}
												
						$clear_float_divider_start_args = [
							'class'=>'clear-float',
						];
						
						$divider->displaystart($clear_float_divider_start_args);
						
						$clear_float_divider_end_args = [
						];
						
						$divider->displayend($clear_float_divider_end_args);
				print('</div>');
			}
			print('</div>');
		}
		
				// Display Definitions
			
			// -------------------------------------------------------------
		
		if($definition_count || $this->authentication_object->user_session['UserAdmin.id'])
		{
					// Quote Header
				
				// -------------------------------------------------------------
				
			print('<a name="glossary"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Glossary');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Glossary Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// View Glossary
				
				// -------------------------------------------------------------
			
			if($definition_count) {
				print('<center>');
				print('<div class="width-90percent horizontal-center">');
				print('<center>');
				print('<div class="border-2px background-color-gray15 margin-5px width-90percent horizontal-center">');
				print('<div class="horizontal-left">');
				
				$definitions = $this->definitions;
				$definitions_hash_count = count($definitions);
				
			//	print_r($this->definitions);
				
				foreach($definitions as $term => $definition_list) {
						// View Word
					
					// -------------------------------------------------------------
					
					print('<center>');
					print('<div class="width-90percent">');
					
					$header_secondary_args = [
						'title'=>$term,
						'divmouseover'=>$term . ' defined (Metaphone : ' . metaphone($term) . '; Soundex : ' . soundex($term) . ')',
						'level'=>2,
						'divclass'=>'border-2px background-color-gray13 margin-5px float-left',
						'textclass'=>'padding-0px margin-5px horizontal-left font-family-tahoma',
						'imagedivclass'=>'border-2px margin-5px background-color-gray10',
						'imageclass'=>'border-1px',
						'domainobject'=>$this->domain_object,
						'leftimageenable'=>0,
						'rightimageenable'=>0,
					];
					$header->display($header_secondary_args);
					
					$clear_float_divider_start_args = [
						'class'=>'clear-float',
					];
					
					$divider->displaystart($clear_float_divider_start_args);
					
					$clear_float_divider_end_args = [
					];
					
					$divider->displayend($clear_float_divider_end_args);
					
					print('</div>');
					print('</center>');
					
					$definition_count = count($definition_list);
					
					print('<ol class="margin-10px font-family-tahoma horizontal-left">');
					for($i = 0; $i < $definition_count; $i++) {
						$full_definition = $definition_list[$i];
							
							print('<li>');
							print($full_definition);
							print('</li>');
						
					#	print_r($definition);
					}
					print('</ol>');
				}
				
				print('</div>');
				print('</div>');
				print('</center>');
				print('</div>');
				print('</center>');
			}
			
			if($this->authentication_object->user_session['UserAdmin.id']) {
						// View ALl
					
					// -------------------------------------------------------------
				
				print('<div class="horizontal-center width-50percent">');
				print('<div class="horizontal-center width-100percent background-color-gray14 border-2px margin-top-5px">');
				print('<h3 class="margin-5px">');
				print('<a href="view.php?action=definitions">');
				print('<span class="font-family-tahoma">');
				print('View All x Definitions');
				print('</span>');
				print('</a>');
				print('</h3>');
				print('</div>');
				print('</div>');
			}
		}
		
				// Display Event Dates
			
			// -------------------------------------------------------------
		
		if($this->entry['eventdate'] && $eventdate_count)
		{
					// Quote Header
				
				// -------------------------------------------------------------
				
			print('<a name="eventdate"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Chronology');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Textbody Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Display Event Dates
				
				// -------------------------------------------------------------
			
			print('<center>');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
			print('<div class="border-2px background-color-gray15 margin-5px horizontal-left font-family-arial">');
			
			for($i = 0; $i < $eventdate_count; $i++)
			{
				$eventdate = $this->entry['eventdate'][$i];
				print('<div class="margin-5px horizontal-left font-family-arial">');
				
				$date_time_pieces = explode(' ', $eventdate['EventDateTime']);
				$event_date = $date_time_pieces[0];
				$event_date_pieces = explode('-', $event_date);
				$event_time = $date_time_pieces[1];
				
				print('<div class="float-left border-2px margin-5px background-color-gray13">');
				print('<div class="margin-5px">');
				
				print('<strong>');
				$eventdate['EventDateTime'] = str_replace('-00-00', '-01-01', $eventdate['EventDateTime']);
				$eventdate['EventDateTime'] = str_replace('-00', '-01', $eventdate['EventDateTime']);
				$date_epoch_time = strtotime($eventdate['EventDateTime']);
				
				if($event_date != '0000-00-00')
				{
					if($event_date_pieces[1] != '00' && $event_date_pieces[2] != '00') {
						print(date("F d, Y", $date_epoch_time));
					} elseif($event_date_pieces[1] != '00') {
						print(date("F, Y", $date_epoch_time));
					} else {
						print(date("Y", $date_epoch_time));
					}
				}
				
				print(' ');
				
				if($event_time != '00:00:00')
				{
					print(date("; g:i:s A (e)", $date_epoch_time));
				}
				
				print(' : </strong>');
				print('</div>');
				print('</div>');
				
				if($eventdate['Title'] == 'Publication') {
					print($this->entry['Title']);
					print(' -- ');
				}
				print($eventdate['Title']);
				
				if($eventdate['Description']) {
					print(' ');
					print('(');
					print($eventdate['Description']);
					print(')');
				}
				
				print('.');
					
							// Finish Floated Box
						
						// -------------------------------------------------------------
											
					$clear_float_divider_start_args = [
						'class'=>'clear-float',
					];
					
					$divider->displaystart($clear_float_divider_start_args);
					
					$clear_float_divider_end_args = [
					];
					
					$divider->displayend($clear_float_divider_end_args);
				
				print('</div>');
				
				if(($i + 1) != $eventdate_count)
				{
					print('<hr width="95%">');
				}
			}
			print('</div>');
			print('</div>');
			
			print('</center>');
		}
		
				// Display Share Links
			
			// -------------------------------------------------------------
				
					// Share Links Header
				
				// -------------------------------------------------------------
				
		print('<a name="share"></a>');
		
		print('<center>');
		print('<div class="horizontal-center width-95percent">');
		print('<div class="border-2px background-color-gray15 margin-5px float-left">');
		print('<h2 class="horizontal-left margin-5px font-family-arial">');
		print('Share');
		print('</h2>');
		print('</div>');
		print('</div>');
		print('</center>');
			
					// Finish Share Links Header
				
				// -------------------------------------------------------------
									
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
			
					// Start Display Share Options
				
				// -------------------------------------------------------------
		
		print('<center>');
		print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
		print('<div class="border-2px background-color-gray15 margin-5px horizontal-left font-family-arial">');
		print('<div class="margin-5px horizontal-left font-family-arial">');
		
					// Display "Share" Text
				
				// -------------------------------------------------------------
		
		print('<div class="float-left border-2px margin-5px background-color-gray13">');
		print('<div class="margin-5px">');
		print('<strong>Permalink for Sharing :</strong>');
		print('</div>');
		print('</div>');
			
					// Finish "Share" Text
				
				// -------------------------------------------------------------
									
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
			
					// Display Permalink
				
				// -------------------------------------------------------------
		
		print('<center>');
		print('<div class="margin-5px horizontal-center width-90percent">');
		print('<div class="margin-5px border-2px background-color-gray13 float-left">');
		print('<div class="margin-5px horizontal-left font-family-arial float-left">');
		print('<input class="select-input-contents" type="text" size="100" value="');
		print($this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]));
		print('/?id=');
		print($this->entry['assignment'][0]['id']);
		print('">');
		print('</div>');
		
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
		print('</div>');
		
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
		print('</div>');
		print('</center>');
		
					// Display Social Networking Options
				
				// -------------------------------------------------------------
		
		$social_media_share_links->display();
		
					// End Display Share Options
				
				// -------------------------------------------------------------
				
		print('</div>');
		print('</div>');
		print('</div>');
		print('</center>');
		
				// Display Links
			
			// -------------------------------------------------------------
		
		if($this->entry['link'] && $link_count)
		{
					// Quote Header
				
				// -------------------------------------------------------------
				
			print('<a name="link"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Links');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Textbody Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Display Links
				
				// -------------------------------------------------------------
			
			print('<center>');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
			
			for($i = 0; $i < $link_count; $i++)
			{
				$link = $this->entry['link'][$i];
				
				print('<div class="border-2px background-color-gray15 margin-5px horizontal-left font-family-arial">');
				print('<div class="margin-5px horizontal-left font-family-arial">');
				
				print('<a href="' . $link['URL'] . '">');
				if($link['Title'])
				{
					print(' &bull; ' . $link['Title']);
				}
				else
				{
					print($link['URL']);
				}
				print('</a>');
							
				print('</div>');
				print('</div>');
			}
			print('</div>');
			
			print('</center>');
		}				// Comments Header
			
			// -------------------------------------------------------------
			
		print('<a name="comments"></a>');
		
		print('<center>');
		print('<div class="horizontal-center width-95percent">');
		print('<div class="border-2px background-color-gray15 margin-5px float-left">');
		print('<h2 class="horizontal-left margin-5px font-family-arial">');
		print('Comments');
		print('</h2>');
		print('</div>');
		print('</div>');
		print('</center>');
		
				// Finish Textbody Header
			
			// -------------------------------------------------------------
								
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
		
				// Display Comment Form
			
			// -------------------------------------------------------------
			
		if($_SERVER['HTTPS'] == 'on')
		{
			$start_form_args = [
				'action'=>'view.php#comments',
				'method'=>'post',
				'formid'=>'comment-form',
				'formclass'=>'margin-0px',
			];
			
			$form->StartForm($start_form_args);
			
			if($this->authentication_object->user_session)
			{
				print('<center>');
				
				if($this->username_record_conflict)
				{
					print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-70percent font-family-tahoma">');
					
					$username = '';
					
					if($this->authentication_object->user_session['User.Username'])
					{
						$username = $this->authentication_object->user_session['User.Username'];
					}
					else
					{
						$username = $this->authentication_object->user_session['User.EmailAddress'];
					}
					
					print('<p class="margin-10px horizontal-left">We\'re sorry.  The following username is already taken :<br><br><em>' . str_replace("\n", "<br>\n", strip_tags($this->Param('Username'))) . '</em></p>');
					
					print('</div>');
				}
				
				if($this->comment_results)
				{
					print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-70percent font-family-tahoma">');
					
					$username = '';
					
					if($this->authentication_object->user_session['User.Username'])
					{
						$username = $this->authentication_object->user_session['User.Username'];
					}
					else
					{
						$username = $this->authentication_object->user_session['User.EmailAddress'];
					}
					
					print('<p class="margin-10px horizontal-left">Thank you for your comment!  The following was received and will be published after review.<br><br><em>' . str_replace("\n", "<br>\n", strip_tags($this->comment_results['Comment'])) . '</em></p>');
					
					print('</div>');
				}
				print('<div id="error-box" class="border-2px background-color-gray13 margin-5px horizontal-center width-70percent font-family-tahoma" style="display:none;">');
				
				print('<p id="validation-error-message" class="margin-10px horizontal-left"></p>');
				
				print('</div>');
			
						// Display Likes/Dislikes
					
					// -------------------------------------------------------------
				
				$like_mouseover_value = '';
				$cursor_class = '';
				
				if($_SERVER['HTTPS'] != 'on' || !$this->authentication_object->user_session)
				{
					$like_mouseover_value = 'You must login before you are allowed to upvote or downvote.';
				}
				else
				{
					$like_mouseover_value = 'Let your feelings be known!  Like or dislike this here.';
					$cursor_class = 'cursor-pointer';
				}
				
				print('<center>');
				print('<div class="horizontal-center width-70percent" title="' . $like_mouseover_value . '">');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div id="thumbs-up-button-container" class="border-2px background-color-gray15 margin-5px float-left');
				if($cursor_class)
				{
					print(' ' . $cursor_class);
				}
				print('">');
				print('<div class="height-100px width-100px background-color-gray15">');
				print('<div class="vertical-specialcenter">');
				print('<img id="thumbs-up-button" width="90" height="90" src="');
				print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
				print('/image/');
				print('thumbs-up-right.jpg');
				print('"');
				print('>');
				print('</div>');
				print('</div>');
				print('<nobr><span id="total-likes" class="font-family-tahoma">');
				print(number_format($this->likes_count));
				print('</span>');
				print('<span class="font-family-tahoma"> Likes</span>');
				print('</nobr>');
				print('</div>');
				print('</div>');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div id="thumbs-down-button-container" class="border-2px background-color-gray15 margin-5px float-left');
				if($cursor_class)
				{
					print(' ' . $cursor_class);
				}
				print('">');
				print('<div class="height-100px width-100px background-color-gray15">');
				print('<div class="vertical-specialcenter">');
				print('<img id="thumbs-down-button" width="90" height="90" src="');
				print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
				print('/image/');
				print('thumbs-down-right.jpg');
				print('"');
				print('>');
				print('</div>');
				print('</div>');
				print('<nobr>');
				print('<span id="total-dislikes" class="font-family-tahoma">');
				print(number_format($this->dislikes_count));
				print('</span>');
				print('<span class="font-family-tahoma"> Dislikes</span>');
				print('</nobr>');
				print('</div>');
				print('</div>');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-right" title="Click here to see users who liked this.">');
				print('<div class="background-color-gray15 margin-5px float-left');
				print('<span class="font-family-tahoma">');
				print('<a href="view.php?action=viewUserLikes" class="font-family-tahoma">');
				print('Who Liked This?');
				print('</a>');
				print('</span>');
				print('</div>');
				print('</div>');
				
				print('</div>');
				print('</center>');
				
						// Finish Textbody Header
					
					// -------------------------------------------------------------
										
				$clear_float_divider_start_args = [
					'class'=>'clear-float',
				];
				
				$divider->displaystart($clear_float_divider_start_args);
				
				$clear_float_divider_end_args = [
				];
				
				$divider->displayend($clear_float_divider_end_args);
		
				print('<div class="width-90percent">');
				print('<div class="border-2px background-color-gray13 margin-5px font-family-tahoma float-left">');
				
				if(!$this->authentication_object->user_session['User.Username'])
				{
					print('<p class="margin-5px horizontal-left"><b>Username : </b>');
					print('<input id="Username" class="Username" name="Username" type="text" size="80">');
					print(' <span title="This will be viewable to the public, unlike your E-mail Address.">(Publicly Viewable)</span> <span style="color:FF0000;vertical-align:top;margin:10px;">*</span>');
					print('</p>');
				}
				
				print('<nobr>');
				
				$comment_value = '';
				
				if($this->username_record_conflict)
				{
					$comment_value = $this->Param('Comments');
				}
				
				$type_args = [
					'type'=>'textarea',
					'id'=>'Comments',
					'name'=>'Comments',
					'cols'=>120,
					'rows'=>10,
					'value'=>$comment_value,
				];
				
				$form->DisplayFormField($type_args);
				print(' <span style="color:FF0000;vertical-align:top;margin:10px;">*</span>');
				print('</nobr>');
				
				print('<br>');
				
				$type_args = [
					'type'=>'submit',
					'id'=>'submit',
					'name'=>'Comment',
					'value'=>'Comment',
				];
				
				$form->DisplayFormField($type_args);
				
				print('</div>');
				print('</div>');
				print('</center>');
				
						// Finish Textbody Header
					
					// -------------------------------------------------------------
										
				$clear_float_divider_start_args = [
					'class'=>'clear-float',
				];
				
				$divider->displaystart($clear_float_divider_start_args);
				
				$clear_float_divider_end_args = [
				];
				
				$divider->displayend($clear_float_divider_end_args);
						
		#		print_r($this->authentication_object->user_account);
		#		print_r($this->authentication_object->user_session);
			}
			else
			{
				print('<center>');
				print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-50percent font-family-tahoma">');
				print('<p>Login through Google to Comment or Like/Dislike :</p>');
				
				print('<div class="horizontal-center width-50percent margin-top-5px margin-bottom-5px">');
				print('<div class="g-signin2" data-onsuccess="onSignIn"></div>');
				print('</div>');
				
				print('<input type="hidden" name="google_token_id" id="google_token_id" class="google_token_id">');
				print('<div style="display:none;">');
				$type_args = [
					'type'=>'submit',
					'id'=>'submit',
					'name'=>'Comment',
					'value'=>'Comment',
				];
				
				$form->DisplayFormField($type_args);
				print('</div>');
				
				print('</div>');
				print('</center>');
				
						// Display Likes/Dislikes
					
					// -------------------------------------------------------------
				
				$like_mouseover_value = '';
				$cursor_class = '';
				
				if($_SERVER['HTTPS'] != 'on' || !$this->authentication_object->user_session)
				{
					$like_mouseover_value = 'You must login before you are allowed to upvote or downvote.';
				}
				else
				{
					$like_mouseover_value = 'Let your feelings be known!  Like or dislike this here.';
					$cursor_class = 'cursor-pointer';
				}
				
				print('<center>');
				print('<div class="horizontal-center width-70percent" title="' . $like_mouseover_value . '">');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div id="thumbs-up-button-container" class="border-2px background-color-gray15 margin-5px float-left');
				if($cursor_class)
				{
					print(' ' . $cursor_class);
				}
				print('">');
				print('<div class="height-100px width-100px background-color-gray15">');
				print('<div class="vertical-specialcenter">');
				print('<img width="90" height="90" src="');
				print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
				print('/image/');
				print('thumbs-up-right.jpg');
				print('"');
				print('>');
				print('</div>');
				print('</div>');
				print('<span class="font-family-tahoma"><nobr>');
				print(number_format($this->likes_count));
				print(' Likes</nobr></span>');
				print('</div>');
				print('</div>');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<div class="border-2px background-color-gray15 margin-5px float-left');
				if($cursor_class)
				{
					print(' ' . $cursor_class);
				}
				print('">');
				print('<div class="height-100px width-100px background-color-gray15">');
				print('<div class="vertical-specialcenter">');
				print('<img width="90" height="90" src="');
				print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
				print('/image/');
				print('thumbs-down-right.jpg');
				print('"');
				print('>');
				print('</div>');
				print('</div>');
				print('<span id="total-dislikes" class="font-family-tahoma"><nobr>');
				print(number_format($this->dislikes_count));
				print(' Dislikes');
				print('</nobr></span>');
				print('</div>');
				print('</div>');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-right" title="Click here to see users who liked this.">');
				print('<div class="background-color-gray15 margin-5px float-left');
				print('<span class="font-family-tahoma">');
				print('<a href="view.php?action=viewUserLikes" class="font-family-tahoma">');
				print('Who Liked This?');
				print('</a>');
				print('</span>');
				print('</div>');
				print('</div>');
				
				print('</div>');
				print('</center>');
				
						// Finish Textbody Header
					
					// -------------------------------------------------------------
										
				$clear_float_divider_start_args = [
					'class'=>'clear-float',
				];
				
				$divider->displaystart($clear_float_divider_start_args);
				
				$clear_float_divider_end_args = [
				];
				
				$divider->displayend($clear_float_divider_end_args);
			}
			
			$end_form_args = [
			];
			$form->EndForm($end_form_args);
		}
		else
		{		
			print('<center>');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-50percent font-family-tahoma">');
			
			$new_url = str_replace('/view.php', '/view.php#comments', $_SERVER['SCRIPT_URL']);
			print('<p><b><a href="' . $this->domain_object->GetPrimaryDomain(['secure'=>1, 'lowercase'=>0, 'www'=>1]) . $new_url . '">Login to Comment</a></b></p>');
			
			print('</div>');
			print('</center>');
			
					// Display Likes/Dislikes
				
				// -------------------------------------------------------------
			
			$like_mouseover_value = '';
			$cursor_class = '';
			
			if($_SERVER['HTTPS'] != 'on' || !$this->authentication_object->user_session)
			{
				$like_mouseover_value = 'You must login before you are allowed to upvote or downvote.';
			}
			else
			{
				$like_mouseover_value = 'Let your feelings be known!  Like or dislike this here.';
				$cursor_class = 'cursor-pointer';
			}
			
			print('<center>');
			print('<div class="horizontal-center width-70percent" title="' . $like_mouseover_value . '">');
			
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left');
			if($cursor_class)
			{
				print(' ' . $cursor_class);
			}
			print('">');
			print('<div class="height-100px width-100px background-color-gray15">');
			print('<div class="vertical-specialcenter">');
			print('<img width="90" height="90" src="');
			print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
			print('/image/');
			print('thumbs-up-right.jpg');
			print('"');
			print('>');
			print('</div>');
			print('</div>');
			print('<span id="total-dislikes" class="font-family-tahoma"><nobr>');
			print(number_format($this->likes_count));
			print(' Likes</nobr></span>');
			print('</div>');
			print('</div>');
			
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left');
			if($cursor_class)
			{
				print(' ' . $cursor_class);
			}
			print('">');
			print('<div class="height-100px width-100px background-color-gray15">');
			print('<div class="vertical-specialcenter">');
			print('<img width="90" height="90" src="');
			print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
			print('/image/');
			print('thumbs-down-right.jpg');
			print('"');
			print('>');
			print('</div>');
			print('</div>');
			print('<span id="total-dislikes" class="font-family-tahoma"><nobr>');
			print(number_format($this->dislikes_count));
			print(' Dislikes');
			print('</nobr></span>');
			print('</div>');
			print('</div>');
			
			print('<div class="border-2px background-color-gray15 margin-5px float-right" title="Click here to see users who liked this.">');
			print('<div class="background-color-gray15 margin-5px float-left');
			print('<span class="font-family-tahoma">');
			print('<a href="view.php?action=viewUserLikes" class="font-family-tahoma">');
			print('Who Liked This?');
			print('</a>');
			print('</span>');
			print('</div>');
			print('</div>');
			
			print('</div>');
			print('</center>');
				
						// Finish Textbody Header
					
					// -------------------------------------------------------------
										
				$clear_float_divider_start_args = [
					'class'=>'clear-float',
				];
				
				$divider->displaystart($clear_float_divider_start_args);
				
				$clear_float_divider_end_args = [
				];
				
				$divider->displayend($clear_float_divider_end_args);
		}
		
		print('<input type="hidden" name="userid" id="userid" class="userid" value="' . $this->authentication_object->user_session['User.id'] . '">' . "\n\n");
		print('<input type="hidden" name="usersessionid" id="usersessionid" class="usersessionid" value="' . $this->authentication_object->user_session['CookieToken'] . '">' . "\n\n");
		print('<input type="hidden" name="logout" id="logout" class="logout" value="' . $this->Param('logout') . '">' . "\n\n");
		
		if($this->user_likedislike && $this->user_likedislike['id'])
		{
			print('<input type="hidden" id="likeordislike" class="likeordislike" name="likeordislike" value="');
			print($this->user_likedislike['LikeOrDislike']);
			print('">');
		}
		
				// Display Comments
			
			// -------------------------------------------------------------
		
		print('<center>');
		
		if($this->comments && is_array($this->comments) && count($this->comments))
		{
			print('<div class="horizontal-center width-80percent">');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-left width-50percent font-family-tahoma">');
			print('<p class="margin-5px"><nobr>Total Comments : ' . count($this->comments) . '</nobr></p>');
			print('</div>');
			print('</div>');
			
			foreach($this->comments as $comment)
			{
				print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent font-family-tahoma">');
				print('<div class="horizontal-left margin-5px">');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-left">');
				print('<p class="horizontal-left margin-5px font-family-arial">');
				print('Posted By : ');
				print('<a href="' . $this->domain_object->GetPrimaryDomain(['lowercase'=>0, 'www'=>1]) . '/users.php?action=viewuser&user=' . urlencode($comment['user']['Username']) . '">');
				print($comment['user']['Username']);
				print('</a>');
				print('</p>');
				print('</div>');
				
				print('<div class="border-2px background-color-gray15 margin-5px float-right">');
				print('<p class="horizontal-right margin-5px font-family-arial">');
				print('Original Post Date : ');
				$date_epoch_time = strtotime($comment['OriginalCreationDate']);
				$full_date = date("F d, Y; H:i:s", $date_epoch_time);
				print($full_date);
				print('</p>');
				print('</div>');
				
				$clear_float_divider_start_args = [
					'class'=>'clear-float',
				];
				
				$divider->displaystart($clear_float_divider_start_args);
				
				$clear_float_divider_end_args = [
				];
				
				$divider->displayend($clear_float_divider_end_args);
				
				$comments_text = strip_tags($comment['Comment']);	
				$comments_text = $this->HyperlinkizeText(['text'=>$comments_text]);
				$comments_text = str_replace("\n", "<BR>\n", $comments_text);
				print($comments_text);
				
				print('</div>');
				print('</div>');
			}
		}
		else
		{
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent font-family-tahoma">');
			print('<div class="horizontal-left margin-5px">');
			print('<p class="margin-0px"><b>No comments so far.  You can be the first!</b></p>');
			print('</div>');
			print('</div>');
		}
		
		print('</center>');
			
				// View Selected Record Tags
			
			// -------------------------------------------------------------
		
		if($this->entry['tag'] && $tag_count)
		{
					// Description Header
				
				// -------------------------------------------------------------
				
			print('<a name="tag"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Tags');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Description Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Show Tags
				
				// -------------------------------------------------------------
			
			print('<center>');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
			print('<div class="border-2px background-color-gray15 margin-5px horizontal-left">');
			
			$tags = $this->entry['tag'];
			
			$max_limit = $tag_count;
			
			shuffle($tags);
			
			for($i = 0; $i < $max_limit; $i++)
			{
				$tag = $tags[$i];
				print('<div class="border-2px background-color-gray13 margin-left-5px margin-top-5px margin-bottom-5px float-left">');
				print('<span class="horizontal-left margin-5px font-family-arial">');
				print('<a href="/view.php?action=browseByTag&tag=' . urlencode($tag['Tag']) . '">');
				print($tag['Tag']);
				
				print(' (');
				print(number_format($this->tag_counts[$tag['Tag']]));
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
			
			print('</div>');
			print('</div>');
			print('</center>');
		}
		
						// Display Navigation
			
			// -------------------------------------------------------------
		
		if($younger_sibling_count || $older_sibling_count)
		{
					// Quote Header
				
				// -------------------------------------------------------------
				
			print('<a name="siblings"></a>');
			
			print('<center>');
			print('<div class="horizontal-center width-95percent">');
			print('<div class="border-2px background-color-gray15 margin-5px float-left">');
			print('<h2 class="horizontal-left margin-5px font-family-arial">');
			print('Navigation');
			print('</h2>');
			print('</div>');
			print('</div>');
			print('</center>');
			
					// Finish Textbody Header
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Start Next/Last Styling
				
				// -------------------------------------------------------------
				
			print('<center>');
			print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
			
					// Display Table Start
				
				// -------------------------------------------------------------
			
			print('<table width="100%">');
			print('<tr>');
			
					// Display Last Entry
				
				// -------------------------------------------------------------
			
			print('<td width="33%" class="width-33percent">');
			
			print('<center>');
			print('<span class="font-family-arial font-size-150percent margin-10px border-2px background-color-gray15">');
			print('<div style="display:inline;" class="margin-10px">');
			print('&lt;&lt; Last Work in ' . $this->parent['Title']);
			print('</div>');
			print('</span>');
			print('</center>');
			
					// Clear Float
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Last Entry?
				
				// -------------------------------------------------------------
			
			if($younger_sibling_count)
			{
				$oldest_young_sibling = $this->younger_siblings[count($this->younger_siblings) - 1];
				$sibling_descriptions = $oldest_young_sibling['description'];
				
				if(count($sibling_descriptions))
				{
					$first_sibling_description = $sibling_descriptions[0];
					$last_sibling_mouseover_text = str_replace('"', '&quot;', $first_sibling_description['Description']);
				}
				
				$younger_sibling_text = '<a href="../' . $oldest_young_sibling['Code'] . '/view.php">';
				$younger_sibling_text .= $oldest_young_sibling['Title'];
				$younger_sibling_text .= '</a>';
			}
			else
			{
				$younger_sibling_text = 'This is the first work.';
			}
			
			print('<div id="header_backgroundimageurl" class="border-2px background-color-gray15 margin-5px"');
			if($last_sibling_mouseover_text)
			{
				print(' title="' . $last_sibling_mouseover_text . '"');
			}
			print('>');
			print('<div class="margin-10px">');
			print('<span class="font-family-tahoma font-size-125percent">');
			
			print($younger_sibling_text);
			
			print('</span>');
			print('</div>');
			print('</div>');
			
					// End Displaying Last Entry
				
				// -------------------------------------------------------------
			
			print('</td>');
			
					// Display Current Entry
				
				// -------------------------------------------------------------
			
			print('<td width="33%" class="width-33percent">');
			
			print('<center>');
			print('<span class="font-family-arial font-size-150percent margin-10px border-2px background-color-gray15">');
			print('<div style="display:inline;" class="margin-10px">');
			print('Current Work in ' . $this->parent['Title']);
			print('</div>');
			print('</span>');
			print('</center>');
			
					// Clear Float
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// This Entry?
				
				// -------------------------------------------------------------
			
			if($description_count)
			{
				$first_description = $this->entry['description'][0];
				
				$current_navigation_item_mouseover = str_replace('"', '&quot;', $first_description['Description']);
			}
			
			print('<div id="header_backgroundimageurl" class="border-2px background-color-gray15 margin-5px"');
			
			if($current_navigation_item_mouseover)
			{
				print(' title="' . $current_navigation_item_mouseover . '"');
			}
			
			print('>');
			print('<div class="margin-10px">');
			print('<span class="font-family-tahoma font-size-125percent">');
			
			print($this->entry['Title']);
			
			print('</span>');
			print('</div>');
			print('</div>');
			
					// End Displaying Last Entry
				
				// -------------------------------------------------------------
				
			print('</td>');
			
					// Display Next Entry
				
				// -------------------------------------------------------------
			
			print('<td width="33%" class="width-33percent">');
			
			print('<center>');
			print('<span class="font-family-arial font-size-150percent margin-10px border-2px background-color-gray15">');
			print('<div style="display:inline;" class="margin-10px">');
			print('Next Work in ' . $this->parent['Title'] . ' &gt;&gt;');
			print('</div>');
			print('</span>');
			print('</center>');
			
					// Clear Float
				
				// -------------------------------------------------------------
									
			$clear_float_divider_start_args = [
				'class'=>'clear-float',
			];
			
			$divider->displaystart($clear_float_divider_start_args);
			
			$clear_float_divider_end_args = [
			];
			
			$divider->displayend($clear_float_divider_end_args);
			
					// Last Entry?
				
				// -------------------------------------------------------------
			
			if($older_sibling_count)
			{
				$youngest_old_sibling = $this->older_siblings[0];
				$sibling_descriptions = $youngest_old_sibling['description'];
				if(count($sibling_descriptions))
				{
					$first_sibling_description = $sibling_descriptions[0];
					$next_sibling_mouseover_text = str_replace('"', '&quot;', $first_sibling_description['Description']);
				}
				
				$next_sibling_text = '<a href="../' . $youngest_old_sibling['Code'] . '/view.php">';
				$next_sibling_text .= $youngest_old_sibling['Title'];
				$next_sibling_text .= '</a>';
			}
			else
			{
				$next_sibling_text = 'This is the last lesson.';
			}
			
			print('<div id="header_backgroundimageurl" class="border-2px background-color-gray15 margin-5px"');
			if($next_sibling_mouseover_text)
			{
				print(' title="' . $next_sibling_mouseover_text . '"');
			}
			print('>');
			print('<div class="margin-10px">');
			print('<span class="font-family-tahoma font-size-125percent">');
			
			print($next_sibling_text);
			
			print('</span>');
			print('</div>');
			print('</div>');
			
					// End Displaying Last Entry
				
				// -------------------------------------------------------------
				
			print('</td>');
			
					// Display Last/Next Ten Header
				
				// -------------------------------------------------------------
			
			print('</tr>');
			
			print('<tr>');
			print('<td colspan="3">');
			
			print('<center>');
			print('<span class="font-family-arial font-size-150percent margin-10px border-2px background-color-gray15">');
			print('<div style="display:inline;" class="margin-10px">');
			print('All Nearby Works in ' . $this->parent['Title']);
			print('</div>');
			print('</span>');
			
			print('<div class="margin-10px border-2px background-color-gray15 width-70percent horizontal-left">');
			print('<ul class="font-family-arial font-size-125percent" type="disc">');
			
			if($younger_sibling_count)
			{
				for($i = 0; $i < $younger_sibling_count; $i++)
				{
					$younger_sibling = $this->younger_siblings[$i];
					
					print('<li>');
					print('<a href="../' . $younger_sibling['Code'] . '/view.php">');
					print($younger_sibling['ListTitle']);
					
					$younger_sibling_descriptions = $younger_sibling['description'];
					$younger_sibling_description_count = count($younger_sibling_descriptions);
					
					if($younger_sibling_description_count && $younger_sibling_descriptions[0]['Description'])
					{
						print(' - ');
						
						print('<em>');
						print($younger_sibling_descriptions[0]['Description']);
						print('</em>');
					}
					
					print('</a>');
					
					if($younger_sibling['association'][0] && $younger_sibling['association'][0]['entry']['id'])
					{
						print(', by ');
						print('<a href="../../people/' . $younger_sibling['association'][0]['entry']['Code'] . '/view.php">');
						print($younger_sibling['association'][0]['entry']['Title']);
						print('</a>');
					}
					
					print('</li>');
				}
			}
			
			print('<li>');
			print($this->entry['ListTitle']);
			
			if($description_count && $this->entry['description'][0]['Description'])
			{
				print(' - ');
				
				print('<em>');
				print($this->entry['description'][0]['Description']);
				print('</em>');
			}
			
			print('</li>');
			if($older_sibling_count)
			{
				for($i = 0; $i < $older_sibling_count; $i++)
				{
					$older_sibling = $this->older_siblings[$i];
					
					print('<li>');
					print('<a href="../' . $older_sibling['Code'] . '/view.php">');
					print($older_sibling['ListTitle']);
					
					$older_sibling_descriptions = $older_sibling['description'];
					$older_sibling_description_count = count($older_sibling_descriptions);
					
					if($older_sibling_description_count && $older_sibling_descriptions[0]['Description'])
					{
						print(' - ');
						
						print('<em>');
						print($older_sibling_descriptions[0]['Description']);
						print('</em>');
					}
					
					print('</a>');
					
					if($older_sibling['association'][0] && $older_sibling['association'][0]['entry']['id'])
					{
						print(', by ');
						print('<a href="../../people/' . $older_sibling['association'][0]['entry']['Code'] . '/view.php">');
						print($older_sibling['association'][0]['entry']['Title']);
						print('</a>');
					}
					print('</li>');
				}
			}
			
			print('</ul>');
			print('</div>');
			
			print('</center>');
			
			print('</td>');
			print('</tr>');
			
					// Display Table End
				
				// -------------------------------------------------------------
			
			print('</tr>');
			print('</table>');
			
					// End Next/Last Styling
				
				// -------------------------------------------------------------
			
			print('</div>');
			print('</center>');
		}
		
				// DEBUG
			
			// -------------------------------------------------------------
		
	#	print("BT: INDEX view.php script, display.php template, CHILDOF-people<BR><BR>");
		
		/*
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
		
				// Display Final Ending Navigation
			
			// -------------------------------------------------------------
		
		$bottom_navigation_args = [
			'thispage'=>'',
		];
		$navigation->DisplayBottomNavigation($bottom_navigation_args);
		
		//print("<script type='text/javascript'>
		//		$(window).load( function() {
		//			responsiveVoice.speak(\"$message\", \"UK English Female\");
		//		});
		//</script>");
	}
?>