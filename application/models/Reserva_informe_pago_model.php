<?php
class Reserva_informe_pago_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_reservas_informes_pago";
		$this->indexable = array('banco');
		$this->fk = "id";
		$this->pk = "id";
	}

	function onGetAll(){
		$this->db->distinct();
		$this->db->select($this->table.'.*,m.informe_id');
		$this->db->join('bv_movimientos m','m.informe_id = '.$this->table.'.id','left');
	}
	
	function onGet(){
		$this->db->distinct();
		$this->db->select($this->table.'.*,m.informe_id');
		$this->db->join('bv_movimientos m','m.informe_id = '.$this->table.'.id','left');
	}
	
}