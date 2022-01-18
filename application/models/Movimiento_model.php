<?php
class Movimiento_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_movimientos";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
		
	function onGetAll(){
		$this->db->select('bv_movimientos.*, f.cae, f.tipo, p.codigo as paquete_codigo, p.nombre as paquete_titulo, s.talonario as sucursal_talonario, s.id as sucursal_id,
							f.reserva_id as factura_reserva_id, p1.codigo as op_paquete_codigo, p1.nombre as op_paquete_titulo, r.code as reserva_codigo');
		$this->db->join('bv_facturas f', 'f.id = bv_movimientos.factura_id and f.tipo = bv_movimientos.talonario and f.usuario_id = bv_movimientos.usuario_id', 'left');
		$this->db->join('bv_reservas r', 'r.id = bv_movimientos.reserva_id','left');
		$this->db->join('bv_paquetes p', 'p.id = r.paquete_id','left');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = p.id and pp.id = r.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_lugares_salida ls', 'ls.id = x.lugar_id','left');
		$this->db->join('bv_sucursales s', 's.id = r.sucursal_id','left');
		//join a esta tabla por si el registro del movimiento tiene asociado el paquete (mov de cta cte de operador)
		$this->db->join('bv_paquetes p1', 'p1.id = bv_movimientos.paquete_id','left');
	}
	
	function getLastMovimientoByTipoUsuario($id,$tipo,$cta_usd=false){
		$this->db->order_by("fecha","desc");
		$this->db->order_by("id","desc");
		$this->db->limit(1);
		return $this->db->get_where($this->table,array("usuario_id" => $id, "tipoUsuario" => $tipo, "cta_usd" => ($cta_usd?1:0)));
	}
	
	function getMovAsociado($find){
		$this->db->where($find);
		$this->db->order_by("id","desc");
		$this->db->limit(1);
		return $this->db->get($this->table);
	}
	
	/* obtiene los adicionales que están asociados a la reserva pero que no tienen el movimiento de anulacion asociado */
	function getAdicionalesSinAnular($find){
		$this->db->select('m.*, ra.paquete_adicional_id',false);
		$this->db->join('bv_reservas_adicionales ra','ra.id = m.reserva_adicional_id');
		$this->db->where($find);
		$this->db->where('m.reserva_adicional_id > 0 and (m.mov_asoc_id = 0 or m.mov_asoc_id is null)');
		return $this->db->get($this->table.' m')->result();
	}
	
	function get_pagos_de_reserva($reserva_id,$usuario_id,$tipo,$sin_nc=false,$limit=99999,$offset=0){
		$this->db->select('bv_movimientos.*, f.cae, f.tipo, p.codigo as paquete_codigo, p.nombre as paquete_titulo, s.talonario as sucursal_talonario, s.id as sucursal_id');
		$this->db->limit($limit);
		$this->db->offset($offset);
		$this->db->order_by("fecha","asc");
		$this->db->order_by("bv_movimientos.id","asc");
		$this->db->join('bv_facturas f', 'f.id = bv_movimientos.factura_id and f.tipo = bv_movimientos.talonario and f.usuario_id = bv_movimientos.usuario_id', 'left');
		$this->db->join('bv_reservas r', 'r.id = bv_movimientos.reserva_id');
		$this->db->join('bv_paquetes p', 'p.id = r.paquete_id','left');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = p.id and pp.id = r.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_lugares_salida ls', 'ls.id = x.lugar_id','left');
		$this->db->join('bv_sucursales s', 's.id = r.sucursal_id','left');
		$this->db->where('bv_movimientos.haber > 0');
		
		//busco pagos segun el talonario FA_
		$this->db->where('bv_movimientos.factura_id > 0');
		$this->db->where('bv_movimientos.talonario like "%FA_%"');
		
		if($reserva_id){
			$this->db->where("bv_movimientos.reserva_id = ".$reserva_id);
		}
		
		$this->db->order_by('bv_movimientos.fecha','desc');
		
		return $this->db->get_where($this->table,array("bv_movimientos.usuario_id" => $usuario_id, "tipoUsuario" => $tipo));
	}
	
	function getPago($reserva_id,$tipo,$tipousuario_id){
		$this->db->select("sum(haber)-sum(debe) as pago_hecho");
		$this->db->order_by("fecha","desc");
		return $this->db->get_where($this->table,array("usuario_id" => $tipousuario_id, "tipoUsuario" => $tipo, "reserva_id" => $reserva_id));					
	}
	
	function getPagosHechos($reserva_id,$tipousuario_id,$precio_usd=false,$cta_usd=false){
		if($precio_usd){
			#$this->db->select("sum(m.haber_usd) as pago_hecho");
			$this->db->select("sum(m.haber_usd-m.debe_usd) as pago_hecho");
		}
		else{
			#$this->db->select("sum(m.haber) as pago_hecho");
			$this->db->select("sum(m.haber-m.debe) as pago_hecho");
		}
		
		$this->db->order_by("m.fecha","desc");
		
		// $this->db->where("concepto != 'ANULACION RESERVA'");

		//los mov de pago tienen el nombre del concepto en el detalle.
		//los conceptos de pago son los que aplican al haber y que generan facturacion
		$this->db->join("bv_conceptos c","c.aplica_a != 'debe' and m.concepto like concat('%',c.nombre,'%')");
		$this->db->where("(m.haber > 0 or m.debe > 0)");

		//c.id = 29 BONIFICACION, 5 y 58 correccion
		$this->db->where("((m.concepto like concat('%',c.nombre,'%') and c.facturacion = 1) or c.id = 29 or c.id = 5 or c.id = 58)");

		if($cta_usd){
			$this->db->where("m.cta_usd = 1");
		}
		else{
			$this->db->where("m.cta_usd = 0");
		}
		return $this->db->get_where($this->table." m",array("m.usuario_id" => $tipousuario_id, "m.tipoUsuario" => "U", "reserva_id" => $reserva_id));
	}
	
}