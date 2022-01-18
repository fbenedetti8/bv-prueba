<?php
class Contacto_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_contactos";
		$this->indexable = array('nombre', 'email');
		$this->fk = "id";
		$this->pk = "id";
	}

	function onGetAll_export(){
		$this->onBeforeExportFaqs();

		parent::onGetAll_export();
	}

	function onBeforeExportFaqs(){
		$this->db->select("email, pais, comentario, fecha, ip");
	}

}