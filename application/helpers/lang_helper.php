<?php
	/*
		Actualizado 23-02-2012 por Maxi:
			+ language_dir()
	*/
	
	function switch_uri($lang)
	{
		if ($lang == "es")
			return "/";
		else
			return "/" . $lang . "/";	
	}		
	
	/*
	returns the dir name in 'application/languages/..' for the $lang selected
	*/
	function language_dir($lang){
		switch($lang){
			case "es":
				$dir = "es";
			break;
			case "en":
				$dir = "en";
			break;
            case "pt":
				$dir = "pt";
			break;
			default:
				$dir = "es";
			break;
		}
		
		return $dir;
	}
	
	function current_language() {		
		$urlData = explode("/", $_SERVER["REQUEST_URI"]);
		//if (count($urlData)>1 && strlen($urlData[1])==2)
		if (count($urlData)>1 && strlen($urlData[2])==2){
			//$lang = $urlData[1];
			$lang = $urlData[2];
		}
		else{
			$lang = "es"; //default language
		}
		      
		return $lang;
	}
	
	function curPageURL() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
?>