<?php
class Celular_contacto_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_celulares_contacto";
		$this->indexable = array('telefono');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	/* random entre 1 y 10 en base a relevancia en la ocurrencia */
	function getRandom(){
		$this->db->query("set @rand:=ceil(RAND()*(10-1+1)+1);");
		$q = $this->db->query("
								select telefono, relevancia, @rand as rand, @rand-relevancia as diff 
									from bv_celulares_contacto
									having diff <= 0
									order by rand() asc
									limit 1")->row();

		//si no trae resultado, devuelvo el de mayor relevancia
		if( !$q || !isset($q->telefono) ){
			$q = $this->db->query("select * from bv_celulares_contacto order by relevancia desc limit 1")->row();
		}

		return $q;
	}

}