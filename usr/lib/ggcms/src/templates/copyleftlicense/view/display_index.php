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
	$entryheader = new module_entryindexheader([
		'that'=>$this,
		'main_text'=>$this->header_title_text,
		'sub_text'=>$sub_text,
		'sub_title'=>$sub_title,
	]);
	
	$entryheader->Display();
	
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
			
			// Introduction
		
		// -------------------------------------------------------------
	
	print('<center>');
	print('<div class="horizontal-center width-70percent">');
	print('<div class="border-2px background-color-gray15 margin-5px float-left">');
	
	print('<strong>');
	print(str_replace('<p>', '<p class="horizontal-left margin-5px font-family-tahoma">', $this->entry['textbody'][0]['Text']));
	
	print('<p class="horizontal-left margin-5px font-family-tahoma" title="');
	
	print(' (Last Updated: ');
	$date_epoch_time = strtotime($this->child_record_stats['LastModificationDate']);
	$full_date = date("F d, Y; H:i:s", $date_epoch_time);
	print($full_date);
	print('.)');
	
	print('">');
	print('This archive contains ' . number_format($this->child_record_stats['ChildRecordCount']) . ' texts, with ' . number_format($this->child_record_stats['ChildWordCount']) . ' words or ' . number_format($this->child_record_stats['ChildCharacterCount']) . ' characters.');
	
	print('</strong>');
	print('</p>');
	
	print('</div>');
	print('</div>');
	print('</center>');
							
	$clear_float_divider_start_args = [
		'class'=>'clear-float',
	];
	
	$divider->displaystart($clear_float_divider_start_args);
	
	$clear_float_divider_end_args = [
	];
	
	$divider->displayend($clear_float_divider_end_args);
	
			// View Selected Record List
		
		// -------------------------------------------------------------
	
	//print("BT: INDEX view.php script, display.php template<BR><BR>");
	for($i = 0; $i < count($this->children); $i++)
	{
		$child = $this->children[$i];
		
		$child_title =
			'<a href="' . $child['Code'] . '/view.php?action=index">' .
			$child['Title'] . ' : ' . $child['Subtitle'] .
			'</a>';
		
		print('<center>');
		print('<div class="horizontal-center width-95percent">');
		print('<div class="border-2px background-color-gray15 margin-5px float-left width-100percent">');
		

		print('<div class="border-2px background-color-gray15 margin-5px float-left">');
		print('<div class="border-2px background-color-gray15 margin-5px float-left">');
		print('<div class="background-color-gray0">');
		print('<a href="' . $child['Code'] . '/view.php?action=index">');
		print('<img src="');
		print($this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]));
		print('/image/');
		print(implode('/', str_split($child['image'][0]['FileDirectory'])));
		print('/');
		print($child['image'][0]['IconFileName']);
		print('">');
		print('</a>');
		print('</div>');
		print('</div>');
		print('</div>');
		
		print('<h2 class="horizontal-left margin-5px font-family-arial font-size-250percent">');
		print('<strong>');
		print('<a href="' . $child['Code'] . '/view.php?action=index">');
		print($child_title);
		print('</a>');
		print('</strong>');
		print('</h2>');
		
		if($child['description'][0]['Description'])
		{
			print('<p class="horizontal-left font-family-arial margin-5px">');
			
			print('<b>' . $child['description'][0]['Description'] . '</b><br>');
			
			print('</p>');
		}
		
		if($child['quote'][0])
		{
			$random_quote = $child['quote'][array_rand($child['quote'], 1)];
			
			print('<center>');
			print('<div class="horizontal-center ">');
			print('<blockquote class="horizontal-left font-family-arial"><em>"');
			print(str_replace('"', '\'', $random_quote['Quote']));
			print('"');
			if($random_quote['Source'])
			{
				print(' -- ');
				print($random_quote['Source']);
			}
			print('</em></blockquote>');
			print('</div>');
			print('</center>');
		}
		
		print('</div>');
		print('</div>');
		print('</center>');
								
		$clear_float_divider_start_args = [
			'class'=>'clear-float',
		];
		
		$divider->displaystart($clear_float_divider_start_args);
		
		$clear_float_divider_end_args = [
		];
		
		$divider->displayend($clear_float_divider_end_args);
		
		$grandchildren = $child['children'];
		
		if($grandchildren)
		{
			$grandchildren_count = count($grandchildren);
			
			if($grandchildren_count)
			{
				print('<div class="horizontal-center width-90percent">');
				for($j = 0; $j < $grandchildren_count; $j++)
				{
					$grandchild = $grandchildren[$j];
					
					print('<div class="horizontal-center width-100percent background-color-gray14 border-2px margin-top-5px">');
					
					unset($display_image);
					
					if($grandchild['image'])
					{
						$grandchild_images = $grandchild['image'];
						$grandchild_image_count = count($grandchild_images);
						if($grandchild_image_count)
						{
							shuffle($grandchild_images);
							$grandchild_image = $grandchild_images[0];
							$display_image = $grandchild_image;
						}
					}
					
					print('<div class="border-2px background-color-gray15 margin-5px float-left">');
					print('<div class="border-2px background-color-gray15 margin-5px float-left">');
					print('<div class="height-100px width-100px background-color-gray0">');
					print('<div class="vertical-specialcenter">');
					print('<a href="' . $child['Code'] . '/' . $grandchild['Code'] . '/view.php">');
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
					
					if($grandchild['association'] && count($grandchild['association']))
					{
						$title_max = 30;
					}
					
					$grandchild_title_full = $grandchild['Title'];
					$popup_title = 0;
					if(strlen($grandchild_title_full) > $title_max)
					{
						$grandchild_title_full = substr($grandchild_title_full, 0, $title_max) . '...';
						$popup_title = 1;
					}
					
					$grandchild_title = '<a href="' . $child['Code'] . '/' . $grandchild['Code'] . '/view.php"';
					
					if($popup_title)
					{
						$grandchild_title .= ' title="' . str_replace('"', '&quot;', $grandchild['Title']) . '"';
					}
					
					$grandchild_title .= '>';
					
					$grandchild_title .= $grandchild_title_full;
					$grandchild_title .= '</a>';
					
					if($grandchild['association'] && count($grandchild['association']))
					{
						$author = $grandchild['association'][0]['entry'];
						$grandchild_title .= ', by ';
						
						$grandchild_author_full_title = $author['Title'];
						
						$popup_title = 0;
						if(strlen($grandchild_author_full_title) > 20)
						{
							$grandchild_author_full_title = substr($grandchild_author_full_title, 0, 20) . '...';
							$popup_title = 1;
						}
						
						$grandchild_title .= '<a href="people/' . $author['Code'] . '/view.php"';
						
						if($popup_title)
						{
							$grandchild_title .= ' title="' . str_replace('"', '&quot;', $author['Title']) . '"';
						}
						
						$grandchild_title .= '>';
						
						$grandchild_title .= $grandchild_author_full_title;
						$grandchild_title .= '</a>';
					}
					
					$div_mouseover = '';
					
					if($grandchild['textbody'])
					{
						$text_bodies = $grandchild['textbody'];
						
						$text_body_count = count($text_bodies);
						if($text_body_count)
						{
							$first_textbody = $text_bodies[0];
							
							$div_mouseover .= number_format($first_textbody['WordCount']) . ' Words / ' . number_format($first_textbody['CharacterCount']) . ' Characters';
						}
					}
					
					print('<div id="header_backgroundimageurl" class="border-2px background-color-gray15 margin-5px float-left" title="' . $div_mouseover . '">');

print('<div class="span-header-3"><h3 style="margin:5px;padding:5px;display: inline-block;border:black 2px solid;background-color:#FFFFFF;" class="header-3 padding-0px margin-5px horizontal-left font-family-tahoma">' . $grandchild_title . '</h3></div>');



print('</div>');
					
					print('<p class="horizontal-left margin-5px font-family-arial">');
					
					$time_frame = '';
					
					if($grandchild['eventdate'])
					{
						$grandchild_event_count = count($grandchild['eventdate']);
						for($k = 0; $k < $grandchild_event_count; $k++)
						{
							$grandchild_event = $grandchild['eventdate'][$k];
							
							if($grandchild_event['Title'] == 'Birth Day')
							{
								$birth_event = $grandchild_event;
							}
							elseif($grandchild_event['Title'] == 'Death Day')
							{
								$death_event = $grandchild_event;
							}
							
							if($birth_event && $death_event)
							{
								$k = $grandchild_event_count;
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
					
					if($grandchild['Subtitle'])
					{
						if($time_frame)
						{
							print(' ~ ');
						}
						
						print('<strong>');
						print($grandchild['Subtitle']);
						print('</strong>');
					}
					
					if($grandchild['description'])
					{
						$description = $grandchild['description'][0];
						
						if($description && $description['Description'])
						{
							print('<em>');
							if($time_frame || $grandchild['Subtitle'])
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
					
					if($grandchild['quote'])
					{
						$grandchild_quotes = $grandchild['quote'];
						$grandchild_quotes_count = count($grandchild_quotes);
						$max_limit = $grandchild_quotes_count;
						if($max_limit > 3)
						{
							$max_limit = 3;
						}
						shuffle($grandchild_quotes);
						for($k = 0; $k < $max_limit; $k++)
						{
							$quote = $grandchild_quotes[$k];
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
						$printed = 0;
						if($grandchild['textbody'])
						{
							$text_bodies = $grandchild['textbody'];
							
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
									if($time_frame || $grandchild['Subtitle'] || $grandchild['description'])
									{
										print('<br>');
									}
									print($text_display);
									$printed = 1;
									
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
						
						if(!$printed)
						{
							$great_grand_children = $grandchild['children'];
							if($great_grand_children && is_array($great_grand_children))
							{
								$great_grand_children_count = count($great_grand_children);
								
								if($great_grand_children_count)
								{
									$great_grand_children_display = $entrysort->Sort(['entries'=>$great_grand_children]);
									
									unset($great_grand_child);
									foreach($great_grand_children_display as $single_grand_child)
									{
										if(!$great_grand_child)
										{
											$great_grand_child = $single_grand_child['textbody'][0];
										}
									}
									print(strip_tags($great_grand_child['FirstThousandCharacters']));
									
									if(strlen($great_grand_child['FirstThousandCharacters']) == 1000)
									{
										print('...');
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
					
					if($grandchild['tag'])
					{
						$tag_count = count($grandchild['tag']);
						
						if($tag_count)
						{
							$tags = $grandchild['tag'];
							$max_limit = $tag_count;
							if($max_limit > 10)
							{
								$max_limit = 10;
							}
							shuffle($tags);
							
							for($l = 0; $l < $max_limit; $l++)
							{
								$tag = $tags[$l];
								print('<div class="border-2px background-color-gray15 margin-left-5px margin-bottom-5px float-left">');
								print('<span class="horizontal-left margin-5px font-family-arial">');
								print('<a href="' . $child['Code'] . '/view.php?action=browseByTag&tag=' . urlencode($tag['Tag']) . '">');
								print($tag['Tag']);
								
								print(' (');
								print(number_format($this->tag_counts['grandchildren'][$child['Code']][$tag['Tag']]));
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
				}
				print('</div>');
			}
		}
	}
	
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
		'sharewithtext'=>'Share With',
		'socialmediasharelinkargs'=>[
			'url'=>$this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]) . '/',
			'title'=>$this->header_title_text,
			'desc'=>$image_mouseover,
			'provider'=>$this->domain_object->primary_domain_lowercased,
		],
	];
	$social_media_share_links = new module_socialmediasharelinks($social_media_share_links_args);
	
				// Start Display Share Options
			
			// -------------------------------------------------------------

	print('<center>');
	print('<div class="margin-5px horizontal-center width-80percent">');
	print('<div class="margin-5px border-2px background-color-gray13 float-left">');
	print('<div class="margin-5px horizontal-left font-family-arial float-left">');
		
			// Display "Share" Text
			// -------------------------------------------------------

	print('<table border="0" class="padding-0px margin-0px">');
	print('<tr valign="top">');
	print('<td valign="top">');
	print('<div class="font-family-tahoma font-size-150percent margin-10px border-2px background-color-gray10"><span class="margin-5px"><nobr>' . 'Share' . ' :</nobr></span></div>');
	print('</td>');
	print('<td>');
		
				// Display Social Networking Options
			
			// -------------------------------------------------------------
	
	$social_media_share_links->display();

				// Conclude Table
				// -------------------------------------------------------

	print('</td>');
	print('</tr>');
	print('</table>');
	
				// End Display Share Options
			
			// -------------------------------------------------------------
	
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
	
			// Display Final Ending Navigation
		
		// -------------------------------------------------------------
	
	$bottom_navigation_args = [
		'thispage'=>'Home',
	];
	$navigation->DisplayBottomNavigation($bottom_navigation_args);
	
?>