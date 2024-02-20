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
	
			// Basic Divider Arguments
		
		// -------------------------------------------------------------
	
	$divider_padding_start_args = [
		'class'=>'margin-5px padding-5px',
	];
	
	$divider_end_args = [
	];
	
			// Display Header
		
		// -------------------------------------------------------------
	
	$header_primary_args = [
		'title'=>$this->domain_object->primary_domain . ' System Status : Page Statistics',
		'image'=>'system-status-icon.jpg',
		'divmouseover'=>'The Grand Master C.',
		'imagemouseover'=>'Master C is in the house!',
		'level'=>1,
		'divclass'=>'horizontal-center width-100percent border-2px margin-top-5px background-color-gray13',
		'textclass'=>'margin-0px horizontal-center vertical-center padding-top-22px margin-bottom-10px',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px height-75px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>1,
		'rightimageenable'=>1,
	];
	
	$header->display($header_primary_args);
	
			// Display Page Statistics Results
		
		// -------------------------------------------------------------
	
	if($this->stats_retrieved)
	{
		$divider->displaystart($divider_padding_start_args);
		
		if($this->host_name == 'allhosts')
		{
			$dates_or_clients = 'Clients';
		}
		else
		{
			$dates_or_clients = 'Dates';
		}
		
		$version_list_display_args = [
			'options'=>[
				'tableheaders'=>0,
				'tableclass'=>'width-70percent horizontal-center border-2px background-color-gray13 margin-top-5px',
				'rowclass'=>'border-1px horizontal-left',
				'cellclass'=>[
					'border-1px vertical-top',
					'border-1px width-100percent vertical-top',
				],
			],
			'list'=>[
				[
					'<nobr>' . $dates_or_clients . ' Accounted For</nobr>', count($this->page_statistics) - 1,
				],
			],
		];
		$generic_list->Display($version_list_display_args);
		
		$divider->displayend($divider_end_args);
	}
	
			// Cloning Information
		
		// -------------------------------------------------------------
	
	else
	{
		$divider->displaystart($divider_padding_start_args);
		
		$version_list_display_args = [
			'options'=>[
				'tableheaders'=>0,
				'tableclass'=>'width-70percent horizontal-center border-2px background-color-gray13 margin-top-5px',
				'rowclass'=>'border-1px horizontal-left',
				'cellclass'=>[
					'border-1px vertical-top',
					'border-1px width-100percent vertical-top',
				],
			],
			'list'=>[
				[
					'Enter the domain and the date-range of statistics you would like to see.',
				],
			],
		];
		$generic_list->Display($version_list_display_args);
		
		$divider->displayend($divider_end_args);
	}
	
			// Display Admin Errors
		
		// -------------------------------------------------------------
	
	$error_header_displayed = 0;
	
	if($this->authentication_object->CheckAuthenticationForCurrentObject_IsAdmin() && $this->admin_errors)
	{
		$error_header_displayed = 1;
		$divider->displaystart($divider_padding_start_args);
		
		$version_list_display_args = [
			'options'=>[
				'tableheaders'=>0,
				'tableclass'=>'width-50percent horizontal-center border-2px background-color-gray13 margin-top-5px',
				'rowclass'=>'border-1px horizontal-left',
				'cellclass'=>[
					'border-1px vertical-top horizontal-center font-size-150percent',
					'border-1px width-100percent vertical-top horizontal-center',
				],
			],
			'list'=>[
				[
					'There was an error with your selection.',
				],
			],
		];
		$generic_list->Display($version_list_display_args);
		
		$divider->displayend($divider_end_args);
		
		foreach($this->admin_errors_display as $admin_error_to_display)
		{
			$divider->displaystart($divider_padding_start_args);
			
			$version_list_display_args = [
				'options'=>[
					'tableheaders'=>0,
					'tableclass'=>'width-70percent horizontal-center border-2px background-color-gray13 margin-top-5px',
					'rowclass'=>'border-1px horizontal-left',
					'cellclass'=>[
						'border-1px vertical-top',
						'border-1px width-100percent vertical-top',
					],
				],
				'list'=>$admin_error_to_display,
			];
			$generic_list->Display($version_list_display_args);
			
			$divider->displayend($divider_end_args);
		}
	}
	
			// Display Errors
		
		// -------------------------------------------------------------
	
	if($this->errors)
	{
		if(!$error_header_displayed)
		{
			$divider->displaystart($divider_padding_start_args);
			
			$version_list_display_args = [
				'options'=>[
					'tableheaders'=>0,
					'tableclass'=>'width-50percent horizontal-center border-2px background-color-gray13 margin-top-5px',
					'rowclass'=>'border-1px horizontal-left',
					'cellclass'=>[
						'border-1px vertical-top horizontal-center font-size-150percent',
						'border-1px width-100percent vertical-top horizontal-center',
					],
				],
				'list'=>[
					[
						'There was an error with your submission.',
					],
				],
			];
			$generic_list->Display($version_list_display_args);
			
			$divider->displayend($divider_end_args);
		}
		
		foreach($this->errors_display as $error_to_display)
		{
			$divider->displaystart($divider_padding_start_args);
			
			$version_list_display_args = [
				'options'=>[
					'tableheaders'=>0,
					'tableclass'=>'width-70percent horizontal-center border-2px background-color-gray13 margin-top-5px',
					'rowclass'=>'border-1px horizontal-left',
					'cellclass'=>[
						'border-1px vertical-top',
						'border-1px width-100percent vertical-top',
					],
				],
				'list'=>$error_to_display,
			];
			$generic_list->Display($version_list_display_args);
			
			$divider->displayend($divider_end_args);
		}
	}
	
			// Display Statistics Success
		
		// -------------------------------------------------------------
	
	if($this->stats_retrieved)
	{
				// Display Results : Start
			
			// -------------------------------------------------------------
		
		$divider_padding_start_args = [
			'class'=>'horizontal-center width-80percent margin-top-5px border-2px',
		];
		
		$divider->displaystart($divider_padding_start_args);
		
				// Display Form Elements : Standard Elements
			
			// -------------------------------------------------------------
		
					// Display Form Elements : ID Text Input
				
				// -------------------------------------------------------------
			
		$table_start_args = [
			'id'=>$element,
			'tableclass'=>'width-100percent blank-element',
			'tableborder'=>'3',
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_StartTable($table_start_args);
		
					// Display Form Elements : Statistics From Hostname
				
				// -------------------------------------------------------------
		
print('<nobr>Hostname</nobr>');
		
		
		$separate_cells_args = [
			'cellwidth'=>'99%',
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_SeparateCells($separate_cells_args);
		
print($this->host_name);
		
		
		$separate_cells_args = [
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_SeparateCellsAndRows($separate_cells_args);
		
					// Display Form Elements : Clone Files
				
				// -------------------------------------------------------------
		
print('<nobr>Date Range</nobr>');
		
		
		$separate_cells_args = [
			'cellwidth'=>'99%',
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_SeparateCells($separate_cells_args);
		
print($this->date_range);
		
		
				// Display Results : End
			
			// -------------------------------------------------------------
		
		$table_end_args = [
		];
		$table->DisplayComponent_EndTable($table_end_args);
		
		$divider_end_args = [
		];
		$divider->displayend($divider_end_args);
	
			// Display Page Statistics Results
		
		// -------------------------------------------------------------
	
		if($this->stats_retrieved)
		{
			if($this->host_name == 'allhosts')
			{
				foreach($this->page_statistics as $client => $stats)
				{
					if($stats && count($stats))
					{
						$stats_count = count($stats);
						
						for($i = 1; $i < $stats_count; $i++) {
							$stats[$i][0] = '<NOBR>' . $stats[$i][0] . '</NOBR>';
						}
						
						$header_primary_args = [
							'title'=>$client,
							'divmouseover'=>'The Grand Master C.',
							'imagemouseover'=>'Master C is in the house!',
							'level'=>3,
							'divclass'=>'horizontal-center width-70percent border-2px margin-top-5px background-color-gray13',
							'textclass'=>'margin-0px horizontal-center vertical-center',
							'imagedivclass'=>'border-2px margin-5px background-color-gray10',
							'imageclass'=>'border-1px height-75px',
							'domainobject'=>$this->domain_object,
							'leftimageenable'=>1,
							'rightimageenable'=>1,
						];
						
						$header->display($header_primary_args);
						$version_list_display_args = [
							'options'=>[
								'tableheaders'=>0,
								'tableclass'=>'width-80percent horizontal-center border-2px background-color-gray13 margin-top-5px',
								'rowclass'=>'border-1px horizontal-left',
								'cellclass'=>[
									'border-1px vertical-top',
									'border-1px width-100percent vertical-top',
									'border-1px width-100percent vertical-top',
								],
							],
							'list'=>$stats,
						];
						$generic_list->Display($version_list_display_args);
					}
				}
			}
			else
			{
				$version_list_display_args = [
					'options'=>[
						'tableheaders'=>0,
						'tableclass'=>'width-80percent horizontal-center border-2px background-color-gray13 margin-top-5px',
						'rowclass'=>'border-1px horizontal-left',
						'cellclass'=>[
							'border-1px vertical-top',
							'border-1px width-100percent vertical-top',
							'border-1px width-100percent vertical-top',
						],
					],
					'list'=>$this->page_statistics,
				];
				$generic_list->Display($version_list_display_args);
			}
		}
	}
	
			// Display Clone Failure
		
		// -------------------------------------------------------------
		
	else
	{
				// Display Form Elements : Start
			
			// -------------------------------------------------------------
		
		$divider_padding_start_args = [
			'class'=>'horizontal-center width-80percent margin-top-5px border-2px',
		];
		
		$divider->displaystart($divider_padding_start_args);
		
		$start_form_args = [
			'action'=>0,
			'method'=>'post',
			'files'=>0,
			'formclass'=>'margin-0px',
		];
		
		$form->StartForm($start_form_args);
		
				// Display Form Elements : Standard Elements
			
			// -------------------------------------------------------------
		
					// Display Form Elements : Old Hostname Input
				
				// -------------------------------------------------------------
			
		$table_start_args = [
			'id'=>$element,
			'tableclass'=>'width-100percent blank-element',
			'tableborder'=>'3',
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_StartTable($table_start_args);
		
print('<nobr>Hostname</nobr>');
		
		
		$separate_cells_args = [
			'cellwidth'=>'99%',
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_SeparateCells($separate_cells_args);
		
		$type_args = [
			'type'=>'select',
			'name'=>'hostname',
		];
		
		$form->DisplayFormField($type_args);
		
		$separate_cells_args = [
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_SeparateCellsAndRows($separate_cells_args);
		
					// Display Form Elements : New Hostname Input
				
				// -------------------------------------------------------------
			
print('<nobr>Date Range</nobr>');
		
		
		$separate_cells_args = [
			'cellwidth'=>'99%',
			'cellvalign'=>'top',
		];
		$table->DisplayComponent_SeparateCells($separate_cells_args);
		
		$type_args = [
			'type'=>'select',
			'name'=>'daterange',
			'options'=>[
				[
					'optionvalue'=>'lastmonth',
					'optiontitle'=>'Last Month',
				],
				[	'optionvalue'=>'lastyear',
					'optiontitle'=>'Last Year',
				],
				[
					'optionvalue'=>'alltime',
					'optiontitle'=>'All Time',
				],
			],
		];
		
		$form->DisplayFormField($type_args);
		
		$table_end_args = [
		];
		$table->DisplayComponent_EndTable($table_end_args);
		
		
				// Display Form Elements : Hidden Args
			
			// -------------------------------------------------------------
		
		$type_args = [
			'type'=>'hidden',
			'name'=>'action',
			'value'=>'PageStatistics',
		];
		
		$form->DisplayFormField($type_args);
		
		$login_args = [
			'value'=>'View Statistics',
			'type'=>'submit',
			'class'=>'margin-5px',
			'name'=>'view-statistics',
		];
		
		$form->DisplayFormField($login_args);
		
				// Display Form Elements : End
			
			// -------------------------------------------------------------
		
		$end_form_args = [
		];
		$form->EndForm($end_form_args);
		
		$divider_end_args = [
		];
		$divider->displayend($divider_end_args);
	}
	
?>