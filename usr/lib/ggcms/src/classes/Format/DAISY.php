<?php

	class DAISY extends AbstractBaseFormat {
		public function MimeType() {
			return 'text/html';
		}
		
			// Display DAISY
			// -----------------------------------------------
		
		public function Display() {
			if(!$this->RunScript()) {
				return FALSE;
			}
			
			$this->SetFileNameDisplay();
			$this->HandleHTTPHeaders();
			
			$source_file_location = $this->SetSourceFileLocation();
			$daisy_file_location = $this->SetOutputFileLocation();
			
			$daisy_input = $this->RunTemplates();
			
			#print("INPUT" . $daisy_input . "|");
			if(!$daisy_input) {
				return FALSE;
			}
			
			$old_daisy_input = $this->getLastScriptRun();
			
			#print_r(filesize($daisy_file_location));
			
			if($_GET['forceregen'] || !is_file($daisy_file_location) || filesize($daisy_file_location) === 0 || $old_daisy_input !== $daisy_input) {
				$daisy_output = $this->ConvertHTMLToFormat();
				
				$file_handle_for_output = fopen($daisy_file_location, 'w+');
				fwrite($file_handle_for_output, $daisy_output);
				fclose($file_handle_for_output);
				
				$file_handle_for_source = fopen($source_file_location, 'w+');
				fwrite($file_handle_for_source, $daisy_input);
				fclose($file_handle_for_source);
			}
			
			return readfile($daisy_file_location);
		}
		
		public function ConvertHTMLToFormat() {
			$daisy_output = $this->RunTemplates();
			
			$daisy_output = $this->ConvertHTMLToDaisy_AddLevelTags(['daisyinput'=>$daisy_output]);
			$daisy_output = $this->ConvertHTMLToDaisy_AddPageNumbers(['daisyinput'=>$daisy_output]);
			
			$this->ConvertHTMLToDaisy_GenerateTOC(['daisyinput'=>$daisy_output]);
			
			$daisy_page_toc = $this->daisy_pages_table_of_contents;
			$daisy_subject_toc = $this->daisy_subjects_table_of_contents;
			
			$daisy_output = $this->ConvertHTMLToDaisy_UniqueIdentifiers(['daisyinput'=>$daisy_output]);
			
			$daisy_output = $this->ConvertHTMLToDaisy_AddMetaData(['daisyinput'=>$daisy_output]);
			
			return $this->daisy_output = $daisy_output;
		}
		
		public function ConvertHTMLToDaisy_AddMetaData($args) {
			$daisy_output = $args['daisyinput'];
			
			$daisy_output =
				$this->ConvertHTMLToDaisy_AddMetaData_OpeningMetaHeader(['daisyinput'=>$daisy_output]) .
				$this->daisy_subjects_table_of_contents .
				$this->ConvertHTMLToDaisy_AddMetaData_TocSeparators(['daisyinput'=>$daisy_output]) .
				$this->daisy_pages_table_of_contents .
				$this->ConvertHTMLToDaisy_AddMetaData_BodySeparator(['daisyinput'=>$daisy_output]) .
				$daisy_output .
				$this->ConvertHTMLToDaisy_AddMetaData_DocumentEnder(['daisyinput'=>$daisy_output]) ;
			
			return $daisy_output;
		}
		
		public function SetTitle() {
			$title = '';
			
			if($this->script->record_to_use['Title']) {
				$title = $this->script->record_to_use['Title'];
			}
			
			if($this->script->record_to_use['Subtitle']) {
				if($title) {
					$title .= ' : ';
				}
				
				$title .= $this->script->record_to_use['Subtitle'];
			}
			
			return $this->title = $title;
		}
		
		public function ConvertHTMLToDaisy_AddMetaData_OpeningMetaHeader($args) {
			$opening_headers = '';
			
			$opening_headers .= '<?xml version=\"1.0\" encoding=\"UTF-8\" ?>' . "\n";
			$opening_headers .= '<!DOCTYPE dtbook PUBLIC "-//NISO//DTD dtbook 2005-1//EN\"' . "\n";
			$opening_headers .= '  "http://www.daisy.org/z3986/2005/dtbook-2005-1.dtd">' . "\n\n";
			
			$opening_headers .= '<package xmlns="http://openebook.org/namespaces/oeb-package/1.0/" unique-identifier="' . $this->domain_object->host . '_' . $this->daisy_filename . '">' . "\n\n";
			
			$opening_headers .= "\t" . '<metadata>' . "\n\n";
			
			$opening_headers .= "\t\t" . '<dc-metadata>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<dc:Title>';
			
			$title = $this->SetTitle();
			
			$opening_headers .= $title;
			$opening_headers .= '</dc:Title>' . "\n\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Creator>';
			
			$author_text = '';
			if($this->script->record_to_use['textbody']) {
				$textbody_count = count($this->script->record_to_use['textbody']);
				if($textbody_count) {
					$textbody = $this->script->record_to_use['textbody'][0];
					
					if($textbody['Source']) {
						$author_text .= 'From : ' . $textbody['Source'] . '. ';
					}
				}
			}
			
			$opening_headers .= $author_text;
			
			$opening_headers .= '</dc:Creator>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Subject>';
			
			$subject = $primary_host_record['Subject'];
			
			if($primary_host_record['NewsKeywords']) {
				if($subject) {
					$subject .= ', ';
				}
				
				$subject .= $primary_host_record['NewsKeywords'];
			}
			
			$opening_headers .= $subject;
			
			$opening_headers .= '</dc:Subject>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Description>';
			
			$description_text = '';
			if($this->script->record_to_use['description']) {
				$description_count = count($this->script->record_to_use['description']);
				if($description_count) {
					$description = $this->script->record_to_use['description'][0];
					
					$description_text .= $description['Description'];
					
					if($description['Source']) {
						$description_text .= ' (From : ' . $description['Source'] . '.)';
					}
				}
			}
			
			$opening_headers .= '</dc:Description>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Publisher>';
			
			$opening_headers .= $primary_host_record['Publisher'];
			
			$opening_headers .= '</dc:Publisher>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Date>';
			
			$opening_headers .= $this->script->record_to_use['OriginalCreationDate'];
			
			$opening_headers .= '</dc:Date>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Type>DAISY</dc:Type>' . "\n";
			$opening_headers .= "\t\t\t " . '<dc:Format>DAISY</dc:Format>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Identifier>';
			
			$opening_headers .= $this->domain_object->host;
			$opening_headers .= '_';
			$opening_headers .= $this->daisy_filename;
			
			$opening_headers .= '</dc:Identifier>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Source>';
			
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]);
			
			$opening_headers .= '</dc:Source>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Language>';
			
			$opening_headers .= 'English';
			
			$opening_headers .= '</dc:Language>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Relation>';
			
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]);
			
			$opening_headers .= '</dc:Relation>' . "\n";
			
			$opening_headers .= "\t\t\t " . '<dc:Coverage>Eternity</dc:Coverage>' . "\n\n";
			
			$opening_headers .= "\t\t" . '</dc-metadata>' . "\n\n";
			
			$opening_headers .= "\t\t" . '<x-metadata>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:sourceEdition" content="';
			
			if($this->script->record_to_use['description']) {
				$description_count = count($this->script->record_to_use['description']);
				if($description_count) {
					$description = $this->script->record_to_use['description'][0];
					$opening_headers .= $description['Language'];
				}
			}
			
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:sourcePublisher" content="';
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]);
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:sourceRights" content="';
			$opening_headers .= $primary_host_record['Rights'];
			$opening_headers .= '".' . "\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:sourceTitle" content="';
			$opening_headers .= $title;
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:multimediaType" content="text/html/daisy">' . "\n";
			$opening_headers .= "\t\t\t" . '<meta name="dtb:multimediaContent" content="text/html/daisy">' . "\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:narrator" content="';
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]);
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:producer" content="';
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]);
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t\t" . '<meta name="dtb:revision" content="';
			$opening_headers .= $title;
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '</x-metadata>' . "\n\n";
			
			$opening_headers .= "\t" . '</metadata>' . "\n\n";
			
			$opening_headers .= "\t" . '<manifest>' . "\n\n";
			
			$opening_headers .= "\t\t" . '<item id="text href="';
			$opening_headers .= $this->daisy_filename . '.daisy';
			$opening_headers .= '" media-type="application/x-dtbook+xml">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<itemref idref="text">' . "\n\n";
			
			$opening_headers .= "\t" . '</spine>' . "\n\n";
			
			$opening_headers .= '</package>' . "\n\n";
			
			$opening_headers .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">' . "\n\n";
			
			$opening_headers .= "\t" . '<head>' . "\n\n";
			
			$opening_headers .= "\t\t" . '<title>';
			$opening_headers .= $title;
			$opening_headers .= '</title>' . "\n\n";
			
			$opening_headers .= '	<meta name="robots" content="noindex,nofollow">
	<meta name="cache-control" content="no-cache">
	<meta name="pragma" content="no-cache">
	<meta name="distribution" content="iu">
	<meta name="revisit-after" content="never">
	<meta name="expires" content="thu, 01 Jan 1970 00:00:00 GMT">
	<meta http-equiv="expires" content="thu, 01 Jan 1970 00:00:00 GMT">
	<meta name="googlebot" content="noindex,nofollow,nosnippet,noodp,noarchive,noimageindex,unavailable_after: 01-Jan-1970 00:00:00 EST">
	<meta name="slurp" content="noindex,nofollow,noydir,noodp">';
			
			$opening_headers .= "\t\t" . '<meta http-equiv="content-type" content="text/daisy;charset=UTF-8">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta http-equiv="content-language" content="EN">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta http-equiv="content-style-type" content="text/daisy">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="description" content="';
			$opening_headers .= $description_text;
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="abstract" content="';
			$opening_headers .= $description_text;
			
			$quote_text = '';
			
			if($this->script->record_to_use['quote']) {
				$quote_count = count($this->script->record_to_use['quote']);
				if($quote_count) {
					$quote = $this->script->record_to_use['quote'][0];
					
					$quote_text .= $quote['Quote'];
					
					if($quote['Source']) {
						$quote_text .= ' (From : ' . $quote['Source'] . '.)';
					}
				}
			}
			
			if($quote_text) {
				if($description_text) {
					$opening_headers .= ' ~ ';
				}
				
				$opening_headers .= $quote_text;
			}
			
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="keywords" content="';
			
			$tag_text = '';
			
			if($this->script->record_to_use['tag']) {
				$tag_count = count($this->script->record_to_use['tag']);
				if($tag_count) {
					$display_tags = [];
					$tags = $this->script->record_to_use['tag'];
					for($i = 0; $i < $tag_count; $i++) {
						$tag = $tags[$i];
						$display_tags[] = $tag['Tag'];
					}
					
					$tag_text = implode(', ', $display_tags);
				}
			}
			
			$opening_headers .= $tag_text;
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="classification" content="';
			$opening_headers .= $primary_host_record['Classification'];
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="author" content="';
			$opening_headers .= $author_text;
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="copyright" content="';
			$opening_headers .= $primary_host_record['Copyright'];
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="language" content="English">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="doc-type" content="Web Document">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="doc-class" content="Published">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="doc-rights" content="';
			$opening_headers .= $primary_host_record['Rights'];
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="resource-type" content="document">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="rating" content="general">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="designer" content="';
			$opening_headers .= $primary_host_record['Creator'];
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="generator" content=';
			$opening_headers .= $this->version_object->GetSoftwareName() . ', v. ' . $this->version_object->GetSoftwareVersion();
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="publisher" content="';
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>1, 'www'=>1]);
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="template" content="html-templates/daisy-template.html">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:format" content="DAISY">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:Generator" content="';
			$opening_headers .= $this->version_object->GetSoftwareName() . ', v. ' . $this->version_object->GetSoftwareVersion();
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:ToCItems" content="';
			$opening_headers .= $this->total_number_of_toc_entries;
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:Charset" content="helvetica">' . "\n\n";
			$opening_headers .= "\t\t" . '<meta name="NCC:page-front" content="i">' . "\n";
			$opening_headers .= "\t\t" . '<meta name="NCC:page-normal" content="1">' . "\n";
			$opening_headers .= "\t\t" . '<meta name="NCC:page-special" content="0">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:publisher" content="';
			$opening_headers .= $primary_host_record['Publisher'];
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:identifier" content="';
			$opening_headers .= $this->domain_object->host . '_' . $this->daisy_filename;
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:Country" content="';
			$opening_headers .= 'US';
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:Producer" content="';
			$opening_headers .= $author_text;
			$opening_headers .= '">' . "\n";
			
			$opening_headers .= "\t\t" . '<meta name="NCC:Revision" content="';
			$opening_headers .= $title;
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t\t" . '<link rel="canonical" href="';
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>0, 'www'=>1]);
			$opening_headers .= $_SERVER['REDIRECT_URL'];
			$opening_headers .= '">' . "\n\n";
			
			$opening_headers .= "\t" . '</head>' . "\n\n";
			
			$opening_headers .= "\t" . '<book>' . "\n\n";
			
			$opening_headers .= "\t\t" . '<frontmatter>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<doctitle>';
			$opening_headers .= $this->script->record_to_use['Title'];
			$opening_headers .= '</doctitle>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<covertitle>';
			$opening_headers .= $title;
			$opening_headers .= '</covertitle>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<docauthor>';
			$opening_headers .= $author;
			$opening_headers .= '</docauthor>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<pagenum page="front" id="p_i" class="page-front"><a id="front-matter-page-i" name="page-i">i</a></pagenum>' . "\n\n";
			$opening_headers .= "\t\t\t" . '<prodnote class="preface">' . "\n\n";
			
			$opening_headers .= "\t\t\t\t";
			$opening_headers .= 'This DAISY file was generated by the ' . $this->version_object->GetSoftwareName() . ', v. ' . $this->version_object->GetSoftwareVersion() . '.  The URL containing the original data is: ';
			$opening_headers .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>0, 'www'=>1]);
			$opening_headers .= $_SERVER['REDIRECT_URL'];
			$opening_headers .= '.' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '</prodnote>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<pagenum page="front" id="p_ii" class="page-front"><a id="front-matter-page-ii" name="page-ii">ii</a></pagenum>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<level1 class="title-page">' . "\n\n";
			
			$opening_headers .= "\t\t\t\t" . '<h1 class="title">' . $this->script->record_to_use['Title'] . '</h1>' . "\n";
			
			$opening_headers .= "\t\t\t\t" . '<h2 class="title">' . $this->script->record_to_use['Subtitle'] . '</h2>' . "\n";
			
			$opening_headers .= "\t\t\t\t" . '<h3 class="author">' . $author . '</h3>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '</level1>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<pagenum page="front" id="p_iii" class="page_front"><a id="front-matter-page-iii" name="page-iii">iii</a></pagenum>' . "\n\n";
			
			$opening_headers .= "\t\t\t" . '<level1 class="print-toc" id="frontmatter_level_1">' . "\n\n";
			
			$opening_headers .= "\t\t\t\t" . '<h1 id="h1_first">Table of Contents</h1>' . "\n\n";
			$opening_headers .= "\t\t\t\t" . '<h2 id="h2_first">T.O.C. - Subject Organization</h2>' . "\n\n";
			
			$opening_headers .= "\t\t\t\t" . '<list id="list_toc_subjects">' . "\n\n";
			
			$opening_headers .= "\t\t\t\t\t" . '<a href="#page-i"><li id="toc_subject_organization_entry_i">';
			$opening_headers .= '<span class="entry" id="toc_subject_entry_i">Frontmatter</span> ';
			$opening_headers .= '<span class="pagenum" id="toc_subject_entry_pagenum_i">i</span></li></a>' . "\n";
			
			$opening_headers .= "\t\t\t\t\t" . '<a href="#page-ii"><li id="toc_subject_organization_entry_ii"><span class="entry" id+"toc_subject_entry_ii">Production Note</span> <span class="pagenum id="toc_subject_entry_pagenum_ii">ii</span></li></a>' . "\n";
			
			$opening_headers .= "\t\t\t\t\t" . '<a href="#page_iii"><li id="toc_subject_organization_entry_iii"><span class="entry" id="toc_subject_entry_iii">Title Page</span> <span class="pagenum" id="toc_subject_entry_pagenum_iii">iii</span></li></a>' . "\n";
			
			$opening_headers .= "\t\t\t\t\t" . '<a href="#page-iv"><li id="toc_subject_organization_entry_iv"><span class="entry" id="toc_subject_entry_iv">Table of Contents</span> <span class="pagenum" id="toc_subject_entry_pagenum_iv">iv</span></li></la>' . "\n\n";
			
			return $opening_headers;
		}
		
		public function ConvertHTMLToDaisy_AddMetaData_TocSeparators($args) {
			$toc_separators = "\n\n";
			$toc_separators .= "\t\t\t\t" . '</list>' . "\n\n";
			
			$toc_separators .= "\t\t\t\t\t" . '<h2 id="h2_second">T.O.C. - Page Organization</h2>' . "\n\n";
			
			$toc_separators .= "\t\t\t\t" . '<list id="list_toc_pages">' . "\n\n";
			$toc_separators .= "\t\t\t\t\t" . '<a href="#page-i"><li id="toc_page_organization_entry_i">';
			$toc_separators .= '<span class="entry" id="toc_page_entry_i">Page i</span> ';
			$toc_separators .= '<span class="pagenum" id="toc_page_entyr_pagenum_i">i</span></li></a>' . "\n";
			
			$toc_separators .= "\t\t\t\t\t" . '<a href="#page-ii"><li id="toc_page_organization_entry_ii">';
			$toc_separators .= '<span class="entry" id="toc_page_entry_ii">Page ii</span> ';
			$toc_separators .= '<span class="pagenum" id="toc_page_entry_pagenum_ii">ii</span></li></a>' . "\n";
			
			$toc_separators .= "\t\t\t\t\t" . '<a href="#page-iii"><li id="toc_page_organization_entry_iii"><span class="entry" id="toc_page_entry_iii">Page iii</span> ';
			$toc_separators .= '<span class="pagenum" id="toc_page_entry_pagenum_iii">iii</span></li></a>' . "\n";
			
			$toc_separators .= "\t\t\t\t\t" . '<a href="#page-iv"><li id="toc_page_organization_entry_iv"><span class="entry" id="toc_page_entry_iv">Page iv</span> <span class="pagenum" id="toc_page_entry_pagenum_iv">iv</span></li></a>' . "\n\n";
			
			return $toc_separators;
		}
		
		public function ConvertHTMLToDaisy_AddMetaData_BodySeparator($args) {
			$body_separator = "\n\n";
			
			$body_separator .= "\t\t\t\t" . '</list>' . "\n\n";
			
			$body_separator .= "\t\t\t" . '</level1>' . "\n\n";
			
			$body_separator .= "\t\t\t" . '<br><br><pagenum page="front" id="p_iv" class="page-front"><a id="front-matter-page-iv" name="page-iv">iv</a></pagenum>' . "\n\n";
			
			$body_separator .= "\t\t" . '</frontmatter>' . "\n\n";
			
			$body_separator .= "\t\t" . '<bodymatter>' . "\n\n";
			
			$body_separator .= '<p id="par_0" class="title-text">';
			$body_separator .= $this->title;
			$body_separator .= '</p>' . "\n\n";
			
			return $body_separator;
		}
		
		public function ConvertHTMLToDaisy_AddMetaData_DocumentEnder($args) {
			$document_ender = "\n";
			
			$document_ender .= "\t\t" . '</bodymatter>' . "\n\n";
			$document_ender .= "\t\t" . '<rearmatter>' . "\n\n";
			$document_ender .= "\t\t\t" . '<level1>' . "\n\n";
			
			$document_ender .= "\t\t\t" . '<prodnote class="acknowledgments">' . "\n\n";
			$document_ender .= "\t\t\t\t" . '<div id="bakunin_cannabis_engine_notice">' . "\n\n";
			$document_ender .= "\t\t\t\t" . 'This DAISY file was generated by the ' . $this->version_object->GetSoftwareName() . ', v. ' . $this->version_object->GetSoftwareVersion() . '.  The URL containing the original data is at : ';
			
			$document_ender .= $this->domain_object->GetPrimaryDomain(['insecure'=>1, 'lowercase'=>0, 'www'=>1]);
			$document_ender .= $_SERVER['REDIRECT_URL'];
			
			$document_ender .= ' .' . "\n\n";
			
			$document_ender .= "\t\t\t\t" . '</div>' . "\n\n";
			$document_ender .= "\t\t\t" . '</prodnote>' . "\n\n";
			$document_ender .= "\t\t\t" . '</level1>' . "\n\n";
			$document_ender .= "\t\t" . '</rearmatter>' . "\n\n";
			$document_ender .= "\t" . '</book>' . "\n\n";
			$document_ender .= '</html>' . "\n\n";
			
			$document_ender .= '<!--' . "\n\n" . 'This page was generated by the' . "\n";
			$document_ender .= $this->version_object->GetSoftwareName() . ', v. ' . $this->version_object->GetSoftwareVersion() . '.' . "\n\n";
			$document_ender .= '-->' . "\n";
			
			return $document_ender;
		}
		
		public function ConvertHTMLToDaisy_AddLevelTags($args) {
			$daisy_input = $args['daisyinput'];
			
			$daisy_with_leveling = $daisy_input;
			
			$daisy_leveled_explosion = explode("\n", $daisy_with_leveling);
			$number_of_lines = count($daisy_leveled_explosion);
			
					// Handle All H3 Tags
					// ..................................
		
			for($i = 0; $i < $number_of_lines; $i++) {
				$daisy_leveled_explosion[$i] = str_replace("<h3>", "<level1 class=\"chapter\"><h3>", $daisy_leveled_explosion[$i]);
			}
			
					// Handle All H4 Tags
					// ..................................
		
			for($i = 0; $i < $number_of_lines; $i++) {
				$daisy_leveled_explosion[$i] = str_replace("<h4>", "<level2 class=\"part\"><h4>", $daisy_leveled_explosion[$i]);
			}
			
					// Handle All H5 Tags
					// ..................................
		
			for($i = 0; $i < $number_of_lines; $i++) {
				$daisy_leveled_explosion[$i] = str_replace("<h5>", "<level3 class=\"section\"><h5>", $daisy_leveled_explosion[$i]);
			}
			
					// Handle All H6 Tags
					// ..................................
		
			for($i = 0; $i < $number_of_lines; $i++) {
				$daisy_leveled_explosion[$i] = str_replace("<h6>", "<level4 class=\"subsection\"><h6>", $daisy_leveled_explosion[$i]);
			}
			
					// Handle All Level Closing Tags
					// ...................................
					
			$ignore_first_h3 = 1;
			
			$count_the_h3s_total = 0;
			$count_the_h4s_total = 0;
			$count_the_h5s_total = 0;
			$count_the_h6s_total = 0;
					
			for($i = 0; $i < $number_of_lines; $i++) {
					// Handle H3 Tags
					// .....................
				
				$testable = str_replace("<h3>", "<h3>", $daisy_leveled_explosion[$i], $count_the_h3_tags_involved);
					
				$count_the_h3s_total = $count_the_h3s_total + $count_the_h3_tags_involved;
				
				
				if(	($count_the_h3_tags_involved > 0)	&&
					($ignore_first_h3 == 0)			) {
						// Discover Previous Leveling, Mein Comrade
					for($i_reverse = $i - 1; $i_reverse >= 0; $i_reverse--) {
						$test = str_replace("<h3>", "<h3>", $daisy_leveled_explosion[$i_reverse], $count_the_h3_tags);
						
						if($count_the_h3_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level1>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
						
						$test = str_replace("<h4>", "<h4>", $daisy_leveled_explosion[$i_reverse], $count_the_h4_tags);
						
						if($count_the_h4_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level2>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
						
						$test = str_replace("<h5>", "<h5>", $daisy_leveled_explosion[$i_reverse], $count_the_h5_tags);
						
						if($count_the_h5_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level3>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
						
						$test = str_replace("<h6>", "<h6>", $daisy_leveled_explosion[$i_reverse], $count_the_h6_tags);
						
						if($count_the_h6_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level4>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
					}
				} else {
					$ignore_first_h3 = 0;
				}
				
					// Handle H4 Tags
					// .....................
					
				$testable = str_replace("<h4>", "<h4>", $daisy_leveled_explosion[$i], $count_the_h4_tags_involved);
				
				$count_the_h4s_total = $count_the_h4s_total + $count_the_h4_tags_involved;
				
				if($count_the_h4_tags_involved > 0) {
						// Discover Previous Leveling, Mein Comrade
					for($i_reverse = $i - 1; $i_reverse >= 0; $i_reverse--) {
						
						$test = str_replace("<h4>", "<h4>", $daisy_leveled_explosion[$i_reverse], $count_the_h4_tags);
						
						if($count_the_h4_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level2>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
						
						$test = str_replace("<h5>", "<h5>", $daisy_leveled_explosion[$i_reverse], $count_the_h5_tags);
						
						if($count_the_h5_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level3>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
						
						$test = str_replace("<h6>", "<h6>", $daisy_leveled_explosion[$i_reverse], $count_the_h6_tags);
						
						if($count_the_h6_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level4>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
					}
				}
				
					// Handle H5 Tags
					// .....................
					
				$testable = str_replace("<h5>", "<h5>", $daisy_leveled_explosion[$i], $count_the_h5_tags_involved);
				
				$count_the_h5s_total = $count_the_h5s_total + $count_the_h5_tags_involved;
				
				if($count_the_h5_tags_involved > 0) {
						// Discover Previous Leveling, Mein Comrade
					for($i_reverse = $i - 1; $i_reverse >= 0; $i_reverse--) {
						$test = str_replace("<h5>", "<h5>", $daisy_leveled_explosion[$i_reverse], $count_the_h5_tags);
						
						if($count_the_h5_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level3>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
						
						$test = str_replace("<h6>", "<h6>", $daisy_leveled_explosion[$i_reverse], $count_the_h6_tags);
						
						if($count_the_h6_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level4>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
					}
				}
				
					// Handle H6 Tags
					// .....................
					
				$testable = str_replace("<h6>", "<h6>", $daisy_leveled_explosion[$i], $count_the_h6_tags_involved);
				
				$count_the_h6s_total = $count_the_h6s_total + $count_the_h6_tags_involved;
				
				if($count_the_h6_tags_involved > 0) {
						// Discover Previous Leveling, Mein Comrade
					for($i_reverse = $i - 1; $i_reverse >= 0; $i_reverse--) {
						$test = str_replace("<h6>", "<h6>", $daisy_leveled_explosion[$i_reverse], $count_the_h6_tags);
						
						if($count_the_h6_tags > 0) {
							$daisy_leveled_explosion[$i] = "</level4>" . $daisy_leveled_explosion[$i];
							$i_reverse = 0;
						}
					}
				}
			}
			
				// Return Data Please
				
			$compiled = implode("\n", $daisy_leveled_explosion);
			
			if($count_the_h6s_total > 0) {
				$compiled = $compiled . "</level4>";
			}
			
			if($count_the_h5s_total > 0) {
				$compiled = $compiled . "</level3>";
			}
			
			if($count_the_h4s_total > 0) {
				$compiled = $compiled . "</level2>";
			}
			
			if($count_the_h3s_total > 0) {
				$compiled = $compiled . "</level1>";
			}
			
			$compiled = str_replace("<h3>", "<h1>", $compiled);
			$compiled = str_replace("</h3>", "</h1>", $compiled);
			
			$compiled = str_replace("<h4>", "<h2>", $compiled);
			$compiled = str_replace("</h4>", "</h2>", $compiled);
			
			$compiled = str_replace("<h5>", "<h3>", $compiled);
			$compiled = str_replace("</h5>", "</h3>", $compiled);
			
			$compiled = str_replace("<h6>", "<h4>", $compiled);
			$compiled = str_replace("</h6>", "</h4>", $compiled);
			
			return $compiled;
		}
		
		public function ConvertHTMLToDaisy_AddPageNumbers($args) {
			$daisy_input = $args['daisyinput'];
			
			$daisy_with_page_numbering = $daisy_input;
			
			$daisy_page_explosion = explode("\n", $daisy_with_page_numbering);
			$number_of_lines = count($daisy_page_explosion);
			
			$pages = 0;
			$string_length_so_far = 0;
			
			for($i = 0; $i < $number_of_lines; $i++) {
				$current_test = strip_tags($daisy_page_explosion[$i]);
				
				$string_length_so_far = $string_length_so_far + strlen($current_test);
				
				if($string_length_so_far > 2250) {
					$string_length_so_far = 0;
					
					$pages++;
					
					$daisy_page_explosion[$i] = $daisy_page_explosion[$i] . "\n\n<br><br><pagenum page=\"normal\" id=\"p_$pages\" class=\"page-front\"><a name=\"page-$pages\">$pages</a></pagenum><br><br>\n\n";
				}
			}
			
				// Last Page
				
			if(	($string_length_so_far > 1)	||
				($pages == 0)			) {
				$pages++;
			
				$daisy_page_explosion[$i] = $daisy_page_explosion[$i] . "\n\n<br><br><pagenum page=\"normal\" id=\"p_$pages\" class=\"page-front\"><a name=\"page-$pages\">$pages</a></pagenum><br><br>\n\n";
			}
			
			$compiled = implode("\n", $daisy_page_explosion);
			
			$this->pages = $pages;
			
			return $compiled;
		}

		public function ConvertHTMLToDaisy_GenerateTOC($args) {
			$daisy_input = $args['daisyinput'];
			
					// Pages TOC
			
			$toc_ids = 1;
		
			$daisy_pages_table_of_contents = [];
			
			$pages = $this->pages;
			
			for($i = 0; $i < $pages; $i++) {
				$place = $i + 1;
				$daisy_pages_table_of_contents[] = "\n\t\t\t\t\t<a href=\"#page-$place\"><span class=\"entry\" id=\"toc_subject_entry_$toc_ids\"><li id=\"toc_page_organization_entry_$toc_ids\">Page $place</span> <span class=\"pagenum\" id=\"toc_page_entry_pagenum_$toc_ids\">$place</span></li></a>";
				$toc_ids++;
			}
		
					// Subject TOC
					
			$daisy_subjects_table_of_contents = [];
			
			$toc_ids = 1;
			$count_the_pagenums = 0;
			$daisy_subject_table_parsing_explosion = explode("\n", $daisy_input);
			$number_of_subjects = count($daisy_subject_table_parsing_explosion);
			
			for($i = 0; $i < $number_of_subjects; $i++) {
				$test = str_replace("<h1>", "<h1>", $daisy_subject_table_parsing_explosion[$i], $counting_the_h3s);
				
				$test = str_replace("<h2>", "<h2>", $daisy_subject_table_parsing_explosion[$i], $counting_the_h4s);
				
				$test = str_replace("<h3>", "<h3>", $daisy_subject_table_parsing_explosion[$i], $counting_the_h5s);
				
				$test = str_replace("<h4>", "<h4>", $daisy_subject_table_parsing_explosion[$i], $counting_the_h6s);
				
				$test = str_replace("<pagenum page=", "<pagenum page=", $daisy_subject_table_parsing_explosion[$i], $counting_the_page_nums);
				
				$count_the_pagenums = $count_the_pagenums + $counting_the_page_nums;
				
				if($counting_the_h3s > 0) {
					$title = strip_tags($daisy_subject_table_parsing_explosion[$i]);
					$title = trim($title);
					$next_page = $count_the_pagenums + 1;
					
					$daisy_subjects_table_of_contents[] = "\n\t\t\t\t\t<a href=\"#page-$next_page\"><span class=\"entry\" id=\"toc_subject_entry_$toc_ids\"><li id=\"toc_subject_organization_entry_$toc_ids\">$title (Chapter)</span> <span class=\"pagenum\" id=\"toc_subject_entry_pagenum_$toc_ids\">$next_page</span></li></a>";
					$toc_ids++;
				}
				
				if($counting_the_h4s > 0) {
					$title = strip_tags($daisy_subject_table_parsing_explosion[$i]);
					$title = trim($title);
					$next_page = $count_the_pagenums + 1;
					
					$daisy_subjects_table_of_contents[] = "\n\t\t\t\t\t<a href=\"#page-$next_page\"><span class=\"entry\" id=\"toc_subject_entry_$toc_ids\"><li id=\"toc_subject_organization_entry_$toc_ids\">$title (Part)</span> <span class=\"pagenum\" id=\"toc_subject_entry_pagenum_$toc_ids\">$next_page</span></li></a>";
					$toc_ids++;
				}
				
				if($counting_the_h5s > 0) {
					$title = strip_tags($daisy_subject_table_parsing_explosion[$i]);
					$title = trim($title);
					$next_page = $count_the_pagenums + 1;
					
					$daisy_subjects_table_of_contents[] = "\n\t\t\t\t\t<a href=\"#page-$next_page\"><span class=\"entry\" id=\"toc_subject_entry_$toc_ids\"><li id=\"toc_subject_organization_entry_$toc_ids\">$title (Section)</span> <span class=\"pagenum\" id=\"toc_subject_entry_pagenum_$toc_ids\">$next_page</span></li></a>";
					$toc_ids++;
				}
				
				if($counting_the_h6s > 0) {
					$title = strip_tags($daisy_subject_table_parsing_explosion[$i]);
					$title = trim($title);
					$next_page = $count_the_pagenums + 1;
					
					$daisy_subjects_table_of_contents[] = "\n\t\t\t\t\t<a href=\"#page-$next_page\"><span class=\"entry\" id=\"toc_subject_entry_$toc_ids\"><li id=\"toc_subject_organization_entry_$toc_ids\">$title (Subsection)</span> <span class=\"pagenum\" id=\"toc_subject_entry_pagenum_$toc_ids\">$next_page</span></li></a>";
					$toc_ids++;
				}
			}
			
			$this->daisy_pages_table_of_contents = implode("", $daisy_pages_table_of_contents);
			$this->daisy_subjects_table_of_contents = implode("", $daisy_subjects_table_of_contents);
			
			$number_of_page_toc_entries = count($daisy_pages_table_of_contents);
			$number_of_subject_toc_entries = count($daisy_subjects_table_of_contents);
			
			$this->number_of_page_toc_entries = $number_of_page_toc_entries;
			$this->number_of_subject_toc_entries = $number_of_subject_toc_entries;
			$this->total_number_of_toc_entries = $number_of_page_toc_entries + $number_of_subject_toc_entries;
			
			return true;
		}
		
		public function ConvertHTMLToDaisy_UniqueIdentifiers($args) {
			$daisy_with_unique_identifiers = $args['daisyinput'];
			
			$daisy_uniques_explosion = explode("\n", $daisy_with_unique_identifiers);
			$number_of_lines = count($daisy_uniques_explosion);
			
					// Starting ID Information
			
			$p_ids = 1;
			$q_ids = 1;	
			$b_ids = 1;
			$i_ids = 1;
			$u_ids = 1;
			$a_ids = 1;
			
			$h1_ids = 1;
			$h2_ids = 1;
			$h3_ids = 1;
			$h4_ids = 1;
			$br_ids = 1;
			$tr_ids = 1;
			$td_ids = 1;
			
			$cite_ids = 1;
			
			$table_ids = 1;
			
			$level1_ids = 1;
			$level2_ids = 1;
			$level3_ids = 1;
			$level4_ids = 1;
			$level5_ids = 1;
			$level6_ids = 1;
			
			$blockquote_ids = 1;
			
			for($i_total = 0; $i_total < $number_of_lines; $i_total++) {
				$current_line = $daisy_uniques_explosion[$i_total];
				
				$line_length = strlen($current_line);
				
				for($i_inner = 0; $i_inner <= $line_length - 3; $i_inner++) {
					$previous_component = substr($current_line, 0, $i_inner);
					$remaining_component = substr($current_line, ($i_inner + 3), $line_length);
					$testable_component = substr($current_line, $i_inner, 3);
					
					if($testable_component == "<p ") {
						$daisy_uniques_explosion[$i_total] = $previous_component . $testable_component . "id=\"par_$p_ids\" " . $remaining_component;
						$p_ids++;
					}
					
					if($testable_component == "<p>") {
						$daisy_uniques_explosion[$i_total] = $previous_component . '<p ' . "id=\"par_$p_ids\">" . $remaining_component;
						$p_ids++;
					}
					
					if($testable_component == "<q>") {
						$daisy_uniques_explosion[$i_total] = $previous_component . "<q id=\"q_$q_ids\">" . $remaining_component;
						$q_ids++;
					}
					
					if($testable_component == "<b>") {
						$daisy_uniques_explosion[$i_total] = $previous_component . "<b id=\"b_$b_ids\">" . $remaining_component;
						$b_ids++;
					}
					
					if($testable_component == "<i>") {
						$daisy_uniques_explosion[$i_total] = $previous_component . "<i id=\"i_$i_ids\">" . $remaining_component;
						$i_ids++;
					}
					
					if($testable_component == "<u>") {
						$daisy_uniques_explosion[$i_total] = $previous_component . "<u id=\"u_$u_ids\">" . $remaining_component;
						$u_ids++;
					}
					
					if($testable_component == "<a ") {
						$daisy_uniques_explosion[$i_total] = $previous_component . $testable_component . "id=\"a_$a_ids\" " . $remaining_component;
						$a_ids++;
					}
					
					$current_line = $daisy_uniques_explosion[$i_total];
					
					$line_length = strlen($current_line);
				}
				
				for($i_inner = 0; $i_inner <= $line_length - 4; $i_inner++) {
					$previous_component = substr($current_line, 0, $i_inner);
					$remaining_component = substr($current_line, ($i_inner + 4), $line_length);
					$testable_component = substr($current_line, $i_inner, 4);
					
					if($testable_component == "<h1>") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<h1 id=\"h1_$h1_ids\">" . $remaining_component;
						$h1_ids++;
					}
					
					if($testable_component == "<h2>") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<h2 id=\"h2_$h2_ids\">" . $remaining_component;
						$h2_ids++;
					}
					
					if($testable_component == "<h3>") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<h3 id=\"h3_$h3_ids\">" . $remaining_component;
						$h3_ids++;
					}
					
					if($testable_component == "<h4>") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<h4 id=\"h4_$h4_ids\">" . $remaining_component;
						$h4_ids++;
					}
					
					if($testable_component == "<br>") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<br id=\"br_$br_ids\">" . $remaining_component;
						$br_ids++;
					}
					
					if($testable_component == "<tr>") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<tr id=\"tr_$tr_ids\">" . $remaining_component;
						$tr_ids++;
					}
					
					if($testable_component == "<td>") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<td id=\"td_$td_ids\">" . $remaining_component;
						$td_ids++;
					}
					
					$current_line = $daisy_uniques_explosion[$i_total];
					
					$line_length = strlen($current_line);
				}
				
				for($i_inner = 0; $i_inner <= $line_length - 6; $i_inner++) {
					$previous_component = substr($current_line, 0, $i_inner);
					$remaining_component = substr($current_line, ($i_inner + 6), $line_length);
					$testable_component = substr($current_line, $i_inner, 6);
					
					if($testable_component == "<cite ") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<cite id=\"cite_$cite_ids\" " . $remaining_component;
						$cite_ids++;
					}
				
					$current_line = $daisy_uniques_explosion[$i_total];
					
					$line_length = strlen($current_line);
				
				}
				
				for($i_inner = 0; $i_inner <= $line_length - 7; $i_inner++) {
					$previous_component = substr($current_line, 0, $i_inner);
					$remaining_component = substr($current_line, ($i_inner + 7), $line_length);
					$testable_component = substr($current_line, $i_inner, 7);
					
					if($testable_component == "<table ") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<table id=\"table_$table_ids\" " . $remaining_component;
						$table_ids++;
					}
					
					if($testable_component == "<level1") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<level1 id=\"level1_$level1_ids\" " . $remaining_component;
						$level1_ids++;
					}
					
					if($testable_component == "<level2") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<level2 id=\"level2_$level2_ids\" " . $remaining_component;
						$level2_ids++;
					}
					
					if($testable_component == "<level3") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<level3 id=\"level3_$level3_ids\" " . $remaining_component;
						$level3_ids++;
					}
					
					if($testable_component == "<level4") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<level4 id=\"level4_$level4_ids\" " . $remaining_component;
						$level4_ids++;
					}
					
					$current_line = $daisy_uniques_explosion[$i_total];
					
					$line_length = strlen($current_line);
				
				}
				
				for($i_inner = 0; $i_inner <= $line_length - 11; $i_inner++) {
					$previous_component = substr($current_line, 0, $i_inner);
					$remaining_component = substr($current_line, ($i_inner + 11), $line_length);
					$testable_component = substr($current_line, $i_inner, 11);
					
					if($testable_component == "<blockquote") {
						$daisy_uniques_explosion[$i_total] = $previous_component  . "<blockquote id=\"blockquote_$blockquote_ids\" " . $remaining_component;
						$blockquote_ids++;
					}
					
					$current_line = $daisy_uniques_explosion[$i_total];
					
					$line_length = strlen($current_line);
				
				}
			}
			
			$compiled = implode("\n", $daisy_uniques_explosion);
			
			return $compiled;
		}
	}
	
?>