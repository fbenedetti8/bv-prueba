<?php
class Vendedor_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_vendedores";
		$this->indexable = array('nombre','apellido','email','dni');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	//login vendedor
	function login($name,$pass){
		$admin = $this->db->get_where($this->table, array('email' => $name))->row();
		
		if ($admin) {
			if ($admin->password == md5($pass)) {
				/*
				$data = array('intentos' => 0, 'fecha_ultimo_acceso' => date('Y-m-d H:i:s'), 'ip' => $_SERVER['REMOTE_ADDR']);

				$this->db->where('id', $admin->id);
				$this->db->update($this->table, $data);
				*/
				return array('success' => TRUE, 'admin' => $admin);
			}
			else {
				/*
				$data = array('intentos' => $admin->intentos + 1);
				if ($data['intentos'] > 4) $data['activo'] = 0;

				$this->db->where('id', $admin->id);
				$this->db->update($this->table, $data);
				*/
				return array('success' => FALSE, 'error' => 'Usuario y/o contraseña incorrectos.');
			}
		}
		else {
			return array('success' => FALSE, 'error' => 'Usuario y/o contraseña incorrectos.');
		}
	}

	function getListado() {
		return $this->db->query("SELECT id, CONCAT(nombre, ' ', apellido) as nombreCompleto FROM bv_vendedores ORDER BY nombre")->result();
	}


	function getListadoCompleto() {
		return $this->db->query("SELECT id, CONCAT(nombre, ' ', apellido) as nombreCompleto, 0 as pos
									FROM bv_vendedores 
								UNION 
							 	 SELECT -id, CONCAT('Admin - ', nombre) as nombreCompleto, 1 as pos
									FROM bv_admins WHERE perfil = 'VEN' 
								ORDER BY 3 desc, 2 asc")->result();
	}


	function onGetAll(){
		$this->db->select('bv_vendedores.*, CONCAT(nombre," ",apellido) as nombreCompleto, "DNI" as dniTipo, m.saldo');
		$this->db->join('(select usuario_id, sum(debe)-sum(haber) as saldo
							from bv_movimientos 
							where tipoUsuario = "V"
							group by usuario_id) m', 'm.usuario_id = bv_vendedores.id ','left');
	}
	
	function onGet(){
		$this->db->select('bv_vendedores.*, CONCAT(nombre," ",apellido) as nombreCompleto, "DNI" as dniTipo, m.saldo');
		$this->db->join('(select usuario_id, sum(debe)-sum(haber) as saldo
							from bv_movimientos 
							where tipoUsuario = "V"
							group by usuario_id) m', 'm.usuario_id = bv_vendedores.id ','left');
	}
	
	function getCoordinadoresDisponibles($paquete_id){
		$paq = $this->db->query("select p.fecha_inicio, p.fecha_fin from bv_paquetes p where id = ".$paquete_id)->row();

		$q = "select CONCAT(c.nombre,' ',c.apellido) as nombreCompleto, c.*, 'DNI' as dniTipo 
				from bv_vendedores c 
				where c.es_coordinador = 1 and 
						c.id not in ( 
							select c.id 
							from bv_vendedores c
							join bv_paquetes_coordinadores pc on pc.vendedor_id = c.id 
							join bv_paquetes p on p.id = pc.paquete_id and ('".$paq->fecha_inicio."'  between p.fecha_inicio and p.fecha_fin) and ('".$paq->fecha_fin."'  between p.fecha_inicio and p.fecha_fin) and p.id != ".$paquete_id."
						)
				order by 1 asc";
		return $this->db->query($q)->result();
	}	

}