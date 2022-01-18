<?php
include "AdminController.php";

class Facturacion extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Factura_model', 'Factura');
		$this->load->model('Reserva_model', 'Reserva');
		$this->load->model('Usuario_model', 'Usuario');
		$this->load->model('Vendedor_model', 'Vendedor');
		$this->load->model('Operador_model', 'Operador');
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Paquete_model', 'Paquete');
		$this->load->model('Mailing_model','Mailing');
		$this->load->model('Combinacion_model','Combinacion');
		$this->load->model('Reserva_pasajero_model','Reserva_pasajero');
		$this->load->model('Reserva_facturacion_model','Reserva_facturacion');
		$this->load->model('Reserva_estado_model','Reserva_estado');
		$this->load->model('Reserva_informe_pago_model','Reserva_informe_pago');
		$this->load->model('Reserva_voucher_model', 'Reserva_voucher');	
		$this->model = $this->Factura;
		$this->page = "facturacion";
		$this->data['currentModule'] = "reportes";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Facturacion";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
		
		$this->load->model('Sucursal_model','Sucursal');
		$this->data['sucursales'] = $this->Sucursal->getAll(999,0,'nombre','asc')->result();
		$this->data['numeradora'] = $this->db->query('select sucursal_id,FA_B,FA_X,NC_B,NC_X,RE_X from bv_numeradora ')->result();
		$this->is_nelson = false;
		if(userloggedId() == 16){
			$this->is_nelson = true;
			$this->Sucursal->filters = 'id = 2';
			$this->data['sucursales'] = $this->Sucursal->getAll(999,0,'nombre','asc')->result();
		}

		ini_set('max_execution_time','3600');
		ini_set('memory_limit','1024M');
	}

	function filtro_por_mes(){
		/*
		$_POST['fecha_desde'] = '01-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-'.$_POST['anio'];
		$_POST['fecha_hasta'] = '31-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-'.$_POST['anio'];
		*/
		
		$_POST['fecha_desde'] = $_POST['anio'].'-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-01';
		$_POST['fecha_hasta'] = $_POST['anio'].'-'.str_pad($_POST['mes'],2,0,STR_PAD_LEFT).'-31';
		
		$this->data['anio'] = $_POST['anio'];
		$this->data['mes'] = $_POST['mes'];
		$this->data['filtro_mes'] = true;
		$this->session->set_userdata('filtro_mes',true);
		$this->session->set_userdata('anio',$_POST['anio']);
		$this->session->set_userdata('mes',$_POST['mes']);
		
		$this->filtrar_fechas();
	}
	
	function filtrar_fechas(){
		if(isset($_POST['fecha_desde']) && isset($_POST['fecha_hasta']) && $_POST['fecha_desde'] != '' && $_POST['fecha_hasta'] != ''){
			$this->model->filters .= ' and bv_facturas.fecha >= "'.$_POST['fecha_desde'].' 00:00:00'.'"';
			$this->model->filters .= ' and bv_facturas.fecha <= "'.$_POST['fecha_hasta'].' 23:59:59'.'"';
			$this->data['fecha_desde'] = $_POST['fecha_desde'];
			$this->data['fecha_hasta'] = $_POST['fecha_hasta'];
			$this->session->set_userdata('fecha_hasta',$_POST['fecha_hasta']);
			$this->session->set_userdata('fecha_desde',$_POST['fecha_desde']);
		}
		else if(isset($_POST['fecha_hasta']) && $_POST['fecha_hasta'] != ''){
			$this->model->filters .= ' and bv_facturas.fecha <= "'.$_POST['fecha_hasta'].' 23:59:59'.'"';
			$this->data['fecha_hasta'] = $_POST['fecha_hasta'];
			$this->session->set_userdata('fecha_hasta',$_POST['fecha_hasta']);
		}
		else if(isset($_POST['fecha_desde']) && $_POST['fecha_desde'] != ''){
			$this->model->filters .= ' and bv_facturas.fecha >= "'.$_POST['fecha_desde'].' 00:00:00'.'"';
			$this->data['fecha_desde'] = $_POST['fecha_desde'];
			$this->session->set_userdata('fecha_desde',$_POST['fecha_desde']);
		}
		else{
			$this->session->unset_userdata('fecha_desde');
			$this->session->unset_userdata('fecha_hasta');
		}
		
	}
	
	function index(){
		//si reseteo
		if(false && isset($_POST['btnReset'])){
			$this->session->unset_userdata('fecha_desde');
			$this->session->unset_userdata('fecha_hasta');
			$this->session->unset_userdata('filtro_mes');
			$this->session->unset_userdata('sucursal_id');
			
			redirect($this->data['route']);
		}
		else{
			if( !$this->session->userdata('fecha_desde') && !$this->session->userdata('fecha_hasta')
				&& (!isset($_POST['fecha_desde']) or !isset($_POST['fecha_hasta']) )){
				$_POST['anio'] = date('Y');
				$_POST['mes'] = date('m');
				$this->filtro_por_mes();
			}
		}
		
		if(isset($_POST['tipo_factura']) && $_POST['tipo_factura'] != ''){
			if( $_POST['tipo_factura'] == 'b' ){
				$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "NC_B")';
				$this->session->set_userdata('tipo_factura','b');
				$this->data['tipo_factura'] = 'b';
			}
			elseif( $_POST['tipo_factura'] == 'x' ){
				$this->model->filters = '(bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_X")';
				$this->session->set_userdata('tipo_factura','x');
				$this->data['tipo_factura'] = 'x';
			}
		}
		else{
			$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_B" or bv_facturas.tipo = "NC_X")';
			$this->session->set_userdata('tipo_factura','bx');
		}
		
		
		if($this->is_nelson){
			$_POST['sucursal_id'] = 2;
		}
		
		//added 19-06-2014
		if(isset($_POST['sucursal_id']) && $_POST['sucursal_id'] != ''){
			$this->model->filters .= ' and r.sucursal_id = '.$_POST['sucursal_id'];
			$this->session->set_userdata('sucursal_id',$_POST['sucursal_id']);
			$this->data['sucursal_id'] = $_POST['sucursal_id'];
		}
		else if(isset($_POST['sucursal_id']) && $_POST['sucursal_id'] == ''){			
			$this->session->unset_userdata('sucursal_id');
		}
		
		//si esta seteado filtro_fechas
		if( isset($_POST['filtro_fechas']) && $_POST['filtro_fechas'] != ''){
			$this->data['filtro_fechas'] = $_POST['filtro_fechas'];
			
			if($_POST['filtro_fechas'] == 'mes'){
				//por mes
				if( (isset($_POST['mes']) && $_POST['mes'] != '' && isset($_POST['anio']) && $_POST['anio'] != '' ) 
				   ){
					$this->filtro_por_mes();
				}
			}
			else{
				//por rango
				$this->session->unset_userdata('filtro_mes');
				unset($this->data['filtro_mes']);

				$this->filtrar_fechas();
			}
		}
		else{
			//si hay algo en sesion -> lo tomo
			if($this->session->userdata('fecha_desde') or $this->session->userdata('fecha_hasta')){
				if($this->session->userdata('filtro_mes')){
					$_POST['mes'] = $this->session->userdata('mes');
					$_POST['anio'] = $this->session->userdata('anio');
					$this->filtro_por_mes();
					$this->data['filtro_fechas'] = 'mes';
				}
				else{
					$_POST['fecha_desde'] = ($this->session->userdata('fecha_desde')) ? $this->session->userdata('fecha_desde') : '';
					$_POST['fecha_hasta'] = ($this->session->userdata('fecha_hasta')) ? $this->session->userdata('fecha_hasta') : '';
					unset($this->data['filtro_mes']);
					$this->filtrar_fechas();
					$this->data['filtro_fechas'] = 'rango';
				}
			}
			else{
				//si no setea nada -> filtro por mes actual
				$_POST['mes'] = date('m');
				$_POST['anio'] = date('Y');
				$this->filtro_por_mes();
			}
		}
		
		$this->data['sort'] = 'fecha';
		$this->data['sortType'] = 'asc';
		parent::index();
	}
	
	function data_facturas($para_exportar=false,$test=false){
		if($this->session->userdata('tipo_factura')){
			if($this->session->userdata('tipo_factura') == 'b') 
				$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "NC_B")';
			else if($this->session->userdata('tipo_factura') == 'x') 
				$this->model->filters = '(bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_X")';
			else 
				$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_B" or bv_facturas.tipo = "NC_X")';
		}
		else
			$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_B" or bv_facturas.tipo = "NC_X")';
		
		//added 19-06-2014
		if($this->session->userdata('sucursal_id')){
			$this->model->filters .= ' and r.sucursal_id = '.$this->session->userdata('sucursal_id');
		}
		
		if($this->session->userdata('fecha_desde') && $this->session->userdata('fecha_hasta')){
			$this->model->filters .= ' and bv_facturas.fecha >= "'.$this->session->userdata('fecha_desde').' 00:00:00'.'"';
			$this->model->filters .= ' and bv_facturas.fecha <= "'.$this->session->userdata('fecha_hasta').' 23:59:59'.'"';			
		}
		else if($this->session->userdata('fecha_hasta')){
			$this->model->filters .= 'and bv_facturas.fecha <= "'.$this->session->userdata('fecha_hasta').' 23:59:59'.'"';			
		}
		else if($this->session->userdata('fecha_desde')){
			$this->model->filters .= 'and bv_facturas.fecha >= "'.$this->session->userdata('fecha_desde').' 00:00:00'.'"';
		}
		
		if($para_exportar){
			
			$this->data['sort'] = 'fecha';
			$this->data['sortType'] = 'asc';

			if($_SERVER['REMOTE_ADDR'] == '201.213.149.119'){
				#$this->model->filters = "bv_facturas.reserva_id = 3009";
			}
			
			$results = $this->model->getAll_export(99999,$this->uri->segment(4), $this->data['sort'], $this->data['sortType'], '')->result();
			
			if($_SERVER['REMOTE_ADDR'] == '181.171.7.108'){
				//echo $this->db->last_query();exit();
			}			
		
			foreach($results as $res){				
				//se usa mas abajo
				$total_de_afip = false;
				
				$tipo_fact = $res->{'TIPO FACTURA'};
				$sucursal_id = $res->factura_sucursal_id;
				unset($res->factura_sucursal_id);
				$nro_fact = $res->{'NUMERO FACTURA'};
				$nro_fact = explode('-',$nro_fact);
				$factura_id = (int)$nro_fact[1];
				
				//$factura = $this->model->getFactura($factura_id,$tipo_fact,$sucursal_id)->row();
				
				$reserva_id = $res->reserva_id;
				unset($res->reserva_id);
				
			// pre($factura);
				if(isset($reserva_id) && $reserva_id){
					$datos_factura = $this->Factura->obtenerDatos($factura_id, $tipo_fact, $sucursal_id,$reserva_id,$para_facturacion=true);		
					
					if(isset($datos_factura) && $datos_factura && !empty($datos_factura)){
						//pre($datos_factura);

						/*if($reserva_id == 3009 && $_SERVER['REMOTE_ADDR'] == '18.233.172.207'){
							pre($datos_factura);exit();
						}
						*/

						$datos_factura['percepcion_3825'] = isset($datos_factura['percepcion_3825']) ? $datos_factura['percepcion_3825'] : 0.00;

					
					//si es de Mercado pago
						if($datos_factura['concepto_id'] == 18 || $datos_factura['concepto_id'] == 17){
							//este ya es el neto
							$neto21_gastos_adm = $datos_factura['gastos_administrativos'];
							$total_gastos_adm = $neto21_gastos_adm*1.21;
							$iva21_gastos_adm = $total_gastos_adm-$neto21_gastos_adm;
							//$datos_factura['total'] -= $iva21_gastos_adm; 
							
							//le sumo el importe de gastos al valor exento, solo si es al exterior
							if($datos_factura['exterior'] == 1){
								$datos_factura['neto_exento'] += $total_gastos_adm;
							}
							else{
								//if($datos_factura['neto_exento'] > 0){
								if($datos_factura['neto_exento'] > 0 && $datos_factura['neto_exento'] < $datos_factura['gastos_administrativos']){
									$datos_factura['neto_exento'] += $total_gastos_adm;
								}
							}
						}
						else if($datos_factura['concepto_id'] == 70 || $datos_factura['concepto_id'] == 71){
							//si es PAYPAL
							$total_gastos_adm = $datos_factura['gastos_administrativos'];
							$neto21_gastos_adm = $total_gastos_adm; //los gastosde paypal son todos netos
							#$datos_factura['gastos_administrativos'] = $neto21_gastos_adm;
							$iva21_gastos_adm = $total_gastos_adm-$neto21_gastos_adm;

							//la parte neta de los gastos lo segrego como NETO IVA 21
							#$datos_factura['neto_iva_21'] += $datos_factura['pp_neto_gastos_admin'];
							
							/*
							$datos_factura['iva_21'] = number_format($datos_factura['total_iva_21']-$neto21,2,'.','');
							*/
							/*
							if($_SERVER['REMOTE_ADDR'] == '201.213.149.119'){
								echo "MAXO";
								pre($datos_factura);
							}*/

							if($datos_factura['comision']>0){
								$neto21 = $datos_factura['total_iva_21']/1.21;
								$datos_factura['neto_iva_21'] = number_format($neto21-$datos_factura['comision'],2,'.','');
								$datos_factura['iva_21'] = number_format($datos_factura['total_iva_21']-$neto21,2,'.','');
							}
							/*
							if($_SERVER['REMOTE_ADDR'] == '201.213.149.119'){
								echo "MAXO2";
								pre($datos_factura);
							}*/

						}
						else{
							//calculo de gastos
							$total_gastos_adm = $datos_factura['gastos_administrativos'];
							$neto21_gastos_adm = $total_gastos_adm/1.21;
							$datos_factura['gastos_administrativos'] = $neto21_gastos_adm;
							$iva21_gastos_adm = $total_gastos_adm-$neto21_gastos_adm;
							
						}
						

						//si es tarjeta de credito
						if(in_array($datos_factura['concepto_id'],array(21,22))){
							//este ya es el neto
							$neto10_intereses = $datos_factura['intereses'];
							$total_intereses = $neto10_intereses*1.105;
							$iva10_intereses = $total_intereses-$neto10_intereses;
							//$datos_factura['total'] -= $iva10_intereses; 
						}
						else{
							//calculo de intereses
							$total_intereses = 0.00;
							$neto10_intereses = 0.00;
							$datos_factura['intereses'] = 0.00;
							$iva10_intereses = 0.00;
						}
						
						//08-10, estaba mas abajo
						if($datos_factura['neto_nogravado'] > 0 && $datos_factura['neto_nogravado'] >= $datos_factura['otros_impuestos_impuesto']){
							$datos_factura['neto_nogravado'] -= $datos_factura['otros_impuestos_impuesto'];
						}
						
						$subtotaliva21 = $iva21_gastos_adm+$datos_factura['iva_21'];
						$subtotaliva10 = $datos_factura['iva_10']+$iva10_intereses;
						$netogastos = $neto21_gastos_adm+$neto10_intereses;

						$subtotaliva21 = number_format($subtotaliva21,2,'.','');
						$subtotaliva10 = number_format($subtotaliva10,2,'.','');
						$netogastos = number_format($netogastos,2,'.','');
						
						//ajusto sumatoria total
						$suma_total = $datos_factura['neto_nogravado']+$datos_factura['neto_exento']+$datos_factura['comision']+$datos_factura['neto_iva_21']+$datos_factura['neto_iva_10']+$subtotaliva10+$subtotaliva21+$netogastos+$datos_factura['otros_impuestos_impuesto']+$datos_factura['percepcion_3825']+$datos_factura['impuesto_pais'];

						
						$datos_factura['neto_exento'] = number_format($datos_factura['neto_exento'],2,'.','');
						$suma_total = number_format($suma_total,2,'.','');
						$datos_factura['total'] = number_format($datos_factura['total'],2,'.','');
						
							/*if($_SERVER['REMOTE_ADDR'] == '201.213.149.119'){
								echo "MAXO3";
								#echo number_format($suma_total - $datos_factura['total'],2,'.','')
								echo $suma_total;
								echo $datos_factura['total'];
								$dif = number_format($suma_total - $datos_factura['total'],2,'.','');
								if($dif == -0.01){
									$suma_total = $datos_factura['total'];
								}
								echo $suma_total;
								pre($datos_factura);
								exit();
							}*/

						$dif = number_format($suma_total - $datos_factura['total'],2,'.','');
						if($dif == -0.01){
							$suma_total = $datos_factura['total'];
						}
						else if($suma_total < $datos_factura['total'] ){
							$datos_factura['neto_exento'] += ($datos_factura['total']-$suma_total);
						}
						else{
							if($suma_total > $datos_factura['total'] ){
								$datos_factura['neto_exento'] -= ($suma_total-$datos_factura['total']);
								$datos_factura['neto_exento'] = $datos_factura['neto_exento'] < 0 ? 0 : $datos_factura['neto_exento'];
							}
						}

	
						$res->{"VALOR"} = number_format($res->{"VALOR"}+$total_gastos_adm+$total_intereses,2,',','.');
						
						//segun explicacion JUAN pdf facturacion, van los 3 conceptos por separado
						//$res->{"CONCEPTOS EXENTOS"} = number_format($datos_factura['neto_exento']+$total_gastos_adm+$total_intereses,2,',','.');
						//$res->{"CONCEPTOS EXENTOS"} = number_format($datos_factura['neto_exento']+$total_intereses,2,',','.');
						$res->{"CONCEPTOS EXENTOS"} = number_format($datos_factura['neto_exento'],2,',','.');
											
						$res->{"NO GRAVADO"} = number_format($datos_factura['neto_nogravado'],2,',','.');

						if($datos_factura['intereses']>0 && $datos_factura['comision']>0){
							//$datos_factura['comision'] -= ($datos_factura['intereses']*0.105);
						}

						$res->{"COMISION/UTILIDAD"} = number_format($datos_factura['comision'],2,',','.');
						
						$res->{"GRAVADO 21%"} = number_format($datos_factura['neto_iva_21'],2,',','.');
						$res->{"GRAVADO 10.5%"} = number_format($datos_factura['neto_iva_10'],2,',','.');
						
						/*
						$res->{"IVA 21%"} = number_format($iva21_gastos_adm+$datos_factura['iva_21'],2,',','.');
						$res->{"IVA 10.5%"} = number_format($datos_factura['iva_10']+$iva10_intereses,2,',','.');
						//pedido de juan los intereses tienen q salir en la columna de gastos administrativos
						//$res->{"INTERESES"} = number_format($neto10_intereses,2,',','.');
						$res->{"GASTOS ADMINISTRATIVOS"} = number_format($neto21_gastos_adm+$neto10_intereses,2,',','.');
						*/

						$res->{"IVA 21%"} = number_format($subtotaliva21,2,',','.');
						$res->{"IVA 10.5%"} = number_format($subtotaliva10,2,',','.');
						$res->{"GASTOS ADMINISTRATIVOS"} = number_format($netogastos,2,',','.');
						
						$res->{"PERCEPCION AFIP"} = number_format($datos_factura['percepcion_3825'],2,',','.');
						$res->{"OTROS IMP."} = number_format($datos_factura['otros_impuestos_impuesto'],2,',','.');
						$res->{"IMPUESTO PAIS"} = number_format($datos_factura['impuesto_pais'],2,',','.');
						//$res->{"TOTAL"} = number_format($datos_factura['total']+$datos_factura['percepcion_3825']+$total_gastos_adm+$total_intereses,2,',','.');
						//$res->{"TOTAL"} = number_format($datos_factura['total']+$datos_factura['percepcion_3825'],2,',','.');
						$res->{"TOTAL"} = number_format($datos_factura['total'],2,',','.');
					}
				}
			

				//no
				if(false && isset($factura->id) && $factura->id){
					$datos_factura = $this->Factura->obtenerDatos($factura_id, $tipo_fact, $sucursal_id);		
								
					//SEGREGACION DE GASTOS ADMINISTRATIVOS: UNA PARTE NETA Y OTRA DE IVA 21
					//cargo gastos administrativos por el neto (LA PARTE DE IMPUESTOS VA EN IVA 21%)
					if(isset($factura->gastos_adm) && $factura->gastos_adm > 0){
						$gastos_adm_bruto = number_format($factura->gastos_adm,2,'.','');					
						
						//if($datos_factura['percepcion_3450'] == 0){
						if($datos_factura['exterior'] == 0){
							//argentina
							//nuevos calculos de iva
							$gastos_adm_gravado_21 = number_format($factura->gastos_adm  / 1.21 * .21,2,'.','');
							$gastos_adm_iva_21 = 0.00;
							$gastos_adm_neto = number_format($factura->gastos_adm - $gastos_adm_gravado_21,2,'.','');
							$gastos_adm_exento = 0.00;
						}
						else{
							//exterior
							//nuevos calculos de iva
							$gastos_adm_gravado_21 = 0.00;
							$gastos_adm_iva_21 = number_format($factura->gastos_adm  / 1.21 * .21,2,'.','');			
							$gastos_adm_neto = number_format($factura->gastos_adm - $gastos_adm_iva_21,2,'.','');			
							$gastos_adm_exento = number_format($factura->gastos_adm  / 1.20 * .20,2,'.','');			
						}
					}
					else{
						//NO HAY GASTOS ADMINISTRATIVOS
						$gastos_adm_neto = 0.00;
						$gastos_adm_iva_21 = 0.00;
						$gastos_adm_gravado_21 = 0.00;
						$gastos_adm_exento = 0.00;
						$gastos_adm_bruto = 0.00;
					}
					
					//FIX PARA QUE EN MAC SE VEA BIEN EL NUMERO E FACTURA
					$this->load->library('user_agent');
					$platform = $this->agent->platform();
					
					if( strpos(strtolower($platform),'os x') === false )
						$res->{'NUMERO FACTURA'} = $res->{'NUMERO FACTURA'};
					else
						$res->{'NUMERO FACTURA'} = "'".$res->{'NUMERO FACTURA'};
					
					$res->{'EXENTO / NO GRAVADO'} = $datos_factura['neto_nogravado'];
					
					if($datos_factura['exterior'] == 0){
						//VIAJE ARGENTINA
						
						//si tiene concepto exento
						if($res->{'EXENTO / NO GRAVADO'} > 0){
							$res->{'GRAVADO 10.5%'} = 0;
							$res->{'GRAVADO 21%'} = $datos_factura['neto_iva_21'] - $gastos_adm_bruto + $gastos_adm_gravado_21;
							$res->{'IVA 10.5%'} = 0;
							$res->{'IVA 21%'} = $datos_factura['iva_21'];
						}
						else{
							$res->{'GRAVADO 10.5%'} = $datos_factura['neto_iva_10'];
							$res->{'GRAVADO 21%'} = $datos_factura['neto_iva_21'] - $gastos_adm_bruto + $gastos_adm_gravado_21;
							$res->{'IVA 10.5%'} = $datos_factura['iva_10'];
							$res->{'IVA 21%'} = $datos_factura['iva_21'];
						}
						
						$res->{'PERCEPCION RG 3450'} = $datos_factura['percepcion_3450'];
						$res->{'IMPUESTO PAIS'} = $datos_factura['impuesto_pais'];
						$res->{'PERCEPCION RG 3825'} = 0;
						$res->{'OTROS IMPUESTOS'} = ($factura->fecha > '2015-08-26') ? $datos_factura['otros_impuestos_impuesto'] : 0;
						$res->{'GASTOS ADMINISTRATIVOS'} = $gastos_adm_neto;
					}
					else{
						//viaje al exterior
						$res->{'GRAVADO 10.5%'} = 0;
						$res->{'GRAVADO 21%'} = 0;
						$res->{'IVA 10.5%'} = 0;
						$res->{'IVA 21%'} = $gastos_adm_iva_21;
						
						$reserva = $this->Reserva->get($factura->reserva_id)->row();
						$paquete = $this->Paquete->get($reserva->paquete_id)->row();
						$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();
						
						if($paquete->exterior && @$combinacion->en_avion){
							$res->{'EXENTO / NO GRAVADO'} = $res->{'EXENTO / NO GRAVADO'} - $gastos_adm_bruto;

							if($datos_factura['total'] == $res->{'EXENTO / NO GRAVADO'}){
								$res->{'EXENTO / NO GRAVADO'} -= $datos_factura['percepcion_3450'];
							}
						} else {
							if($res->{'EXENTO / NO GRAVADO'} == 0){
								$res->{'EXENTO / NO GRAVADO'} += $datos_factura['neto_exento'];
							}
							
							$res->{'EXENTO / NO GRAVADO'} = $res->{'EXENTO / NO GRAVADO'} - $gastos_adm_bruto;
						}
						
						//me fijo en al tabla de facturas de afip si estan los datos
						$f_afip = $this->db->query("SELECT * FROM bv_facturas_afip WHERE cae = '".$factura->cae."'")->row();
						if(isset($f_afip->id) && $f_afip->id){
							$resultget = json_decode($f_afip->resultget);
							
							if (isset($resultget->ImpOpEx) && $resultget->ImpOpEx) {
								$monto = $resultget->ImpOpEx;
							}
							else {
								$monto = $resultget->ImpTotal;
							}
							
							$otros_impuestos = $datos_factura['otros_impuestos_impuesto'];
							$res->{'EXENTO / NO GRAVADO'}  = $res->{'EXENTO / NO GRAVADO'} - $otros_impuestos;
						}
						
						$res->{'PERCEPCION RG 3450'} = $datos_factura['percepcion_3450'];
						$res->{'IMPUESTO PAIS'} = $datos_factura['impuesto_pais'];
						if(isset($f_afip->id) && $f_afip->id){
							//para lso viajes con el 35%
							if(isset($resultget->Tributos) && isset($resultget->Tributos->Tributo) &&
								isset($resultget->Tributos->Tributo->Desc) && 
								$resultget->Tributos->Tributo->Desc == 'Percep. RG 3450'
								){
									$res->{'PERCEPCION RG 3450'} = @$resultget->ImpTrib;
							}
						}

						$res->{'PERCEPCION RG 3825'} = $datos_factura['percepcion_3825'];
						if(isset($f_afip->id) && $f_afip->id){						
							//para viajes con 5% de recargo
							if(isset($resultget->Tributos) && isset($resultget->Tributos->Tributo) &&
								isset($resultget->Tributos->Tributo->Desc) && 
								$resultget->Tributos->Tributo->Desc == 'Percep. RG 3825'
								){
									$res->{'PERCEPCION RG 3825'} = @$resultget->ImpTrib;
							}
							$total_de_afip = @$resultget->ImpTotal;
						}
						
						$res->{'OTROS IMPUESTOS'} = @$otros_impuestos;
						$res->{'GASTOS ADMINISTRATIVOS'} = $gastos_adm_neto;
						
					}
					
					if( $total_de_afip ){
						$res->{'TOTAL'} = $total_de_afip;
					}
					else{
						$res->{'TOTAL'} = $datos_factura['total'];
					}
					
					$res->{'EXENTO / NO GRAVADO'} = number_format($res->{'EXENTO / NO GRAVADO'},2,'.','');
					$res->{'GRAVADO 10.5%'} = number_format($res->{'GRAVADO 10.5%'},2,'.','');
					$res->{'GRAVADO 21%'} = number_format($res->{'GRAVADO 21%'},2,'.','');
					$res->{'IVA 10.5%'} = number_format($res->{'IVA 10.5%'},2,'.','');
					$res->{'IVA 21%'} = number_format($res->{'IVA 21%'},2,'.','');
					$res->{'PERCEPCION RG 3450'} = number_format($res->{'PERCEPCION RG 3450'},2,'.','');
					$res->{'PERCEPCION RG 3825'} = number_format($res->{'PERCEPCION RG 3825'},2,'.','');
					$res->{'OTROS IMPUESTOS'} = number_format($res->{'OTROS IMPUESTOS'},2,'.','');
					$res->{'GASTOS ADMINISTRATIVOS'} = number_format($res->{'GASTOS ADMINISTRATIVOS'},2,'.','');
					$res->{'TOTAL'} = number_format($res->{'TOTAL'},2,'.','');
				
					//si el tipo de factura es NOTA DE CREDITO pongo importes de exento, percepcion, gs administrativos total en negativo
					if($res->{'TIPO FACTURA'} == 'NC_B' or $res->{'TIPO FACTURA'} == 'NC_X'){
						$res->{'EXENTO / NO GRAVADO'} = number_format(-$res->{'EXENTO / NO GRAVADO'},2,'.','');
						
						//si es viaje al exterior pongo en cero estos valores
						if($res->{'VIAJE AL EXTERIOR'} == 1){
							$res->{'GRAVADO 10.5%'} = number_format(0,2,'.','');
							$res->{'GRAVADO 21%'} = number_format(0,2,'.','');
							$res->{'IVA 10.5%'} = number_format(0,2,'.','');
							$res->{'IVA 21%'} = number_format(($res->{'IVA 21%'} > 0) ? -$res->{'IVA 21%'} : $res->{'IVA 21%'},2,'.','');
						}
						else{
							$res->{'GRAVADO 10.5%'} = number_format(-$res->{'GRAVADO 10.5%'},2,'.','');
							$res->{'GRAVADO 21%'} = number_format(-$res->{'GRAVADO 21%'},2,'.','');
							$res->{'IVA 10.5%'} = number_format(-$res->{'IVA 10.5%'},2,'.','');
							$res->{'IVA 21%'} = number_format(-($res->{'IVA 21%'}),2,'.','');
						}
						
						$res->{'PERCEPCION RG 3450'} = number_format(-$res->{'PERCEPCION RG 3450'},2,'.','');
						$res->{'PERCEPCION RG 3825'} = number_format(-$res->{'PERCEPCION RG 3825'},2,'.','');
						//VERIFICAR CON MAXI SI ESTA BIEN (Agregue signo negativo en el valor) Dam // 12-07-2016
						$res->{'OTROS IMPUESTOS'} = number_format(-$datos_factura['otros_impuestos_impuesto'] ,2,'.','');
						$res->{'GASTOS ADMINISTRATIVOS'} = number_format(($res->{'GASTOS ADMINISTRATIVOS'} > 0) ? -$res->{'GASTOS ADMINISTRATIVOS'} : $res->{'GASTOS ADMINISTRATIVOS'},2,'.','');
						
						$res->{'TOTAL'} = number_format(-$datos_factura['total'],2,'.','');
					}
					
					unset($res->{'VALOR'});
					unset($res->{'GASTOS ADM'});
				}

				//si es Nota de credito, van en negativo
				if($res->{'TIPO FACTURA'} == 'NC_B' or $res->{'TIPO FACTURA'} == 'NC_X'){
					$res->{'VALOR'} = '-'.$res->{'VALOR'};
					$res->{'CONCEPTOS EXENTOS'} = '-'.$res->{'CONCEPTOS EXENTOS'};
					$res->{'NO GRAVADO'} = '-'.$res->{'NO GRAVADO'};
					$res->{'OTROS IMP.'} = '-'.$res->{'OTROS IMP.'};
					
					$res->{'GRAVADO 10.5%'} = '-'.$res->{'GRAVADO 10.5%'};
					$res->{'GRAVADO 21%'} = '-'.$res->{'GRAVADO 21%'};
					$res->{"COMISION/UTILIDAD"} = '-'.$res->{"COMISION/UTILIDAD"};
					$res->{'IVA 10.5%'} = '-'.$res->{'IVA 10.5%'};
					$res->{'IVA 21%'} = '-'.$res->{'IVA 21%'};
					$res->{'GASTOS ADMINISTRATIVOS'} = '-'.$res->{'GASTOS ADMINISTRATIVOS'};
					//$res->{'INTERESES'} = '-'.$res->{'INTERESES'};
					$res->{"PERCEPCION AFIP"} = '-'.$res->{"PERCEPCION AFIP"};
					$res->{'IMPUESTO PAIS'} = '-'.$res->{'IMPUESTO PAIS'};
					$res->{'TOTAL'} = '-'.$res->{'TOTAL'};
				}
				
				unset($res->{'VALOR'});
			}
		
			//if($_SERVER['REMOTE_ADDR'] == '190.191.156.166'){
				//exit();
			//}
		
		}
		else{
			$results = $this->model->getAll(99999,$this->uri->segment(4), $this->data['sort'], $this->data['sortType'], '')->result();
			
		}
			
// exit();

		return $results;
	}
	
	function data_facturas_m($para_exportar=false,$test=false){
		if($this->session->userdata('tipo_factura')){
			if($this->session->userdata('tipo_factura') == 'b') 
				$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "NC_B")';
			else if($this->session->userdata('tipo_factura') == 'x') 
				$this->model->filters = '(bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_X")';
			else 
				$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_B" or bv_facturas.tipo = "NC_X")';
		}
		else
			$this->model->filters = '(bv_facturas.tipo = "FA_B" or bv_facturas.tipo = "FA_X" or bv_facturas.tipo = "NC_B" or bv_facturas.tipo = "NC_X")';
		
		//added 19-06-2014
		if($this->session->userdata('sucursal_id')){
			$this->model->filters .= ' and r.sucursal_id = '.$this->session->userdata('sucursal_id');
		}
		
		if($this->session->userdata('fecha_desde') && $this->session->userdata('fecha_hasta')){
			$this->model->filters .= ' and bv_facturas.fecha >= "'.$this->session->userdata('fecha_desde').' 00:00:00'.'"';
			$this->model->filters .= ' and bv_facturas.fecha <= "'.$this->session->userdata('fecha_hasta').' 23:59:59'.'"';			
		}
		else if($this->session->userdata('fecha_hasta')){
			$this->model->filters .= 'and bv_facturas.fecha <= "'.$this->session->userdata('fecha_hasta').' 23:59:59'.'"';			
		}
		else if($this->session->userdata('fecha_desde')){
			$this->model->filters .= 'and bv_facturas.fecha >= "'.$this->session->userdata('fecha_desde').' 00:00:00'.'"';
		}
		
		if($para_exportar){
			
			$this->data['sort'] = 'fecha';
			$this->data['sortType'] = 'desc';

			$results = $this->model->getAll_export(99999,$this->uri->segment(4), $this->data['sort'], $this->data['sortType'], '')->result();
			
			
		
			foreach($results as $res){				
				//se usa mas abajo
				$total_de_afip = false;
				
				$tipo_fact = $res->{'TIPO FACTURA'};
				$sucursal_id = $res->factura_sucursal_id;
				unset($res->factura_sucursal_id);
				$nro_fact = $res->{'NUMERO FACTURA'};
				$nro_fact = explode('-',$nro_fact);
				$factura_id = (int)$nro_fact[1];
				
				$factura = $this->model->getFactura($factura_id,$tipo_fact,$sucursal_id)->row();
				
				
		// pre($factura);
				if(isset($factura->id) && $factura->id){
					$datos_factura = $this->Factura->obtenerDatos($factura_id, $tipo_fact, $sucursal_id,$factura->reserva_id,$para_facturacion=true);		
					
					if(isset($datos_factura) && $datos_factura && !empty($datos_factura)){
						//pre($datos_factura);

						if($_SERVER['REMOTE_ADDR'] == '201.213.84.51'){
							//pre($datos_factura);
						}


						// pre($datos_factura);

						$datos_factura['percepcion_3825'] = isset($datos_factura['percepcion_3825']) ? $datos_factura['percepcion_3825'] : 0.00;

						//si es de Mercado pago
						if($datos_factura['concepto_id'] == 18 || $datos_factura['concepto_id'] == 17){
							//este ya es el neto
							$neto21_gastos_adm = $datos_factura['gastos_administrativos'];
							$total_gastos_adm = $neto21_gastos_adm*1.21;
							$iva21_gastos_adm = $total_gastos_adm-$neto21_gastos_adm;
							//$datos_factura['total'] -= $iva21_gastos_adm; 
							
							//le sumo el importe de gastos al valor exento, solo si es al exterior
							if($datos_factura['exterior'] == 1){
								$datos_factura['neto_exento'] += $total_gastos_adm;
							}
							else{
								if($datos_factura['neto_exento'] > 0 && $datos_factura['neto_exento'] < $datos_factura['gastos_administrativos']){
									$datos_factura['neto_exento'] += $total_gastos_adm;
								}
							}
						}
						else{
							//calculo de gastos
							$total_gastos_adm = $datos_factura['gastos_administrativos'];
							$neto21_gastos_adm = $total_gastos_adm/1.21;
							$datos_factura['gastos_administrativos'] = $neto21_gastos_adm;
							$iva21_gastos_adm = $total_gastos_adm-$neto21_gastos_adm;
							
						}
						


						//si es tarjeta de credito
						if(in_array($datos_factura['concepto_id'],array(21,22))){
							//este ya es el neto
							$neto10_intereses = $datos_factura['intereses'];
							$total_intereses = $neto10_intereses*1.105;
							$iva10_intereses = $total_intereses-$neto10_intereses;
							//$datos_factura['total'] -= $iva10_intereses; 
						}
						else{
							//calculo de intereses
							$total_intereses = 0.00;
							$neto10_intereses = 0.00;
							$datos_factura['intereses'] = 0.00;
							$iva10_intereses = 0.00;
						}
					

						$res->{"VALOR"} = number_format($res->{"VALOR"}+$total_gastos_adm+$total_intereses,2,',','.');
						
						//segun explicacion JUAN pdf facturacion, van los 3 conceptos por separado
						//$res->{"CONCEPTOS EXENTOS"} = number_format($datos_factura['neto_exento']+$total_gastos_adm+$total_intereses,2,',','.');
						//$res->{"CONCEPTOS EXENTOS"} = number_format($datos_factura['neto_exento']+$total_intereses,2,',','.');
						$res->{"CONCEPTOS EXENTOS"} = number_format($datos_factura['neto_exento'],2,',','.');
						
						if($datos_factura['neto_nogravado'] > 0 && $datos_factura['neto_nogravado'] >= $datos_factura['otros_impuestos_impuesto']){
							$datos_factura['neto_nogravado'] -= $datos_factura['otros_impuestos_impuesto'];
						}
						
						$res->{"NO GRAVADO"} = number_format($datos_factura['neto_nogravado'],2,',','.');

						if($datos_factura['intereses']>0 && $datos_factura['comision']>0){
							//$datos_factura['comision'] -= ($datos_factura['intereses']*0.105);
						}

						$res->{"COMISION/UTILIDAD"} = number_format($datos_factura['comision'],2,',','.');
						
						$res->{"GRAVADO 21%"} = number_format($datos_factura['neto_iva_21'],2,',','.');
						$res->{"GRAVADO 10.5%"} = number_format($datos_factura['neto_iva_10'],2,',','.');
						
						$res->{"IVA 21%"} = number_format($iva21_gastos_adm+$datos_factura['iva_21'],2,',','.');
						$res->{"IVA 10.5%"} = number_format($datos_factura['iva_10']+$iva10_intereses,2,',','.');
						//pedido de juan los intereses tienen q salir en la columna de gastos administrativos
						//$res->{"INTERESES"} = number_format($neto10_intereses,2,',','.');
						$res->{"GASTOS ADMINISTRATIVOS"} = number_format($neto21_gastos_adm+$neto10_intereses,2,',','.');
						$res->{"PERCEPCION AFIP"} = number_format($datos_factura['percepcion_3825'],2,',','.');
						$res->{"OTROS IMP."} = number_format($datos_factura['otros_impuestos_impuesto'],2,',','.');
						//$res->{"TOTAL"} = number_format($datos_factura['total']+$datos_factura['percepcion_3825']+$total_gastos_adm+$total_intereses,2,',','.');
						//$res->{"TOTAL"} = number_format($datos_factura['total']+$datos_factura['percepcion_3825'],2,',','.');
						$res->{"TOTAL"} = number_format($datos_factura['total'],2,',','.');
					}
				}
			
				//no
				if(false && isset($factura->id) && $factura->id){
					$datos_factura = $this->Factura->obtenerDatos($factura_id, $tipo_fact, $sucursal_id);		
								
					//SEGREGACION DE GASTOS ADMINISTRATIVOS: UNA PARTE NETA Y OTRA DE IVA 21
					//cargo gastos administrativos por el neto (LA PARTE DE IMPUESTOS VA EN IVA 21%)
					if(isset($factura->gastos_adm) && $factura->gastos_adm > 0){
						$gastos_adm_bruto = number_format($factura->gastos_adm,2,'.','');					
						
						//if($datos_factura['percepcion_3450'] == 0){
						if($datos_factura['exterior'] == 0){
							//argentina
							//nuevos calculos de iva
							$gastos_adm_gravado_21 = number_format($factura->gastos_adm  / 1.21 * .21,2,'.','');
							$gastos_adm_iva_21 = 0.00;
							$gastos_adm_neto = number_format($factura->gastos_adm - $gastos_adm_gravado_21,2,'.','');
							$gastos_adm_exento = 0.00;
						}
						else{
							//exterior
							//nuevos calculos de iva
							$gastos_adm_gravado_21 = 0.00;
							$gastos_adm_iva_21 = number_format($factura->gastos_adm  / 1.21 * .21,2,'.','');			
							$gastos_adm_neto = number_format($factura->gastos_adm - $gastos_adm_iva_21,2,'.','');			
							$gastos_adm_exento = number_format($factura->gastos_adm  / 1.20 * .20,2,'.','');			
						}
					}
					else{
						//NO HAY GASTOS ADMINISTRATIVOS
						$gastos_adm_neto = 0.00;
						$gastos_adm_iva_21 = 0.00;
						$gastos_adm_gravado_21 = 0.00;
						$gastos_adm_exento = 0.00;
						$gastos_adm_bruto = 0.00;
					}
					
					//FIX PARA QUE EN MAC SE VEA BIEN EL NUMERO E FACTURA
					$this->load->library('user_agent');
					$platform = $this->agent->platform();
					
					if( strpos(strtolower($platform),'os x') === false )
						$res->{'NUMERO FACTURA'} = $res->{'NUMERO FACTURA'};
					else
						$res->{'NUMERO FACTURA'} = "'".$res->{'NUMERO FACTURA'};
					
					$res->{'EXENTO / NO GRAVADO'} = $datos_factura['neto_nogravado'];
					
					if($datos_factura['exterior'] == 0){
						//VIAJE ARGENTINA
						
						//si tiene concepto exento
						if($res->{'EXENTO / NO GRAVADO'} > 0){
							$res->{'GRAVADO 10.5%'} = 0;
							$res->{'GRAVADO 21%'} = $datos_factura['neto_iva_21'] - $gastos_adm_bruto + $gastos_adm_gravado_21;
							$res->{'IVA 10.5%'} = 0;
							$res->{'IVA 21%'} = $datos_factura['iva_21'];
						}
						else{
							$res->{'GRAVADO 10.5%'} = $datos_factura['neto_iva_10'];
							$res->{'GRAVADO 21%'} = $datos_factura['neto_iva_21'] - $gastos_adm_bruto + $gastos_adm_gravado_21;
							$res->{'IVA 10.5%'} = $datos_factura['iva_10'];
							$res->{'IVA 21%'} = $datos_factura['iva_21'];
						}
						
						$res->{'PERCEPCION RG 3450'} = $datos_factura['percepcion_3450'];
						$res->{'IMPUESTO PAIS'} = $datos_factura['impuesto_pais'];
						$res->{'PERCEPCION RG 3825'} = 0;
						$res->{'OTROS IMPUESTOS'} = ($factura->fecha > '2015-08-26') ? $datos_factura['otros_impuestos_impuesto'] : 0;
						$res->{'GASTOS ADMINISTRATIVOS'} = $gastos_adm_neto;
					}
					else{
						//viaje al exterior
						$res->{'GRAVADO 10.5%'} = 0;
						$res->{'GRAVADO 21%'} = 0;
						$res->{'IVA 10.5%'} = 0;
						$res->{'IVA 21%'} = $gastos_adm_iva_21;
						
						$reserva = $this->Reserva->get($factura->reserva_id)->row();
						$paquete = $this->Paquete->get($reserva->paquete_id)->row();
						$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();
						
						if($paquete->exterior && @$combinacion->en_avion){
							$res->{'EXENTO / NO GRAVADO'} = $res->{'EXENTO / NO GRAVADO'} - $gastos_adm_bruto;

							if($datos_factura['total'] == $res->{'EXENTO / NO GRAVADO'}){
								$res->{'EXENTO / NO GRAVADO'} -= $datos_factura['percepcion_3450'];
							}
						} else {
							if($res->{'EXENTO / NO GRAVADO'} == 0){
								$res->{'EXENTO / NO GRAVADO'} += $datos_factura['neto_exento'];
							}
							
							$res->{'EXENTO / NO GRAVADO'} = $res->{'EXENTO / NO GRAVADO'} - $gastos_adm_bruto;
						}
						
						//me fijo en al tabla de facturas de afip si estan los datos
						$f_afip = $this->db->query("SELECT * FROM bv_facturas_afip WHERE cae = '".$factura->cae."'")->row();
						if(isset($f_afip->id) && $f_afip->id){
							$resultget = json_decode($f_afip->resultget);
							
							if (isset($resultget->ImpOpEx) && $resultget->ImpOpEx) {
								$monto = $resultget->ImpOpEx;
							}
							else {
								$monto = $resultget->ImpTotal;
							}
							
							$otros_impuestos = $datos_factura['otros_impuestos_impuesto'];
							$res->{'EXENTO / NO GRAVADO'}  = $res->{'EXENTO / NO GRAVADO'} - $otros_impuestos;
						}
						
						$res->{'PERCEPCION RG 3450'} = $datos_factura['percepcion_3450'];
						$res->{'IMPUESTO PAIS'} = $datos_factura['impuesto_pais'];
						if(isset($f_afip->id) && $f_afip->id){
							//para lso viajes con el 35%
							if(isset($resultget->Tributos) && isset($resultget->Tributos->Tributo) &&
								isset($resultget->Tributos->Tributo->Desc) && 
								$resultget->Tributos->Tributo->Desc == 'Percep. RG 3450'
								){
									$res->{'PERCEPCION RG 3450'} = @$resultget->ImpTrib;
							}
						}

						$res->{'PERCEPCION RG 3825'} = $datos_factura['percepcion_3825'];
						if(isset($f_afip->id) && $f_afip->id){						
							//para viajes con 5% de recargo
							if(isset($resultget->Tributos) && isset($resultget->Tributos->Tributo) &&
								isset($resultget->Tributos->Tributo->Desc) && 
								$resultget->Tributos->Tributo->Desc == 'Percep. RG 3825'
								){
									$res->{'PERCEPCION RG 3825'} = @$resultget->ImpTrib;
							}
							$total_de_afip = @$resultget->ImpTotal;
						}
						
						$res->{'OTROS IMPUESTOS'} = @$otros_impuestos;
						$res->{'GASTOS ADMINISTRATIVOS'} = $gastos_adm_neto;
						
					}
					
					if( $total_de_afip ){
						$res->{'TOTAL'} = $total_de_afip;
					}
					else{
						$res->{'TOTAL'} = $datos_factura['total'];
					}
					
					$res->{'EXENTO / NO GRAVADO'} = number_format($res->{'EXENTO / NO GRAVADO'},2,'.','');
					$res->{'GRAVADO 10.5%'} = number_format($res->{'GRAVADO 10.5%'},2,'.','');
					$res->{'GRAVADO 21%'} = number_format($res->{'GRAVADO 21%'},2,'.','');
					$res->{'IVA 10.5%'} = number_format($res->{'IVA 10.5%'},2,'.','');
					$res->{'IVA 21%'} = number_format($res->{'IVA 21%'},2,'.','');
					$res->{'PERCEPCION RG 3450'} = number_format($res->{'PERCEPCION RG 3450'},2,'.','');
					$res->{'PERCEPCION RG 3825'} = number_format($res->{'PERCEPCION RG 3825'},2,'.','');
					$res->{'OTROS IMPUESTOS'} = number_format($res->{'OTROS IMPUESTOS'},2,'.','');
					$res->{'GASTOS ADMINISTRATIVOS'} = number_format($res->{'GASTOS ADMINISTRATIVOS'},2,'.','');
					$res->{'TOTAL'} = number_format($res->{'TOTAL'},2,'.','');
				
					//si el tipo de factura es NOTA DE CREDITO pongo importes de exento, percepcion, gs administrativos total en negativo
					if($res->{'TIPO FACTURA'} == 'NC_B' or $res->{'TIPO FACTURA'} == 'NC_X'){
						$res->{'EXENTO / NO GRAVADO'} = number_format(-$res->{'EXENTO / NO GRAVADO'},2,'.','');
						
						//si es viaje al exterior pongo en cero estos valores
						if($res->{'VIAJE AL EXTERIOR'} == 1){
							$res->{'GRAVADO 10.5%'} = number_format(0,2,'.','');
							$res->{'GRAVADO 21%'} = number_format(0,2,'.','');
							$res->{'IVA 10.5%'} = number_format(0,2,'.','');
							$res->{'IVA 21%'} = number_format(($res->{'IVA 21%'} > 0) ? -$res->{'IVA 21%'} : $res->{'IVA 21%'},2,'.','');
						}
						else{
							$res->{'GRAVADO 10.5%'} = number_format(-$res->{'GRAVADO 10.5%'},2,'.','');
							$res->{'GRAVADO 21%'} = number_format(-$res->{'GRAVADO 21%'},2,'.','');
							$res->{'IVA 10.5%'} = number_format(-$res->{'IVA 10.5%'},2,'.','');
							$res->{'IVA 21%'} = number_format(-($res->{'IVA 21%'}),2,'.','');
						}
						
						$res->{'PERCEPCION RG 3450'} = number_format(-$res->{'PERCEPCION RG 3450'},2,'.','');
						$res->{'PERCEPCION RG 3825'} = number_format(-$res->{'PERCEPCION RG 3825'},2,'.','');
						//VERIFICAR CON MAXI SI ESTA BIEN (Agregue signo negativo en el valor) Dam // 12-07-2016
						$res->{'OTROS IMPUESTOS'} = number_format(-$datos_factura['otros_impuestos_impuesto'] ,2,'.','');
						$res->{'GASTOS ADMINISTRATIVOS'} = number_format(($res->{'GASTOS ADMINISTRATIVOS'} > 0) ? -$res->{'GASTOS ADMINISTRATIVOS'} : $res->{'GASTOS ADMINISTRATIVOS'},2,'.','');
						
						$res->{'TOTAL'} = number_format(-$datos_factura['total'],2,'.','');
					}
					
					unset($res->{'VALOR'});
					unset($res->{'GASTOS ADM'});
				}

				//si es Nota de credito, van en negativo
				if($res->{'TIPO FACTURA'} == 'NC_B' or $res->{'TIPO FACTURA'} == 'NC_X'){
					$res->{'VALOR'} = '-'.$res->{'VALOR'};
					$res->{'CONCEPTOS EXENTOS'} = '-'.$res->{'CONCEPTOS EXENTOS'};
					$res->{'NO GRAVADO'} = '-'.$res->{'NO GRAVADO'};
					$res->{'OTROS IMP.'} = '-'.$res->{'OTROS IMP.'};
					
					$res->{'GRAVADO 10.5%'} = '-'.$res->{'GRAVADO 10.5%'};
					$res->{'GRAVADO 21%'} = '-'.$res->{'GRAVADO 21%'};
					$res->{"COMISION/UTILIDAD"} = '-'.$res->{"COMISION/UTILIDAD"};
					$res->{'IVA 10.5%'} = '-'.$res->{'IVA 10.5%'};
					$res->{'IVA 21%'} = '-'.$res->{'IVA 21%'};
					$res->{'GASTOS ADMINISTRATIVOS'} = '-'.$res->{'GASTOS ADMINISTRATIVOS'};
					//$res->{'INTERESES'} = '-'.$res->{'INTERESES'};
					$res->{"PERCEPCION AFIP"} = '-'.$res->{"PERCEPCION AFIP"};
					$res->{'TOTAL'} = '-'.$res->{'TOTAL'};
				}
				
				unset($res->{'VALOR'});
			}
		
		//if($_SERVER['REMOTE_ADDR'] == '190.191.156.166'){
			//exit();
		//}
		
		}
		else{
			$results = $this->model->getAll(99999,$this->uri->segment(4), $this->data['sort'], $this->data['sortType'], '')->result();
		}
			
// exit();
		return $results;
	}
	
	function export(){
		/*
		if($_SERVER['REMOTE_ADDR'] == '190.18.8.47'){
			$results = $this->data_facturas_m($para_exportar=true);
		}
		else{
		*/
		
		$results = $this->data_facturas($para_exportar=true);
		
		//}
		
		parent::exportar($results);
	}
	
	function download_zip(){
		$this->load->library('zip');
		
		$results = $this->data_facturas();
		
		foreach($results as $res){
			$cod_fact = ($res->tipo == 'FA_X' or $res->tipo == 'NC_X') ? '0010' : $res->codigoFacturacion;
			$nombre_fact = $res->tipo.'-'.$cod_fact.'-'.str_pad($res->id, 8, '0', STR_PAD_LEFT).'.pdf';
			//para testing en /test
			$path = './data/facturas/'.$nombre_fact;
			$this->zip->read_file($path); 
		}
		
		$this->zip->download('facturas-afip.zip');
	}
	
	function configuracion(){
		$results = $this->MNumeradora->get(1);
		
		foreach ($results->result() as $row)
			$this->data['row'] = $row;
			
		if($this->session->userdata('msg')){
			$this->data['msg'] = $this->session->userdata('msg');
			$this->session->unset_userdata('msg');
		}
			
		$this->load->view('admin/facturacion_form', $this->data);
	}
	
	function saveNumeradora(){
		extract($_POST);

		foreach ($_POST as $key=>$value){			
			$data[$key] = $value;
		}

		$this->MNumeradora->update(1, $data); 

		header("location:" . $this->data['route'] . '/config');
	}
	
	//descargar formato SICORE
	function sicore($mes='',$anio=''){
		$this->load->library('afip');
		
		//solo las confirmadas, oficiales
		//si viene en formato fecha
		if(strpos($mes,'-') !== false || strpos($anio,'-') !== false){
			$this->Factura->filters = "(bv_facturas.tipo = 'FA_B' or bv_facturas.tipo = 'NC_B')";
			
			if($mes != '')
				$this->Factura->filters .= " and bv_facturas.fecha >= '".$mes." 00:00:00'";
				
			if($anio != '')
				$this->Factura->filters .= " and bv_facturas.fecha <= '".$anio." 23:59:59'";
		}
		else{
			//puede venir mes y/o anio
			if($mes != '' && $anio != '')
				$this->Factura->filters = "(bv_facturas.tipo = 'FA_B' or bv_facturas.tipo = 'NC_B') and month(bv_facturas.fecha) = '".$mes."' and year(bv_facturas.fecha) = '".$anio."'";
			else
				$this->Factura->filters = "(bv_facturas.tipo = 'FA_B' or bv_facturas.tipo = 'NC_B')";
		}
			
		
		$facturas = $this->Factura->getAll()->result();
		
		/*pre($facturas);
		exit();*/

		/*if($_SERVER['REMOTE_ADDR'] == '190.18.8.47'){
			pre($facturas);
		}*/

		$txt = '';
		
		$file_name = "sicore";
		if($anio!='' && $mes !=''){
			$file_name = "sicore_".$anio."-".$mes;
		}
		
		header('Content-type: text/plain');
		header("Content-Disposition: attachment; filename=".$file_name.".txt");

		foreach($facturas as $f){
			/*
			los tipo numericos (DECIMALES) se rellenan con 0 a la izquierda
			los tipo texto se rellenan con ' ' a la derecha
			*/
			
			$data_fact = $this->model->obtenerDatos($f->id, $f->tipo,$f->sucursal_id,$f->reserva_id,$para_facturacion=true);
			
			/*if($_SERVER['REMOTE_ADDR'] == '190.18.8.47'){
				echo "<pre>";
				echo $this->db->last_query();
				echo "</pre>";
				pre($data_fact);
			}*/
			//pre($data_fact);				
			
			 $cod_comp = ($f->tipo == 'FA_B') ? '01' : '03'; //Long: 2
			 $fecha_emision = date('d/m/Y', strtotime($f->fecha)); //Long: 10
			
			$string = substr($f->comprobante,-13);
			
			//tomo el valor de la factura, pero se puede pisar aca abajo
			$valor = $f->valor;
			$base_calculo_valor = 0;
			$base_calculo_valor_pais = 0;
			$retencion_importe_valor = 0;
			$retencion_importe_valor_pais = 0;
			
			/*
			//me fijo si en la tabla de facturacion de afip tengo datos actualizados
			$f_afip = $this->db->query("SELECT * FROM bv_facturas_afip WHERE cae = '".$f->cae."'")->row();
			if(isset($f_afip->id) && $f_afip->id){
				$resultget = json_decode($f_afip->resultget);
				if (isset($resultget->ImpTotal) && $resultget->ImpTotal) {
					$valor = $resultget->ImpTotal;
				}
				
				if (isset($resultget->ImpOpEx) && $resultget->ImpOpEx) {
					$base_calculo_valor = $resultget->ImpOpEx;
				}
				
				if (isset($resultget->ImpTrib) && $resultget->ImpTrib) {
					$retencion_importe_valor = $resultget->ImpTrib;
				}
			}
			else{
			*/

				/*if($f->id == '2412' && $f->tipo =='NC_B' ){ //fix esta
					$data_fact['impuesto_pais'] = 0;
				}*/

			 //13/1/2020 impuesto y base del impuesto PAIS
			 if(isset($data_fact['impuesto_pais']) && $data_fact['impuesto_pais'] ){
				$base_calculo_valor_pais = number_format(@$data_fact['base_imponible_pais'],2,'.','');
				$retencion_importe_valor_pais = number_format(@$data_fact['impuesto_pais'],2,'.','');
			 }
			 //else{
				//estos son los valores informados
				$base_calculo_valor = number_format(@$data_fact['neto_exento'],2,'.','');
				$retencion_importe_valor = number_format(@$data_fact['percepcion_3825'],2,'.','');
			//}
			//}
			
			 $importe_comp = str_pad(number_format($valor,2,',',''),16,' ',STR_PAD_LEFT); //Long: 16;
			 $nro_comp = str_pad(str_replace('-','',$string),16,' ',STR_PAD_RIGHT); //Long: 16
			 $cod_imp = '217'; //Long: 3 (217: imp a las ganancias) -> es la correcta?
			 $cod_reg = '802'; //Long: 3 -> codigo de regimen es 802 siempre

			 //13/1/2020 el codig ode impuesto y regimen es otro para las operaciones con percepciones por viajes al exterior
			$cod_imp_pais = '';
			$cod_reg_pais = '';
			 if(isset($data_fact['impuesto_pais']) && $data_fact['impuesto_pais'] ){
				$cod_imp_pais = '939';
				$cod_reg_pais = '991';
			 }

			 $cod_oper = '2'; //Long: 1 (1: retencion) ==> // 20-01-17 juan dijo de cambiarlo por 2
			
			if($base_calculo_valor > 0){
				$base_calculo = str_pad(number_format($base_calculo_valor,2,',',''),14,' ',STR_PAD_LEFT); //Long: 14 
			}
			else{
				$base_calculo = str_pad(number_format(@$data_fact['neto_nogravado']+@$data_fact['neto_iva_10']+@$data_fact['neto_iva_21'],2,',',''),14,' ',STR_PAD_LEFT); //Long: 14 
			}
			
			$base_calculo_pais = 0;
			$retencion_importe_pais = 0;
			if($base_calculo_valor_pais > 0){
				$base_calculo_pais = str_pad(number_format($base_calculo_valor_pais,2,',',''),14,' ',STR_PAD_LEFT); //Long: 14 

			}
			if($retencion_importe_valor_pais > 0){
				$retencion_importe_pais = str_pad(number_format($retencion_importe_valor_pais,2,',',''),14,' ',STR_PAD_LEFT); //Long: 14 
			}

			 $fecha_emision_ret = $fecha_emision; //Long: 10
			 $cod_condicion = '01'; //Long: 2, el codigo de condicion es 01
			 $retencion_practicada = '0'; //Long: 1
			
			if($base_calculo_valor > 0){
				 $retencion_importe = str_pad(number_format($retencion_importe_valor,2,',',''),14,' ',STR_PAD_LEFT); //Long: 14 
			}
			else{
				 $retencion_importe = str_pad(number_format(@$data_fact['percepcion'],2,',',''),14,' ',STR_PAD_LEFT); //Long: 14  -> a partir de ahora se empieza a guardar en tbl FACTURAS
			}
			
			 $porcentaje_exclusion = '  0,00'; //Long: 6  -> siempre 0,00
			 $fecha_emision_boletin = '          '; //Long: 10  siempre vacio
			 $tipo_doc_retenido = '80'; //Long: 2 -> TIPO CUIT
			 $nro_doc_retenido = str_pad(str_replace('-','',$f->cuit_cuil),20,' ',STR_PAD_RIGHT); //Long: 20 -> el nro de CUIT del sujeto retenido
			
			//Long: 14 -> numero certificado original: si es NC entonces va el nro de FACT, sino en blanco con todos 0
			if($f->tipo == 'NC_B' && $f->factura_asociada_id && $f->talonario_asociado){
				//obtengo nro comprobante de FACT asocaida a NC
				$asoc = $this->db->query("SELECT m.comprobante 
											from bv_movimientos m
											WHERE m.factura_id = ".$f->factura_asociada_id."
												and m.talonario = '".$f->talonario_asociado."'
												and m.tipoUsuario = 'U' 
											limit 1")->row();
				
				if($asoc->comprobante != ''){
					$string_cert = substr($asoc->comprobante,-13);
				}
				else{
					$string_cert = '';
				}
				 $nro_cert_original = str_pad(str_replace('-','',$string_cert),14,'0',STR_PAD_RIGHT); //Long: 14
			}
			else {
				 $nro_cert_original = '00000000000000';
			}
			

			/*if($_SERVER['REMOTE_ADDR'] == '190.18.8.47'){
				echo "<pre>";
				echo "base ".$base_calculo;
				echo "<br>retencion ".$retencion_importe;
				echo "</pre>";
			}*/

			if( $f->tipo =='NC_B' ){ //07-02-2020 para las NC este valor debe coincidir con el de la retencion
				$base_calculo = $retencion_importe;
				$base_calculo_pais = $retencion_importe_pais;
			}

			//imprimo cada datos
			if($base_calculo > 0 && $retencion_importe > 0){
				echo $cod_comp;
				echo $fecha_emision;
				echo $nro_comp;
				echo $importe_comp;
				echo $cod_imp;
				echo $cod_reg;
				echo $cod_oper;
				echo $base_calculo;
				echo $fecha_emision_ret;
				echo $cod_condicion;
				echo $retencion_practicada;
				echo $retencion_importe;
				echo $porcentaje_exclusion;
				echo $fecha_emision_boletin;
				echo $tipo_doc_retenido;
				echo $nro_doc_retenido;
				echo $nro_cert_original;
				
				echo "\r\n";
			}			
			

			//07-02-2020 si tambien tiene IMPUESTO PAIS
			if($base_calculo_pais > 0 && $retencion_importe_pais > 0){
				echo $cod_comp;
				echo $fecha_emision;
				echo $nro_comp;
				echo $importe_comp;
				echo $cod_imp_pais;
				echo $cod_reg_pais;
				echo $cod_oper;
				echo $base_calculo_pais;
				echo $fecha_emision_ret;
				echo $cod_condicion;
				echo $retencion_practicada;
				echo $retencion_importe_pais;
				echo $porcentaje_exclusion;
				echo $fecha_emision_boletin;
				echo $tipo_doc_retenido;
				echo $nro_doc_retenido;
				echo $nro_cert_original;
				
				echo "\r\n";
			}			
			
			//$txt .= $cod_comp.$fecha_emision.$nro_comp."\r\n";
		}
		//exit();
			
		/*
		header('Content-type: text/plain');
		header("Content-Disposition: attachment; filename=sicore.txt");
		echo $txt;
		*/
	}
	
	/*
	01 | Tipo de comprobante | 001 | 003 | Numérico | 3 | Según tabla Comprobantes
	02 | Punto de venta | 004 | 008 | Numérico | 5 |
	03 | Número de comprobante | 009 | 028 | Numérico | 20 |
	04 | Importe neto gravado | 029 | 043 | Numérico | 15 | 13 enteros 2 decimales sin punto decimal
	05 | Alícuota de IVA | 044 | 047 | Numérico | 4 | Según tabla Alícuotas
	06 | Impuesto liquidado | 048 | 062 | Numérico | 15 | 13 enteros 2 decimales sin punto decimal
	*/
	function alicuotas_txt($mes='',$anio=''){
		$results = $this->data_facturas($para_exportar=1);
		
		$alicuotas = '';

		foreach ($results as $r) {

			$r->{'GRAVADO 10.5%'} = str_replace(array(',','.'),array('',''),$r->{'GRAVADO 10.5%'});
			$r->{'IVA 10.5%'} = str_replace(array(',','.'),array('',''),$r->{'IVA 10.5%'});
			$r->{'GRAVADO 21%'} = str_replace(array(',','.'),array('',''),$r->{'GRAVADO 21%'});
			$r->{'IVA 21%'} = str_replace(array(',','.'),array('',''),$r->{'IVA 21%'});
			$r->{'COMISION/UTILIDAD'} = str_replace(array(',','.'),array('',''),$r->{'COMISION/UTILIDAD'});

			//esto porque la comision tiene parte de iva
			$r->{'GRAVADO 21%'} += $r->{'COMISION/UTILIDAD'};

			/*$r->{'GRAVADO 10.5%'} = floatval($r->{'GRAVADO 10.5%'});
			$r->{'IVA 10.5%'} = floatval($r->{'IVA 10.5%'});
			$r->{'GRAVADO 21%'} = floatval($r->{'GRAVADO 21%'});
			$r->{'IVA 21%'} = floatval($r->{'IVA 21%'});*/
			
			$aux = $r->{'NUMERO FACTURA'};
			$aux = explode('-', $aux);
			
			//si no hay gravado 10.5 ni gravado 21 envio el exento
			$gravado10 = str_pad(abs($r->{'GRAVADO 10.5%'}),15,0,STR_PAD_LEFT);
			$gravado21 = str_pad(abs($r->{'GRAVADO 21%'}),15,0,STR_PAD_LEFT);
			$iva10 = str_pad(abs($r->{'IVA 10.5%'}),15,0,STR_PAD_LEFT);
			$iva21 = str_pad(abs($r->{'IVA 21%'}),15,0,STR_PAD_LEFT);
			
			if($iva10 == '000000000000000' and $iva21 == '000000000000000'){
				//si estos son 0 envio el codigo de exento 0003
				$alicuotas .= $r->{'TIPO FACTURA'} == 'FA_B' ? '006' : ($r->{'TIPO FACTURA'} == 'NC_B' ? '008' : '000');
				$alicuotas .= $r->{'SUCURSAL ID'} == 1 ? '00002' : ($r->{'SUCURSAL ID'} == 2 ? '00005' : '00000');
				$alicuotas .= str_pad(str_replace('-','',$aux[1]),20,'0',STR_PAD_LEFT);
				$alicuotas .= '000000000000000';
				$alicuotas .= '0003';
				$alicuotas .= '000000000000000';
				$alicuotas .= "\r\n";
			}
			else{
				if($iva10 != '000000000000000'){
						
					//gravado 10.5
					$alicuotas .= $r->{'TIPO FACTURA'} == 'FA_B' ? '006' : ($r->{'TIPO FACTURA'} == 'NC_B' ? '008' : '000');
					$alicuotas .= $r->{'SUCURSAL ID'} == 1 ? '00002' : ($r->{'SUCURSAL ID'} == 2 ? '00005' : '00000');
					$alicuotas .= str_pad(str_replace('-','',$aux[1]),20,'0',STR_PAD_LEFT);
					$alicuotas .= $gravado10;
					$alicuotas .= '0004';
					$alicuotas .= $iva10;
					$alicuotas .= "\r\n";

				}
				

				if($iva21 != '000000000000000'){
					
					//gravado 21
					$alicuotas .= $r->{'TIPO FACTURA'} == 'FA_B' ? '006' : ($r->{'TIPO FACTURA'} == 'NC_B' ? '008' : '000');
					$alicuotas .= $r->{'SUCURSAL ID'} == 1 ? '00002' : ($r->{'SUCURSAL ID'} == 2 ? '00005' : '00000');
					$alicuotas .= str_pad(str_replace('-','',$aux[1]),20,'0',STR_PAD_LEFT);
					$alicuotas .= $gravado21;
					$alicuotas .= '0005';
					$alicuotas .= $iva21;
					$alicuotas .= "\r\n";
					
				}
			}
		}
		
		$file_name = "alicuotas_ventas";
		if($anio!='' && $mes !=''){
			$file_name .= "_".$anio."-".$mes;
		}

		header('Content-type: text/plain');
		header("Content-Disposition: attachment; filename=".$file_name.".txt");

		echo $alicuotas;
	}

	/*
	01 | Fecha de comprobante | 001 | 008 | Numérico | 8 | 	Fto: AAAAMMDD
	02 | Tipo de comprobante | 009 | 011 | Numérico | 3 | 	Según tabla Comprobantes
	03 | Punto de venta | 012 | 016 | Numérico | 5 |
	04 | Número de comprobante | 017 | 036 | Numérico | 20 |
	05 | Número de comprobante hasta | 037 | 056 | Numérico | 20 |
	06 | Código de documento del comprador | 057 | 058 | Numérico | 2 | Según tabla Documentos
	07 | Número de identificación del comprador | 059 | 078 | Alfanumérico | 20 | De ser númerico completar con ceros a la izquierda
	08 | Apellido y nombre del comprador | 079 | 108 | Alfanumérico | 30 |
	09 | Importe total de la operación | 109 | 123 | Numérico | 15 | 	13 enteros 2 decimales sin punto decimal
	10 | Importe total de conceptos que no integran el precio neto gravado | 124 | 138 | Numérico | 15 | 	13 enteros 2 decimales sin punto decimal
	11 | Percepción a no categorizados | 139 | 153 | Numérico | 15 | 	13 enteros 2 decimales sin punto decimal
	12 | Importe operaciones exentas | 154 | 168 | Numérico | 15 | 	13 enteros 2 decimales sin punto decimal
	13 | Importe de percepciones o pagos a cuenta de impuestos nacionales | 169 | 183 | Numérico | 15 | 13 enteros 2 decimales sin punto decimal
	14 | Importe de percepciones de ingresos brutos | 184 | 198 | Numérico | 15 | 13 enteros 2 decimales sin punto decimal
	15 | Importe de percepciones impuestos municipales | 199 | 213 | Numérico | 15 | 13 enteros 2 decimales sin punto decimal
	16 | Importe impuestos internos | 214 | 228 | Numérico | 15 | 13 enteros 2 decimales sin punto decimal
	17 | Código de Moneda | 229 | 231 | Alfanumérico | 3 | 	Según tabla Monedas
	18 | Tipo de cambio | 232 | 241 | Numérico | 10 | 	4 enteros 6 decimales sin punto decimal
	19 | Cantidad de alícuotas de IVA | 242 | 242 | Numérico | 1 |
	20 | Código de operación | 243 | 243 | Alfanumérico | 1 | 	Según tabla Codigo_Operación, de No Corresponder v
	21 | Otros Tributos | 244 | 258 | Numérico | 15 |
	22 | Fecha de vencimiento de pago | 259 | 266 | Numérico | 8 | 	Fto: AAAAMMDD
	*/
	function compras_txt($mes='',$anio=''){

		$results = $this->data_facturas($para_exportar=1);
	
		$alicuotas = '';

		foreach ($results as $r) {


			$r->{'TOTAL'} = str_replace(array(',','.'),array('',''),$r->{'TOTAL'});
			$r->{'CONCEPTOS EXENTOS'} = str_replace(array(',','.'),array('',''),$r->{'CONCEPTOS EXENTOS'});
			$r->{'PERCEPCION AFIP'} = str_replace(array(',','.'),array('',''),$r->{'PERCEPCION AFIP'});
			$r->{'GASTOS ADMINISTRATIVOS'} = str_replace(array(',','.'),array('',''),$r->{'GASTOS ADMINISTRATIVOS'});
			$r->{'OTROS IMP.'} = str_replace(array(',','.'),array('',''),$r->{'OTROS IMP.'});
			$r->{'COMISION/UTILIDAD'} = str_replace(array(',','.'),array('',''),$r->{'COMISION/UTILIDAD'});

		
		$r->{'USUARIO'} = trim($r->{'USUARIO'});
		$aux = str_replace(array('á','é','í','ó','ú','Á','É','Í','Ó','Ú'),array('a','e','i','o','u','A','E','I','O','U'),$r->{'USUARIO'});

		//$aux = utf8_decode($aux);
		//$aux = htmlentities($aux, ENT_QUOTES, 'iso-8859-1');
		$aux = substr($aux, 0, 29);

		$r->{'USUARIO'} = $aux;

			$alicuotas .= date('Ymd',strtotime($r->{'FECHA'}));
			$alicuotas .= $r->{'TIPO FACTURA'} == 'FA_B' ? '006' : ($r->{'TIPO FACTURA'} == 'NC_B' ? '008' : '000');
			$alicuotas .= $r->{'SUCURSAL ID'} == 1 ? '00002' : ($r->{'SUCURSAL ID'} == 2 ? '00005' : '00000');
			
			$auxf = $r->{'NUMERO FACTURA'};
			$auxf = explode('-', $auxf);

			$r->{'CUIT/CUIL'} = str_replace('-', '', $r->{'CUIT/CUIL'});

			$alicuotas .= str_pad(str_replace('-','',$auxf[1]),20,'0',STR_PAD_LEFT);
			$alicuotas .= str_pad(str_replace('-','',$auxf[1]),20,'0',STR_PAD_LEFT);
			// $alicuotas .= str_pad(str_replace('-','',$aux[1]),20,'0',STR_PAD_LEFT);
			// $alicuotas .= str_pad(str_replace('-','',$aux[1]),20,'0',STR_PAD_LEFT);
			$alicuotas .= $r->{'DNI'} > 0 ? '96' : ($r->{'CUIT/CUIL'} > 0 ? '86' : '99');
			$alicuotas .= $r->{'DNI'} > 0 ? str_pad($r->{'DNI'},20,'0',STR_PAD_LEFT) : ($r->{'CUIT/CUIL'} > 0 ? str_pad($r->{'CUIT/CUIL'},20,'0',STR_PAD_LEFT) : '00000000000000000000');

			$alicuotas .= str_pad($r->{'USUARIO'},30," ",STR_PAD_RIGHT);

			$alicuotas .= str_pad(abs($r->{'TOTAL'}),15,0,STR_PAD_LEFT);
			//$alicuotas .= str_pad(abs(number_format($r->{'TOTAL'},2,'','')),15,0,STR_PAD_LEFT);
			//$alicuotas .= '000000000000000'; //10 | Importe total de conceptos que no integran el precio neto gravado 
			$alicuotas .= str_pad(abs($r->{'GASTOS ADMINISTRATIVOS'}),15,0,STR_PAD_LEFT);//10 | Importe total de conceptos que no integran el precio neto gravado 
			$alicuotas .= '000000000000000'; //11 | Percepción a no categorizados 
			$alicuotas .= str_pad(abs($r->{'CONCEPTOS EXENTOS'}),15,0,STR_PAD_LEFT); //12 | Importe operaciones exentas
			//$alicuotas .= str_pad(abs(number_format($r->{'CONCEPTOS EXENTOS'},2,'','')),15,0,STR_PAD_LEFT); //12 | Importe operaciones exentas
			$alicuotas .= '000000000000000'; //13 | Importe de percepciones o pagos a cuenta de impuestos nacionales 
			$alicuotas .= str_pad(abs($r->{'PERCEPCION AFIP'}),15,0,STR_PAD_LEFT); //14 | Importe de percepciones de ingresos brutos 
			//$alicuotas .= str_pad(abs(number_format($r->{'PERCEPCION AFIP'},2,'','')),15,0,STR_PAD_LEFT); //14 | Importe de percepciones de ingresos brutos 
			$alicuotas .= '000000000000000'; //15 | Importe de percepciones impuestos municipales
			$alicuotas .= '000000000000000'; //16 | Importe impuestos internos 
			$alicuotas .= 'PES';
			$alicuotas .= '0001000000'; //18 | Tipo de cambio 
			
			//para saber cantidad de alicuotas
			$r->{'IVA 10.5%'} = str_replace(array(',','.'),array('',''),$r->{'IVA 10.5%'});
			$r->{'IVA 21%'} = str_replace(array(',','.'),array('',''),$r->{'IVA 21%'});
			$iva10 = str_pad(abs($r->{'IVA 10.5%'}),15,0,STR_PAD_LEFT);
			$iva21 = str_pad(abs($r->{'IVA 21%'}),15,0,STR_PAD_LEFT);
			
			$cant_alicuotas = '1';
			if($iva10 != '000000000000000' and $iva21 != '000000000000000'){
				$cant_alicuotas = '2';
			}
			if($iva10 == '000000000000000' and $iva21 != '000000000000000'){
				$cant_alicuotas = '1';
			}
			if($iva10 != '000000000000000' and $iva21 == '000000000000000'){
				$cant_alicuotas = '1';
			}
			
			$alicuotas .= $cant_alicuotas; //19 | Cantidad de alícuotas de IVA 
			$alicuotas .= abs($r->{'CONCEPTOS EXENTOS'}) > 0 ? 'E' : '0'; //20 | Código de operación 
			//$alicuotas .= '000000000000000'; //21 | Otros Tributos
			$alicuotas .= str_pad(abs($r->{'OTROS IMP.'}),15,0,STR_PAD_LEFT);//21 | Otros Tributos
			$alicuotas .= '00000000'; //22 | Fecha de vencimiento de pago
			$alicuotas .= "\r\n";

			/*
			//gravado 21
			$alicuotas .= date('Ymd',strtotime($r->{'FECHA'}));
			$alicuotas .= $r->{'TIPO FACTURA'} == 'FA_B' ? '006' : ($r->{'TIPO FACTURA'} == 'NC_B' ? '008' : '000');
			$alicuotas .= $r->{'SUCURSAL ID'} == 1 ? '00002' : ($r->{'SUCURSAL ID'} == 2 ? '00005' : '00000');
		$aux = $r->{'NUMERO FACTURA'};
		$aux = explode('-', $aux);
			$alicuotas .= str_pad(str_replace('-','',$aux[1]),20,'0',STR_PAD_LEFT);
			$alicuotas .= str_pad(str_replace('-','',$aux[1]),20,'0',STR_PAD_LEFT);
			$alicuotas .= $r->{'DNI'} > 0 ? '96' : ($r->{'CUIT/CUIL'} > 0 ? '86' : '99');
			$alicuotas .= $r->{'DNI'} > 0 ? str_pad($r->{'DNI'},20,'0',STR_PAD_LEFT) : ($r->{'CUIT/CUIL'} > 0 ? str_pad($r->{'CUIT/CUIL'},20,'0',STR_PAD_LEFT) : '00000000000000000000');
			
			$alicuotas .= str_pad($r->{'USUARIO'},30," ",STR_PAD_RIGHT);
			
			$alicuotas .= str_pad(abs(number_format($r->{'TOTAL'},2,'','')),15,0,STR_PAD_LEFT);
			$alicuotas .= '000000000000000'; //10 | Importe total de conceptos que no integran el precio neto gravado 
			$alicuotas .= '000000000000000'; //11 | Percepción a no categorizados 
			$alicuotas .= str_pad(abs(number_format($r->{'CONCEPTOS EXENTOS'},2,'','')),15,0,STR_PAD_LEFT); //12 | Importe operaciones exentas
			$alicuotas .= '000000000000000'; //13 | Importe de percepciones o pagos a cuenta de impuestos nacionales 
			$alicuotas .= str_pad(abs(number_format($r->{'PERCEPCION AFIP'},2,'','')),15,0,STR_PAD_LEFT); //14 | Importe de percepciones de ingresos brutos 
			$alicuotas .= '000000000000000'; //15 | Importe de percepciones impuestos municipales
			$alicuotas .= '000000000000000'; //16 | Importe impuestos internos 
			$alicuotas .= 'PES';
			$alicuotas .= '0001000000'; //18 | Tipo de cambio 
			$alicuotas .= '1'; //19 | Cantidad de alícuotas de IVA 
			$alicuotas .= $r->{'CONCEPTOS EXENTOS'} > 0 ? 'E' : '0'; //20 | Código de operación 
			$alicuotas .= '000000000000000'; //21 | Otros Tributos
			$alicuotas .= '00000000'; //22 | Fecha de vencimiento de pago
			$alicuotas .= "\r\n";
			*/
		}

		$file_name = "iva_ventas";
		if($anio!='' && $mes !=''){
			$file_name .= "_".$anio."-".$mes;
		}

			header('Content-type: text/plain');
			header("Content-Disposition: attachment; filename=".$file_name.".txt");
		

		echo $alicuotas;
	}
	
	/*
	Metodo que genera el registro para reenviar el mail de factura de pago con factura adjunta
	*/
	function reenviar_factura($factura_id){
		$factura = $this->Factura->get($factura_id)->row();
		$reserva = $this->Reserva->get($factura->reserva_id)->row();
		$mail = true;
		$template = 'pago_recibido';
		//paso el $factura->pago_id como ref_id del pago
		registrar_comentario_reserva($factura->reserva_id,7,'envio_mail_factura','Envio de email de pago recibido al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template,$factura->pago_id);
		$ret['status'] = 'ok';
		$ret['msg'] = 'Se ha enviado el mail de pago al pasajero con la factura adjunta';
		echo json_encode($ret);
	}

	// actiualizar tabla numeradora
	function actualizarComprobantes() {

		$this->load->library('afip');
		$sucursales = $this->Sucursal->getAll()->result();

		foreach($sucursales as $sucursal) {
			$ultimo_comprobante = $this->afip->ultimo_comprobante($sucursal->codigoFacturacion,'006');
			$this->db->query('update bv_numeradora set FA_B = ('.$ultimo_comprobante->FECompUltimoAutorizadoResult->CbteNro.'+1) where sucursal_id = '.$sucursal->id);
			
			$ultimo_comprobante = $this->afip->ultimo_comprobante($sucursal->codigoFacturacion,'008');
			$this->db->query('update bv_numeradora set NC_B = ('.$ultimo_comprobante->FECompUltimoAutorizadoResult->CbteNro.'+1) where sucursal_id = '.$sucursal->id);

		} 
	}
	
}