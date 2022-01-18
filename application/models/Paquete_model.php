<?php
class Paquete_model extends MY_Model {

  function __construct(){
    parent::__construct();
    $this->table = "bv_paquetes";
    $this->indexable = array('bv_paquetes.nombre','bv_paquetes.codigo');
    $this->fk = "id";
    $this->pk = "id";
    $this->defaultSort = 'fecha_inicio DESC';
  }

  function getOne($paquete_id) {
    $res = $this->db->get_where('bv_paquetes', array('id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr['codigo'] = $r->codigo;
      $arr['nombre'] = $r->nombre;
    }
    return $arr;
  }

  function onGetAll() {
    $this->db->select($this->table.'.*, d.nombre as destino, o.nombre as operador, ifnull(le.cantidad,0) as lista_espera');
    $this->db->join('bv_destinos d', 'd.id = '.$this->table.'.destino_id', 'left');
    $this->db->join('bv_operadores o', 'o.id = '.$this->table.'.operador_id', 'left');
	//join a lista de espera de la categoria
	$this->db->join('(select tipo_id, count(*) as cantidad
							from bv_lista_espera
							where tipo = "paquete"
							group by tipo_id) le','le.tipo_id = '.$this->table.'.id','left');
  }

  function onGet_old() {
    $this->db->select($this->table.'.*, d.nombre as destino, o.nombre as operador');
    $this->db->join('bv_destinos d', 'd.id = '.$this->table.'.destino_id', 'left');
    $this->db->join('bv_operadores o', 'o.id = '.$this->table.'.operador_id', 'left');
  }

  function onGet() {
	  /*
	ifnull(p.precio,0) as precio,
	ifnull(p.grupal,0) as grupal,
	ifnull(p.precio_usd,0) as precio_usd,
	*/
    $this->db->select("bv_paquetes.*, o.nombre as operador, o.razonsocial, o.legajo,
						 d.nombre as destino, d.imagen, d.categoria_id, d.slug as destino_slug,
						 c.nombre as categoria, c.slug as categoria_slug,
						ifnull(de.estacional,'') as estacional,
						ifnull(t.tipo,'') as tipo_transporte,
            case ifnull(t.tipo,'') when 'Avion' then 1 else 0 end as en_avion,
						ifnull(t.descripcion,'') as descripcion_transporte,
						ifnull(t.clase,'') as clase_transporte,
						ifnull(x.fecha_checkin,'') as fecha_checkin,
						ifnull(x.fecha_checkout,'') as fecha_checkout,
						ifnull(x.pax,'') as pax");
	$this->db->join('bv_operadores o', 'o.id = bv_paquetes.operador_id', 'left');
	$this->db->join('bv_destinos d', 'd.id = bv_paquetes.destino_id', 'left');
	//obtengo subcategoria estacional del destino
	$this->db->join('(select d.id, e.nombre as estacional
						from bv_destinos d
						join bv_categorias cc on cc.id = d.categoria_id
						join bv_destinos_estacionales dd on dd.destino_id = d.id
						join bv_estacionales e on e.id = dd.estacional_id
						group by d.id) de','de.id = bv_paquetes.destino_id','left');
	$this->db->join('bv_categorias c', 'c.id = d.categoria_id', 'left');
	//menor precio de las combinaciones que no estén CONFIRMADAS del paquete
	$this->db->join('(select p.id, max(p.grupal) as grupal, min(pc.v_total) as precio, p.precio_usd
						from bv_paquetes p
						join bv_destinos d on d.id = p.destino_id
						join bv_categorias cc on cc.id = d.categoria_id
						join bv_destinos_estacionales dd on dd.destino_id = d.id
						join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
						group by p.id) p','p.id = bv_paquetes.id','left');
	//tipo de transporte del paquete
	$this->db->join('(select pl.paquete_id, tt.nombre as tipo, tt.clase, tt.descripcion
						from bv_transportes t
						join bv_tipos_transportes tt on tt.id =t.tipo_id
						join bv_paquetes_alojamientos pl on pl.transporte_id = t.id
						group by pl.paquete_id) t','t.paquete_id = bv_paquetes.id','left');
	//cantidad maxima de pasajeros que admite
	$this->db->join('(select pc.paquete_id, af.fecha_checkin, af.fecha_checkout, max(h.pax) as pax
								from bv_habitaciones h
								join bv_alojamientos_fechas_cupos fc on fc.habitacion_id = h.id
								join bv_alojamientos_fechas af on af.id = fc.fecha_id
								join bv_paquetes_alojamientos pq on pq.fecha_alojamiento_id = af.id
								join bv_paquetes_combinaciones pc on pc.fecha_alojamiento_id = af.id and pq.paquete_id = pc.paquete_id and fc.id = pc.fecha_alojamiento_cupo_id
								group by pc.paquete_id) x','x.paquete_id = bv_paquetes.id','left');
  }

  function getBySlug($slug) {
    $this->db->select("pa.*, o.nombre as operador, o.razonsocial, o.legajo,
						d.imagen, d.imagen as imagen_mobile, d.descripcion as destino_descripcion, d.categoria_id, c.nombre as categoria,
						ifnull(de.estacional,'') as estacional,
						ifnull(pe.paquete_estacional,'') as paquete_estacional,
						ifnull(p.precio,0) as precio,
						ifnull(p.grupal,0) as grupal,
						ifnull(p.precio_usd,0) as precio_usd,
						ifnull(t.tipo,'') as tipo_transporte,
						ifnull(t.descripcion,'') as descripcion_transporte,
						ifnull(t.clase,'') as clase_transporte,
						ifnull(x.fecha_checkin,'') as fecha_checkin,
						ifnull(x.fecha_checkout,'') as fecha_checkout,
						ifnull(x.pax,'') as pax");
	$this->db->join('bv_operadores o', 'o.id = pa.operador_id', 'left');
	$this->db->join('bv_destinos d', 'd.id = pa.destino_id', 'left');
	//obtengo subcategoria estacional del destino
	$this->db->join('(select d.id, e.nombre as estacional
						from bv_destinos d
						join bv_categorias cc on cc.id = d.categoria_id
						join bv_destinos_estacionales dd on dd.destino_id = d.id
						join bv_estacionales e on e.id = dd.estacional_id
						group by d.id) de','de.id = pa.destino_id','left');
	//obtengo subcategoria estacional del PAQUETE por si tiene una propia definida
	$this->db->join('(select d.id, e.nombre as paquete_estacional
						from bv_paquetes d
						join bv_paquetes_estacionales dd on dd.paquete_id = d.id
						join bv_estacionales e on e.id = dd.estacional_id
						group by d.id) pe','pe.id = pa.id','left');
	$this->db->join('bv_categorias c', 'c.id = d.categoria_id', 'left');
	//menor precio de las combinaciones que no estén CONFIRMADAS del paquete
	$this->db->join('(select p.id, max(p.grupal) as grupal, min(pc.v_total) as precio, p.precio_usd
						from bv_paquetes p
						join bv_destinos d on d.id = p.destino_id
						join bv_categorias cc on cc.id = d.categoria_id
						join bv_destinos_estacionales dd on dd.destino_id = d.id
						join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
						group by p.id) p','p.id = pa.id','left');
	//tipo de transporte del paquete
	$this->db->join('(select pl.paquete_id, tt.nombre as tipo, tt.clase, tt.descripcion
						from bv_transportes t
						join bv_tipos_transportes tt on tt.id =t.tipo_id
						join bv_paquetes_alojamientos pl on pl.transporte_id = t.id
            join bv_paquetes_combinaciones pc on pc.paquete_id = pl.paquete_id and pc.alojamiento_id =pl.alojamiento_id and pc.transporte_id =pl.transporte_id
               and pl.fecha_alojamiento_id = pc.fecha_alojamiento_id and pc.fecha_transporte_id =pl.fecha_transporte_id
						group by pl.paquete_id) t','t.paquete_id = pa.id','left');
	//cantidad maxima de pasajeros que admite
	$this->db->join('(select pc.paquete_id, af.fecha_checkin, af.fecha_checkout, max(h.pax) as pax
								from bv_habitaciones h
								join bv_alojamientos_fechas_cupos fc on fc.habitacion_id = h.id
								join bv_alojamientos_fechas af on af.id = fc.fecha_id
								join bv_paquetes_alojamientos pq on pq.fecha_alojamiento_id = af.id
								join bv_paquetes_combinaciones pc on pc.fecha_alojamiento_id = af.id and pq.paquete_id = pc.paquete_id and fc.id = pc.fecha_alojamiento_cupo_id
								group by pc.paquete_id) x','x.paquete_id = pa.id','left');
	$this->db->where('pa.slug = "'.$slug.'"');
	$this->db->where('pa.visible = 1');
	$this->db->where('pa.activo = 1');

	return $this->db->get('bv_paquetes pa')->row();
  }

  function getDocumentaciones($paquete_id) {
    $res = $this->db->get_where('bv_paquetes_documentaciones', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->documentacion_id;
    }
    return $arr;
  }

  function clearDocumentaciones($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_documentaciones');
  }

  function addDocumentacion($paquete_id, $documentacion_id) {
    $this->db->insert('bv_paquetes_documentaciones', array(
      'paquete_id' => $paquete_id,
      'documentacion_id' => $documentacion_id
    ));
  }

  function getCaracteristicas($paquete_id) {
    $res = $this->db->get_where('bv_paquetes_caracteristicas', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->caracteristica_id;
    }
    return $arr;
  }

  function getCaracteristicasData($paquete_id) {
    $this->db->select('pc.*, c.nombre',false);
    $this->db->join('bv_caracteristicas c', 'c.id = pc.caracteristica_id');
    $res = $this->db->get_where('bv_paquetes_caracteristicas pc', array('pc.paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = array('id' => $r->caracteristica_id, 'nombre' => $r->nombre);
    }
    return $arr;
  }

  function clearCaracteristicas($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_caracteristicas');
  }

  function addCaracteristica($paquete_id, $caracteristica_id) {
    $this->db->insert('bv_paquetes_caracteristicas', array(
      'paquete_id' => $paquete_id,
      'caracteristica_id' => $caracteristica_id
    ));
  }

  function getExcursiones($paquete_id) {
    $this->db->order_by('id','asc');
    $res = $this->db->get_where('bv_paquetes_excursiones', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->excursion_id;
    }
    return $arr;
  }

  function getExcursionesData($paquete_id) {
    $this->db->select('bv_paquetes_excursiones.*,e.nombre',false);
    $this->db->order_by('id','asc');
    $this->db->join('bv_excursiones_destinos d','d.excursion_id = bv_paquetes_excursiones.excursion_id','left');
    $this->db->join('bv_excursiones e','e.id = bv_paquetes_excursiones.excursion_id');
    $this->db->group_by('bv_paquetes_excursiones.excursion_id');
    $res = $this->db->get_where('bv_paquetes_excursiones', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = array('id' => $r->excursion_id, 'nombre' => $r->nombre);
    }
    return $arr;
  }

  function clearExcursiones($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_excursiones');
  }

  function addExcursion($paquete_id, $excursion_id) {
    $this->db->insert('bv_paquetes_excursiones', array(
      'paquete_id' => $paquete_id,
      'excursion_id' => $excursion_id
    ));
  }

  function getMedios($paquete_id) {
    $res = $this->db->get_where('bv_paquetes_medios', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->medio_id;
    }
    return $arr;
  }

  function clearMedios($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_medios');
  }

  function addMedio($paquete_id, $medio_id) {
    $this->db->insert('bv_paquetes_medios', array(
      'paquete_id' => $paquete_id,
      'medio_id' => $medio_id
    ));
  }

  function getPromociones($paquete_id) {
    $res = $this->db->get_where('bv_paquetes_promociones', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->promocion_id;
    }
    return $arr;
  }

  function clearPromociones($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_promociones');
  }

  function addPromocion($paquete_id, $promocion_id) {
    $this->db->insert('bv_paquetes_promociones', array(
      'paquete_id' => $paquete_id,
      'promocion_id' => $promocion_id
    ));
  }

  function getRegimenes($paquete_id) {
    $this->db->select('r.*, af.alojamiento_id');
    $this->db->join('bv_alojamientos_fechas af', 'af.id = r.fecha_alojamiento_id');
    $res = $this->db->get_where('bv_paquetes_regimenes r', array('r.paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = array($r->id,$r->regimen_id,$r->alojamiento_id,$r->fecha_alojamiento_id);
    }
    return $arr;
  }

  function clearRegimenes($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_regimenes');
  }

  function addRegimen($paquete_id, $regimen_id) {
    $this->db->insert('bv_paquetes_regimenes', array(
      'paquete_id' => $paquete_id,
      'regimen_id' => $regimen_id
    ));
  }

  //devulve data alojamiento con transporte
  function getAlojamientos($paquete_id) {
    $this->db->where('pa.transporte_id > 0 and pa.fecha_transporte_id > 0');
    $this->db->join('bv_transportes_fechas tf','tf.id = pa.fecha_transporte_id');
	$this->db->join('bv_alojamientos_fechas af', 'af.id = pa.fecha_alojamiento_id');
	$res = $this->db->get_where('bv_paquetes_alojamientos pa', array('pa.paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
		$arr[] = array($r->alojamiento_id,$r->transporte_id,$r->fecha_alojamiento_id,$r->fecha_transporte_id);
    }
    return $arr;
  }

  //devulve data de habitacion, cupo y alojamiento_id
  function getHabitaciones($paquete_id) {
    $this->db->distinct();
    $this->db->select('f.alojamiento_id, fc.*');
    $this->db->join('bv_alojamientos_fechas f','f.id = fc.fecha_id');
	$this->db->join('bv_alojamientos a','a.id = f.alojamiento_id');
	$this->db->join('bv_paquetes_alojamientos pa','pa.alojamiento_id = a.id and pa.paquete_id = '.$paquete_id.' and pa.fecha_alojamiento_id = fc.fecha_id');
	$this->db->join('bv_paquetes p','p.id = pa.paquete_id');
	$this->db->join('bv_habitaciones h','h.id = fc.habitacion_id');
	$this->db->where('f.fecha_checkin between p.fecha_inicio and p.fecha_fin and
					f.fecha_checkout between p.fecha_inicio and p.fecha_fin
					and f.alojamiento_id > 0 and fc.habitacion_id > 0');
    $res = $this->db->get('bv_alojamientos_fechas_cupos fc')->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = array($r->id,$r->habitacion_id,$r->alojamiento_id,$r->fecha_id);
    }
    return $arr;
  }

  function getLugares($paquete_id) {
    $this->db->where('lugar_id > 0');
    $res = $this->db->get_where('bv_paquetes_lugares', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->lugar_id;
    }
    return $arr;
  }

  function clearLugares($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_lugares');
  }

  function addLugar($paquete_id, $lugar_id) {
    $this->db->insert('bv_paquetes_lugares', array(
      'paquete_id' => $paquete_id,
      'lugar_id' => $lugar_id
    ));
  }

  function getCupoHabitaciones($paquete_id) {
    $this->db->select('ifnull(sum(fc.cupo),0) as cantidad');
    $this->db->join('bv_alojamientos_fechas f','f.id = fc.fecha_id');
	$this->db->join('bv_alojamientos a','a.id = f.alojamiento_id');
	$this->db->join('bv_paquetes_alojamientos pa','pa.alojamiento_id = a.id and pa.paquete_id = '.$paquete_id);
	$this->db->join('bv_paquetes p','p.id = pa.paquete_id');
	$this->db->where('f.fecha_checkin between p.fecha_inicio and p.fecha_fin and
					f.fecha_checkout between p.fecha_inicio and p.fecha_fin');
    return $this->db->get('bv_alojamientos_fechas_cupos fc')->row()->cantidad;
  }

  function getCupoTransportes($paquete_id) {
    $this->db->select('ifnull(sum(tf.cupo),0) as cantidad');
	$this->db->join('bv_transportes_fechas tf','tf.id = pa.fecha_transporte_id');
	$res = $this->db->get_where('bv_paquetes_alojamientos pa', array('paquete_id' => $paquete_id))->row()->cantidad;
  }

  function getDataCaracteristicas($paquete_id) {
    $this->db->select('c.*');
    $this->db->join('bv_paquetes p','p.id = pc.paquete_id and p.id = '.$paquete_id);
    $this->db->join('bv_caracteristicas c','c.id = pc.caracteristica_id');
    $this->db->group_by('c.id');
	#$this->db->order_by('c.orden asc');
    $this->db->order_by('pc.id asc');
  return $this->db->get_where('bv_paquetes_caracteristicas pc')->result();
  }

  function getDataDocumentaciones($paquete_id) {
    $this->db->select('c.*');
    $this->db->join('bv_paquetes p','p.id = pc.paquete_id and p.id = '.$paquete_id);
    $this->db->join('bv_documentaciones c','c.id = pc.documentacion_id');
    $this->db->group_by('c.id');
    $this->db->order_by('c.nombre asc');
	return $this->db->get_where('bv_paquetes_documentaciones pc')->result();
  }

  function getDataMedios($paquete_id) {
    $this->db->select('c.*');
    $this->db->join('bv_paquetes p','p.id = pc.paquete_id and p.id = '.$paquete_id);
    $this->db->join('bv_medios_pago c','c.id = pc.medio_id');
    $this->db->group_by('c.id');
    $this->db->order_by('c.orden asc');
	return $this->db->get_where('bv_paquetes_medios pc', array('paquete_id' => $paquete_id))->result();
  }

  function getDataPromociones($paquete_id) {
    $this->db->select('c.*');
    $this->db->join('bv_paquetes p','p.id = pc.paquete_id and p.id = '.$paquete_id);
    $this->db->join('bv_promociones c','c.id = pc.promocion_id');
    $this->db->group_by('c.id');
    $this->db->order_by('c.orden asc');
	return $this->db->get_where('bv_paquetes_promociones pc', array('paquete_id' => $paquete_id))->result();
  }

  function getDataExcursiones($paquete_id) {
    $this->db->select('c.*');
    $this->db->join('bv_paquetes p','p.id = pc.paquete_id');
    $this->db->join('bv_excursiones_destinos d','d.excursion_id = pc.excursion_id','left');
    $this->db->join('bv_excursiones c','c.id = pc.excursion_id');
    $this->db->group_by('c.id');
    $this->db->order_by('pc.id asc');
	return $this->db->get_where('bv_paquetes_excursiones pc', array('paquete_id' => $paquete_id))->result();
  }

  function getDataHabitaciones($paquete_id){
    $this->db->select('h.*');
    $this->db->join('bv_paquetes_combinaciones p','p.id = pc.paquete_id');
    $this->db->join('bv_paquetes p','p.id = pc.paquete_id');
    $this->db->join('bv_excursiones c','c.id = d.excursion_id');
    $this->db->group_by('c.id');
    $this->db->order_by('c.orden asc');
	return $this->db->get_where('bv_paquetes_excursiones pc', array('paquete_id' => $paquete_id))->result();
  }

  /*function getCoordinadores($id){
		$this->db->select("p.*,
							v1.nombre as c1_nombre, v1.apellido as c1_apellido, v1.fechaNacimiento as c1_fechaNacimiento,  v1.nacionalidad as c1_nacionalidad,  v1.dniTipo as c1_dniTipo,  v1.dni as c1_dni,
							v2.nombre as c2_nombre, v2.apellido as c2_apellido, v2.fechaNacimiento as c2_fechaNacimiento,  v2.nacionalidad as c2_nacionalidad,  v2.dniTipo as c2_dniTipo,  v2.dni as c2_dni,
							v3.nombre as c3_nombre, v3.apellido as c3_apellido, v3.fechaNacimiento as c3_fechaNacimiento,  v3.nacionalidad as c3_nacionalidad,  v3.dniTipo as c3_dniTipo,  v3.dni as c3_dni
						",false);

		$this->db->join("bv_vendedores v1","v1.id = p.coordinador_id_1","left");
		$this->db->join("bv_vendedores v2","v2.id = p.coordinador_id_2","left");
		$this->db->join("bv_vendedores v3","v3.id = p.coordinador_id_3","left");

		return $this->db->get_where($this->table.' p',array("p.id"=>$id));
	}*/

  function getEstacionales($paquete_id) {
    $res = $this->db->get_where('bv_paquetes_estacionales', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->estacional_id;
    }
    return $arr;
  }

  function clearEstacionales($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_estacionales');
  }

  function addEstacional($paquete_id, $estacional_id) {
    $this->db->insert('bv_paquetes_estacionales', array(
      'paquete_id' => $paquete_id,
      'estacional_id' => $estacional_id
    ));
  }

  function getCelulares($paquete_id) {
    $res = $this->db->get_where('bv_paquetes_celulares', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->celular_id;
    }
    return $arr;
  }

  function clearCelulares($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_celulares');
  }

  function addCelular($paquete_id, $celular_id) {
    $this->db->insert('bv_paquetes_celulares', array(
      'paquete_id' => $paquete_id,
      'celular_id' => $celular_id
    ));
  }

  function getCoordinadoresData($id){
    $this->db->select("v1.id,
               v1.apellido as apellido, v1.nombre as nombre,
               v1.fechaNacimiento as fechaNacimiento,   v1.dniTipo as dniTipo,  v1.dni as dni, v1.fecha_emision, v1.fecha_vencimiento, v1.pasaporte, v1.sexo, v1.dieta, v1.telefono , v1.email,
                PI.nombre as nacionalidad, 'DNI' as dniTipo, v1.emergencia_nombre, v1.emergencia_telefono_codigo,
                v1.emergencia_telefono_numero",false);

    $this->db->join("bv_paquetes_coordinadores pc","pc.paquete_id = p.id");
    $this->db->join("bv_vendedores v1","v1.id = pc.vendedor_id");
    $this->db->join('bv_paises PI','PI.id = v1.nacionalidad_id', 'left');

    if(is_array($id) && count($id)){
      $this->db->where_in("p.id",$id);
    }
    else{
      $this->db->where("p.id",$id);
    }

    return $this->db->get($this->table.' p');
  }

  function getCoordinadores($paquete_id) {
    $res = $this->db->get_where('bv_paquetes_coordinadores', array('paquete_id' => $paquete_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->vendedor_id;
    }
    return $arr;
  }

  function clearCoordinadores($paquete_id) {
    $this->db->where('paquete_id', $paquete_id);
    $this->db->delete('bv_paquetes_coordinadores');
  }

  function addCoordinador($paquete_id, $vendedor_id) {
    $this->db->insert('bv_paquetes_coordinadores', array(
      'paquete_id' => $paquete_id,
      'vendedor_id' => $vendedor_id
    ));
  }

  /*
  Devuelve el código del último paquete del destino, para calcular el correlativo
  */
  function getLastCode($destino_id){
	$this->db->limit(1);
	$this->db->order_by('pa.codigo','DESC');
	return $this->db->get_where('bv_paquetes pa', array('pa.destino_id' => $destino_id))->row();
  }

  function validar_codigo($id){
    $paquete = $this->db->query("select * from ".$this->table." where id = ".$id)->row();
    $destino = $this->db->query("select * from bv_destinos where id = ".$paquete->destino_id)->row();
    return (isset($destino->codigo) && $destino->codigo) ? $destino->codigo : false;
  }

  function duplicar($id){
	  $paquete = $this->db->query("select * from ".$this->table." where id = ".$id)->row();
	  $destino = $this->db->query("select * from bv_destinos where id = ".$paquete->destino_id)->row();

	  //nuevo codigo correlativo
	  $code = $this->getLastCode($paquete->destino_id);

    $nro = isset($code->codigo) && $code->codigo ? substr($code->codigo,3,4) : 1000;
	  $nro = (int)$nro+1;
	  $nro = zerofill($nro,4);//4 digitos el numero (relleno con 0 a la izquierda)
	  $nuevo_codigo = $destino->codigo.$nro;

	  //nueva url y codigo
	  $paquete->slug =  str_replace($paquete->codigo,$nuevo_codigo,$paquete->slug);
	  $paquete->codigo =  $nuevo_codigo;
	  $paquete->activo = 0; //el nuevo paquete arranca como INACTIVO
    $paquete->alerta_cupos_revisar = 1;//alerta para indicar que deben revisar los cupos
    $paquete->slug = eliminar_tildes(url_title($paquete->nombre.'-'.$nuevo_codigo));

	  //elimino el ID
	  unset($paquete->id);

	  $this->db->insert($this->table,$paquete);
	  $nuevo_id = $this->db->insert_id();

	  //tablas referenciales

	  //adicionales
    /*
	  $results = $this->db->query("select * from bv_paquetes_adicionales where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_adicionales',$res);
	  }
    */
	  //-------------------------------------------------------

	  //alojamientos
    /*
	  $results = $this->db->query("select * from bv_paquetes_alojamientos where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_alojamientos',$res);
	  }
    */
	  //-------------------------------------------------------

	  //caracteristicas
	  $results = $this->db->query("select * from bv_paquetes_caracteristicas where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_caracteristicas',$res);
	  }
	  //-------------------------------------------------------

	  //combinaciones
    /*
	  $results = $this->db->query("select * from bv_paquetes_combinaciones where paquete_id = ".$id)->result();
    $tra_ids = array();
    $aloj_ids = array();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_combinaciones',$res);
      $nueva_comb_id = $this->db->insert_id();

      $tra_ids[] = $res->fecha_transporte_id;
      $aloj_ids[] = $res->fecha_alojamiento_cupo_id;
	  }
    */
	  //-------------------------------------------------------

    //$tra_ids = array_unique($tra_ids);
    //$aloj_ids = array_unique($aloj_ids);

    //por cada CUPO de TRANSPORTE viejo, genero uno nuevo y se lo asocio a las nuevas combinaciones del paquete
    /*
    foreach ($tra_ids as $tf_id) {
        //obtengo registro del CUPO del TRANSPORTE
        $tf = $this->db->query("select * from bv_transportes_fechas where id = ".$tf_id)->row();
        //LO DUPLICO
        unset($tf->id);
        $this->db->insert('bv_transportes_fechas',$tf);
        $tf_nuevo_id = $this->db->insert_id();
        //actualizo el cupo de este nuevo al total
        $this->db->query('update bv_transportes_fechas set cupo = cupo_total where id = '.$tf_nuevo_id);

        //actualizo en las combinaciones del paquete NUEVO el nuevo ID del cupo de transporte
        $this->db->query('update bv_paquetes_combinaciones set fecha_transporte_id = '.$tf_nuevo_id.' where paquete_id = '.$nuevo_id.' and fecha_transporte_id = '.$tf_id);

        //actualizo en tabla de paquetes_alojamientos la referencia del fecha_transporte_id
        $this->db->query('update bv_paquetes_alojamientos set fecha_transporte_id = '.$tf_nuevo_id.' where paquete_id = '.$nuevo_id.' and fecha_transporte_id = '.$tf_id);

        //END TRANSPORTE---------------------------
    }

    //por cada CUPO de ALOJAMIENTO viejo, genero uno nuevo y se lo asocio a las nuevas combinaciones del paquete
    foreach ($aloj_ids as $af_id) {
      //obtengo registro del CUPO del ALOJAMIENTO
      $af = $this->db->query("select * from bv_alojamientos_fechas_cupos where id = ".$af_id)->row();
      //LO DUPLICO
      unset($af->id);
      $this->db->insert('bv_alojamientos_fechas_cupos',$af);
      $af_nuevo_id = $this->db->insert_id();
      //actualizo el cupo de este nuevo al total
      if($af->habitacion_id != 99){
        //si NO es compartida, el CUPO es el TOTAL
        $this->db->query('update bv_alojamientos_fechas_cupos set cupo = cupo_total where id = '.$af_nuevo_id);
      }

      //actualizo en la combinacion el nuevo ID del cupo
      $this->db->query('update bv_paquetes_combinaciones set fecha_alojamiento_cupo_id = '.$af_nuevo_id.' where paquete_id = '.$nuevo_id.' and fecha_alojamiento_cupo_id = '.$af_id);


      //END ALOJAMIENTO---------------------------
    }
    */

	  //documentaciones
	  $results = $this->db->query("select * from bv_paquetes_documentaciones where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_documentaciones',$res);
	  }
	  //-------------------------------------------------------

	  //estacionales
	  $results = $this->db->query("select * from bv_paquetes_estacionales where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_estacionales',$res);
	  }
	  //-------------------------------------------------------

	  //excursiones
	  $results = $this->db->query("select * from bv_paquetes_excursiones where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_excursiones',$res);
	  }
	  //-------------------------------------------------------

	  //habitaciones
	  $results = $this->db->query("select * from bv_paquetes_habitaciones where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_habitaciones',$res);
	  }
	  //-------------------------------------------------------

	  //lugares
	  $results = $this->db->query("select * from bv_paquetes_lugares where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_lugares',$res);
	  }
	  //-------------------------------------------------------

	  //medios
	  $results = $this->db->query("select * from bv_paquetes_medios where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_medios',$res);
	  }
	  //-------------------------------------------------------

	  //paradas
	  $results = $this->db->query("select * from bv_paquetes_paradas where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_paradas',$res);
	  }
	  //-------------------------------------------------------

	  //regimenes
	  /*
    $results = $this->db->query("select * from bv_paquetes_regimenes where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_regimenes',$res);
	  }
    */
	  //-------------------------------------------------------

	  //promociones
	  $results = $this->db->query("select * from bv_paquetes_promociones where paquete_id = ".$id)->result();
	  foreach($results as $res){
		  $res->paquete_id = $nuevo_id;
		  unset($res->id);

		  $this->db->insert('bv_paquetes_promociones',$res);
	  }
	  //-------------------------------------------------------

	  return $nuevo_id;
  }

  function puedeBorrarCombinaciones($paquete_id) {
    $ordenes = $this->db->get_where('bv_ordenes', array('paquete_id' => $paquete_id, 'vencida' => 0))->result();
    if (count($ordenes)) {
      return FALSE;
    }

    $reservas = $this->db->get_where('bv_reservas', array('paquete_id' => $paquete_id))->result();
    if (count($reservas)) {
      return FALSE;
    }

    return TRUE;
  }

  //22-04-19 busco paquetes que coincidan con los filtros aplicados
  function buscarViajes($filters=[],$limit=5,$offset=0) {
			$this->db->distinct();
			$this->db->select($this->table.'.*, d.slug as destino_slug, ca.slug as categoria_slug, d.imagen, d.nombre as destino, group_concat(distinct est.nombre) as estacionales, x.fecha_checkin, x.fecha_checkout, x.fecha_salida, x.fecha_regreso, x.tipo_id, x.transporte, ifnull(mins.impuestos,0)+(case when bv_paquetes.exterior=1 then ifnull(bv_paquetes.impuesto_pais,0) else 0 end) as precio_impuestos, ifnull(mins.total,0) as precio_total,
        (case when bv_paquetes.cupo_paquete_personalizado = 1
                then (case when bv_paquetes.cupo_paquete_disponible>0 then 1 else 0 end)
                else (case when bv_paquetes.cupo_disponible>0 then 1 else 0 end)
            end) as con_precio',false);

      $this->db->join('bv_destinos d', 'd.id = '.$this->table.'.destino_id', 'left');
      $this->db->join('bv_categorias ca', 'ca.id = d.categoria_id', 'left');
      $this->db->join('bv_paquetes_estacionales pe','pe.paquete_id = '.$this->table.'.id','left');
      $this->db->join('bv_estacionales est','est.id = pe.estacional_id','left');

      //fechas de checkin y checkout
      $this->db->join('(select pc.paquete_id, af.fecha_checkin, af.fecha_checkout, tf.fecha_salida, tf.fecha_regreso, t.tipo_id, t.nombre as transporte
                from bv_alojamientos_fechas_cupos fc
                join bv_alojamientos_fechas af on af.id = fc.fecha_id
                join bv_paquetes_alojamientos pq on pq.fecha_alojamiento_id = af.id
                join bv_paquetes_combinaciones pc on pc.fecha_alojamiento_id = af.id and pq.paquete_id = pc.paquete_id and fc.id = pc.fecha_alojamiento_cupo_id
                join bv_transportes_fechas tf on tf.id = pc.fecha_transporte_id
                join bv_transportes t on t.id = pc.transporte_id
                group by pc.paquete_id) x','x.paquete_id = '.$this->table.'.id','left');

      $this->db->join('(SELECT paquete_id, (v_iva21+v_iva10+v_otros_imp) as impuestos, min(v_total-v_iva21-v_iva10-v_otros_imp) as total FROM bv_paquetes_combinaciones GROUP BY paquete_id) mins','mins.paquete_id = '.$this->table.'.id','left');

      //filtro destino
      if(isset($filters['destino_id']) && $filters['destino_id']){
        $this->db->where('d.id = '.$filters['destino_id']);
      }

      //filtro por region
      if(isset($filters['region_id']) && $filters['region_id']){
        $this->db->where('ca.id = '.$filters['region_id']);
      }

      //filtro de fecha (año-mes)
      if(isset($filters['fecha']) && $filters['fecha']){
        $desde = $filters['fecha'].'-01';
        $hasta = $filters['fecha'].'-31';
        $this->db->where('bv_paquetes.fecha_inicio BETWEEN "'.$desde.'" AND "'.$hasta.'"');
      }

      //filtro categoria estacional
      if(isset($filters['categoria_id']) && $filters['categoria_id']){
        $this->db->where('est.id = '.$filters['categoria_id']);
      }

      //destinos publicados y paquetes activos
      $this->db->where('d.publicado = 1 and '.$this->table.'.activo = 1 and '.$this->table.'visible = 1 and '.$this->table.'.fecha_inicio >= NOW()');
      $this->db->limit($limit);
      $this->db->offset($offset);

      //agrupador es el destino
      $this->db->group_by($this->table.'.id');

      #$this->db->order_by('d.nombre asc, '.$this->table.'.nombre asc');
      /*$this->db->order_by('d.nombre asc');

      //30-08-19 el nuevo orden es los mas proximos primero
      $this->db->order_by($this->table.'.fecha_inicio','asc');
      $this->db->order_by('con_precio','desc');
      $this->db->order_by('precio_total','asc');*/

      //30-08-19 el nuevo orden es los mas proximos primero
      $this->db->order_by($this->table.'.fecha_inicio','asc');
      $this->db->order_by('con_precio','desc');
      $this->db->order_by('precio_total','asc');

			$this->db->get($this->table);

      return $this->db->get($this->table)->result();
  }

  //6/12/19 busco destinos que coincidan con los filtros aplicados
  function buscarDestinos($filters=[],$limit=5,$offset=0) {
      $this->db->distinct();
      $this->db->select($this->table.'.*, d.descripcion as destino_descripcion, d.slug as destino_slug, ca.slug as categoria_slug, d.imagen, d.nombre as destino, group_concat(distinct est.nombre) as estacionales, x.fecha_checkin, x.fecha_checkout, x.fecha_salida, x.fecha_regreso, x.tipo_id, x.transporte, ifnull(mins.impuestos,0)+(case when bv_paquetes.exterior=1 then ifnull(bv_paquetes.impuesto_pais,0) else 0 end) as precio_impuestos, ifnull(mins.total,0) as precio_total,
        (case when bv_paquetes.cupo_paquete_personalizado = 1
                then (case when bv_paquetes.cupo_paquete_disponible>0 then 1 else 0 end)
                else (case when bv_paquetes.cupo_disponible>0 then 1 else 0 end)
            end) as con_precio',false);

      $this->db->join('bv_destinos d', 'd.id = '.$this->table.'.destino_id', 'left');
      $this->db->join('bv_categorias ca', 'ca.id = d.categoria_id', 'left');
      $this->db->join('bv_paquetes_estacionales pe','pe.paquete_id = '.$this->table.'.id','left');
      $this->db->join('bv_estacionales est','est.id = pe.estacional_id','left');

      //fechas de checkin y checkout
      $this->db->join('(select pc.paquete_id, af.fecha_checkin, af.fecha_checkout, tf.fecha_salida, tf.fecha_regreso, t.tipo_id, t.nombre as transporte
                from bv_alojamientos_fechas_cupos fc
                join bv_alojamientos_fechas af on af.id = fc.fecha_id
                join bv_paquetes_alojamientos pq on pq.fecha_alojamiento_id = af.id
                join bv_paquetes_combinaciones pc on pc.fecha_alojamiento_id = af.id and pq.paquete_id = pc.paquete_id and fc.id = pc.fecha_alojamiento_cupo_id
                join bv_transportes_fechas tf on tf.id = pc.fecha_transporte_id
                join bv_transportes t on t.id = pc.transporte_id
                group by pc.paquete_id) x','x.paquete_id = '.$this->table.'.id','left');

      $this->db->join('(SELECT paquete_id, (v_iva21+v_iva10+v_otros_imp) as impuestos, min(v_total-v_iva21-v_iva10-v_otros_imp) as total FROM bv_paquetes_combinaciones GROUP BY paquete_id) mins','mins.paquete_id = '.$this->table.'.id','left');

      //filtro por region
      if(isset($filters['region_id']) && $filters['region_id']){
        $this->db->where('ca.id = '.$filters['region_id']);
      }

      //filtro de fecha (año-mes)
      if(isset($filters['fecha']) && $filters['fecha']){
        $desde = $filters['fecha'].'-01';
        $hasta = $filters['fecha'].'-31';
        $this->db->where('bv_paquetes.fecha_inicio BETWEEN "'.$desde.'" AND "'.$hasta.'"');
      }

      //filtro categoria estacional
      if(isset($filters['categoria_id']) && $filters['categoria_id']){
        $this->db->where('est.id = '.$filters['categoria_id']);
      }

      //destinos publicados y paquetes activos
      $this->db->where('d.publicado = 1 and '.$this->table.'.activo = 1 and '.$this->table.'.visible = 1 and '.$this->table.'.fecha_inicio >= NOW()');
      $this->db->limit($limit);
      $this->db->offset($offset);

      //agrupador es el destino
      $this->db->group_by('d.id');

      //30-08-19 el nuevo orden es los mas proximos primero
      $this->db->order_by($this->table.'.fecha_inicio','asc');
      $this->db->order_by('con_precio','desc');
      $this->db->order_by('precio_total','asc');

      return $this->db->get($this->table) ? $this->db->get($this->table)->result() : [];
  }

  //22-04-19 obtengo las fechas (anio-mes) de viajes disponibles para mostrar en buscador de home
  function fechasDisponibles() {
      $this->db->distinct();
      $this->db->select('SUBSTRING('.$this->table.'.fecha_inicio, 1, 7) as fecha',false);

      $this->db->join('bv_destinos d', 'd.id = '.$this->table.'.destino_id', 'left');
      $this->db->join('bv_paquetes_estacionales pe','pe.paquete_id = '.$this->table.'.id','left');
      $this->db->join('bv_estacionales est','est.id = pe.estacional_id','left');

      //destinos publicados y paquetes activos
      $this->db->where('d.publicado = 1 and '.$this->table.'.activo = 1 and '.$this->table.'.fecha_inicio >= NOW()');
      $this->db->order_by('1 asc');

      return $this->db->get($this->table)->result();
  }


  function getDataEstacionales($paquete_id) {
      $this->db->select('e.*',false);
      $this->db->join('bv_estacionales e','e.id = bv_paquetes_estacionales.estacional_id');
      return $this->db->get_where('bv_paquetes_estacionales', array('paquete_id' => $paquete_id))->result();
  }

  function getAllConReservas_mailings(){
    $this->db->distinct();
    $this->db->select("P.*, concat(P.codigo,' ',P.nombre) as paquete");
    $this->db->join("bv_reservas R","R.paquete_id = P.id");
    $this->db->where("P.activo = 1 or (P.activo = 0 and P.fecha_fin > date(now()))");
    $this->db->order_by("P.nombre","asc");
    $this->db->order_by("P.fecha_inicio","asc");
    return $this->db->get_where($this->table." P");
  }


  function getCelularesData($paquete_id) {
    $this->db->select('c.*',false);
    $this->db->join('bv_celulares c','c.id = pc.celular_id');
    $this->db->where('pc.paquete_id = ',$paquete_id);
    return $this->db->get('bv_paquetes_celulares pc')->result();
  }

}
