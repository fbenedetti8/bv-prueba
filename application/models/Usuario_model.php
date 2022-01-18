<?php
class Usuario_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_usuarios";
		$this->indexable = array('nombre','apellido','email','dni');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function onGetAll(){
		$this->db->select('bv_usuarios.*, "DNI" as dniTipo, m.saldo');
		$this->db->join('(select usuario_id, sum(debe)-sum(haber) as saldo, sum(debe_usd)-sum(haber_usd) as saldo_usd
							from bv_movimientos 
							where tipoUsuario = "U"
							group by usuario_id) m', 'm.usuario_id = bv_usuarios.id ','left');
	}
	
	function onGet(){
		$this->db->select('bv_usuarios.*, "DNI" as dniTipo, m.saldo');
		$this->db->join('(select usuario_id, sum(debe)-sum(haber) as saldo, sum(debe_usd)-sum(haber_usd) as saldo_usd
							from bv_movimientos 
							where tipoUsuario = "U"
							group by usuario_id) m', 'm.usuario_id = bv_usuarios.id ','left');
	}
	
}