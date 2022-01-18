<?php
class Excursion_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_excursiones";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
  }

  function getDestinos($excursion_id) {
    $this->db->select('destino_id');
    $servicios = $this->db->get_where('bv_excursiones_destinos', array('excursion_id' => $excursion_id))->result();

    $serviciosArr = [];
    foreach ($servicios as $servicio) {
      $serviciosArr[] = $servicio->destino_id;
    }

    return $serviciosArr;
  }

  function clearDestinos($excursion_id) {
    $this->db->where('excursion_id', $excursion_id);
    $this->db->delete('bv_excursiones_destinos');
  }

  function addDestino($excursion_id, $destino_id) {
    $this->db->insert('bv_excursiones_destinos', array(
      'excursion_id' => $excursion_id,
      'destino_id' => $destino_id
    ));
  }

  function getAllDestinos($arr,$arr2) {
    /*$this->db->select('ed.*, e.nombre');
    $this->db->join('bv_excursiones e', 'e.id = ed.excursion_id', 'left');
    $this->db->where_in('ed.destino_id',$arr);
    $this->db->or_where_in('ed.destino_id',$arr2);
    $res = $this->db->get('bv_excursiones_destinos ed')->result();
    return $res;*/

    $where = "";
    if(count($arr2) > 0){
      $where = ' OR ed.destino_id in ("'.implode(',',$arr2).'")';
    }

    $q = "select ed.*, e.nombre 
            from bv_excursiones_destinos ed
             join bv_excursiones e on e.id = ed.excursion_id
            where ed.destino_id in (0,'',NULL) ".$where."

            union 

            select 0 as id, e.id as excursion_id,  0 as destino_id, e.nombre 
            from bv_excursiones e 
            where e.id not in (select ed.excursion_id from bv_excursiones_destinos ed )";

      return $this->db->query($q)->result();
  }

}