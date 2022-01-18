<?php
class Combinacion_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_paquetes_combinaciones";
    $this->indexable = array();
    $this->fk = "id";
    $this->pk = "id";
  }
 
  function onGet() {
    $this->db->select('bv_paquetes_combinaciones.*, p.nombre as paquete, p.confirmacion_inmediata, p.grupal, 
						concat(d.slug,"/",p.slug) as slug, p.fecha_inicio, p.fecha_fin, 
						p.monto_minimo_reserva, p.fecha_indefinida, p.mostrar_calendario, p.itinerario, p.precio_usd, p.cupo_disponible, p.cupo_paquete_personalizado, p.cupo_paquete_disponible, p.cupo_paquete_disponible_real, 
						a.nombre as alojamiento, af.fecha_checkin, af.fecha_checkout, t.tipo_id, t.nombre as transporte, r.nombre as regimen,
						h.pax, h.nombre as habitacion, l.nombre as lugar, d.nombre as destino,
						(case when t.tipo_id = 1 then 1 else 0 end) as en_avion, tf.fecha_salida, tf.fecha_regreso,
						(bv_paquetes_combinaciones.v_iva21+bv_paquetes_combinaciones.v_iva10+bv_paquetes_combinaciones.v_otros_imp)+(case when p.exterior=1 then ifnull(p.impuesto_pais,0) else 0 end) as precio_impuestos, (bv_paquetes_combinaciones.v_total-bv_paquetes_combinaciones.v_iva21-bv_paquetes_combinaciones.v_iva10-bv_paquetes_combinaciones.v_otros_imp) as precio_total, ifnull(p.impuesto_pais,0) as impuesto_pais, p.exterior');
    $this->db->join('bv_alojamientos a', 'a.id = bv_paquetes_combinaciones.alojamiento_id', 'left');
    $this->db->join('bv_alojamientos_fechas af', 'af.id = bv_paquetes_combinaciones.fecha_alojamiento_id', 'left');
    $this->db->join('bv_transportes t', 't.id = bv_paquetes_combinaciones.transporte_id', 'left');
    $this->db->join('bv_transportes_fechas tf', 'tf.id = bv_paquetes_combinaciones.fecha_transporte_id', 'left');
    $this->db->join('bv_paquetes p', 'p.id = bv_paquetes_combinaciones.paquete_id', 'left');
    $this->db->join('bv_destinos d', 'd.id = p.destino_id', 'left');
    $this->db->join('bv_categorias c', 'c.id = d.categoria_id', 'left');
    $this->db->join('bv_regimenes r', 'r.id = bv_paquetes_combinaciones.regimen_id', 'left');
    $this->db->join('bv_habitaciones h', 'h.id = bv_paquetes_combinaciones.habitacion_id', 'left');
    $this->db->join('bv_lugares_salida l', 'l.id = bv_paquetes_combinaciones.lugar_id', 'left');
  }
  
  function getByPaquete($paquete_id,$limit=9999,$filtros=array()) {
    $this->db->distinct();
    $this->db->select('min(tf.cupo) as cupo_trans, min(case when pc.habitacion_id = 99 then 9999 else fc.cupo end) as cupo_aloj, pc.*, p.nombre as paquete, p.confirmacion_inmediata, p.grupal, p.slug, p.fecha_inicio, p.fecha_fin, p.monto_minimo_reserva, p.fecha_indefinida, p.mostrar_calendario, p.itinerario, p.precio_usd, 
						a.nombre as alojamiento, af.fecha_checkin, af.fecha_checkout, t.nombre as transporte, r.nombre as regimen,
						h.pax, h.nombre as habitacion, l.nombre as lugar, d.nombre as destino,
						CONCAT(a.nombre," (",sum(distinct case when fc.habitacion_id != 99 then fc.cupo_total else 0 end)," lugares)", "<br>Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y")) as alojamientocompleto,
						CONCAT(t.nombre," (",tf.cupo," lugares)","<br>Salida: ",DATE_FORMAT(tf.fecha_salida,"%d/%m/%Y")," - Regreso: ",DATE_FORMAT(tf.fecha_regreso,"%d/%m/%Y")) as transportecompleto,
						(pc.v_iva21+pc.v_iva10+pc.v_otros_imp)+(case when p.exterior=1 then ifnull(p.impuesto_pais,0) else 0 end) as precio_impuestos, (pc.v_total-pc.v_iva21-pc.v_iva10-pc.v_otros_imp) as precio_total, p.exterior, ifnull(p.impuesto_pais,0) as impuesto_pais');
    $this->db->join('bv_alojamientos a', 'a.id = pc.alojamiento_id', 'left');
    $this->db->join('bv_alojamientos_fechas af', 'af.id = pc.fecha_alojamiento_id', 'left');
    $this->db->join('bv_alojamientos_fechas_cupos fc', 'fc.fecha_id = af.id and fc.habitacion_id = pc.habitacion_id', 'left');
	$this->db->join('bv_paquetes_alojamientos ad','af.id = ad.fecha_alojamiento_id');
	$this->db->join('bv_transportes_fechas tf','tf.id = ad.fecha_transporte_id and pc.fecha_transporte_id = tf.id');
    $this->db->join('bv_transportes t', 't.id = pc.transporte_id', 'left');
    $this->db->join('bv_paquetes p', 'p.id = pc.paquete_id', 'left');
    $this->db->join('bv_destinos d', 'd.id = p.destino_id', 'left');
    $this->db->join('bv_regimenes r', 'r.id = pc.regimen_id', 'left');
    $this->db->join('bv_habitaciones h', 'h.id = pc.habitacion_id', 'left');
    $this->db->join('bv_lugares_salida l', 'l.id = pc.lugar_id', 'left');
	
	//filtros para la combinacion
		if(isset($filtros['pax']) && $filtros['pax']){
			if(isset($filtros['cupo_alojam']) && $filtros['cupo_alojam']){
				
				//si la reserva es grupal, entonces cambia el filtro, busco habitacion apra que como minimo me alcance para la totalidad de pax
				if(isset($filtros['reserva_grupal']) && $filtros['reserva_grupal']){
					$this->db->where('(h.pax >= '.$filtros['pax'].' or pc.habitacion_id = 99)');
				}
				else{
					$this->db->where('(h.pax = '.$filtros['pax'].' or pc.habitacion_id = 99)');
				}
			
			}
			else{
					
				//si la reserva es grupal, entonces cambia el filtro
				if(isset($filtros['reserva_grupal']) && $filtros['reserva_grupal']){
					$this->db->where('(h.pax <= '.$filtros['pax'].' or pc.habitacion_id = 99)');
				}
				else{
					$this->db->where('(h.pax = '.$filtros['pax'].' or pc.habitacion_id = 99)');
				}
			
			}
			
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
			//$this->db->where('af.id = '.$filtros['fecha_id']);
			$this->db->where('af.fecha_checkin = "'.$rango[0].'" and af.fecha_checkout = "'.$rango[1].'"');
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
	
		if(isset($filtros['transporte']) && $filtros['transporte']){
			$this->db->where('pc.fecha_transporte_id = '.$filtros['transporte']);
		}
		if(isset($filtros['disponibles']) && $filtros['disponibles']){
			$this->db->where('pc.agotada = 0');
		}
		if(isset($filtros['combinacion_id']) && $filtros['combinacion_id']){
			$this->db->where('pc.id = '.$filtros['combinacion_id']);
		}
		if(isset($filtros['not_combinacion_id']) && $filtros['not_combinacion_id']){
			$this->db->where('pc.id != '.$filtros['not_combinacion_id']);
		}

		
    $this->db->limit($limit);
    $this->db->order_by('pc.v_total','asc');
    $this->db->order_by('h.pax','asc');
    
    $this->db->join('(select pp.paquete_id, l.id, min(pp.hora) as hora
							from bv_paquetes_paradas pp 
							join bv_paradas pa on pp.parada_id = pa.id
							join bv_lugares_salida l on l.id = pa.lugar_id
							where pp.paquete_id = '.$paquete_id.'
							group by pp.paquete_id, l.id) tt','tt.id = l.id','left');
    	$this->db->order_by('tt.hora','asc');
    
    
    $this->db->group_by('pc.id');

	$res = $this->db->get_where('bv_paquetes_combinaciones pc', array('pc.paquete_id' => $paquete_id));
	if($limit==1){
		$res = $res->row();
	}
	else{
		$res = $res->result();
	}
	
    return $res;
  }
  
  function updatePrecios($paquete_id,$data){
	  //solo actualizo los que todavia no fueron actualizados manualmente
	$this->db->where('precio_actualizado', '0');
	$this->db->where('paquete_id', $paquete_id);
	$this->db->update('bv_paquetes_combinaciones', $data);
	$updated_status = $this->db->affected_rows();

	return $updated_status;
  }
  
  /*
  En base a un array de combinaciones creadas, devuelve cuáles son las NO existen para el paquete
  */
  function getInexistentesPaquete($id,$arr){
	  $inexistentes = array();
	  
		foreach($arr as $a){
			$wh = array();
			$wh['alojamiento_id'] = $a[0];//alojamiento_id
			$wh['transporte_id'] = $a[1];//transporte_id
			$wh['fecha_alojamiento_id'] = $a[2];//fecha_alojamiento_id
			$wh['fecha_transporte_id'] = $a[3];//fecha_transporte_id
			$wh['fecha_alojamiento_cupo_id'] = $a[4];//fecha_alojamiento_cupo_id
			$wh['habitacion_id'] = $a[5];//habitacion_id
			$wh['paquete_regimen_id'] = $a[6];//paquete_regimen_id
			$wh['regimen_id'] = $a[7];//regimen_id
			$wh['lugar_id'] = $a[8];//lugar_id
		
			$this->db->where('paquete_id',$id);
			$this->db->where($wh);
			$existe = $this->db->get($this->table)->row();
			if(!$existe){
				$inexistentes[] = $a;
			}
		}
		
		return $inexistentes;
  }

  function delete($id) {
  	$ordenes = $this->db->get_where('bv_ordenes', array('combinacion_id' => $id, 'vencida' => 0))->result();
  	if (count($ordenes) > 0) {
  		return FALSE;
  	}

  	$reservas = $this->db->get_where('bv_reservas', array('combinacion_id' => $id))->result();
  	if (count($reservas) > 0) {
  		return FALSE;
  	}

  	parent::delete($id);
  }
  
}
