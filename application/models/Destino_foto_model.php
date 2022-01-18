<?php
class Destino_foto_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_destinos_fotos";
		$this->indexable = array('foto');
		$this->fk = "id";
		$this->pk = "id";
		$this->defaultSort = 'orden';
	}
	
}
?>