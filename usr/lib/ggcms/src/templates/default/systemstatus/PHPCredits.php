<?php
		
			// Standard Requires
		
		// -------------------------------------------------------------

	ggreq('modules/spacing.php');
	
	ggreq('modules/html/text.php');
	$text = new module_text;
	
	ggreq('modules/html/iframe.php');
	$iframe = new module_iframe;
	
	ggreq('modules/html/form.php');
	$form = new module_form;
	
	ggreq('modules/html/divider.php');
	$divider = new module_divider;
	
	ggreq('modules/html/table.php');
	$table = new module_table;
	
	ggreq('modules/html/header.php');
	$header = new module_header;
	
			// Basic Divider Arguments
		
		// -------------------------------------------------------------
	
	$divider_padding_start_args = array(
		'class'=>'margin-5px padding-5px',
	);
	
	$divider_end_args = array(
	);
	
			// Display Header
		
		// -------------------------------------------------------------
	
	$header_primary_args = array(
		'title'=>$this->domain_object->primary_domain . ' System Status : PHP Credits',
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
	);
	
	$header->display($header_primary_args);
			
			// Display 'Back to Master C.' Link
		
		// -------------------------------------------------------------
	
	$return_to_master_c_args = array(
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
	);
	
	$header->display($return_to_master_c_args);
	
			// Display PHP Credits
		
		// -------------------------------------------------------------
	
	$divider->displaystart($divider_padding_start_args);
	
	$iframe_phpcredits_display_args = array(
		'url'=>'systemstatus.php?action=PHPCredits_IFrameDisplay',
		'class'=>'border-0px width-100percent',
		'autoadjustheight'=>1,
		'scrolling'=>'no',
	);
	
	$iframe->display($iframe_phpcredits_display_args);
	
	$divider->displayend($divider_end_args);
	
?>