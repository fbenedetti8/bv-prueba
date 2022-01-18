<?php
class Celular_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_celulares";
		$this->indexable = array('nombre','telefono','pais');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function onGetAll(){
		$this->db->select("bv_celulares.*, CONCAT(nombre,' ',pais,' - ',telefono) as nombreCompleto",false);
	}

	function onGet(){
		$this->db->select("bv_celulares.*, CONCAT(nombre,' ',pais,' - ',telefono) as nombreCompleto",false);
	}

	function getCelularesDisponibles($paquete_id){
		$paq = $this->db->query("select p.fecha_inicio, p.fecha_fin from bv_paquetes p where id = ".$paquete_id)->row();

		$q = "select c.*, CONCAT(nombre,' ',pais,' - ',telefono) as nombreCompleto
				from bv_celulares c 
				where c.id not in ( 
							select c.id 
							from bv_celulares c
							join bv_paquetes_celulares pc on pc.celular_id = c.id 
							join bv_paquetes p on p.id = pc.paquete_id and ('".$paq->fecha_inicio."'  between p.fecha_inicio and p.fecha_fin) and ('".$paq->fecha_fin."'  between p.fecha_inicio and p.fecha_fin) and p.id != ".$paquete_id."
						)
				order by c.id asc";
		return $this->db->query($q)->result();
	}

}