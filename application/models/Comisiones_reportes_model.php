<?php
class Comisiones_reportes_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_comisiones_minimos";
		$this->indexable = array('rol');
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function generarReporte($reporte, $tipo, $p1, $p2, $equipos=FALSE) {
		switch ($reporte) {
			case 'comisiones':
				return $this->reporteComisiones($tipo, $p1, $p2);
				break;
			
			case 'ranking':
				return $this->reporteRanking($tipo, $p1, $p2);
				break;

			case 'ventas':
				return $this->reporteVentas($tipo, $p1, $p2, $equipos);
				break;
		}
	}

	function reporteComisiones($tipo, $p1, $p2) {
		if ($tipo == 'mes') {
			$filtro_fecha = " YEAR(conf.fecha_confirmada) = '".$p1."' AND MONTH(conf.fecha_confirmada) = '".$p2."' and re.estado_id = 4";
		}
		else{
			$filtro_fecha = " conf.fecha_confirmada >= '".$p1."' AND conf.fecha_confirmada < '".$p2."' and re.estado_id = 4";
		}

		//los filtros de fecha se basan en la fecha de confirmacion de reserva
		return $this->db->query("	SELECT bv_comisiones_liquidaciones.id, usuario,
				(case when cc.coordinador_id > 0 then 'Lider' else case when cg.gerente_id > 0 then 'Gerente' else 'Vendedor' end end) as jerarquia, 
				max(paquetes_equipo) 
				as paquetes_equipo, 
				max(comision_equipo) 
				as comision_equipo, 
				max(porcentaje_comision_equipo) as porcentaje_comision_equipo,
				max(total_venta_equipo_comisionable) as total_venta_equipo_comisionable,
				max(total_venta_equipo) as total_venta_equipo,

				max(paquetes_directos) 
				as paquetes_directos,
				max( comision_directa) 
				as comision_directa, 
				max( porcentaje_comision_directa) 
				as porcentaje_comision_directa, 
				max(total_venta_directa_comisionable) as total_venta_directa_comisionable, 
				max(total_venta_directa) as total_venta_directa, 
				
				minimo_no_comisionable,
				MAX(total_comision) as total_comision

				FROM bv_comisiones_liquidaciones
				JOIN bv_comisiones_liquidaciones_reservas r on r.liquidacion_id = bv_comisiones_liquidaciones.id
				JOIN bv_reservas re on re.id = r.reserva_id
				INNER JOIN (select m.reserva_id, min(fecha) as fecha_confirmada
													from bv_movimientos m
													inner join bv_conceptos c on c.nombre = m.concepto
													where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1
													group by m.reserva_id
												) conf ON conf.reserva_id = re.id
				LEFT JOIN (select e.*, concat(vv.nombre,' ',vv.apellido) as coordinador
									from bv_equipos e
									left join bv_vendedores vv on e.coordinador_id = vv.id) cc on cc.coordinador = usuario

				LEFT JOIN (select e.*, concat(v.nombre,' ',v.apellido) as gerente
									from bv_equipos e
									left join bv_vendedores v  on e.gerente_id = v.id) cg on cg.gerente = usuario
				WHERE ".$filtro_fecha."
				group by usuario
				ORDER BY total_venta_directa DESC
			")->result();
	}

	function reporteRanking($tipo, $p1, $p2) {
		if ($tipo == 'mes') {
			$filtro_fecha = " YEAR(conf.fecha_confirmada) = '".$p1."' AND MONTH(conf.fecha_confirmada) = '".$p2."' and r.estado_id = 4";
		}
		else{
			$filtro_fecha = " conf.fecha_confirmada >= '".$p1."' AND conf.fecha_confirmada < '".$p2."' and r.estado_id = 4";
		}

		//los filtros de fecha se basan en la fecha de confirmacion de reserva
		$rows = $this->db->query("	SELECT l.id, l.usuario, 
				(case when cc.coordinador_id > 0 then 'Lider' else case when cg.gerente_id > 0 then 'Gerente' else 'Vendedor' end end) as jerarquia, 
				sum(l.paquetes_directos+(case when l.jerarquia <> 'Vendedor' then l.paquetes_equipo else 0 end)) as paquetes_directos, MAX(l.total_comision) as total_comision, sum(l.total_venta_directa) as total_venta_directa, sum(l.total_venta_directa_comisionable) as total_venta_directa_comisionable
										FROM bv_comisiones_liquidaciones l
										JOIN (select distinct cr.liquidacion_id
													from bv_reservas r
													join bv_comisiones_liquidaciones_reservas cr on cr.reserva_id = r.id
													JOIN (select m.reserva_id, min(fecha) as fecha_confirmada
														from bv_movimientos m
														inner join bv_conceptos c on c.nombre = m.concepto
														where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1
														group by m.reserva_id
													) conf ON conf.reserva_id = r.id
													WHERE $filtro_fecha
										) t on t.liquidacion_id = l.id
										LEFT JOIN (select e.*, concat(vv.nombre,' ',vv.apellido) as coordinador
															from bv_equipos e
															left join bv_vendedores vv on e.coordinador_id = vv.id) cc on cc.coordinador = l.usuario

										LEFT JOIN (select e.*, concat(v.nombre,' ',v.apellido) as gerente
															from bv_equipos e
															left join bv_vendedores v  on e.gerente_id = v.id) cg on cg.gerente = l.usuario
										group by l.usuario
										ORDER BY 6 DESC")->result();

		$total = $this->db->query("SELECT SUM(l.total_venta_directa) as total 
										FROM bv_comisiones_liquidaciones l 
										JOIN (select distinct cr.liquidacion_id
													from bv_reservas r
													join bv_comisiones_liquidaciones_reservas cr on cr.reserva_id = r.id
													JOIN (select m.reserva_id, min(fecha) as fecha_confirmada
														from bv_movimientos m
														inner join bv_conceptos c on c.nombre = m.concepto
														where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1
														group by m.reserva_id
													) conf ON conf.reserva_id = r.id
													WHERE $filtro_fecha
										) t on t.liquidacion_id = l.id")->row()->total;

		/*
		if ($tipo == 'mes') {
			$rows = $this->db->query("	SELECT id, usuario, jerarquia, paquetes_directos, total_comision, total_venta_directa, total_venta_directa_comisionable
										FROM bv_comisiones_liquidaciones
										WHERE anio = $p1 AND mes = $p2
										ORDER BY total_venta_directa DESC
									")->result();
			

			$total = $this->db->query("SELECT SUM(total_venta_directa) as total 
										FROM bv_comisiones_liquidaciones 
										WHERE anio = $p1 AND mes = $p2")->row()->total;
		}
		else {
			$rows = $this->db->query("	SELECT GROUP_CONCAT(id SEPARATOR '-') as id, usuario, jerarquia, sum(paquetes_directos) as paquetes_directos, MAX(total_comision) as total_comision, SUM(total_venta_directa) as total_venta_directa, sum(total_venta_directa_comisionable) as total_venta_directa_comisionable
										FROM bv_comisiones_liquidaciones
										WHERE fecha_liquidacion >= '".$p1."' AND fecha_liquidacion < '".$p2."'
										GROUP BY usuario, jerarquia
										ORDER BY total_venta_directa DESC
									")->result();
			
			$total = $this->db->query("SELECT SUM(total_venta_directa) as total FROM bv_comisiones_liquidaciones WHERE fecha_liquidacion >= '".$p1."' AND fecha_liquidacion < '".$p2."'")->row()->total;
		}
		*/
		
		foreach ($rows as $row) {
			$row->proporcion = number_format($row->total_venta_directa / $total * 100, 2);
		}

		return $rows;
	}
	
	function reporteVentas($tipo, $p1, $p2, $equipos=FALSE) {
		if ($tipo == 'mes') {
			$p1 = $p1.'-'.$p2.'-01';
			//$p2 = date("Y-m-t", strtotime($p1));
			$p2 = date('Y-m-d', strtotime('+1 month', strtotime($p1)));
		}
		
		if (!$equipos) {
			$q = "	SELECT 	
							v.id as vendedor_id,
							v.nombre,
							v.apellido, 
							COUNT(*) as paquetes_directos,
							sum(CASE WHEN p.precio_usd=1 THEN (r.paquete_precio + r.impuestos + r.adicionales_precio) * r.cotizacion
								 ELSE (r.paquete_precio + r.impuestos + r.adicionales_precio)
							END) as total_venta_directa,
							sum( (CASE WHEN p.precio_usd=1 THEN (r.paquete_precio + r.impuestos + r.adicionales_precio) * r.cotizacion
								 ELSE (r.paquete_precio + r.impuestos + r.adicionales_precio)
							END) * p.porcentaje_comisionable/100) as total_comisionable_directo
					FROM	bv_reservas r
					INNER JOIN (select m.reserva_id, min(fecha) as fecha_confirmada
													from bv_movimientos m
													inner join bv_conceptos c on c.nombre = m.concepto
													where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1
													group by m.reserva_id
												) conf ON conf.reserva_id = r.id
					INNER JOIN bv_paquetes p ON p.id = r.paquete_id
					INNER JOIN bv_vendedores v ON v.id = r.vendedor_id
					WHERE conf.fecha_confirmada >= '$p1' AND conf.fecha_confirmada < '$p2' and r.estado_id = 4
					GROUP BY 1, 2, 3
					ORDER BY 5 DESC";
		}
		else {
			/*					
			$q = "	SELECT 	'Equipo' as jerarquia, 
							e.id as equipo_id,
							e.nombre,
							COUNT(*) as paquetes_directos,
							SUM(CASE WHEN p.precio_usd=1 THEN (r.paquete_precio + r.impuestos + r.adicionales_precio) * r.cotizacion
								 ELSE (r.paquete_precio + r.impuestos + r.adicionales_precio)
							END) as total_venta_directa,
							SUM( (CASE WHEN p.precio_usd=1 THEN (r.paquete_precio + r.impuestos + r.adicionales_precio) * r.cotizacion
								 ELSE (r.paquete_precio + r.impuestos + r.adicionales_precio)
							END) * p.porcentaje_comisionable/100) as total_comisionable_directo
					FROM	bv_reservas r
					INNER JOIN (select m.reserva_id, min(fecha) as fecha_confirmada
													from bv_movimientos m
													inner join bv_conceptos c on c.nombre = m.concepto
													where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1
													group by m.reserva_id
												) conf ON conf.reserva_id = r.id
					INNER JOIN bv_paquetes p ON p.id = r.paquete_id
					INNER JOIN bv_vendedores v ON v.id = r.vendedor_id
					INNER JOIN bv_vendedores_equipos ve ON ve.vendedor_id = v.id
					INNER JOIN bv_equipos e ON e.id = ve.equipo_id
					WHERE conf.fecha_confirmada >= '$p1' AND conf.fecha_confirmada < '$p2' and r.estado_id = 4
					GROUP BY 1, 2, 3
					ORDER BY 5 DESC";		
			*/

			//ajustado el query para considerar los equipos que no tienen vendedores con ventas, para que aparezca el registro con valores en 0
			$q = "	SELECT 	'Equipo' as jerarquia, 
							e.id as equipo_id,
							e.nombre,
							COUNT(DISTINCT r.id) as paquetes_directos,
							IFNULL(SUM(CASE WHEN p.precio_usd=1 THEN (r.paquete_precio + r.impuestos + r.adicionales_precio) * r.cotizacion
								 ELSE (r.paquete_precio + r.impuestos + r.adicionales_precio)
							END),0) as total_venta_directa,
							IFNULL(SUM( (CASE WHEN p.precio_usd=1 THEN (r.paquete_precio + r.impuestos + r.adicionales_precio) * r.cotizacion
								 ELSE (r.paquete_precio + r.impuestos + r.adicionales_precio)
							END) * p.porcentaje_comisionable/100),0) as total_comisionable_directo
					FROM bv_equipos e 
					INNER JOIN bv_vendedores_equipos ve ON e.id = ve.equipo_id
					INNER JOIN bv_vendedores v ON ve.vendedor_id = v.id
					LEFT JOIN bv_reservas r ON v.id = r.vendedor_id and r.estado_id = 4
					LEFT JOIN bv_paquetes p ON p.id = r.paquete_id
					LEFT JOIN (select m.reserva_id, min(fecha) as fecha_confirmada
													from bv_movimientos m
													inner join bv_conceptos c on c.nombre = m.concepto
													where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1
													group by m.reserva_id
												) conf ON conf.reserva_id = r.id AND conf.fecha_confirmada >= '$p1' AND conf.fecha_confirmada < '$p2' 

					GROUP BY 1, 2, 3
					ORDER BY 5 DESC ";
		}

		return $this->db->query($q)->result();
	}

}