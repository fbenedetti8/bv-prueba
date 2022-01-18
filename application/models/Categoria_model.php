<?php
class Categoria_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_categorias";
    $this->indexable = array('nombre');
    $this->fk = "id";
    $this->pk = "id";
    $this->defaultSort = 'orden';
  }
	
	function onGetAll(){
		$this->db->select('bv_categorias.*, ifnull(p.precio,0) as precio, 
								ifnull(p.grupal,0) as grupal, 
								ifnull(p.precio_usd,0) as precio_usd,
								ifnull(co.cantidad,0) as disponibles,
								ifnull(le.cantidad,0) as lista_espera');
		//menor precio de las combinaciones que no estén AGOTADAS del paquete
		$this->db->join('(select d.categoria_id, max(p.grupal) as grupal, min(pc.v_total) as precio, p.precio_usd 
							from bv_paquetes p 
							join bv_destinos d on d.id = p.destino_id
							join bv_destinos_estacionales dd on dd.destino_id = d.id
							join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
							group by d.categoria_id) p','p.categoria_id = bv_categorias.id','left');
		//join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
		$this->db->join('(select d.categoria_id, count(distinct pc.id) as cantidad
								from bv_paquetes p 
								join bv_destinos d on d.id = p.destino_id
								join bv_categorias cc on cc.id = d.categoria_id
								join bv_destinos_estacionales dd on dd.destino_id = d.id
								join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
								group by d.categoria_id) co','co.categoria_id = bv_categorias.id','left');
		//join a lista de espera de la categoria
		$this->db->join('(select tipo_id, count(*) as cantidad
								from bv_lista_espera  
								where tipo = "categoria"
								group by tipo_id) le','le.tipo_id = bv_categorias.id','left');
	}
	
  function getPrecioDesde($categoria_id=0, $grupales=0) {
    $COTIZACION_USD = $this->db->select('cotizacion_dolar')->get('bv_config')->row()->cotizacion_dolar;
    
    $q = "SELECT precio_usd, total, impuestos
          FROM
          (
            SELECT d.categoria_id, p.destino_id, p.precio_usd, p.grupal, mins.total, mins.impuestos+(case when p.exterior=1 then ifnull(p.impuesto_pais,0) else 0 end) as impuestos,  case when p.precio_usd = 1 then mins.total * $COTIZACION_USD else mins.total end as total_ars
            FROM bv_paquetes p, bv_destinos d,
                  (SELECT paquete_id, (v_iva21+v_iva10+v_otros_imp) as impuestos, min(v_total-v_iva21-v_iva10-v_otros_imp) as total FROM bv_paquetes_combinaciones GROUP BY paquete_id) mins
            WHERE p.id = mins.paquete_id and d.id = p.destino_id and p.activo = 1
          ) op ";

    if ($categoria_id) {
    	$q .= "WHERE categoria_id = $categoria_id";
    }
    elseif ($grupales) {
    	$q.= "WHERE grupal = 1";
    }

    $q .= " ORDER BY op.total_ars
           LIMIT 1";

    return $this->db->query($q)->row();
  }

  function getMenorPrecio(){
    $this->db->select(' c.*, 
                        ifnull(p.grupal,0) as grupal, 
                        ifnull(co.cantidad,0) as disponibles');
    
    //menor precio de las combinaciones que no estén ANULADAS del paquete
    $this->db->join('(  select d.categoria_id, max(p.grupal) as grupal, min(pc.v_total) as precio, p.precio_usd 
                        from bv_paquetes p 
                        join bv_destinos d on d.id = p.destino_id
                        join bv_destinos_estacionales dd on dd.destino_id = d.id
                        join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
                        group by d.categoria_id
                      ) p','p.categoria_id = c.id','left');

    //join para obtener la cantidad de combinaciones DISPONIBLES (NO AGOTADAS) de cada paquete
    $this->db->join('(  select d.categoria_id, count(distinct pc.id) as cantidad
                        from bv_paquetes p 
                        join bv_destinos d on d.id = p.destino_id
                        join bv_categorias cc on cc.id = d.categoria_id
                        join bv_destinos_estacionales dd on dd.destino_id = d.id
                        join bv_paquetes_combinaciones pc on pc.paquete_id = p.id and pc.agotada = 0
                        group by d.categoria_id
                      ) co','co.categoria_id = c.id','left');
    
    $this->db->where('c.visible = 1 and c.destacada_home = 1');
    $this->db->order_by('c.orden','asc');
    
    $categorias = $this->db->get('bv_categorias c')->result();

    foreach ($categorias as $c) {
      $precio_minimo = $this->getPrecioDesde($c->id);
      if ($precio_minimo) {
        $c->precio = $precio_minimo->total;
        $c->impuestos = $precio_minimo->impuestos;
        $c->precio_usd = $precio_minimo->precio_usd;
      }
    }

    return $categorias;
  }

	function getMenu(){
		//categorias basicas
		$this->db->order_by('orden','asc');
		$basicas = $this->db->get_where('bv_categorias c',array('visible'=>1, 'otros'=>0))->result();
		
		foreach($basicas as $c){
			$c->destinos = $this->db->query("
				select id, nombre, slug, case when t.disponible>0 then 0 else 1 end as menu_lista_espera
				from bv_destinos 
				left join (select destino_id, sum(activo*cupo_disponible) as disponible from bv_paquetes group by destino_id) t
				on t.destino_id = bv_destinos.id
				where publicado = 1 and categoria_id = ".$c->id.' order by orden asc')->result();
		/*
        foreach ($c->destinos as $d) {
			$this->db->query("select sum(cupo_disponible) as disponible from bv_paquetes where destino_id = ".$d->id." and activo=1 and cupo_disponible")

             //22-11-18
            //pongo valor por defecto
            $d->menu_lista_espera = false;

            $this->load->model('Destino_model', 'Destino');
            //paquetes del destino agrupados por categoria estacional
            $estacionales = $this->Destino->getEstacionalesById($d->id);
            
            $cant_paquetes = 0;
            $primer_paquete = [];
            foreach($estacionales as $e){
              $paquetes = $this->Destino->getPaquetesPorEstacional($d->id,$e->id);

              $cant_paquetes += count($paquetes);

              if(count($paquetes)){
                $primer_paquete[] = isset($paquetes[0]) ? $paquetes[0] : false;
              }
            }

            if($cant_paquetes == 0){
              //si no tiene ningun paquete activo, va a estar como lista de espera
              $d->menu_lista_espera = true;
            }
            /*
            else if($cant_paquetes == 1){ 
             	if ($d->id == 20) {
	             	echo "A";
             	}
                if( count($primer_paquete) && $primer_paquete[0] ){
                    //si hay un solo paquete activo, me fijo si ese paquete va a estar como lista de espera
                    //busco la primer combinacion que no este agotada
                    $combinacion = FALSE;
                    $this->load->model('Combinacion_model', 'Combinacion');
                    $combinaciones = $this->Combinacion->getByPaquete($primer_paquete[0]->id,50);
                    foreach ($combinaciones as $c) {
                      //la primera q no este agorada o que sea compartida
                      if (!$c->agotada || $c->habitacion_id == 99) {
                        $combinacion = $c;
                        break;
                      }
                    }
                    if (!$combinacion) {
                      $combinacion = @$combinaciones[0];
                    }

                    if(!isset($combinacion->id) || !$combinacion->id){
                      //si no tiene alguna combinacion para elgir, va a estar lista de espera
                      $d->menu_lista_espera = true;
                    }

                }
                
            }
        }
        */


		}
		
		//otras categorias
		$this->db->order_by('orden','asc');
		$otros = $this->db->get_where('bv_categorias c',array('visible'=>1, 'otros'=>1))->result();
		
		$categorias['basicas'] = $basicas;
		$categorias['otros'] = $otros;
		
		return $categorias;
	}
	
	function getEstacionales($id){
		$this->db->select('e.*');
		$this->db->join('bv_destinos_estacionales de','e.id = de.estacional_id');
		$this->db->join('bv_destinos d','d.id = de.destino_id and d.publicado = 1');
		$this->db->join('bv_categorias c','c.id = d.categoria_id and c.id = '.$id);
		$this->db->group_by('e.id');
		$this->db->order_by('e.orden','asc');
		return $this->db->get('bv_estacionales e')->result();
	}
	
  function getDisponibles() {
    $this->db->select('c.id, c.slug, c.nombre, ifnull(x.cantidad,0) as cant_paquetes');
    $this->db->join('bv_categorias c', 'c.id = bv_destinos.categoria_id', 'left');
   //obtengo cantidad de paquetes activos del destino
    $this->db->join('(select d.categoria_id,  count(p.id) as cantidad 
                        from bv_paquetes p 
                        join bv_destinos d on d.id = p.destino_id and d.publicado =1 
                        where p.activo = 1 and p.fecha_inicio >= NOW() 
                        group by d.categoria_id) x','x.categoria_id = c.id and x.cantidad > 0');

    $this->db->where('bv_destinos.publicado = 1');
    $this->db->group_by('c.id');
    return $this->db->get('bv_destinos')->result();
  }


}