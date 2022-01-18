<?php
class Telefonos_contacto_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_telefonos_contacto";
		$this->indexable = array('bv_telefonos_contacto.nombre','telefono');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function onGetAll() {
		$this->db->select('p.nombre as pais,'.$this->table.'.*');
		$this->db->join('bv_paises p', 'p.id = '.$this->table.'.id_pais', 'left');
	}

	function getTelefonos() {

		$telefonos_contacto = $this->db->query('
			select * from(
					select z.*
					from
					  (SELECT bv_telefonos_contacto.id, bv_telefonos_contacto.telefono, id_pais, bv_paises.nombre as pais
					  FROM bv_telefonos_contacto LEFT JOIN bv_paises ON bv_paises.id = bv_telefonos_contacto.id_pais 
					  ORDER BY RAND()  ) as z
					group by z.id_pais, z.pais) p
			join bv_telefonos_contacto x on x.id = p.id
			order by x.orden asc');
		return $telefonos_contacto ? $telefonos_contacto->result() : [];
	}
}