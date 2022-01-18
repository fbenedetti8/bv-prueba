<?php
class Reserva_comentario_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_reservas_comentarios";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function onGetAll(){
		$this->db->select($this->table.'.*, TA.tipo, TA.nombre, TA.icono, TA.imagen, TA.editable, (case when tipoUsuario = "V" then concat(V.nombre," ",V.apellido) else A.usuario end) as admin',false);
		$this->db->join('bv_comentarios_tipos_acciones TA','TA.id = '.$this->table.'.tipo_id','left');
		$this->db->join('bv_reservas R','R.id = '.$this->table.'.reserva_id');
		$this->db->join('bv_admins A','A.id = '.$this->table.'.admin_id','left');
		$this->db->join('bv_vendedores V','V.id = '.$this->table.'.admin_id','left');
	}
	
	/*
	devuelve estado de cantidad de registros de costo operador generados para la reserva, y cantidad de anulados
	*/
	function getStatusCostosOperador($id){
		$q = "select ifnull(anu.anulados,0) as anulados, 
					  ifnull(gen.generados,0) as generados 
				from bv_reservas r
				left join (select reserva_id, count(*) as anulados from `bv_reservas_comentarios` 
				where tipo_id = 19 and reserva_id = ".$id." and comentarios like '%anulación%'
				group by reserva_id order by id desc) anu on anu.reserva_id = r.id
				left join (select reserva_id, count(*) as generados from `bv_reservas_comentarios` 
				where tipo_id = 19 and reserva_id = ".$id." and comentarios not like '%anulación%'
				group by reserva_id order by id desc) gen on gen.reserva_id = r.id
				WHERE r.id = ".$id;
		
		return $this->db->query($q)->row();
	}

}