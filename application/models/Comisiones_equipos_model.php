<?php
class Comisiones_equipos_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_equipos";
		$this->indexable = array('nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function onGetAll() {
$this->db->select('bv_equipos.*, CASE WHEN bv_equipos.coordinador_tipo=\'V\' THEN CONCAT(v1.nombre, " ", v1.apellido) ELSE a1.nombre END as coordinador, CASE WHEN bv_equipos.gerente_tipo=\'V\' THEN CONCAT(v2.nombre, " ", v2.apellido) ELSE a2.nombre END as gerente');
		$this->db->join('bv_vendedores v1', 'v1.id = bv_equipos.coordinador_id and bv_equipos.coordinador_tipo = \'V\'', 'left');
		$this->db->join('bv_vendedores v2', 'v2.id = bv_equipos.gerente_id and bv_equipos.gerente_tipo = \'V\'', 'left');
		$this->db->join('bv_admins a1', 'a1.id = bv_equipos.coordinador_id and bv_equipos.coordinador_tipo = \'A\'', 'left');
		$this->db->join('bv_admins a2', 'a2.id = bv_equipos.gerente_id and bv_equipos.gerente_tipo = \'A\'', 'left');
	}

	function getMiembros($id) {
		return $this->db->query("SELECT m.id, v.nombre, v.apellido FROM bv_vendedores_equipos m INNER JOIN bv_vendedores v ON v.id = m.vendedor_id WHERE m.equipo_id = ".$id." ORDER BY v.nombre, v.apellido")->result();
	}

	function getMiembro($vendedor_id) {
		return $this->db->get_where('bv_vendedores_equipos', array('vendedor_id' => $vendedor_id))->row();
	}

	function addMiembro($equipo_id, $vendedor_id) {
		$this->db->insert('bv_vendedores_equipos', array(
			'equipo_id' => $equipo_id,
			'vendedor_id' => $vendedor_id,
		));
	}

	function removeMiembro($id) {
		$this->db->where('id', $id);
		$this->db->delete('bv_vendedores_equipos');
	}
	
	function deleteMiembros($equipo_id) {
		$this->db->where('equipo_id', $equipo_id);
		$this->db->delete('bv_vendedores_equipos');
	}

}