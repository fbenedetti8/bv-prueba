<?php
class Alojamiento_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_alojamientos";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
  }

  function getServicios($alojamiento_id) {
    $this->db->select('servicio_id');
    $servicios = $this->db->get_where('bv_alojamientos_servicios', array('alojamiento_id' => $alojamiento_id))->result();

    $serviciosArr = [];
    foreach ($servicios as $servicio) {
      $serviciosArr[] = $servicio->servicio_id;
    }

    return $serviciosArr;
  }

  function clearServicios($alojamiento_id) {
    $this->db->where('alojamiento_id', $alojamiento_id);
    $this->db->delete('bv_alojamientos_servicios');
  }

  function addServicio($alojamiento_id, $servicio_id) {
    $this->db->insert('bv_alojamientos_servicios', array(
      'alojamiento_id' => $alojamiento_id,
      'servicio_id' => $servicio_id
    ));
  }

  function getDestinos($alojamiento_id) {
    $this->db->select('destino_id');
    $servicios = $this->db->get_where('bv_alojamientos_destinos', array('alojamiento_id' => $alojamiento_id))->result();

    $serviciosArr = [];
    foreach ($servicios as $servicio) {
      $serviciosArr[] = $servicio->destino_id;
    }

    return $serviciosArr;
  }

  function clearDestinos($alojamiento_id) {
    $this->db->where('alojamiento_id', $alojamiento_id);
    $this->db->delete('bv_alojamientos_destinos');
  }

  function addDestino($alojamiento_id, $destino_id) {
    $this->db->insert('bv_alojamientos_destinos', array(
      'alojamiento_id' => $alojamiento_id,
      'destino_id' => $destino_id
    ));
  }

  //devuelve los alojamientos asociados al destino particular
  function getByDestino($destino_id,$fecha_inicio,$fecha_fin,$fecha_indefinida=false) {
    $this->db->select('a.*, f.id as fecha_id, ifnull(t.total,0) as cupo_total_alojamiento, 
						CONCAT(a.nombre," (",ifnull(t.total,0)," lugares)"," - Del ",DATE_FORMAT(f.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(f.fecha_checkout,"%d/%m/%Y"), " ",ifnull(f.descripcion, "")) as nombrecompleto');
	
    $this->db->join('bv_alojamientos a','a.id = ad.alojamiento_id');
    if($fecha_indefinida)
		$this->db->join('bv_alojamientos_fechas f','f.alojamiento_id = ad.alojamiento_id');
	else
		$this->db->join('bv_alojamientos_fechas f','f.alojamiento_id = ad.alojamiento_id and "'.$fecha_inicio.'" <= f.fecha_checkin and "'.$fecha_inicio.'" <= f.fecha_checkout and "'.$fecha_fin.'" >= f.fecha_checkin and "'.$fecha_fin.'" >= fecha_checkout');
	
	$this->db->join('(select fecha_id, sum(case when habitacion_id != 99 then cupo_total else 0 end) as total from bv_alojamientos_fechas_cupos group by fecha_id) t','t.fecha_id = f.id','left');
    
	$servicios = $this->db->get_where('bv_alojamientos_destinos ad', array('ad.destino_id' => $destino_id))->result();

    return $servicios;
  }

  //devuelve los alojamientos asociados al paquete
  function getByPaquete($paquete_id) {
    $this->db->select('ad.*, 
						a.nombre as alojamiento, 
            tf.vuelo_ida,
            tf.vuelo_regreso,
						CONCAT(a.nombre," (",ifnull(ac.total,0)," lugares)"," - Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y"), " ",ifnull(af.descripcion, "")) as alojamientocompleto, 
						ifnull(ac.total,0) as cupo_total_alojamiento, 
						t.nombre as transporte, 
						CONCAT(t.nombre," (",tf.cupo_total," lugares)"," - Salida ", tf.vuelo_ida, ": ",DATE_FORMAT(tf.fecha_salida,"%d/%m/%Y")," - Regreso ", tf.vuelo_regreso,": ",DATE_FORMAT(tf.fecha_regreso,"%d/%m/%Y")) as transportecompleto');
    $this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id');
    $this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
	$this->db->join('(select fecha_id, sum(case when habitacion_id != 99 then cupo_total else 0 end) as total from bv_alojamientos_fechas_cupos group by fecha_id) ac','ac.fecha_id = af.id','left');
	
	  $this->db->join('bv_transportes_fechas tf','tf.id = ad.fecha_transporte_id');
    $this->db->join('bv_transportes t','t.id = tf.transporte_id');
	$servicios = $this->db->get_where('bv_paquetes_alojamientos ad', array('ad.paquete_id' => $paquete_id))->result();

    return $servicios;
  }

  function addPaquete($paquete_id, $fecha_transporte_id, $fecha_alojamiento_id, $transporte_id, $alojamiento_id) {
    $this->db->insert('bv_paquetes_alojamientos', array(
      'alojamiento_id' => $alojamiento_id,
      'transporte_id' => $transporte_id,
      'fecha_alojamiento_id' => $fecha_alojamiento_id,
      'fecha_transporte_id' => $fecha_transporte_id,
      'paquete_id' => $paquete_id
    ));
	
	return $this->db->insert_id();
  }
  
  function getAsociacionPaquete($id){
    $this->db->select('ad.id, 
						a.nombre as alojamiento, 
						CONCAT(a.nombre," (",ifnull(ac.total,0)," lugares)"," - Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y")) as alojamientocompleto, 
						ifnull(ac.total,0) as cupo_total_alojamiento, 
						t.nombre as transporte, 
						CONCAT(t.nombre," (",tf.cupo," lugares)"," - Salida: ",DATE_FORMAT(tf.fecha_salida,"%d/%m/%Y")," - Regreso: ",DATE_FORMAT(tf.fecha_regreso,"%d/%m/%Y")) as transportecompleto');
    $this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id');
    $this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
	$this->db->join('(select fecha_id, sum(case when habitacion_id != 99 then cupo_total else 0 end) as total from bv_alojamientos_fechas_cupos group by fecha_id) ac','ac.fecha_id = af.id','left');
	  $this->db->join('bv_transportes_fechas tf','tf.id = ad.fecha_transporte_id');
    $this->db->join('bv_transportes t','t.id = tf.transporte_id');
    return $this->db->get_where('bv_paquetes_alojamientos ad', array('ad.id' => $id))->row();
  }  

  function deleteAsociacionPaquete($id){
    $rel = $this->db->get_where('bv_paquetes_alojamientos', array('id' => $id))->row();

    $ordenes = $this->db->get_where('bv_ordenes', array('fecha_alojamiento_id' => $rel->fecha_alojamiento_id, 'transporte_fecha_id' => $rel->fecha_transporte_id, 'paquete_id' => $rel->paquete_id, 'vencida' => 0))->result();

    $reservas = $this->db->get_where('bv_reservas', array('fecha_alojamiento_id' => $rel->fecha_alojamiento_id, 'transporte_fecha_id' => $rel->fecha_transporte_id, 'paquete_id' => $rel->paquete_id))->result();

    if (count($ordenes) || count($reservas)) {
      return FALSE;
    }
    else {
      $this->db->where('id = '.$id);
      $this->db->delete('bv_paquetes_alojamientos');
      return TRUE;
    }
  }  
  
  function getDataServicios($alojamiento_id) {
	$this->db->select('s.*');
	$this->db->join('bv_servicios s','s.id = a.servicio_id');
	$this->db->order_by('s.orden asc, s.nombre asc');
    $servicios = $this->db->get_where('bv_alojamientos_servicios a', array('a.alojamiento_id' => $alojamiento_id))->result();
	return $servicios;
  }


}