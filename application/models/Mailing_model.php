<?php
class Mailing_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_mailings";
		$this->indexable = array('bv_mailings.asunto','p.nombre');
		$this->fk = "id";
		$this->pk = "id";
    	$this->defaultSort = 'id';
    	$this->defaultSortType = 'desc';
	}
	
    function onGet(){
        $this->db->select("bv_mailings.*, p.nombre, p.fecha_inicio, p.destino_id",false);
        $this->db->join("bv_paquetes p","p.id = bv_mailings.paquete_id");
    }
    function onGetAll(){
        $this->db->select("bv_mailings.*, p.nombre, p.fecha_inicio, p.destino_id",false);
        $this->db->join("bv_paquetes p","p.id = bv_mailings.paquete_id");
    }

}