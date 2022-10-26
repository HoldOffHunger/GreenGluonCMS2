<?php

	class module_hideable extends module_spacing
	{
		public $domain_object;
		public $secure;
		public $primary_domain;
		
		public function display ($args) {
			$table_args = $args;
			
			$this->domain_object = $args['options']['domainobject'];
			$this->secure = $args['options']['secure'];
			
			$get_primary_domain_args = [
				'www'=>1,
				'secure'=>$this->domain_object->secure,
			];
			
			$this->primary_domain = $this->domain_object->GetPrimaryDomain($get_primary_domain_args);
			
			if(!isset($table_args['level'])) {
				$table_args['level'] = 1;
			}
			
			$this->display_tablestart($table_args);
			
			$this->display_rows($table_args);
			
			$this->display_tableend($table_args);
		}
		
		public function display_tablestart ($args) {
			$level = $args['level'];
			
			print('<div class="margin-2px">');
			$this->DisplayDoubleLineBreak();
			print('<ol class="');
			
			if($level) {
				print('list-level-' . $level . '');
			}
			
			print(' noselect padding-0px margin-0px">');
			$this->DisplayDoubleLineBreak();
		}
		
		public function display_tableend ($args) {
			print('</ol>');
			$this->DisplayDoubleLineBreak();
			print('</div>');
			$this->DisplayDoubleLineBreak();
		}
		
		public function display_rows ($args) {
			if($args) {
				$list = $args['list'];
				$level = $args['level'];
				
				foreach ($list as $listkey => $listoption) {
					$text = $listoption['text'];
					$link = $listoption['link'];
					$mouseover = $listoption['mouseover'];
					
					$row_args = array(
						'title'=>$text,
						'link'=>$link,
						'mouseover'=>$mouseover,
						'level'=>$level,
						'arrow'=>is_array($listoption[0]),
					);
					
					$this->display_row_start($row_args);
					
					if(is_array($listoption[0])) {
						unset($listoption['text']);
						unset($listoption['link']);
						unset($listoption['mouseover']);
						
						$next_level = $level + 1;
						
						$display_row_args = [
							'level'=>$level,
						];
						
						$tablestart_args = [
							'level'=>$next_level,
						];
						
						$this->display_tablestart($tablestart_args);
						
						$hideable_args = [
							'list'=>$listoption,
							'level'=>$next_level,
						];
						$this->display_rows($hideable_args);
						
						$tableend_args = [];
						
						$this->display_tableend($tableend_args);
					}
					
					$this->display_row_end($row_args);
				}
			}
		}
		
		public function display_row_start ($args) {
			$title = $args['title'];
			$link = $args['link'];
			$mouseover = $args['mouseover'];
			$level = $args['level'];
			$arrow = $args['arrow'];
			
			if(strlen($link)) {
				
				$link_start_code = '<a href="' . $link . '" class="list-item-link-level-' . $level .'">';
				$link_end_code = '</a>';
			} else {
				$link_start_code = '';
				$link_end_code = '';
			}
			
			if($arrow) {
				$arrow_code = '<span class="list-item-arrow-level-' . $level . ' noselect"><img src="' . $this->primary_domain . '/image/arrow.gif' . '" class="list-item-arrow-image-level-' . $level . ' noselect"></span>';
			} else {
				$arrow_code = '<span class="list-item-noarrow-level-' . $level . ' noselect"><img src="' . $this->primary_domain . '/image/bullet-hole.gif' . '" class="list-item-noarrow-image-level-' . $level . ' noselect"></span>';
			}
			
			print(	$tr_tabs . '<li class="list-item-row-level-' . $level . ' noselect" title="' . $mouseover .'">' );
			$this->DisplayDoubleLineBreak();
			print(	$line_tabs . $arrow_code . $link_start_code . '<span class="list-item-row-text-level-' . $level . ' noselect">' . 
				$title . '</span>' . $link_end_code
			);
			$this->DisplayDoubleLineBreak();
		}
		
		public function display_row_end ($args) {
			$link = $args['link'];
			
			if(strlen($link)) {
				$link_start_code = '<a href="' . $this->primary_domain . $link . '" class="list-item-link-level-' . $level .'">';
				$link_end_code = '</a>';
			} else {
				$link_start_code = '';
				$link_end_code = '';
			}
			
			print($tr_tabs . '</li>');
			$this->DisplayDoubleLineBreak();
		}
	}
?>