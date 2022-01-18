<?php
class Orden_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_ordenes";
		$this->indexable = array('code');
		$this->fk = "id";
		$this->pk = "id";
	}

	function onGet(){
		$this->db->select('bv_ordenes.*, x.nombre as lugarSalida, p.nombre, p.codigo as paquete_codigo, p.confirmacion_inmediata, p.exterior, p.precio_usd, p.fecha_limite_completar_datos, p.grupal, 
							pp.hora, s.nombre as sucursal, s.direccion, s.telefono, op.email, 
							concat(op.nombre, " ",op.apellido) as pasajero, 
							h.pax, (case when p.cupo_disponible<h.pax then true else false end) as completo');
		$this->db->join('bv_ordenes_pasajeros op', 'op.orden_id = bv_ordenes.id and op.responsable = 1');
		$this->db->join('bv_paquetes p','p.id = bv_ordenes.paquete_id', 'left');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = p.id and pp.id = bv_ordenes.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_lugares_salida ls', 'ls.id = x.lugar_id','left');
		$this->db->join('bv_sucursales s', 's.id = bv_ordenes.sucursal_id','left');
		$this->db->join('bv_habitaciones h', 'h.id = bv_ordenes.habitacion_id','left');
	}
	
	
	function getAllExport($num=100000, $offset=0, $sort='', $type='DESC', $keywords='') {
		if ($sort == '') $sort=$this->pk;
		
		$this->onGetAllExport();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " .(strpos($index, ".")?$index:($this->table.".".$index)) . " LIKE '%" . $keywords . "%'";
			//$this->db->or_like($index, $keywords);
			$q .= ")";
			$this->db->where($q);
		}
		
		if ($this->filters != '')
			$this->db->where($this->filters);
				
		if ($sort != '')
			$query = $this->db->order_by($sort, $type)->get($this->table, $num, $offset);	
		else 
			$query = $this->db->get($this->table, $num, $offset);	
		
		return $query;
	}

	function onGetAllExport(){
		$this->db->distinct();
		/*
		$this->db->select('bv_ordenes.*, x.nombre as lugarSalida, p.nombre, p.codigo as paquete_codigo, p.confirmacion_inmediata, p.exterior, p.precio_usd, p.fecha_limite_completar_datos, p.grupal, 					pp.hora, s.nombre as sucursal, s.direccion, s.telefono, op.email, 
							concat(op.nombre, " ",op.apellido) as pasajero, 
							h.pax, (case when p.cupo_disponible<h.pax then true else false end) as completo');
		*/

		$this->db->select("CONCAT(p.nombre,' - Cód: ',p.codigo) as paquete, op.apellido, op.nombre, CONCAT(op.celular_codigo,op.celular_numero) as celular, op.email, op.dni, 
			op.pasaporte, op.fecha_emision, op.fecha_vencimiento, op.fecha_nacimiento,
			PII.nombre as nacionalidad, op.dieta as menu, op.sexo, bv_ordenes.fecha_orden,bv_ordenes.fecha_orden as fecha,
			'' as adicionales, LSA.nombre as lugar_salida, x.nombre as parada, '' as comentario, 
			regi.nombre as pension, bv_ordenes.vendedor_id, V.nombre as vendedor_nombre, V.apellido as vendedor_apellido", false);

		$this->db->join('bv_ordenes_pasajeros op', 'op.orden_id = bv_ordenes.id and op.responsable = 1');
		$this->db->join('bv_paises PII','PII.id = op.nacionalidad_id','left');
		$this->db->join('bv_paquetes p','p.id = bv_ordenes.paquete_id', 'left');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = p.id and pp.id = bv_ordenes.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_lugares_salida ls', 'ls.id = x.lugar_id','left');
		$this->db->join('bv_sucursales s', 's.id = bv_ordenes.sucursal_id','left');
		$this->db->join('bv_habitaciones h', 'h.id = bv_ordenes.habitacion_id','left');
		$this->db->join('bv_lugares_salida LSA','LSA.id = bv_ordenes.lugar_id','left');
		$this->db->join('bv_paquetes_regimenes pr', 'pr.id = bv_ordenes.paquete_regimen_id and pr.paquete_id = p.id','left');
		$this->db->join('bv_regimenes regi', 'regi.id = pr.regimen_id','left');
		$this->db->join('bv_vendedores V','V.id = bv_ordenes.vendedor_id','left');

		//este join es para obtener los adicionales contratados
		$this->db->join('(select GROUP_CONCAT(AD.nombre SEPARATOR ", ") as adicionales, R.id
								from bv_ordenes_adicionales A 
								join bv_ordenes R on R.id = A.orden_id
								join bv_paquetes_adicionales PA on PA.paquete_id = R.paquete_id 
								join bv_adicionales AD on AD.id = PA.adicional_id
								group by R.id
							) adic', 'bv_ordenes.id = adic.id', 'left');
	}
	
	function onGetAll(){
		$this->db->distinct();
		$this->db->select('bv_ordenes.*, x.nombre as lugarSalida, p.nombre, p.codigo as paquete_codigo, p.confirmacion_inmediata, p.exterior, p.precio_usd, p.fecha_limite_completar_datos, p.grupal, 					pp.hora, s.nombre as sucursal, s.direccion, s.telefono, op.email, 
							concat(op.nombre, " ",op.apellido) as pasajero, 
							h.pax, (case when p.cupo_disponible<h.pax then true else false end) as completo');
		$this->db->join('bv_ordenes_pasajeros op', 'op.orden_id = bv_ordenes.id and op.responsable = 1');
		$this->db->join('bv_paquetes p','p.id = bv_ordenes.paquete_id', 'left');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = p.id and pp.id = bv_ordenes.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_lugares_salida ls', 'ls.id = x.lugar_id','left');
		$this->db->join('bv_sucursales s', 's.id = bv_ordenes.sucursal_id','left');
		$this->db->join('bv_habitaciones h', 'h.id = bv_ordenes.habitacion_id','left');
	}

	function addAdicional($orden_id,$adicional_id,$valor,$cantidad=0){
		$data = array();
		$data['orden_id'] = $orden_id;
		$data['paquete_adicional_id'] = $adicional_id;
		$data['valor'] = $valor;
		$data['cantidad'] = $cantidad;
		$this->db->insert('bv_ordenes_adicionales',$data);
		return true;
	}
	
	function getAdicionales($orden_id){
		$this->db->select('a.*, pa.v_total, oa.cantidad, oa.paquete_adicional_id');
		$this->db->join('bv_paquetes_adicionales pa','pa.id = oa.paquete_adicional_id');
		$this->db->join('bv_adicionales a','a.id = pa.adicional_id');

		return $this->db->get_where('bv_ordenes_adicionales oa',array('oa.orden_id'=>$orden_id))->result();
	}
		
	/*
	Se usa en el cron para actualizar las ordenes vencidas segun las horas seteadas por admin
	*/
	function updateOrdenesVencidas(){
		$horas_orden = $this->settings->horas_orden;
		$this->db->query("UPDATE bv_ordenes SET vencida = 1 
							WHERE vencida = 0 and TIMESTAMPDIFF(MINUTE, timestamp, now()) >= ".$horas_orden);
	}
	
}