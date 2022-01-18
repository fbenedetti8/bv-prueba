<?php
class Caja_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_caja";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function onGetAll(){
		$this->db->select('bv_caja.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido, p.nombre as paquete_titulo, ad.usuario as admin_username');
		$this->db->join('bv_movimientos m','m.id = bv_caja.movimiento_id and m.tipoUsuario = "U"','left');
		$this->db->join('bv_usuarios u','u.id = m.usuario_id','left');
		$this->db->join('bv_reservas r','r.id = m.reserva_id','left');
		$this->db->join('bv_paquetes p','p.id = r.paquete_id','left');
		$this->db->join('bv_admins ad','ad.id = bv_caja.admin_id','left');
	}
	
	function onGetAll_export(){
		$this->db->select('DATE_FORMAT(bv_caja.fecha,"%d/%m/%Y") as "FECHA", DATE_FORMAT(bv_caja.fecha,"%H:%i") as "HORA", bv_caja.id as "ID", bv_caja.concepto as "CONCEPTO", p.nombre as "TITULO VIAJE", bv_caja.ingreso as "INGRESO", bv_caja.egreso as "EGRESO", bv_caja.saldo as "SALDO", CONCAT(u.apellido," ",u.nombre) as "PASAJERO", bv_caja.observaciones as "OBSERVACIONES", ad.usuario as "ADMIN"',false);
		$this->db->join('bv_movimientos m','m.id = bv_caja.movimiento_id and m.tipoUsuario = "U"','left');
		$this->db->join('bv_usuarios u','u.id = m.usuario_id','left');
		$this->db->join('bv_reservas r','r.id = m.reserva_id','left');
		$this->db->join('bv_paquetes p','p.id = r.paquete_id','left');
		$this->db->join('bv_admins ad','ad.id = bv_caja.admin_id','left');
	}
	
	//cambio nombres de columnas para exportar en excel
	function getAll_export($num=100000, $offset=0, $sort='', $type='', $keywords='') {
		$this->onGetAll_export();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " .$this->table.".".$index . " LIKE '%" . $keywords . "%'";
			//$this->db->or_like($index, $keywords);
			$q .= ")";
			$this->db->where($q);
		}
		
		if ($this->filters != '')
			$this->db->where($this->filters);
				
		if ($sort != '')
			$query = $this->db->order_by('1', $type)->get($this->table, $num, $offset);	
		else 
			$query = $this->db->get($this->table, $num, $offset);	
		
		return $query;
	}

}