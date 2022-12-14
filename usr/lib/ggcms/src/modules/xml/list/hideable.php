<?php

	class module_hideable extends module_spacing {
		public function display ($args) {
			$table_args = $args;
			
			if(!isset($table_args['level'])) {
				$table_args['level'] = 1;
			}
			
			$this->display_tablestart($table_args);
			
			$this->display_rows($table_args);
			
			$this->display_tableend($table_args);
		}
		
		public function display_tablestart ($args) {
			$level = $args['level'];
			
			print('<ol');
			
			if($level) {
				print(' class="list-level-' . $level . '"');
			}
			
			print('>');
			print("\n\n");
		}
		
		public function display_tableend ($args) {
			print('</ol>');
			print("\n\n");
		}
		
		public function display_rows ($args) {
			if($args) {
				$list = $args['list'];
				$level = $args['level'];
				
				foreach ($list as $listkey => $listoption) {
					$text = $listoption['text'];
					$link = $listoption['link'];
					$mouseover = $listoption['mouseover'];
					
					$row_args = [
						'title'=>$text,
						'link'=>$link,
						'mouseover'=>$mouseover,
						'level'=>$level,
						'indentation'=>$indentation,
					];
					
					$this->display_row_start($row_args);
					
					if(is_array($listoption[0])) {
						unset($listoption['text']);
						unset($listoption['link']);
						unset($listoption['mouseover']);
						$next_indentation = $indentation + 1;
						$after_next_indentation = $indentation + 2;
						
						$next_level = $level + 1;
						
						$display_row_args = [
							'level'=>$level,
							'indentation'=>$indentation,
						];
						
						$tablestart_args = [
							'level'=>$next_level,
							'indentation'=>$next_indentation,
						];
						
						$this->display_tablestart($tablestart_args);
						
						$hideable_args = [
							'list'=>$listoption,
							'level'=>$next_level,
							'indentation'=>$after_next_indentation,
						];
						$this->display_rows($hideable_args);
						
						$tableend_args = [
							'indentation'=>$next_indentation,
						];
						
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
			
			if(strlen($link)) {
				$link_start_code = '<a href="' . $link . '" class="list-item-link-level-' . $level .'">';
				$link_end_code = '</a>';
			} else {
				$link_start_code = '';
				$link_end_code = '';
			}
			
			print( '<li class="list-item-row-level-' . $level . '" title="' . $mouseover .'">' . "\n\n" .
				$link_start_code . '<span class="list-item-row-text-level-' . $level . '">' . 
				$title . '</span>' . $link_end_code . "\n\n"
			);
		}
		
		public function display_row_end ($args) {
			$link = $args['link'];
			
			if(strlen($link)) {
				$link_start_code = '<a href="' . $link . '" class="list-item-link-level-' . $level .'">';
				$link_end_code = '</a>';
			} else {
				$link_start_code = '';
				$link_end_code = '';
			}
			
			print('</li>' . "\n\n");
		}
	}
?>