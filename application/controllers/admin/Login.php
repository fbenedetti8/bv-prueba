<?php
class Login extends CI_Controller{

	function __construct() {
		parent::__construct();
		$this->data = array();
		$this->load->model('Admin_model', 'Admin');
		$this->load->model('Vendedor_model', 'Vendedor');
	}
    
    function index(){
		$data = array();
        $data['title'] = $this->config->item('appName');

        if (isset($_COOKIE['bv_usuario']) && isset($_COOKIE['bv_password'])) {

        	$results = $this->Admin->login($_COOKIE['bv_usuario'], $_COOKIE['bv_password']);
        	if (count($results)) {
        		$row = $results[0];
				$this->session->set_userdata('admin_id', $row->id);
				$this->session->set_userdata('usuario', $row->nombre);

				redirect(site_url("admin/panel"));
        	}
        }

        $this->load->view('admin/login',$data);
    }
	
	function validate(){
		extract($_POST);

		if (!empty($username) && !empty($password)) {
			$result = $this->Admin->login($username, $password);
			
			//login ADMIN
			if ($result['success']) {
				$row = $result['admin'];
				$this->session->set_userdata('admin_id', $row->id);
				$this->session->set_userdata('usuario', $row->nombre);
				$this->session->set_userdata('es_admin', true);
				
				//si tiene vendedor asociado
				if($row->vendedor_id > 0){
					$this->session->set_userdata('es_vendedor', true);
				}
				else{
					$this->session->set_userdata('es_vendedor', false);
				}

				$this->session->set_userdata('perfil', $row->perfil);

				if ($row->cambio_password) {
					redirect(site_url('admin/seguridad'));
				}
				else {
					if (isset($_POST['remember'])) {
						setcookie('bv_usuario', $username, time()+60*60*24*30);
						setcookie('bv_password', md5($password), time()+60*60*24*30);
					}

					redirect(site_url("admin"));
				}
			}
			else {
				//login vendedor externo
				$result = $this->Vendedor->login($username, $password);
			
				if ($result['success']) {
					$row = $result['admin'];
					$this->session->set_userdata('admin_id', $row->id);
					$this->session->set_userdata('usuario', $row->nombre);
					$this->session->set_userdata('es_admin', true);
					$this->session->set_userdata('es_vendedor', true);
					$this->session->set_userdata('perfil', 'VENEXT');

					if ($row->cambio_password) {
						redirect(site_url('admin/seguridad'));
					}
					else {
						if (isset($_POST['remember'])) {
							setcookie('bv_usuario', $username, time()+60*60*24*30);
							setcookie('bv_password', md5($password), time()+60*60*24*30);
						}

						redirect(site_url("admin"));
					}
				}
				else {
					$data = array('error' => $result['error']);
					$data['title'] = $this->config->item('appName');
					$this->load->view('admin/login', $data);
				}
			}
		} else {	
			$data = array('error' => "Debes ingresar tu usuario y contraseña");
			$data['title'] = $this->config->item('appName');
			$this->load->view('admin/login', $data);
		}
	}

	function logoff() {
		$this->session->sess_destroy();
		
		setcookie('bv_usuario', '', 0);
		setcookie('bv_password', '', 0);

		redirect(site_url("admin/login"));
	}

	function recover() {
		$this->load->model('Admin');
		$user = $this->Admin->getBy('email='.$_POST['email']);
		if ($user) {
			$this->load->helper('string');
			$password = random_string('alnum', 8);
			$this->Admin->update($user->id, array('password' => md5($password), 'cambio_password' => 1));

			$mensaje = "<p>Sus nuevos datos de acceso a la plataforma de administración de BUENAS VIBRAS son:<br/><br/><strong>Usuario:</strong> ".$user->usuario."<br/><strong>Contraseña:</strong> ".$password."<br/></p><p>Ingrese ahora <a href='".base_url().'admin'."'>haciendo click aquí</a>.</p>";
			enviarMail('noreply@buenasvibras.com.ar', $_POST['email'], $bcc='', 'Datos de acceso a su panel de BUENAS VIBRAS', $mensaje, 'BUENAS VIBRAS');

			redirect(site_url('admin/login/?recovered=1'));
		}
		else {
			redirect(site_url('admin/login/?recovered=0'));			
		}
	}
	
	function versesion(){
		echo $this->session->userdata('admin_id');
		echo $this->session->userdata('usuario');
		echo $this->session->userdata('es_admin');
		echo $this->session->userdata('es_vendedor');

	}	

}
