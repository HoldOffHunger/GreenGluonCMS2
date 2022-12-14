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
	
	$header_primary_args [
		'title'=>$this->domain_object->primary_domain . ' System Page : Review Accepted Suggestion',
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
	
			// Start Div
		
		// -------------------------------------------------------------

	print('<center>');
	
			// Start Form
		
		// -------------------------------------------------------------

	$start_form_args = [
		'action'=>0,
		'method'=>'post',
		'formclass'=>'margin-0px',
	];
	
	$form->StartForm($start_form_args);
	
			// Display Suggestions
		
		// -------------------------------------------------------------
	
	foreach($this->suggestions as $suggestion)
	{
		print('<div class="border-2px background-color-gray13 margin-5px horizontal-center width-90percent font-family-tahoma">');
		print('<div class="horizontal-left margin-5px">');
		
		print('<p class="margin-5px">Suggestion ' . $suggestion['id'] . ' / User ' . $suggestion['user']['id'] . ' / Entry ' . $suggestion['entry']['id'] . ' (Language: ' . $suggestion['Language'] . ')<br>');
		
		$date_epoch_time = strtotime($suggestion['OriginalCreationDate']);
		$full_date = date("F d, Y; H:i:s", $date_epoch_time);
		
		print('Date : ' . $full_date . '<br>');
		
		print('IP Address : ' . $suggestion['IPAddress'] . '<br>');
		
		print('User : <a href="' . $this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]) . '/users.cgi?action=viewuser&user=' . urlencode($suggestion['user']['Username']) . '">' . $suggestion['user']['Username'] . ' (' . $suggestion['user']['EmailAddress'] . ')</a><br>');
		
		$parent_codes = [];
		$parents = $suggestion['entry']['parents'];
		
		foreach($parents as $parent_key => $parent)
		{
			$parent_codes[] = $parent['Code'];
			
			if($parent['id'] != $suggestion['entry']['id'])
			{
				$last_parent = $parent;
			}
		}
		
		print('Entry : <a href="' . $this->domain_object->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]) . '/' . implode('/', $parent_codes) . '/view.php">' .  $suggestion['entry']['Title']);
		
		if($last_parent && $last_parent['id'])
		{
			print(' (of ' . $last_parent['Title'] . ')');
		}
		
		print('</a>');
		
		print('</p>');
		
		$suggestion_text = str_replace("\n", '<br>', strip_tags($suggestion['Suggestion']));
		print('<blockquote>');
		print($suggestion_text);
		print('</blockquote>');
		
		print('<input type="radio" name="accept_reject_suggestion_' . $suggestion['id'] . '" value="Accept" CHECKED>');
		print('Accept');
		print('<br>');
		print('<input type="radio" name="accept_reject_suggestion_' . $suggestion['id'] . '" value="Reject">');
		print('Reject');
		print('<br>');
		print('<input type="radio" name="accept_reject_suggestion_' . $suggestion['id'] . '" value="Clear">');
		print('Clear (No Decision)');
		
		print('</div>');
		print('</div>');
	}
	
			// Submit Button
		
		// -------------------------------------------------------------
	
	if(count($this->suggestions) < 1)
	{
		print('<br>');
	}
	
	$type_args = [
		'type'=>'submit',
		'name'=>'submit',
		'value'=>'Save Changes to Suggestions',
	];
				
	$form->DisplayFormField($type_args);
	
			// Finish Form
		
		// -------------------------------------------------------------
		
	$end_form_args = [
	];
	$form->EndForm($end_form_args);
	
			// Finish Div
		
		// -------------------------------------------------------------
	
	print('</center>');
	
?>