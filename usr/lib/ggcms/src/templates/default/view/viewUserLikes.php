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
	
	if(!$div_mouseover)
	{
		if($this->primary_host_record['Classification'])
		{
			$div_mouseover = str_replace('"', '\'', $this->primary_host_record['Classification']);
		}
	}
	
	if(!$image_mouseover)
	{
		if($this->primary_host_record['Subject'])
		{
			$image_mouseover = str_replace('"', '\'', $this->primary_host_record['Subject']);
		}
	}
	
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
			}
		}
		
		if(!$primary_image)
		{
			$primary_image = $this->primary_host_record['PrimaryImageLeft'];
			$primary_image_text = $this->primary_host_record['Classification'];
		}

				// Mouseover Values
			
			// -------------------------------------------------------------
		
		$div_mouseover = '';
		
		if($this->entry['quote'] && $this->entry['quote'][0])
		{
			$random_quote = $this->entry['quote'][array_rand($this->entry['quote'], 1)];
			
			$div_mouseover = '&quot;' . str_replace('"', '\'', $random_quote['Quote']) . '&quot; -- ' . str_replace('"', '\'', $random_quote['Source']);
		}
		
		if(!$div_mouseover)
		{
			if($this->primary_host_record['Subject'])
			{
				$div_mouseover = str_replace('"', '\'', $this->primary_host_record['Subject']);
			}
		}
	}
	
			// Display Header
		
		// -------------------------------------------------------------
	
	$header_primary_args = [
		'title'=>'Viewing Users Who Liked ' . $this->header_title_text,
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
		$breadcrumbs = new module_breadcrumbs(['that'=>$this, 'title'=>'Who Liked This?']);
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
	
			// Browse Users
		
		// -------------------------------------------------------------
	
	print('<center>');
	
	print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent">');
	print('<div class="border-2px background-color-gray15 margin-5px horizontal-left font-family-arial">');
	
	print('<div class="margin-5px horizontal-left font-family-tahoma">');
	
	print('<strong>Users Who Like This : </strong>');
	
	if($this->likes && count($this->likes))
	{
		$users = [];
		$url = $this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]) . '/users.php?action=viewuser&';
		foreach($this->likes as $like)
		{
			$new_user = '';
			if($like['User.Username'])
			{
				$new_url = $url . 'user=' . urlencode($like['User.Username']);
				$new_user = '<a href="' . $new_url . '">' . $like['User.Username'] . '</a>';
			}
			else
			{
				$new_url = $url . 'userid=' . $like['User.id'];
				$new_user = '<a href="' . $new_url . '">User #' . $like['User.id'] . '</a>';
			}
			
			$users[] = $new_user;
		}
		
		print(implode(', ', $users));
		print('.');
	}
	else
	{
		print('There are plenty!  They just haven\'t clicked the "like" button yet.');
	}
	
	print('</div>');
	
	print('</div>');
	print('</div>');
	
	print('</center>');
	
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