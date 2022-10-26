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
			
			$log_string = $this->RecordUserTracking_getLogString();
			$filename = $this->RecordUserTracking_getLogFilename();
			
			return $this->RecordUserTracking_saveLog([
				'logstring'=>$log_string,
				'filename'=>$filename,
			]);
		}
		
		public function RecordUserTracking_getLogString() {
			$information_pieces = [
				date('o-M-d H:i:s', $this->handler->time->time),
				'[' . $this->handler->time->time . ']',
				$_SERVER['REMOTE_ADDR'],
				$_SERVER['REQUEST_URI'],
				'(' . $this->handler->language->GetLanguageCode() . ':' . $this->handler->language->GetLanguage() . ')',
			];
			
			return implode(' ', $information_pieces) . "\n";
		}
		
		public function RecordUserTracking_getLogFilename() {
			return $this->handler->domain->primary_domain_lowercased . '/stats/' . date('o-M', $this->handler->time->time) . '.txt';
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