<?php

	ggreq('traits/scripts/DBFunctions.php');
	ggreq('traits/scripts/SimpleErrors.php');
	ggreq('traits/scripts/SimpleForms.php');
	ggreq('traits/scripts/SimpleLookupLists.php');
	ggreq('traits/scripts/SimpleORM.php');
	ggreq('traits/scripts/SimpleSocialMedia.php');
	
	class view extends basicscript {
						// Traits
						// ---------------------------------------------
		
		use DBFunctions;
		use SimpleErrors;
		use SimpleForms;
		use SimpleLookupLists;
		use SimpleORM;
		use SimpleSocialMedia;
		
						// Security Data
						// ---------------------------------------------
		
		public function IsSecure() {
			return FALSE;
		}
		
		public function RequiresLogin() {
			return FALSE;
		}
		
						// Select Entry by ID Form
						// ---------------------------------------------
		
		public function Select() {
			$this->SetOrmBasics();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# Causes 404
			}
			
			$this->by = $this->param('by');
			
			if(
				($this->by === 'id') ||
				($this->by === 'Title') ||
				($this->by === 'Code') ||
				($this->by === 'Description') ||
				($this->by === 'Quote') ||
				($this->by === 'Link') ||
				($this->by === 'TextBody') ||
				($this->by === 'Tag') ||
				($this->by === 'AvailabilityStart') ||
				($this->by === 'AvailabilityEnd') ||
				($this->by === 'Level')
			) {
				$this->fieldname_validity = 1;
				$this->select = $this->param('Select');	# Form Button
				
				if($this->select) {
					$fieldname = $this->param('fieldname');
					$this->urlaction = $this->param('urlaction');
					
					switch($this->urlaction) {
						case 'view':
							$this->script_name = 'view.php';
							break;
						
						case 'edit':
							$this->script_name = 'modify.php?action=Edit';
							break;
					}
					
					if($fieldname) {
						$this->fieldname = $fieldname;
						$this->matchlike = $this->param('matchlike');
						
						$orm_match_args = [
							'fieldname'=>$this->by,
							'fieldvalue'=>$this->fieldname,
							'matchlike'=>$this->matchlike,
						];
						
						$record_results = $this->SearchForEntries($orm_match_args);
					#	print_r($record_results);
						if($record_results['error']) {
							$this->admin_errors[] = $record_results;
						} else {
							$this->selections = $record_results;
							$this->StatusDataArray = [];
							foreach($this->selections as $entry) {
								$this->StatusDataArray[] = [
									$this->Bullet() .
									$this->NonBreakingSpace() .
									$this->GetHyperlinkedEntryView([
										'entry'=>$entry,
										'entrylist'=>$entry['parents'],
										'scriptname'=>$this->script_name,
										'by'=>$this->by,
									])
								];
							}
						}
					} else {
						$this->errors[] = ['You must enter some search term in order to search.'];
					}
				}
			} else {
				$this->errors[] = ['The selected fieldname, "' . $this->CleanseForDisplay($this->by) . '", is invalid.'];
				$this->fieldname_validity = 0;
			}
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
						// User Functionality
						// ---------------------------------------------
			
							// Main Browse Functionality
							// ---------------------------------------------
		
		public function viewUserLikes() {
			$this->SetORMBasics();
		#	$this->SetRecordTree();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			$this->getEntryLikesAndDislikes();
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
		public function getEntryLikesAndDislikes() {
			$likedislike_get_args = [
				'type'=>'LikeDislike',
				'definition'=>[
					'LikeOrDislike'=>1,
					'Entryid'=>$this->entry['id'],
				],
				'joins'=>[
					'JOIN'=>[
						'User'=>'User.id = LikeDislike.Userid',
					],
				],
				'orderby'=>'OriginalCreationDate DESC',
			];
			
			return $this->likes = $this->handler->db_access->GetRecords($likedislike_get_args);
		}
		
						// Browse Functionality
						// ---------------------------------------------
			
							// Main Browse Functionality
							// ---------------------------------------------
		
		public function browse() {
			$this->SetORMBasics();
		#	$this->SetRecordTree();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			$this->SetChildRecordCount();
			$this->entry_count = $this->children_count;
			/*
			if($this->entry_count > 50) {
				ini_set('memory_limit','150M');
			} else if($this->entry_count > 100) {
				ini_set('memory_limit','200M');
			} else if($this->entry_count > 150) {
				ini_set('memory_limit','250M');
			}
			*/
			$this->SetBrowseParameters();
			$this->SetChildRecords([]);
			
			$this->SetEntryChildRecordStats([]);
			$this->SetEntryAssociatedRecordStats([]);
			
			$this->SetSimpleChildAssociationRecords();
			$this->SetChildRecordsOfChildren();
			
			$this->ExtendedSetChildRecordsAssociated();
			
			$this->SetTagCounts();
			$this->SetSocialMediaBasics();
			
			$this->FormatEventDates();
			$this->FormatEntryInformation();
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
							// Specialized Browse Functionality
							// ---------------------------------------------
		
		public function browseByTag() {
			$this->SetORMBasics();
			$this->SetRecordTree();
			
			if(count($this->record_list) !== 0) {
				$this->redirect_script = 'view';
				$this->redirect_action = 'browseByTag';
				$this->redirect_base = '/';
				$this->redirect_query = '&tag=' . $this->Param('tag');
				
				return FALSE;
			}
			$this->SetTagParameters();
			//SetChildRecordCount
			$this->SetEntryRecordCount();
			$this->SetBrowseParameters();
			$this->SetChildRecords(['noassignment'=>TRUE]);
			$this->children = $this->GetEntriesParents(['entries'=>$this->children]);
		//	print_r($this->children);
		//	$this->SetEntryRecords([]);
		//	$this->children = $this->entries;	// yargh
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			$this->SetSimpleChildAssociationRecords();
			$this->SetChildRecordsOfChildren();
			$this->SetSocialMediaBasics();
			
			$this->SetTagCounts();
			
			$this->SetEntryChildRecordStats([]);
			$this->SetEntryAssociatedRecordStats([]);
			
			$this->FormatEntryInformation();
			$this->FormatErrors();
			
			return TRUE;
		}
		
							// Browse Helper Functionality
							// ---------------------------------------------
		
		public function SetTagParameters() {
			$this->tag = $this->Param('tag');
			$this->tag_cleansed = $this->CleanseForDisplay($this->tag);
			return $this->where = [
				'sql'=>'JOIN Tag ON Tag.Entryid = Entry1.id AND Tag.Tag = ? ',
				'bind'=>'s',
				'value'=>[$this->tag],
			];
		}
		
		public function SetBrowseParameters() {
			$this->SetBrowseParameters_PageAndPerPage();
			$this->SetBrowseParameters_TotalPages();
			$this->SetBrowseParameters_RemainingPages();
			return TRUE;
		}
		
		public function SetBrowseParameters_PageAndPerPage() {
			$this->page = (int)$this->Param('page');
			$possible_per_page = $this->Param('perpage');
			if($possible_per_page == 'custom') {
				$this->custom_per_page_selected = true;
				$possible_per_page = $this->Param('CustomPerPage');
			}
			$this->perpage = (int)$possible_per_page;
			
			if($this->page < 1) {
				$this->page = 1;
			}
			
			if($this->perpage < 0) {
				$this->perpage = $this->browse_DefaultPerPage();
			}
			
			if($this->perpage < $this->browse_MinPerPage()) {
				$this->perpage = $this->browse_DefaultPerPage();
			} elseif($this->perpage > $this->browse_MaxPerPage()) {
				$this->perpage = $this->browse_MaxPerPage();
			}
			
			$child_record_start_index = ($this->page - 1) * $this->perpage + 1;
			if($child_record_start_index > $this->entry_count) {
				$this->page = 1;
				$this->perpage = $this->browse_DefaultPerPage();
				$child_record_start_index = 1;
			}
			$child_record_end_index = $child_record_start_index + $this->perpage - 1;
			
			if($child_record_end_index > $this->entry_count) {
				$child_record_end_index = $this->entry_count;
			}
			
			$this->child_record_start_index = $child_record_start_index;
			$this->child_record_end_index = $child_record_end_index;
			
			return TRUE;
		}
		
		public function SetBrowseParameters_TotalPages() {
			$this->total_pages = (int) ceil($this->entry_count / $this->perpage);
			
			return TRUE;
		}
		
		public function SetBrowseParameters_RemainingPages() {
			$this->total_children_viewed = $this->perpage * ($this->page - 1);
			$this->total_children_left = $this->entry_count - $this->total_children_viewed - ($this->child_record_end_index - $this->child_record_start_index + 1);
			
			return TRUE;
		}
		
		public function maxTextLength() {
			return 10000;
		}
		
		public function maxChildren() {
			return 10;
		}
		
		public function maxAssociated() {
			return 10;
		}
		
		public function browse_DefaultPerPage() {
			return 30;
		}
		
		public function browse_MinPerPage() {
			return 1;
		}
		
		public function browse_MaxPerPage() {
			if($this->isUserAdmin()) {
				ini_set('memory_limit','500M');
				return 10000;	# sometimes, in life, it pays to be a tough sonovabitch ~ bukowski
			}
			
			return 200;
		}
		
						// Browse Associated Functionality
						// ---------------------------------------------
			
							// Main Browse Functionality
							// ---------------------------------------------
		
		public function browseAssociated() {
			$this->SetORMBasics();
			$this->SetRecordTree();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			$this->SetChildRecordCount();
			$this->entry_count = $this->children_count;
			
			$this->SetBrowseParameters();
			
			$this->SetChildRecords([]);
			$this->SetEntryChildRecordStats([]);
			$this->SetEntryAssociatedRecordStats([]);
			
			$this->children = $this->orm->GetEntriesParents([
				'entries'=>$this->children,
			]);
			
			$this->SetSimpleChildAssociationRecords();
			$this->SetExtendedChildAssociationRecords();
			$this->SetChildRecordsOfChildren();
			
			$this->SetTagCounts();
			$this->SetSocialMediaBasics();
			
			$this->FormatEventDates();
			$this->FormatEntryInformation();
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
						// View Functionality
						// ---------------------------------------------
		
		public function display() {
			$this->SetORMBasics();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			$this->FormatEntryInformation();
			
			if(!$this->canUserAccess()) {
				return FALSE;
			}
			
			$this->FormatText();
			$this->FormatEventDates();
			
			$this->SaveComments();
			$this->SetComments();
			
			$this->SetChildRecordCount();
			
			if($this->children_count > 400) {
				$this->desired_action = 'index';	// you don't want this page
				return $this->index();
			}
			
			$this->SetChildRecords([]);
			$this->SetEntryChildRecordStats([]);
			$this->SetEntryAssociatedRecordStats([]);
			
		#	if($this->entry && $this->entry['associated'] && count($this->entry['associated']) < $this->maxAssociated() + 1) {
				$this->SetSimpleChildAssociationRecords();
				$this->SetAssociationRecords();
		#	}
			$this->SetChildRecordsOfChildren();
			
			$this->SetLikeDislikeRecords();
			$this->HandleMainPage();
			
			$this->CompactDefinitions();
			$this->SetTagCounts();
			$this->SetSocialMediaBasics();
			$this->SetSiblings([]);
			
			$this->CountRecords();
			$this->FixDates();
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
		public function FixDates() {
			if($this->entry['eventdate']) {
				$event_dates = $this->entry['eventdate'];
				
				if($this->counts['eventdate']) {
					for($i = 0; $i < $this->counts['eventdate']; $i++) {
						$event_date = $event_dates[$i];
						
						$event_date_time_pieces = explode('-', $event_date['EventDateTime']);
						
						$year = (int)$event_date_time_pieces[0];
						
						if($year >= 3000) {
							$diff = $year - 3000;
							$real_year = 1000 - $diff;
						} else {
							$real_year = $year;
						}
						
						$event_date_time_pieces[0] = $real_year;
						$new_time = implode('-', $event_date_time_pieces);
						$event_date['EventDateTime'] = $new_time;
						
						$event_dates[$i] = $event_date;
						
		#				print("<!-- BT: DATE! ");
		#				print_r($event_date);
		#				print("-->");
					}
				#	print("<!-- BT: event dates exist! go! -->");
				}
			}
		}
		
		public function CountRecords() {
				# BT: REDO!  VARIABLE-IZE!
			if($this->children) {
				$children_count = count($this->children);
			} else {
				$children_count = 0;
			}
			
			if($this->younger_siblings) {
				$younger_sibling_count = count($this->younger_siblings);
			} else {
				$younger_sibling_count = 0;
			}
			
			if($this->older_siblings) {
				$older_sibling_count = count($this->older_siblings);
			} else {
				$older_sibling_count = 0;
			}
			
			if($this->entry['image']) {
				$image_count = count($this->entry['image']);
			} else {
				$image_count = 0;
			}
			
			if($this->entry['description']) {
				$description_count = count($this->entry['description']);
			} else {
				$description_count = 0;
			}
			
			if($this->entry['quote']) {
				$quote_count = count($this->entry['quote']);
			} else {
				$quote_count = 0;
			}
			
			if($this->entry['tag']) {
				$tag_count = count($this->entry['tag']);
			} else {
				$tag_count = 0;
			}
			
			if($this->entry['textbody']) {
				$textbody_count = count($this->entry['textbody']);
			} else {
				$textbody_count = 0;
			}
			
			if($this->entry['associated']) {
				$associated_count = count($this->entry['associated']);
			} else {
				$associated_count = 0;
			}
			
			if($this->entry['association']) {
				$association_count = count($this->entry['association']);
			} else {
				$association_count = 0;
			}
			
			if($this->entry['eventdate']) {
				$eventdate_count = count($this->entry['eventdate']);
			} else {
				$eventdate_count = 0;
			}
			
			if($this->entry['definition']) {
				$definition_count = count($this->entry['definition']);
			} else {
				$definition_count = 0;
			}
			
			if($this->entry['link']) {
				$link_count = count($this->entry['link']);
			} else {
				$link_count = 0;
			}
			
			if($this->comments) {
				$comments_count = count($this->comments);
			} else {
				$comments_count = 0;
			}
			
			$this->counts = [
				'image'=>$image_count,
				'tag'=>$tag_count,
				'description'=>$description_count,
				'quote'=>$quote_count,
				'textbody'=>$textbody_count,
				'associated'=>$associated_count,
				'association'=>$association_count,
				'eventdate'=>$eventdate_count,
				'definition'=>$definition_count,
				'link'=>$link_count,
				
				'children'=>$children_count,
				
				'comment'=>$comments_count,
				
				'younger_sibling'=>$younger_sibling_count,
				'older_sibling'=>$older_sibling_count,
			];
			
			return TRUE;
		}
		
		public function FormatText() {
			if($this->entry['textbody'] && $this->entry['textbody'][0] && $this->entry['textbody'][0]['id'] && $this->entry['textbody'][0]['Text']) {
				$text = $this->formatImageText([
					'text'=>$this->entry['textbody'][0]['Text'],
					'images'=>$this->entry['image'],
				]);
				$this->entry['textbody'][0]['Text'] = $text;
			}
			return TRUE;
		}
		
		public function FormatEventDates() {
			if($this->parent['eventdate'] && $this->parent['eventdate'][0]) {
				for($i = 0; $i < count($this->parent['eventdate']); $i++) {
					$event_date = $this->parent['eventdate'][$i];
					
					$date_time_pieces = explode(' ', $event_date['EventDateTime']);
					$date = $date_time_pieces[0];
					$time = $date_time_pieces[1];
					
					$event_date['date'] = $date;
					$event_date['time'] = $time;
					
					$this->parent['eventdate'][$i] = $event_date;
				}
			}
			
			if($this->entry['eventdate'] && $this->entry['eventdate'][0]) {
				for($i = 0; $i < count($this->entry['eventdate']); $i++) {
					$event_date = $this->entry['eventdate'][$i];
					
					$date_time_pieces = explode(' ', $event_date['EventDateTime']);
					$date = $date_time_pieces[0];
					$time = $date_time_pieces[1];
					
					$event_date['date'] = $date;
					$event_date['time'] = $time;
					
					$this->entry['eventdate'][$i] = $event_date;
				}
			}
			
			if($this->children && count($this->children)) {
				for($i = 0; $i < count($this->children); $i++) {
					$child = $this->children[$i];
					
					if($child['eventdate']) {
						for($j = 0; $j < count($child['eventdate']); $j++) {
							$event_date = $child['eventdate'][$j];
							
							$date_time_pieces = explode(' ', $event_date['EventDateTime']);
							$date = $date_time_pieces[0];
							$time = $date_time_pieces[1];
							
							$event_date['date'] = $date;
							$event_date['time'] = $time;
							
							$this->children[$i]['eventdate'][$j] = $event_date;
						}
					}
				}
			}
#			$this->entry['eventdate'][0]['test'] = 'soybean';
			return TRUE;
		}
		
		public function formatImageText($args) {
			$args['text'] = $this->formatImageText_fulls($args);
			$args['text'] = $this->formatImageText_icons($args);
			
			return $args['text'];
		}
		
		public function formatImageText_icons($args) {
			$text = $args['text'];
			$images = $args['images'];
			
			if ($this->script_format_lower == 'pdf') {
				$text = preg_replace('/Image::(\d+)/', '', $text); 
				return $text;
			}
			
			$dom = preg_split('/Image::(\d+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
			$dom_length = count($dom);
			
			if($dom_length > 1) {
				$orientation = 'right';
				$mobile_friendly = $this->Param('mobilefriendly');
				
				$max_image_height = 400;
				$max_image_width = 500;
				
				for($i = 1; $i < $dom_length; $i += 2) {
					$dom_piece = $dom[$i];
					$number = (int)$dom_piece;
					$image = $images[$number - 1];
					
					if($mobile_friendly || !$image) {
						$dom[$i] = '';
					} else {
						$real_height = 0;
						$real_width = 0;
						$perceived_width = (int)$image['PixelWidth'];
						
						if((int)$image['PixelHeight'] > (int)$image['PixelWidth'] && (int)$image['PixelHeight'] > $max_image_height) {
							$real_height = $max_image_height;
							$perceived_width = ceil((int)$image['PixelWidth'] * ($max_image_height / (int)$image['PixelHeight']));
						} elseif((int)$image['PixelWidth'] > $max_image_width) {
							$real_width = $max_image_width;
							$perceived_width = $real_width;
						}
						
						$perceived_width -= 5;
						
						$image_code = '';
						
						$image_code .= '<div class="document-image-holder document-image-holder-';
						$image_code .= $orientation;
						$image_code .= '" ';
						$image_code .= 'title="';
						$image_code .= $image['Title'];
						$image_code .= ' ';
						$image_code .= ' - (Click to View Full Image)';
						$image_code .= '" ';
						$image_code .= '>';
						
						$image_directory = '/image/' . implode('/', str_split($image['FileDirectory'])) . '/';
						
						$image_code .= '<a href="';
						$image_code .= $image_directory;
						$image_code .= $image['FileName'];
						$image_code .= '" target="_blank">';
						
						$image_code .= '<img ';
						$image_code .= 'class="document-image" ';
						$image_code .= 'src="';
						$image_code .= $image_directory;
						$image_code .= $image['StandardFileName'];
						$image_code .= '" ';
						
						$image_code .= 'alt=" ';
						$image_code .= $image['Title'];
						$image_code .= '" ';
						
						$image_code .= 'style="margin:0px;" ';
						
						if($real_height > 0) {
							$image_code .= 'height="' . $real_height . '" ';
						} elseif($real_width > 0) {
							$image_code .= 'width="' . $real_width . '" ';
						}
						
						$image_code .= '>';
						
						$image_code .= '</a>';
						
						$image_code .= '<p ';
						$image_code .= 'class="margin-2px font-family-arial font-size-75percent horizontal-center" ';
						$image_code .= 'style="';
						$image_code .= 'max-width:' . $perceived_width . 'px;';
						$image_code .= 'font-size:12px;';
						$image_code .= '">';
						
						$image_title = $image['Title'];
						$image_title = str_replace(', CC ', ',<BR>CC ', $image_title);
						$image_code .= $image_title;
						$image_code .= '</p>';
						
						$image_code .= '</div>';
						
						$new_dom_piece = $image_code;
						$dom[$i] = $new_dom_piece;
						
						if($orientation == 'left') {
							$orientation = 'right';
						} else {
							$orientation = 'left';
						}
					}
				}
				$text = implode('', $dom);
			}
			
			return $text;
		}
		public function formatImageText_fulls($args) {
			$text = $args['text'];
			$images = $args['images'];
			
			if ($this->script_format_lower == 'pdf') {
				$text = preg_replace('/FullImage::(\d+)/', '', $text); 
				return $text;
			}
			
			$dom = preg_split('/FullImage::(\d+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
			$dom_length = count($dom);
			
			if($dom_length > 1) {
				$mobile_friendly = $this->Param('mobilefriendly');
				
				for($i = 1; $i < $dom_length; $i += 2) {
					$dom_piece = $dom[$i];
					$number = (int)$dom_piece;
					$image = $images[$number - 1];
					
					if($mobile_friendly || !$image) {
						$dom[$i] = '';
					} else {
						
						if((int)$image['PixelHeight'] > (int)$image['PixelWidth'] && (int)$image['PixelHeight'] > $max_image_height) {
							$real_height = $max_image_height;
						} elseif((int)$image['PixelWidth'] > $max_image_width) {
							$real_width = $max_image_width;
						}
						$real_height = $image['StandardPixelHeight'];
						$real_width = $image['StandardPixelWidth'];
						
						$image_code = '';
						
						$image_code .= '<center>';
						$image_code .= '<div ';
						$image_code .= 'title="';
						$image_code .= $image['Title'];
						$image_code .= ' ';
						$image_code .= ' - (Click to View Full Image)';
						$image_code .= '" ';
						$image_code .= '>';
						
						$image_directory = '/image/' . implode('/', str_split($image['FileDirectory'])) . '/';
						
						$image_code .= '<a href="';
						$image_code .= $image_directory;
						$image_code .= $image['FileName'];
						$image_code .= '" target="_blank">';
						
						$image_code .= '<img ';
						$image_code .= 'class="document-image" ';
						$image_code .= 'src="';
						$image_code .= $image_directory;
						$image_code .= $image['StandardFileName'];
						$image_code .= '" ';
						
						$image_code .= 'alt=" ';
						$image_code .= $image['Title'];
						$image_code .= '" ';
						
						$image_code .= 'style="margin:0px;" ';
						
						if($real_height > 0) {
							$image_code .= 'height="' . $real_height . '" ';
						} elseif($real_width > 0) {
							$image_code .= 'width="' . $real_width . '" ';
						}
						
						$image_code .= '>';
						
						$image_code .= '</a>';
						
						$image_code .= '</div>';
						$image_code .= '</center>';
						
						$new_dom_piece = $image_code;
						$dom[$i] = $new_dom_piece;
					}
				}
				$text = implode('', $dom);
			}
			
			return $text;
		}
		
		public function HandleMainPage() {
			if($this->IsMainPage()) {
				$this->SetGrandChildAssociationRecords();
				$this->SetGrandChildRecordsOfChildren();
				$this->SetNewestChildren();
			}
			
			return TRUE;
		}
		
		public function SetNewestChildren() {
			$get_record_where = [
				'type'=>'Entry',
				'limit'=>'10',
				'orderby'=>'Entry.OriginalCreationDate DESC',
				'definition'=>[
					'Publish'=>1,
				],
			];
			$newest_entries = $this->handler->db_access->GetRecords($get_record_where);
			$newest_entries = $this->GetEntriesParents(['entries'=>$newest_entries]);
			
			$this->newest_entries = $newest_entries;
			
			return TRUE;
		}
		
		public function IsMainPage() {
			if(count($this->record_list) < 1) {
				return TRUE;
			}
			
			return FALSE;
		}
		
		public function display_wordweight() {
			if(count($this->record_list) > 1) {
				return FALSE;
			}
			
			$this->SetORMBasics();
			
			if(count($this->object_list)) {
				$this->word = $this->object_code;
				$this->definitions = $this->dictionary->LookupWords(['words'=>[$this->word]])[strtolower($this->word)];
				
				$this->definition_count = count($this->definitions);
				
				if(!$this->definition_count) {
					return FALSE;
				}
			}
			
			$this->SetSocialMediaBasics();
			
			$this->search_term = $this->Param('search');
			
			if($this->search_term) {
				$this->definitions = $this->dictionary->LookupWords(['words'=>[$this->search_term]])[strtolower($this->search_term)];
				
				$this->definition_count = count($this->definitions);
				
				if($this->definition_count) {
					return header('Location: ' . $this->handler->domain->GetPrimaryDomain(['lowercase'=>1, 'www'=>1]) . '/' . $this->search_term . '/view.php');
				}
			}
			
			return TRUE;
		}
		
						// Index Functionality
						// ---------------------------------------------
		
		public function index() {
			if(!$this->orm) {
				$this->SetORMBasics();
			#	$this->SetRecordTree();		# why not?
			}
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			$this->FormatEntryInformation();
			
			if(!$this->canUserAccess()) {
				return FALSE;
			}
			
			$this->SetIndexChildRecords([]);
			$this->SetChildRecordsOfChildren();
			$this->where = [];
			$this->SetChildRecordCount();
			$this->SetEntryChildRecordStats([]);
			$this->SetEntryAssociatedRecordStats([]);
			
			$this->SetChildAssociationRecords();
			
			if($this->globals->IndexPullChildRecordStats()) {
				$this->setChildRecordStatsOfChildren();
	#			print('soyo-beano');
			}
			
			$this->SetTagCounts();
			$this->SetSocialMediaBasics();
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
						// Dictionary Functionality
						// ---------------------------------------------
		
		public function dictionary() {
			$this->SetORMBasics();
			$this->SetRecordTree();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			ggreq('classes/Database/ORMDictionary.php');
			$this->dictionary = new ORMDictionary(['dbaccess'=>$this->handler->db_access]);
			$entry_dictionary = $this->dictionary->GetDictionary(['entry'=>$this->entry]);
			$entry_dictionary_count = count($entry_dictionary);
			
			$defined_words = [];
			
			$cache_file_location = 'data/dictionary/' . $this->handler->domain->host . '/' . $this->entry['id'] . '.json';
			if($this->handler->authentication->CheckAuthenticationForCurrentObject_IsAdmin() && $this->Param('godmode')) {
				ini_set('memory_limit','500M');		// God says "Move aside, little ones."
				for($i = 0; $i < $entry_dictionary_count; $i++) {
					$entry_definition = $entry_dictionary[$i];
					
					$term = $entry_definition['Term'];
					
					if(!$defined_words[$term]) {
						$defined_words[$term] = [];
					}
					
					$publication_date = FALSE;
					
					if($entry_definition['EventDate2_EventDateTime']) {
						$date_time_pieces = explode(' ', $entry_definition['EventDate2_EventDateTime']);
						$date_pieces = explode('-', $date_time_pieces[0]);
						
						if($date_pieces[0] && $date_pieces[0] != '0000') {
							$full_date = $date_pieces[0];
							
							if($date_pieces[1] && $date_pieces[2] && $date_pieces[1] != '00' && $date_pieces[2] != '00') {
								$full_date .= '-' . $date_pieces[1] . '-' . $date_pieces[2];
							}
							$publication_date = $full_date;
						}
					}
					
					$defined_words[$term][] = [
						'Definition'=>$entry_definition['Definition'],
						'Author'=>$entry_definition['Title'],
						'AuthorCode'=>$entry_definition['Code'],
						'Source'=>$entry_definition['Entry2_Title'],
						'SourcePermaid'=>$entry_definition['Assignment1_id'],
						'PublicationDate'=>$publication_date,
					];
				}
				
				$this->natksort($defined_words);
				
				$file_handle_for_source = fopen($cache_file_location, 'w+');
				fwrite($file_handle_for_source, json_encode($defined_words));
				fclose($file_handle_for_source);
			} else {
				$defined_words = json_decode(file_get_contents($cache_file_location), TRUE);
#				print_r($defined_words);
			}
			$this->entrydictionary = $defined_words;
		#	print_r();
			
			return TRUE;
		}
				
		function natksort(&$array) {
			$keys = array_keys($array);
			natcasesort($keys);
			
			foreach ($keys as $k) {
				$new_array[$k] = $array[$k];
			}
			
			$array = $new_array;
			return true;
		}
		
						// Definitions Functionality
						// ---------------------------------------------
		
		public function definitions() {
			$this->SetORMBasics();
			$this->SetRecordTree();
			
			if(!$this->ValidateOrm()) {
				return FALSE;	# 404
			}
			
			$this->SetChildRecords(['alltext'=>TRUE]);
			$this->SetAssociationRecords();
			
			ggreq('classes/Language/Grammar.php');
			$this->grammar = new Grammar();
			
			ggreq('classes/Language/TextCleanup.php');
			$this->textcleanup = new TextCleanup(['grammar'=>$this->grammar]);
			
			ggreq('classes/Language/Definition.php');
			$this->definition = new Definition(['grammar'=>$this->grammar, 'textcleanup'=>$this->textcleanup]);
			
			$text = '';
			
			if($this->entry['textbody'] && $this->entry['textbody'][0] && $this->entry['textbody'][0]['id'] && $this->entry['textbody'][0]['Text']) {
				$text = $this->entry['textbody'][0]['Text'];
			} else {
				if($this->children && count($this->children)) {
					$child_count = count($this->children);
					for($i = 0; $i < $child_count; $i++) {
						$child = $this->children[$i];
						if($child['textbody'] && $child['textbody'][0] && $child['textbody'][0]['id'] && $child['textbody'][0]['Text']) {
							$text .= $child['textbody'][0]['Text'];
							
							if($i + 1 < $child_count) {
								$text .= ' ';
							}
						}
					}
				}
			}
			
			$text = trim(html_entity_decode(strip_tags($text)));
			
			$this->definitions_found = $this->definition->GetDefinitions(['text'=>$text]);
			
			$this->SetChildRecords([]);
			
			$this->FormatErrors();
			
			return TRUE;
		}
		
						// Load Like/Dislike Functionality
						// ---------------------------------------------
		
		public function SetLikeDislikeRecords() {
			$likesdislikes_counts = $this->GetEntryLikesDislikesCount([]);
			
			$this->likes_count = $likesdislikes_counts['likes'];
			$this->dislikes_count = $likesdislikes_counts['dislikes'];
			
			$user_id = $this->handler->authentication->user_session['User.id'];
			
			if($user_id) {
				$user_where = [
					'type'=>'LikeDislike',
					'definition'=>[
						'Userid'=>$user_id,
						'Entryid'=>$this->entry['id'],
					],
				];
				
				$this->user_likedislike = $this->handler->db_access->GetRecords($user_where)[0];
			}
			
			return TRUE;
		}
		
						// JS Like/Dislike Functionality
						// ---------------------------------------------
		
			// BT: Here
		
		public function upvote() {
			$this->SetUserAndEntry();
			$likedislike = $this->GetUserLike([]);
			$likedislike = $this->SetUserLike(['likedislike'=>$likedislike, 'liked'=>1]);
			
			return $this->rpc_results = [
				'Success'=>1,
			];
		}
		
		public function undoupvote() {
			$this->SetUserAndEntry();
			$likedislike = $this->GetUserLike([]);
			$this->RemoveUserVote(['likedislike'=>$likedislike]);
			
			return $this->rpc_results = [
				'Success'=>1,
			];
		}
		
		public function downvote($args) {
			$this->SetUserAndEntry($args);
			$likedislike = $this->GetUserLike([]);
			$likedislike = $this->SetUserLike(['likedislike'=>$likedislike, 'liked'=>0]);
			
			return $this->rpc_results = [
				'Success'=>1,
			];
		}
		
		public function undodownvote($args) {
			$this->SetUserAndEntry($args);
			$likedislike = $this->GetUserLike([]);
			$this->RemoveUserVote(['likedislike'=>$likedislike]);
			
			return $this->rpc_results = [
				'Success'=>1,
			];
		}
		
		public function SetUserAndEntry() {
			$user_id = $this->handler->authentication->user_session['User.id'];
			
			if(!$user_id) {
				return FALSE;
			}
			
			$this->user_id = $user_id;
			
			if(!$this->orm) {
				$this->SetORMBasics();
			}
			
			if(!$this->entry || !$this->entry['id']) {
				return FALSE;
			}
			
			return TRUE;
		}
		
		public function GetUserLike($args) {
			$user_id = $this->user_id;
			
			$user_upvote_args = [
				'type'=>'LikeDislike',
				'definition'=>[
					'Userid'=>$user_id,
					'Entryid'=>$this->entry['id'],
				],
			];
			
			$user_vote = $this->handler->db_access->GetRecords($user_upvote_args);
			
			return $user_vote[0];
		}
		
		public function SetUserLike($args) {
			$user_id = $this->user_id;
			$likedislike = $args['likedislike'];
			$liked = $args['liked'];
			
			if($likedislike) {
				if($likedislike['LikeOrDislike'] != $liked) {
					$likedislike['LikeOrDislike'] = $liked;
					
					$likedislike_update_args = [
						'type'=>'LikeDislike',
						'update'=>[
							'LikeOrDislike'=>$liked,
						],
						'where'=>[
							'id'=>$likedislike['id'],
						],
					];
					
					$likedislike = $this->handler->db_access->UpdateRecord($likedislike_update_args)[0];
				}
			} else {
				$likedislike_update_args = [
					'type'=>'LikeDislike',
					'definition'=>[
						'Userid'=>$user_id,
						'Entryid'=>$this->entry['id'],
						'LikeOrDislike'=>$liked,
					],
				];
				
				$likedislike = $this->handler->db_access->CreateRecord($likedislike_update_args)[0];
			}
			
			return $likedislike;
		}
		
		public function RemoveUserVote($args) {
			$likedislike = $args['likedislike'];
			
			$likedislike_delete_args = [
				'type'=>'LikeDislike',
				'wherevalues'=>[$likedislike['id']],
				'where'=>'id = ?',
				'sqlbindstring'=>'i',
			];
			
			return $this->handler->db_access->DeleteRecords($likedislike_delete_args);
		}
	}

?>