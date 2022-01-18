<?php
class Estacionales_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_estacionales";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
    $this->defaultSort = 'orden';
  }

	function getRegionales($id){
		$this->db->select('c.*');
		$this->db->join('bv_destinos d','d.categoria_id = c.id');
		$this->db->join('bv_destinos_estacionales de','de.destino_id = d.id');
		$this->db->join('bv_estacionales e','e.id = de.estacional_id and e.id = '.$id);
		$this->db->group_by('c.id');
		$this->db->order_by('c.orden','asc');
		return $this->db->get('bv_categorias c')->result();
	}
	
	function getEstacionalesGrupales(){
		$this->db->select('e.*, ifnull(p.grupal,0) as grupal');
		$this->db->join('bv_destinos_estacionales de','e.id = de.estacional_id');
		$this->db->join('bv_destinos d','d.id = de.destino_id');
		$this->db->join('bv_categorias c','c.id = d.categoria_id');
		//obtengo la marca de grupal de los paquetes de cada destino
		$this->db->join('(select p.destino_id, max(p.grupal) as grupal
							from bv_paquetes p 
							join bv_destinos d on d.id = p.destino_id
							where p.grupal = 1
							group by p.destino_id) p','p.destino_id = d.id');
		$this->db->group_by('e.id');
		$this->db->order_by('e.orden','asc');
		return $this->db->get('bv_estacionales e')->result();
	}
	
	function getListDelDestino($destino_id=false,$publicado=false,$home=false){
		$where = "";
		if($destino_id){
			$where = " and d.id = ".$destino_id;
		}
		if($publicado){
			$where = " and d.publicado = 1";
		}

		$join= "";
		if($home){
			$join = " join bv_paquetes p on p.destino_id = d.id and p.activo = 1
            		  join bv_paquetes_estacionales pe on pe.paquete_id = p.id and pe.estacional_id = e.id";
		}

		$q = "select distinct e.* from bv_estacionales e 
				JOIN bv_destinos_estacionales de on de.estacional_id = e.id
				join bv_destinos d on d.id = de.destino_id".$where.$join;
		return $this->db->query($q)->result();
	}
	
	//Devuelve las categorias estacionales de los paquetes que estén vigentes al dia de hoy
	function getConPaquetes($con_paquetes=false){		
		$q = "select distinct x.pids, x.cantidad, e.* 
				from bv_estacionales e 
				JOIN bv_destinos_estacionales de on de.estacional_id = e.id
				join bv_destinos d on d.id = de.destino_id
				join bv_paquetes p on p.destino_id = d.id
				join bv_paquetes_estacionales pe on pe.paquete_id = p.id 
				join (select pe.estacional_id, count(distinct p.id) as cantidad, group_concat(p.id) as pids
											from bv_paquetes p 
				join bv_paquetes_estacionales pe on pe.paquete_id = p.id
											where p.activo = 1 and p.fecha_inicio >= NOW() 
											group by pe.estacional_id) x ON x.estacional_id = e.id 

				where d.publicado = 1 and p.activo = 1 and p.fecha_inicio >= NOW() 
				group by e.id
				order by e.orden asc";
		return $this->db->query($q)->result();
	}
	
}