<?php
include "AdminController.php";

class Seguridad extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['currentModule'] = "seguridad";
		$this->page = "seguridad";
		$this->data['page'] = $this->page;
		$this->data['route'] = base_url() . 'admin/' . $this->page;
		$this->validate = FALSE;
		$this->load->model('Admin_model', 'Admin');
		$this->model = $this->Admin;
		$this->init();		
	}

	function index(){
		$admin = $this->Admin->get(userloggedId())->row();

		$this->data['first'] = $admin->cambio_password;
		$this->data['success'] = FALSE;
		$this->load->view('admin/password', $this->data);
	}
	
	function cambiar() {
		$admin = $this->Admin->get(userloggedId())->row();
		
		if ($admin->password != md5($_POST['password'])) {
			redirect(site_url('admin/seguridad/?error=1'));
		}
		elseif ($_POST['new_password'] != $_POST['new_password2']) {
			redirect(site_url('admin/seguridad/?error=2'));
		}
		elseif (strlen($_POST['new_password']) < 5) {
			redirect(site_url('admin/seguridad/?error=3'));
		}
		else {
			$this->Admin->update(userloggedId(), array('password' => md5($_POST['new_password']), 'cambio_password' => 0, 'fecha_cambio_password' => date('Y-m-d H:i:s')));
			
			redirect(site_url('admin/seguridad/?saved=1'));
		}
	}
	
	
}