<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->data['current'] = 'pagos';
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Transaccion_model','Transaccion');
		$this->load->model('Concepto_model','Concepto');
		$this->load->model('Sucursal_model','Sucursal');
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Paquete_model','Paquete');
		$this->load->model('Factura_model','Factura');
		$this->load->model('Combinacion_model','Combinacion');
	}
	
	function verf($nro_fact=false,$reserva_id=false,$tipo_c='FA_B',$gen_doc=false){
		if(!$nro_fact || !$reserva_id){
			return false;
		}

		$this->load->library('afip');
		
		//genera factura manualmente (ya fue informada pero no se habia generado)
		$this->load->model('Factura_model','Factura');
		$this->load->model('Sucursal_model','Sucursal');
		$this->load->model('Concepto_model','Concepto');
		
		//para ver datos q manda a afip
		$datos = $this->Factura->obtenerDatos($factura_id=$nro_fact, $tipo_comprobante=$tipo_c, $sucursal_id=1,$reserva_id,true,$para_factura_pdf=false);
		
			pre($datos);
		
		//$datos = $this->MFactura->obtenerDatos($factura_id=6922, $tipo_comprobante='FA_B', $sucursal_id=1);
		$datos = $this->Factura->obtenerDatos($factura_id=$nro_fact, $tipo_comprobante=$tipo_c, $sucursal_id=1,$reserva_id,true,$para_factura_pdf=true);
		
			pre($datos);
		
		//$datos['total'] += $datos['gastos_administrativos'];
		
		//echo "<pre>";
		//print_r($datos);
		
		$fact = $this->Factura->getFactura($factura_id, $tipo_comprobante, $sucursal_id)->row();

		$datos['cae'] = $fact->cae ? $fact->cae : '';
		$datos['fvto_cae'] = $fact->fvto_cae ? $fact->fvto_cae : '';
				
		$datos['fecha_factura'] = date('d/m/Y',strtotime($fact->fecha));
		/*
		if($datos['fvto_cae']){
			$datos['fecha_factura'] = date('d/m/Y',strtotime($datos['fvto_cae']));
		}
		*/
		
		echo $html = $this->load->view('factura', $datos, true);
		
		if($gen_doc){
			$comprobante = generar_comprobante($datos, $tipo_comprobante, './data/facturas/');
			
			echo "<br>comprobante ".$comprobante."<br>";
		}
		
		//18-03-19 
		//me guardo estos valores de utilidad neta y utilidad total que me serviran para reporte de UTILIDADES
		$data_upd = [];
		$data_upd['venta_total'] = $datos['total'];
		$data_upd['venta_neta'] = $datos['total']-$datos['iva_21'];

		$data_upd['utilidad_neta'] = $datos['comision'];
		$data_upd['utilidad_total'] = $datos['comision']+$datos['iva_21'];

		$data_upd['costo_total'] = $data_upd['venta_total']-$data_upd['utilidad_total'];
		$data_upd['costo_neto'] = $data_upd['venta_neta']-$data_upd['utilidad_neta'];

		$where = ['id'=>$factura_id, 'tipo'=>$tipo_comprobante, 'sucursal_id'=>$sucursal_id];
		$this->Factura->updateWhere($where,$data_upd);
			
		exit();
	}
	
	function fix() {
		//el ultimo parametro en false poruqe primero informa a afip, entonces el detalle tiene que vijar como si fuera para facturacion
		//luego cuando se va a generar el archivo fisico pdf (dentro de generar() ) se obtienen los datos para la factura con ese ultimo parametro en true
		$comprobante = $this->Factura->generar(25643, 'FA_B', 1, 258, $para_reporte_facturacion=TRUE,$para_factura_pdf=false);
		print_r($comprobante);
	}
	
	function info($id) {
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$payment_info = $mp->get("/collections/notifications/" . $id);
	
		echo "<pre>";
		print_r($payment_info);
		
		$payment_info = $mp->get("/v1/payments/" . $id);
		print_r($payment_info);

		$merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["order"]["id"]);
		print_r($merchant_order_info);

		echo "<br>settlement<br>";
		$data = $mp->get("/v1/account/settlement_report/settlement-report-1478421351538367033892.csv");
		print_r($data);

		echo "<br>bank report<br>";
		$data = $mp->get("/v1/account/bank_report/config");
		print_r($data);

		echo "<br>bank report csv<br>";
		$data = $mp->get("/v1/account/bank_report/bank-report-147842135.csv");
		print_r($data);

	}

	//mp nueva api 13/1/2020
	function maxi($id){
	  	//require_once 'vendor/autoload.php';

	    //MercadoPago\SDK::setAccessToken("ENV_ACCESS_TOKEN");
   		MercadoPago\SDK::setAccessToken("APP_USR-8038305266910906-011319-c7224752ed8f9cbab1f1cee20ba04f32-147842135");

	    MercadoPago\SDK::setClientId($this->config->item('mp_client_id'));
		MercadoPago\SDK::setClientSecret($this->config->item('mp_client_secret'));

	    $payment = new MercadoPago\Payment();
	    $pp = $payment->find_by_id($id);
		pre($pp);
	}
	
	function infop($id) {
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$payment_info = $mp->get("/authorized_payments/" . $payment_info["response"]["collection"]["id"]);
		echo "<pre>";
		print_r($payment_info);
	}
	
	/*
	IPN: recibo notificaciones de MP
	*/
	function update_mp(){
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();

		if (isset($_GET["topic"]) && $_GET["topic"] == 'payment'){
			$payment_info = $mp->get("/collections/notifications/" . $_GET["id"]);
			
			$data = $mp->get_payment_info($payment_info["response"]["collection"]["id"]);
			
			echo "<pre>";
			print_r($data);
			
			if ($data['status'] == 200) {
				
				//me fijo si la Reserva que obtengo existe
				$existe_reserva = $this->Reserva->getWhere(array('code' => $data['response']['collection']['external_reference']))->row();
				
			print_r($existe_reserva);
			
				if(isset($existe_reserva->id) && $existe_reserva->id){
					$reserva = $this->Reserva->get($existe_reserva->id)->row();
					
					$status = $data['response']['collection']['status'];
					$status_detail = $data['response']['collection']['status_detail'];
					$date_approved = $data['response']['collection']['date_approved'];
					$amount = $data['response']['collection']['transaction_amount'];
					$monto_neto = $data['response']['collection']['net_received_amount'];
					
					//obtengo el importe de gastos administrativos para luego segregarlo en factura
					//$gastos_adm = number_format($amount - $monto_neto,2,'.','');

					//16/01/2020 los gastos los tomo del result que obtengo
					$payment_info_ext = $mp->get("/v1/payments/".$payment_info["response"]["collection"]["id"]);
					$gastos_adm = 0;

					//fix Maxi 22-01-2020
					//recorro array de fee_details para saber cual corresponde a gastos de MP
					if(isset($payment_info_ext["response"]["fee_details"]) && $payment_info_ext["response"]["fee_details"]){

						foreach ($payment_info_ext["response"]["fee_details"] as $feedetails) {
							if($feedetails['type'] == 'mercadopago_fee'){
								//si son gastos de mp
								$gastos_adm = $feedetails["amount"];
							}
						}
					}

					/*if( isset($payment_info_ext["response"]["fee_details"][0]["amount"]) && $payment_info_ext["response"]["fee_details"][0]["amount"] ){
						$gastos_adm = $payment_info_ext["response"]["fee_details"][0]["amount"];
					}*/
					
					//calculo la diferencia entre el total de la transaccion y el neto y gastos
					$diferencia = $amount-$monto_neto-$gastos_adm;

					// si hay alguna diferencia en los montos es que corresponde a retencion
					$retencioniibb = $diferencia>0 ? $diferencia : 0;

					$data['response']['collection']['external_reference'] = trim($data['response']['collection']['external_reference']);
					//enviarMail($from='hotsale@buenas-vibras.com.ar',$to='maxi@id4you.com',$bcc='dlobalzo@id4you.com',$asunto='probar codigo',$mensaje=$data['response']['collection']['external_reference'],'BUENAS VIBRAS VIAJES');
					
					$data_transaction = array();
					$data_transaction['fecha_procesado'] = $date_approved;
					$data_transaction['monto_neto'] = $monto_neto;
					$data_transaction['monto_total'] = $amount;
					$data_transaction['gastos_adm'] = $gastos_adm;
					$data_transaction['retencioniibb'] = $retencioniibb;
					$data_transaction['codigo'] = $data['response']['collection']['external_reference'];
					$data_transaction['numtransaccion'] = $payment_info["response"]["collection"]["id"];
					$data_transaction['estado'] = $status;
					$data_transaction['estado_detalle'] = $status_detail;
					//$data_transaction['ip'] = $_SERVER['REMOTE_ADDR'];
					$data_transaction['reserva_id'] = $existe_reserva->id;
					$data_transaction['origen'] = 'mercadopago';

					//solo las operaciones aprobadas
					if($status == 'approved'){					
						//obtengo, si existe, la transaccion de DB
						$datos = array();
						$datos['numtransaccion'] = $payment_info["response"]["collection"]["id"];
						$trans = $this->Transaccion->getWhere($datos)->row();
						
						//31-07-2019 si la transaccion existe y ya está aprobada, no la vuelvo a procesar
						if(isset($trans->id) && $trans->id && $trans->estado == 'approved'){
							return FALSE;
						}
						
						//si existe actualizo, sino genero
						if(isset($trans->id) && $trans->id){
							$tr_id = $trans->id;
							$this->Transaccion->update($tr_id,$data_transaction);
						}
						else{
							$tr_id = $this->Transaccion->insert($data_transaction);				
						}
					

						$reserva_queda_anulada = false;

						//si la reserva está anulada y entro un pago ver primero el cupo cupo
						if($reserva->estado_id == 5){

							//chequear si hay cupo en el viaje (combinacion) para la cantidad de pasajeros de la reserva
							$filtros = array();
							$filtros['pax'] = $reserva->pasajeros;
							//$filtros['disponibles'] = '1';
							$filtros['combinacion_id'] = $reserva->combinacion_id;
							$data_combinacion = $this->Combinacion->getByPaquete($reserva->paquete_id,1,$filtros);

							//si hay combinacion ,chequeo que la cantidad de pasajeros me alcance para ambos cupos de transporte y alojamiento
							//se muestra esta alarma en el listado de reservas del back
							if(isset($data_combinacion->id) && $data_combinacion->id && 
								($reserva->pasajeros > $data_combinacion->cupo_trans || $reserva->pasajeros > $data_combinacion->cupo_aloj) ){

								//seteo marca para que luego de facturar la ponga en ANULADA
								$reserva_queda_anulada = true;

								registrar_comentario_reserva($reserva->id,7,'comentario','Se recibió pago de Mercado Pago por importe de ARS '.$data_transaction['monto_total'].' pero no hay cupo para el viaje');
							}
							else{
								//pasar a nueva (con registro del movimiento por el importe de reserva)

								//doy de alta el registro inicial de la reserva
								//genero movimiento en cta cte de ese usuario
								$reserva_id = $reserva->id;
								$usuario_id = $reserva->usuario_id;
								$fecha_reserva = date('Y-m-d H:i:s');
								$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
								/*el valor del monto de reserva es segun el precio de venta de la combinacion, por las dudas si el registro de 
								reserva quedó con algun valor previo de otra combinacion*/
								$monto_reserva = $reserva->v_total+$reserva->adicionales_precio;
								
								$paquete = $this->Paquete->get($reserva->paquete_id)->row();
								
								if(isset($paquete->impuesto_pais) && $paquete->impuesto_pais && $paquete->exterior){
									//el precio del impuesto se lo sumo solo para las reservas posteriores a la fecha de vigencia de la ley
									if($this->config->item('vigencia_impuesto_pais') <= $reserva->fecha_reserva){
										$monto_reserva += $reserva->pasajeros*$paquete->impuesto_pais;
									}
								}

								$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($usuario_id,"U",$reserva->precio_usd)->row();		
								$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;
								
								//globales
								$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
								$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
								$tipo_cambio = $this->settings->cotizacion_dolar;
								
								registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
								
								//tambien seteo marcas en el historial para que luego se le envie mail de reserva
								$mail = true;
								$template = 'datos_reserva';

								registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);
									
								//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
								$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();			
								$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
								registrar_movimiento_cta_cte(1,"A",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
								registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
								//--------------------------------------------------------------------------	

								// pasar a nueva
								$this->Reserva->update($reserva->id,array('estado_id' => 1));

							}
							
						}

						//le facturo el pago
						$this->facturar_mp($tr_id,$data_transaction,$reserva_queda_anulada);
					}
				
				}
				
			}
		}
	}

	function facturar_mp($transaccion_id,$data_transaction,$reserva_queda_anulada=false){
		$transaccion = $this->Transaccion->get($transaccion_id)->row();
		$reserva = $this->Reserva->get($transaccion->reserva_id)->row();		
		$sucursal = $this->Sucursal->get($reserva->sucursal_id)->row();
		$conceptoObj = $this->Concepto->get(18)->row(); //concepto MERCADOPAGO
		$paquete = $this->Paquete->get($reserva->paquete_id)->row();
		
		//pre($transaccion);
		//pre($reserva);
		//pre($sucursal);
		//pre($conceptoObj);
		//pre($paquete);

		//datos grales del pago
		$debe = 0.00;
		$haber = $data_transaction['monto_neto'];
		//$fecha = $data_transaction['fecha_procesado'];
		$fecha = date('Y-m-d H:i:s');
		$tipo_id = $reserva->usuario_id;
		$tipo = 'U';
		$reserva_id = $reserva->id;
		$new_concepto = $conceptoObj->nombre;
		$nuevo_parcial = 0.00; //se actualiza internamente
		$comentarios = '';
		$moneda = 'ARS';
		$tipo_cambio = $this->settings->cotizacion_dolar;
		$informe_id = 0;

		// FACTURA-------------------
		$talonario = $sucursal->talonario;		
		$tipo_comprobante = 'FA_'.$talonario;
		$valor_factura = $haber;

		$datos_factura['sucursal_id'] = $reserva->sucursal_id;		
		$datos_factura['vendedor_id'] = $reserva->vendedor_id;		
		$datos_factura['usuario_id'] = $tipo_id;
		$datos_factura['reserva_id'] = $reserva_id;
		$datos_factura['fecha'] = $fecha;
		$datos_factura['valor'] = $valor_factura;
		$datos_factura['punto_venta'] = 2;
		$datos_factura['forma_pago'] = $new_concepto;
		$datos_factura['total'] = $valor_factura;
		$datos_factura['gastos_adm'] = ( isset($data_transaction['gastos_adm']) && $data_transaction['gastos_adm'] > 0 ) ? $data_transaction['gastos_adm'] : 0.00;
		$datos_factura['retencioniibb'] = ( isset($data_transaction['retencioniibb']) && $data_transaction['retencioniibb'] > 0 ) ? $data_transaction['retencioniibb'] : 0.00;

		//Si corresponde facturacion, generar comprobante
		if ($conceptoObj->facturacion) {
			$datos_factura['tipo_factura'] = $talonario;
			$datos_factura['tipo'] = $tipo_comprobante;
			
			//agregado 08/07/14
			$datos_factura['concepto_id'] = $conceptoObj->id;
			$factura_id = $this->Factura->insert($datos_factura);
			
		}
		else {
			$factura_id = 0;
		}
		//-----------------------------------------------------------------
		
		pre($datos_factura);

		//obtengo parcial segun ultimo movimiento en base a $tipo_id,$tipo,
		//genero movimiento en cta cte de ese usuario en concepto de Pago de parte o total de la deuda
		$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($tipo_id,$tipo)->row();		
		
		pre($mov);
		
		//16/01/2020 para generar el registro en cuenta corriente al monto del haber (que es el valor neto del pago, le sumo el importe de retencion de IIBB)
		$haber = $haber+$datos_factura['retencioniibb'];

		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		if(isset($mov->parcial))
			$nuevo_parcial = $mov->parcial+$debe-$haber;
		else
			$nuevo_parcial = .00+$debe-$haber;
		
		//24-07-15 si es una nota de credito, le asocio la factura y el tipo de talonario
		$factura_asociada_id = '';
		$talonario_asociado = '';
		
		$movimiento_id = registrar_movimiento_cta_cte($tipo_id,$tipo,$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id);
		
		echo "<br>haber<br>";
		echo $haber;
		echo "<br>";
		echo $movimiento_id;
		echo "<br>";
		
		//genero registro en historial de reserva con marcas para que luego se le envie mail de PAGO RECIBIDO
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);

		
		//idem genero movimiento en cta cte de BBV en concepto de Pago de parte o total de la deuda por parte del usuario o agencia externa
		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A")->row();		
		$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) + $debe - $haber;
		
		$movimiento_agencia_id = registrar_movimiento_cta_cte(1,'A',$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial2,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id);
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);
		
		// pasa a CONFIRMADA si el concepto lo especifica -> según el estado TARJETA DE CREDITO / PAGO FACIL (MERCADOPAGO) -> Sí


		//si está seteada esta marca ($reserva_queda_anulada) la dejo en anulada y no la CONFIRMO
		if ($conceptoObj->pasa_a_confirmada == 1 && !$reserva_queda_anulada){
			//$pagos = $this->Movimiento->getPagosHechos($reserva_id, $reserva->usuario_id);
			//TODO: habria que confirmar si lo que pago alcanza para confirmar la reserva. Hay que verificar el minimo.

			$this->Reserva->update($reserva_id,array("estado_id"=>4));

			//Registrar la confirmacion de la reserva
			/*
			$mail = true;
			$template = 'pago_recibido';
			registrar_comentario_reserva($reserva->id,7,'cambio_estado','Reserva confirmada por acreditación de pago',$mail,$template,$movimiento_id);
			*/

			echo "confirmada";
		}
			
		//chequeo si la suma de los movimientos en la cta cte de BBV sobre esa reserva no alcanzan el monto total
		$movs = $this->Movimiento->getPago($reserva_id,"A",1)->row();
		
		//Si corresponde facturar, armo el comprobante
		if ($conceptoObj->facturacion) {
			//el ultimo parametro en false poruqe primero informa a afip, entonces el detalle tiene que vijar como si fuera para facturacion
			//luego cuando se va a generar el archivo fisico pdf (dentro de generar() ) se obtienen los datos para la factura con ese ultimo parametro en true
			
			//NO HACEMOS LA GENERACION AHORA SINO QUE LA HAREMOS POR EL CRON
			//$comprobante = $this->Factura->generar($factura_id, $tipo_comprobante, $sucursal->id, $reserva_id,true,false);
			$comprobante = FALSE;
			
			
			//04-01-18
			//tomo los IDs de movimientos de cuenta de psaajero y bv para ajustar saldo y haber si corresponde
			//si le movimiento tuvo percepcion, tengo que actualizar el valor del haber y parcial 
			//sacandole el importe correspondiente a percepcion
			$movs_ids = array($movimiento_id,$movimiento_agencia_id);
			
			$datos = $this->Factura->obtenerDatos($factura_id, $tipo_comprobante, $sucursal->id, $reserva_id);
			if(isset($datos['percepcion_3825']) && $datos['percepcion_3825']){
				foreach($movs_ids as $m_id){
					//movimiento_id
					$mov2 = $this->Movimiento->get($m_id)->row();
					
					if(isset($mov2) && isset($mov2->id) && $mov2->id){
						$nuevo_haber = $mov2->haber - $datos['percepcion_3825'];
						$parcial = $mov2->parcial + $datos['percepcion_3825'];
						$haber_usd = $mov2->haber_usd - ($datos['percepcion_3825']/$tipo_cambio);
						$parcial_usd = $mov2->parcial_usd + ($datos['percepcion_3825']/$tipo_cambio);
						
						$data_mov = array(
									"haber" => $nuevo_haber,
									"parcial" => $parcial,
									"haber_usd" => $haber_usd,
									"parcial_usd" => $parcial_usd
							);		
						$this->Movimiento->update($m_id,$data_mov);
					}
				}
			}
			
			pre($mov_ids);

			//si el comprobante fallo o vino con error
			if(!$comprobante || (isset($comprobante['error']) && $comprobante['error']) ){
				//$movimiento_id
				if($movimiento_id){
					$updata = array();
					$updata['factura_id'] = 0;
					$updata['comprobante'] = '';
					//$updata['talonario'] = '';
					//$this->Movimiento->update($movimiento_id,$updata);
				}
			}
		}
		else {
			$comprobante = FALSE;
		}

		echo "abajo";
		
		if($tipo=="U"){
			
			//01-11-19 Maxi: envio mail de voucher solo si la reserva esta confirmada
			if($reserva->estado_id == 4){
					
				/*
				si el operador del viaje es Buenas Vibras y si ya pago todo el monto del viaje 
				genero registro en historial para luego enviar el voucher adjunto
				*/
				//if( $paquete->operador_id == 1 && $movs->pago_hecho == 0 ){
				
				//06-03-19 EL envío del voucher ahora estaá definido por paquete
				if( $paquete->voucher_automatico && $movs->pago_hecho == 0 ){
					$mail = true;
					$template = 'voucher';
					registrar_comentario_reserva($reserva->id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);

				}	

			}	
			
		}
		


	}

	function ver_cupo(){
		$paquete_id = 13;
		$combinacion_id = 13;
		$pax = 2;


		$filtros = array();
		$filtros['pax'] = $pax;
		$filtros['disponibles'] = '1';
		$filtros['combinacion_id'] = $combinacion_id;
		$data_combinacion = $this->Combinacion->getByPaquete($paquete_id,9999,$filtros);
		
		echo $this->db->last_query();

		pre($data_combinacion);
	}

	
	function ver_pago($tr_code=3822676927){
		
		/*$id = 8;
		$tr = $this->Transaccion->get($id)->row();

		$data_transaction = array();
		$data_transaction['fecha_procesado'] = $tr->fecha_procesado;
		$data_transaction['monto_neto'] = $tr->monto_neto;
			
		$this->facturar_mp($id,$data_transaction);
		*/
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$data = $mp->get_payment_info($tr_code);
		
		echo "<pre>";
		print_r($data);

		exit();

		$existe_reserva = $this->Reserva->getWhere(array('code' => $data['response']['collection']['external_reference']))->row();
		
		$datos['numtransaccion'] = $tr_code;
		$trans = $this->Transaccion->getWhere($datos)->row();
		$tr_id = $trans->id;

		$status = $data['response']['collection']['status'];
		$status_detail = $data['response']['collection']['status_detail'];
		$date_approved = $data['response']['collection']['date_approved'];
		$amount = $data['response']['collection']['transaction_amount'];
		$monto_neto = $data['response']['collection']['net_received_amount'];
		
		//obtengo el importe de gastos administrativos para luego segregarlo en factura
		$gastos_adm = number_format($amount - $monto_neto,2,'.','');
		
		$data['response']['collection']['external_reference'] = trim($data['response']['collection']['external_reference']);

		$data_transaction = array();
		$data_transaction['fecha_procesado'] = $date_approved;
		$data_transaction['monto_neto'] = $monto_neto;
		$data_transaction['monto_total'] = $amount;
		$data_transaction['gastos_adm'] = $gastos_adm;
		$data_transaction['codigo'] = $data['response']['collection']['external_reference'];
		$data_transaction['numtransaccion'] = $tr_code;
		$data_transaction['estado'] = $status;
		$data_transaction['estado_detalle'] = $status_detail;
		$data_transaction['ip'] = $_SERVER['REMOTE_ADDR'];
		$data_transaction['reserva_id'] = $existe_reserva->id;

		$this->facturar_mp($tr_id,$data_transaction);
	}

	/*27-02-19
	metodo que procesa las notificaciones de paypal cuando el usuario acaba de pagar desde el nevagador
	es el returnURL y cancelURL de paypal.
	Genera la transaccion correspondiente y lo redirige al resumen del viaje
	Seria como EL IPN de MP pero para PAYPAL
	*/
	function paypal($hash){
		$data = desencriptar($hash);
		
		if(is_array($data) && count($data) == 2){
			$reserva = $this->Reserva->getWhere(array('code' => $data[0]))->row();
		}
		else{
			return;
		}

		$reserva = $this->Reserva->get($reserva->id)->row();

		$this->load->library('PayPallib');
		
	    $settings = [];
	    $settings['API_Username'] = $this->config->item('pp_username');
	    $settings['API_Password'] = $this->config->item('pp_password');
	    $settings['API_Signature'] = $this->config->item('pp_signature');
	    $settings['currency'] = 'USD';

		$settings['returnURL'] = site_url('pagos/paypal/'.encriptar($reserva->code));
		$settings['cancelURL'] = site_url('pagos/paypal/'.encriptar($reserva->code));

	    $pp = $this->paypallib;

	    //inicializo
		$pp->setup($settings);

		//defino modo prueba o no
		$pp->sandbox(false);
	
		$result = $pp->process();
		if ($result) {
			if ($result['status'] == 'success') {
				$result['status'] = 'completed';
			}

			$date_approved=date('Y-m-d H:i:s');

			//genero una descripcion con algo mas de detalle
			$descripcion = $result['description'];
			$descripcion .= " | Item price: ".$result['item_price'].' | Item total price: '.$result['total_price'].' | Tipo cambio: '.$this->settings->cotizacion_dolar;

			//el importe de la transaccion es tal cual viene de PP, por ende es en USD
			$result['item_price'] = $result['item_price'];
			$result['total_price'] = $result['total_price'];

			$data_transaction = array();
			$data_transaction['fecha_procesado'] = $date_approved;
			$data_transaction['monto_neto'] = $result['item_price'];
			$data_transaction['monto_total'] = $result['total_price'];
			$data_transaction['gastos_adm'] = $result['tax_amount'];
			$data_transaction['codigo'] = $reserva->codigo;
			$data_transaction['numtransaccion'] = $result["transaction_id"];
			$data_transaction['estado'] = $result['status'];
			$data_transaction['estado_detalle'] = $descripcion;
			$data_transaction['reserva_id'] = $reserva->id;
			$data_transaction['origen'] = 'paypal';
			
			$this->Transaccion->insert($data_transaction);

			//hasta este punto le genero la transaccion en la tabla, y luego estaria bueno que un proceso tome estas, las procese y genere movimientos en cuenta (eso lo hace el metodo de abajo procesar_paypal() )

			redirect(site_url('reservas/resumen/'.$hash));
		}
		else{
			redirect(base_url());
		}
	}

	function procesar_paypal(){
		//tomo de a 2 transacciones por procesar que esten marcadas como COMPLETED
		$this->Transaccion->filters = "origen = 'paypal' and estado = 'completed'";
		$trans = $this->Transaccion->getAll(2,0,'fecha_procesado','asc')->result_array();

		
		foreach ($trans as $t) {
			echo "transaccion<br>";
			pre($t);

			echo "reserva<br>";
			$reserva = $this->Reserva->get($t['reserva_id'])->row();
			echo $this->db->last_query();

			//ESTA PARTE ES IGUAL QUE PARA LOS IPN DE MP MAS ARRIBA
			if(isset($reserva->id) && $reserva->id){

				$reserva_queda_anulada = false;
					
				//si la reserva está anulada y entro un pago ver primero el cupo cupo
				if($reserva->estado_id == 5){


					//chequear si hay cupo en el viaje (combinacion) para la cantidad de pasajeros de la reserva
					$filtros = array();
					$filtros['pax'] = $reserva->pasajeros;
					//$filtros['disponibles'] = '1';
					$filtros['combinacion_id'] = $reserva->combinacion_id;
					$data_combinacion = $this->Combinacion->getByPaquete($reserva->paquete_id,1,$filtros);

					//si hay combinacion ,chequeo que la cantidad de pasajeros me alcance para ambos cupos de transporte y alojamiento
					//se muestra esta alarma en el listado de reservas del back
					if(isset($data_combinacion->id) && $data_combinacion->id && 
						($reserva->pasajeros > $data_combinacion->cupo_trans || $reserva->pasajeros > $data_combinacion->cupo_aloj) ){

						//seteo marca para que luego de facturar la ponga en ANULADA
						$reserva_queda_anulada = true;

						registrar_comentario_reserva($reserva->id,7,'comentario','Se recibió pago de Paypal por importe de USD '.$data_transaction['monto_total'].' pero no hay cupo para el viaje');
					}
					else{
						//pasar a nueva (con registro del movimiento por el importe de reserva)

						//doy de alta el registro inicial de la reserva
						//genero movimiento en cta cte de ese usuario
						$reserva_id = $reserva->id;
						$usuario_id = $reserva->usuario_id;
						$fecha_reserva = date('Y-m-d H:i:s');
						$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
						/*el valor del monto de reserva es segun el precio de venta de la combinacion, por las dudas si el registro de 
						reserva quedó con algun valor previo de otra combinacion*/
						$monto_reserva = $reserva->v_total+$reserva->adicionales_precio;
						
						$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($usuario_id,"U",$reserva->precio_usd)->row();		
						$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;
						
						//globales
						$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
						$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
						$tipo_cambio = $this->settings->cotizacion_dolar;
						
						registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
						
						//tambien seteo marcas en el historial para que luego se le envie mail de reserva
						$mail = true;
						$template = 'datos_reserva';

						registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);
							
						//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
						$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();			
						$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
						registrar_movimiento_cta_cte(1,"A",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
						registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
						//--------------------------------------------------------------------------	

						// pasar a nueva
						$this->Reserva->update($reserva->id,array('estado_id' => 1));

					}
					
				}

				echo "antes facturar<br>";
				//le facturo el pago
				$this->facturar_pp($t['id'],$t,$reserva_queda_anulada);
				
				echo "salio";
				//este status es NUESTRO para diferenciarlo del COMPLETED de paypal
				$this->Transaccion->update($t['id'],array('estado' => 'finished'));
				echo $this->db->last_query();
			}


		}

	}

	//Mismo metodo para facturar que MP pero para Paypal
	function facturar_pp($transaccion_id,$data_transaction,$reserva_queda_anulada=false){
		$transaccion = $this->Transaccion->get($transaccion_id)->row();
		$reserva = $this->Reserva->get($transaccion->reserva_id)->row();		
		$sucursal = $this->Sucursal->get($reserva->sucursal_id)->row();
		$conceptoObj = $this->Concepto->get(70)->row(); //concepto PAYPAL
		$paquete = $this->Paquete->get($reserva->paquete_id)->row();
		
		$tipo_cambio = $this->settings->cotizacion_dolar;
		
		//para usarlo para generar los mov en cuenta
		$monto_total = $data_transaction['monto_total'];

		//el monto de la transaccion va a estar en USD, por lo cual para facturar tengo q pasarlo a ARS
		$data_transaction['monto_neto'] = number_format($data_transaction['monto_neto']*$tipo_cambio,2,'.','');

		//idem gastos
		if(isset($data_transaction['gastos_adm'])){
			$data_transaction['gastos_adm'] = number_format($data_transaction['gastos_adm']*$tipo_cambio,2,'.','');
		}
		else{
			$data_transaction['gastos_adm'] = 0.00;
		}

		//datos grales del pago
		$debe = 0.00;
		$haber = $data_transaction['monto_neto'];
		//$fecha = $data_transaction['fecha_procesado'];
		$fecha = date('Y-m-d H:i:s');
		$tipo_id = $reserva->usuario_id;
		$tipo = 'U';
		$reserva_id = $reserva->id;
		$new_concepto = $conceptoObj->nombre;
		$nuevo_parcial = 0.00; //se actualiza internamente
		$comentarios = '';
		$moneda = 'USD';
		$informe_id = 0;

		// FACTURA-------------------
		$talonario = $sucursal->talonario;		
		$tipo_comprobante = 'FA_'.$talonario;
		$valor_factura = $haber;

		$datos_factura['sucursal_id'] = $reserva->sucursal_id;		
		$datos_factura['vendedor_id'] = $reserva->vendedor_id;		
		$datos_factura['usuario_id'] = $tipo_id;
		$datos_factura['reserva_id'] = $reserva_id;
		$datos_factura['fecha'] = $fecha;
		$datos_factura['valor'] = $valor_factura;
		$datos_factura['punto_venta'] = 2;
		$datos_factura['forma_pago'] = $new_concepto;
		$datos_factura['total'] = $valor_factura;
		$datos_factura['gastos_adm'] = $data_transaction['gastos_adm'];

		//Si corresponde facturacion, generar comprobante
		if ($conceptoObj->facturacion) {
			$datos_factura['tipo_factura'] = $talonario;
			$datos_factura['tipo'] = $tipo_comprobante;
			
			//agregado 08/07/14
			$datos_factura['concepto_id'] = $conceptoObj->id;
			$factura_id = $this->Factura->insert($datos_factura);
			
		}
		else {
			$factura_id = 0;
		}
		//-----------------------------------------------------------------
		
		pre($datos_factura);

		//obtengo parcial segun ultimo movimiento en base a $tipo_id,$tipo,
		//genero movimiento en cta cte de ese usuario en concepto de Pago de parte o total de la deuda
		$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($tipo_id,$tipo,$cta_usd=true)->row();		
echo "antes mov";		
echo "<br>";
		pre($mov);

	echo "luego mov";		
		//para los movimientos en cuenta, tomo el valor total que está en la transaccion y que es en USD
		echo $haber = $monto_total;

		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		if(isset($mov->parcial))
			$nuevo_parcial = $mov->parcial+$debe-$haber;
		else
			$nuevo_parcial = .00+$debe-$haber;
		
		//24-07-15 si es una nota de credito, le asocio la factura y el tipo de talonario
		$factura_asociada_id = '';
		$talonario_asociado = '';
		
		$movimiento_id = registrar_movimiento_cta_cte($tipo_id,$tipo,$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id);
		
		echo "<br>0<br>";
		echo $movimiento_id;
		echo "<br>1<br>";
		
		//genero registro en historial de reserva con marcas para que luego se le envie mail de PAGO RECIBIDO
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);

		
		//idem genero movimiento en cta cte de BBV en concepto de Pago de parte o total de la deuda por parte del usuario o agencia externa
		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A")->row();		
		$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) + $debe - $haber;
		
		$movimiento_agencia_id = registrar_movimiento_cta_cte(1,'A',$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial2,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id);
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);
		
		// pasa a CONFIRMADA si el concepto lo especifica -> según el estado TARJETA DE CREDITO / PAGO FACIL (MERCADOPAGO) -> Sí

echo "aca0";

		//si está seteada esta marca ($reserva_queda_anulada) la dejo en anulada y no la CONFIRMO
		if ($conceptoObj->pasa_a_confirmada == 1 && !$reserva_queda_anulada){
			//$pagos = $this->Movimiento->getPagosHechos($reserva_id, $reserva->usuario_id);
			//TODO: habria que confirmar si lo que pago alcanza para confirmar la reserva. Hay que verificar el minimo.

			$this->Reserva->update($reserva_id,array("estado_id"=>4));

			//Registrar la confirmacion de la reserva
			/*
			$mail = true;
			$template = 'pago_recibido';
			registrar_comentario_reserva($reserva->id,7,'cambio_estado','Reserva confirmada por acreditación de pago',$mail,$template,$movimiento_id);
			*/
		}
			
		//chequeo si la suma de los movimientos en la cta cte de BBV sobre esa reserva no alcanzan el monto total
		$movs = $this->Movimiento->getPago($reserva_id,"A",1)->row();
		
		echo "aca1";

		//Si corresponde facturar, armo el comprobante
		if ($conceptoObj->facturacion) {
			//el ultimo parametro en false poruqe primero informa a afip, entonces el detalle tiene que vijar como si fuera para facturacion
			//luego cuando se va a generar el archivo fisico pdf (dentro de generar() ) se obtienen los datos para la factura con ese ultimo parametro en true
			
			//NO HACEMOS LA GENERACION AHORA SINO QUE LA HAREMOS POR EL CRON
			//$comprobante = $this->Factura->generar($factura_id, $tipo_comprobante, $sucursal->id, $reserva_id,true,false);
			$comprobante = FALSE;
			
			
			//04-01-18
			//tomo los IDs de movimientos de cuenta de psaajero y bv para ajustar saldo y haber si corresponde
			//si le movimiento tuvo percepcion, tengo que actualizar el valor del haber y parcial 
			//sacandole el importe correspondiente a percepcion
			$movs_ids = array($movimiento_id,$movimiento_agencia_id);
			
			$datos = $this->Factura->obtenerDatos($factura_id, $tipo_comprobante, $sucursal->id, $reserva_id);
			if(isset($datos['percepcion_3825']) && $datos['percepcion_3825']){
				foreach($movs_ids as $m_id){
					//movimiento_id
					$mov2 = $this->Movimiento->get($m_id)->row();
					
					if(isset($mov2) && isset($mov2->id) && $mov2->id){
						$nuevo_haber = $mov2->haber - $datos['percepcion_3825'];
						$parcial = $mov2->parcial + $datos['percepcion_3825'];
						$haber_usd = $mov2->haber_usd - ($datos['percepcion_3825']/$tipo_cambio);
						$parcial_usd = $mov2->parcial_usd + ($datos['percepcion_3825']/$tipo_cambio);
						
						$data_mov = array(
									"haber" => $nuevo_haber,
									"parcial" => $parcial,
									"haber_usd" => $haber_usd,
									"parcial_usd" => $parcial_usd
							);		
						$this->Movimiento->update($m_id,$data_mov);
					}
				}
			}
			
			//si el comprobante fallo o vino con error
			if(!$comprobante || (isset($comprobante['error']) && $comprobante['error']) ){
				//$movimiento_id
				if($movimiento_id){
					$updata = array();
					$updata['factura_id'] = 0;
					$updata['comprobante'] = '';
					//$updata['talonario'] = '';
					//$this->Movimiento->update($movimiento_id,$updata);
				}
			}
		}
		else {
			$comprobante = FALSE;
		}
		
		echo "aca2";

		if($tipo=="U"){
			//01-11-19 Maxi: envio mail de voucher solo si la reserva esta confirmada
			if($reserva->estado_id == 4){

				/*
				si el operador del viaje es Buenas Vibras y si ya pago todo el monto del viaje 
				genero registro en historial para luego enviar el voucher adjunto
				*/
				//if( $paquete->operador_id == 1 && $movs->pago_hecho == 0 ){

				//06-03-19 EL envío del voucher ahora estaá definido por paquete
				if( $paquete->voucher_automatico && $movs->pago_hecho == 0 ){
					$mail = true;
					$template = 'voucher';
					registrar_comentario_reserva($reserva->id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
				}
				
			}
				
		}
		


	}


	// variables probeniantes de la tabla bv_facturacion (comprobante,tipo_comprobante,factura_id,reserva_id,sucursal_id,fecha)
	function download_pdf ($comprobante, $tipo_comprobante, $factura_id, $reserva_id, $sucursal_id=1, $fecha) {
		// 1.ir a buscar el pdf al servidor o al aws S3
		// 2.comprobar que el pdf este en el servidor
		// 3.hacer validacion para saber si esta en el S3 o en la carpeta '/data/'
		// 4.mostart en pantalla pdf correspondiente

		$this->load->library('aws_s3');

		$fileName = $comprobante . ".pdf";

		// verificar fecha del comprobante para saber donde ir a buscar el pdf
		$result = buscar_s3_o_servidor($fileName, $fecha);

		if($result) {
			header("Content-type:application/pdf");
			
			if( is_object($result) ) { // si es un objeto viene del S3
				echo $result['Body'];
				
			} else {
				@readfile($result);
			}	
			
		} else { // subida de pdf a S3
			
			// Obtener datos 
			$datos = $this->Factura->obtenerDatos($factura_id, $tipo_comprobante, $sucursal_id, $reserva_id, true,true);

			// generar QR de AFIP
			$datos['qr'] = generarUrlQR($datos);

			// guardar comprobante
			$data = generar_comprobante_S3($datos, $tipo_comprobante, './data/facturas/'); 

			// buscar comprobante
			$result = buscar_s3_o_servidor($data.'.pdf', false);

			// imprimir en pantalla
			header("Content-type:application/pdf");
			echo $result['Body'];

		}

		// Asignar comprobante a factura
		$this->Factura->asignarComprobante($factura_id, $tipo_comprobante, $comprobante); 

	}



}