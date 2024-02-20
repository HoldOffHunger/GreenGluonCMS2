<?php

	class UserTracking {
		public function __construct($args) {
			$this->handler = $args['handler'];
			
			return $this;
		}
		
		public function RecordUserTracking() {
			if($this->handler->script_format_lower !== 'html') {
				return FALSE;
			}
			
			if($this->handler->globals->EnableStats()) {
				$log_string = $this->RecordUserTracking_getLogString([]);
				$filename = $this->RecordUserTracking_getLogFilename();
				
				$this->RecordUserTracking_saveLog([
					'logstring'=>$log_string,
					'filename'=>$filename,
				]);
			}
			
			if($this->handler->globals->EnableStats_LogExcessiveMemoryUse()) {
				if($this->handler->globals->EnableStats_LogExcessiveMemoryUse_MaxSize() < memory_get_usage()) {
					$log_string = $this->RecordUserTracking_getLogString(['show_memory'=>TRUE]);
					$filename = $this->RecordUserTracking_getLogFilename_Memory();
					
					$this->RecordUserTracking_saveLog([
						'logstring'=>$log_string,
						'filename'=>$filename,
					]);
				}
			}
			
			return TRUE;
		}
		
		public function RecordUserTracking_getLogString($args) {
			$information_pieces = [
				date('o-M-d H:i:s', $this->handler->time->time),
				'[' . $this->handler->time->time . ']',
				$_SERVER['REMOTE_ADDR'],
				$_SERVER['REQUEST_URI'],
				'(' . $this->handler->language->GetLanguageCode() . ':' . $this->handler->language->GetLanguage() . ')',
			];
			
			if($args['show_memory'] || $this->handler->globals->EnableStats_LogMemoryUse()) {
				$information_pieces[] = memory_get_usage();
			}
			
			return implode(' ', $information_pieces) . PHP_EOL;
		}
		
		public function RecordUserTracking_getLogFilename() {
			return $this->handler->domain->primary_domain_lowercased . '/stats/' . date('o-M', $this->handler->time->time) . '.txt';
		}
		
		public function RecordUserTracking_getLogFilename_Memory() {
			return $this->handler->domain->primary_domain_lowercased . '/stats/' . date('o-M', $this->handler->time->time) . '_memory.txt';
		}
		
		public function RecordUserTracking_saveLog ($args) {
			$log_string = $args['logstring'];
			$filename = $args['filename'];
			
			$filename_handle = gglog($filename, 'a+');
			
			while (!flock($filename_handle, LOCK_EX)) {
				usleep(round(rand(0, 100)*1000)); //0-100 milliseconds
			}
			
			chmod($filename, 0755);
			fwrite($filename_handle, $log_string);
			
			flock($filename_handle, LOCK_UN);
			fclose($filename_handle);
			
			return TRUE;
		}
	}

?>