<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends CI_Controller{

	function __construct () {
		parent::__construct();
		
		$this->load->model('Newsletter_model','Newsletter');
	}
    
	function save(){
		extract($_POST);
		
		if( isset($email) && $email != "" && isset($pais) && $pais != "" ){
			//chequeo si ya esta suscripto
			$usr = $this->Newsletter->getWhere(array("email" => $email))->row();
			if(empty($usr)){
				$dd = array(
							"email" => $email,
							"pais" => $pais,
							"fecha" => date("Y-m-d H:i:s"),
						);
				$this->Newsletter->insert($dd);
				$ret['status'] = "success";
				$ret['msg'] = "Gracias por suscribirte.";
			}
			else{
				$ret['status'] = "error";
				$ret['msg'] = "Ya te encuentras suscripto con ese email.";
			}
		}
		else{
			$ret['status'] = "error";
			$ret['msg'] = "* Debes completar este campo.";
		}
		
		echo json_encode($ret);
	}
	
}
?>