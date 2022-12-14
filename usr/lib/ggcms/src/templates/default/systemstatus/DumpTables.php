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
	
	$divider_padding_start_args = array(
		'class'=>'margin-5px padding-5px',
	);
	
	$divider_end_args = array(
	);
	
			// Display Header
		
		// -------------------------------------------------------------
	
	$header_primary_args = array(
		'title'=>$this->domain_object->primary_domain . ' System Status : Dump MySQL Tables',
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
	
			// Display PHP Info
		
		// -------------------------------------------------------------
	
	$divider->displaystart($divider_padding_start_args);
	
	$version_list_display_args = array(
		'options'=>array(
			'tableheaders'=>0,
			'tableclass'=>'width-50percent horizontal-center border-2px background-color-gray13',
			'rowclass'=>'border-1px horizontal-left',
			'cellclass'=>array(
				'border-1px vertical-top',
				'border-1px width-100percent vertical-top',
			),
		),
		'list'=>[
			[
				'Instructions:', 'Select the Table(s) to Dump.',
			],
		],
	);
	$generic_list->Display($version_list_display_args);
	
	$divider->displayend($divider_end_args);
		
			// Display Selected Tables
		
		// -------------------------------------------------------------
	
	if($this->StatusDataArray)
	{
		$divider->displaystart($divider_padding_start_args);
		
		$version_list_display_args = array(
			'options'=>array(
				'tableheaders'=>0,
				'tableclass'=>'width-50percent horizontal-center border-2px background-color-gray13',
				'rowclass'=>'border-1px horizontal-left',
				'cellclass'=>array(
					'border-1px vertical-top',
					'border-1px width-100percent vertical-top',
				),
			),
			'list'=>[
				[
					'<nobr>Selected Tables:</nobr>', implode($this->SelectedMySQLTables, ', ') . '.',
				],
			],
		);
		$generic_list->Display($version_list_display_args);
		
		$divider->displayend($divider_end_args);
	}
		
			// Start Form Box
		
		// -------------------------------------------------------------
	
	$divider_padding_start_args = array(
		'class'=>'horizontal-center width-25percent margin-top-5px border-2px',
	);
	
	$divider->displaystart($divider_padding_start_args);
	
			// Start Form
		
		// -------------------------------------------------------------
	
	$start_form_args = array(
		'action'=>0,
		'method'=>'post',
		'files'=>0,
		'formclass'=>'margin-0px',
	);
	
	$form->StartForm($start_form_args);
	
			// Display Form
		
		// -------------------------------------------------------------
	
	$type_args = array(
		'type'=>'select',
		'multiple'=>1,
		'size'=>10,
		'name'=>'MySQLTables[]',
		'options'=>$this->mysql_tables_select,
	);
	
	$form->DisplayFormField($type_args);
		
			// End Form Box
		
		// -------------------------------------------------------------
	
	$divider_end_args = array(
	);
	$divider->displayend($divider_end_args);
		
			// Submit Button
		
		// -------------------------------------------------------------
		
print('<center>');
	
	
	$type_args = array(
		'type'=>'submit',
		'name'=>'Select',
		'value'=>'Select',
		'class'=>'margin-5px',
	);
	
	$form->DisplayFormField($type_args);
	
print('</center>');
	
		
			// End Form
		
		// -------------------------------------------------------------
	
	$end_form_args = array(
	);
	$form->EndForm($end_form_args);
		
			// Display Results
		
		// -------------------------------------------------------------
		
	if($this->StatusDataArray)
	{
	#	print_r($this->StatusDataArray);
		foreach ($this->StatusDataArray as $table_name => $table_records)
		{
					// Start Results Display Box
				
				// -------------------------------------------------------------
			
			$divider_padding_start_args = array(
				'class'=>'horizontal-center width-70percent margin-top-5px margin-bottom-5px border-2px',
			);
			
			$divider->displaystart($divider_padding_start_args);
			
					// Display Table Title
				
				// -------------------------------------------------------------
				
print('<h3>' . $table_name . '</h3>');
			
			
					// Start Results Display Box
				
				// -------------------------------------------------------------
			
			$divider_padding_start_args = array(
				'class'=>'margin-5px horizontal-left border-1px',
			);
			
			$divider->displaystart($divider_padding_start_args);
			
					// Table Start for Records List
				
				// -------------------------------------------------------------
			
			$table_start_args = array(
				'tableclass'=>'width-100percent',
				'tableborder'=>'2',
				'cellwidth'=>'25%',
			);
			
			$table->DisplayComponent_StartTable($table_start_args);
			
					// Display Record Field Names
				
				// -------------------------------------------------------------
			
			if($table_records[0])
			{
			
				$first_table_record = $table_records[0];
				
				$display_start = 0;
				
				foreach ($first_table_record as $key => $value)
				{
					reset($first_table_record);
					if($key === key($first_table_record))
					{
					}
					else
					{
						$separate_cells_and_rows_args = array(
							'cellwidth'=>'25%',
						);
						$table->DisplayComponent_SeparateCells($separate_cells_and_rows_args);
					}
					
					print('<b>' . $key . '</b>');
				}
					
				$separate_cells_and_rows_args = array(
					'cellwidth'=>'25%',
				);
				$table->DisplayComponent_SeparateCellsAndRows($separate_cells_and_rows_args);
				
			#	print("<PRE>");
			#	print_r($table_records);
			#	print("</PRE>");
			}
			
					// Display Records
				
				// -------------------------------------------------------------
			
			foreach ($table_records as $table_record)
			{
			#	print("BT: $table_record");
				foreach ($table_record as $record_field => $record_value)
				{
					reset($table_record);
					if($record_field === key($table_record))
					{
					}
					else
					{
						$separate_cells_and_rows_args = array(
							'cellwidth'=>'25%',
						);
						$table->DisplayComponent_SeparateCells($separate_cells_and_rows_args);
					}
					
					print($record_value);
				}
					
				$separate_cells_and_rows_args = array(
					'cellwidth'=>'25%',
				);
				$table->DisplayComponent_SeparateCellsAndRows($separate_cells_and_rows_args);
			}
			
					// Table End for Records List
				
				// -------------------------------------------------------------
			
			$table_end_args = array(
			);
			$table->DisplayComponent_EndTable($table_end_args);
			
					// End Results Display Box
				
				// -------------------------------------------------------------
			
			$divider->displayend($divider_end_args);
			
					// End Results Display Box
				
				// -------------------------------------------------------------
			
			$divider->displayend($divider_end_args);
		}
	}
	
?>