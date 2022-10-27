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
	
	$good_header_text = $this->domain_object->primary_domain . ' System Status : Entry Symmetry Check';
	
	ggreq('modules/html/entry-header.php');
	ggreq('modules/html/entry-index-header.php');
	$entryindexheader = new module_entryindexheader([
		'that'=>$this,
		'main_text'=>$this->domain_object->primary_domain . ' -&gt; ' . $good_header_text,
		'sub_text'=>$good_header_text,
	]);
	
	$entryindexheader->Display();
			
			// Display 'Back to Master C.' Link
		
		// -------------------------------------------------------------
	
	$return_to_master_c_args = [
		'title'=>'Return to the Master Control Program',
		'image'=>'master-c-icon.jpg',
		'divmouseover'=>'The Grand Master C.',
		'imagemouseover'=>'Master C is in the house!',
		'level'=>3,
		'divclass'=>'width-400px border-2px margin-left-20px margin-top-5px background-color-gray13',
		'textclass'=>'margin-0px horizontal-center vertical-center',
		'imagedivclass'=>'border-2px margin-5px background-color-gray10',
		'imageclass'=>'border-1px height-75px',
		'domainobject'=>$this->domain_object,
		'leftimageenable'=>1,
		'rightimageenable'=>0,
		'link'=>'master-c.php',
	];
	
	$header->display($return_to_master_c_args);
			
			// Display Instructions
		
		// -------------------------------------------------------------
	
	$results_count = count($this->StatusDataArray);
	if($results_count) {
		$primary_url = $this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]);
		
		$version_list_display_args = [
			'options'=>[
				'tableheaders'=>0,
				'tableclass'=>'width-70percent horizontal-center border-2px background-color-gray13 margin-top-14px',
				'rowclass'=>'border-1px horizontal-left',
				'cellclass'=>[
					'border-1px vertical-top',
					'border-1px width-100percent vertical-top',
				],
			],
			'list'=>[[
				'Note: Examined a total number of ' . number_format($this->entry_code_count) . ' entry records.  A total of ' . number_format(count($this->StatusDataArray)) . ' asymmetric records were discovered.',
			]],
		];
		$generic_list->Display($version_list_display_args);
	}
	
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
	
			// Display Form Save Button : Save
		
		// -------------------------------------------------------------
	
	print('<center>');
	
	
	print('<b>Master Table Code :</b> <input type="text" name="master-table"');
	if($this->master_table) {
		print(' value="' . $this->master_table . '"');
	}
	print('> ');
	
	$type_args = [
		'type'=>'submit',
		'name'=>'detect-entry-symmetry',
		'class'=>'margin-5px',
		'value'=>'Detect Entry Symmetry',
	];
	
	$form->DisplayFormField($type_args);
	
	print('</center>');
	
			// Display Form Elements : End
		
		// -------------------------------------------------------------
	
	$end_form_args = [];
	$form->EndForm($end_form_args);
	
	$divider_end_args = [];
	$divider->displayend($divider_end_args);
	
			// Display Results
		
		// -------------------------------------------------------------
	
	if($results_count) {
				// Display Results : Start
			
			// -------------------------------------------------------------
		
		$divider_padding_start_args = [
			'class'=>'horizontal-center width-80percent margin-top-5px border-2px',
		];
		$divider->displaystart($divider_padding_start_args);
		
				// Display Results : Entries
			
			// -------------------------------------------------------------
		
		print('<div style="text-align:left;" class="width-100percent"><ul style="text-align:left;">');
		
		for($i = 0; $i < $results_count; $i++) {
			$result = $this->StatusDataArray[$i];
			print('<li>' . $result . '</li>');
		}
		print('</ul></div>');
	
				// Display Results : End
			
			// -------------------------------------------------------------
		
		$divider_end_args = [];
		$divider->displayend($divider_end_args);
	}
	
?>