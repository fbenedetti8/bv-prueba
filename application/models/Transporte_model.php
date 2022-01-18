<?php
class Transporte_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_transportes";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
  }

  function onGet() {
    $this->db->select($this->table.'.*, ta.nombre as tipo');
	$this->db->join('bv_tipos_transportes ta','ta.id = '.$this->table.'.tipo_id');
  }
  
  function onGetAll() {
    $this->db->select($this->table.'.*, ta.nombre as tipo');
	$this->db->join('bv_tipos_transportes ta','ta.id = '.$this->table.'.tipo_id');
  }
  
  function getDestinos($transporte_id) {
    $res = $this->db->get_where('bv_transportes_destinos', array('transporte_id' => $transporte_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->destino_id;
    }
    return $arr;
  }

  function clearDestinos($transporte_id) {
    $this->db->where('transporte_id', $transporte_id);
    $this->db->delete('bv_transportes_destinos');
  }

  function addDestino($transporte_id, $destino_id) {
    $this->db->insert('bv_transportes_destinos', array(
      'transporte_id' => $transporte_id,
      'destino_id' => $destino_id
    ));
  }

  //devuelve los transportes por fecha asociados al destino particular
  function getByDestino($destino_id,$fecha,$fecha_indefinida=false) {
    $this->db->select('a.*, f.id as fecha_id, 
						CONCAT(a.nombre," (",f.cupo," lugares) "," - Salida ", f.vuelo_ida,": ",DATE_FORMAT(f.fecha_salida,"%d/%m/%Y")," - Regreso ", f.vuelo_regreso,": ",DATE_FORMAT(f.fecha_regreso,"%d/%m/%Y")) as nombrecompleto');
    
    $this->db->join('bv_transportes a','a.id = ad.transporte_id');
	if($fecha_indefinida)
		$this->db->join('bv_transportes_fechas f','f.transporte_id = ad.transporte_id and f.fecha_salida >= "'.$fecha.'"');
	else
		$this->db->join('bv_transportes_fechas f','f.transporte_id = ad.transporte_id and f.fecha_salida = "'.$fecha.'"');
	
    $servicios = $this->db->get_where('bv_transportes_destinos ad', array('ad.destino_id' => $destino_id))->result();

    return $servicios;
  }
  
  //devuelve los transportes asociados al destino particular
  function getTransportesDestino($destino_id,$fecha,$fecha_indefinida=false) {
    $this->db->select('f.id as transporte_fecha_id, a.*, CONCAT(a.nombre," (",f.cupo," lugares)") as nombrecompleto');    
    $this->db->join('bv_transportes a','a.id = ad.transporte_id');	
    if($fecha_indefinida)
		$this->db->join('bv_transportes_fechas f','f.transporte_id = ad.transporte_id and f.fecha_salida >= "'.$fecha.'"');
    else
		$this->db->join('bv_transportes_fechas f','f.transporte_id = ad.transporte_id and f.fecha_salida = "'.$fecha.'"');
	$servicios = $this->db->get_where('bv_transportes_destinos ad', array('ad.destino_id' => $destino_id))->result();

    return $servicios;
  }
  
  function getCombinacionPaquete($id,$filtros=array()) {
	$this->db->select('tf.id, tf.transporte_id, t.nombre, t.titulo, t.descripcion, tf.cupo');
	$this->db->join('bv_paquetes_combinaciones pc','pc.fecha_transporte_id = tf.id and pc.paquete_id = '.$id);
	$this->db->join('bv_paquetes p','p.id = pc.paquete_id');
	$this->db->join('bv_transportes t','t.id = tf.transporte_id');
	
	if(isset($filtros['pax']) && $filtros['pax']){
		$this->db->join('bv_habitaciones h','h.id = pc.habitacion_id and h.pax = '.$filtros['pax']);
	}
		
	if(isset($filtros['lugar_salida']) && $filtros['lugar_salida']){
		$this->db->where('pc.lugar_id = '.$filtros['lugar_salida']);
	}
	
	/*
	if(isset($filtros['fecha_id']) && $filtros['fecha_id']){
		$this->db->where('pc.fecha_alojamiento_id = '.$filtros['fecha_id']);
	}
	*/
	//fecha_id ahroa se usa como rango
	if(isset($filtros['fecha_id']) && $filtros['fecha_id']){
		$rango = explode('|',$filtros['fecha_id']);
		
		$this->db->join('bv_alojamientos_fechas af','af.fecha_checkin = "'.$rango[0].'" and af.fecha_checkout = "'.$rango[1].'"');
	}
	
	if(isset($filtros['alojamiento']) && $filtros['alojamiento']){
		$this->db->where('pc.alojamiento_id = '.$filtros['alojamiento']);
	}
	
	if(isset($filtros['habitacion']) && $filtros['habitacion']){
		$this->db->where('pc.habitacion_id = '.$filtros['habitacion']);
	}
		
	if(isset($filtros['pension']) && $filtros['pension']){
		$this->db->where('pc.paquete_regimen_id = '.$filtros['pension']);
	}
		
	//$this->db->group_by('tf.id');
	$this->db->group_by('tf.transporte_id');
	$res = $this->db->get('bv_transportes_fechas tf')->result();
	return $res;
  }
}