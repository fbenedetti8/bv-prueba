 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	function run() {
		$this->disponibilidad_combinaciones();
		$this->disponibilidad_cupo();
		$this->actualizar_estados();
		$this->ordenes_pendientes();
		$this->ordenes_vencidas();
		$this->enviar_correos();
	}
	
	function probar(){
		$fecha_limite_pago_min = '2017-10-11 15:51:41';
		
		echo strtotime($fecha_limite_pago_min);
		echo "<br>";
		echo time();
	}
	
	function runrun() {
		$this->disponibilidad_combinaciones();
		$this->disponibilidad_cupo();
		#$this->actualizar_estados();
		$this->ordenes_pendientes();
		$this->ordenes_vencidas();
		$this->actualizar_vencimiento_cupos();
		$this->update_cotizacion();
		$this->facturas_afip();
		//$this->cupo_personalizado();
	}


	function facturar() {
		$this->load->model('Factura_model', 'Factura');
		
		//Obtengo la primer factura pendiente por orden
		$facturas = $this->db->query("select * from bv_facturas where cae='' and tipo in ('FA_B', 'NC_B') order by id limit 1")->result();
		foreach ($facturas as $factura) {
			//Genero la factura informando a AFIP
			$comprobante = $this->Factura->generar($factura->id, $factura->tipo, $factura->sucursal_id, $factura->reserva_id,true,false);	
			
			if ($comprobante !== FALSE && !is_array($comprobante)) {
				//Obtengo el o los movimientos relacionado con esta factura
				$movimiento = $this->db->query("SELECT * FROM bv_movimientos WHERE factura_id = ".$factura->id." AND talonario = '".$factura->tipo."' AND tipoUsuario = 'U'")->row();
				
				//Actualizo el numero de comprobante generado en los movimientos
				$this->db->query("UPDATE bv_movimientos SET comprobante = '$comprobante' WHERE factura_id = ".$factura->id);
				
				//Informo al cliente que recibimos el pago, con la factura adjunta
				$mail = true;
				$template = 'pago_recibido';
				registrar_comentario_reserva($factura->reserva_id,7,'cambio_estado','Reserva confirmada por acreditación de pago',$mail,$template,$movimiento->id);
			}
		}
	}


	/*
	Actualiza el valor de la cotizacion del dolar obtenido de currencylayer.com aplicado el porcentaje de ajuste establecido por backend
	Configurarlo para que ejecute cada 1 hora
	*/
	public function update_cotizacion(){
		/*
		$url = "http://apilayer.net/api/live?access_key=".$this->config->item('currency_api_key')."&currencies=ARS&format=1"; 
		$cont = file_get_contents($url);
		$cont = json_decode($cont);
		$cotizacion = @$cont->quotes->USDARS;
		*/
		
		$cotizacion = cotizacion_dolar();
		if($cotizacion>0){
			$cotizacion_dolar = $cotizacion*(1+$this->settings->cotizacion_ajuste/100);
			$this->db->query("update bv_config set cotizacion_dolar = '".$cotizacion_dolar."' where id = 1");
		}
	}
	
	/*
	//Cron para enviar los correos correspondientes de cada reserva
	//Levanta de la tabla bv_reservas_comentarios todos los movimientos que necesiten un envio y que
	//se encuentre pendiente.
	//
	//Este proceso deberia correr lo mas pronto posible para evitar demoras en los envios
	*/
	public function enviar_correos() {
		$envios = $this->db->query("SELECT id, reserva_id, template, ref_id FROM bv_reservas_comentarios WHERE mail=1 AND enviado=0 ORDER BY fecha")->result();

		foreach ($envios as $envio) {

			switch ($envio->template) {
				case 'cupo_liberado': 
					$enviado = enviar_mail_cupo_liberado($envio->reserva_id); 
					break;
				case 'mail_confirmacion': 
					$enviado = enviar_mail_confirmacion($envio->reserva_id); 
					break;
				case 'datos_reserva': 
					$enviado = enviar_datos_reserva($envio->reserva_id); 
					break;
				case 'informe_pago_recibido':					
					$enviado = enviar_informe_pago_recibido($envio->reserva_id, $envio->ref_id); 
					break;
				case 'pago_recibido':
					$enviado = enviar_recepcion_pago($envio->reserva_id, $envio->ref_id); 
					break;		
				case 'datos_reserva_faltan_datos':
					$enviado = enviar_faltan_datos($envio->reserva_id); 
					break;
				case 'saldo_pendiente': 
					$enviado = enviar_saldo_pendiente($envio->reserva_id); 
					break;
				case 'reserva_anulada': 
					$enviado = enviar_reserva_anulada($envio->reserva_id); 
					break;
				case 'reserva_anulada_manual': 
					$enviado = enviar_reserva_anulada_manualmente($envio->reserva_id); 
					break;
				case 'datos_completos':
					$enviado = enviar_datos_completos($envio->reserva_id); 
					break;
				case 'pago_completo':
					$enviado = enviar_pago_completo($envio->reserva_id); 
					break;	
				case 'ajuste_precio':
					$enviado = enviar_ajuste_precio($envio->reserva_id); 
					break;
				case 'cambios_reserva':
					$enviado = enviar_cambios_reserva($envio->reserva_id); 
					break;
				case 'voucher':
					$enviado = enviar_mail_vouchers($envio->reserva_id, @$envio->ref_id); 
					break;
			}

			if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $enviado) {
				$this->db->query("UPDATE bv_reservas_comentarios SET enviado=1, fecha_enviado='".date("Y-m-d H:i:s")."' WHERE id = ".$envio->id);
			}

		}
	}

	/*
	//Cron para actualizar disponibilidad de combinaciones 
	//la marca que se usa es la de AGOTADA en la combinacion
	//habria que chequear el tema de cupo disponibles vs reservados para marcarlo como AGOTADA o no
	
	Este proceso deberia actualzar esa marca de AGOTADA en cada combinacion, teniendo en cuenta
	si el cupo de esa combinacion ya fue reservado completo o no
	*/
	public function disponibilidad_combinaciones(){
		//obtengo cantidad de cupo reservado por combinacion con los datos de cupos de alojamientos y transportes
		//obtengo lsitado de combinaciones vigentes con cantidad de reservadas
		//las que no sean de HABITACION COMPARTIDA

		//los saque del join
		//r.estado_id IN (1,2,4) and 
		$comb = $this->db->query("select  pc.id, pc.fecha_alojamiento_cupo_id, 
									pc.fecha_transporte_id, 
									ifnull(x.reservadas,0) as reservadas
									from bv_paquetes_combinaciones pc
									join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
									join (select r.combinacion_id, sum(case when r.estado_id in (1,2,4) then r.pasajeros else 0 end) as reservadas 
										from bv_reservas r where r.habitacion_id != 99 group by r.combinacion_id) x 
										on x.combinacion_id = pc.id
									where pc.habitacion_id != 99
									group by pc.id")->result();
						
		foreach($comb as $r){
			//por cada combinacion obtengo los cupos de transporte y alojamiento
			$ct = $this->db->query("
					select  
						sum(tf.cupo) as t_disponible, 
						sum(tf.cupo_total) as t_total
					from bv_transportes_fechas tf 
					where tf.id = ".$r->fecha_transporte_id)->row();
			$ca = $this->db->query("
					select  
						sum(fc.cupo) as a_disponible, 
						sum(fc.cupo_total) as a_total 
					from bv_alojamientos_fechas_cupos fc 
					where fc.id = ".$r->fecha_alojamiento_cupo_id)->row();
					
			//chequeo cual es el menor cupo disponible (el del alojamiento o transporte)
			//$cupo_disponible = $r->a_disponible < $r->t_disponible ? $r->a_disponible : $r->t_disponible;
			
			$cupo_disponible = $ca->a_disponible > $ct->t_disponible ? $ct->t_disponible : $ca->a_disponible;
			
			//if($r->reservadas >= $cupo_disponible){
			//si el menor cupo de transporte o alojamiento todavia tiene
			if($cupo_disponible > 0){
				//update marca AGOTADA para la combinacion, combinacion disponible
				$this->db->query("UPDATE bv_paquetes_combinaciones set agotada = 0 WHERE id = ".$r->id);				
			}
			else{
				//update marca AGOTADA para la combinacion
				$this->db->query("UPDATE bv_paquetes_combinaciones set agotada = 1 WHERE id = ".$r->id);				
			}
		}
		
		
		
		//ahora las que SON de HABITACION COMPARTIDA
		//lo saque del join 
		//where r.estado_id IN (1,2,4) 
		$comb = $this->db->query("select  pc.id, pc.fecha_alojamiento_cupo_id, 
									pc.fecha_transporte_id, 
									ifnull(x.reservadas,0) as reservadas, p.cupo_disponible, p.cupo_total 
									from bv_paquetes_combinaciones pc
									join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
									join (select r.combinacion_id, sum(case when r.estado_id in (1,2,4) then r.pasajeros else 0 end) as reservadas 
										from bv_reservas r where r.habitacion_id = 99 group by r.combinacion_id) x 
										on x.combinacion_id = pc.id
									where pc.habitacion_id = 99
									group by pc.id")->result();
		
		foreach($comb as $r){
			if($r->reservadas >= $r->cupo_total){
				//update marca AGOTADA para la combinacion
				$this->db->query("UPDATE bv_paquetes_combinaciones set agotada = 1 WHERE id = ".$r->id);				
			}
			else{
				//update marca AGOTADA para la combinacion, combinacion disponible
				$this->db->query("UPDATE bv_paquetes_combinaciones set agotada = 0 WHERE id = ".$r->id);				
			}
		}
		
	}
	
	/*
	//calcular la disponibilidad de cupo y asociarla al paquete
	//la menor entre el alojamiento y el transporte
	
	Este proceso deberia actualizar los campos cupo_total y cupo_disponible de cada paquete (tabla paquetes)
	que se usan para mostrar si hay cupo o no en la pagina de destino y paquete.
	Este cupo total y disponible es en funcion del cupo que haya en sus combinaciones
	*/
	public function disponibilidad_cupo(){	
		actualizar_cupos();
		
		//Confirmar si hace falta hacer algo mas
		return;
	
		/*foreach($res as $r){
			if($r->cantidad > 0){
			
				//ES ACA!!!
				$t_cupo = $r->cupo_total-$r->cantidad;
				$t_cupo = $t_cupo > 0 ? $t_cupo : 0;
				$this->db->query("UPDATE bv_paquetes set cupo_disponible = ".$t_cupo." WHERE id = ".$r->id);

				$ids_updated[] = $r->id;
			}
			
			
			// //actualizo cupo teniendo en cuenta el de transporte compartido
			// if($r->p_id > 0 and $r->cant_r > 0){
			// 	$t_cupo = $r->cupo_total-$r->cant_r;
			// 	$t_cupo = $t_cupo > 0 ? $t_cupo : 0;
			// 	$this->db->query("UPDATE bv_paquetes set cupo_disponible = ".$t_cupo." WHERE id = ".$r->id);

			// 	$ids_updated[] = $r->id;
			// }
			
		}

		//en base a los paquetes ya actualizados me fijo si no quedó alguno que no se haya actualizado
		if(count($ids_updated)){
			$ids = implode(',',array_unique($ids_updated));
	
			$res = $this->db->query("select p.* from bv_paquetes p 
				where p.id not in (".$ids.") and p.activo = 1")->result();
			
			foreach($res as $p){
				//voy a actualizar el cupo disponible y total en base a transporte y alojamiento menor
				$ct = $this->db->query("
						select min(fc.cupo) as t_disponible, min(fc.cupo_total) as t_total
							from bv_paquetes_combinaciones pc
							join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
							join bv_transportes_fechas fc on fc.id = pc.fecha_transporte_id
							where pc.agotada = 0 and p.id = ".$p->id)->row();
				
				
				// $ca = $this->db->query("
				// 		select min(a.cupo_alojamiento) as a_disponible, min(a.cupo_alojamiento) as a_total
				// 			from bv_paquetes_combinaciones pc
				// 			join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
				// 			join bv_alojamientos_fechas_cupos fc on fc.id = pc.fecha_alojamiento_cupo_id
				// 			join bv_alojamientos_fechas af on af.id = fc.fecha_id 
				// 			join bv_alojamientos a on a.id = af.alojamiento_id 
				// 			where pc.agotada = 0 and p.id = ".$p->id)->row();
				
				
				$ca = $this->db->query("
						SELECT min(t.habitacion_id) as hab_id, sum(case when t.habitacion_id != 99 then t.cupo else 0 end) as a_disponible, 
								sum(case when t.habitacion_id != 99 then t.cupo_total else 0 end) as a_total  
							from (select fc.* 
									from bv_paquetes_combinaciones pc
									join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
									join bv_alojamientos_fechas_cupos fc on fc.id = pc.fecha_alojamiento_cupo_id
									join bv_alojamientos_fechas af on af.id = fc.fecha_id 
									join bv_alojamientos a on a.id = af.alojamiento_id 
									where pc.agotada = 0 and p.id = ".$p->id."
									group by fc.id
								) t")->row();
				
				if($ca->hab_id == 99){
					//si solo tien hab compartida, disponible y total va a ser 0, entonces me base en el del transporte
					$p_cupo_dispo = $ct->t_disponible;
					$p_cupo_total = $ct->t_total;
				}
				else{
					//si tiene otro tipo de hab, me base en el cupo que tenga
					
					//en el paquete actualizo el cupo disponible en base al menor cupo de alojamiento y transporte
					$p_cupo_dispo = $ca->a_disponible > $ct->t_disponible ? $ct->t_disponible : $ca->a_disponible;
					//el total que respete cuál es el menor de los disponibles
					$p_cupo_total = $ca->a_total > $ct->t_total ? $ct->t_total: $ca->a_total;
				}
				
				if ($p_cupo_total) {
					$p_cupo_dispo = $p_cupo_dispo > 0 ? $p_cupo_dispo : 0;
					$this->db->query("UPDATE bv_paquetes set cupo_total = ".$p_cupo_total.", cupo_disponible = ".$p_cupo_dispo." WHERE id = ".$p->id);
					
				}
			}
		}*/
	}		

	/*
	este metodo actualiza el estado de las reservas en funcion de su situacion
	*/
	public function actualizar_estados(){
		$this->load->model('Reserva_model', 'Reserva');
		$this->load->model('Movimiento_model', 'Movimiento');
		$this->load->model('Combinacion_model', 'Combinacion');

		//no tiene en cuenta las reservas con fecha extendida
		$reservas = $this->db->query("SELECT r.id, r.usuario_id, r.estado_id, r.paquete_precio, r.impuestos, r.adicionales_precio, 
										r.fecha_limite_pago_min, r.fecha_limite_pago_completo, r.fecha_limite_datos, 
										e.horas_vencimiento, r.completo_paso2, r.completo_paso3, p.precio_usd, r.cotizacion, r.fecha_extendida
																	FROM bv_reservas r
																	INNER JOIN bv_reservas_estados e ON e.id = r.estado_id
																	INNER JOIN bv_paquetes p ON p.id = r.paquete_id
																	WHERE manual=0 AND estado_id IN (1,2) and (r.fecha_extendida is null or r.fecha_extendida < now())")->result();

		
		foreach ($reservas as $reserva) {
			//Si falta un dia para la fecha de limite de pago minimo se cambia a X VENCER
			//if ($reserva->estado_id == 1 && time() >= strtotime($reserva->fecha_limite_pago_min.' - 1 day')) {				
			if ($reserva->estado_id == 1 && time() >= strtotime($reserva->fecha_limite_pago_min)) {				
				registrar_comentario_reserva($reserva->id, 7, 'cambio_estado', 'Cambio de estado automático a X VENCER', $mail=true, $template='datos_reserva', $ref_id=false);
				
				$fecha_limite_pago_min = new DateTime(date('Y-m-d H:i:s'));
				$fecha_limite_pago_min->add(new DateInterval('PT'.$this->settings->horas_pago_min.'H'));

				//Pongo el estado en por vencer con la nueva fecha limite (1 hs mas)
				$nueva_fecha = $fecha_limite_pago_min->format('Y-m-d H:i:s');
				
				$this->Reserva->update($reserva->id, array('estado_id' => 2, 'fecha_limite_pago_min' => $nueva_fecha)); //X VENCER
			}
			elseif (time() >= strtotime($reserva->fecha_limite_pago_min)) {
				//genero registro en cuenta por anulacion del importe del viaje
				$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
				$mov5 = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U")->row();
				//este dato de parcial se actualize en la sig funcion
				$nuevo_parcial5 = 0;
				registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,date('Y-m-d H:i:s'),"ANULACION RESERVA",0.00,$monto_reserva,$nuevo_parcial5,'','','','','','',$reserva->precio_usd?'USD':'ARS',$reserva->cotizacion?$reserva->cotizacion:$this->settings->cotizacion_dolar,false,false,false);
				
				registrar_comentario_reserva($reserva->id, 7, 'anulacion_automatica', 'Anulación por falta de pago', $mail=true, $template='reserva_anulada', $ref_id=false);

				$row_reserva = $this->Reserva->get($reserva->id)->row();
				verificar_costo_operador($row_reserva);
			
				$this->Reserva->update($reserva->id, array('estado_id' => 5)); //ANULADA

				//27-08-18 si el viaje es grupal, y estoy anulando una reserva de ese grupo, a todas las del mismo las tengo que reasignar a compartida
				if($row_reserva->grupal){
					asignar_habitacion_compartida($reserva->id);
				}

				//si se cancela automaticamente, y si hay reservas en lista de espera para este viaje, les envio mail, hasta uno por dia
				enviar_lista_espera($reserva->id);
			}
		}

		

		$reservas = $this->db->query("SELECT r.id, r.usuario_id, r.estado_id, r.paquete_precio, r.impuestos, r.adicionales_precio, r.combinacion_id, 
										r.fecha_limite_pago_min, r.fecha_limite_pago_completo, r.fecha_limite_datos, 
										e.horas_vencimiento, r.completo_paso2, r.completo_paso3, p.precio_usd, r.cotizacion, r.fecha_extendida 
																	FROM bv_reservas r
																	INNER JOIN bv_reservas_estados e ON e.id = r.estado_id
																	INNER JOIN bv_paquetes p ON p.id = r.paquete_id
																	WHERE manual=0 AND estado_id = 4 AND p.fecha_inicio >= '".date('Y-m-d')."' and (r.fecha_extendida is null or r.fecha_extendida < now())")->result();

		foreach ($reservas as $reserva) {
			$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
			$reserva_adicionales = adicionales_reserva($reserva);
			$precios = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],@$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);


			//si ya alcanzo la fecha limite de pago
			if (strtotime($reserva->fecha_limite_pago_completo) <= time()) {
				
				//pedido nuevo reunion 06-06-18
				//si la reserva tiene pagos hechos, que mande mail de saldo pendiente y que NO la anule automaticamente
				$mov = $this->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
				$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;
				$pagos_hechos = $precios['num']['monto_abonado'];

				if($pagos_hechos > 0){
					
					//si todavia le queda algo por pagar
					if(@$precios['num']['saldo_pendiente'] > 0){
						//me fijo si ya no hay un registro para este envio de mail
						$re = $this->db->query("select * from bv_reservas_comentarios where reserva_id = ".$reserva->id." and template = 'saldo_pendiente'")->result();
						if(count($re)==0){
							//si no hay registro de este tipo, lo genero
							registrar_comentario_reserva($reserva->id, 7, 'envio_mail', 'Aviso de reserva por vencer por falta de pago', $mail=true, $template='saldo_pendiente', $ref_id=false);
						}	
					}
				}
				else{
					//si no tiene pagos hechos => que la ANULE

					//me fijo si ya no hay un registro para este envio de mail
					$res = $this->db->query("select * from bv_reservas_comentarios where reserva_id = ".$reserva->id." and template = 'reserva_anulada'")->result();

					if(count($res)==0){
				
						registrar_comentario_reserva($reserva->id, 7, 'anulacion_automatica', 'Anulación por falta de pago', $mail=true, $template='reserva_anulada', $ref_id=false);

						//genero registro en cuenta por anulacion del importe del viaje
						$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
						$mov5 = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U")->row();
						//este dato de parcial se actualize en la sig funcion
						$nuevo_parcial5 = 0;
						registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,date('Y-m-d H:i:s'),"ANULACION RESERVA",0.00,$monto_reserva,$nuevo_parcial5,'','','','','','',$reserva->precio_usd?'USD':'ARS',$reserva->cotizacion?$reserva->cotizacion:$this->settings->cotizacion_dolar,false,false,false);
					
						$row_reserva = $this->Reserva->get($reserva->id)->row();
						verificar_costo_operador($row_reserva);
					
						$this->Reserva->update($reserva->id, array('estado_id' => 5)); //ANULADA

						//27-08-18 si el viaje es grupal, y estoy anulando una reserva de ese grupo, a todas las del mismo las tengo que reasignar a compartida
						if($row_reserva->grupal){
							asignar_habitacion_compartida($reserva->id);
						}
					}	
				}	
			}
			elseif (time() >= strtotime($reserva->fecha_limite_pago_completo.' - 2 days')) {
				//si todavia le queda algo por pagar
				if(@$precios['num']['saldo_pendiente'] > 0){
					//me fijo si ya no hay un registro para este envio de mail
					$res = $this->db->query("select * from bv_reservas_comentarios where reserva_id = ".$reserva->id." and template = 'saldo_pendiente'")->result();
					if(count($res)==0){
						//si no hay registro de este tipo, lo genero
						registrar_comentario_reserva($reserva->id, 7, 'envio_mail', 'Aviso de reserva por vencer por falta de pago', $mail=true, $template='saldo_pendiente', $ref_id=false);
					}
				}
			}
			elseif (!$reserva->completo_paso2 && time() >= strtotime($reserva->fecha_limite_datos.' - 2 days')) {
				//me fijo si ya no hay un registro para este envio de mail
				$res = $this->db->query("select * from bv_reservas_comentarios where reserva_id = ".$reserva->id." and template = 'datos_reserva_faltan_datos'")->result();
				if(count($res)==0){
					//si no hay registro de este tipo, lo genero
					registrar_comentario_reserva($reserva->id, 7, 'envio_mail', 'Aviso de reserva con datos incompletos', $mail=true, $template='datos_reserva_faltan_datos', $ref_id=false);
				}
			}

		}

		
		//reservas en estado POR ACREDITAR luego de las 48hs deben pasar a POR VENCER
		//no tiene en cuenta las reservas con fecha extendida
		/*
		$reservas = $this->db->query("SELECT r.id, r.fecha_reserva, r.usuario_id, r.estado_id, r.paquete_precio, r.impuestos, r.adicionales_precio, 
										e.horas_vencimiento, r.completo_paso3, p.precio_usd, r.cotizacion, r.fecha_extendida
																	FROM bv_reservas r
																	INNER JOIN bv_reservas_estados e ON e.id = r.estado_id
																	INNER JOIN bv_paquetes p ON p.id = r.paquete_id
																	WHERE manual=0 AND estado_id = 14 and (r.fecha_extendida is null or r.fecha_extendida < now())")->result();

		
		foreach ($reservas as $reserva){
			//por cada reservo chequeo si no hay algun informe de pago que hayan pasado las 48hs y todavia no se registró la confirmacion????

			if(time() >= strtotime($reserva->fecha_reserva.' - 2 day')){
				registrar_comentario_reserva($reserva->id, 7, 'cambio_estado', 'Cambio de estado automático a X VENCER', $mail=true, $template='datos_reserva', $ref_id=false);

				$this->Reserva->update($reserva->id, array('estado_id' => 2)); //X VENCER
			}
		}
		*/

	}

	/*
	este metodo debería buscar las ordenes que hayan sido completadas hasta el paso 3,
	pero que no hayan elegido ningun medio de pago.
	Para las cuales deberían tratarse como si hubiesen elegido PAGAR LUEGO, entonces
	se le deberia enviar el mail de PRE RESERVA
	
	Podríamos considerar las ordenes pasadas 1 hora desde su creacion, enviarles el mail y 
	actualizarles la marca de "mail_prereserva" en la orden
	*/
	public function ordenes_pendientes(){
		date_default_timezone_set('America/Argentina/Buenos_Aires');

		//Obtener ordenes a las cuales debo mandarles el mail de pre-reserva, porque paso mas de una hora desde que se hicieron
		$ordenes = $this->db->query("SELECT id FROM bv_ordenes WHERE (completo_paso3=1 OR salteo_paso3=1) AND completo_paso4=0 AND mail_prereserva=0 AND TIMESTAMPDIFF(HOUR, fecha_orden, '".date('Y-m-d H:i:s')."') >= 1")->result();

		/*if($_SERVER['REMOTE_ADDR'] == '190.18.187.8'){
			echo $this->db->last_query();
			pre($ordenes);
			exit();
		}*/

		foreach ($ordenes as $orden) {
			if (enviar_datos_orden($orden->id)) {
				$this->db->query("UPDATE bv_ordenes SET mail_prereserva=1 WHERE id=".$orden->id);
			}
		}
		
		//actualizo ordenes que ni siquiera hayan llegado al ultimo paso del checkout
		$ordenes = $this->db->query("SELECT id, fecha_orden,completo_paso1, completo_paso2, completo_paso3, completo_paso4 FROM bv_ordenes WHERE (completo_paso2=0 OR completo_paso1=0) AND TIMESTAMPDIFF(HOUR, fecha_orden, '".date('Y-m-d H:i:s')."') >= 1")->result();
		foreach ($ordenes as $orden) {
			$this->db->query("UPDATE bv_ordenes SET vencida=1 WHERE id=".$orden->id);
		}
	}
	
	/*
	Este metodo debe chequear el vencimiento de las ordenes segun la cantidad de horas de vigencia seteadas por admin,
	y en caso de vencimiento, actualiza marca de vencida
	*/
	public function ordenes_vencidas(){
		date_default_timezone_set('America/Argentina/Buenos_Aires');

		if($_SERVER['REMOTE_ADDR'] == '190.18.187.8'){
			$ordenes = $this->db->query("select * from bv_ordenes WHERE (vencida = 0 or vencida is null) and completo_paso4=0 AND mail_prereserva=1 AND TIMESTAMPDIFF(HOUR, fecha_orden, '".date('Y-m-d H:i:s')."') >= ".$this->settings->horas_orden)->result();

			echo $this->db->last_query();
			pre($ordenes);
			exit();
		}

		//Anular las ordenes que llevan mas del tiempo configurado
		$this->db->query("UPDATE bv_ordenes SET vencida=1 WHERE (vencida = 0 or vencida is null) and completo_paso4=0 AND mail_prereserva=1 AND TIMESTAMPDIFF(MINUTE, fecha_orden, '".date('Y-m-d H:i:s')."') >= ".$this->settings->horas_orden);	

		//20-09-18 Anular las ordenes que ya tienen mas de 1 hora porque vencieron o se efectivizaron
		$this->db->query("UPDATE bv_ordenes SET vencida=1 WHERE (vencida = 0 or vencida is null) AND TIMESTAMPDIFF(HOUR, fecha_orden, '".date('Y-m-d H:i:s')."') > 1");		
	}
	
  /*
	Chequea las fechas de vencimiento de cada cupo de paquete disponible, y en caso de haber llegado al vencimiento, se actualiza
	el estado del paquete A CONFIRMAR (confirmacion_inmediata en false)
	*/
	public function actualizar_vencimiento_cupos(){
		$res = $this->db->query("select p.id, tf.fecha_vencimiento 
									from bv_transportes_fechas tf
									join bv_paquetes_combinaciones pc on pc.fecha_transporte_id = tf.id
									join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1")->result();
		foreach($res as $r){
			if($r->fecha_vencimiento >= '0000-00-00' && $r->fecha_vencimiento <= date('Y-m-d') && $r->id){
				$this->db->query("UPDATE bv_paquetes set confirmacion_inmediata = 0 WHERE id = ".$r->id);
			}
		}
	}
	
	/* metodo de prueba para ver los mails que genera el sistema */
	public function ver_mails_old(){
		$q = "select c.reserva_id, c.template, c.ref_id
				from bv_reservas_comentarios c
				join bv_comentarios_tipos_acciones ta on ta.id = c.tipo_id
				where c.template != '' and c.mail = 1 and c.ref_id > 0
				group by c.tipo_id limit 1";
		$res = $this->db->query($q);
		foreach($res->result() as $envio){
					$enviado = enviar_datos_reserva($envio->reserva_id,true); 
					$enviado = enviar_informe_pago_recibido($envio->reserva_id, $envio->ref_id,true); 
					$enviado = enviar_recepcion_pago($envio->reserva_id, $envio->ref_id,true); 
					$enviado = enviar_faltan_datos($envio->reserva_id,true); 
					$enviado = enviar_saldo_pendiente($envio->reserva_id,true); 
					$enviado = enviar_reserva_anulada($envio->reserva_id,true); 
					$enviado = enviar_reserva_anulada_manualmente($envio->reserva_id,true); 
					$enviado = enviar_datos_completos($envio->reserva_id,true); 
					$enviado = enviar_pago_completo($envio->reserva_id,true); 
					$enviado = enviar_ajuste_precio($envio->reserva_id,true); 
					$enviado = enviar_mail_vouchers($envio->reserva_id,@$envio->ref_id,true);
		}
		
		$ordenes = $this->db->query("SELECT id FROM bv_ordenes WHERE (completo_paso3=1 OR salteo_paso3=1) limit 1")->result();
		foreach ($ordenes as $orden) {
			enviar_datos_orden($orden->id,true);
		}
			
	}

	function ver_mails(){
		
		$reserva_id = $_SERVER['SERVER_NAME'] == 'buenasvibrasdev.id4you.com' ? 789 : 41;
		
		$enviado = enviar_datos_reserva($reserva_id,true); 
		
		$ref_id = $_SERVER['SERVER_NAME'] == 'buenasvibrasdev.id4you.com' ? 169 : 2; //id inform pago
		$enviado = enviar_informe_pago_recibido($reserva_id, $ref_id,true); 
		
		$ref_id = $_SERVER['SERVER_NAME'] == 'buenasvibrasdev.id4you.com' ? 5087 : 1171; //id mov
		$enviado = enviar_recepcion_pago($reserva_id, $ref_id,true); 

		$enviado = enviar_faltan_datos($reserva_id,true); 
		$enviado = enviar_saldo_pendiente($reserva_id,true); 
		$enviado = enviar_reserva_anulada($reserva_id,true); 
		$enviado = enviar_reserva_anulada_manualmente($reserva_id,true); 
		$enviado = enviar_datos_completos($reserva_id,true); 
		$enviado = enviar_pago_completo($reserva_id,true); 
		$enviado = enviar_ajuste_precio($reserva_id,true); 
		$enviado = enviar_mail_vouchers($reserva_id,$ref_id,true);

		$orden_id = $_SERVER['SERVER_NAME'] == 'buenasvibrasdev.id4you.com' ? 1795 : 45;
		enviar_datos_orden($orden_id,true);
		
		echo $this->load->view('mails/pre-viaje','',true);
	}

	function update_saldos_ctas(){
		//obtengo usuario qe tenga movimientos
		$q = "select distinct m.usuario_id from bv_movimientos m where tipoUsuario = 'U' and usuario_id = 10";
		$res = $this->db->query($q);

		$parcial_usd = 0.00;
		$parcial = 0.00;
		foreach ($res->result() as $r) {
			//primero la CTA en USD
			$q = "select * from bv_movimientos m
					where usuario_id = ".$r->usuario_id." and tipoUsuario = 'U' and cta_usd = 1
					order by fecha asc";

			$movs = $this->db->query($q);
			foreach ($movs->result() as $m) {
				$parcial_usd += $m->debe_usd-$m->haber_usd;

				$q = "update bv_movimientos set parcial_usd = '".$parcial_usd."' where id = ".$m->id;
			}

			//luego la CTA en ARS
			$q = "select * from bv_movimientos m
					where usuario_id = ".$r->usuario_id." and tipoUsuario = 'U' and cta_usd = 0
					order by fecha asc";

			$movs = $this->db->query($q);
			foreach ($movs->result() as $m) {
				$parcial += $m->debe-$m->haber;

				$q = "update bv_movimientos set parcial = '".$parcial."' where id = ".$m->id;
			}
		}

		$parcial_usd = 0.00;
		$parcial = 0.00;

		//obtengo AGENCIAS qe tenga movimientos
		$q = "select distinct m.usuario_id from bv_movimientos m where tipoUsuario = 'A'";
		$res = $this->db->query($q);

		foreach ($res->result() as $r) {
			//primero la CTA en USD
			$q = "select * from bv_movimientos m
					where usuario_id = ".$r->usuario_id." and tipoUsuario = 'A' and cta_usd = 1
					order by fecha asc";

			$movs = $this->db->query($q);
			foreach ($movs->result() as $m) {
				$parcial_usd += $m->debe_usd-$m->haber_usd;

				$q = "update bv_movimientos set parcial_usd = '".$parcial_usd."' where id = ".$m->id;
			}

			//luego la CTA en ARS
			$q = "select * from bv_movimientos m
					where usuario_id = ".$r->usuario_id." and tipoUsuario = 'A' and cta_usd = 0
					order by fecha asc";

			$movs = $this->db->query($q);
			foreach ($movs->result() as $m) {
				$parcial += $m->debe-$m->haber;

				$q = "update bv_movimientos set parcial = '".$parcial."' where id = ".$m->id;
			}
		}
	}

	public function test_cupo(){	
		//actualizo el cupo de cada paquete segun la cantidad de reservas
		$res = $this->db->query("select t1.cant as cantidad, p.* 
			from bv_paquetes p 
			left join (select R2.paquete_id, sum(R2.pasajeros) as cant from bv_reservas R2 join bv_paquetes_combinaciones c on c.id = R2.combinacion_id where R2.estado_id > 0 and R2.estado_id not in (5,13) group by R2.paquete_id) t1 on t1.paquete_id = p.id")->result();
	
		foreach($res as $r){
			if($r->cantidad > 0){
			
				$t_cupo = $r->cupo_total-$r->cantidad;
				$this->db->query("UPDATE bv_paquetes set cupo_disponible = ".$t_cupo." WHERE id = ".$r->id);

			}
		}

	}

	function facturas_afip(){
		ini_set('max_execution_time',3600);
		ini_set('memory_limit','512M');

		$this->load->model('Factura_model','Factura');
		$this->load->model('Sucursal_model','Sucursal');

		$mes_pasado = date('m',strtotime("-1 month"));
		$anio = date('Y');
		
		if($mes_pasado == 12){
			$anio = date('Y',strtotime("-1 year"));
		}
			
		//ahora tomo todas las facturas del mes pasado en adelante
		$filtro = 'bv_facturas.fecha >= "'.$anio.'-'.$mes_pasado.'-01"';
		$this->Factura->filters = $filtro." and datos_afip = 0";
		
		$limit = 100;
		$offset = 0;
		$results = $this->Factura->getAll($limit,$offset, 'bv_facturas.fecha', 'desc', '')->result();
		

		foreach($results as $res){		
			//fix 06-01-15 para los viajes al exterior consulto con AFIP para ver el tema de los impuestos
			$suc = $this->Sucursal->get($res->sucursal_id)->row();
			$data_afip['punto_venta'] = @$suc->codigoFacturacion; //'0002'; //bs as 0002 rosario 0005
			$tipo_factura = $res->talonario;
			$tipo_factura = explode('_',$tipo_factura);
			$data_afip['codigo_factura'] = ($tipo_factura[0] == 'FA') ? '006' : '008'; //factura 006 NC 008
			$data_afip['numero_factura'] = $res->id;
								
			//descomentar esto 
			$this->load->library('afip');
			
			$res_afip = $this->afip->consultar($data_afip);
			
			#pre($res_afip);
			
			if(isset($res_afip->FECompConsultarResult->ResultGet) && $res_afip->FECompConsultarResult->ResultGet){

				$cae = $res_afip->FECompConsultarResult->ResultGet->CodAutorizacion;
				$resultget = json_encode($res_afip->FECompConsultarResult->ResultGet);
				
				
				$existe = $this->db->query("SELECT * FROM bv_facturas_afip WHERE cae = '".$cae."'")->result();
				
				if(count($existe) == 0){
					$this->db->query("INSERT INTO bv_facturas_afip (cae,resultget) values ('".$cae."','".$resultget."')");
					echo "insert<br>";
				}
				else {
					$this->db->query("UPDATE bv_facturas_afip SET cae = '".$cae."', resultget = '".$resultget."' WHERE id = ".$existe[0]->id);
					echo "update<br>";
				}
				
				//update marca en FACTURAS
				if($res->id > 0 && $res->tipo != '' && $res->sucursal_id != ''){
					$this->db->query("UPDATE bv_facturas SET datos_afip = 1 WHERE id = ".$res->id." AND tipo = '".$res->tipo."' AND sucursal_id = ".$res->sucursal_id);
				}
			}
			
			
		}
		
	}

	/* actualizo cupo disponible de paquetes con marca de cupo personalizado */
	function cupo_personalizado(){
		$results = $this->db->query("select * from bv_paquetes where activo = 1 and cupo_paquete_personalizado = 1")->result();
		
		foreach($results as $res){
			//si el cupo personalizado NO es cero
			if($res->cupo_paquete_disponible > 0){
				
				//por cada paquete obtengo la cantidad de reservas actuales
				$row = $this->db->query("select paquete_id, sum(pasajeros) as reservas from bv_reservas where paquete_id = ".$res->id." and estado_id in (1,2,4,14) group by paquete_id")->row();

				if(isset($row->paquete_id) && $row->paquete_id){
					//$cupo_disponible = $res->cupo_paquete_total-$row->reservas;
					//el cupo se calcula en base en el nuevo campo
					//$cupo_disponible = $res->cupo_paquete_disponible-$row->reservas;
					//el personalizado "real" es sobre el personalizado "total" 
					$cupo_disponible = $res->cupo_paquete_total-$row->reservas;
					$cupo_disponible = $cupo_disponible>0 ? $cupo_disponible : 0;

					//$this->db->query("UPDATE bv_paquetes SET cupo_paquete_disponible = ".$cupo_disponible." WHERE id = ".$res->id);
					$this->db->query("UPDATE bv_paquetes SET cupo_paquete_disponible_real = ".$cupo_disponible." WHERE id = ".$res->id);
					
					//el cupo disponible nunca puede ser mayor que el real
					if( $res->cupo_paquete_disponible > $cupo_disponible ){
						$this->db->query("UPDATE bv_paquetes SET cupo_paquete_disponible = ".$cupo_disponible." WHERE id = ".$res->id);
					}
				}
			}
		}

	}

	function test_voucher($reserva_id) {
		$CI = $this;
		
		$CI->load->model('Reserva_model','Reserva');
		$CI->load->model('Combinacion_model','Combinacion');
		$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');
		$CI->load->model('Movimiento_model','Movimiento');
			
		$reserva = $CI->Reserva->get($reserva_id)->row();
		
		if(!isset($reserva->id) || !$reserva->id){	
			return true;
		}
			
		$combinacion = $CI->Combinacion->get($reserva->combinacion_id)->row();
			
			//responsable
			$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 1 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
			$responsable = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->row();
					
			//acompañantes
			$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 0 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
			$acompanantes = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();
			
			$incompletos = 0;
			foreach($acompanantes as $a){
				if(!$a->completo)
					$incompletos+=1;
			}
			
			//adicionales
			$adicionales = $CI->Reserva->getAdicionales($reserva_id);
			$adicionales_valores = array();
			foreach($adicionales as $a){
				$adicionales_valores[$a->paquete_adicional_id] = $a->v_total;
			}
			
			$precios = calcular_precios_totales($combinacion,$adicionales_valores,$reserva->precio_usd?'USD':'ARS',$reserva->id);
			
			//le decremento el cupo al paquete
			$data = array();
			$data['reserva'] = $reserva;
			$data['combinacion'] = $combinacion;
			$data['responsable'] = $responsable;
			$data['acompanantes'] = $acompanantes;
			$data['incompletos'] = $incompletos;
			$data['precios'] = $precios;
			$data['ocultar_boton_pago'] = true;
			$html = cargar_mail('voucher',$data);
		
		$attachments=FALSE;
	
		$asunto = 'Tu voucher del viaje';
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	
	function alarmas() {
		$CI = $this;
		$CI->load->model('Reserva_model','Reserva');

		$reservas_paquetes = $CI->Reserva->getAllByPaquetesActivos('','','','','','',TRUE)->result();
		foreach ($reservas_paquetes as $res) {
			$alarmas = cargar_alarmas($res, TRUE);
			
			$data = array(
					'informes' => $alarmas->informes_de_pago,
					'completar_datos_pax' => $alarmas->completar_datos_pax,
					'alerta_no_llamar' => $alarmas->alerta_no_llamar,
					'alerta_llamar_pax' => $alarmas->alerta_llamar_pax,
					'alerta_reestablecida' => $alarmas->alerta_reestablecida,
					'alerta_contestador' => $alarmas->alerta_contestador,
					'falta_factura_proveedor' => $alarmas->falta_factura_proveedor,
					'faltan_cargar_vouchers' => $alarmas->faltan_cargar_vouchers,
					'alerta_cupos_vencidos' => $alarmas->alerta_cupos_vencidos,
					'fecha_limite_pago_completo' => $alarmas->fecha_limite_pago_completo,
					'diferencias_rooming' => $alarmas->diferencias_rooming,
					'tiene_adicionales' => count($alarmas->tiene_adicionales) > 0 ? 1 : 0,
					'timestamp' => date('Y-m-d H:i:s')
			);
			
			$reserva = $this->db->get_where('bv_reservas_alarmas', array('reserva_id' => $res->id))->row();
			if ($reserva) {
				$this->db->where('reserva_id', $res->id);
				$this->db->update('bv_reservas_alarmas', $data);
			}
			else {
				$data['reserva_id'] = $res->id;
				$this->db->insert('bv_reservas_alarmas', $data);
			}
		}		
	}

	/* Envio automatico de mailings generados desde el backend */
	function enviar_mailings(){
		$this->load->model('Enviomailing_model', 'Enviomailing');
	 	$envios = $this->Enviomailing->getAll(1,0,'ME.id','desc','',$con_restantes=true)->result();

	 	//por cada uno de los envios que tengo creados
	 	foreach($envios as $e){
	 		$cant_restantes = $e->inscriptos_paquete-$e->envios_hechos;

	 		//chequeo la cantidad de envios restantes que haya
	 		if($cant_restantes>0){
	 			$restantes = $this->Enviomailing->getMailsInscriptosRestantes($e->id)->result();

				if(count($restantes)){
					realizar_envio_mailing($e->id,$restantes);
				}
	 		}
	 	}
	 	
	}
	
	function ver(){
		$this->load->model('Enviomailing_model', 'Enviomailing');
	 	$envios = $this->Enviomailing->getAll(1,0,'ME.id','desc','',$con_restantes=true)->result();
		pre($envios);
	}

	function verreserva(){
		
		enviar_datos_reserva(2095,true,true);
		enviar_reserva_anulada_manualmente(2095,true);
		
	}

	function actualizar_tabla_facturas() {
		$this->db->query(
			'ALTER TABLE bv_facturas
			ADD (idComprobanteAfip int,
			informandoAfip ENUM("informando", "informada"))'
		);

		$this->db->query(
		'UPDATE bv_facturas
		SET idComprobanteAfip = id
		WHERE cae != "" ');
	}

	
}
