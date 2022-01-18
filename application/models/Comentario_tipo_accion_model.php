<?php
class Comentario_tipo_accion_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_comentarios_tipos_acciones";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
}