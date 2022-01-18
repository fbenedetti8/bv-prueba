<?php
class Stats_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_facturas";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
//queries del hotsale, revisar las tablas y datos
	function getReporteVentas($keywords=''){
		$this->db->select("DATE_FORMAT(f.fecha,'%d/%m/%Y') as 'Fecha', 
							concat(f.tipo,'-',s.codigoFacturacion,'-',LPAD(f.id,8,'0')) as 'Nro. Cpte', 
							CONCAT(p.nombre,' ',p.apellido) as 'Pasajero',
							r.code as 'Cod Reserva',
							pa.nombre as 'Nombre FILE',
							o.nombre as 'Operador',
							'Consumidor Final' as 'IVA',
							CONCAT(rf.f_cuit_prefijo,rf.f_cuit_numero,rf.f_cuit_sufijo) as 'CUIT',
							f.cae as 'CAE',
							CAST(f.valor/(case when pa.precio_usd and pa.exterior then r.pasajeros*r.cotizacion*v.v_total else v.v_total end)*r.pasajeros*r.cotizacion*v.v_exento as DECIMAL(10,2)) as 'Exento',
							CAST(f.valor/(case when pa.precio_usd and pa.exterior then r.pasajeros*r.cotizacion*v.v_total else v.v_total end)*r.pasajeros*r.cotizacion*v.v_gravado21 as DECIMAL(10,2)) as 'Gravado 21',
							CAST(f.valor/(case when pa.precio_usd and pa.exterior then r.pasajeros*r.cotizacion*v.v_total else v.v_total end)*r.pasajeros*r.cotizacion*v.v_gravado10 as DECIMAL(10,2)) as 'Gravado 10.5',
							CAST(f.valor/(case when pa.precio_usd and pa.exterior then r.pasajeros*r.cotizacion*v.v_total else v.v_total end)*r.pasajeros*r.cotizacion*v.v_comision as DECIMAL(10,2)) as 'Comision',
							CAST(f.valor/(case when pa.precio_usd and pa.exterior then r.pasajeros*r.cotizacion*v.v_total else v.v_total end)*r.pasajeros*r.cotizacion*v.v_iva21 as DECIMAL(10,2)) as 'IVA 21',
							CAST(f.valor/(case when pa.precio_usd and pa.exterior then r.pasajeros*r.cotizacion*v.v_total else v.v_total end)*r.pasajeros*r.cotizacion*v.v_iva10 as DECIMAL(10,2)) as 'IVA 10.5',
							CAST(f.valor/(case when pa.precio_usd and pa.exterior then r.pasajeros*r.cotizacion*v.v_total else v.v_total end)*r.pasajeros*r.cotizacion*v.v_gastos_admin as DECIMAL(10,2)) as 'Gastos administrativos',
							f.valor as 'Total en Pesos'
						  ",false);
		$this->db->join("bv_reservas_pasajeros rp","rp.id = f.usuario_id");
		$this->db->join("bv_pasajeros p","p.id = rp.pasajero_id and rp.responsable");
		$this->db->join("bv_reservas r","r.id = f.reserva_id");
		$this->db->join("bv_reservas_facturacion rf","rf.reserva_id = r.id");
		$this->db->join("bv_paquetes pa","pa.id = r.paquete_id");
		$this->db->join("bv_operadores o","o.id = pa.operador_id");
		$this->db->join("bv_paquetes_combinaciones v","v.id = r.combinacion_id");
		$this->db->join("bv_lugares_salida l","l.id = r.lugar_id");
		$this->db->join("bv_sucursales s","s.id = r.sucursal_id");
		if($keywords != ''){
			$this->db->where("r.codigo like '%".$keywords."%' or o.nombre like '%".$keywords."%' or p.nombre like '%".$keywords."%' or p.apellido like '%".$keywords."%'");
		}

		$this->db->order_by('f.fecha','asc');
		return $this->db->get($this->table.' f');
	}
	
	function getReporteIngresos($keywords='', $fecha_desde=FALSE, $fecha_hasta=FALSE){
		$filtro_fechas_desde = "";
		if ($fecha_desde) {
			$filtro_fechas_desde = "f.fecha >= '".$fecha_desde." 00:00:00'";
		}

		$filtro_fechas_hasta = "";
		if ($fecha_hasta) {
			$filtro_fechas_hasta = "f.fecha <= '".$fecha_hasta." 23:59:59'";
		}

		$this->db->select("DATE_FORMAT(f.fecha,'%d/%m/%Y') as 'Fecha', 
							concat(f.tipo,'-',s.codigoFacturacion,'-',LPAD(f.id,8,'0')) as 'Nro. Recibo', 
							CONCAT(rf.f_nombre,' ',rf.f_apellido) as 'Pasajero',
							r.code as 'Cod Reserva',
							pa.nombre as 'Nombre FILE',
							o.nombre as 'Operador',
							m.concepto as 'Concepto (Forma de Pago)',
							(CASE WHEN m.pago_usd = 1 then concat(m.tipo_cambio) else '' end) as 'Tipo de cambio',
							(CASE WHEN m.pago_usd = 1 then 'Dolares' else 'Pesos' end) as 'Moneda',
							(CASE WHEN m.pago_usd = 1 then cast((f.valor+(case when f.concepto_id in (70,71) then f.gastos_adm else 0 end))/m.tipo_cambio as decimal(10,2)) else f.valor+(case when f.concepto_id in (70,71) then f.gastos_adm else 0 end) end) as 'Total',
							f.tipo
						  ",false);
		$this->db->join("bv_reservas r","r.id = f.reserva_id");
		$this->db->join("bv_reservas_pasajeros rp","rp.reserva_id = r.id");
		$this->db->join("bv_pasajeros p","p.id = rp.pasajero_id and rp.responsable");
		$this->db->join("bv_movimientos m","m.reserva_id = r.id and m.tipoUsuario = 'U' and m.usuario_id = r.usuario_id and m.factura_id = f.id");
		$this->db->join("bv_reservas_facturacion rf","rf.reserva_id = r.id");
		$this->db->join("bv_paquetes pa","pa.id = r.paquete_id");
		$this->db->join("bv_operadores o","o.id = pa.operador_id");
		$this->db->join("bv_paquetes_combinaciones v","v.id = r.combinacion_id");
		$this->db->join("bv_lugares_salida l","l.id = r.lugar_id");
		$this->db->join("bv_sucursales s","s.id = r.sucursal_id");
		if($keywords != ''){
			$this->db->where("r.code like '%".$keywords."%' or o.nombre like '%".$keywords."%' or rf.f_nombre like '%".$keywords."%' or rf.f_apellido like '%".$keywords."%'");
		}
		if ($filtro_fechas_desde != "") {
			$this->db->where($filtro_fechas_desde);
		}
		if ($filtro_fechas_hasta != "") {
			$this->db->where($filtro_fechas_hasta);
		}
		$this->db->order_by('f.fecha','asc');
		return $this->db->get('bv_facturas f');
	}
	
	function getReporteCostos($keywords='', $fecha_desde=FALSE, $fecha_hasta=FALSE){
		$where = "r.estado_id in (4,5,13,2)";
		if($keywords != ''){
			$where .= " and r.code like '%".$keywords."%' or o.nombre like '%".$keywords."%' or rf.f_nombre like '%".$keywords."%' or rf.f_apellido like '%".$keywords."%'";
		}
		
		/*
		12-06 'dato' Venta_Total si es pago en USD de viaje al exterior
		le adiciono el importe de percepcion
		*/

		$filtro_fechas = "1";
		if ($fecha_desde) {
			$filtro_fechas .= " and ppp.fecha >= '".$fecha_desde." 00:00:00'";
		}
		if ($fecha_hasta) {
			$filtro_fechas .= " and ppp.fecha <= '".$fecha_hasta." 23:59:59'";
		}

		$q = "select 
				ppp.tipo as 'Tipo Factura',
				ppp.Estado_Reserva as 'Estado Reserva',
				ifnull(c.fecha,ifnull(ppp.fecha,'')) as 'Fecha Confirmada',
				ppp.Cod_Reserva as 'Cod Reserva',
				ppp.Nombre_FILE as 'Nombre FILE',
				ppp.nro_cpte as 'Nro. Cpte',
				ppp.Pasajero as 'Pasajero',
				ppp.Operador_Mayorista as 'Operador',
				ppp.Moneda,
				ppp.tipo_cambio as 'Tipo de Cambio',
				sum(ppp.Venta_Total) as 'Venta Total',
				sum(ppp.Costo_Total) as 'Costo Total',
				sum(ppp.Utilidad) as 'Utilidad',
				sum(ppp.Venta_Neta) as 'Venta Neta',
				sum(ppp.Costo_Neto) as 'Costo Neto',
				sum(ppp.Utilidad_Neta) as 'Utilidad Neta',
				concat(avg(ppp.Porcentual_Neto),'%') as 'Porcentual Neto' 
				from 
				(
					select 
						#@com := CAST( v.v_comision*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_comision,					
						#@comvta := CAST( v.fee*v.c_costo_operador/1.21/100/v.v_total*ifnull(ifnull(f.intereses,0)+f.gastos_adm+f.valor,0) as DECIMAL(10,2)) as vta_comision,					
						#@iva21 := CAST( @rel*v.v_iva21*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_iva21,					
						#@iva10 := CAST( v.v_iva10*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_iva10,					
						#@ppv := CAST( v.v_total*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_venta,					
						#@ppvn := CAST( (v.v_total-v.v_iva21-v.v_iva10)*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_venta_neta,					
						#@ppc := CAST( v.c_costo_operador*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_costo,					
						#@ppcn := CAST( (v.c_costo_operador-v.c_iva21-v.c_iva10)*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_costo_neto,					
						#@rel := cast(@pvt/@ppv as DECIMAL(10,2)) as relacion_pago_total,
						
						@perc := CAST( ifnull((CASE WHEN (pa.exterior and f.concepto_id IN (select (id) from `bv_conceptos` where nombre like '%deposito%')) THEN ifnull(ifnull(f.intereses,0)+ifnull(f.gastos_adm,0)+f.valor/1.05*0.05,0) ELSE 0 END ),0) as DECIMAL(10,2)) as percepcion,
						
						@pvt := ifnull( CAST(ifnull(ifnull(f.intereses,0)+f.gastos_adm+f.valor,0) as DECIMAL(10,2)) ,0) as 'Venta_Total',
						@pvsperc := ifnull(@pvt-@perc,0) as precio_venta_sin_perc,
						@gs := ifnull( CAST(ifnull(f.gastos_adm,0) as DECIMAL(10,2)) ,0) as gastos,
						@pvspsg := ifnull(@pvt-@perc-@gs,0) as precio_venta_sin_perc_ni_gs,				
						@gsn := CAST( ifnull((v.v_iva21+v.v_iva10)/v.v_total*@pvspsg + @gs/1.21*0.21,0) as DECIMAL(10,2)) as gsn, 
						#@vnaa := CAST( @pvsperc*((v.v_total-v.v_iva21-v.v_iva10)/v.v_total) as DECIMAL(10,2)) as 'Venta_Neta_NO', 
						@vn := ifnull(f.venta_neta,CAST( ifnull(@pvt-@gsn,0) as DECIMAL(10,2))) as 'Venta_Neta', 
						@utn := ifnull(f.utilidad_neta,CAST( ifnull((v.v_comision/v.v_total)*@pvspsg,0) as DECIMAL(10,2))) as 'Utilidad_Neta', 
						@cn := ifnull(f.costo_neto,CAST( ifnull(@vn-@utn,0) as DECIMAL(10,2))) as 'Costo_Neto', 

						@porc := concat(CAST( ifnull(@utn/@vn*100,0) as DECIMAL(10,0)),'%') as 'Porcentual_Neto', 
						
						@ut :=  ifnull(f.utilidad_total,CAST( ifnull(@utn+@utn*.21,0) as DECIMAL(10,2))) as 'Utilidad', 
						@ctt :=  ifnull(f.costo_total,CAST( (ifnull(@pvt-@ut,0)) as DECIMAL(10,2))) as 'Costo_Total', 
						
						f.tipo,
						r.id as reserva_id,
						f.fecha,
						IFNULL(DATE_FORMAT(f.fecha,'%d/%m/%Y'),'-') as 'Fecha_Comprobante', 
						(case r.estado_id 
								when 5 then 'Anulada' 
								when 4 then 'Confirmada' 
								when 2 then 'Por Vencer' 
								when 13 then 'Espera Pago Bancario' 
						end) as 'Estado_Reserva', 
						r.code as 'Cod_Reserva',
						concat(f.tipo,'-',s.codigoFacturacion,'-',LPAD(f.id,8,'0')) as 'nro_cpte', 
						pa.nombre as 'Nombre_FILE',
						CONCAT(rf.f_nombre,' ',rf.f_apellido) as 'Pasajero',
						o.nombre as 'Operador_Mayorista',
						(case when pa.precio_usd =1 then 'Dolares' else 'Pesos' end) as 'Moneda',
						(CASE WHEN m.pago_usd = 1 then m.tipo_cambio else '' end) as tipo_cambio
						
						from bv_reservas r
						join bv_facturas f on f.reserva_id = r.id
						join bv_reservas_facturacion rf on rf.reserva_id = r.id
						join bv_paquetes pa on pa.id = r.paquete_id 
						join bv_operadores o on o.id = pa.operador_id
						join bv_paquetes_combinaciones v on v.id = r.combinacion_id
						join bv_lugares_salida l on l.id = r.lugar_id
						join bv_sucursales s on s.id = r.sucursal_id
						join bv_movimientos m on m.usuario_id =r.usuario_id and m.tipoUsuario ='U' and m.reserva_id =r.id and m.talonario = f.tipo and f.id =m.factura_id 
						join (select re.id, CAST(vi.v_total*re.pasajeros as DECIMAL(10,2)) as total_viaje
										from bv_paquetes_combinaciones vi 
										join bv_reservas re on re.combinacion_id = vi.id
										join bv_paquetes pp on pp.id = re.paquete_id
										group by re.id
								) vp on vp.id = r.id
					where ".$where."
				) ppp 
				left join (select reserva_id, min(fecha) as fecha from `bv_reservas_comentarios` 
						where tipo_id = 6 and comentarios like '%confirmada%' 
						group by reserva_id ) c on c.reserva_id = ppp.reserva_id
				where ".$filtro_fechas."
				group by ppp.nro_cpte
				order by 3 asc";
		
		return $this->db->query($q);
	}

	function getReporteCostos_backup_130319($keywords='', $fecha_desde=FALSE, $fecha_hasta=FALSE){
		$where = "r.estado_id in (4,5,13,2)";
		if($keywords != ''){
			$where .= " and r.code like '%".$keywords."%' or o.nombre like '%".$keywords."%' or rf.f_nombre like '%".$keywords."%' or rf.f_apellido like '%".$keywords."%'";
		}
		
		/*
		12-06 'dato' Venta_Total si es pago en USD de viaje al exterior
		le adiciono el importe de percepcion
		*/

		$filtro_fechas = "1";
		if ($fecha_desde) {
			$filtro_fechas .= " and ppp.fecha >= '".$fecha_desde." 00:00:00'";
		}
		if ($fecha_hasta) {
			$filtro_fechas .= " and ppp.fecha <= '".$fecha_hasta." 23:59:59'";
		}

		$q = "select 
				ppp.tipo as 'Tipo Factura',
				ppp.Estado_Reserva as 'Estado Reserva',
				ifnull(c.fecha,ifnull(ppp.fecha,'')) as 'Fecha Confirmada',
				ppp.Cod_Reserva as 'Cod Reserva',
				ppp.Nombre_FILE as 'Nombre FILE',
				ppp.nro_cpte as 'Nro. Cpte',
				ppp.Pasajero as 'Pasajero',
				ppp.Operador_Mayorista as 'Operador',
				ppp.Moneda,
				ppp.tipo_cambio as 'Tipo de Cambio',
				sum(ppp.Venta_Total) as 'Venta Total',
				sum(ppp.Costo_Total) as 'Costo Total',
				sum(ppp.Utilidad) as 'Utilidad',
				sum(ppp.Venta_Neta) as 'Venta Neta',
				sum(ppp.Costo_Neto) as 'Costo Neto',
				sum(ppp.Utilidad_Neta) as 'Utilidad Neta',
				concat(avg(ppp.Porcentual_Neto),'%') as 'Porcentual Neto' 
				from 
				(
					select 
						#@com := CAST( v.v_comision*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_comision,					
						#@comvta := CAST( v.fee*v.c_costo_operador/1.21/100/v.v_total*ifnull(ifnull(f.intereses,0)+f.gastos_adm+f.valor,0) as DECIMAL(10,2)) as vta_comision,					
						#@iva21 := CAST( @rel*v.v_iva21*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_iva21,					
						#@iva10 := CAST( v.v_iva10*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_iva10,					
						#@ppv := CAST( v.v_total*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_venta,					
						#@ppvn := CAST( (v.v_total-v.v_iva21-v.v_iva10)*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_venta_neta,					
						#@ppc := CAST( v.c_costo_operador*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_costo,					
						#@ppcn := CAST( (v.c_costo_operador-v.c_iva21-v.c_iva10)*(case when pa.precio_usd=1 then m.tipo_cambio else 1 end) as DECIMAL(10,2)) as paquete_precio_costo_neto,					
						#@rel := cast(@pvt/@ppv as DECIMAL(10,2)) as relacion_pago_total,
						
						@perc := CAST( ifnull((CASE WHEN (pa.exterior and f.concepto_id IN (select (id) from `bv_conceptos` where nombre like '%deposito%')) THEN ifnull(ifnull(f.intereses,0)+ifnull(f.gastos_adm,0)+f.valor/1.05*0.05,0) ELSE 0 END ),0) as DECIMAL(10,2)) as percepcion,
						
						@pvt := ifnull( CAST(ifnull(ifnull(f.intereses,0)+f.gastos_adm+f.valor,0) as DECIMAL(10,2)) ,0) as 'Venta_Total',
						@pvsperc := ifnull(@pvt-@perc,0) as precio_venta_sin_perc,
						@gs := ifnull( CAST(ifnull(f.gastos_adm,0) as DECIMAL(10,2)) ,0) as gastos,
						@pvspsg := ifnull(@pvt-@perc-@gs,0) as precio_venta_sin_perc_ni_gs,				
						@gsn := CAST( ifnull((v.v_iva21+v.v_iva10)/v.v_total*@pvspsg + @gs/1.21*0.21,0) as DECIMAL(10,2)) as gsn, 
						#@vnaa := CAST( @pvsperc*((v.v_total-v.v_iva21-v.v_iva10)/v.v_total) as DECIMAL(10,2)) as 'Venta_Neta_NO', 
						@vn := CAST( ifnull(@pvt-@gsn,0) as DECIMAL(10,2)) as 'Venta_Neta', 
						@utn := CAST( ifnull((v.v_comision/v.v_total)*@pvspsg,0) as DECIMAL(10,2)) as 'Utilidad_Neta', 
						@cn := CAST( ifnull(@vn-@utn,0) as DECIMAL(10,2)) as 'Costo_Neto', 

						@porc := concat(CAST( ifnull(@utn/@vn*100,0) as DECIMAL(10,0)),'%') as 'Porcentual_Neto', 
						
						@ut := CAST( ifnull(@utn+@utn*.21,0) as DECIMAL(10,2)) as 'Utilidad', 
						@ctt := CAST( (ifnull(@pvt-@ut,0)) as DECIMAL(10,2)) as 'Costo_Total', 
						
						f.tipo,
						r.id as reserva_id,
						f.fecha,
						IFNULL(DATE_FORMAT(f.fecha,'%d/%m/%Y'),'-') as 'Fecha_Comprobante', 
						(case r.estado_id 
								when 5 then 'Anulada' 
								when 4 then 'Confirmada' 
								when 2 then 'Por Vencer' 
								when 13 then 'Espera Pago Bancario' 
						end) as 'Estado_Reserva', 
						r.code as 'Cod_Reserva',
						concat(f.tipo,'-',s.codigoFacturacion,'-',LPAD(f.id,8,'0')) as 'nro_cpte', 
						pa.nombre as 'Nombre_FILE',
						CONCAT(rf.f_nombre,' ',rf.f_apellido) as 'Pasajero',
						o.nombre as 'Operador_Mayorista',
						(case when pa.precio_usd =1 then 'Dolares' else 'Pesos' end) as 'Moneda',
						(CASE WHEN m.pago_usd = 1 then m.tipo_cambio else '' end) as tipo_cambio
						
						from bv_reservas r
						join bv_facturas f on f.reserva_id = r.id
						join bv_reservas_facturacion rf on rf.reserva_id = r.id
						join bv_paquetes pa on pa.id = r.paquete_id 
						join bv_operadores o on o.id = pa.operador_id
						join bv_paquetes_combinaciones v on v.id = r.combinacion_id
						join bv_lugares_salida l on l.id = r.lugar_id
						join bv_sucursales s on s.id = r.sucursal_id
						join bv_movimientos m on m.usuario_id =r.usuario_id and m.tipoUsuario ='U' and m.reserva_id =r.id and m.talonario = f.tipo and f.id =m.factura_id 
						join (select re.id, CAST(vi.v_total*re.pasajeros as DECIMAL(10,2)) as total_viaje
										from bv_paquetes_combinaciones vi 
										join bv_reservas re on re.combinacion_id = vi.id
										join bv_paquetes pp on pp.id = re.paquete_id
										group by re.id
								) vp on vp.id = r.id
					where ".$where."
				) ppp 
				left join (select reserva_id, min(fecha) as fecha from `bv_reservas_comentarios` 
						where tipo_id = 6 and comentarios like '%confirmada%' 
						group by reserva_id ) c on c.reserva_id = ppp.reserva_id
				where ".$filtro_fechas."
				group by ppp.nro_cpte
				order by 3 asc";
		
		return $this->db->query($q);
		
		/*
		$this->db->select("IFNULL(DATE_FORMAT(f.fecha,'%d/%m/%Y'),'-') as 'Fecha Comprobante', 
							(case r.estado_id 
									when 5 then 'Anulada' 
									when 4 then 'Confirmada' 
									when 2 then 'Por Vencer' 
									when 13 then 'Espera Pago Bancario' 
							end) as 'Estado Reserva', 
							r.code as 'Cod Reserva',
							pa.nombre as 'Nombre FILE',
							CONCAT(rf.f_nombre,' ',rf.f_apellido) as 'Pasajero',
							o.nombre as 'Operador',
							'Pesos' as 'Moneda',
							ifnull(f.valor,0) as 'Venta Total',
							ifnull(
								CAST((f.valor/vp.total_viaje)*v.c_costo_operador*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,0)
								as 'Costo Total',
							ifnull(
								(f.valor-(CAST((f.valor/vp.total_viaje)*v.c_costo_operador*r.pasajeros*r.cotizacion as DECIMAL(10,2)))) 
								,0)
								as 'Utilidad',
							ifnull(
								CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,0) 
								as 'Venta Neta',
							ifnull(
								CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,0) 
								as 'Costo Neto',
							ifnull(
								(CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))-CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))) 
								,0) 
								as 'Utilidad Neta',
							ifnull(
								concat(round(round(
									(CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))-CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))) 
									/
									CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,2)*100,0),'%') 
								,'0%') as 'Porcentual Neto'
						  ",false);
		$this->db->join("bv_facturas f","f.reserva_id = r.id");
		$this->db->join("bv_reservas_pasajeros rp","rp.id = f.usuario_id and rp.responsable");
		$this->db->join("bv_pasajeros p","p.id = rp.pasajero_id");
		$this->db->join("bv_reservas_facturacion rf","rf.reserva_id = r.id");
		$this->db->join("bv_paquetes pa","pa.id = r.paquete_id");
		$this->db->join("bv_operadores o","o.id = pa.operador_id");
		$this->db->join("bv_paquetes_combinaciones v","v.id = r.combinacion_id");
		$this->db->join("bv_lugares_salida l","l.id = r.lugar_id");
		$this->db->join("bv_sucursales s","s.id = r.sucursal_id");
		$this->db->join("(select re.id, CAST(vi.v_total*re.pasajeros*re.cotizacion as DECIMAL(10,2)) as total_viaje
									from bv_paquetes_combinaciones vi 
									join bv_reservas re on re.combinacion_id = vi.id
									group by re.id
						  ) vp","vp.id = r.id");
		if($keywords != ''){
			$this->db->where("r.code like '%".$keywords."%' or o.nombre like '%".$keywords."%' or rf.f_nombre like '%".$keywords."%' or rf.f_apellido like '%".$keywords."%'");
		}
		$this->db->where('r.estado_id in (4,5,13,2)');
		return $this->db->get('bv_reservas r');
		*/
	}

	function getReporteCostos_backup($keywords='', $fecha_desde=FALSE, $fecha_hasta=FALSE){
		$where = "r.estado_id in (4,5,13,2)";
		if($keywords != ''){
			$where .= " and r.code like '%".$keywords."%' or o.nombre like '%".$keywords."%' or rf.f_nombre like '%".$keywords."%' or rf.f_apellido like '%".$keywords."%'";
		}
		
		/*
		12-06 'dato' Venta_Total si es pago en USD de viaje al exterior
		le adiciono el importe de percepcion
		*/

		$filtro_fechas = "1";
		if ($fecha_desde) {
			$filtro_fechas .= " and c.fecha >= '".$fecha_desde."'";
		}
		if ($fecha_hasta) {
			$filtro_fechas .= " and c.fecha <= '".$fecha_hasta."'";
		}

		$q = "select 
				ppp.tipo as 'Tipo Factura',
				ppp.Estado_Reserva as 'Estado Reserva',
				ifnull(c.fecha,'') as 'Fecha Confirmada',
				ppp.Cod_Reserva as 'Cod Reserva',
				ppp.Nombre_FILE as 'Nombre FILE',
				ppp.nro_cpte as 'Nro. Cpte',
				ppp.Pasajero as 'Pasajero',
				ppp.Operador_Mayorista as 'Operador',
				ppp.Moneda,
				ppp.tipo_cambio as 'Tipo de Cambio',
				sum(ppp.Venta_Total) as 'Venta Total',
				sum(ppp.Costo_Total) as 'Costo Total',
				sum(ppp.Utilidad) as 'Utilidad',
				sum(ppp.Venta_Neta) as 'Venta Neta',
				sum(ppp.Costo_Neto) as 'Costo Neto',
				sum(ppp.Utilidad_Neta) as 'Utilidad Neta',
				concat(avg(ppp.Porcentual_Neto),'%') as 'Porcentual Neto' 
				from 
				(
					select f.tipo,
					r.id as reserva_id,
					f.fecha,
					IFNULL(DATE_FORMAT(f.fecha,'%d/%m/%Y'),'-') as 'Fecha_Comprobante', 
					(case r.estado_id 
							when 5 then 'Anulada' 
							when 4 then 'Confirmada' 
							when 2 then 'Por Vencer' 
							when 13 then 'Espera Pago Bancario' 
					end) as 'Estado_Reserva', 
					r.code as 'Cod_Reserva',
					concat(f.tipo,'-',s.codigoFacturacion,'-',LPAD(f.id,8,'0')) as 'nro_cpte', 
					pa.nombre as 'Nombre_FILE',
					CONCAT(rf.f_nombre,' ',rf.f_apellido) as 'Pasajero',
					o.nombre as 'Operador_Mayorista',
					(case when pa.precio_usd =1 then 'Dolares' else 'Pesos' end) as 'Moneda',
					(case when pa.precio_usd =1 then m.tipo_cambio else '' end) as tipo_cambio,  
					ifnull(
						CAST((CASE WHEN (pa.exterior and f.concepto_id IN (select (id) from `bv_conceptos` where nombre like '%deposito%')) THEN ifnull(f.gastos_adm+f.valor*1.05,0) ELSE ifnull(f.gastos_adm+f.valor,0) END ) as DECIMAL(10,2))
						,0) 
						as 'Venta_Total',
					ifnull(
						CAST((f.valor/vp.total_viaje)*v.c_costo_operador*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2)) 
						,0)
						as 'Costo_Total',
					ifnull(
						((CASE WHEN (pa.exterior and f.concepto_id IN (select (id) from `bv_conceptos` where nombre like '%deposito%')) THEN ifnull(f.gastos_adm+f.valor*1.05,0) ELSE ifnull(f.gastos_adm+f.valor,0) END )-(CAST((f.valor/vp.total_viaje)*v.c_costo_operador*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2)))) 
						,0)
						as 'Utilidad',
					ifnull(
						CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2)) 
						,0) 
						as 'Venta_Neta',
					ifnull(
						CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2)) 
						,0) 
						as 'Costo_Neto',
					ifnull(
						(CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2))-CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2))) 
						,0) 
						as 'Utilidad_Neta',
					ifnull(
						concat(round(round(
							(CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2))-CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2))) 
							/
							CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*(case when pa.precio_usd=1 then r.cotizacion else 1 end) as DECIMAL(10,2)) 
						,2)*100,0),'%') 
						,'0%') as 'Porcentual_Neto'
					from bv_reservas r
					join bv_facturas f on f.reserva_id = r.id
					join bv_reservas_facturacion rf on rf.reserva_id = r.id
					join bv_paquetes pa on pa.id = r.paquete_id 
					join bv_operadores o on o.id = pa.operador_id
					join bv_paquetes_combinaciones v on v.id = r.combinacion_id
					join bv_lugares_salida l on l.id = r.lugar_id
					join bv_sucursales s on s.id = r.sucursal_id
					join bv_movimientos m on m.usuario_id =r.usuario_id and m.tipoUsuario ='U' and m.reserva_id =r.id and m.talonario = f.tipo and f.id =m.factura_id 
					join (select re.id, CAST(vi.v_total*re.pasajeros*(case when pp.precio_usd=1 then re.cotizacion else 1 end) as DECIMAL(10,2)) as total_viaje
									from bv_paquetes_combinaciones vi 
									join bv_reservas re on re.combinacion_id = vi.id
									join bv_paquetes pp on pp.id = re.paquete_id
									group by re.id
						  ) vp on vp.id = r.id
					where ".$where."
				) ppp 
				join (select reserva_id, max(fecha) as fecha from `bv_reservas_comentarios` 
						where tipo_id = 6 and comentarios like '%confirmada%' 
						group by reserva_id ) c on c.reserva_id = ppp.reserva_id
				where ".$filtro_fechas."
				group by ppp.nro_cpte
				order by ppp.fecha desc";
		
		return $this->db->query($q);
		
		/*
		$this->db->select("IFNULL(DATE_FORMAT(f.fecha,'%d/%m/%Y'),'-') as 'Fecha Comprobante', 
							(case r.estado_id 
									when 5 then 'Anulada' 
									when 4 then 'Confirmada' 
									when 2 then 'Por Vencer' 
									when 13 then 'Espera Pago Bancario' 
							end) as 'Estado Reserva', 
							r.code as 'Cod Reserva',
							pa.nombre as 'Nombre FILE',
							CONCAT(rf.f_nombre,' ',rf.f_apellido) as 'Pasajero',
							o.nombre as 'Operador',
							'Pesos' as 'Moneda',
							ifnull(f.valor,0) as 'Venta Total',
							ifnull(
								CAST((f.valor/vp.total_viaje)*v.c_costo_operador*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,0)
								as 'Costo Total',
							ifnull(
								(f.valor-(CAST((f.valor/vp.total_viaje)*v.c_costo_operador*r.pasajeros*r.cotizacion as DECIMAL(10,2)))) 
								,0)
								as 'Utilidad',
							ifnull(
								CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,0) 
								as 'Venta Neta',
							ifnull(
								CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,0) 
								as 'Costo Neto',
							ifnull(
								(CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))-CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))) 
								,0) 
								as 'Utilidad Neta',
							ifnull(
								concat(round(round(
									(CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))-CAST((f.valor/vp.total_viaje)*(v.c_exento+v.c_nogravado+v.c_gravado21+v.c_gravado10+v.c_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2))) 
									/
									CAST((f.valor/vp.total_viaje)*(v.v_exento+v.v_nogravado+v.v_gravado21+v.v_gravado10+v.v_comision+v.v_otros_imp)*r.pasajeros*r.cotizacion as DECIMAL(10,2)) 
								,2)*100,0),'%') 
								,'0%') as 'Porcentual Neto'
						  ",false);
		$this->db->join("bv_facturas f","f.reserva_id = r.id");
		$this->db->join("bv_reservas_pasajeros rp","rp.id = f.usuario_id and rp.responsable");
		$this->db->join("bv_pasajeros p","p.id = rp.pasajero_id");
		$this->db->join("bv_reservas_facturacion rf","rf.reserva_id = r.id");
		$this->db->join("bv_paquetes pa","pa.id = r.paquete_id");
		$this->db->join("bv_operadores o","o.id = pa.operador_id");
		$this->db->join("bv_paquetes_combinaciones v","v.id = r.combinacion_id");
		$this->db->join("bv_lugares_salida l","l.id = r.lugar_id");
		$this->db->join("bv_sucursales s","s.id = r.sucursal_id");
		$this->db->join("(select re.id, CAST(vi.v_total*re.pasajeros*re.cotizacion as DECIMAL(10,2)) as total_viaje
									from bv_paquetes_combinaciones vi 
									join bv_reservas re on re.combinacion_id = vi.id
									group by re.id
						  ) vp","vp.id = r.id");
		if($keywords != ''){
			$this->db->where("r.code like '%".$keywords."%' or o.nombre like '%".$keywords."%' or rf.f_nombre like '%".$keywords."%' or rf.f_apellido like '%".$keywords."%'");
		}
		$this->db->where('r.estado_id in (4,5,13,2)');
		return $this->db->get('bv_reservas r');
		*/
	}
	
}