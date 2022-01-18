<?php
class Adicional_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_adicionales";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
  }

  //devuelve los adicionales asociadas al paquete
	function getByPaquete($paquete_id) {
		$this->db->select('ad.*, a.nombre as adicional, ad.cantidad, d.cantidad as usados, ad.obligatorio, p.precio_usd, 
							CONCAT(t.nombre," (",ifnull(tf.cupo,0)," lugares)") as transporte');
		$this->db->join('bv_adicionales a','a.id = ad.adicional_id');
		$this->db->join('bv_paquetes p','p.id = ad.paquete_id');
		$this->db->join('bv_transportes t', 't.id = ad.transporte_id', 'left');
		$this->db->join('bv_transportes_fechas tf', 'tf.id = ad.transporte_fecha_id', 'left');
		/*
		join con reserva de adiconales para saber cuántos tengo reservados de este tipo en reservas NO anuladas
		*/
		$this->db->join('(select pa.paquete_id, pa.adicional_id, sum(r.pasajeros) as cantidad 
							from bv_reservas_adicionales ra 
							join bv_reservas r on r.id = ra.reserva_id and r.estado_id != 5
							join bv_paquetes_adicionales pa on pa.id = ra.paquete_adicional_id
							group by pa.paquete_id, pa.adicional_id) d', 'd.paquete_id = ad.paquete_id and d.adicional_id = ad.adicional_id', 'left');

		$servicios = $this->db->get_where('bv_paquetes_adicionales ad', array('ad.paquete_id' => $paquete_id))->result();

		return $servicios;
	}

	function addPaquete($paquete_id, $adicional_id, $cantidad, $obligatorio, $transporte_fecha_id, $transporte_id) {
		$this->db->insert('bv_paquetes_adicionales', array(
		  'adicional_id' => $adicional_id,
		  'cantidad' => $cantidad,
		  'obligatorio' => $obligatorio,
		  'paquete_id' => $paquete_id,
		  'transporte_fecha_id' => $transporte_fecha_id,
		  'transporte_id' => $transporte_id,
		));
		
		return $this->db->insert_id();
	}

	function getAsociacionPaquete($id){
		$this->db->select('ad.*, a.nombre as adicional, p.precio_usd, ifnull(d.cantidad,0) as usados, 
							CONCAT(t.nombre," (",ifnull(tf.cupo,0)," lugares)") as transporte');
		$this->db->join('bv_adicionales a','a.id = ad.adicional_id');
		$this->db->join('bv_paquetes p', 'p.id = ad.paquete_id', 'left');
		$this->db->join('bv_transportes t', 't.id = ad.transporte_id', 'left');
		$this->db->join('bv_transportes_fechas tf', 'tf.id = ad.transporte_fecha_id', 'left');
		/*
		join con reserva de adiconales para saber cuántos tengo reservados de este tipo en reservas NO anuladas
		*/
		$this->db->join('(select pa.paquete_id, pa.adicional_id, sum(r.pasajeros) as cantidad 
							from bv_reservas_adicionales ra 
							join bv_reservas r on r.id = ra.reserva_id and r.estado_id != 5
							join bv_paquetes_adicionales pa on pa.id = ra.paquete_adicional_id
							group by pa.paquete_id, pa.adicional_id) d', 'd.paquete_id = ad.paquete_id and d.adicional_id = ad.adicional_id', 'left');
		return $this->db->get_where('bv_paquetes_adicionales ad', array('ad.id' => $id))->row();
	}  
	
	function deleteAsociacionPaquete($id){
		$this->db->join('bv_ordenes o','o.id = oa.orden_id and o.vencida = 0');
		$ordenes = $this->db->get_where('bv_ordenes_adicionales oa', array('oa.paquete_adicional_id' => $id))->result();

	    $reservas = $this->db->get_where('bv_reservas_adicionales', array('paquete_adicional_id' => $id))->result();

	    if (count($ordenes) || count($reservas)) {
	      return FALSE;
	    }
	    else {
			$this->db->where('id = '.$id);
			$this->db->delete('bv_paquetes_adicionales');
			return TRUE;
		}
	}  
	
	function updatePaqueteAdicional($paq_adicional_id, $data) {
		$this->db->where('id', $paq_adicional_id);
		$this->db->update('bv_paquetes_adicionales', $data);
		return true;
	}

	function getPaqueteAdicional($adicional_id) {
		$this->db->select('bv_paquetes_adicionales.*, p.precio_usd');
		$this->db->join('bv_paquetes p', 'p.id = bv_paquetes_adicionales.paquete_id', 'left');
   
		$this->db->where('bv_paquetes_adicionales.id', $adicional_id);
		return $this->db->get('bv_paquetes_adicionales');		
	}

	function getCombinacionPaquete($paquete_id,$filtros=array()) {
		$this->db->select('ad.*, a.nombre as adicional, ad.cantidad, ad.obligatorio, t.nombre as transporte, 
							ifnull(d.cantidad,0) as usados');
		$this->db->join('bv_adicionales a','a.id = ad.adicional_id');
		$this->db->join('bv_transportes t', 't.id = ad.transporte_id', 'left');
		/*
		join con reserva de adiconales para saber cuántos tengo reservados de este tipo en reservas NO anuladas
		*/
		$this->db->join('(select pa.paquete_id, pa.adicional_id, sum(r.pasajeros) as cantidad 
							from bv_reservas_adicionales ra 
							join bv_reservas r on r.id = ra.reserva_id and r.estado_id != 5
							join bv_paquetes_adicionales pa on pa.id = ra.paquete_adicional_id
							group by pa.paquete_id, pa.adicional_id) d', 'd.paquete_id = ad.paquete_id and d.adicional_id = ad.adicional_id', 'left');
							
		if(isset($filtros['transporte']) && $filtros['transporte']){
			$this->db->join('bv_transportes_fechas tf','t.id = tf.transporte_id and tf.id = '.$filtros['transporte'],'left');
			$this->db->where('(ad.transporte_fecha_id = '.$filtros['transporte'].' or ad.transporte_fecha_id = 0 or ad.transporte_fecha_id is null)');
		}
	
		$servicios = $this->db->get_where('bv_paquetes_adicionales ad', array('ad.paquete_id' => $paquete_id))->result();

		return $servicios;
	}

}