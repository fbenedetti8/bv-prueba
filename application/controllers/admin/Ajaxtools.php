<?php
class AjaxTools extends CI_Controller{

	function AjaxTools () {
		parent::__construct();
	}
	
	function deletefile() {
		$filename = ".".$_POST['filename'];
		unlink($filename);
	}
	
	
}
