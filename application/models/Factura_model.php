<?php
class Factura_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_facturas";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function insert($data) {
		$this->db->trans_start();
		
		// $vend = false;
		// //obtengo el registro de NUMERADORA correspondiente, segun la sucursal y si la hizo vendedor o no
		// if(isset($data['sucursal_id']) && $data['sucursal_id'] > 0){
		// 	if(isset($data['vendedor_id']) && $data['vendedor_id'] > 0){
		// 		//si la reserva tiene vendedor asociado, me fijo cual es su sucursal asignada
		// 		$vend = $this->db->query("select * from bv_vendedores where id = ".$data['vendedor_id'])->row();
		// 		$vend->sucursal_id = $vend->sucursal_id>0 ? $vend->sucursal_id : 1;
		// 		$num = $this->db->query('select '.$data['tipo'].' as proximo from bv_numeradora where sucursal_id = '.$vend->sucursal_id)->row();
		// 	}
		// 	else{
		// 		$num = $this->db->query('select '.$data['tipo'].' as proximo from bv_numeradora where sucursal_id = '.$data['sucursal_id'])->row();
		// 	}
		// }
		// else{
		// 	$num = $this->db->query('select '.$data['tipo'].' as proximo from bv_numeradora where id=1')->row(); //este por default es bs as
		// }
		
		// $data['id'] = $num->proximo;
		$this->db->insert('bv_facturas', array(
			// 'id' => $data['id'], // No se le pasa el id porq ahora es auto incremental (fecha: 11/19/2021)
			'tipo' => $data['tipo'],
			'sucursal_id' => $data['sucursal_id'],
			'reserva_id' => $data['reserva_id'],
			'usuario_id' => $data['usuario_id'],
			'valor' => $data['valor'],
			'fecha' => $data['fecha'],
			'gastos_adm' => $data['gastos_adm'],
			'intereses' => @$data['intereses'],
			'retencioniibb' => @$data['retencioniibb'],
			'concepto_id' => isset($data['concepto_id'])?$data['concepto_id']:0
		));
		
		//deberia venir siempre el sucursal_id de la reserva
		// if(isset($data['sucursal_id']) && $data['sucursal_id'] > 0){
		// 	if($vend){
		// 		$this->db->query('update bv_numeradora set '.$data['tipo'].' = ('.$data['tipo'].'+1) where id = '.$vend->sucursal_id);
		// 	}
		// 	else{
		// 		$this->db->query('update bv_numeradora set '.$data['tipo'].' = ('.$data['tipo'].'+1) where id = '.$data['sucursal_id']);
		// 	}	
		// }
		// else{
		// 	$this->db->query('update bv_numeradora set '.$data['tipo'].' = ('.$data['tipo'].'+1) where id=1'); //este por default es bs as
		// }
		
		$this->db->trans_complete();

		return $data['id'];
	}

	//por default pongo bs as pero deberia venir siempre, al igual que reserva_id
	function generar($factura_id, $tipo_comprobante, $sucursal_id=1, $reserva_id=0, $para_facturacion=FALSE, $para_factura_pdf=FALSE) { 
		$this->load->library('afip');

		//Obtener datos del movimiento
		$datos = $this->obtenerDatos($factura_id, $tipo_comprobante, $sucursal_id, $reserva_id, $para_facturacion,$para_factura_pdf);

	/*if($_SERVER['REMOTE_ADDR'] == '190.18.187.8'){
		echo "<pre>";
		echo $this->db->last_query();
		print_r($datos);			
	}*/

		if(!$datos){
			return false;
		}
		
		$recibo = false;
		
		//para test factura no oficial
		// if ($tipo_comprobante != 'FA_X' && $tipo_comprobante != 'NC_X') {
		// 	//$tipo_comprobante = 'FA_X';
		// }
		
		if ($tipo_comprobante == 'FA_X' || $tipo_comprobante == 'NC_X') {
		
			//Al ser factura Blue, el CAE y la fecha son random
			$datos['punto_venta'] = 10;
			$fvto_cae = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+15, date('Y')));
			$cae = random_string('numeric', 14);

		}
		elseif ($tipo_comprobante == 'RE_X') {
		
			//Al ser RECIBO no se usa CAE
			$datos['punto_venta'] = 10;
			$fvto_cae = '';
			$cae = '';
			$recibo = true;

		}
		else {
			/*
			//para test
			if($_SERVER['HTTP_HOST'] != 'www.buenas-vibras.com.ar'){

				//genero la factura simulada
				$datos['cae'] = $cae=genRandomString();
				$datos['fvto_cae'] = $fvto_cae=date('Y-m-d');
				$datos['reserva_id'] = $reserva_id;
        		$this->afip->preview_datos($datos);

        		//obtengo nuevamente con los parametros para factura pdf
        		$datos = $this->obtenerDatos($factura_id, $tipo_comprobante, $sucursal_id, $reserva_id, true,true);
				$datos['cae'] = $cae;
				$datos['fvto_cae'] = $fvto_cae;
			
				//Con los datos generar el PDF
				$comprobante = generar_comprobante($datos, $tipo_comprobante, './data/facturas/');
				return $comprobante;
				exit();
			}*/
			
			/*
			if($_SERVER['HTTP_HOST'] != 'www.buenas-vibras.com.ar'){

				//genero la factura simulada
				$datos['cae'] = $cae=genRandomString();
				$datos['fvto_cae'] = $fvto_cae=date('Y-m-d');
					
				//Marcar la factura como aprobada y grabar el CAE y fecha de vencimiento
				$this->Factura->updateWhere(array('id'=>$factura_id, 'tipo'=>$tipo_comprobante, 'sucursal_id'=>$sucursal_id), array('cae' => $cae, 'fvto_cae' => $fvto_cae));
			
				if(!is_dir('./data/')){
					mkdir('./data/',0777);
				}
				if(!is_dir('./data/facturas/')){
					mkdir('./data/facturas/',0777);
				}
				
				
				//Con los datos generar el PDF
				$comprobante = generar_comprobante($datos, $tipo_comprobante, './data/facturas/');
				
				//Asignar el comprobante a los movimientos relacionados con esta factura
				$this->Factura->asignarComprobante($factura_id, $tipo_comprobante, $comprobante);
				
				return $comprobante;
			
				//no genero facturacion desde testing
				//return false;
				exit();
			}
			*/

			$num = $this->db->query('select '.$tipo_comprobante.' as proximo from bv_numeradora where id='.$sucursal_id)->row();
			$datos['idComprobanteAfip'] = $num->proximo;
			
			// Poner el campo informandoAfip en informando ..
			$this->Factura->updateWhere(array('id'=>$factura_id, 'tipo'=>$tipo_comprobante, 'sucursal_id'=>$sucursal_id),array('informandoAfip'=>'informando'));

			//Al ser factura AFIP, el CAE y la fecha hay que obtenerlos de AFIP
			$this->afip->auth();

			// $result = $this->afip->autorizar($datos);
			$result = $this->afip->autorizar_test($datos);

			// Poner el campo informandoAfip en informanda
			$this->Factura->updateWhere(array('id'=>$factura_id, 'tipo'=>$tipo_comprobante, 'sucursal_id'=>$sucursal_id),array('informandoAfip'=>'informada'));
			
		
			$cae = false;
						
			if (isset($result->FECAESolicitarResult)) {
				if (isset($result->FECAESolicitarResult->Errors)) {
					if ($result->FECAESolicitarResult->Errors->Err->Code == 600) {
						$this->afip->resync();
						$this->generar($factura_id, $tipo_comprobante, $sucursal_id,$reserva_id);
						return;
					}
					
					$this->logErrorFactura($factura_id, $tipo_comprobante, $sucursal_id, $result->FECAESolicitarResult->Errors->Err->Msg, $result);
					
					$resultado = array('error' => $result->FECAESolicitarResult->Errors->Err->Msg);
					return $resultado;
				}
				elseif ($result->FECAESolicitarResult->FeCabResp->Resultado == 'A') {
					$cae = $result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAE;
					$fvto_cae = $result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAEFchVto;
					$fvto_cae = substr($fvto_cae, 0, 4).'-'.substr($fvto_cae, 4, 2).'-'.substr($fvto_cae, 6, 2);

					// Al finalizar la autoirazacion(AFIP) guardar idComprobanteAfip y actualizar tabla bv_numeradora
					$this->Factura->updateWhere(array('id'=>$factura_id, 'tipo'=>$tipo_comprobante, 'sucursal_id'=>$sucursal_id),array('idComprobanteAfip'=>$datos['idComprobanteAfip']));
					$this->db->query('update bv_numeradora set '. $tipo_comprobante.' = ('.$datos['idComprobanteAfip'].'+1) where sucursal_id = '.$sucursal_id);
				}
				else {

					$errors_afip = [];
					foreacH ($result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->Observaciones as $obs) {
						$errors_afip[] = isset($obs->Msg) ? $obs->Msg : json_encode($obs);

					}
					$this->logErrorFactura($factura_id, $tipo_comprobante, $sucursal_id, implode("\n", $errors_afip), $result);

					$result = $this->afip->consultar($datos);
					if (isset($result->FECompConsultarResult->ResultGet) &&  $result->FECompConsultarResult->ResultGet->Resultado == 'A') {
						$cae = $result->FECompConsultarResult->ResultGet->CodAutorizacion;
						$fvto_cae = $result->FECompConsultarResult->ResultGet->FchVto;
						$fvto_cae = substr($fvto_cae, 0, 4).'-'.substr($fvto_cae, 4, 2).'-'.substr($fvto_cae, 6, 2);
					}
					else {
						return FALSE;
					}
				}
			}
			
		}

		if ($cae || $recibo) {
			$datos['cae'] = $cae;
			$datos['fvto_cae'] = $fvto_cae;
				
			//Marcar la factura como aprobada y grabar el CAE y fecha de vencimiento
			$this->Factura->updateWhere(array('id'=>$factura_id, 'tipo'=>$tipo_comprobante, 'sucursal_id'=>$sucursal_id), array('cae' => $cae, 'fvto_cae' => $fvto_cae));
		
			if(!is_dir('./data/')){
				mkdir('./data/',0777);
			}
			if(!is_dir('./data/facturas/')){
				mkdir('./data/facturas/',0777);
			}
			
			//para generar la factura llamo de nuevo a metodo con los ultimos 2 parametros en true
			$datos = $this->obtenerDatos($factura_id, $tipo_comprobante, $sucursal_id, $reserva_id, true,true);
			$datos['cae'] = $cae;
			$datos['fvto_cae'] = $fvto_cae;
			
			// 14/10/2021 /Se comentan lineas para generar el comprobante en el mail y no antes. 
			
				//Con los datos generar el PDF
				// $comprobante = generar_comprobante($datos, $tipo_comprobante, './data/facturas/');
				
				//Asignar el comprobante a los movimientos relacionados con esta factura
				// $this->Factura->asignarComprobante($factura_id, $tipo_comprobante, $comprobante);
			
				
			//18-03-19 
			//me guardo estos valores de utilidad neta y utilidad total que me serviran para reporte de UTILIDADES
			$data_upd = [];
			$data_upd['venta_total'] = $datos['total'];
			$data_upd['venta_neta'] = $datos['total']-$datos['iva_21'];

			$data_upd['utilidad_neta'] = $datos['comision'];
			$data_upd['utilidad_total'] = $datos['comision']+$datos['iva_21'];

			$data_upd['impuesto_pais'] = $datos['impuesto_pais'];

			$data_upd['costo_total'] = $data_upd['venta_total']-$data_upd['utilidad_total'];
			$data_upd['costo_neto'] = $data_upd['venta_neta']-$data_upd['utilidad_neta'];

			$where = ['id'=>$factura_id, 'tipo'=>$tipo_comprobante, 'sucursal_id'=>$sucursal_id];
			$this->Factura->updateWhere($where,$data_upd);

		}

	}
	
	function logErrorFactura($factura_id, $tipo, $sucursal_id, $error, $result) {
		$existe = $this->db->get_where('bv_facturas_log', array(
			'factura_id' => $factura_id,
			'tipo' => $tipo,
			'sucursal_id' => $sucursal_id,
			'error' => $error
		))->row();
		
		if (!$existe) {
			$this->db->insert('bv_facturas_log', array(
				'factura_id' => $factura_id,
				'tipo' => $tipo,
				'sucursal_id' => $sucursal_id,
				'fecha' => date('Y-m-d H:i:s'),
				'error' => $error,
				'result' => json_encode($result)
			));
		}
	}
	
	
	//el 5to parametro (nuevo_tipo_comprobante) se usa cuando se hace el cambio de RECIBO a FACTURA
	function facturarRecibo($recibo_id, $tipo_comprobante, $comprobante_recibo, $factura_id, $nuevo_tipo_comprobante=false, $sucursal_id=1, $reserva_id=0) { 
		$this->load->library('afip');
		
		//Obtener datos del movimiento (recibo)
		$datos = $this->obtenerDatos($recibo_id, $tipo_comprobante, $sucursal_id, $reserva_id,true,false);		
		
		if(!$datos){
			return false;
		}
		
		$recibo = false;
		
		//para test factura no oficial
		if ($nuevo_tipo_comprobante != 'FA_X' && $nuevo_tipo_comprobante != 'NC_X') {
			//$tipo_comprobante = 'FA_X';
		}
		
		if ($nuevo_tipo_comprobante == 'FA_X' || $nuevo_tipo_comprobante == 'NC_X') {
		
			//Al ser factura Blue, el CAE y la fecha son random
			$datos['punto_venta'] = 10;
			$fvto_cae = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+15, date('Y')));
			$cae = random_string('numeric', 14);

		}
		elseif ($nuevo_tipo_comprobante == 'RE_X') {
		
			//Al ser RECIBO no se usa CAE
			$datos['punto_venta'] = 10;
			$fvto_cae = '';
			$cae = '';
			$recibo = true;

		}
		else {
			
			if($_SERVER['HTTP_HOST'] != 'www.buenas-vibras.com.ar'){

				//genero la factura simulada
				$datos['cae'] = $cae=genRandomString();
				$datos['fvto_cae'] = $fvto_cae=date('Y-m-d');
					
				//Marcar la factura como aprobada y grabar el CAE y fecha de vencimiento
				$this->Factura->updateWhere(
										array('id'=>$factura_id, 'tipo'=>$nuevo_tipo_comprobante, 'sucursal_id'=>$sucursal_id), 
										array('cae' => $cae, 'fvto_cae' => $fvto_cae)
									);
			
				if(!is_dir('./data/')){
					mkdir('./data/',0777);
				}
				if(!is_dir('./data/facturas/')){
					mkdir('./data/facturas/',0777);
				}
				
				//piso este dato por el de la nueva factura
				$datos['numero_factura'] = $factura_id;
				
				//Con los datos generar el PDF
				$comprobante = generar_comprobante($datos, $nuevo_tipo_comprobante, './data/facturas/');
				
				//Asignar el comprobante a los movimientos relacionados con esta factura
				$this->Factura->asignarFacturaConRecibo($recibo_id, $tipo_comprobante, $comprobante_recibo, $factura_id, $nuevo_tipo_comprobante, $comprobante);
				
				return $comprobante;
			
				//no genero facturacion desde testing
				//return false;
				exit();
			}

			//Al ser factura AFIP, el CAE y la fecha hay que obtenerlos de AFIP
			$this->afip->auth();
			
			$result = $this->afip->autorizar($datos);

			$cae = false;
			
			if (isset($result->FECAESolicitarResult)) {
				if (isset($result->FECAESolicitarResult->Errors)) {
					if ($result->FECAESolicitarResult->Errors->Err->Code == 600) {
						$this->afip->resync();
						$this->generar($factura_id, $tipo_comprobante, $sucursal_id,$reserva_id);
						return;
					}
					$resultado = array('error' => $result->FECAESolicitarResult->Errors->Err->Msg);
					return $resultado;
				}
				elseif ($result->FECAESolicitarResult->FeCabResp->Resultado == 'A') {
					$cae = $result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAE;
					$fvto_cae = $result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAEFchVto;
					$fvto_cae = substr($fvto_cae, 0, 4).'-'.substr($fvto_cae, 4, 2).'-'.substr($fvto_cae, 6, 2);
				}
				else {
					$result = $this->afip->consultar($datos);
					if (isset($result->FECompConsultarResult->ResultGet) &&  $result->FECompConsultarResult->ResultGet->Resultado == 'A') {
						$cae = $result->FECompConsultarResult->ResultGet->CodAutorizacion;
						$fvto_cae = $result->FECompConsultarResult->ResultGet->FchVto;
						$fvto_cae = substr($fvto_cae, 0, 4).'-'.substr($fvto_cae, 4, 2).'-'.substr($fvto_cae, 6, 2);
					}
					else {
						return FALSE;
					}
				}
			}
			
		}

		if ($cae || $recibo) {
			$datos['cae'] = $cae;
			$datos['fvto_cae'] = $fvto_cae;
				
			//Marcar la factura como aprobada y grabar el CAE y fecha de vencimiento
			$this->Factura->updateWhere(
									array('id'=>$factura_id, 'tipo'=>$nuevo_tipo_comprobante, 'sucursal_id'=>$sucursal_id), 
									array('cae' => $cae, 'fvto_cae' => $fvto_cae)
								);
		
			if(!is_dir('./data/')){
				mkdir('./data/',0777);
			}
			if(!is_dir('./data/facturas/')){
				mkdir('./data/facturas/',0777);
			}
			
			//para generar la factura llamo de nuevo a metodo con los ultimos 2 parametros en true
			$datos = $this->obtenerDatos($factura_id, $nuevo_tipo_comprobante, $sucursal_id, $reserva_id, true,true);
			$datos['cae'] = $cae;
			$datos['fvto_cae'] = $fvto_cae;
			
			//Con los datos generar el PDF
			$comprobante = generar_comprobante($datos, $nuevo_tipo_comprobante, './data/facturas/');
			
			//Asignar el comprobante a los movimientos relacionados con esta factura
			$this->Factura->asignarFacturaConRecibo($recibo_id, $tipo_comprobante, $comprobante_recibo, $factura_id, $nuevo_tipo_comprobante, $comprobante);
			
			return $comprobante;
		}
		else {
			return FALSE;
		}
	}
	
	function obtenerDatos($factura_id, $tipo_comprobante,$sucursal_id=1,$reserva_id=0,$para_facturacion=false,$para_factura_pdf=false) {
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Lugar_salida_model','Lugar_salida');
		
		$datos['numero_factura'] = $factura_id;
		
		$where_reserva = "";
		if($reserva_id > 0){
			$where_reserva = " and m.reserva_id = ".$reserva_id;
		}
		
		$qu = "select p.exterior, f.concepto_id, f.retencioniibb, f.valor as total_factura, m.* 
				from bv_movimientos m 
				join bv_reservas r on r.id = m.reserva_id 
				join bv_paquetes p on p.id = r.paquete_id 
				left join bv_paquetes_paradas pp on pp.paquete_id = p.id and pp.id = r.paquete_parada_id 
				left join bv_paradas x on x.id = pp.parada_id 
				left join bv_lugares_salida ls on ls.id = x.lugar_id 
				join bv_sucursales s on s.id = r.sucursal_id and s.id = ".$sucursal_id."
				join bv_facturas f on f.id = m.factura_id and m.talonario = f.tipo
				where m.factura_id = ".$factura_id." and m.talonario = '".$tipo_comprobante."' 
					and m.tipoUsuario = 'U' ".$where_reserva." and m.usuario_id = r.usuario_id and m.usuario_id = f.usuario_id  
				order by m.id desc
				limit 1 offset 0";
		$movimiento = $this->db->query($qu)->result();
	
		//pre($movimiento);
		//echo $this->db->last_query();	
		/*if($_SERVER['REMOTE_ADDR'] == '201.213.84.51'){
			echo $this->db->last_query();	
			pre($movimiento);
		}*/

		if (!count($movimiento)) {
			return FALSE;
		}
		else {
			$movimiento = $movimiento[0];
		}
		
		//guardo el dato de la cotizacion
		$datos['cotizacion_dolar'] = $movimiento->tipo_cambio;
		
		if ($movimiento->debe > 0) {
			$es_nota_credito = true;
			$datos['codigo_factura'] = '008';
			$datos['total'] = $movimiento->total_factura;//debe
		}
		else {
			$es_factura = true;
			$datos['codigo_factura'] = '006';
			$datos['total'] = $movimiento->total_factura;//haber
		}
		
		if(FALSE && $para_facturacion){
			$conceptos_con_percepcion = $this->config->item('conceptos_con_percepcion');
		
			//si es deposito usd de viaje al exterior, al total le saco la percepcion
			if($movimiento->exterior == 1 && in_array($movimiento->concepto_id,$conceptos_con_percepcion)){
				$datos['total'] = $datos['total']*1.05;
			}
		}

		$datos['concepto_nombre'] = $movimiento->concepto;
		$datos['reserva_id'] = $movimiento->reserva_id;
		$datos['fecha'] = $movimiento->fecha;
		$datos['valor'] = $datos['total'];
		$datos['forma_pago'] = $movimiento->concepto;
		
		//obtengo datos de reserva para obtener la sucursal_id
		$reserva = $this->Reserva->get($datos['reserva_id'])->row();
		
		$this->load->model('Sucursal_model', 'Sucursal');
		if(isset($reserva->sucursal_id) && $reserva->sucursal_id > 0){
			$sucursal = $this->Sucursal->get($reserva->sucursal_id)->row();
			$datos['punto_venta'] = (isset($sucursal->codigoFacturacion) && $sucursal->codigoFacturacion) ? $sucursal->codigoFacturacion : 2;
		}
		else{
			$datos['punto_venta'] = 2;
		}
		
		//habria q llamar a getFactura($data['numero_factura'] para obtener el importe de GASTOS ADMINISTRATIVOS 
		//correspondiente a la factura para luego incluirlo en el detalle de la factura
		$fact = $this->Factura->getFactura($factura_id,$tipo_comprobante,$reserva->sucursal_id)->row();
		
		//datos de sucursales: codigoFacturacion, nombre, dirección, pie_factura
		$datos['concepto_id'] = isset($fact->concepto_id) ? $fact->concepto_id : '';
		$datos['codigoFacturacion'] = isset($fact->codigoFacturacion) ? $fact->codigoFacturacion : '';
		$datos['sucursal_nombre'] = isset($fact->sucursal_nombre) ? $fact->sucursal_nombre : '';
		$datos['sucursal_direccion'] = isset($fact->sucursal_direccion) ? $fact->sucursal_direccion : '';
		$datos['pie_factura'] = isset($fact->pie_factura) ? $fact->pie_factura : '';
		$datos['pago_usd'] = isset($fact->pago_usd) ? $fact->pago_usd : '';
		if($reserva->operador_id > 1){
			$datos['pie_operador'] = 'Operador Responsable: '.$reserva->operador.' | '.$reserva->operador_legajo.' | CUIT '.$reserva->operador_cuit;
		}
		
		//este dato sale del movimiento
		//se va a usar para mostrar cotizacion del dolar o no, si pagó en USD 
		$datos['tipo_cambio'] = $movimiento->tipo_cambio;
		
		//si la factura va con gastos, se lo sumo al total de la factura
		$datos['gastos_administrativos'] = 0.00;
		if(isset($fact->gastos_adm) && $fact->gastos_adm > 0){
			$datos['gastos_administrativos'] = $fact->gastos_adm;
			//$datos['total'] += $datos['gastos_administrativos'];
		}
		
		//si la factura va con intereses, se lo sumo al total de la factura
		$datos['intereses'] = 0.00;
		if(isset($fact->intereses) && $fact->intereses > 0){
			$datos['intereses'] = $fact->intereses;
		}
		
		if(isset($fact->retencioniibb) && $fact->retencioniibb > 0){
			$datos['retencioniibb'] = $fact->retencioniibb;

			//16/01/2020 este valor de IIBB se lo sumo al total que se paga ya que debe segregarse proporcionalmente en cada concepto del viaje (no puede ir como un concepto aparte ni impuesto)
			$datos['valor'] += $datos['retencioniibb'];
			$datos['total'] += $datos['retencioniibb'];
		}
		
		$this->calcular_impuestos($datos,$para_facturacion,$para_factura_pdf);

		return $datos;
	}
	
	function calcular_impuestos(&$datos_factura,$para_facturacion=false,$para_factura_pdf=false){
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Paquete_model','Paquete');
		$this->load->model('Usuario_model','Usuario');
		$this->load->model('Concepto_model','Concepto');
		$this->load->model('Lugar_salida_model','Lugar_salida');
		$this->load->model('Combinacion_model','Combinacion');
		$this->load->model('Reserva_facturacion_model','Reserva_facturacion');

		$reserva = $this->Reserva->get($datos_factura['reserva_id'])->row();
		$paquete = $this->Paquete->get($reserva->paquete_id)->row();
		
		$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();
		
		$pasajero = $this->Usuario->get($reserva->usuario_id)->row();
		$datapax = array();
		$datapax['reserva_id'] = $datos_factura['reserva_id'];
		$pax_facturacion = $this->Reserva_facturacion->getWhere($datapax)->row();
		$datos_factura['pasajero'] = $pasajero->nombre.' '.$pasajero->apellido.' - DNI '.$pasajero->dni;
		//$datos_factura['tipo_documento'] = $pasajero->dniTipo == 'DNI' ? 96 : 94;
		$datos_factura['tipo_documento'] = $pax_facturacion->f_nacionalidad_id == 1 ? 96 : 94;
		$datos_factura['nro_documento'] = $pax_facturacion->f_cuit_numero;
		
		$datos_factura['concepto'] = $paquete->nombre.' - Lugar: '.$reserva->lugarSalida;
		
		//adicionales 
		$adicionales = $this->Reserva->getAdicionales($datos_factura['reserva_id']);
		foreach($adicionales as $a){
			$datos_factura['concepto'].= ' + '.$a->nombre;
		}
		
		$datos_factura['concepto'].= '. Salida: '.date('d/M/Y', strtotime($paquete->fecha_inicio)).' . Llegada: '.date('d/M/Y', strtotime($paquete->fecha_fin));
		
		$datos_factura['condiciones'] = $this->settings->condiciones_factura;
		$datos_factura['fecha_desde'] = date('Ymd', strtotime($paquete->fecha_inicio));
		$datos_factura['fecha_hasta'] = date('Ymd', strtotime($paquete->fecha_fin));
		$datos_factura['exterior'] = $paquete->exterior;
		$datos_factura['en_avion'] = @$combinacion->tipo_id == 1 ? 1 : 0; //este dato sale del tipo de transporte de la combinacion reservada

		//si estoy pidiendo los datos para el reporte de facturacion, tomo el precio bruto de reserva, sin adicionales
		$reserva_adicionales = adicionales_reserva($reserva);

		$precios = calcular_precios_totales($combinacion,array(),@$combinacion->precio_usd?'USD':'ARS',$reserva->id);
		
		//pre($precios);
		
		/*
		//para facturacion con adicionales, por ahora no
		if($para_facturacion){
			$precios = calcular_precios_totales($combinacion,$this->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$reserva->id);
			
		}
		else{	
			//el monto total lo calculo desde aca			
			//$reserva_adicionales = adicionales_reserva($reserva);
			$precios = calcular_precios_totales($combinacion,$this->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$reserva->id);
		}
		*/

		$total_reserva = $precios['num']['precio_total'];

		//echo "<br><br>total ".$total_reserva."<br><br>";

		$conceptos_con_percepcion = $this->config->item('conceptos_con_percepcion');
		
		//obtengo el concepto_id del movimiento
		$conceptoObj = $this->Concepto->getBy('nombre='.$datos_factura['concepto_nombre']);

		//si no encuentra por el nombre, uso su ID
		if(!isset($conceptoObj->id) || !$conceptoObj->id){
			if(isset($datos_factura['concepto_id']) && $datos_factura['concepto_id']){
				$conceptoObj = $this->Concepto->get($datos_factura['concepto_id'])->row();
				$datos_factura['concepto_nombre'] = $conceptoObj->nombre;
			}
		}

		$datos_factura['concepto_id'] = $conceptoObj->id;	

		//si es deposito usd de viaje al exterior, al total le saco la percepcion
		if($paquete->exterior == 1 && in_array($conceptoObj->id,$conceptos_con_percepcion)){
			
			$datos_factura['total'] = round($datos_factura['total']/1.05,2);	
			$datos_factura['percepcion_3825'] = round($datos_factura['total']*0.05,2);
			$datos_factura['neto_percepcion_3825'] = 0;
		}

		//monto total de la reserva
		//$total_reserva = $reserva->paquete_precio+$reserva->adicionales_precio+$reserva->impuestos;

		//monto del pago (creo que con gastos administrativos si corresponden)
		if( $datos_factura['concepto_id'] == 70 || $datos_factura['concepto_id'] == 71 ){
			$pago_neto = round($datos_factura['total']+$datos_factura['gastos_administrativos'],2);
		}
		else{
			$pago_neto = round($datos_factura['total'],2);
		}

		//el importe del pago siempre está en pesos ya que sale de la factura, pero el total_reserva está en la moneda de lviaje
		if( $datos_factura['concepto_id'] == 70 || $datos_factura['concepto_id'] == 71 ){
			if($combinacion->precio_usd){
				$pago_neto = $pago_neto/$datos_factura['tipo_cambio'];
			}
		}


		//relacion de pago realizado para calcular proporcion en conceptos de la factura
		$relacion_pago = $pago_neto/$total_reserva;
		
		//el dato del total de la factura es en base al dato DEBE o HABER del movimiento, el cual es en PESOS
		/*
		//conversion segun el tipo de viaje y moneda
		if($paquete->exterior && $paquete->precio_usd)
			$conversion = $reserva->pasajeros*$this->settings->cotizacion_dolar;
		else
		*/
		if( $datos_factura['concepto_id'] == 70 || $datos_factura['concepto_id'] == 71 ){
			$conversion = $reserva->pasajeros*($combinacion->precio_usd?$datos_factura['tipo_cambio']:1);
		}
		else{
			$conversion = $reserva->pasajeros;
		}
		
		if(!isset($combinacion->id) || !$combinacion->id){
			$datos_factura['neto_iva_10'] = 0.00;
			$datos_factura['neto_iva_21'] = 0.00;
			$datos_factura['neto_exento'] = 0.00;
			$datos_factura['total_iva_10'] = 0.00;
			$datos_factura['total_iva_21'] = 0.00;
			$datos_factura['total_iva_exento'] = 0.00;
			$datos_factura['otros_impuestos_impuesto'] = 0.00;
			$datos_factura['iva_10'] = 0.00;
			$datos_factura['iva_21'] = 0.00;
			$datos_factura['percepcion'] = 0.00;
			$datos_factura['percepcion_3450'] = 0.00;
			$datos_factura['neto_percepcion_3450'] = 0.00;
			$datos_factura['tasa_impuesto_pais'] = 0.00;
			$datos_factura['base_imponible_pais'] = 0.00;
			$datos_factura['impuesto_pais'] = 0.00;
			$datos_factura['neto_gravado'] = 0.00;
			$datos_factura['neto_nogravado'] = 0.00;
			$datos_factura['percepcion_tasa'] = 0.00;
			$datos_factura['percepcion_base'] = 0.00;	
			$datos_factura['gastos_administrativos'] = 0.00;	
			$datos_factura['intereses'] = 0.00;	
			$datos_factura['pp_neto_gastos_admin'] = 0.00;
		}
		else{

			$combinacion->pp_neto_gastos_admin = 0;
			$combinacion->pp_neto_gastos_admin_fijos = 0;
			$combinacion->neto_gastos_pp = 0;

			//17/01/2020 si el paquete tiene IMPUESTO PAIS, pero no aplica por la fecha o la moneda de pago, 
			//se lo redistribuyo sobre los demas conceptos
			//el impuesto solo aplica para las reservas que se hicieron posterior a la fecha de entrada en vigencia de la ley y segun el tipo de pago (ars y/o usd) 
			$moneda_pago = $datos_factura['pago_usd'] ? 'usd' : 'ars';
			//echo "antes comb";
			//pre($combinacion);
			if( !aplica_impuesto_pais($reserva,$moneda_pago) ){
				$proporcion = 0;
				
				//el monto de impuesto PAIS esta en USD siempre, entonces veo como determinar la proporcion
				//total_reserva esta en la moneda del viaje
				if($paquete->exterior){
					if($paquete->impuesto_pais)
						$total_reserva -= $paquete->impuesto_pais;
					
					//echo $total_reserva;
					$proporcion = $paquete->impuesto_pais/$total_reserva;
				}			
				
				//le adiciono el proporcional del impuesto PAIS a los demas conceptos ya que no aplico para este pago
				$combinacion->v_exento += $combinacion->v_exento*$proporcion;
				$combinacion->v_nogravado += $combinacion->v_nogravado*$proporcion;
				$combinacion->v_comision += $combinacion->v_comision*$proporcion;
				$combinacion->v_gravado21 += $combinacion->v_gravado21*$proporcion;
				$combinacion->v_iva21 += $combinacion->v_iva21*$proporcion;
				$combinacion->v_gravado10 += $combinacion->v_gravado10*$proporcion;
				$combinacion->v_iva10 += $combinacion->v_iva10*$proporcion;
				$combinacion->v_gastos_admin += $combinacion->v_gastos_admin*$proporcion;
				$combinacion->v_rgafip += $combinacion->v_rgafip*$proporcion;
				$combinacion->v_otros_imp += $combinacion->v_otros_imp*$proporcion;
			}
			//echo "desp comb";
			//pre($combinacion);
			
			//si es pago con PAYPAL: rearmo el costo del viaje porque el importe de gastos admin de PAYPAL tengo que descontarselo						
			if( $datos_factura['concepto_id'] == 70 || $datos_factura['concepto_id'] == 71 ){
				//$datos_factura['gastos_administrativos'] es el 5% del total de la venta (está en ARS porque sale de la tabla de facturas)
				$gastos_paypal = $datos_factura['gastos_administrativos'];
				$combinacion->pp_neto_gastos_admin = $gastos_paypal; //en la moneda del pago 968 ars = 25 usd (esto es el % mas el fijo)

				//el proporcional de gastos de paypal es:
				$total_gastos_pp = number_format($this->settings->pp_gastos_admin*$combinacion->v_total,2,'.','');

				//le sumo el importe fijo de gastos de paypal
				$combinacion->pp_neto_gastos_admin_fijos = number_format($this->settings->pp_gastos_admin_fijos*(!$combinacion->precio_usd?$datos_factura['tipo_cambio']:1),2,'.','');

				$combinacion->neto_gastos_pp = $total_gastos_pp;

				//si el paquete tiene COMISION > 0 (ESA COMISION ES NETA)
				if($combinacion->v_comision > 0){
					//nueva comision le saco el proporcional de los gastos
					$combinacion->v_comision = $combinacion->v_comision-$combinacion->neto_gastos_pp;
					$combinacion->v_comision = number_format($combinacion->v_comision,2,'.','');

				}
				else{
					//22-03-19 si no tiene comision entonces hay que descontar un proporcional del gasto de paypal en cada concepto del precio del viaje
					//$combinacion->neto_gastos_pp

					$combinacion->v_exento -= ($combinacion->v_exento/$combinacion->v_total)*$combinacion->neto_gastos_pp;
					$combinacion->v_nogravado -= ($combinacion->v_nogravado/$combinacion->v_total)*$combinacion->neto_gastos_pp;
					$combinacion->v_gravado21 -= ($combinacion->v_gravado21/$combinacion->v_total)*$combinacion->neto_gastos_pp;
					$combinacion->v_iva21 -= ($combinacion->v_iva21/$combinacion->v_total)*$combinacion->neto_gastos_pp;
					$combinacion->v_gravado10 -= ($combinacion->v_gravado10/$combinacion->v_total)*$combinacion->neto_gastos_pp;
					$combinacion->v_iva10 -= ($combinacion->v_iva10/$combinacion->v_total)*$combinacion->neto_gastos_pp;
					$combinacion->v_rgafip -= ($combinacion->v_rgafip/$combinacion->v_total)*$combinacion->neto_gastos_pp;
					$combinacion->v_otros_imp -= ($combinacion->v_otros_imp/$combinacion->v_total) *$combinacion->neto_gastos_pp;
				}
			}

			$datos_factura['pp_neto_gastos_admin_fijos'] = $combinacion->pp_neto_gastos_admin_fijos;

			//datos a mostrar en factura
			//cada concepto es proporcional al monto total de la reserva
			$datos_factura['neto_iva_10'] = $combinacion->v_gravado10 > 0 ? round($relacion_pago*$conversion*$combinacion->v_gravado10,2) : 0.00;
			$datos_factura['neto_iva_21'] = $combinacion->v_gravado21 > 0 ? round($relacion_pago*$conversion*$combinacion->v_gravado21,2) : 0.00;

			/*echo "relacion_pago ".$relacion_pago;
			echo "conversion ".$conversion;
			echo "relacion_pago ".$combinacion->v_exento;*/

			$datos_factura['neto_exento'] = round($relacion_pago*$conversion*$combinacion->v_exento,2);
			$datos_factura['total_iva_10'] = round($relacion_pago*$conversion*($combinacion->v_gravado10+$combinacion->v_iva10),2);
			$datos_factura['total_iva_21'] = round($relacion_pago*$conversion*($combinacion->v_gravado21+$combinacion->v_iva21+$combinacion->v_comision),2);
			
			//se genera un nuevo concepto util para nosotros por el neto de los gastos de paypal
			$datos_factura['pp_neto_gastos_admin'] = round($combinacion->pp_neto_gastos_admin,2);

			$datos_factura['total_iva_exento'] = round($relacion_pago*$conversion*$combinacion->v_exento,2);

			$datos_factura['comision'] = round($relacion_pago*$conversion*($combinacion->v_comision),2);
			

			//la comision va en total_iva_21, segun excel juan explicacion facturacion
			$datos_factura['otros_impuestos_impuesto'] = round($relacion_pago*$conversion*($combinacion->v_otros_imp),2); //otros impuestos
			//$datos_factura['otros_impuestos_impuesto'] = round($relacion_pago*$conversion*($combinacion->v_comision+$combinacion->v_otros_imp),2); //otros impuestos
			$datos_factura['iva_10'] = round($relacion_pago*$conversion*$combinacion->v_iva10,2);
			$datos_factura['iva_21'] = round($relacion_pago*$conversion*$combinacion->v_iva21,2);
			$datos_factura['percepcion'] = round($relacion_pago*$conversion*$combinacion->v_rgafip,2);
			$datos_factura['percepcion_3450'] = 0.00;
			$datos_factura['neto_percepcion_3450'] = 0;		
			
			//el impuesto solo aplica para las reservas que se hicieron posterior a la fecha de entrada en vigencia de la ley y segun el tipo de pago (ars y/o usd) 
			$moneda_pago = $datos_factura['pago_usd'] ? 'usd' : 'ars';
			if( aplica_impuesto_pais($reserva,$moneda_pago) ){
				$datos_factura['tasa_impuesto_pais'] = $paquete->tasa_impuesto_pais;
				$datos_factura['base_imponible_pais'] = round($relacion_pago*$conversion*$paquete->base_imponible_pais,2);
				$datos_factura['impuesto_pais'] = round($relacion_pago*$conversion*$paquete->impuesto_pais,2);
			}
			else{
				$datos_factura['tasa_impuesto_pais'] = 0.00;
				$datos_factura['base_imponible_pais'] = 0.00;
				$datos_factura['impuesto_pais'] = 0.00;
			}
			
			$datos_factura['neto_gravado'] = round($datos_factura['neto_iva_10']+$datos_factura['neto_iva_21'],2);
			$datos_factura['neto_nogravado'] = round($relacion_pago*$conversion*$combinacion->v_nogravado,2);
			//$datos_factura['neto_nogravado'] = ($datos_factura['neto_exento'] > 0) ? 0 : round($datos_factura['neto_exento'],2);
		
			//dato adicional que se usa en wsfe.class.php
			$datos_factura['percepcion_tasa'] = 0.00;
			$datos_factura['percepcion_base'] = 0.00;	
			$datos_factura['gastos_administrativos'] += round($relacion_pago*$conversion*($combinacion->v_gastos_admin),2);
			
			//por el momento al facturacion de adicionales no esta activa
			if(FALSE){

				foreach ($reserva_adicionales as $ad) {
					$adicionales_factura['neto_iva_10'] = $ad->v_gravado10 > 0 ? round($relacion_pago*$conversion*$ad->v_gravado10,2) : 0.00;
					$adicionales_factura['neto_iva_21'] = $ad->v_gravado21 > 0 ? round($relacion_pago*$conversion*$ad->v_gravado21,2) : 0.00;
					$adicionales_factura['neto_exento'] = round($relacion_pago*$conversion*$ad->v_exento,2);
					$adicionales_factura['total_iva_10'] = round($relacion_pago*$conversion*($ad->v_gravado10+$ad->v_iva10),2);
					$adicionales_factura['total_iva_21'] = round($relacion_pago*$conversion*($ad->v_gravado21+$ad->v_iva21+$ad->v_comision),2);
					$adicionales_factura['total_iva_exento'] = round($relacion_pago*$conversion*$ad->v_exento,2);
					$adicionales_factura['comision'] = round($relacion_pago*$conversion*($ad->v_comision),2);
					$adicionales_factura['otros_impuestos_impuesto'] = round($relacion_pago*$conversion*($ad->v_otros_imp),2); 
					$adicionales_factura['iva_10'] = round($relacion_pago*$conversion*$ad->v_iva10,2);
					$adicionales_factura['iva_21'] = round($relacion_pago*$conversion*$ad->v_iva21,2);
					$adicionales_factura['percepcion'] = round($relacion_pago*$conversion*$ad->v_rgafip,2);
					$adicionales_factura['percepcion_3450'] = 0.00;
					$adicionales_factura['neto_percepcion_3450'] = 0;		
					$adicionales_factura['tasa_impuesto_pais'] = 0.00;
					$adicionales_factura['base_imponible_pais'] = 0.00;
					$adicionales_factura['impuesto_pais'] = 0.00;
					$adicionales_factura['neto_gravado'] = round($adicionales_factura['neto_iva_10']+$adicionales_factura['neto_iva_21'],2);
					$adicionales_factura['neto_nogravado'] = round($relacion_pago*$conversion*$ad->v_nogravado,2);
					$adicionales_factura['percepcion_tasa'] = 0.00;
					$adicionales_factura['percepcion_base'] = 0.00;	
					$adicionales_factura['gastos_administrativos'] = round($relacion_pago*$conversion*($ad->v_gastos_admin),2);

				/*echo "data adicional<br>";
				pre($adicionales_factura);*/

					//se lo sumo al precio del paquete
					$datos_factura['neto_iva_10'] += $adicionales_factura['neto_iva_10'];
					$datos_factura['neto_iva_21'] += $adicionales_factura['neto_iva_21'];
					$datos_factura['neto_exento'] += $adicionales_factura['neto_exento'];
					$datos_factura['total_iva_10'] += $adicionales_factura['total_iva_10'];
					$datos_factura['total_iva_21'] += $adicionales_factura['total_iva_21'];
					$datos_factura['total_iva_exento'] += $adicionales_factura['total_iva_exento'];
					$datos_factura['comision'] += $adicionales_factura['comision'];
					$datos_factura['otros_impuestos_impuesto'] += $adicionales_factura['otros_impuestos_impuesto'];
					$datos_factura['iva_10'] += $adicionales_factura['iva_10'];
					$datos_factura['iva_21'] += $adicionales_factura['iva_21'];
					$datos_factura['percepcion'] += $adicionales_factura['percepcion'];
					$datos_factura['percepcion_3450'] += $adicionales_factura['percepcion_3450'];
					$datos_factura['neto_percepcion_3450'] += $adicionales_factura['neto_percepcion_3450'];
					$datos_factura['base_imponible_pais'] += $adicionales_factura['base_imponible_pais'];
					$datos_factura['tasa_impuesto_pais'] += $adicionales_factura['tasa_impuesto_pais'];
					$datos_factura['impuesto_pais'] += $adicionales_factura['impuesto_pais'];

					$datos_factura['neto_gravado'] += $adicionales_factura['neto_gravado'];
					$datos_factura['neto_nogravado'] += $adicionales_factura['neto_nogravado'];
					$datos_factura['percepcion_tasa'] += $adicionales_factura['percepcion_tasa'];
					$datos_factura['percepcion_base'] += $adicionales_factura['percepcion_base'];	
					$datos_factura['gastos_administrativos'] += $adicionales_factura['gastos_administrativos'];
				}

			}

			
			//echo "Aca";
			//pre($datos_factura);
		}

		/*if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
			print_r($datos_factura);
		}*/

		//solo si no es paypal
		if( $datos_factura['concepto_id'] != 70 && $datos_factura['concepto_id'] != 71 ){
			if($datos_factura['gastos_administrativos'] > 0 && $datos_factura['neto_exento']>$datos_factura['gastos_administrativos']){
				$datos_factura['neto_exento'] -= $datos_factura['gastos_administrativos'];
			}

			if($datos_factura['intereses'] > 0 && ($datos_factura['neto_exento']/$reserva->pasajeros)>$datos_factura['intereses']){
				$datos_factura['neto_exento'] -= $datos_factura['intereses'];
			}
		}
		
		if($paquete->exterior == 1 && in_array($conceptoObj->id,$conceptos_con_percepcion)){
			/*
			$datos_factura['percepcion_3825'] = round($pago_neto*0.05,2);
			$datos_factura['neto_percepcion_3825'] = 0;	
			$datos_factura['total'] = round($pago_neto*1.05,2);	
			*/
			
			if($para_facturacion && !$para_factura_pdf){
				if($datos_factura['percepcion_3825']>0){
					$datos_factura['total'] = round($datos_factura['total']*1.05,2);	
				}
			}
			
			//cambiamos la forma de calculo por pedido de bv 15-11-17
			//08-06 comente estos 3
			//$datos_factura['total'] = round($pago_neto/1.05,2);	
			//$datos_factura['percepcion_3825'] = round($datos_factura['total']*0.05,2);
			//$datos_factura['neto_percepcion_3825'] = 0;	

			//le saco el valor de percepcio al exento
			//08-06 comente estos 2
			//$datos_factura['neto_exento'] -= $datos_factura['percepcion_3825'];
			//$datos_factura['total_iva_exento'] -= $datos_factura['percepcion_3825'];
		}


		$mi_ip = $_SERVER['REMOTE_ADDR'] == '152.171.157.183' || $_SERVER['REMOTE_ADDR'] == '200.80.220.102';

		/*if($datos_factura['reserva_id'] == '2693'){
			pre($datos_factura);
		}*/

		//solo si es paypal
		if( $datos_factura['concepto_id'] == 70 || $datos_factura['concepto_id'] == 71 ){
			if(isset($datos_factura['gastos_administrativos']) && $datos_factura['gastos_administrativos'] > 0){
				if($para_facturacion){
					if($para_factura_pdf){
						//OK
						$datos_factura['total_iva_21'] -= $combinacion->pp_neto_gastos_admin_fijos*($combinacion->precio_usd?$datos_factura['tipo_cambio']:1);

						//02-05-19 si este valor me quedó en negativo
						if( $datos_factura['total_iva_21'] < 0 ){
							$dif = (-1)*$datos_factura['total_iva_21'];

							//la diferencia se la saco al valor exento
							$datos_factura['total_iva_exento'] -= $dif;

							//este lo pongoen 0
							$datos_factura['total_iva_21'] = 0.00;
						}

						$datos_factura['neto_iva_21'] = $datos_factura['total_iva_21']/1.21;
						$datos_factura['iva_21'] = $datos_factura['total_iva_21']-$datos_factura['neto_iva_21'];
						
						$valor_descontar = $combinacion->pp_neto_gastos_admin_fijos*($combinacion->precio_usd?$datos_factura['tipo_cambio']:1);

						if($datos_factura['comision'] >= $valor_descontar){
							$datos_factura['comision'] -= $valor_descontar;
						}
						
						$datos_factura['total'] += $datos_factura['gastos_administrativos'];
					}
					else{
						//segregacion de iva y neto en gastos
					/*	$total_gastos_adm = $datos_factura['gastos_administrativos'];
						$neto21_gastos_adm = $total_gastos_adm/1.21;
						$datos_factura['gastos_administrativos'] = round($neto21_gastos_adm,2);
						$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm, 2);
						$datos_factura['total_iva_21'] += $iva21_gastos_adm;
						#$datos_factura['total'] += $total_gastos_adm;
						
						$datos_factura['neto_exento'] += $total_gastos_adm;*/

						$datos_factura['total_iva_21'] -= $combinacion->pp_neto_gastos_admin_fijos*($combinacion->precio_usd?$datos_factura['tipo_cambio']:1);
						
						//02-05-19 si este valor me quedó en negativo
						if( $datos_factura['total_iva_21'] < 0 ){
							$dif = (-1)*$datos_factura['total_iva_21'];

							//la diferencia se la saco al valor exento
							$datos_factura['total_iva_exento'] -= $dif;

							//este lo pongoen 0
							$datos_factura['total_iva_21'] = 0.00;
						}


						#$datos_factura['neto_iva_21'] = $datos_factura['total_iva_21']/1.21;
						#$datos_factura['iva_21'] = $datos_factura['total_iva_21']-$datos_factura['neto_iva_21'];
						
						$datos_factura['iva_21'] = ($datos_factura['comision']+$datos_factura['neto_iva_21'])/.21;

						$valor_descontar = $combinacion->pp_neto_gastos_admin_fijos*($combinacion->precio_usd?$datos_factura['tipo_cambio']:1);

						if($datos_factura['comision'] >= $valor_descontar){
							$datos_factura['comision'] -= $valor_descontar;
						}

						$datos_factura['total'] += $datos_factura['gastos_administrativos'];

					}
				}
				else{
					//segregacion de iva y neto en gastos
					$total_gastos_adm = $datos_factura['gastos_administrativos'];
					$neto21_gastos_adm = $total_gastos_adm/1.21;
					//$datos_factura['gastos_administrativos'] = $neto21_gastos_adm;
					$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm,2);
					//$datos_factura['total_iva_21'] += $iva21_gastos_adm;
					//$datos_factura['total'] += $iva21_gastos_adm;
				}
			}
		}
		else{
			//SI NO ES PAYPAL (COMO HASTA ANTES)
			if(isset($datos_factura['gastos_administrativos']) && $datos_factura['gastos_administrativos'] > 0){
				//se lo saco al total factura
				//$datos_factura['total'] -= $datos_factura['gastos_administrativos'];

				if($para_facturacion){
					if($para_factura_pdf){
						//segregacion de iva y neto en gastos
						$total_gastos_adm = $datos_factura['gastos_administrativos'];
						//$neto21_gastos_adm = $total_gastos_adm/1.21;
						//$datos_factura['gastos_administrativos'] = $neto21_gastos_adm;
						//$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm, 2);
						//$datos_factura['total_iva_21'] += $iva21_gastos_adm;
						$datos_factura['total'] += $total_gastos_adm;
					}
					else{
						//segregacion de iva y neto en gastos
						$total_gastos_adm = $datos_factura['gastos_administrativos'];
						$neto21_gastos_adm = $total_gastos_adm/1.21;
						$datos_factura['gastos_administrativos'] = round($neto21_gastos_adm,2);
						$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm, 2);
						$datos_factura['total_iva_21'] += $iva21_gastos_adm;
						$datos_factura['total'] += $total_gastos_adm;
						
						//si es de mercado pago no se lo sumo
						if($conceptoObj->id != 18 && $conceptoObj->id != 17){
							$datos_factura['neto_exento'] += $total_gastos_adm;
						}
					}
				}
				else{
					//segregacion de iva y neto en gastos
					$total_gastos_adm = $datos_factura['gastos_administrativos'];
					$neto21_gastos_adm = $total_gastos_adm/1.21;
					//$datos_factura['gastos_administrativos'] = $neto21_gastos_adm;
					$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm,2);
					//$datos_factura['total_iva_21'] += $iva21_gastos_adm;
					//$datos_factura['total'] += $iva21_gastos_adm;
				}
			}
		}

		if(isset($datos_factura['intereses']) && $datos_factura['intereses'] > 0){
			//se lo saco al total factura
			//$datos_factura['total'] -= $datos_factura['intereses'];

			
			if($para_facturacion){
				if($para_factura_pdf){
					//segregacion de iva y neto en gastos
					$total_intereses = $datos_factura['intereses'];
					//$neto10_intereses = $total_intereses/1.105;
					//$datos_factura['intereses'] = $neto10_intereses;
					//$iva10_intereses = round($total_intereses-$neto10_intereses,2);
					//$datos_factura['total_iva_10'] += $iva10_intereses;
					$datos_factura['total'] += $total_intereses;
				}
				else{
					//segregacion de iva y neto en intereses
					$total_intereses = $datos_factura['intereses'];
					$neto10_intereses = $total_intereses/1.105;
					$datos_factura['intereses'] = round($neto10_intereses,2);
					$iva10_intereses = round($total_intereses-$neto10_intereses,2);
					$datos_factura['total_iva_10'] += $iva10_intereses;
					$datos_factura['total'] += $total_intereses;
				}
			}
			else{
				//segregacion de iva y neto en intereses
				$total_intereses = $datos_factura['intereses'];
				$neto10_intereses = $total_intereses/1.105;
				//$datos_factura['intereses'] = $neto10_intereses;
				$iva10_intereses = round($total_intereses-$neto10_intereses,2);
				//$datos_factura['total_iva_10'] += $iva10_intereses;
				/*$datos_factura['total_iva_21'] -= $iva10_intereses;
				if($datos_factura['total_iva_21'] < 0){
					$datos_factura['total_iva_21'] = 0.00;
				}*/
				//$datos_factura['total'] += $iva21_intereses;
			}
		}
		
		if(isset($datos_factura['otros_impuestos_impuesto']) && $datos_factura['otros_impuestos_impuesto'] > 0){
			if($para_facturacion){
				if($para_factura_pdf){
					
				}
				else{
					$datos_factura['neto_nogravado'] += $datos_factura['otros_impuestos_impuesto'];
				}
			}
		}

		/*if(isset($datos_factura['comision']) && $datos_factura['comision'] > 0){
			if($para_facturacion){
				if($para_factura_pdf){
					
				}
				else{
					$datos_factura['neto_nogravado'] += $datos_factura['comision'];
				}
			}
		}*/
		
		/*
		if(isset($datos_factura['comision']) && $datos_factura['comision'] > 0){
			$datos_factura['neto_nogravado'] += $datos_factura['comision'];
		}*/
		
		/*
		if(isset($datos_factura['comision']) && $datos_factura['comision'] > 0){
			//se lo saco al total factura
			//$datos_factura['total'] -= $datos_factura['gastos_administrativos'];

			if($para_facturacion){
				if($para_factura_pdf){
					//segregacion de iva y neto en gastos
					$total_gastos_adm = $datos_factura['comision'];
					//$neto21_gastos_adm = $total_gastos_adm/1.21;
					//$datos_factura['comision'] = $neto21_gastos_adm;
					//$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm, 2);
					//$datos_factura['total_iva_21'] += $iva21_gastos_adm;
					$datos_factura['total'] += $total_gastos_adm;
				}
				else{
					//segregacion de iva y neto en gastos
					$total_gastos_adm = $datos_factura['comision'];
					$neto21_gastos_adm = $total_gastos_adm/1.21;
					$datos_factura['comision'] = round($neto21_gastos_adm,2);
					$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm, 2);
					$datos_factura['total_iva_21'] += $iva21_gastos_adm;
					$datos_factura['total'] += $total_gastos_adm;
				}
			}
			else{
				//segregacion de iva y neto en gastos
				$total_gastos_adm = $datos_factura['comision'];
				$neto21_gastos_adm = $total_gastos_adm/1.21;
				//$datos_factura['comision'] = $neto21_gastos_adm;
				$iva21_gastos_adm = round($total_gastos_adm-$neto21_gastos_adm,2);
				//$datos_factura['total_iva_21'] += $iva21_gastos_adm;
				//$datos_factura['total'] += $iva21_gastos_adm;
			}
		}
		*/


	}	

	function getFactura($factura_id,$tipo='',$sucursal_id=1){ 
		
		if($tipo != '')
			$q = "SELECT f.*, s.codigoFacturacion, s.nombre as sucursal_nombre, s.direccion as sucursal_direccion, s.pie_factura,
					pc.v_exento, pc.v_gravado21, pc.v_gravado10, pc.v_comision, pc.v_otros_imp, pc.v_iva21, pc.v_iva10, pc.v_gastos_admin, 
					pc.v_rgafip, pc.v_total, m.pago_usd
					FROM bv_facturas f 
					JOIN bv_reservas r ON r.id = f.reserva_id 
					JOIN bv_paquetes_combinaciones pc ON pc.id = r.combinacion_id 
					join bv_paquetes p on p.id = r.paquete_id 
					left join bv_paquetes_paradas pp on pp.paquete_id = p.id and pp.id = r.paquete_parada_id 
					left join bv_paradas x on x.id = pp.parada_id 
					left join bv_lugares_salida ls on ls.id = x.lugar_id
					join bv_sucursales s on s.id = r.sucursal_id and s.id = ".$sucursal_id." 
					left join bv_movimientos m on m.factura_id = f.id and m.tipoUsuario = 'U' and m.usuario_id = r.usuario_id 
					WHERE f.id = ".$factura_id." AND f.tipo = '".$tipo."' and f.sucursal_id = ".$sucursal_id;
		else
			$q = "SELECT f.*, s.codigoFacturacion, s.nombre as sucursal_nombre, s.direccion as sucursal_direccion, s.pie_factura, 
					pc.v_exento, pc.v_gravado21, pc.v_gravado10, pc.v_comision, pc.v_otros_imp, pc.v_iva21, pc.v_iva10, pc.v_gastos_admin, 
					pc.v_rgafip, pc.v_total, m.pago_usd
					FROM bv_facturas f
					JOIN bv_reservas r ON r.id = f.reserva_id 
					JOIN bv_paquetes_combinaciones pc ON pc.id = r.combinacion_id 
					join bv_paquetes p on p.id = r.paquete_id 
					left join bv_paquetes_paradas pp on pp.paquete_id = p.id and pp.id = r.paquete_parada_id 
					left join bv_paradas x on x.id = pp.parada_id 
					join bv_sucursales s on s.id = r.sucursal_id and s.id = ".$sucursal_id." 
					left join bv_movimientos m on m.factura_id = f.id and m.tipoUsuario = 'U' and m.usuario_id = r.usuario_id
					WHERE f.id = ".$factura_id." and f.sucursal_id = ".$sucursal_id;
		return $this->db->query($q);
	}
	
	function asignarComprobante($factura_id, $tipo_comprobante, $comprobante) {
		$this->db->where('talonario', $tipo_comprobante);
		$this->db->where('factura_id', $factura_id);
		$this->db->update('bv_movimientos', array('comprobante' => $comprobante));
	}
	
	function asignarFacturaConRecibo($recibo_id, $tipo_comprobante, $comprobante_recibo, $factura_id, $nuevo_tipo_comprobante, $comprobante) {
		//en los datos del recibo, los actualizo por los de la factura nueva
		$this->db->where('talonario', $tipo_comprobante);
		$this->db->where('factura_id', $recibo_id);
		$this->db->update('bv_movimientos', array(
												'comprobante' => $comprobante,
												'factura_id' => $factura_id,
												'talonario' => $nuevo_tipo_comprobante,
												'comprobante_padre' => $comprobante_recibo,
												'talonario_padre' => $tipo_comprobante
											)
										);
	}
	
	//backend
	//modulo backend 
	function onGetAll(){
		$this->db->select('bv_facturas.*, 
						case when bv_facturas.concepto_id in (70,71) then bv_facturas.gastos_adm+bv_facturas.valor else bv_facturas.valor end as valor_final, p.nombre as titulo, "" as subtitulo, 
						u.nombre, u.apellido, u.dni, concat(rf.f_cuit_prefijo, "-", rf.f_cuit_numero, "-", rf.f_cuit_sufijo) as cuit_cuil, m.talonario, 
						r.sucursal_id, s.codigoFacturacion, s.nombre as sucursal, 
						s.talonario as sucursal_talonario, m.comprobante, m.factura_asociada_id, 
						m.talonario_asociado, m.tipo_cambio, 
						r.code, (case when p.precio_usd =1 then "Dolares" else "Pesos" end) as moneda,
						o.nombre as operador',false);
		$this->db->join('bv_reservas r','r.id = bv_facturas.reserva_id');
		$this->db->join('bv_reservas_facturacion rf','rf.reserva_id = r.id');
		$this->db->join('bv_paquetes p','p.id = r.paquete_id');
		$this->db->join('bv_operadores o','o.id = p.operador_id');
		$this->db->join('bv_usuarios u','u.id = r.usuario_id');
		$this->db->join('bv_lugares_salida ls','ls.id = r.lugar_id','left');
		$this->db->join('bv_sucursales s','s.id = r.sucursal_id','left');
		$this->db->join('bv_movimientos m','m.factura_id = bv_facturas.id and m.usuario_id = u.id and m.reserva_id = r.id and m.tipoUsuario = "U"');
	}
	
	function onGet(){
		$this->db->select('bv_facturas.*, m.id as pago_id',false);
		$this->db->join('bv_reservas r','r.id = bv_facturas.reserva_id');
		$this->db->join('bv_paquetes p','p.id = r.paquete_id');
		$this->db->join('bv_usuarios u','u.id = r.usuario_id');
		$this->db->join('bv_lugares_salida ls','ls.id = r.lugar_id','left');
		$this->db->join('bv_sucursales s','s.id = r.sucursal_id','left');
		$this->db->join('bv_movimientos m','m.factura_id = bv_facturas.id and m.usuario_id = u.id and m.reserva_id = r.id and m.tipoUsuario = "U"');
	}
	
	/*function getAll($num=100000, $offset=0, $sort='', $type='', $keywords='') {
		$this->onGetAll();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " .$this->table.".".$index . " LIKE '%" . $keywords . "%'";
			
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
	}*/
	
	function onGetAll_export(){
		$this->db->select('bv_facturas.fecha as "FECHA", bv_facturas.tipo as "TIPO FACTURA", CONCAT(s.codigoFacturacion,"-",LPAD(bv_facturas.id,8,"0")) as "NUMERO FACTURA", bv_facturas.cae as "CAE", 
							CONCAT(trim(BOTH "\t" from u.nombre)," ",trim(BOTH "\t" from u.apellido)) as "USUARIO", u.dni as "DNI", 
							CONCAT(rf.f_cuit_prefijo,"-",rf.f_cuit_numero,"-",rf.f_cuit_sufijo) as "CUIT/CUIL",
							r.code as "Cod Reserva",
							p.nombre as "PAQUETE", o.nombre as "Operador", 
							p.fecha_inicio as "FECHA SALIDA", p.fecha_fin as "FECHA REGRESO", 
							bv_facturas.valor as "VALOR", mov.concepto as "CONCEPTO", 
							(case when p.precio_usd =1 then "Dolares" else "Pesos" end) as "Moneda",
							(case when p.precio_usd =1 then mov.tipo_cambio else "" end) as "Tipo de cambio",  
							(CASE p.exterior WHEN 1 THEN "SI" ELSE "NO" END) as "VIAJE AL EXTERIOR", s.nombre as "SUCURSAL",
							r.sucursal_id as "SUCURSAL ID", bv_facturas.sucursal_id as factura_sucursal_id,bv_facturas.reserva_id',false);
		$this->db->join('bv_reservas r','r.id = bv_facturas.reserva_id');
		$this->db->join('bv_reservas_facturacion rf','rf.reserva_id = r.id');
		$this->db->join('bv_paquetes_combinaciones pc','pc.id = r.combinacion_id');
		$this->db->join('bv_paquetes p','p.id = r.paquete_id');
		$this->db->join('bv_operadores o','o.id = p.operador_id');
		$this->db->join('bv_destinos d','d.id = p.destino_id');
		$this->db->join('bv_usuarios u','u.id = r.usuario_id');
		$this->db->join('bv_lugares_salida ls','ls.id = r.lugar_id','left');
		$this->db->join('bv_sucursales s','s.id = r.sucursal_id','left');
		$this->db->join('bv_movimientos mov','mov.factura_id = bv_facturas.id and mov.tipoUsuario = "U" and mov.usuario_id = u.id and mov.reserva_id = r.id','left');
	}
	
	//cambio nombres de columnas para exportar en excel
	function getAll_export($num=100000, $offset=0, $sort='', $type='', $keywords='') {
		$this->onGetAll_export();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " .$this->table.".".$index . " LIKE '%" . $keywords . "%'";
			
			$q .= ")";
			$this->db->where($q);
		}
		
		if ($this->filters != '')
			$this->db->where($this->filters);
				
		if ($sort != '')
			$query = $this->db->order_by('1', $type)->get($this->table, $num, $offset);	
		else 
			$query = $this->db->get($this->table, $num, $offset);	
		
		return $query;
	}
	
	function count($keywords = '') {
		$this->onGetAll();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " . $index . " LIKE '%" . $keywords . "%'";
			
			$q .= ")";
			$this->db->where($q);
		}

		if ($this->filters != '')
			$this->db->where($this->filters);
			
		return $this->db->count_all_results($this->table);
	}

	function verificacionAfip() {
		$resp = $this->db->query('SELECT COUNT(id) as informando FROM bv_facturas WHERE informandoAfip = "informando"')->result();
		return $resp[0]->informando;
	}

}