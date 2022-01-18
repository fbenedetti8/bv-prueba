<?php
class Destino_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_destinos";
    $this->indexable = array('bv_destinos.nombre','bv_destinos.codigo');
    $this->fk = "id";
    $this->pk = "id";
    $this->defaultSort = 'orden';
  }

  function onGetAll() {
    $this->db->select('bv_destinos.*, c.nombre as categoria, ifnull(le.cantidad,0) as lista_espera');
    $this->db->join('bv_categorias c', 'c.id = bv_destinos.categoria_id', 'left');
	//join a lista de espera del destino
	$this->db->join('(select tipo_id, count(*) as cantidad
							from bv_lista_espera  
							where tipo = "destino"
							group by tipo_id) le','le.tipo_id = bv_destinos.id','left');
  }
  
  function getPorOperador($operador_id) {
		$this->db->distinct();
		$this->db->select('d.*');
		$this->db->join('bv_paquetes p', 'p.destino_id = d.id and p.operador_id = '.$operador_id);
		return $this->db->get($this->table.' d');
  }
  
  function getBySlug($slug) {
    $this->db->select('bv_destinos.*, 
						c.nombre as categoria,
						ifnull(p.precio,0) as precio, 
						ifnull(p.grupal,0) as grupal, 
						ifnull(p.precio_usd,0) as precio_usd');						
	$this->db->join('bv_categorias c', 'c.id = bv_destinos.categoria_id', 'left');
	//menor precio de las combinaciones que no estén CONFIRMADAS del paquete
	$this->db->join('(select p.destino_id, max(p.grupal) as grupal, min(pc.v_total) as precio, p.precio_usd 
						from bv_paquetes p 
						join bv_destinos d on d.id = p.destino_id
						join bv_categorias cc on cc.id = d.categoria_id
						join bv_destinos_estacionales dd on dd.destino_id = d.id
						join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
						group by p.destino_id) p','p.destino_id = bv_destinos.id','left');
	$this->db->where('bv_destinos.slug = "'.$slug.'"');
	return $this->db->get('bv_destinos')->row();
  }
  
  function getEstacionales($destino_id) {
    $res = $this->db->get_where('bv_destinos_estacionales', array('destino_id' => $destino_id))->result();

    $arr = [];
    foreach ($res as $r) {
      $arr[] = $r->estacional_id;
    }
    return $arr;
  }

  function clearEstacionales($destino_id) {
    $this->db->where('destino_id', $destino_id);
    $this->db->delete('bv_destinos_estacionales');
  }

  function addEstacional($destino_id, $estacional_id) {
    $this->db->insert('bv_destinos_estacionales', array(
      'destino_id' => $destino_id,
      'estacional_id' => $estacional_id
    ));
  }

  function getPrecioDesde($destino_id,$grupales=0) {
    $COTIZACION_USD = $this->db->select('cotizacion_dolar')->get('bv_config')->row()->cotizacion_dolar;

    $q = "SELECT precio_usd, total, impuestos,  precio_anterior_neto, precio_anterior_impuestos, imagen, slug, nombre 
          FROM
          (
            SELECT d.categoria_id, p.destino_id, p.precio_usd, p.grupal, mins.total, mins.impuestos+(case when p.exterior=1 then ifnull(p.impuesto_pais,0) else 0 end) as impuestos, case when p.precio_usd = 1 then mins.total * $COTIZACION_USD else mins.total end as total_ars, p.precio_anterior_neto, p.precio_anterior_impuestos, d.imagen, d.slug, d.nombre
            FROM bv_paquetes p, bv_destinos d,
                  (SELECT paquete_id, (v_iva21+v_iva10+v_otros_imp) as impuestos, min(v_total-v_iva21-v_iva10-v_otros_imp) as total FROM bv_paquetes_combinaciones GROUP BY paquete_id) mins
            WHERE p.id = mins.paquete_id and d.id = p.destino_id and p.activo = 1 and p.fecha_inicio >= current_date() 
          ) op
          WHERE destino_id =". $destino_id;
		  
		if ($grupales) {
    	$q.= " and grupal = 1";
    }
	
    $q .= " ORDER BY op.total_ars
           LIMIT 1";

		$query = $this->db->query($q);

		return $query ? $query->row() : null; 
  }
  
  function getPorEstacionalGrupales($estacional_id){
		$this->db->select('d.*, d.descripcion as subtitulo,
								ifnull(p.grupal,0) as grupal, 
								c.slug as categoria_slug,
								ifnull(co.cantidad,0) as disponibles');
		$this->db->join('bv_destinos_estacionales de','d.id = de.destino_id');
		$this->db->join('bv_estacionales e','e.id = de.estacional_id and e.id = '.$estacional_id);
		$this->db->join('bv_categorias c','c.id = d.categoria_id');
		//averiguar si hay algun paquete que sea grupal
		$this->db->join('(  select p.destino_id, max(p.grupal) as grupal 
                        from bv_paquetes p where p.grupal = 1
							          group by p.destino_id
                      ) p','p.destino_id = d.id');
		//join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
		$this->db->join('(select p.destino_id, count(distinct pc.id) as cantidad
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
								where p.grupal = 1
								group by p.destino_id) co','co.destino_id = d.id','left');
		$this->db->group_by('d.id');
		$this->db->order_by('d.orden','asc');
		$destinos = $this->db->get('bv_destinos d')->result();

    foreach ($destinos as $d) {
      $precio_minimo = $this->getPrecioDesde($d->id,$grupal=1);
      $d->precio = @$precio_minimo->total;
      $d->impuestos = @$precio_minimo->impuestos;
      $d->precio_usd = @$precio_minimo->precio_usd;
      $d->precio_anterior_neto = @$precio_minimo->precio_anterior_neto;
      $d->precio_anterior_impuestos = @$precio_minimo->precio_anterior_impuestos;
    }

    return $destinos;
  }
  
  function getPorEstacionalRegional($estacional_id,$regional_id){
		$this->db->select('d.*, d.descripcion as subtitulo,
								ifnull(p.grupal,0) as grupal, 
								c.slug as categoria_slug,
								ifnull(co.cantidad,0) as disponibles');
		$this->db->join('bv_destinos_estacionales de','d.id = de.destino_id');
		$this->db->join('bv_estacionales e','e.id = de.estacional_id and e.id = '.$estacional_id);
		$this->db->join('bv_categorias c','c.id = d.categoria_id and c.id = '.$regional_id);
		//menor precio de las combinaciones que no estén CONFIRMADAS del paquete
		$this->db->join('(select p.destino_id, max(p.grupal) as grupal
							from bv_paquetes p 
							group by p.destino_id) p','p.destino_id = d.id','left');
		//join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
		$this->db->join('(select p.destino_id, count(distinct pc.id) as cantidad
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
								group by p.destino_id) co','co.destino_id = d.id','left');
		$this->db->group_by('d.id');
		$this->db->order_by('d.orden','asc');
		$this->db->where('d.publicado = 1');
		$destinos = $this->db->get('bv_destinos d')->result();

    foreach ($destinos as $d) {
      $precio_minimo = $this->getPrecioDesde($d->id);
      if ($precio_minimo) {
        $d->precio = $precio_minimo->total;
        $d->impuestos = $precio_minimo->impuestos;
        $d->precio_usd = $precio_minimo->precio_usd;
      $d->precio_anterior_neto = @$precio_minimo->precio_anterior_neto;
      $d->precio_anterior_impuestos = @$precio_minimo->precio_anterior_impuestos;
      }
    }

    return $destinos;
  }
  
  function getPorRegionalEstacional($regional_id,$estacional_id){
		$this->db->select('d.*, d.descripcion as subtitulo,
								ifnull(p.precio,0) as precio, 
								ifnull(p.grupal,0) as grupal, 
								ifnull(p.precio_usd,0) as precio_usd,
								c.slug as categoria_slug,
								ifnull(co.cantidad,0) as disponibles');
		$this->db->join('bv_destinos_estacionales de','d.id = de.destino_id and de.estacional_id = '.$estacional_id);
		$this->db->join('bv_estacionales e','e.id = de.estacional_id');
		$this->db->join('bv_categorias c','c.id = d.categoria_id');
		//menor precio del paquete
		$this->db->join('(select p.destino_id, max(p.grupal) as grupal, min(p.v_total-p.v_iva21-p.v_iva10-p.v_otros_imp) as precio, p.precio_usd 
							from bv_paquetes p 
							join bv_destinos d on d.id = p.destino_id
							join bv_categorias cc on cc.id = d.categoria_id
							join bv_destinos_estacionales dd on dd.destino_id = d.id
							join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
							group by p.destino_id) p','p.destino_id = d.id','left');
		//join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
		$this->db->join('(select p.destino_id, count(distinct pc.id) as cantidad
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
								group by p.destino_id) co','co.destino_id = d.id','left');
		$this->db->group_by('d.id');
		$this->db->order_by('d.orden','asc');
		$destinos = $this->db->get_where('bv_destinos d',array('d.categoria_id' => $regional_id))->result();
		
		 foreach ($destinos as $d) {
		  $precio_minimo = $this->getPrecioDesde($d->id);
		  if ($precio_minimo) {
			$d->precio = $precio_minimo->total;
			$d->precio_usd = $precio_minimo->precio_usd;
		      $d->precio_anterior_neto = @$precio_minimo->precio_anterior_neto;
		      $d->precio_anterior_impuestos = @$precio_minimo->precio_anterior_impuestos;
		  }
		}
  
		return $destinos;

  }
  
  //obtiene las caracteristicas de todos los paquetes de dicho destino_id
  function getCaracteristicas($destino_id) {
    $this->db->select('c.*');
    $this->db->join('bv_paquetes p','p.id = pc.paquete_id');
    $this->db->join('bv_destinos d','d.id = p.destino_id and d.id = '.$destino_id);
    $this->db->join('bv_caracteristicas c','c.id = pc.caracteristica_id');
    $this->db->group_by('c.id');
    $this->db->order_by('c.orden asc');
	return $this->db->get_where('bv_paquetes_caracteristicas pc')->result();
  }
  
  //obtiene los paquetes de la subcategoria estacional
  function getPaquetesPorEstacional($destino_id,$estacional_id) {
    $this->db->select("pa.id, pa.nombre, pa.nombre_visible, pa.slug, pa.fecha_inicio, pa.fecha_fin, 
						pa.cupo_disponible, pa.cupo_total, pa.fecha_indefinida, pa.confirmacion_inmediata, pa.precio_anterior_neto, pa.precio_anterior_impuestos, 
						xx.fecha_salida, xx.fecha_regreso,
						pa.cupo_paquete_personalizado, pa.cupo_paquete_total, pa.cupo_paquete_disponible, pa.cupo_paquete_disponible_real, 
						(case when pa.cupo_paquete_personalizado = 1 
								then (case when cupo_paquete_disponible>0 then 1 else 0 end) 
								else (case when cupo_disponible>0 then 1 else 0 end)  
						end) as con_precio, 
						ifnull(p.precio,0) as precio, 
						ifnull(p.impuestos,0)+(case when p.exterior=1 then ifnull(p.impuesto_pais,0) else 0 end) as impuestos, 
						ifnull(p.grupal,0) as grupal, 
						ifnull(p.precio_usd,0) as precio_usd,
						ifnull(s.lugar,'') as lugar_salida,
						ifnull(s.referencialugar,'') as referencialugar,
						ifnull(s.nombrelugar,'') as nombrelugar_salida,
						ifnull(t.tipo_id,'') as tipo_id,
						ifnull(t.tipo,'') as tipo_transporte,
						ifnull(t.descripcion,'') as descripcion_transporte,
						ifnull(t.clase,'') as clase_transporte,
						ifnull(x.fecha_checkin,'') as fecha_checkin,
						ifnull(x.fecha_checkout,'') as fecha_checkout,
						ifnull(x.pax,'') as pax,
						ifnull(co.cantidad,0) as disponibles,
						ifnull(cc.precio,0) as combinaciones_precio, 
						ifnull(cc.impuestos,0) as combinaciones_impuestos, 
						ifnull(cc.precio_usd,0) as combinaciones_precio_usd,
						d.imagen, pa.destino_id, pa.imagen_listado,
						d.slug as destino_slug, pa.detalle_inicio");
    $this->db->join('bv_destinos d','d.id = pa.destino_id');
    $this->db->join('bv_categorias ca','ca.id = d.categoria_id');
    //$this->db->join('bv_destinos_estacionales de','de.destino_id = d.id and de.estacional_id = '.$estacional_id);
    //las subcategorias son por paquete
	$this->db->join('bv_paquetes_estacionales pe','pe.paquete_id = pa.id and pe.estacional_id = '.$estacional_id);
	//menor precio de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
    $this->db->join('(select pc.id as combinacion_id, p.id, max(p.grupal) as grupal, (pc.v_iva21+pc.v_iva10+pc.v_otros_imp) as impuestos, min(pc.v_total-pc.v_iva21-pc.v_iva10-pc.v_otros_imp) as precio, p.precio_usd, p.exterior, ifnull(p.impuesto_pais,0) as impuesto_pais
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0 
								where p.activo = 1 and p.fecha_inicio >= current_date()
								group by p.id) p','p.id = pa.id','left');
	//traigo los lugares de salidas para este paquete
	$this->db->join('(select pl.paquete_id, group_concat(substr(l.referencia,1,3)) as referencialugar, l.nombre as nombrelugar, group_concat(substr(l.nombre,1,3)) as lugar
								from bv_lugares_salida l
								join bv_paquetes_lugares pl on pl.lugar_id = l.id 
								group by pl.paquete_id) s','s.paquete_id = pa.id','left');
	//tipo de transporte del paquete
	$this->db->join('(select pl.paquete_id, tt.nombre as tipo, tt.clase, tt.descripcion, t.tipo_id
						from bv_transportes t
						join bv_tipos_transportes tt on tt.id =t.tipo_id
						join bv_paquetes_alojamientos pl on pl.transporte_id = t.id 
					 	join bv_paquetes_combinaciones pc on pc.paquete_id = pl.paquete_id and pc.alojamiento_id =pl.alojamiento_id and pc.transporte_id =pl.transporte_id and pl.fecha_alojamiento_id = pc.fecha_alojamiento_id and pc.fecha_transporte_id =pl.fecha_transporte_id
           				group by pl.paquete_id) t','t.paquete_id = pa.id','left');
	//fecha de checkin y checkout del paquete y cantidad de pasajeros que admite
	$this->db->join('(select pq.paquete_id, af.fecha_checkin, af.fecha_checkout, group_concat(distinct h.pax) as pax 
								from bv_habitaciones h
								join bv_alojamientos_fechas_cupos fc on fc.habitacion_id = h.id
								join bv_alojamientos_fechas af on af.id = fc.fecha_id
								join bv_paquetes_alojamientos pq on pq.fecha_alojamiento_id = af.id
								group by pq.paquete_id) x','x.paquete_id = pa.id','left');
	//join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
    $this->db->join('(select p.id, count(distinct pc.id) as cantidad
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
								where p.activo = 1 and p.fecha_inicio >= current_date()
								group by p.id) co','co.id = pa.id','left');
	//menor precio de TODAS las combinaciones de cada paquete (este se muestra cuando TODAS las combinaciones están AGOTADAS)
    $this->db->join('(select pc.id as combinacion_id, p.id, max(p.grupal) as grupal, (p.v_iva21+p.v_iva10+p.v_otros_imp)+(case when p.exterior=1 then ifnull(p.impuesto_pais,0) else 0 end) as impuestos, min(pc.v_total-pc.v_iva21-pc.v_iva10-pc.v_otros_imp) as precio, p.precio_usd 
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id
								group by p.id) cc','cc.id = pa.id','left');

    //fechas de checkin y checkout
 	$this->db->join('(select pq.paquete_id, af.fecha_checkin, af.fecha_checkout, tf.fecha_salida, tf.fecha_regreso, t.tipo_id, t.nombre as transporte
                from bv_alojamientos_fechas_cupos fc 
                join bv_alojamientos_fechas af on af.id = fc.fecha_id
                join bv_paquetes_alojamientos pq on pq.fecha_alojamiento_id = af.id
                join bv_transportes_fechas tf on tf.id = pq.fecha_transporte_id 
                join bv_transportes t on t.id = pq.transporte_id 
                group by pq.paquete_id) xx','xx.paquete_id = pa.id','left');

 	//23-04-19 agregado para mostrar solo los paquetes vigentes en la intermedia
 	$this->db->where("pa.fecha_inicio >= now()");
	
	$this->db->order_by('pa.fecha_inicio','asc');
	$this->db->order_by('con_precio','desc');
	$this->db->order_by('precio','asc');
	
	return $this->db->get_where('bv_paquetes pa', array('pa.activo' =>1, 'pa.destino_id' => $destino_id))->result();
  }

  //obtiene las subcategorias estacionales del destino
  function getEstacionalesById($destino_id) {
    $this->db->select('e.*');
    $this->db->join('bv_destinos d','d.id = de.destino_id');
    $this->db->join('bv_estacionales e','e.id = de.estacional_id');
    $this->db->order_by('e.orden asc');
    return $this->db->get_where('bv_destinos_estacionales de', array('de.destino_id' => $destino_id))->result();
  }
  
  //traigo destinos recomendados de la misma categoria
  function getRecomendados($categoria_id,$destino_id,$limit=3) {
    $this->db->select('bv_destinos.*, bv_destinos.descripcion as subtitulo,
						ifnull(p.precio,0) as precio, 
						ifnull(p.impuestos,0) as impuestos, 
						ifnull(p.grupal,0) as grupal, 
						ifnull(p.precio_usd,0) as precio_usd,
						ifnull(co.cantidad,0) as disponibles,
						c.slug as categoria_slug,
						c.nombre as categoria');
    $this->db->join('bv_categorias c', 'c.id = bv_destinos.categoria_id');
	//menor precio de las combinaciones que no estén AGOTADAS de los paquetes del destino
	$this->db->join('(select p.destino_id, max(p.grupal) as grupal, (p.v_iva21+p.v_iva10+p.v_otros_imp)+(case when p.exterior=1 then ifnull(p.impuesto_pais,0) else 0 end) as impuestos, (p.v_total-p.v_iva21-p.v_iva10-p.v_otros_imp) as precio, p.precio_usd 
						from bv_paquetes p 
						join bv_destinos d on d.id = p.destino_id
						join bv_categorias cc on cc.id = d.categoria_id
						join bv_destinos_estacionales dd on dd.destino_id = d.id
						join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
						join (select p.nombre, min(p.v_total) as min_price 
						         from bv_paquetes p where p.activo = 1 and p.fecha_inicio >= current_date() group by p.nombre) x 
							 on x.nombre = p.nombre and x.min_price = p.v_total
						where p.activo = 1 and p.visible = 1 and p.fecha_inicio >= current_date()
						group by p.destino_id) p','p.destino_id = bv_destinos.id','left');
	//join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
    $this->db->join('(select p.destino_id, count(distinct pc.id) as cantidad
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
								where p.activo = 1 and p.visible = 1 and p.fecha_inicio >= current_date()
								group by p.destino_id) co','co.destino_id = bv_destinos.id','left');
	$this->db->limit($limit);
	$this->db->order_by('bv_destinos.proximamente asc, disponibles desc');
	//Maxi 10-01-19: lo saco del where: and bv_destinos.proximamente = 1 
	$this->db->where('bv_destinos.publicado = 1 and bv_destinos.id != '.$destino_id);

	$destinos = $this->db->get_where('bv_destinos',array('bv_destinos.categoria_id'=>$categoria_id)) ? $this->db->get_where('bv_destinos',array('bv_destinos.categoria_id'=>$categoria_id)) : [] ;

	if($destinos){
		$destinos = $destinos->result();
	    foreach ($destinos as $d) {
	      $precio_minimo = $this->getPrecioDesde($d->id);
	      if ($precio_minimo) {
		        $d->precio = $precio_minimo->total;
		        $d->impuestos = $precio_minimo->impuestos;
		        $d->precio_usd = $precio_minimo->precio_usd;
						$d->precio_anterior_neto = @$precio_minimo->precio_anterior_neto;
						$d->precio_anterior_impuestos = @$precio_minimo->precio_anterior_impuestos;
	     	}
	    }
	}else{
		$destinos = [];
	}
	return $destinos;
  }
	
  function getGrupales(){
	$q = "select * from bv_destinos 
			join (select destino_id, grupal from bv_paquetes where grupal = 1 group by destino_id) x on x.destino_id = id 
			where (publicado = 1 or id = 35) 
			order by nombre asc";
	return $this->db->query($q);
  }
  
  function getIndividuales(){
	$q = "select * from bv_destinos 
			join (select destino_id, grupal from bv_paquetes where grupal = 0 group by destino_id) x on x.destino_id = id 
			where publicado = 1
			order by nombre asc";
	return $this->db->query($q);
  }
 

  function getDestacadosHome(){
		$this->db->select('d.*, d.descripcion as subtitulo,
								ifnull(p.grupal,0) as grupal, 
								c.slug as categoria_slug,
								c.otros,
								ifnull(co.cantidad,0) as disponibles,
								group_concat(e.id) as estacionales');
		$this->db->join('bv_categorias c','c.id = d.categoria_id');
		$this->db->join('bv_destinos_estacionales de','d.id = de.destino_id','left');
		$this->db->join('bv_estacionales e','e.id = de.estacional_id','left');
		//menor precio de las combinaciones que no estén CONFIRMADAS del paquete
		$this->db->join('(select p.destino_id, max(p.grupal) as grupal
							from bv_paquetes p 
							group by p.destino_id) p','p.destino_id = d.id','left');
		//join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
		$this->db->join('(select p.destino_id, count(distinct pc.id) as cantidad
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
								where p.activo = 1 and p.visible = 1 and p.fecha_inicio >= current_date()
								group by p.destino_id) co','co.destino_id = d.id','left');
		$this->db->group_by('d.id');
		$this->db->order_by('d.orden','asc');
		$this->db->where('d.publicado = 1 and d.destacado = 1');

		//$destinos = $this->db->get('bv_destinos d') ? $this->db->get('bv_destinos d')->result() : [];

		$destinos = $this->db->get_where('bv_destinos',array('bv_destinos.categoria_id'=>$categoria_id)) ? $this->db->get_where('bv_destinos',array('bv_destinos.categoria_id'=>$categoria_id))->result() : [] ;
		$destinos = $this->db->get('bv_destinos d');

		if($destinos){
				$destinos = $destinos->result();
				foreach ($destinos as $d) {
					$precio_minimo = $this->getPrecioDesde($d->id);
					if ($precio_minimo) {
						$d->precio = $precio_minimo->total;
						$d->impuestos = $precio_minimo->impuestos;
						$d->precio_usd = $precio_minimo->precio_usd;
						$d->precio_anterior_neto = @$precio_minimo->precio_anterior_neto;
						$d->precio_anterior_impuestos = @$precio_minimo->precio_anterior_impuestos;
					}
				}
		}else{
				$destinos = [];
		}

    return $destinos;
  }

  function getProximamente(){
	$this->db->select('d.*, d.descripcion as subtitulo,
							c.slug as categoria_slug,
							c.otros');
	$this->db->join('bv_categorias c','c.id = d.categoria_id');

	$this->db->group_by('d.id');
	$this->db->order_by('d.orden','asc');
	$this->db->where('d.publicado = 1 and d.proximamente = 1');
	return $this->db->get('bv_destinos d')->result();
  }

 
  function getDisponibles() {
    $this->db->select('bv_destinos.*, c.nombre as categoria, ifnull(le.cantidad,0) as lista_espera, ifnull(x.cantidad,0) as cant_paquetes');
    $this->db->join('bv_categorias c', 'c.id = bv_destinos.categoria_id', 'left');
	//join a lista de espera del destino
	$this->db->join('(select tipo_id, count(*) as cantidad
							from bv_lista_espera  
							where tipo = "destino"
							group by tipo_id) le','le.tipo_id = bv_destinos.id','left');
	//obtengo cantidad de paquetes activos del destino
	$this->db->join('(select destino_id, count(p.id) as cantidad
							from bv_paquetes p
							where p.activo = 1 and p.visible = 1 and p.fecha_inicio >= NOW() 
							group by destino_id) x','x.destino_id = bv_destinos.id and x.cantidad > 0');

	$this->db->where('bv_destinos.publicado = 1');
	return $this->db->get('bv_destinos') ? $this->db->get('bv_destinos')->result() : [];
  }
}
