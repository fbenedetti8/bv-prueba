<?php
include "AdminController.php";

class Comisiones extends AdminController{

	function __construct() {
		parent::__construct();
		$this->page = "comisiones";
		$this->data['currentModule'] = "comisiones";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->data['page_title'] = "Comisiones";
		$this->init();
/*
		if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			ini_set('display_errors', true);
			error_reporting(E_ALL);
		}*/
	}

	function index() {
		$mes = $this->input->post('mes');
		$anio = $this->input->post('anio');
		$action = $this->input->post('action');
		$btnConfirmar = isset($_POST['btnConfirmar']) ? true : false;;

		$this->data['mensaje_error'] = '';
		$this->data['mensaje_exito'] = '';
		$this->data['anio'] = $anio;
		$this->data['mes'] = $mes;

		if ($action) {
			if ($mes=='' || $anio=='') {
				$this->data['mensaje_error'] = 'Por favor elija mes y año a liquidar.';
				$this->load->view('admin/comisiones', $this->data);
				return;
			}
			else {
				$this->data['periodo'] = monthName($mes).' - '.$anio;
				$this->data['liquidaciones'] = [];
			}

			$from = $anio.'-'.$mes.'-01';
			$to = date('Y-m-d', strtotime('+1 month', strtotime($from)));

			//Obtener la lista de reservas confirmadas del periodo
			$reservas = $this->db->query("
				SELECT 	r.id, r.code, r.vendedor_id, concat(v.nombre, ' ', v.apellido) as vendedor, p.porcentaje_comisionable, 
						CASE WHEN p.precio_usd=1 THEN (r.paquete_precio + r.impuestos + r.adicionales_precio) * r.cotizacion
							 ELSE (r.paquete_precio + r.impuestos + r.adicionales_precio)
						END as total_facturado
				FROM bv_reservas r, bv_paquetes p, bv_vendedores v,
			 		(select m.reserva_id, min(fecha) as fecha_confirmada
													from bv_movimientos m
													inner join bv_conceptos c on c.nombre = m.concepto
													where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1
													group by m.reserva_id
												) conf

				WHERE conf.reserva_id = r.id and r.paquete_id = p.id and v.id = r.vendedor_id and r.vendedor_id > 0 and conf.fecha_confirmada >= '$from' and conf.fecha_confirmada < '$to' and r.estado_id = 4")->result();

			//Calcular el monto comisionable con el porcentaje
			foreach ($reservas as $reserva) {
				$reserva->monto_comisionable = $reserva->total_facturado * $reserva->porcentaje_comisionable / 100;
			}

			//Calcular el monto comisionable con el porcentaje
			foreach ($reservas as $reserva) {
				$reserva->monto_comisionable = $reserva->total_facturado * $reserva->porcentaje_comisionable / 100;
			}

			//Preprocesar la lista de vendedores a los que corresponde liquidar
			$vendedores = [];
			foreach ($reservas as $r) {
				if (!isset($vendedores[$r->vendedor_id])) {
					$vendedores[$r->vendedor_id] = ['vendedor' => $r->vendedor, 'monto_facturado' => 0, 'monto_comisionable' => 0, 'reservas' => []];
				}
				$vendedores[$r->vendedor_id]['id']= $r->vendedor_id;
				$vendedores[$r->vendedor_id]['reservas'][] = $r;
				$vendedores[$r->vendedor_id]['monto_facturado'] += $r->total_facturado;
				$vendedores[$r->vendedor_id]['monto_comisionable'] += $r->monto_comisionable;
			}

			// if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			// 	echo "<br>VENDEDORES<br>";
			// 	pre($vendedores);
			// }
			
			foreach ($vendedores as $vendedor_id => &$vendedor) {
				$total_facturado = $vendedor['monto_facturado'];
				$total_comisionable = $vendedor['monto_comisionable'];
				
				//Buscar la escala que corresponde en funcion al total facturado
				$escala = $this->db->query("SELECT id FROM bv_comisiones_escalas WHERE desde <= $total_comisionable AND hasta > $total_comisionable")->row();
				if ($escala) {
					$escala_id = $escala->id;
				}

				//Definir el minimo no comisionable
				$minimo_no_comisionable = 0;

				//Primero buscar si hay un minimo no comisionable para ese vendedor para esa escala y periodo
				$mnc = $this->db->query("SELECT m.valor_mnc FROM bv_comisiones_minimos m, bv_vendedores v WHERE m.vendedor_id = v.id AND m.vendedor_id = $vendedor_id AND v.minimos_personalizados = 1 AND m.mes = $mes AND m.anio = $anio")->row();
				
				//Caso contrario buscar el valor global por defecto para esa escala y periodo
				if (!$mnc) {
					$mnc = $this->db->query("SELECT valor_mnc FROM bv_comisiones_minimos WHERE vendedor_id IS NULL AND rol = 'VEN' AND mes = $mes AND anio = $anio")->row();
					$minimo_no_comisionable = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;

					$mnc = $this->db->query("SELECT valor_mnc FROM bv_comisiones_minimos WHERE vendedor_id IS NULL AND rol = 'LID' AND mes = $mes AND anio = $anio")->row();
					$minimo_no_comisionable_lid = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;

					$mnc = $this->db->query("SELECT valor_mnc FROM bv_comisiones_minimos WHERE vendedor_id IS NULL AND rol = 'GER' AND mes = $mes AND anio = $anio")->row();
					$minimo_no_comisionable_ger = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;
				}
				else {
					$minimo_no_comisionable = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;
					$minimo_no_comisionable_lid = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;
					$minimo_no_comisionable_ger = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;
				}

				//Obtener los porcentajes que le corresponden al vendedor
				$comisiones_vendedor = $this->db->query("SELECT comision".$escala_id." as comision, comision".$escala_id."_eq as comision_eq FROM bv_vendedores WHERE comisiones_personalizadas=1 AND id = ".$vendedor_id)->row();

				$comision = 0; $comision_lid = 0; $comision_ger = 0;
				$comision_eq = 0; $comision_lid_eq = 0; $comision_ger_eq = 0;
				if ($comisiones_vendedor) {
					$comision = $comisiones_vendedor->comision;
					$comision_eq = $comisiones_vendedor->comision_eq;
					$comision_lid = $comision;
					$comision_lid_eq = $comision_eq;
					$comision_ger = $comision;
					$comision_ger_eq = $comision_eq;
				}
				else {
					$comisiones_vendedor = $this->db->query("SELECT comision, comision_eq FROM bv_comisiones_porcentajes WHERE rol='VEN' AND escala_id = ".$escala_id)->row();
					if ($comisiones_vendedor) {
						$comision = $comisiones_vendedor->comision;
						$comision_eq = $comisiones_vendedor->comision_eq;
					}

					$comisiones_lider = $this->db->query("SELECT comision, comision_eq FROM bv_comisiones_porcentajes WHERE rol='LID' AND escala_id = ".$escala_id)->row();
					if ($comisiones_lider) {
						$comision_lid = $comisiones_lider->comision;
						$comision_lid_eq = $comisiones_lider->comision_eq;
					}

					$comisiones_gerente = $this->db->query("SELECT comision, comision_eq FROM bv_comisiones_porcentajes WHERE rol='GER' AND escala_id = ".$escala_id)->row();
					if ($comisiones_gerente) {
						$comision_ger = $comisiones_gerente->comision;
						$comision_ger_eq = $comisiones_gerente->comision_eq;
					}
				}

				//Calcular el valor de comisiones propias
				$valor_comision_propia = 0;
				foreach ($vendedor['reservas'] as $reserva) {
					$valor_comision_propia += $reserva->monto_comisionable;
				}

				//Calcular el valor de comisiones de equipo
				$valor_comision_equipo = 0;

				//Grabar datos en el array de vendedores
				$vendedor['escala_id'] = $escala_id;
				$vendedor['comision'] = $comision;
				$vendedor['comision_eq'] = $comision_eq;
				$vendedor['comision_lid'] = $comision_lid;
				$vendedor['comision_lid_eq'] = $comision_lid_eq;
				$vendedor['comision_ger'] = $comision_ger;
				$vendedor['comision_ger_eq'] = $comision_ger_eq;
				$vendedor['valor_comision_propia'] = $valor_comision_propia;
				$vendedor['valor_comision_equipo'] = $valor_comision_equipo;
				$vendedor['minimo_no_comisionable'] = $minimo_no_comisionable;
				$vendedor['minimo_no_comisionable_lid'] = $minimo_no_comisionable_lid;
				$vendedor['minimo_no_comisionable_ger'] = $minimo_no_comisionable_ger;
				$vendedor['total_liquidacion'] = 0;
				$vendedor['reservas_equipo'] = [];
				$vendedor['monto_facturado_equipo'] = 0;
				$vendedor['monto_comisionable_equipo'] = 0;
			}

			/*if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
				echo "<br>VENDEDORES<br>";
				pre($vendedores);
			}*/

			//Calculadas las comisiones de cada vendedor, hay que analizar los equipos
			
			//Primero buscamos los miembros del mismo equipo
			foreach ($vendedores as $vendedor_id => &$vendedor_info) {
				$vendedoresEquipoArr = [];
				/*
				$vendedores_equipo = $this->db->query("SELECT vendedor_id FROM bv_vendedores_equipos WHERE equipo_id = (SELECT equipo_id FROM bv_vendedores_equipos WHERE vendedor_id = ".$vendedor_id.") and vendedor_id != ".$vendedor_id)->result();	
				*/

				//busco los miembros del equipo del vendedor siempre que este sea lider o gerente
				$vendedores_equipo = $this->db->query("select ve.* from bv_equipos e, bv_vendedores_equipos ve 
					where (e.coordinador_id = $vendedor_id or e.gerente_id = $vendedor_id) and e.id = ve.equipo_id")->result();	

				foreach ($vendedores_equipo as $vendedor_equipo) {
					$vendedoresEquipoArr[] = $vendedor_equipo->vendedor_id;
				}

				$comisiones_companeros = 0;
				$reservas_equipo = [];
				foreach ($vendedores as $v_id => &$vendedor_data) {
					if (in_array($v_id, $vendedoresEquipoArr)) {
						$comisiones_companeros += $vendedor_data['monto_comisionable'];
						
						$reservas_equipo = array_merge($reservas_equipo, $vendedor_data['reservas']);
					}
				}

				$vendedor_info['valor_comision_equipo'] = $comisiones_companeros;
				$vendedor_info['reservas_equipo'] = $reservas_equipo;

				$total_facturado_equipo = 0;
				$total_comisionable_equipo = 0;
				foreach ($reservas_equipo as $res) {
					$total_facturado_equipo += $res->total_facturado;
					$total_comisionable_equipo += $res->monto_comisionable;
				}

				$vendedor_info['monto_facturado_equipo'] = $total_facturado_equipo;
				$vendedor_info['monto_comisionable_equipo'] = $total_comisionable_equipo;
			}

/*
			if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
				echo "<br>VENDEDORES2<br>";
				pre($vendedores);
			}*/

			//los referentes son los lideres o gerentes que no deberian aparecer como vendedores en el reporte
			$referentes = [];
			//Ahora analizamos el caso de que el vendedor sea ademas lider de algun equipo
			foreach ($vendedores as $vendedor_id => &$vendedor_info) {
				$vendedoresEquipoArr = [];
				$vendedores_equipo = $this->db->query("SELECT vendedor_id FROM bv_vendedores_equipos WHERE equipo_id IN (SELECT id FROM bv_equipos WHERE coordinador_id = ".$vendedor_id.")")->result();	

				//Si hay vendedores es porque es lider de ellos
				if (count($vendedores_equipo)) {
					$referentes[$vendedor_id] = true;
				
					foreach ($vendedores_equipo as $vendedor_equipo) {
						$vendedoresEquipoArr[] = $vendedor_equipo->vendedor_id;
					}

					$comisiones_companeros = 0;
					$reservas_equipo = [];
					foreach ($vendedores as $v_id => &$vendedor_data) {
						if (in_array($v_id, $vendedoresEquipoArr)) {
							$comisiones_companeros += $vendedor_data['monto_comisionable'];

							$reservas_equipo = array_merge($reservas_equipo, $vendedor_data['reservas']);
						}
					}

					$vendedor_info['valor_comision_equipo'] = $comisiones_companeros;
					$vendedor_info['comision'] = $vendedor_info['comision_lid'];
					$vendedor_info['comision_eq'] = $vendedor_info['comision_lid_eq'];
					$vendedor_info['minimo_no_comisionable'] = $vendedor_info['minimo_no_comisionable_lid'];
					$vendedor_info['reservas_equipo'] = $reservas_equipo;

					$total_facturado_equipo = 0;
					$total_comisionable_equipo = 0;
					foreach ($reservas_equipo as $res) {
						$total_facturado_equipo += $res->total_facturado;
						$total_comisionable_equipo += $res->monto_comisionable;
					}

					$vendedor_info['monto_facturado_equipo'] = $total_facturado_equipo;
					$vendedor_info['monto_comisionable_equipo'] = $total_comisionable_equipo;
				}
			}
/*
			if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
				echo "<br>coordinador2<br>";
				pre($vendedores);
			}*/

			//Ahora analizamos el caso de que el vendedor sea ademas gerente de algun equipo
			foreach ($vendedores as $vendedor_id => &$vendedor_info) {
				$vendedoresEquipoArr = [];
				$vendedores_equipo = $this->db->query("SELECT vendedor_id FROM bv_vendedores_equipos WHERE equipo_id IN (SELECT id FROM bv_equipos WHERE gerente_id = ".$vendedor_id.")")->result();
				
				//Si hay vendedores es porque es gerente de ellos
				if (count($vendedores_equipo)) {
					$referentes[$vendedor_id] = true;

					foreach ($vendedores_equipo as $vendedor_equipo) {
						$vendedoresEquipoArr[] = $vendedor_equipo->vendedor_id;
					}

					$comisiones_companeros = 0;
					$reservas_equipo = [];
					foreach ($vendedores as $v_id => &$vendedor_data) {
						if (in_array($v_id, $vendedoresEquipoArr)) {
							$comisiones_companeros += $vendedor_data['monto_comisionable'];

							$reservas_equipo = array_merge($reservas_equipo, $vendedor_data['reservas']);
						}
					}

					$vendedor_info['valor_comision_equipo'] = $comisiones_companeros;
					$vendedor_info['comision'] = $vendedor_info['comision_ger'];
					$vendedor_info['comision_eq'] = $vendedor_info['comision_ger_eq'];
					$vendedor_info['minimo_no_comisionable'] = $vendedor_info['minimo_no_comisionable_ger'];
					$vendedor_info['reservas_equipo'] = $reservas_equipo;

					$total_facturado_equipo = 0;
					$total_comisionable_equipo = 0;
					foreach ($reservas_equipo as $res) {
						$total_facturado_equipo += $res->total_facturado;
						$total_comisionable_equipo += $res->monto_comisionable;
					}

					$vendedor_info['monto_facturado_equipo'] = $total_facturado_equipo;
					$vendedor_info['monto_comisionable_equipo'] = $total_comisionable_equipo;
				}
			}
/*
			if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
				echo "<br>gerente2<br>";
				pre($vendedores);
			}
*/
			//Finalmente calcular los totales a liquidar
			foreach ($vendedores as $vendedor_id => &$vendedor_info) {
				$vendedor_info['valor_comision_propia'] = $vendedor_info['valor_comision_propia'] * $vendedor_info['comision'] / 100;				
				$vendedor_info['valor_comision_equipo'] = $vendedor_info['valor_comision_equipo'] * $vendedor_info['comision_eq'] / 100;				
				$vendedor_info['total_liquidacion'] = $vendedor_info['valor_comision_propia'] + $vendedor_info['valor_comision_equipo'] - $vendedor_info['minimo_no_comisionable'];

				if ($vendedor_info['total_liquidacion'] < 0) $vendedor_info['total_liquidacion'] = 0;
			}

/*
			if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
				echo "<br>totalesliquidar<br>";
				pre($vendedores);
			}*/
			/* Si un vendedor es LIDER O GERENTE, lo saco del listado de VENDEDORES */
			/*
			foreach ($referentes as $v_id=>$st) {
				if(isset($vendedores[$v_id]) && $vendedores[$v_id]){
					unset($vendedores[$v_id]);
				}
			}
			*/

			/*if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
				echo "<br>VENDEDORES<br>";
				pre($vendedores);
			}*/


			//Ahora hay que calcular las comisiones para admins
			$lideres = $this->_calcularLiquidacionAdmins('LID', $vendedores, $mes, $anio);
			/*
			if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
				echo "<br>totalesliquidar<br>";
				pre($lideres);
			}*/
			
			$gerentes = $this->_calcularLiquidacionAdmins('GER', $vendedores, $mes, $anio);

			/*
			foreach ($lideres as $ll) {
				$referentes[$ll['id']] = true;
			}

			// Si un vendedor es LIDER O GERENTE, lo saco del listado de VENDEDORES 
			foreach ($referentes as $v_id=>$st) {
				if(isset($vendedores[$v_id]) && $vendedores[$v_id]){
					$lideres[$v_id] = $vendedores[$v_id];
					unset($vendedores[$v_id]);
					unset($referentes[$v_id]);
				}
			}

			foreach ($gerentes as $g) {
				$referentes[$g['id']] = true;
			}

			// Si un vendedor es LIDER O GERENTE, lo saco del listado de VENDEDORES 
			foreach ($referentes as $v_id=>$st) {
				if(isset($vendedores[$v_id]) && $vendedores[$v_id]){
					$gerentes[$v_id] = $vendedores[$v_id];
					unset($vendedores[$v_id]);
					unset($referentes[$v_id]);
				}
			}
			*/

			//chequeo si para este mes elegido, ya fueron confirmadas las liquidaciones
			if($anio && $mes){
				$this->verificar_confirmadas($anio,$mes);
			}

			$liquidar = $this->input->post('liquidar');

			if ($liquidar) {

				//solo le voy a generar la liquidación cuando no haya sido generada previamente
				if(!isset($this->data['cant_confirmadas'])){
					$this->_borrarLiquidacion($anio, $mes);

					$this->_generarLiquidacion($anio, $mes, $vendedores, 'Vendedor', $btnConfirmar);
					$this->_generarLiquidacion($anio, $mes, $lideres, 'Lider', $btnConfirmar);
					$this->_generarLiquidacion($anio, $mes, $gerentes, 'Gerente', $btnConfirmar);
				}
				else{
					$this->_obtenerLiquidacion($anio, $mes, $vendedores, 'Vendedor', $btnConfirmar);
					$this->_obtenerLiquidacion($anio, $mes, $lideres, 'Lider', $btnConfirmar);
					$this->_obtenerLiquidacion($anio, $mes, $gerentes, 'Gerente', $btnConfirmar);
				}
				
				//unifico los lideres y los saco de vendedores
				foreach($lideres as &$l){

					$l['total_liquidacion'] = 0;

					//si el lider está en array de venedores, lo dejo solo en lideres
					if(isset($vendedores[$l['id']]) && $vendedores[$l['id']]){
						$l['liquidacion_id'] = @$vendedores[$l['id']]['liquidacion_id'];
						$l['monto_facturado'] += @$vendedores[$l['id']]['monto_facturado'];
						$l['monto_comisionable'] += @$vendedores[$l['id']]['monto_comisionable'];
						$l['valor_comision_propia'] += @$vendedores[$l['id']]['valor_comision_propia'];
						$l['total_liquidacion'] += @$vendedores[$l['id']]['total_liquidacion'];
					
						unset($vendedores[$l['id']]);
					}
					
				}

				//unifico los gerentes y los saco de vendedores
				foreach($gerentes as &$l){

					$l['total_liquidacion'] = 0;
					
					//si el lider está en array de venedores, lo dejo solo en gerentes
					if(isset($vendedores[$l['id']]) && $vendedores[$l['id']]){
						$l['liquidacion_id'] = @$vendedores[$l['id']]['liquidacion_id'];
						$l['monto_facturado'] += @$vendedores[$l['id']]['monto_facturado'];
						$l['monto_comisionable'] += @$vendedores[$l['id']]['monto_comisionable'];
						$l['valor_comision_propia'] += @$vendedores[$l['id']]['valor_comision_propia'];
						$l['total_liquidacion'] += @$vendedores[$l['id']]['total_liquidacion'];
					
						unset($vendedores[$l['id']]);
					}
					
				}


				$this->data['mensaje_exito'] = 'Las comisiones fueron generadas con exito.';

				$log = "<h3>VENDEDORES</h3>";
				$log .= "<table class='table'>";
				$log .= "<tr>";
				$log .= "<th>Vendedor</th>";
				$log .= "<th class='text-right'>Total facturado</th>";
				$log .= "<th class='text-right'>Total comisionable</th>";
				$log .= "<th class='text-right'>Reservas</th>";
				$log .= "<th class='text-center'>Escala</th>";
				$log .= "<th class='text-right'>% Com</th>";
				$log .= "<th class='text-right'>% Eq</th>";
				$log .= "<th class='text-right'>Comision</th>";
				$log .= "<th class='text-right'>Comision Eq</th>";
				$log .= "<th class='text-right'>Minimo No Com.</th>";
				$log .= "<th class='text-right'>Total liquidacion</th>";
				$log .= "</tr>";
				foreach ($vendedores as &$vendedor) {
					//Log
					$log .= "<tr>";
					$log .= "<td>".$vendedor['vendedor']."</td>";
					$log .= "<td class='text-right'>$".number_format($vendedor['monto_facturado'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($vendedor['monto_comisionable'], 2, ",", ".")."</td>";
					$log .= "<td>
								<a href='#' class='btn btn-primary btn-xs btn-block btnDetalleVentas' data-liquidacion='".$vendedor['liquidacion_id']."' data-tipo='propia'><i class='glyphicon glyphicon-search'></i> Propias</a>
								<a href='#' class='btn btn-success btn-xs btnDetalleVentas' data-liquidacion='".$vendedor['liquidacion_id']."' data-tipo='equipo'><i class='glyphicon glyphicon-search'></i> Equipo</a>
							</td>";
					$log .= "<td class='text-center'>#".$vendedor['escala_id']."</td>";
					$log .= "<td class='text-right'>".$vendedor['comision']."%</td>";
					$log .= "<td class='text-right'>".$vendedor['comision_eq']."%</td>";
					$log .= "<td class='text-right'>$".number_format($vendedor['valor_comision_propia'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($vendedor['valor_comision_equipo'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($vendedor['minimo_no_comisionable'], 2, ",", ".")."</td>";			
					$log .= "<td class='text-right'>$".number_format($vendedor['total_liquidacion'], 2, ",", ".")."</td>";
					$log .= "</tr>";			
				}
				$log .= "</table>";

				$log .= "<h3>LIDERES DE EQUIPOS</h3>";
				$log .= "<table class='table'>";
				$log .= "<tr>";
				$log .= "<th>Lider</th>";
				$log .= "<th class='text-right'>Total facturado</th>";
				$log .= "<th class='text-right'>Total comisionable</th>";
				$log .= "<th>Reservas</th>";
				$log .= "<th class='text-center'>Escala</th>";
				$log .= "<th class='text-right'>% Com</th>";
				$log .= "<th class='text-right'>% Eq</th>";
				$log .= "<th class='text-right'>Comision</th>";
				$log .= "<th class='text-right'>Comision Eq</th>";
				$log .= "<th class='text-right'>Minimo No Com.</th>";
				$log .= "<th class='text-right'>Total liquidacion</th>";
				$log .= "</tr>";

				foreach ($lideres as &$lider) {
					//Log
					$log .= "<tr>";
					$log .= "<td>".$lider['nombre']."</td>";
					$log .= "<td class='text-right'>$".number_format($lider['monto_facturado'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($lider['monto_comisionable'], 2, ",", ".")."</td>";
					$log .= "<td>
								<a href='#' class='btn btn-primary btn-xs btn-block btnDetalleVentas' data-liquidacion='".$lider['liquidacion_id']."' data-tipo='propia'><i class='glyphicon glyphicon-search'></i> Propias</a>
								<a href='#' class='btn btn-success btn-xs btnDetalleVentas' data-liquidacion='".$lider['liquidacion_id']."' data-tipo='equipo'><i class='glyphicon glyphicon-search'></i> Equipo</a>
							</td>";
					$log .= "<td class='text-center'>#".$lider['escala_id']."</td>";
					$log .= "<td class='text-right'>".$lider['comision']."%</td>";
					$log .= "<td class='text-right'>".$lider['comision_eq']."%</td>";
					$log .= "<td class='text-right'>$".number_format($lider['valor_comision_propia'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($lider['valor_comision_equipo'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($lider['minimo_no_comisionable'], 2, ",", ".")."</td>";			
					$log .= "<td class='text-right'>$".number_format($lider['total_liquidacion'], 2, ",", ".")."</td>";
					$log .= "</tr>";			
				}
				$log .= "</table>";

				$log .= "<h3>GERENTES DE EQUIPOS</h3>";
				$log .= "<table class='table'>";
				$log .= "<tr>";
				$log .= "<th>Lider</th>";
				$log .= "<th class='text-right'>Total facturado</th>";
				$log .= "<th class='text-right'>Total comisionable</th>";
				$log .= "<th>Reservas</th>";
				$log .= "<th class='text-center'>Escala</th>";
				$log .= "<th class='text-right'>% Com</th>";
				$log .= "<th class='text-right'>% Eq</th>";
				$log .= "<th class='text-right'>Comision</th>";
				$log .= "<th class='text-right'>Comision Eq</th>";
				$log .= "<th class='text-right'>Minimo No Com.</th>";
				$log .= "<th class='text-right'>Total liquidacion</th>";
				$log .= "</tr>";
				foreach ($gerentes as &$gerente) {
					//Log
					$log .= "<tr>";
					$log .= "<td>".$gerente['nombre']."</td>";
					$log .= "<td class='text-right'>$".number_format($gerente['monto_facturado'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($gerente['monto_comisionable'], 2, ",", ".")."</td>";
					$log .= "<td>
								<a href='#' class='btn btn-primary btn-xs btn-block btnDetalleVentas' data-liquidacion='".$gerente['liquidacion_id']."' data-tipo='propia'><i class='glyphicon glyphicon-search'></i> Propias</a>
								<a href='#' class='btn btn-success btn-xs btnDetalleVentas' data-liquidacion='".$gerente['liquidacion_id']."' data-tipo='equipo'><i class='glyphicon glyphicon-search'></i> Equipo</a>
							</td>";
					$log .= "<td class='text-center'>#".$gerente['escala_id']."</td>";
					$log .= "<td class='text-right'>".$gerente['comision']."%</td>";
					$log .= "<td class='text-right'>".$gerente['comision_eq']."%</td>";
					$log .= "<td class='text-right'>$".number_format($gerente['valor_comision_propia'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($gerente['valor_comision_equipo'], 2, ",", ".")."</td>";
					$log .= "<td class='text-right'>$".number_format($gerente['minimo_no_comisionable'], 2, ",", ".")."</td>";			
					$log .= "<td class='text-right'>$".number_format($gerente['total_liquidacion'], 2, ",", ".")."</td>";
					$log .= "</tr>";			
				}
				$log .= "</table>";
				$this->data['log'] = $log;
			}
		}

		//chequeo si para este mes elegido, ya fueron confirmadas las liquidaciones
		//nuevamente por si acaban de ser generadas
		if($anio && $mes){
			$this->verificar_confirmadas($anio,$mes);
		}

		$this->load->view('admin/comisiones', $this->data);
	}

	function _calcularLiquidacionAdmins($rol, $vendedores, $mes, $anio) {
		switch ($rol) {
			case 'LID': $field = 'coordinador_id'; break;
			case 'GER': $field = 'gerente_id'; break;
		}

		$admins = [];
		//$usuarios = $this->db->query("SELECT DISTINCT a.id, a.nombre FROM bv_admins a, bv_equipos e WHERE a.id = e.".$field)->result();
		
		//si el rol es LIDER
		if($field == 'coordinador_id'){
			$usuarios = $this->db->query("SELECT DISTINCT 
					( case when e.coordinador_tipo = 'V' then v.id else a.id end ) as id, 
					( case when e.coordinador_tipo = 'V' then concat(v.nombre,' ',v.apellido) else a.nombre end ) as nombre,
					e.coordinador_tipo as tipo
					FROM bv_equipos e 
					left join bv_admins a on a.id = e.coordinador_id
					left join bv_vendedores v on v.id = e.coordinador_id")->result();
		}
		else{
			//GERENTE
			$usuarios = $this->db->query("SELECT DISTINCT 
					( case when e.gerente_tipo = 'V' then v.id else a.id end ) as id, 
					( case when e.gerente_tipo = 'V' then concat(v.nombre,' ',v.apellido) else a.nombre end ) as nombre,
					e.gerente_tipo as tipo
					FROM bv_equipos e 
					left join bv_admins a on a.id = e.gerente_id
					left join bv_vendedores v on v.id = e.gerente_id")->result();
		}
		
		/*if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
			pre($usuarios);
		}*/

		foreach ($usuarios as $usuario) {
			if($usuario->id){

				$misVendedores = $this->db->query("SELECT DISTINCT vendedor_id FROM bv_vendedores_equipos ve, bv_equipos e WHERE ve.equipo_id = e.id AND e.".$field." = ".$usuario->id)->result();

				$misVendedoresArr = [];
				foreach ($misVendedores as $v) {
					$misVendedoresArr[] = $v->vendedor_id;
				}
			
				//Verificar si es lider
				//28-09-2018 juan pidio que si un lider o gerente no tiene vendedores, que igualmente aparezca en la tabla de lider o gerente
				if (count($misVendedores)) {
					$admins[] = [
						'id' => $usuario->id,
						'nombre' => $usuario->nombre,
						'tipo' => $usuario->tipo,
						'vendedores' => $misVendedoresArr,
						'monto_facturado' => 0,
						'monto_comisionable' => 0
					];
				}
				else{
					//si el lider o gerente no tiene vendedores en equipos, tomo las comisiones de sus ventas y se las cargo acá
					/*$admins[] = [
						'id' => $usuario->id,
						'nombre' => $usuario->nombre,
						'tipo' => $usuario->tipo,
						'vendedores' => $vendedores[$usuario->id],
						'monto_facturado' => 0,
						'monto_comisionable' => 0
					];*/
				}
			}
		}

		foreach ($admins as &$admin) {
			$admin_id = $admin['id'];
			//tipo es ADMIN (A) o VENDEDOR (V)
			$tipo = $admin['tipo'];

			//if(count($admin['vendedores'])){
				foreach ($vendedores as $vendedor_id => $vendedor_info) {
					if (in_array($vendedor_id, $admin['vendedores'])) {
						$admin['monto_facturado'] += $vendedor_info['monto_facturado'];
						$admin['monto_comisionable'] += $vendedor_info['monto_comisionable'];
					}
				}

				$total_comisionable = $admin['monto_comisionable'];

				//Buscar la escala que corresponde en funcion al total comisionable del equipo
				$escala = $this->db->query("SELECT id FROM bv_comisiones_escalas WHERE desde <= $total_comisionable AND hasta > $total_comisionable")->row();
				if ($escala) {
					$escala_id = $escala->id;
					$admin['escala_id'] = $escala_id;
				}
				else {
					$admin['escala_id'] = 0;
				}

				//Definir el minimo no comisionable
				$minimo_no_comisionable = 0;

				//Primero buscar si hay un minimo no comisionable para ese lider para esa escala y periodo
				if($tipo = 'V'){
					//tipo vendedor
					$mnc = $this->db->query("SELECT m.valor_mnc FROM bv_comisiones_minimos m, bv_vendedores v WHERE m.vendedor_id = v.id AND m.vendedor_id = $admin_id AND v.minimos_personalizados = 1 AND m.mes = $mes AND m.anio = $anio")->row();
				}
				else{
					//tipo admin
					$mnc = $this->db->query("SELECT m.valor_mnc FROM bv_comisiones_minimos m, bv_admins v WHERE m.admin_id = v.id AND m.admin_id = $admin_id AND v.minimos_personalizados = 1 AND m.mes = $mes AND m.anio = $anio")->row();
				}

				//Caso contrario buscar el valor global por defecto para esa escala y periodo
				if (!$mnc) {
					
					if($tipo = 'V'){
						//tipo vendedor
						$mnc = $this->db->query("SELECT valor_mnc FROM bv_comisiones_minimos WHERE vendedor_id IS NULL AND rol = '$rol' AND mes = $mes AND anio = $anio")->row();
					}
					else{
						//tipo admin
						$mnc = $this->db->query("SELECT valor_mnc FROM bv_comisiones_minimos WHERE admin_id IS NULL AND rol = '$rol' AND mes = $mes AND anio = $anio")->row();
					}

					$minimo_no_comisionable = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;
				}
				else {
					$minimo_no_comisionable = isset($mnc->valor_mnc) ? $mnc->valor_mnc : 0;
				}

				$admin['minimo_no_comisionable'] = $minimo_no_comisionable;

				//Obtener los porcentajes que le corresponden al lider /gerente
				if($tipo == 'A'){
					//tipo vendedor
					$comisiones_lider = $this->db->query("SELECT comision".$escala_id." as comision, comision".$escala_id."_eq as comision_eq FROM bv_admins WHERE comisiones_personalizadas=1 AND id = ".$admin_id)->row();
				}
				else{
					//tipo admin
					$comisiones_lider = $this->db->query("SELECT comision".$escala_id." as comision, comision".$escala_id."_eq as comision_eq FROM bv_vendedores WHERE comisiones_personalizadas=1 AND id = ".$admin_id)->row();
				}

				$comision = 0;
				$comision_eq = 0;
				if ($comisiones_lider) {
					$comision = $comisiones_lider->comision;
					$comision_eq = $comisiones_lider->comision_eq;
				}
				else {
					$comisiones_lider = $this->db->query("SELECT comision, comision_eq FROM bv_comisiones_porcentajes WHERE rol='$rol' AND escala_id = ".$escala_id)->row();
					if ($comisiones_lider) {
						$comision = $comisiones_lider->comision;
						$comision_eq = $comisiones_lider->comision_eq;
					}
				}

				$admin['comision'] = $comision;
				$admin['comision_eq'] = $comision_eq;
				
				//Comision propia no va a tener porque no es vendedor
				$admin['valor_comision_propia'] = 0;

				//Calcular el valor de comisiones del equipo
				$valor_comision_equipo = 0;
				$reservas_equipo = [];
				foreach ($vendedores as $vendedor_id => $vendedor_info) {
					if (in_array($vendedor_id, $admin['vendedores'])) {
						$valor_comision_equipo += $vendedor_info['monto_comisionable'];

						$reservas_equipo = array_merge($reservas_equipo, $vendedor_info['reservas']);
					}
				}

				$total_facturado_equipo = 0;
				$total_comisionable_equipo = 0;
				foreach ($reservas_equipo as $res) {
					$total_facturado_equipo += $res->total_facturado;
					$total_comisionable_equipo += $res->monto_comisionable;
				}

				$admin['monto_facturado_equipo'] = $total_facturado_equipo;
				$admin['monto_comisionable_equipo'] = $total_comisionable_equipo;
				$admin['reservas_equipo'] = $reservas_equipo;
				$admin['valor_comision_equipo'] = $valor_comision_equipo * $comision_eq / 100;
				// $admin['total_liquidacion'] = $admin['valor_comision_equipo'] - $admin['minimo_no_comisionable'];
				$admin['total_liquidacion'] = $admin['valor_comision_propia'] + $admin['valor_comision_equipo'] - $admin['minimo_no_comisionable'];
				if ($admin['total_liquidacion'] < 0) $admin['total_liquidacion'] = 0;
			//}
			
		}

		return $admins;
	}

	function _borrarLiquidacion($anio, $mes) {
		//Por ahora borro antes de generar
		$this->db->where(['anio' => $anio, 'mes' => $mes]);
		$this->db->delete('bv_comisiones_liquidaciones');
		$this->db->query("DELETE FROM bv_comisiones_liquidaciones_reservas WHERE liquidacion_id NOT IN (SELECT id FROM bv_comisiones_liquidaciones)");		
	}

	function _obtenerLiquidacion($anio, $mes, &$vendedores, $jerarquia, $btnConfirmar=false) {
		$liquidaciones = $this->db->get_where('bv_comisiones_liquidaciones', array('anio' => $anio, 'mes' => $mes, 'jerarquia' => $jerarquia))->result();
		
		//por cada liquidacion, se la pego al vendedor que le corresponda
		foreach ($liquidaciones as $l) {
			foreach ($vendedores as &$vendedor) {
				if( isset($vendedor['vendedor']) && $vendedor['vendedor'] == $l->usuario ){

					$vendedor['liquidacion_id'] = $l->id; 	

				}
				
			}
		}

	}

	function _generarLiquidacion($anio, $mes, &$vendedores, $jerarquia, $btnConfirmar=false) {
		$comisiones = $this->db->get_where('bv_comisiones_liquidaciones', array('anio' => $anio, 'mes' => $mes, 'jerarquia' => $jerarquia))->result();
		if (count($comisiones)) {
			return FALSE;
		}

		foreach ($vendedores as &$vendedor) {
			$this->db->insert('bv_comisiones_liquidaciones', array(
				'anio' => $anio,
				'mes' => $mes,
				'fecha_liquidacion' => date('Y-m-d H:i:s'),
				'usuario' => isset($vendedor['vendedor']) ? $vendedor['vendedor'] : $vendedor['nombre'],
				'jerarquia' => $jerarquia,
				'equipo' => '',
				'paquetes_directos' => isset($vendedor['reservas']) ? count($vendedor['reservas']) : 0,
				'total_venta_directa' => isset($vendedor['monto_facturado']) ? $vendedor['monto_facturado'] : 0,
				'total_venta_directa_comisionable' => isset($vendedor['monto_comisionable']) ? $vendedor['monto_comisionable'] : 0,
				'paquetes_equipo' => count($vendedor['reservas_equipo']),
				'total_venta_equipo' => $vendedor['monto_facturado_equipo'],
				'total_venta_equipo_comisionable' => $vendedor['monto_comisionable_equipo'],
				'porcentaje_comision_directa' => $vendedor['comision'],
				'porcentaje_comision_equipo' => $vendedor['comision_eq'],
				'comision_directa' => $vendedor['valor_comision_propia'],
				'comision_equipo' => $vendedor['valor_comision_equipo'],
				'minimo_no_comisionable' => $vendedor['minimo_no_comisionable'],
				'total_comision' => $vendedor['total_liquidacion'],
			));

			$liquidacion_id = $this->db->insert_id();

			//si confirmó la liquidación, entonces le genero el movimiento en la cuenta al vendedor
			if($btnConfirmar){
				if($vendedor['id'] > 0 && $vendedor['total_liquidacion'] > 0){
					$fecha = date('Y-m-d H:i:s');
					$moneda='ARS';
					$tipo_cambio = $CI->settings->cotizacion_dolar;
					registrar_movimiento_cta_cte($vendedor['id'],"V",0,$fecha,"Comisiones liquidadas del mes ".$mes." - ".$anio,$vendedor['total_liquidacion'],0.00,0,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio,false,$alta_de_reserva=false);				

					$this->db->query("update bv_comisiones_liquidaciones set confirmada = 1, fecha_confirmada = '".$fecha."' where id = ".$liquidacion_id);
				}
			}

			$vendedor['liquidacion_id'] = $liquidacion_id;

			if (isset($vendedor['reservas'])) {
				foreach ($vendedor['reservas'] as $reserva) {
					$this->db->insert('bv_comisiones_liquidaciones_reservas', array(
						'liquidacion_id' => $liquidacion_id,
						'tipo' => 'propia',
						'reserva_id' => $reserva->id,
						'codigo' => $reserva->code,
						'vendedor' => $reserva->vendedor,
						'total_facturado' => $reserva->total_facturado,
						'monto_comisionable' => $reserva->monto_comisionable
					));
				}
			}

			foreach ($vendedor['reservas_equipo'] as $reserva) {
				$this->db->insert('bv_comisiones_liquidaciones_reservas', array(
					'liquidacion_id' => $liquidacion_id,
					'tipo' => 'equipo',
					'reserva_id' => $reserva->id,
					'codigo' => $reserva->code,
					'vendedor' => $reserva->vendedor,
					'total_facturado' => $reserva->total_facturado,
					'monto_comisionable' => $reserva->monto_comisionable
				));
			}
		}		
	}

	function verificar_confirmadas($anio,$mes){
		//si las comisiones de este mes ya están liquidadas, muestro mensaje alerta y oculto el boton de CONFIRMAR
		$rows = $this->db->get_where('bv_comisiones_liquidaciones',array('anio'=>$anio,'mes'=>$mes,'confirmada'=>'1'))->result();
		$cant = count($rows);
		if($cant){
			$this->data['cant_confirmadas'] = $cant;
			$this->data['mensaje_confirmadas'] = 'Las liquidaciones de este mes ya fueron confirmadas el día <b>'.date('d/m/Y H:i',strtotime($rows[0]->fecha_confirmada)).'</b>';
		}
	}

}
