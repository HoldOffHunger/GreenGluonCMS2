<?php
		
			// Standard Requires
		
		// -------------------------------------------------------------

	require(GGCMS_DIR . 'modules/spacing.php');
		
				// Timeframe
			
			// -------------------------------------------------------------
	
	require(GGCMS_DIR . 'modules/html/entry-date.php');
	$entrydate = new module_entrydate(['that'=>$this]);
	$time_data = $entrydate->getSimpleData();
	$time_frame = $time_data['text'];
	
				// Header_REAL
			
			// -------------------------------------------------------------
	
	require(GGCMS_DIR . 'modules/html/entry-header.php');
	$entryheader = new module_entryheader(['that'=>$this, 'time_frame'=>$time_frame]);
	
	require(GGCMS_DIR . 'modules/html/header.php');
	$header = new module_header;
	
	require(GGCMS_DIR . 'modules/html/navigation.php');
	$navigation_args = [
		'globals'=>$this->handler->globals,
		'languageobject'=>$this->language_object,
		'domainobject'=>$this->domain_object,
	];
	$navigation = new module_navigation($navigation_args);
	
	require(GGCMS_DIR . 'modules/html/entry-sort.php');
	$entrysort = new module_entrysort(['that'=>$this]);
	
	require(GGCMS_DIR . 'modules/html/image-list.php');
	$imagelist = new module_imagelist(['that'=>$this]);
	
	require(GGCMS_DIR . 'modules/html/image-list-navigation.php');
	$entrylistnavigation = new module_imagelistnavigation(['that'=>$this]);
	
	$breadcrumbs_title = 'Image Gallery of';
	
	if($this->entry['ChildAdjective']) {
		$breadcrumbs_title .= ' ' . $this->entry['ChildAdjective'];
	}
	
	if($this->entry['ChildNounPlural']) {
		$breadcrumbs_title .= ' ' . $this->entry['ChildNounPlural'];
	}
	
	$this->header_title_text .= ' &mdash; ' . $breadcrumbs_title;
	
			// Display Header
		
		// -------------------------------------------------------------
	
	if(!$this->Param('headless')) {
		$entryheader->Display();
	}
	
			// Admin Controls
		
		// -------------------------------------------------------------
	
	if(!$this->Param('headless') && $this->authentication_object->user_session['UserAdmin.id']) {
		require(GGCMS_DIR . 'modules/html/entry-controls.php');
		$entry_controls = new module_entrycontrols;
		$entry_controls->Display(['that'=>$this, 'file'=>__FILE__]);
	}
	
	if(!$this->Param('headless')) {
			// Start Top Bar
		
		// -------------------------------------------------------------
	
		print('<div class="horizontal-center width-95percent margin-top-5px">');
	
			// Breadcrumbs Info
		
		// -------------------------------------------------------------
		require(GGCMS_DIR . 'modules/html/breadcrumbs.php');
		$breadcrumbs = new module_breadcrumbs(['that'=>$this, 'title'=>$breadcrumbs_title]);
		$breadcrumbs->Display();
		
				// Login Info
			
			// -------------------------------------------------------------
			
		require(GGCMS_DIR . 'modules/html/auth.php');
		$auth = new module_auth(['that'=>$this]);
		$auth->Display();
	
				// End Top Bar
			
			// -------------------------------------------------------------
		
		print('</div>');
	
		print('<div class="clear-float"></div>');
	}
	
			// View Browsing Numbers
		
		// -------------------------------------------------------------
	
	$header_secondary_args = [
		'title'=>'Browsing : ' . $this->child_record_start_index . ' to ' . $this->child_record_end_index . ' of ' . number_format($this->image_count),
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
	
	print('<form class="margin-0px" method="POST">');
	
	$browsing_options = 'Results Per Page : <select id="perpage" name="perpage">';
	
	for($i = 10; $i <= 200; $i += 10) {
		$browsing_options .= '<option value="' . $i . '"';
		
		if($i == $this->perpage && !$this->custom_per_page_selected) {
			$browsing_options .= ' SELECTED="SELECTED"';
		}
		
		$browsing_options .= '>' . $i . '</option>';
	}
	
	$browsing_options .= '<option value="custom"';
	if($this->custom_per_page_selected) {
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
	
	print('</form>');
	
	print('<div class="clear-float"></div>');
	
			// View Selected Record List Pages
		
		// -------------------------------------------------------------
		
	$entrylistnavigation->Display([]);
	
			// View Selected Record List Pages
		
		// -------------------------------------------------------------
	
	print('<div class="horizontal-center width-90percent">');
	
	$imagelist->Display(['children'=>$this->children]);
	
	print('</div>');
	
			// View Selected Record List Pages
		
		// -------------------------------------------------------------
	
	print('<br>');
	
	$entrylistnavigation->Display([]);
	
			// Display Final Ending Navigation
		
		// -------------------------------------------------------------
	
	if(!$this->Param('headless')) {
		$bottom_navigation_args = [
			'thispage'=>'',
		];
		$navigation->DisplayBottomNavigation($bottom_navigation_args);
	}
	
			// Display Debug
		
		// -------------------------------------------------------------
	
	require(GGCMS_DIR . 'modules/html/debug.php');
	$debug = new module_debug(['that'=>$this]);
	#$debug->DisplayBasicRecords([]);
	
?>