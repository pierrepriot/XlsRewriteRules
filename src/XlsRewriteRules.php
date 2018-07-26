<?php

class XlsRewriteRules
{
    // main configuration storage object
	private $inputFileName; 
	private $colOld;
	private $colNew;
		
	// class constructor
	public function __construct($sInput, $sOld, $sNew) {
		$this->inputFileName=$sInput;
		$this->colOld=$sOld;
		$this->colNew=$sNew;
		
	}
	
	private function removeFirstSlash($str){
		return preg_replace('/^\/(.*)$/msi', '$1', $str);	
	}

	private function detectIdenticalURLs($old, $new){
		if($old==$new){
			return true;
		}
		elseif (str_replace($new, '', $old)=='/'	||	str_replace($new, '', $old)=='//'){
			return true;	
		}
		elseif (str_replace($old, '', $new)=='/'	||	str_replace($old, '', $new)=='//'){
			return true;	
		}
		return false;
	}

	public function generate(){
		
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->inputFileName);
		$sheetTout = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

		foreach($sheetTout as $k => $row){
			// seulement les cases remplies
			if ($row[$this->colOld]!=null){	

				// si nouvelle url vide = /
				if ($row[$this->colNew]==''){
					$row[$this->colNew]='/';
				}

				// seulement si si nouvelle url valide
				if (preg_match('/^\/.*$/si',  $row[$this->colNew])){

					// cas querystring
					if (preg_match('/(.*)\?(.*)/si', $row[$this->colOld], $qs)){				
						echo 'RewriteCond %{QUERY_STRING} '.$qs[2].'<br>';
						echo 'RewriteRule ^'.$this->removeFirstSlash($qs[1]).'$	'.$row[$this->colNew].' [R=301,L]<br>';			
					}
					// normal
					else{
						// ignorer les rewrites sur url identique
						if (!$this->detectIdenticalURLs($row[$this->colOld], $row[$this->colNew])){
							echo 'RewriteRule ^'.$this->removeFirstSlash($row[$this->colOld]).'$	'.$row[$this->colNew].' [R=301,L]<br>';
						}
					}	
				}
			}	
		}
	}
}

