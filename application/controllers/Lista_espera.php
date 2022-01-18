<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lista_espera extends CI_Controller{

	function __construct () {
		parent::__construct();
		
		$this->load->model('Lista_espera_model','Lista_espera');
	}
    
	function save(){
		extract($_POST);
		
		$this->form_validation->set_rules('nombre', 'Nombre', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('celular_codigo', 'Cód Area', 'required');
		$this->form_validation->set_rules('celular_numero', 'Número', 'required');
		
		if ($this->form_validation->run() == FALSE){
			$valida = false;
			
			$ret['status'] = 'error';
			$ret['fields'] = array_keys($this->form_validation->error_array());
		}
		else{
			//chequeo si ya esta suscripto
			$usr = $this->Lista_espera->getWhere(array("email" => $email,"tipo" => $tipo,"tipo_id" => $tipo_id))->row();
			if(empty($usr)){
				$dd = array(
							"tipo" => $tipo,
							"tipo_id" => $tipo_id,
							"email" => $email,
							"nombre" => $nombre,
							"celular_codigo" => @$celular_codigo,
							"celular_numero" => @$celular_numero,
							"telefono_codigo" => @$telefono_codigo,
							"telefono_numero" => @$telefono_numero,
							"fecha" => date("Y-m-d H:i:s"),
							"ip" => $_SERVER['REMOTE_ADDR']
						);
						
				$this->Lista_espera->insert($dd);
				$ret['status'] = "success";
				$ret['msg'] = "Gracias por anotarte en la lista de espera.";
			}
			else{
				$ret['status'] = "error";
				$ret['msg'] = "Ya te encuentras anotado en la lista de espera.";
			}
		}
			
		echo json_encode($ret);
	}
	
}
?>