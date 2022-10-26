<?php

	class ORM {
		
			// Construction
			// -------------------------------------------------
		
		public function __construct($args) {
			$this->handler = $args['handler'];
			
			return $this;
		}
		
		public function GetAllEntryRecordTypes() {
			$entry_record_types = [
				'Entry'=>'entry',
				'EntryTranslation'=>'entrytranslation',
				'Description'=>'description',
				'Quote'=>'quote',
				'TextBody'=>'textbody',
				'Image'=>'image',
				'ImageTranslation'=>'imagetranslation',
				'Tag'=>'tag',
				'Link'=>'link',
				'EventDate'=>'eventdate',
				'AvailabilityDateRange'=>'availabilitydaterange',
				'Assignment'=>'assignment',
				'Association'=>'association',
				'EntryPermission'=>'entrypermission',
				'Definition'=>'definition',
			];
			
			return $entry_record_types;
		}
		
		public function GetStandardEntryRecordTypes() {
			$entry_record_types = [
				'EntryTranslation'=>'entrytranslation',
				'Description'=>'description',
				'Quote'=>'quote',
				'TextBody'=>'textbody',
				'Image'=>'image',
				'ImageTranslation'=>'imagetranslation',
				'Tag'=>'tag',
				'Link'=>'link',
				'EventDate'=>'eventdate',
				'Association'=>'association',
				'AvailabilityDateRange'=>'availabilitydaterange',
				'Definition'=>'definition',
			];
			
			return $entry_record_types;
		}
		
		public function GetChildRecordTypes() {
			return [
				'EntryTranslation',
				'Description',
				'Quote',
				'TextBody',
				'Image',
				'ImageTranslation',
				'Tag',
				'Link',
				'EventDate',
				'AvailabilityDateRange',
				'Association',
				'Definition',
				'EntryPermission',
			];
		}
		
		public function GetRecordTypeByField($args) {
			$field = $args['field'];
			
			switch($field) {
				case 'EntryTranslation':
				case 'Description':
				case 'Quote':
				case 'Image':
				case 'ImageTranslation':
				case 'Link':
				case 'TextBody':
				case 'Tag':
				case 'EventDate':
				case 'Association':
				case 'AvailabilityStart':
				case 'AvailabilityEnd':
				case 'Definition':
					$recordtype = $field;
					break;
			}
			
			return $recordtype;
		}
		
		public function SearchForEntries($args) {
			$fieldname = $args['fieldname'];
			
			if(
				($fieldname == 'id') ||
				($fieldname == 'Title') ||
				($fieldname == 'Subtitle') ||
				($fieldname == 'Code')
			) {
				return $this->SearchForEntries_ByRecordField($args);
			} else if($fieldname == 'Level') {
				return $this->SearchForEntries_ByRecordLevel($args);
			}
			
			return $this->SearchForEntries_ByChildField($args);
		}
		
		public function SearchForEntries_ByRecordLevel($args) {
			$fieldname = $args['fieldname'];
			$fieldvalue = $args['fieldvalue'];
			
			$level = min($fieldvalue, 10);
			
			$limit = $level;
			
			$selects = [
				'Entry' . $limit . '.id AS Entry' . $limit . '_id, ' .
				'Entry' . $limit . '.Title AS Entry' . $limit . '_Title, ' .
				'Entry' . $limit . '.Subtitle AS Entry' . $limit . '_Subtitle, ' .
				'Entry' . $limit . '.Code AS Entry' . $limit . '_Code '
			];
			
			$joins = [];
			
			$limit--;
			
			while($limit >= 1) {
				$previous_limit = $limit + 1;
				$join = 
					'JOIN Assignment AS Assignment' . $limit . ' ' .
					'ON Assignment' . $limit . '.Parentid = Entry' . $previous_limit . '.id ' .
					'JOIN Entry AS Entry' . $limit . ' ON Entry' . $limit . '.id = Assignment' . $limit . '.Childid ';
					
					if(!$args['includeunpublished']) {
						$join .= 'AND Entry' . $limit . '.Publish = 1 ';
					}
				$joins[] = $join;
				
				$selects[] =
					'Entry' . $limit . '.id AS Entry' . $limit . '_id, ' .
					'Entry' . $limit . '.Title AS Entry' . $limit . '_Title, ' .
					'Entry' . $limit . '.Subtitle AS Entry' . $limit . '_Subtitle, ' .
					'Entry' . $limit . '.Code AS Entry' . $limit . '_Code '
				;
				
				$limit--;
			}
			
			$sql = 'SELECT ' . implode(',', $selects) . ' ';
			
			$sql .= 'FROM Assignment AS Assignment' . $level . ' ';
			$sql .= 'JOIN Entry AS Entry' . $level . ' ON Entry' . $level . '.id = Assignment' . $level . '.Parentid and Assignment' . $level . '.Childid = 0 ';
			
			if(!$args['includeunpublished']) {
				$sql .= 'AND Entry' . $level . '.Publish = 1 ';
			}
			
			$sql .= implode('', $joins);
		
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>'',
				'recordvalues'=>[],
			];
			
			$results = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			$entries = [];
			
			foreach($results as $result) {
				$entry = [
					'id'=>$result['Entry1_id'],
					'Title'=>$result['Entry1_Title'],
					'Code'=>$result['Entry1_Code'],
				];
				
				$entry['parents'] = [];
				
				$limit = $level - 1;
				
				while($limit >= 1) {
					$entry['parents'][] = [
						'id'=>$result['Entry' . $limit . '_id'],
						'Title'=>$result['Entry' . $limit . '_Title'],
						'Code'=>$result['Entry' . $limit . '_Code'],
					];
					$limit--;
				}
				
				$entries[] = $entry;
			}
			
			return $entries;
		}
		
		public function SearchForEntries_ByChildField($args) {
			$child_records = $this->SearchForEntries_ByChildField_ChildRecords($args);
			
			$args['childrecords'] = $child_records;
			
			$entry_records = $this->SearchForEntries_ByChildField_EntryRecords($args);
			
			$args['entryrecords'] = $entry_records;
			
			$entry_records = $this->SearchForEntries_ByChildField_SortRecords($args);
			
			$args['entryrecords'] = $entry_records;
			
			$entry_records = $this->SearchForEntries_ByChildField_ParentRecords($args);
			
			return $entry_records;
		}
		
		public function SearchForEntries_ByChildField_ParentRecords($args) {
			$entry_records = $args['entryrecords'];
			
			$entry_records_with_parents = [];
			
			foreach($entry_records as $entry_record) {
				$get_entry_parents_args = [
					'entry'=>$entry_record,
				];
				$parents_results = $this->GetEntryParents($get_entry_parents_args);
				$entry_record['parents'] = $parents_results['parents'];
				$entry_record['assignment'] = $parents_results['assignment'];
				
				$entry_records_with_parents[] = $entry_record;
			}
			
			return $entry_records_with_parents;
		}
		
		public function SearchForEntries_ByChildField_SortRecords($args) {
			$child_records = $args['childrecords'];
			$entry_records = $args['entryrecords'];
			$field_name = $args['fieldname'];
			
			$record_type_args = [
				'field'=>$field_name,
			];
			
			$record_type = $this->GetRecordTypeByField($record_type_args);
			$record_type_key = strtolower($record_type);
			
			$new_entries = $entry_records;
			
			foreach ($entry_records as $entry) {
				foreach ($child_records as $child_record) {
					if($child_record['Entryid'] == $entry['id']) {
						$entry[$record_type_key] = [];
						$entry[$record_type_key][] = $child_record;
						
						$new_entries[] = $entry;
					}
				}
			}
			
			return $entry_records;
		}
		
		public function SearchForEntries_ByChildField_EntryRecords($args) {
			$child_records = $args['childrecords'];
			
			$entry_ids = [];
			
			foreach ($child_records as $child_record) {
				$entry_ids[] = $child_record['Entryid'];
			}
			
			$entry_ids = array_unique($entry_ids);
			
			$question_marks = array_fill(0, count($entry_ids), '?');
			$question_marks_imploded = implode(', ', $question_marks);
			$sql = 'SELECT * FROM Entry WHERE id IN(' . $question_marks_imploded . ')';
			
			$sql_bind_string = str_repeat('i', count($entry_ids));
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$entry_ids,
			];
			
			$record_results = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $record_results;
		}
		
		public function SearchForEntries_ByChildField_ChildRecords($args) {
			$fieldname = $args['fieldname'];
			$fieldvalue = $args['fieldvalue'];
			$matchlike = $args['matchlike'];
			
			$record_type_args = [
				'field'=>$fieldname,
			];
			
			$recordtype = $this->GetRecordTypeByField($record_type_args);
			
			$sql = 'SELECT * FROM ' . $recordtype . ' WHERE ' . $fieldname . ' ';
			
			if($matchlike) {
				$sql .= 'RLIKE';
			} else {
				$sql .= '=';
			}
			
			$sql .= ' ?';
			
			$bind_string = 's';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$bind_string,
				'recordvalues'=>[$fieldvalue],
			];
			
			$record_results = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $record_results;
		}
		
		public function SearchForEntries_ByRecordField($args) {
			$fieldname = $args['fieldname'];
			$fieldvalue = $args['fieldvalue'];
			$matchlike = $args['matchlike'];
			
			$sql = 'SELECT * FROM Entry WHERE ' . $fieldname . ' ';
			
			if($matchlike) {
				$sql .= 'RLIKE ';
			} else {
				$sql .= '= ';
			}
			
			$sql .= '? ';
			
			if(!$args['includeunpublished']) {
				$sql .= ' AND Entry.Publish = 1 ';
			}
			
			$get_description_args = [
				'type'=>'Entry',
			];
			$record_description = $this->handler->db_access->GetRecordDescription($get_description_args);
			
			$record_results = [];
			
			if($record_description && $record_description[$fieldname]) {
				if($record_description[$fieldname]['TypeBase'] === 'int') {
					$bind_string = 'i';
				} else {
					$bind_string = 's';
				}
				
				$record_values = [$fieldvalue];
				
				$fill_arrays_from_db_args = [
					'query'=>$sql,
					'sqlbindstring'=>$bind_string,
					'recordvalues'=>$record_values,
				];
				
				$record_results = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			}
			
			if($record_results && count($record_results)) {
				$new_record_results = [];
				
				foreach ($record_results as $record_result) {
					$get_parents_args = [
						'entry'=>$record_result,
						'assignmentid'=>$args['assignmentid'],
					];
					$parents_results = $this->GetEntryParents($get_parents_args);
					$record_result['assignment'] = $parents_results['assignment'];
					$record_result['parents'] = $parents_results['parents'];
					
					$new_record_results[] = $record_result;
				}
				
				$record_results = $new_record_results;
			}
			
			return $record_results;
		}
		
		public function GetEntryParents($args) {
			$parents = $this->GetEntryParents_GetRecords($args);
			$args['parents'] = $parents;
			
			$child_assignment = [
				'id'=>$parents['Assignment_1_id'],
				'Parentid'=>$parents['Assignment_1_Parentid'],
				'Childid'=>$parents['Assignment_1_Childid'],
			];
			
			$args['entry']['assignment'] = $child_assignment;
			
			$parents = $this->GetEntryParents_SortRecords($args);
			
			return [
				'parents'=>$parents,
				'assignment'=>$child_assignment,
			];
		}
		
		public function GetEntriesParents($args) {
			$entries = $args['entries'];
			$entries_count = count($entries);
			$entry_ids = [];
			for($i = 0; $i < $entries_count; $i++) {
				$entry = $entries[$i];
				$entry_ids[] = $entry['id'];
			}
			
			$parents = $this->GetEntriesParents_GetRecords(['entryids'=>$entry_ids]);
			$entries_with_parents = $this->GetEntriesParents_SortRecords(['entries'=>$entries, 'parents'=>$parents]);
		//	print_r($entries_with_parents);
			return $entries_with_parents;
		}
		
		public function GetEntriesParents_SortRecords($args) {
			$entries = $args['entries'];
			$entries_count = count($entries);
			
			$entries_hash = [];
			
			for($i = 0; $i < $entries_count; $i++) {
				$entry = $entries[$i];
				$entries_hash[$entry['id']] = $entry;
			//	print_r($entry);
			//	print("<BR><BR>");
			}
			
			$parents = $args['parents'];
			$parents_count = count($parents);
			
			$parents_hash = [];
			for($i = 0; $i < $parents_count; $i++) {
				$parent = $parents[$i];
				
				$entry = $entries_hash[$parent['Assignment_1_Childid']];
				$entries_hash[$parent['Assignment_1_Childid']]['parents'] = $this->GetEntryParents_SortRecords(['parents'=>$parent, 'entry'=>$entry]);
			}
			
			return array_values($entries_hash);
			
	//		print_r($parents_hash);
			/*
			for($i = 0; $i < $entries_count; $i++) {
				$entry = $entries[$i];
				
				$parents = [];
				
				$current_entry = $entry;
				
				while($parents_hash[$current_entry['id']]) {
					$new_parent = $parents_hash[$current_entry['id']];
					$parents[] = $new_parent;
					$current_entry = $new_parent;
				}
				
				$entry['parents'] = $parents;
				
				$entries[$i] = $entry;
			}*/
			
			return $entries;
		}
		
		public function EntrySelects() {
			$selects = [
				'Entry1.id as Entry_1_id',
				'Entry1.Title as Entry_1_Title',
				'Entry1.Subtitle as Entry_1_Subtitle',
				'Entry1.ListTitle as Entry_1_ListTitle',
				'Entry1.ListTitle as Entry_1_ListTitleSortKey',
				'Entry1.Code as Entry_1_Code',
				'Entry1.ChildAdjective as Entry_1_ChildAdjective',
				'Entry1.ChildNoun as Entry_1_ChildNoun',
				'Entry1.ChildNounPlural as Entry_1_ChildNounPlural',
				'Entry1.GrandChildAdjective as Entry_1_GrandChildAdjective',
				'Entry1.GrandChildNoun as Entry_1_GrandChildNoun',
				'Entry1.GrandChildNounPlural as Entry_1_GrandChildNounPlural',
				'Entry1.Publish as Entry_1_Publish',
				'Entry1.OriginalCreationDate as Entry_1_OriginalCreationDate',
				'Entry1.LastModificationDate as Entry_1_LastModificationDate',
			];
			
			return $selects;
		}
		
		public function GetEntriesParents_GetRecords($args) {
			$entry_ids = $args['entryids'];
			
			if(!$entry_ids || count($entry_ids) === 0) {
				return [];
			}
			
			$sql = 'SELECT ';
			
			$sql .= 'Assignment1.id as Assignment_1_id, Assignment1.Parentid as Assignment_1_Parentid, Assignment1.Childid as Assignment_1_Childid ';
			
			$joins = [];
			$selects = $this->EntrySelects();
			
			$max_depth = 10;
			
			for($i = 2; $i < $max_depth; $i++) {
				$previous_index = $i - 1;
				
				$selects[] = 'Assignment' . $i . '.id as Assignment_' . $i . '_id';
				$selects[] = 'Assignment' . $i . '.Parentid as Assignment_' . $i . '_Parentid';
				$selects[] = 'Assignment' . $i . '.Childid as Assignment_' . $i . '_Childid';
				
				$selects[] = 'Entry' . $i . '.id as Entry_' . $i . '_id';
				$selects[] = 'Entry' . $i . '.Title as Entry_' . $i . '_Title';
				$selects[] = 'Entry' . $i . '.Subtitle as Entry_' . $i . '_Subtitle';
				$selects[] = 'Entry' . $i . '.ListTitle as Entry_' . $i . '_ListTitle';
				$selects[] = 'Entry' . $i . '.ListTitleSortKey as Entry_' . $i . '_ListTitleSortKey';
				$selects[] = 'Entry' . $i . '.Code as Entry_' . $i . '_Code';
				$selects[] = 'Entry' . $i . '.ChildAdjective as Entry_' . $i . '_ChildAdjective';
				$selects[] = 'Entry' . $i . '.ChildNoun as Entry_' . $i . '_ChildNoun';
				$selects[] = 'Entry' . $i . '.ChildNounPlural as Entry_' . $i . '_ChildNounPlural';
				$selects[] = 'Entry' . $i . '.GrandChildAdjective as Entry_' . $i . '_GrandChildAdjective';
				$selects[] = 'Entry' . $i . '.GrandChildNoun as Entry_' . $i . '_GrandChildNoun';
				$selects[] = 'Entry' . $i . '.GrandChildNounPlural as Entry_' . $i . '_GrandChildNounPlural';
				$selects[] = 'Entry' . $i . '.Publish as Entry_' . $i . '_Publish';
				$selects[] = 'Entry' . $i . '.OriginalCreationDate as Entry_' . $i . '_OriginalCreationDate';
				$selects[] = 'Entry' . $i . '.LastModificationDate as Entry_' . $i . '_LastModificationDate';
				
				$joins[] = 'LEFT JOIN Assignment AS Assignment' . $i . ' ON Assignment' . $i . '.Childid = Entry' . $previous_index . '.id';
				$joins[] = 'LEFT JOIN Entry AS Entry' . $i . ' ON Assignment' . $i . '.Parentid = Entry' . $i . '.id';
			}
			
			if(count($selects)) {
				$sql .= ', ' . implode(', ', $selects) . ' ';
			}
			$sql .= 'FROM Assignment Assignment1 ';
			$sql .= 'LEFT JOIN Entry AS Entry1 ON Entry1.id = Assignment1.Parentid ';
			
			$sql .= implode(' ', $joins);
			$sql .= ' WHERE Assignment1.Childid IN (';
			$sql .= implode(', ', array_fill(0, count($entry_ids), '?'));
			$sql .= ')';
			
			$bind_string = str_repeat('i', count($entry_ids));
			
			if($args['assignmentid']) {
				$sql .= ' AND Assignment1.id = ?';
				$bind_string .= 'i';
				$ids[] = $args['assignmentid'];
			}
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$bind_string,
				'recordvalues'=>$entry_ids,
			];
			
			$parents = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $parents;
		}
		
		public function GetEntryParents_GetRecords($args) {
			$entry = $args['entry'];
			
			$ids = [$entry['id']];
			
			$sql = 'SELECT ';
			
			$sql .= 'Assignment1.id as Assignment_1_id, Assignment1.Parentid as Assignment_1_Parentid, Assignment1.Childid as Assignment_1_Childid ';
			
			$joins = [];
			$selects = [
				'Entry1.id as Entry_1_id',
				'Entry1.Title as Entry_1_Title',
				'Entry1.Subtitle as Entry_1_Subtitle',
				'Entry1.ListTitle as Entry_1_ListTitle',
				'Entry1.ListTitleSortKey as Entry_1_ListTitleSortKey',
				'Entry1.Code as Entry_1_Code',
				'Entry1.ChildAdjective as Entry_1_ChildAdjective',
				'Entry1.ChildNoun as Entry_1_ChildNoun',
				'Entry1.ChildNounPlural as Entry_1_ChildNounPlural',
				'Entry1.GrandChildAdjective as Entry_1_GrandChildAdjective',
				'Entry1.GrandChildNoun as Entry_1_GrandChildNoun',
				'Entry1.GrandChildNounPlural as Entry_1_GrandChildNounPlural',
				'Entry1.Publish as Entry_1_Publish',
				'Entry1.OriginalCreationDate as Entry_1_OriginalCreationDate',
				'Entry1.LastModificationDate as Entry_1_LastModificationDate',
			];
			
			$max_depth = 10;
			
			for($i = 2; $i < $max_depth; $i++) {
				$previous_index = $i - 1;
				
				$selects[] = 'Assignment' . $i . '.id as Assignment_' . $i . '_id';
				$selects[] = 'Assignment' . $i . '.Parentid as Assignment_' . $i . '_Parentid';
				$selects[] = 'Assignment' . $i . '.Childid as Assignment_' . $i . '_Childid';
				
				$selects[] = 'Entry' . $i . '.id as Entry_' . $i . '_id';
				$selects[] = 'Entry' . $i . '.Title as Entry_' . $i . '_Title';
				$selects[] = 'Entry' . $i . '.Subtitle as Entry_' . $i . '_Subtitle';
				$selects[] = 'Entry' . $i . '.ListTitle as Entry_' . $i . '_ListTitle';
				$selects[] = 'Entry' . $i . '.ListTitleSortKey as Entry_' . $i . '_ListTitleSortKey';
				$selects[] = 'Entry' . $i . '.Code as Entry_' . $i . '_Code';
				$selects[] = 'Entry' . $i . '.ChildAdjective as Entry_' . $i . '_ChildAdjective';
				$selects[] = 'Entry' . $i . '.ChildNoun as Entry_' . $i . '_ChildNoun';
				$selects[] = 'Entry' . $i . '.ChildNounPlural as Entry_' . $i . '_ChildNounPlural';
				$selects[] = 'Entry' . $i . '.GrandChildAdjective as Entry_' . $i . '_GrandChildAdjective';
				$selects[] = 'Entry' . $i . '.GrandChildNoun as Entry_' . $i . '_GrandChildNoun';
				$selects[] = 'Entry' . $i . '.GrandChildNounPlural as Entry_' . $i . '_GrandChildNounPlural';
				$selects[] = 'Entry' . $i . '.Publish as Entry_' . $i . '_Publish';
				$selects[] = 'Entry' . $i . '.OriginalCreationDate as Entry_' . $i . '_OriginalCreationDate';
				$selects[] = 'Entry' . $i . '.LastModificationDate as Entry_' . $i . '_LastModificationDate';
				
				$joins[] = 'LEFT JOIN Assignment AS Assignment' . $i . ' ON Assignment' . $i . '.Childid = Entry' . $previous_index . '.id';
				$joins[] = 'LEFT JOIN Entry AS Entry' . $i . ' ON Assignment' . $i . '.Parentid = Entry' . $i . '.id';
			}
			
			if(count($selects)) {
				$sql .= ', ' . implode(', ', $selects) . ' ';
			}
			$sql .= 'FROM Assignment Assignment1 ';
			$sql .= 'LEFT JOIN Entry AS Entry1 ON Entry1.id = Assignment1.Parentid ';
			
			$sql .= implode(' ', $joins);
			$sql .= ' WHERE Assignment1.Childid = ?';
			
			$bind_string = 'i';
			
			if($args['assignmentid']) {
				$sql .= ' AND Assignment1.id = ?';
				$bind_string .= 'i';
				$ids[] = $args['assignmentid'];
			}
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$bind_string,
				'recordvalues'=>$ids,
			];
			
			$parents = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $parents[0];
		}
		
		public function GetEntryParents_SortRecords($args) {
			$entry = $args['entry'];
			$parents = $args['parents'];
			$parents_count = count($parents);
			
			$parent_objects = [];
			
			for($i = 1; $i <= $parents_count; $i++) {
				$next_increment = $i + 1;
				$assignment_id = $parents['Assignment_' . $next_increment . '_id'];
				
				if($assignment_id) {
					$assignment_parentid = $parents['Assignment_' . $next_increment . '_Parentid'];
					$assignment_childid = $parents['Assignment_' . $next_increment . '_Childid'];
					
					$entry_id = $parents['Entry_' . $i . '_id'];
					$entry_title = $parents['Entry_' . $i . '_Title'];
					$entry_subtitle = $parents['Entry_' . $i . '_Subtitle'];
					$entry_listtitle = $parents['Entry_' . $i . '_ListTitle'];
					$entry_listtitlesortkey = $parents['Entry_' . $i . '_ListTitleSortKey'];
					$entry_code = $parents['Entry_' . $i . '_Code'];
					$entry_childadjective = $parents['Entry_' . $i . '_ChildAdjective'];
					$entry_childnoun = $parents['Entry_' . $i . '_ChildNoun'];
					$entry_childnounplural = $parents['Entry_' . $i . '_ChildNounPlural'];
					$entry_grandchildadjective = $parents['Entry_' . $i . '_GrandChildAdjective'];
					$entry_grandchildnoun = $parents['Entry_' . $i . '_GrandChildNoun'];
					$entry_grandchildnounplural = $parents['Entry_' . $i . '_GrandChildNounPlural'];
					$entry_publish = $parents['Entry_' . $i . '_Publish'];
					$entry_originalcreationdate = $parents['Entry_' . $i . '_OriginalCreationDate'];
					$entry_latsmodificationdate = $parents['Entry_' . $i . '_LastModificationDate'];
					
					$parent = [
						'id'=>$entry_id,
						'Title'=>$entry_title,
						'Subtitle'=>$entry_subtitle,
						'ListTitle'=>$entry_listtitle,
						'ListTitleSortKey'=>$entry_listtitlesortkey,
						'Code'=>$entry_code,
						'ChildAdjective'=>$entry_childadjective,
						'ChildNoun'=>$entry_childnoun,
						'ChildNounPlural'=>$entry_childnounplural,
						'GrandChildAdjective'=>$entry_grandchildadjective,
						'GrandChildNoun'=>$entry_grandchildnoun,
						'GrandChildNounPlural'=>$entry_grandchildnounplural,
						'Publish'=>$entry_publish,
						'OriginalCreationDate'=>$entry_originalcreationdate,
						'LastModificationDate'=>$entry_latsmodificationdate,
						'assignment'=>[
							'id'=>$assignment_id,
							'Parentid'=>$assignment_parentid,
							'Childid'=>$assignment_childid,
						],
					];
					
					$parent_objects[] = $parent;
				} else {
					$i = $parents_count + 1;
				}
			}
			
			$parent_objects = array_reverse($parent_objects);
			
			$parent_objects[] = $entry;
			
			return $parent_objects;
		}
		
		public function GetMasterRecord() {
			$sql = 'SELECT ';
			
			$sql .= 
				'Entry.id as Entry_id, ' .
				'Entry.Title as Entry_Title, ' .
				'Entry.Subtitle as Entry_Subtitle, ' .
				'Entry.ListTitle as Entry_ListTitle, ' .
				'Entry.ListTitleSortKey as Entry_ListTitleSortKey, ' .
				'Entry.Code as Entry_Code, ' .
				'Entry.ChildAdjective as Entry_ChildAdjective, ' .
				'Entry.ChildNoun as Entry_ChildNoun, ' .
				'Entry.ChildNounPlural as Entry_ChildNounPlural, ' .
				'Entry.GrandChildAdjective as Entry_GrandChildAdjective, ' .
				'Entry.GrandChildNoun as Entry_GrandChildNoun, ' .
				'Entry.GrandChildNounPlural as Entry_GrandChildNounPlural, ' .
				'Entry.Publish as Entry_Publish, ' .
				'Entry.OriginalCreationDate as Entry_OriginalCreationDate, ' .
				'Entry.LastModificationDate as Entry_LastModificationDate, ' .
				'Assignment.id as Assignment_id, ' .
				'Assignment.Parentid as Assignment_Parentid, ' .
				'Assignment.Childid as Assignment_Childid ' ;
			
			$sql .= 'FROM Entry JOIN Assignment ON Assignment.Parentid = Entry.id AND Assignment.Childid = 0;';
			
			$bind_string = '';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$bind_string,
				'recordvalues'=>[],
			];
			
			$master_records = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			$new_master_records = [];
			
			foreach($master_records as $master_record) {
				$new_master_record = [
					'id'=>$master_record['Entry_id'],
					'Title'=>$master_record['Entry_Title'],
					'Subtitle'=>$master_record['Entry_Subtitle'],
					'ListTitle'=>$master_record['Entry_ListTitle'],
					'ListTitleSortKey'=>$master_record['Entry_ListTitleSortKey'],
					'Code'=>$master_record['Entry_Code'],
					'ChildAdjective'=>$master_record['Entry_ChildAdjective'],
					'ChildNoun'=>$master_record['Entry_ChildNoun'],
					'ChildNounPlural'=>$master_record['Entry_ChildNounPlural'],
					'GrandChildAdjective'=>$master_record['Entry_GrandChildAdjective'],
					'GrandChildNoun'=>$master_record['Entry_GrandChildNoun'],
					'GrandChildNounPlural'=>$master_record['Entry_GrandChildNounPlural'],
					'Publish'=>$master_record['Entry_Publish'],
					'OriginalCreationDate'=>$master_record['Entry_OriginalCreationDate'],
					'LastModificationDate'=>$master_record['Entry_LastModificationDate'],
					'assignment'=>[
						'id'=>$master_record['Assignment_id'],
						'Parentid'=>$master_record['Assignment_Parentid'],
						'Childid'=>$master_record['Assignment_Childid'],
					],
				];
				
				$get_child_record_args = [
					'entrieslist'=>[
						$new_master_record,
					],
				];
				
				$new_master_record = $this->GetRecordTree_GetEntryChildRecords($get_child_record_args);
				$new_master_records[] = $new_master_record[0];
			}
			
			return $new_master_records;
		}
		
		public function GetRecordTree($args) {
			$entries_list = $this->GetRecordTree_GetEntries($args);
			
			$entries_list_args = $args;
			$entries_list_args['entrieslist'] = $entries_list;
			$entries_list = $this->GetRecordTree_StructureRecords($entries_list_args);
			
			$entries_list_args['entrieslist'] = $entries_list;
			
			$entries_list = $this->GetRecordTree_GetEntryChildRecords($entries_list_args);
			
			return $entries_list;
		}
		
		public function GetRecordTree_GetEntries($args) {
			$code_list = $args['codelist'];
			$availability_limit = $args['availabilitylimit'];
			
			$code_list_count = count($code_list);
			
			$code_index = 1;
			$joins = [];
			$wheres = [];
			
			$get_base_select_args = [
				'index'=>$code_index,
			];
			
			$selects = $this->GetRecordTree_GetEntries_GetBaseSelect($get_base_select_args);
			
			if($code_list_count) {
				$joins[] = 'JOIN Assignment AS Assignment' . $code_index . ' ON Assignment1.Childid = Entry' . $code_index . '.id';
			} else {
				$joins[] = 'JOIN Assignment AS Assignment' . $code_index . ' ON Assignment1.Parentid = Entry' . $code_index . '.id AND Assignment' . $code_index . '.Childid = 0';
			}
			
			if($code_list_count) {
				$wheres[] = 'Entry' . $code_index . '.Code = ?'; #. $code_list[0];
			}
			
			$code_index++;
			
			$parseable_code_list = $code_list;
			array_shift($parseable_code_list);
			
			foreach ($parseable_code_list as $code) {
				$previous_code_index = $code_index - 1;
				
				$get_base_select_args = [
					'index'=>$code_index,
					'list'=>$selects,
				];
				
				$selects = $this->GetRecordTree_GetEntries_GetBaseSelect($get_base_select_args);
				
				$joins[] = 'JOIN Assignment AS Assignment' . $code_index . ' ON Assignment' . $code_index . '.Parentid = Entry' . $previous_code_index . '.id';
				$joins[] = 'JOIN Entry AS Entry' . $code_index . ' ON Assignment' . $code_index . '.Childid = Entry' . $code_index . '.id';
				
				$wheres[] = 'Entry' . $code_index . '.Code = ?'; # . $code_list[$previous_code_index];
				
				$code_index++;
			}
			
			$joins[] = 'JOIN Assignment AS Assignmentx ON Assignmentx.Childid = 0 ';
			$joins[] = 'JOIN Assignment AS Assignmenty ON Assignmenty.Parentid = Assignmentx.Parentid AND Assignmenty.Childid = Entry1.id ';
			
			$sql = 'SELECT ';
			$sql .= implode(', ', $selects) . ' ';
			$sql .= 'FROM ';
			$sql .= 'Entry as Entry1 ';
			$sql .= implode(' ', $joins) . ' ';
			
			if($code_list_count) {
				$sql .= 'WHERE ';
				$sql .= implode(' AND ', $wheres);
			}
			
			$sql_bind_string = str_repeat('s', $code_list_count);
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$code_list,
			];
			
#			print($sql);
#			print("<BR><BR>");
#			print_r($code_list);
			
			return $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
		}
		
		public function GetRecordTree_StructureChildRecords($args) {
			$entries_list = $args['entrieslist'];
			$entries_list_count = count($entries_list);
			
			if($args['extraselect']) {
				$new_select = explode(',',$args['extraselect']);
			}
			
			$new_entries_list = [];
			
			for($i = 0; $i < $entries_list_count; $i++) {
				$entry = $entries_list[$i];
				
				$new_entry = [
					'id'=>$entry['Entry_1_id'],
					'Title'=>$entry['Entry_1_Title'],
					'Subtitle'=>$entry['Entry_1_Subtitle'],
					'ListTitle'=>$entry['Entry_1_ListTitle'],
					'ListTitleSortKey'=>$entry['Entry_1_ListTitleSortKey'],
					'Code'=>$entry['Entry_1_Code'],
					'ChildAdjective'=>$entry['Entry_1_ChildAdjective'],
					'ChildNoun'=>$entry['Entry_1_ChildNoun'],
					'ChildNounPlural'=>$entry['Entry_1_ChildNounPlural'],
					'GrandChildAdjective'=>$entry['Entry_1_GrandChildAdjective'],
					'GrandChildNoun'=>$entry['Entry_1_GrandChildNoun'],
					'GrandChildNounPlural'=>$entry['Entry_1_GrandChildNounPlural'],
					'Publish'=>$entry['Entry_1_Publish'],
					'OriginalCreationDate'=>$entry['Entry_1_OriginalCreationDate'],
					'LastModificationDate'=>$entry['Entry_1_LastModificationDate'],
					'assignment'=>[
						[
							'id'=>$entry['Assignment_1_id'],
							'Parentid'=>$entry['Assignment_1_Parentid'],
							'Childid'=>$entry['Assignment_1_Childid'],
						],
					],
				];
				
				if($new_select) {
					foreach($new_select as $select_field) {
						$select_pieces = explode(' AS ', $select_field);
						$new_entry[$select_pieces[1]] = $entry[$select_pieces[1]];
					}
				}
				
				$new_entries_list[] = $new_entry;
			}
			
			return $new_entries_list;
		}
		
		public function GetRecordTree_StructureRecords($args) {
			$entries_list = $args['entrieslist'];
			$entry_data = $entries_list[0];
			$code_list_count = $args['codelistcount'];
			
			if(!$code_list_count) {
				$code_list = $args['codelist'];
				$code_list_count = count($code_list);
			}
			
			$new_entries_list = [];
			
			for($i = 1; $i <= $code_list_count; $i++) {
				if($entry_data['Entry_' . $i . '_id']) {
					$new_entries_list[] = [
						'id'=>$entry_data['Entry_' . $i . '_id'],
						'Title'=>$entry_data['Entry_' . $i . '_Title'],
						'Subtitle'=>$entry_data['Entry_' . $i . '_Subtitle'],
						'ListTitle'=>$entry_data['Entry_' . $i . '_ListTitle'],
						'ListTitleSortKey'=>$entry_data['Entry_' . $i . '_ListTitleSortKey'],
						'Code'=>$entry_data['Entry_' . $i . '_Code'],
						'ChildAdjective'=>$entry_data['Entry_' . $i . '_ChildAdjective'],
						'ChildNoun'=>$entry_data['Entry_' . $i . '_ChildNoun'],
						'ChildNounPlural'=>$entry_data['Entry_' . $i . '_ChildNounPlural'],
						'GrandChildAdjective'=>$entry_data['Entry_' . $i . '_GrandChildAdjective'],
						'GrandChildNoun'=>$entry_data['Entry_' . $i . '_GrandChildNoun'],
						'GrandChildNounPlural'=>$entry_data['Entry_' . $i . '_GrandChildNounPlural'],
						'Publish'=>$entry_data['Entry_' . $i . '_Publish'],
						'OriginalCreationDate'=>$entry_data['Entry_' . $i . '_OriginalCreationDate'],
						'LastModificationDate'=>$entry_data['Entry_' . $i . '_LastModificationDate'],
						'assignment'=>[
							[
								'id'=>$entry_data['Assignment_' . $i . '_id'],
								'Parentid'=>$entry_data['Assignment_' . $i . '_Parentid'],
								'Childid'=>$entry_data['Assignment_' . $i . '_Childid'],
							],
						],
					];
				}
			}
			
			return $new_entries_list;
		}
		
		public function GetRecordChildrenCount($args) {
			$entry = $args['entry'];
			
			$sql = 'SELECT COUNT(Entry1.id) AS ChildRecordCount ';
			$sql .= 'FROM ';
			$sql .= 'Entry AS Entry1 ';
			$sql .= 'JOIN Assignment as Assignment1 ON Assignment1.Parentid = ? AND Assignment1.Childid = Entry1.id ';
			$sql .= 'WHERE Entry1.Publish = 1 ';
			
			$sql_bind_string = 'i';
			$sql_bind_values = [$entry['id']];
			
			$sql .= ';';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			$child_record_count = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			return $child_record_count[0]['ChildRecordCount'];
		}
		
		public function GetRecordAssociationCount($args) {
			$entry = $args['entry'];
			$ignore_parent = $args['ignore_parent'];
			
			$sql = 'SELECT COUNT(Entry1.id) AS ChildRecordCount ';
			$sql .= 'FROM ';
			$sql .= 'Entry AS Entry1 ';
			$sql .= 'JOIN Association as Association1 ON Association1.ChosenEntryid = ? AND Association1.Entryid = Entry1.id ';
			
			if($ignore_parent) {
				$sql .= 'LEFT JOIN Assignment Assignment1 ON Assignment1.Childid = Entry1.id ';
				$sql .= 'LEFT JOIN Entry EntryParent ON EntryParent.id = Assignment1.Parentid ';
				
				$sql .= 'LEFT JOIN Assignment Assignment2 ON Assignment2.Childid = EntryParent.id ';
				$sql .= 'LEFT JOIN Entry EntryGrandParent ON EntryGrandParent.id = Assignment2.Parentid ';
				
				$sql .= 'LEFT JOIN Assignment Assignment3 ON Assignment3.Childid = EntryGrandParent.id ';
				$sql .= 'LEFT JOIN Entry EntryGreatGrandParent ON EntryGreatGrandParent.id = Assignment3.Parentid ';
			}
			
			$sql .= 'WHERE Entry1.Publish = 1 ';
			
			$sql_bind_string = 'i';
			$sql_bind_values = [$entry['id']];
			
			if($ignore_parent) {
				$sql .= 'AND (ISNULL(EntryParent.Code) || EntryParent.Code != ?) ';
				$sql .= 'AND (ISNULL(EntryGrandParent.Code) || EntryGrandParent.Code != ?) ';
				$sql .= 'AND (ISNULL(EntryGreatGrandParent.Code) || EntryGreatGrandParent.Code != ?) ';
				
				$sql_bind_string .= 'sss';
				$sql_bind_values[] = $ignore_parent;
				$sql_bind_values[] = $ignore_parent;
				$sql_bind_values[] = $ignore_parent;
			}
			
			$sql .= ';';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
		#	print("<!-- BT:!!!\n\n");
			
		#	print_r($sql);
		#	print_r($sql_bind_values);
			
		#	print("\n\n-->");
			
			$child_record_count = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			return $child_record_count[0]['ChildRecordCount'];
		}
		
		public function GetRecordEntryCount($args) {
			$where = $args['where'];
			
			$sql = 'SELECT COUNT(Entry1.id) AS ChildRecordCount ';
			$sql .= 'FROM ';
			$sql .= 'Entry AS Entry1 ';
			
			$sql_bind_string = '';
			$sql_bind_values = [];
			
			if($where && count($where)) {
				$sql .= $where['sql'];
				$sql_bind_string .= $where['bind'];
				foreach($where['value'] as $where_value) {
					$sql_bind_values[] = $where_value;
				}
			}
			
			$sql .= ';';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			$child_record_count = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			return $child_record_count[0]['ChildRecordCount'];
		}
		
		public function GetUserCommentsCount($args) {
			$user = $args['user'];
			
			$sql = 'SELECT COUNT(Comment.id) AS CommentCount ';
			$sql .= 'FROM ';
			$sql .= 'Comment ';
			$sql .= 'WHERE Comment.Userid = ? AND Approved = 1 AND Rejected = 0;';
			
			$sql_bind_string = 'i';
			$sql_bind_values = [$user['id']];
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			$comment_record_count = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $comment_record_count[0]['CommentCount'];
		}
		
		public function GetUserLikesCount($args) {
			$user = $args['user'];
			
			$sql = 'SELECT COUNT(LikeDislike.id) AS LikeDislikeCount ';
			$sql .= 'FROM ';
			$sql .= 'LikeDislike ';
			$sql .= 'WHERE LikeDislike.Userid = ? AND LikeDislike.LikeOrDislike = 1;';
			
			$sql_bind_string = 'i';
			$sql_bind_values = [$user['id']];
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			$likedislikes_record_count = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $likedislikes_record_count[0]['LikeDislikeCount'];
		}
		
		public function GetUserDisLikesCount($args) {
			$user = $args['user'];
			
			$sql = 'SELECT COUNT(LikeDislike.id) AS LikeDislikeCount ';
			$sql .= 'FROM ';
			$sql .= 'LikeDislike ';
			$sql .= 'WHERE LikeDislike.Userid = ? AND LikeDislike.LikeOrDislike = 0;';
			
			$sql_bind_string = 'i';
			$sql_bind_values = [$user['id']];
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			$likedislikes_record_count = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $likedislikes_record_count[0]['LikeDislikeCount'];
		}
		
		public function GetRecordImages($args) {
		#	print("BT: GetRecordImages()!");
			$entry = $args['entry'];
			$start_index = $args['startindex'];
			$end_index = $args['endindex'];
			$order_by = $args['orderby'];
			$where = $args['where'];
			$publish = $args['publish'];
			
			$sql = 'SELECT ';
			
			$sql .= 'Image.*, Assignment.id AS Assignment_1_id, ' . implode(',', $this->EntrySelects()) . ' ';
			
			$sql .= 'FROM ';
			$sql .= 'Entry AS Entry1 ';
			
			$sql .= 'JOIN Assignment ON Assignment.Childid = Entry1.id ';
			$sql .= 'JOIN Image ON Image.Entryid = Entry1.id ';
			
			$sql_bind_string = '';
			
			if($where && count($where)) {
				$sql .= $where['sql'];
				$sql_bind_string .= $where['bind'];
				foreach($where['value'] as $where_value) {
					$sql_bind_values[] = $where_value;
				}
				
				if($entry && $entry['id']) {
					$sql .= 'AND Entry1.id = ? ';
					
					$sql_bind_string .= 'i';
					$sql_bind_values = [$entry['id']];
				}
			} elseif($publish) {
				$sql .= 'WHERE Entry1.Publish = 1 ';
				
				if($entry && $entry['id']) {
					$sql .= 'AND Entry1.id = ? ';
					
					$sql_bind_string .= 'i';
					$sql_bind_values = [$entry['id']];
				}
			} else {
				if($entry && $entry['id']) {
					$sql .= 'WHERE Entry1.id = ? ';
					
					$sql_bind_string .= 'i';
					$sql_bind_values = [$entry['id']];
				}
			}
			
			$sql .= 'GROUP BY Image.id ';
			
			if($start_index && $end_index) {
				if($order_by) {
					$sql .= ' ORDER BY ';
					if($order_by === 'RAND()') {	
						$sql .= 'RAND() ';
					} else {
						$sql .= 'Entry1.' . $order_by . ' ';
					}
				}
				
				$sql .= 'LIMIT ' . ($start_index - 1) . ',' . ($end_index) ;
			}
			
			$sql .= ';';
			
		#	print_r($sql_bind_values);
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
		#	print("<!-- BT:!!!\n\n");
		#	print_r($sql);
		#	print("\n\n-->");
			
		#	print("<PRE>");
		#	print($sql . "\n\n");
			
		#	print_r($args);
		#	print("SQL");
			$child_entries = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			#print("BT: IMAGE entrieS!");
			
			#print("<PRE>");
			
			#print_r($child_entries);
			#print("\n\n");
			
			$child_entries = $this->GetRecordTree_StructureImageRecords([
				'entrieslist'=>$child_entries,
				'extraselect'=>$args['extraselect'],
			]);
			
			#print_r($child_entries);
			
			$no_textbodies = 1;		
			if($args['alltext']) {
				$no_textbodies = 0;
			}
			
			$child_entries = $this->GetRecordTree_GetEntryChildRecords(['entrieslist'=>$child_entries, 'notextbodies'=>$no_textbodies]);
			#print_r(count($child_entries));
			
			return $child_entries;
		}
		
		public function GetRecordTree_StructureImageRecords($args) {
			$entries_list = $args['entrieslist'];
			$entries_list_count = count($entries_list);
			
			if($args['extraselect']) {
				$new_select = explode(',',$args['extraselect']);
			}
			
			$new_entries_list = [];
			
			for($i = 0; $i < $entries_list_count; $i++) {
				$entry = $entries_list[$i];
				
				$new_entry = [
					'id'=>$entry['Entry_1_id'],
					'Title'=>$entry['Entry_1_Title'],
					'Subtitle'=>$entry['Entry_1_Subtitle'],
					'ListTitle'=>$entry['Entry_1_ListTitle'],
					'ListTitleSortKey'=>$entry['Entry_1_ListTitleSortKey'],
					'Code'=>$entry['Entry_1_Code'],
					'ChildAdjective'=>$entry['Entry_1_ChildAdjective'],
					'ChildNoun'=>$entry['Entry_1_ChildNoun'],
					'ChildNounPlural'=>$entry['Entry_1_ChildNounPlural'],
					'GrandChildAdjective'=>$entry['Entry_1_GrandChildAdjective'],
					'GrandChildNoun'=>$entry['Entry_1_GrandChildNoun'],
					'GrandChildNounPlural'=>$entry['Entry_1_GrandChildNounPlural'],
					'Publish'=>$entry['Entry_1_Publish'],
					'OriginalCreationDate'=>$entry['Entry_1_OriginalCreationDate'],
					'LastModificationDate'=>$entry['Entry_1_LastModificationDate'],
					'assignment'=>[
						[
							'id'=>$entry['Assignment_1_id'],
							'Parentid'=>$entry['Assignment_1_Parentid'],
							'Childid'=>$entry['Assignment_1_Childid'],
						],
					],
					'image_base'=>[
						'id'=>$entry['id'],
						'Title'=>$entry['Title'],
						'Description'=>$entry['Description'],
						'FileName'=>$entry['FileName'],
						'FileDirectory'=>$entry['FileDirectory'],
						'IconFileName'=>$entry['IconFileName'],
						'StandardFileName'=>$entry['StandardFileName'],
						'PixelWidth'=>$entry['PixelWidth'],
						'PixelHeight'=>$entry['PixelHeight'],
						'IconPixelWidth'=>$entry['IconPixelWidth'],
						'IconPixelHeight'=>$entry['IconPixelHeight'],
						'StandardPixelWidth'=>$entry['StandardPixelWidth'],
						'StandardPixelHeight'=>$entry['StandardPixelHeight'],
						'OriginalCreationDate'=>$entry['OriginalCreationDate'],
						'LastModificationDate'=>$entry['LastModificationDate'],
					],
				];
				
				if($new_select) {
					foreach($new_select as $select_field) {
						$select_pieces = explode(' AS ', $select_field);
						$new_entry[$select_pieces[1]] = $entry[$select_pieces[1]];
					}
				}
				
				$new_entries_list[] = $new_entry;
			}
			
			return $new_entries_list;
		}
		
		public function GetRecordAssociated($args) {
			$entry = $args['entry'];
			$start_index = $args['startindex'];
			$end_index = $args['endindex'];
			$order_by = $args['orderby'];
			$where = $args['where'];
			$publish = $args['publish'];
			$ignore_parent = $args['ignore_parent'];
			
			$selects = $this->GetRecordTree_GetEntries_GetBaseSelect([
				'index'=>1,
				'noassignment'=>TRUE,
				'association'=>TRUE,
			]);
			
			$sql = 'SELECT ';
			$sql .= implode(', ', $selects) . ' ';
			
			if($args['extraselect']) {
				$sql .= ',' . $args['extraselect'] . ' ';
			}
			
			$sql .= 'FROM ';
			$sql .= 'Entry AS Entry1 ';
			
			$sql .= 'JOIN Association AS Association1 ON Association1.ChosenEntryid = ? AND Association1.Entryid = Entry1.id ';
			
			$sql_bind_string = 'i';
			$sql_bind_values = [$entry['id']];
			
			if($ignore_parent) {
				$sql .= 'LEFT JOIN Assignment Assignment1 ON Assignment1.Childid = Entry1.id ';
				$sql .= 'LEFT JOIN Entry EntryParent ON EntryParent.id = Assignment1.Parentid ';
				
				$sql .= 'LEFT JOIN Assignment Assignment2 ON Assignment2.Childid = EntryParent.id ';
				$sql .= 'LEFT JOIN Entry EntryGrandParent ON EntryGrandParent.id = Assignment2.Parentid ';
				
				$sql .= 'LEFT JOIN Assignment Assignment3 ON Assignment3.Childid = EntryGrandParent.id ';
				$sql .= 'LEFT JOIN Entry EntryGreatGrandParent ON EntryGreatGrandParent.id = Assignment3.Parentid ';
			}
			
			if($where && count($where)) {
				$sql .= $where['sql'];
				$sql_bind_string .= $where['bind'];
				foreach($where['value'] as $where_value) {
					$sql_bind_values[] = $where_value;
				}
			} elseif($publish) {
				$sql .= 'WHERE Entry1.Publish = 1 ';
			}
			
			if($ignore_parent) {
				$sql .= 'AND (ISNULL(EntryParent.Code) || EntryParent.Code != ?) ';
				$sql .= 'AND (ISNULL(EntryGrandParent.Code) || EntryGrandParent.Code != ?) ';
				$sql .= 'AND (ISNULL(EntryGreatGrandParent.Code) || EntryGreatGrandParent.Code != ?) ';
				$sql .= 'GROUP BY Entry1.id ';
				
				$sql_bind_string .= 'sss';
				$sql_bind_values[] = $ignore_parent;
				$sql_bind_values[] = $ignore_parent;
				$sql_bind_values[] = $ignore_parent;
			}
			
			if($start_index && $end_index) {
				if($order_by) {
					$sql .= ' ORDER BY ';
					if($order_by === 'RAND()') {	
						$sql .= 'RAND() ';
					} else {
						$sql .= 'Entry1.' . $order_by . ' ';
					}
				}
				
				$sql .= 'LIMIT ' . ($start_index - 1) . ',' . ($end_index) ;
			}
			
			$sql .= ';';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			
			if($ignore_parent) {
	#		print("<!-- BT:!!!\n\n");
	#		print_r($sql);
	#		print_r($sql_bind_values);
	#		print("\n\n-->");
		
			}
			
			#print("<PRE>");
			#print($sql . "\n\n");
			#print("SQL");
			$child_entries = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			
			
		
		#	print("<!-- BT:!!!\n\n");
		#	print_r($child_entries);
		#	print_r($sql);
		#	print("\n\n-->");
			
			#print("BT: ASSOC entrieS!");
			
			#print_r($child_entries);
			$child_entries = $this->GetRecordTree_StructureChildRecords(['entrieslist'=>$child_entries, 'extraselect'=>$args['extraselect']]);
			
			$no_textbodies = 1;		
			if($args['alltext']) {
				$no_textbodies = 0;
			}
			
			$child_entries = $this->GetRecordTree_GetEntryChildRecords(['entrieslist'=>$child_entries, 'notextbodies'=>$no_textbodies]);
			
			return $child_entries;
		}
		
		public function GetRecordChildren($args) {
			$entry = $args['entry'];
			$start_index = $args['startindex'];
			$end_index = $args['endindex'];
			$order_by = $args['orderby'];
			$where = $args['where'];
			$publish = $args['publish'];
			
			$selects = $this->GetRecordTree_GetEntries_GetBaseSelect(['index'=>1, 'noassignment'=>$args['noassignment']]);
			
			$sql = 'SELECT ';
			$sql .= implode(', ', $selects) . ' ';
			
			if($args['extraselect']) {
				$sql .= ',' . $args['extraselect'] . ' ';
			}
			
			$sql .= 'FROM ';
			$sql .= 'Entry AS Entry1 ';
			
			if(!$args['noassignment']) {
				$sql .= 'JOIN Assignment AS Assignment1 ON Assignment1.Parentid = ? AND Assignment1.Childid = Entry1.id ';
				$sql_bind_string = 'i';
				$sql_bind_values = [$entry['id']];
			}
			
			if($where && count($where)) {
				$sql .= $where['sql'];
				$sql_bind_string .= $where['bind'];
				foreach($where['value'] as $where_value) {
					$sql_bind_values[] = $where_value;
				}
			} elseif($publish) {
				$sql .= 'WHERE Entry1.Publish = 1 ';
			}
		#	print_r($sql_bind_values);
		#	print_r($sql);
			if($start_index && $end_index) {
				if($order_by) {
					$sql .= ' ORDER BY ';
					if($order_by === 'RAND()') {	
						$sql .= 'RAND() ';
					} else {
						$sql .= 'Entry1.' . $order_by . ' ';
					}
				}
				
				$sql .= 'LIMIT ' . ($start_index - 1) . ',' . ($end_index) ;
			}
#			print("BT:");
#			print_r($sql_bind_values);
#			print_r($sql);
			$sql .= ';';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			
			
		#	print("\n\n<!-- BT:ORM\n\n");
			#print_r($child_entries);
			
		#	print("sql . " . $sql . "|" . $sql_bind_string . "|");
		#	print_r($sql_bind_values);
		#	print("\n\n-->");
			
		#	print("<sql . " . $sql_bind_string . "|");
		#	print_r($sql_bind_values);
		#	print(">\n\n\n");
			
			$child_entries = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			
##			print_r($child_entries);
#			print_r($sql_bind_values);
#			print_r($sql);
			$child_entries = $this->GetRecordTree_StructureChildRecords(['entrieslist'=>$child_entries, 'extraselect'=>$args['extraselect']]);
			

			
			$no_textbodies = 1;		
			if($args['alltext']) {
				$no_textbodies = 0;
			}
#			print_r($args);
			$child_entries = $this->GetRecordTree_GetEntryChildRecords(['entrieslist'=>$child_entries, 'notextbodies'=>$no_textbodies]);
			
			return $child_entries;
		}
		
		public function SetAssociationEntryParentRelatedRecords($args) {
			$entries = $args['entries'];
			
			$entries_count = count($entries);
			
			if($entries_count === 0) {
				return $entries;
			}
			
			$entries = $this->SetAssociationEntryParentRelatedRecords_fillParentAssociations([
				'entries'=>$entries,
				'count'=>$entries_count,
			]);
			
			$entries = $this->SetAssociationEntryParentRelatedRecords_fillAssociationEntries([
				'entries'=>$entries,
				'count'=>$entries_count,
			]);
			
			$entries = $this->SetAssociationEntryParentRelatedRecords_fillChildEntries([
				'entries'=>$entries,
				'count'=>$entries_count,
			]);
			
			return $entries;
		}
		
		public function SetAssociationEntryParentRelatedRecords_fillParentAssociations($args) {
			$entries = $args['entries'];
			$entries = $this->SetAssociationEntryParentRelatedRecords_fillParentRecords([
				'entries'=>$entries,
				'count'=>$args['count'],
				'record_key'=>'association',
				'record_type'=>'Association',
			]);
			
			$entries = $this->SetAssociationEntryParentRelatedRecords_fillParentRecords([
				'entries'=>$entries,
				'count'=>$args['count'],
				'record_key'=>'image',
				'record_type'=>'Image',
			]);
			
			return $entries;
		}
		
		public function SetAssociationEntryParentRelatedRecords_fillParentRecords($args) {
			$entries = $args['entries'];
			$count = $args['count'];
			
			$record_key = $args['record_key'];
			$record_type = $args['record_type'];
			
			$parent_ids = [];
			
			for($i = 0; $i < $count; $i++) {
				$entry = $entries[$i];
				
				if(!$entry[$record_key] || !$entry[$record_key][0]) {
					$parent_ids[$entry['Parent_id']] = TRUE;
				}
			}
			
			$parent_ids_array = array_keys($parent_ids);
			$sql = 'SELECT * FROM ' . $record_type . ' WHERE Entryid IN(' . implode(', ', array_fill(0, count($parent_ids_array), '?')) . ')';
			$sql_bind_string = str_repeat('i', count($parent_ids_array));
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$parent_ids_array,
			];
			
			$parent_related_records = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			$parent_related_record_hash = [];
			
			for($i = 0; $i < count($parent_related_records); $i++) {
				$parent_related_record = $parent_related_records[$i];
				if(!$parent_related_record_hash[$parent_related_record['Entryid']]) {
					$parent_related_record_hash[$parent_related_record['Entryid']] = [];
				}
				$parent_related_record_hash[$parent_related_record['Entryid']][] = $parent_related_record;
			}
			
			for($i = 0; $i < $count; $i++) {
				$entry = $entries[$i];
				
				if(!$entry[$record_key] || !$entry[$record_key][0]) {
					$entry[$record_key] = $parent_related_record_hash[$entry['Parent_id']];
				}
				
				$entries[$i] = $entry;
			}
			
			return $entries;
		}
		
		public function SetAssociationEntryParentRelatedRecords_fillChildEntries($args) {
			$entries = $args['entries'];
			$count = $args['count'];
			
			$association_entries = [];
			
			for($i = 0; $i < $count; $i++) {
				$entry = $entries[$i];
				
				$associations = $entry['association'];
				
				if($associations) {
					for($j = 0; $j < count($associations); $j++) {
						$association = $associations[$j];
						
						$association_entries[] = $association['entry'];
					}
				}
			}
			
			$filled_association_entries = $this->GetRecordTree_GetEntryChildRecords(['entrieslist'=>$association_entries, 'notextbodies'=>1]);
			$filled_association_entries_count = count($filled_association_entries);
			$filled_association_entries_hash = [];
			
			for($i = 0; $i < $filled_association_entries_count; $i++) {
				$filled_association_entry = $filled_association_entries[$i];
				$filled_association_entries_hash[$filled_association_entry['id']] = $filled_association_entry;
			}
			
			for($i = 0; $i < $count; $i++) {
				$entry = $entries[$i];
				$associations = $entry['association'];
				
				if($associations) {				
					for($j = 0; $j < count($associations); $j++) {
						$association = $associations[$j];
						$association['entry'] = $filled_association_entries_hash[$association['entry']['id']];
						$associations[$j] = $association;
					}
					
					$entry['association'] = $associations;
				}
				$entries[$i] = $entry;
			}
			
			return $entries;
		}
		
		public function SetAssociationEntryParentRelatedRecords_fillAssociationEntries($args) {
			$entries = $args['entries'];
			$count = $args['count'];

			$association_entry_ids = [];
			
			for($i = 0; $i < $count; $i++) {
				$entry = $entries[$i];
				
				$associations = $entry['association'];
				
				if($associations) {
					for($j = 0; $j < count($associations); $j++) {
						$association = $associations[$j];
						$association_entry_ids[] = $association['ChosenEntryid'];
					}
				}
			}
			
			$sql = 'SELECT * FROM Entry  WHERE id IN(' . implode(', ', array_fill(0, count($association_entry_ids), '?')) . ')';
			$sql_bind_string = str_repeat('i', count($association_entry_ids));
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$association_entry_ids,
			];
			
			$association_entries = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			$association_entries_count = count($association_entries);
			
			$association_entries_hash = [];
			
			for($i = 0; $i < $association_entries_count; $i++) {
				$association_entry = $association_entries[$i];
				$association_entries_hash[$association_entry['id']] = $association_entry;
			}
			
			for($i = 0; $i < $count; $i++) {
				$entry = $entries[$i];
				$associations = $entry['association'];
				
				if($associations) {
					for($j = 0; $j < count($associations); $j++) {
						$association = $associations[$j];
						$association_entry = $association_entries_hash[$association['ChosenEntryid']];
						$association['entry'] = $association_entry;
						$associations[$j] = $association;
					}
					
					$entry['association'] = $associations;
				}
				$entries[$i] = $entry;
			}
			
			return $entries;
		}
		
		public function GetRecordAndChildren($args) {
			$entry = $args['entry'];
			$where = $args['where'];
			
			$selects = $this->GetRecordTree_GetEntries_GetBaseSelect(['index'=>1, 'noassignment'=>1]);
			
			$sql = 'SELECT ';
			$sql .= implode(', ', $selects) . ' ';
			$sql .= 'FROM ';
			$sql .= 'Entry as Entry1 ';
			
			$sql_bind_string = '';
			$sql_bind_values = [];
			
			if($entry && $entry['id']) {
				$sql .= 'WHERE Entry1.id = ? AND Entry1.Publish = 1 ';
				$sql_bind_string .= 'i';
				$sql_bind_values[] = $entry['id'];
			} else {
				$sql .= 'WHERE Entry1.Publish = 1 ';
			}
			
			if($where && count($where)) {
				$sql .= $where['sql'];
				$sql_bind_string .= $where['bind'];
				foreach($where['value'] as $where_value) {
					$sql_bind_values[] = $where_value;
				}
			}
			
			$sql .= ';';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$sql_bind_values,
			];
			
			$child_entries = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			$child_entries = $this->GetRecordTree_StructureChildRecords(['entrieslist'=>$child_entries]);
			$no_textbodies = 1;			
			if($args['alltext']) {
				$no_textbodies = 0;
			}
			$child_entries = $this->GetRecordTree_GetEntryChildRecords(['entrieslist'=>$child_entries, 'notextbodies'=>$no_textbodies]);
			
			return $child_entries;
		}
		
		public function GetSiblings($args) {
			$entry = $args['entry'];
			$parent = $args['parent'];
			
			$selects = $this->GetRecordTree_GetEntries_GetBaseSelect(['index'=>1, 'noassignment'=>1]);
			
			$siblings = [];
			$types = [
				'younger'=>'<',
				'older'=>'>',
			];
			
			foreach($types as $type_key => $type_operator) {
				$sql = 'SELECT ';
				$sql .= implode(', ', $selects) . ' ';
				$sql .= 'FROM ';
				$sql .= 'Entry AS Entry1 ';
				
				$sql .= 'JOIN Assignment AS Assignment1 ON Assignment1.Childid = Entry1.id AND Assignment1.Parentid = ? ';
				
				$sql .= 'WHERE Entry1.Publish = 1 AND Entry1.ListTitle ' . $type_operator . ' ? ';
				
				$sql .= 'ORDER BY Entry1.ListTitleSortKey ';
				
				if($type_operator == '<') {
					$sql .= ' DESC ';
				}
				
				$sql .= 'LIMIT 10;';
				
				$sql_bind_string = 'is';
				$sql_bind_values = [
					$parent['id'],
					$entry['ListTitle'],
				];
				
				$fill_arrays_from_db_args = [
					'query'=>$sql,
					'sqlbindstring'=>$sql_bind_string,
					'recordvalues'=>$sql_bind_values,
				];
				
				$type_siblings = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
				$type_siblings = $this->GetRecordTree_StructureChildRecords(['entrieslist'=>$type_siblings]);
				$type_siblings = $this->GetRecordTree_GetEntryChildRecords(['entrieslist'=>$type_siblings, 'notextbodies'=>1]);
				
				$siblings[$type_key] = $type_siblings;
			}
			
			$given_younger_siblings = $siblings['younger'];
			$given_younger_siblings_count = count($given_younger_siblings);
			$younger_siblings = [];
			
			for($i = $given_younger_siblings_count - 1; $i >= 0; $i--) {
				$younger_sibling = $given_younger_siblings[$i];
				$younger_siblings[] = $younger_sibling;
			}
			
			$siblings['younger'] = $younger_siblings;
			
			return $siblings;
		}
		
		public function GetRecordTree_GetEntryChildRecords_PostProcessing($args) {
			$table = $args['table'];
			$records = $args['records'];
			
			switch($table) {
				case 'TextBody':
					$record_count = count($records);
					for($i = 0; $i < $record_count; $i++) {
						$record = $records[$i];
						$record['FirstThousandCharacters'] = mb_substr($record['FirstThousandCharacters'], 0, -1);
						$records[$i] = $record;
					}
					break;
					
				case 'EventDate':
					$record_count = count($records);
					for($i = 0; $i < $record_count; $i++) {
						$record = $records[$i];
						$record_datetime = $record['EventDateTime'];
						$record_datetime_pieces = explode('-', $record_datetime);
						$year = $record_datetime_pieces[0];
		#				print("<!-- BT: New datetime!!!\n\n");
		#				print_r($year);
		#				print("-->");
						if((int)$year >= 3000) {
							$diff = $year - 3000;
							$real_year_number = 1000 - $diff;
							
							$bce_date = FALSE;
							if($real_year_number < 0) {
								$bce_date = TRUE;
							}
							
							$real_year = strval($real_year_number);
							
							if($bce_date) {
								$real_year = str_replace('-', '', $real_year);
							}
							
							if(strlen($real_year) < 4) {
						#		$real_year = '0' . $real_year;
							}
							
							if($bce_date) {
								$real_year = 'bce' . $real_year;
							}
							
							$record_datetime_pieces[0] = $real_year;
					
							$record['EventDateTime'] = implode('-', $record_datetime_pieces);
					#		print("<!-- BT: New datetime!!!\n\n");
					#		print_r($record);
					#		print_r(implode('-', $record_datetime_pieces));
					#		print("-->");
							$records[$i] = $record;
						}
					}
				#case 'Image':
		#			print("<!-- BT: IMAGE!\n\n");
		#			print_r($record);
		#			print("-->");
			#		$record['Title'] = '';
			#		$record['Description'] .= 'doo';
					break;
			}
			
			return $records;
		}
		
		public function GetRecordTree_GetEntryChildRecords($args) {
			$entries_list = $args['entrieslist'];
			$no_textbodies = $args['notextbodies'];
			$child_records = [];
			
			if(count($entries_list)) {
				$queries = [];
				$query_values = [];
				$query_bindstrings = [];
				
				$bindstring = '';
				
				$ids = [];
				foreach($entries_list as $entry) {
					$ids[] = $entry['id'];
					$bindstring .= 'i';
				}
				
				$question_marks = array_fill(0, count($entries_list), '?');
				$question_mark_string = implode(', ', $question_marks);
				
				$tables = $this->GetChildRecordTypes();
				
				foreach($tables as $table) {
					$sql = 'SELECT ';
					
					if($table === 'TextBody' && $no_textbodies) {
						$sql .= 'id, substring(Text,1,1000) as FirstThousandCharacters, Language, WordCount, CharacterCount, Source, Entryid, OriginalCreationDate, LastModificationDate ';
					} else {
						$sql .= '* ';
					}
					
					$sql .= 'FROM ' . $table . ' WHERE Entryid IN(' . $question_mark_string . ')';
					
					$queries[$table] =  $sql;
				}
				
				$queries['Associated'] = 'SELECT * FROM Association JOIN Entry ON Entry.id = Association.Entryid AND Entry.Publish = 1 WHERE ChosenEntryid IN(' . $question_mark_string . ')';
				$tables[] = 'Associated';
				
				$query_results = [];
				
				foreach ($queries as $table => $query) {
					$fill_arrays_from_db_args = [
						'query'=>$query,
						'sqlbindstring'=>$bindstring,
						'recordvalues'=>$ids,
					];
					
					$query_results[$table] = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
					$query_results[$table] = $this->GetRecordTree_GetEntryChildRecords_PostProcessing(['table'=>$table, 'records'=>$query_results[$table]]);
				}
			
				$query_results_by_entryid_and_table = [];
				
				foreach ($query_results as $table => $query_result) {
					$query_results_by_entryid_and_table[$table] = [];
					foreach ($query_result as $record_result) {
						$record_key = 'Entryid';
						
						if($table === 'Associated') {
							$record_key = 'ChosenEntryid';
						}
						
						if(!$query_results_by_entryid_and_table[$table][$record_result[$record_key]]) {
							$query_results_by_entryid_and_table[$table][$record_result[$record_key]] = [];
						}
						
						$query_results_by_entryid_and_table[$table][$record_result[$record_key]][] = $record_result;
					}
					
					$string = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $string);
				}
				
				foreach ($entries_list as $entry) {
					foreach($tables as $table) {
						$entry[strtolower($table)] = $query_results_by_entryid_and_table[$table][$entry['id']];
					}
					
					$new_entries_list[] = $entry;
				}
				
#				print_r($new_entries_list);
				
				$entries_list = $new_entries_list;
			} else {
			#	print("GET ZERO ENTRY LIST ENTRIES!");
			}
			
			return $entries_list;
		}
		
		public function GetRecordTree_GetEntries_GetBaseSelect($args) {
			$index = $args['index'];
			
			if($args['list']) {
				$selects = $args['list'];
			} else {
				$selects = [];
			}
			
			$selects[] = 'Entry' . $index . '.id as Entry_' . $index . '_id';
			$selects[] = 'Entry' . $index . '.Title as Entry_' . $index . '_Title';
			$selects[] = 'Entry' . $index . '.Subtitle as Entry_' . $index . '_Subtitle';
			$selects[] = 'Entry' . $index . '.ListTitle as Entry_' . $index . '_ListTitle';
			$selects[] = 'Entry' . $index . '.ListTitleSortKey as Entry_' . $index . '_ListTitleSortKey';
			$selects[] = 'Entry' . $index . '.Code as Entry_' . $index . '_Code';
			$selects[] = 'Entry' . $index . '.ChildAdjective as Entry_' . $index . '_ChildAdjective';
			$selects[] = 'Entry' . $index . '.ChildNoun as Entry_' . $index . '_ChildNoun';
			$selects[] = 'Entry' . $index . '.ChildNounPlural as Entry_' . $index . '_ChildNounPlural';
			$selects[] = 'Entry' . $index . '.GrandChildAdjective as Entry_' . $index . '_GrandChildAdjective';
			$selects[] = 'Entry' . $index . '.GrandChildNoun as Entry_' . $index . '_GrandChildNoun';
			$selects[] = 'Entry' . $index . '.GrandChildNounPlural as Entry_' . $index . '_GrandChildNounPlural';
			$selects[] = 'Entry' . $index . '.Publish as Entry_' . $index . '_Publish';
			$selects[] = 'Entry' . $index . '.OriginalCreationDate as Entry_' . $index . '_OriginalCreationDate';
			$selects[] = 'Entry' . $index . '.LastModificationDate as Entry_' . $index . '_LastModificationDate';
			
			if(!$args['noassignment']) {
				$selects[] = 'Assignment' . $index . '.id as Assignment_' . $index . '_id';
				$selects[] = 'Assignment' . $index . '.Parentid as Assignment_' . $index . '_Parentid';
				$selects[] = 'Assignment' . $index . '.Childid as Assignment_' . $index . '_Childid';
			}
			
			if($args['association']) {
				$selects[] = 'Association' . $index . '.id as Association_' . $index . '_id';
				$selects[] = 'Association' . $index . '.ChosenEntryid as Association_' . $index . '_ChosenEntryid';
				$selects[] = 'Association' . $index . '.Entryid as Association_' . $index . '_Entryid';
			}
			
			return $selects;
		}
		
		public function DeleteEntry($args) {
			$entry = $args['entry'];
			
			$sql = 'DELETE FROM Entry WHERE id = ' . $entry['id'] . ';';
			$sql_bind_string = '';
			$record_values = [];
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$record_values,
			];
			
			return $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
		}
		
		public function DeleteChildRecords($args) {
			$entry = $args['parent'];
			$record_type = $args['recordtype'];
			$record_ids_to_keep = $args['recordidstokeep'];
			
			if($record_type == 'Association') {
				$sql = 'DELETE Association FROM ' . $record_type;
				$sql .= ' LEFT JOIN Entry ON Entry.id = Association.Entryid ';
				
				$sql .= ' WHERE ChosenEntryid = ' . $entry['id'] . ' ';
				$sql .= ' AND Entry.id IS NULL ';
				$sql_bind_string = '';
				
				$fill_arrays_from_db_args = [
					'query'=>$sql,
					'sqlbindstring'=>$sql_bind_string,
					'recordvalues'=>[],
				];
				
				if($this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args)['line']) {
					return FALSE;
				}
			}
			
			$sql = 'DELETE FROM ' . $record_type;
			
			$sql .= ' WHERE Entryid = ' . $entry['id'];
			
			if(count($record_ids_to_keep)) {
				$sql .= ' AND id NOT IN (' ;
				
				$sql .= implode(', ', array_fill(0, count($record_ids_to_keep), '?'));
			
				$sql .= ')';
				
				$sql_bind_string = str_repeat('i', count($record_ids_to_keep));
			} else {
				$sql_bind_string = '';
			}
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>$record_ids_to_keep,
			];
			
			if($this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args)['line']) {
				return FALSE;
			}
			
			return TRUE;
		}
		
		public function GetLastChildAdded($args) {
			$entry = $args['entry'];
			$where = $args['where'];
			
			$sql = 'SELECT Entry.* FROM Entry ';
			$sql .= 'JOIN Assignment ON Parentid = ? and Childid = Entry.id ';
			
			if($where) {
				$sql .= 'WHERE ' . $where . ' ';
			} else {
				$sql .= 'WHERE Entry.Publish = 1 ';
			}
			
			$sql .= 'ORDER BY Entry.OriginalCreationDate DESC LIMIT 1';
			
			$sql_bind_string = 'i';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>[$entry['id']],
			];
			
			$results = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			if($results) {
				return $results[0];
			}
			
			return FALSE;
		}
		
		public function GetLastChildEdited($args) {
			$entry = $args['entry'];
			$where = $args['where'];
			
			$sql = 'SELECT * FROM Entry ';
			$sql .= 'JOIN Assignment ON Parentid = ? and Childid = Entry.id ';
			
			if($where) {
				$sql .= 'WHERE ' . $where . ' ';
			} else {
				$sql .= 'WHERE Entry.Publish = 1 ';
			}
			
			$sql .= 'ORDER BY Entry.LastModificationDate DESC LIMIT 1';
			
			$sql_bind_string = 'i';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$sql_bind_string,
				'recordvalues'=>[$entry['id']],
			];
			
			$results = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			if($results) {
				return $results[0];
			}
			
			return FALSE;
		}
		
		public function GetTagCounts($args) {
			$tags = $args['tags'];
			
			if($tags && count($tags)) {
				$sql = 'SELECT DISTINCT Tag, Count(Tag.id) AS Count ';
				$sql .= 'FROM Tag ';
				$sql .= 'WHERE Tag.Tag IN(';
	
				$question_marks = array_fill(0, count($tags), '?');
				$question_mark_string = implode(', ', $question_marks);
				$sql .= $question_mark_string;
				
				$sql .= ') ';
				$sql .= 'GROUP BY Tag.Tag ';
				$sql .= 'ORDER BY Tag.Tag;';
				
				$sql_bind_string = str_repeat('s', count($tags));
				
				$sql_args = [];
				
				foreach($tags as $tag) {
					$sql_args[] = $tag;
				}
				
				$fill_arrays_from_db_args = [
					'query'=>$sql,
					'sqlbindstring'=>$sql_bind_string,
					'recordvalues'=>$sql_args,
				];
				
				$tag_counts = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
				
				$tag_counts_hash = [];
				
			#	print_r($tag_counts);
				
				foreach($tag_counts as $tag_count) {
					$tag_counts_hash[$tag_count['Tag']] = $tag_count['Count'];
				}
				
				return $tag_counts_hash;
			}
			
			return [];
		}
		
		public function buildRecordTreeForEntryids($args) {
			$entry_ids = $args['entryids'];
			
			$max = $this->handler->globals->buildRecordTreeForEntryid_MaxDepth();
			
			$sql = 'SELECT ';
			
			for($i = 1; $i <= $max; $i++) {
				$sql .= 'Entry' . $i . '.id AS Entry' . $i . '_id, ';
				$sql .= 'Entry' . $i . '.Code AS Entry' . $i . '_Code, ';
				$sql .= 'Entry' . $i . '.Title AS Entry' . $i . '_Title, ';
				
				$sql .= 'Assignment' . $i . '.id AS Assignment' . $i . '_id';
				
				if($i !== $max) {
					$sql .= ',';
				}
				$sql .= ' ';
			}
			
			$sql .= 'FROM Entry Entry1 ';
			$sql .= 'LEFT JOIN Assignment Assignment1 ON Assignment1.Childid = Entry1.id ';
			
			#$sql .= 'SELECT Entry1.id as Entry1_id, Entry1.Code, Entry1.Title, Assignment1.Parentid, ';
			#$sql .= 'Entry2.id as Entry2_id, Entry2.Code, Entry2.Title, Assignment2.Parentid ';
			#$sql .= 'FROM Entry Entry1 ';
			#$sql .= 'LEFT JOIN Assignment Assignment1 ON Assignment1.Childid = Entry1.id ';
			
			
			for($i = 2; $i <= $max; $i++) {
				$previous = $i - 1;
				
			#	$sql .= 'LEFT JOIN Entry Entry2 ON Entry2.id = Assignment1.Parentid ';
			#	$sql .= 'LEFT JOIN Assignment Assignment2 ON Assignment2.Childid = Entry2.id ';
				
				$sql .= 'LEFT JOIN Entry Entry' . $i . ' ON Entry' . $i . '.id = Assignment' . $previous . '.Parentid ';
				$sql .= 'LEFT JOIN Assignment Assignment' . $i . ' ON Assignment' . $i . '.Childid = Entry' . $i . '.id ';
			}
			
			$sql .= 'WHERE Entry1.id IN (' . implode(', ', array_fill(0, count($entry_ids), '?')) . ')';
			
			$bindstring = implode('', array_fill(0, count($entry_ids), 's'));
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>$bindstring,
				'recordvalues'=>$entry_ids,
			];
			
			$record_list = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			$good_records = [];
			
			for($i = 0; $i < count($record_list); $i++) {
				$record_set = $record_list[$i];
				
				$good_record_set = $this->buildRecordSetFromResults(['record_set'=>$record_set]);
				
				$good_records[] = $good_record_set;
			}
			
		#	print("<PRE>");
		#	print_r($record_list);
		#	print_r($good_records);
		#	print("</PRE>");
			
			return $good_records;
		}
		
		public function buildRecordSetFromResults($args) {
			$record_set = $args['record_set'];
			
			$good_set = [];
			$max = $this->handler->globals->buildRecordTreeForEntryid_MaxDepth();
			
			for($i = 1; $i <= $max; $i++) {
				$entry_key = 'Entry' . $i;
				if($record_set[$entry_key . '_id']) {
					$assignment_key = 'Assignment' . $i;
					
					$new_entry = [
						'id'=>$record_set[$entry_key . '_id'],
						'Code'=>$record_set[$entry_key . '_Code'],
						'Title'=>$record_set[$entry_key . '_Title'],
						'Assignmentid'=>$record_set[$assignment_key . '_id'],
					];
					
					$good_set[] = $new_entry;
				}
			}
			
			return $good_set;
		}
		
		public function getPrimaryChildren() {
			$sql = '';
			$sql .= 'SELECT Entry.* ';
			$sql .= 'FROM Entry ';
			$sql .= 'JOIN Assignment ON Assignment.Childid = Entry.id ';
			$sql .= 'JOIN Entry Parent ON Parent.id = Assignment.Parentid ';
			$sql .= 'JOIN Assignment ParentAssignment ON ParentAssignment.Parentid = Parent.id AND ParentAssignment.Childid = 0 ';
			$sql .= 'ORDER BY ListTitleSortKey ';
			
			$fill_arrays_from_db_args = [
				'query'=>$sql,
				'sqlbindstring'=>'',
				'recordvalues'=>[],
			];
			
			$primary_children = $this->handler->db_access->FillArraysFromDB($fill_arrays_from_db_args);
			
			return $primary_children;
		}
	}

?>