<?php
class Admin_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_admins";
		$this->indexable = array('nombre', 'usuario');
		$this->fk = "admin_id";
		$this->pk = "id";
	}
	
	function login($name,$pass){
		$admin = $this->db->get_where($this->table, array('usuario' => $name))->row();
		if ($admin) {
			if ($admin->activo) {
				if ($admin->password == md5($pass)) {
					$data = array('intentos' => 0, 'fecha_ultimo_acceso' => date('Y-m-d H:i:s'), 'ip' => $_SERVER['REMOTE_ADDR']);

					$this->db->where('id', $admin->id);
					$this->db->update($this->table, $data);

					$this->db->insert('bv_admins_log', array(
						'admin_id' => $admin->id,
						'usuario' => $name,
						'password' => 'xxxxxx',
						'valido' => 1,
						'timestamp' => date('Y-m-d H:i:s'),
						'ip' => $_SERVER['REMOTE_ADDR']
					));

					return array('success' => TRUE, 'admin' => $admin);
				}
				else {
					$data = array('intentos' => $admin->intentos + 1);
					/*
					if ($data['intentos'] > 19) $data['activo'] = 0;

					$this->db->where('id', $admin->id);
					$this->db->update($this->table, $data);
					*/
					
					$this->db->insert('bv_admins_log', array(
						'admin_id' => $admin->id,
						'usuario' => $name,
						'password' => $pass,
						'valido' => 0,
						'timestamp' => date('Y-m-d H:i:s'),
						'ip' => $_SERVER['REMOTE_ADDR']
					));
					
					return array('success' => FALSE, 'error' => 'Usuario y/o contraseña incorrectos.');
				}
			}
			else {
				return array('success' => FALSE, 'error' => 'Tu cuenta ha sido bloqueada por seguridad. Contacta al administrador.');
			}
		}
		else {
			$this->db->insert('bv_admins_log', array(
				'admin_id' => 0,
				'usuario' => $name,
				'password' => $pass,
				'valido' => 0,
				'timestamp' => date('Y-m-d H:i:s'),
				'ip' => $_SERVER['REMOTE_ADDR']
			));

			return array('success' => FALSE, 'error' => 'Usuario y/o contraseña incorrectos.');
		}
	}

	function getByName($name){
		$q = "select * from ".$this->table." where nombre like '%" . $name . "%' COLLATE 'utf8_general_ci'";
		$data = $this->db->query($q);
		return $data;
	}	
	
	
}