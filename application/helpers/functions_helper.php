<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//devuelve true o false si para dicha reserva y moneda de pago aplica o no el impuesto PAIS
function aplica_impuesto_pais($reserva,$moneda_pago){
	$CI =& get_instance();

	$CI->load->model('Config_model');
	$conf = $CI->Config_model->get(1)->row();
	$alcance = $conf->alcance_impuesto_pais=='ambas' ? ['ars','usd'] : [$conf->alcance_impuesto_pais];

	//si la fecha de reserva esta dentro del periodo de vigencia de la ley
	//y si el tipo de pago (usd o ars) tambien esta dentro del alcance de lo establecido dentro de la ley
	if( $CI->config->item('vigencia_impuesto_pais') <= $reserva->fecha_reserva &&
		in_array($moneda_pago,$alcance) ){
		return true;
	}
	else{
		return false;
	}
}

function hay_resize($obj, $section, $device){
	$CI =& get_instance();
	$resizes = $CI->config->item('resizes_'.$section);

	$path = false;

	//chequeo si existe el resize para el tipo de device solicitado
	foreach ($resizes as $key => $size) {
		if($key == $device){
			$filename = $obj->imagen;
			$filename = explode('.',$filename);
			$filename = $filename[0].'_'.$size['width'].'x'.$size['height'].'.'.$filename[1];
			if ($filename && file_exists('./uploads/'.$section.'/'.$obj->id.'/'.$filename)) {
				$path = base_url().'uploads/'.$section.'/'.$obj->id.'/'.$filename;
			}
		}
	}

	return $path;

}

function nombres_alarmas($alarmas){
	$str = "";
	if($alarmas->completar_datos_pax){
		$str .= "Faltan completar datos de pasajeros<br style='mso-data-placement:same-cell;' />";
	}
	if($alarmas->informes){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "Hay informes de pago sin verificar";
	}
	if($alarmas->fecha_limite_pago_completo){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "Hay pagos pendientes";
	}
	if($alarmas->falta_factura_proveedor){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "Falta factura del Proveedor";
	}
	if($alarmas->faltan_cargar_vouchers){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "Hay vouchers sin cargar";
	}
	if($alarmas->alerta_llamar_pax){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "Llamar al pasajero";
	}
	if($alarmas->alerta_reestablecida){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "Restablecida / Extendida";
	}
	if($alarmas->alerta_contestador){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "Contestador / Rellamar";
	}
	if($alarmas->alerta_cupos_vencidos){
		$str .= ( $str!='' ? "<br style='mso-data-placement:same-cell;' />" : "" ) . "La reserva tiene cupos de transporte vencidos";
	}

	return $str;
}

/*Recalculo el valor del viaje en base a la moneda del viaje y el medio de pago*/
function recalcular_precios($precios,$moneda_combinacion,$user_mediopago){

	$num = array();


	$CI =& get_instance();

	//Si el tipo va a pagar con PAYPAL y el valor del viaje es ARS, entonces divido por la cotizacion para saber cuantps USD con esos ARS que va a pagar.
	if(
		($moneda_combinacion == 'ARS' && $user_mediopago == 'PP')
		){
		//multiplico por la cotizacion
		$precios['precio_final_persona'] = $precios['precio_final_persona']/$CI->settings->cotizacion_dolar;
		$precios['precio_bruto_persona'] = $precios['precio_bruto_persona']/$CI->settings->cotizacion_dolar;
		$precios['precio_impuestos_persona'] = $precios['precio_impuestos_persona']/$CI->settings->cotizacion_dolar;
		$precios['precio_bruto'] = $precios['precio_bruto']/$CI->settings->cotizacion_dolar;
		$precios['precio_impuestos'] = $precios['precio_impuestos']/$CI->settings->cotizacion_dolar;
		$precios['precio_total'] = $precios['precio_total']/$CI->settings->cotizacion_dolar;
		$precios['monto_minimo_reserva'] = $precios['monto_minimo_reserva']/$CI->settings->cotizacion_dolar;
		$precios['monto_minimo_reserva_persona'] = $precios['monto_minimo_reserva_persona']/$CI->settings->cotizacion_dolar;
		$precios['monto_abonado'] = $precios['monto_abonado']/$CI->settings->cotizacion_dolar;
		$precios['saldo_pendiente'] = $precios['saldo_pendiente']/$CI->settings->cotizacion_dolar;
	}
	else if(
			($moneda_combinacion == 'USD' && $user_mediopago == 'MP')
		){
		//si el tipo va a pagar con MERCADOPAGO y el viaje es en USD, lo multiplico por la cotizacion para saber cuantos ARS son esos USD del viaje
		$precios['precio_final_persona'] = $precios['precio_final_persona']*$CI->settings->cotizacion_dolar;
		$precios['precio_bruto_persona'] = $precios['precio_bruto_persona']*$CI->settings->cotizacion_dolar;
		$precios['precio_impuestos_persona'] = $precios['precio_impuestos_persona']*$CI->settings->cotizacion_dolar;
		$precios['precio_bruto'] = $precios['precio_bruto']*$CI->settings->cotizacion_dolar;
		$precios['precio_impuestos'] = $precios['precio_impuestos']*$CI->settings->cotizacion_dolar;
		$precios['precio_total'] = $precios['precio_total']*$CI->settings->cotizacion_dolar;
		$precios['monto_minimo_reserva'] = $precios['monto_minimo_reserva']*$CI->settings->cotizacion_dolar;
		$precios['monto_minimo_reserva_persona'] = $precios['monto_minimo_reserva_persona']*$CI->settings->cotizacion_dolar;
		$precios['monto_abonado'] = $precios['monto_abonado']*$CI->settings->cotizacion_dolar;
		$precios['saldo_pendiente'] = $precios['saldo_pendiente']*$CI->settings->cotizacion_dolar;
	}

	$precios['precio_final_persona'] = number_format($precios['precio_final_persona'],2,'.','');
	$precios['precio_bruto_persona'] = number_format($precios['precio_bruto_persona'],2,'.','');
	$precios['precio_impuestos_persona'] = number_format($precios['precio_impuestos_persona'],2,'.','');
	$precios['precio_bruto'] = number_format($precios['precio_bruto'],2,'.','');
	$precios['precio_impuestos'] = number_format($precios['precio_impuestos'],2,'.','');
	$precios['precio_total'] = number_format($precios['precio_total'],2,'.','');
	$precios['monto_minimo_reserva'] = number_format($precios['monto_minimo_reserva'],2,'.','');
	$precios['monto_minimo_reserva_persona'] = number_format($precios['monto_minimo_reserva_persona'],2,'.','');
	$precios['monto_abonado'] = number_format($precios['monto_abonado'],2,'.','');
	$precios['saldo_pendiente'] = number_format($precios['saldo_pendiente'],2,'.','');

	// pre($precios);

	return $precios;
}

/*
	Envia UN MAIL POR DIA a cada reserva en estado LISTA DE ESPERA del mismo viaje ($paquete_id) debido a que se acaba de liberar un cupo
*/
function enviar_lista_espera($reserva_id=false){
	$CI =& get_instance();

	if($reserva_id){
		$CI->load->model("Paquete_model","Paquete");
		$CI->load->model("Reserva_model","Reserva");
		$CI->load->model("Reserva_comentario_model","Reserva_comentario");

		$reserva = $CI->Reserva->get($reserva_id)->row();
		$paquete = $CI->Paquete->get($reserva->paquete_id)->row();

		#$paquete->fecha_inicio = '2018-10-19';
		//si es un paquete activo y todavia no iniciado
		if($paquete->activo && $paquete->fecha_inicio >= date('Y-m-d')){
			//obtengo sus reservas en lista de espera
			$reservas = $CI->Reserva->get_lista_de_espera($reserva->paquete_id);

			foreach ($reservas as $r) {
				//chequeo si para este día ya hay un registro en la tabla de comentarios
				//22: es el tipo_id de cupo_liberado
				$where = [  'reserva_id'=>$r->id,
							'tipo_id'=>22,
							'YEAR(fecha)'=> date('Y'),
							'MONTH(fecha)'=> date('m'),
							'DAY(fecha)'=> date('d')
						];

				$rows = $CI->Reserva_comentario->getWhere($where)->result();

				//si todavia para el dia de hoy no tiene ninguno, le genero el registro en historial para enviar el mail de que se contacte con la oficina porque se liberó cupo del viaje
				if(count($rows) == 0){
					$msg = 'Cupo liberado del viaje Cód. '.$paquete->codigo.' por anulación de reserva Cód. '.$reserva->code;
					registrar_comentario_reserva($r->id, 7, 'cupo_liberado', $msg, $mail=true, $template='cupo_liberado', $ref_id=false);
				}
			}
		}
	}


}

function vendedor_asociado(){
	$CI =& get_instance();

	//si es un ADMIN de tipo VENDEDOR, obtengo el vendedor externo asociado, y se lo pongo a la orden
	$admin_id = userloggedId();
	if($admin_id){

		if(perfil()=='VEN'){
			//es un ADMIN con vendedor_id asociado
			$CI->load->model("Admin_model","Admin");
			$ad = $CI->Admin->get($admin_id)->row();
			return isset($ad->vendedor_id) && $ad->vendedor_id ? $ad->vendedor_id : 0;
		}
		else{
			//es un VENDEDOR externo
			return $admin_id;
		}
	}
	else{
		return false;
	}
}

/* este metodo se usa tanto en este helper al reasignar habitacion compartida, como desde eadmin/Reservas al cambiar la combinacion del paquete de forma manual */
function elegir_combinacion($data_post=false,$return=false){
	if($data_post && count($data_post)){
		extract($data_post);
	}
	else{
		extract($_POST);
	}

	$CI =& get_instance();
	$CI->load->model('Reserva_model', 'Reserva');
	$CI->load->model('Combinacion_model', 'Combinacion');
	$CI->load->model('Paquete_model', 'Paquete');
	$CI->load->model('Movimiento_model', 'Movimiento');
	$CI->load->model('Paquete_rooming_model', 'Paquete_rooming');
	$CI->load->model('Fecha_alojamiento_cupo_model', 'Fecha_alojamiento_cupo');

	if($reserva_id > 0 && $combinacion_id > 0){

		//datos de reserva actual
		$reserva = $CI->Reserva->get($reserva_id)->row();

		//si la combinacion de la reserva es la misma que viene por post, es solo cambio de habitacion, no cambio de combinacion
		$cambia_la_combinacion = ($reserva->combinacion_id != $combinacion_id) ? true : false;

		//armo array con reservas a cambiarle la caombinacion
		$reservas_ids = [];

		//12-12-18 si la reserva actual está recientemente anulada, no la voy a considerar para reasignarla
		if($reserva->estado_id != 5){
			$reservas_ids[] = $reserva_id; //reserva original
		}

		//si la reserva es de un grupo, a todas las reservas no ANULADAS de dicho grupo les cambio la combinacion
		if($reserva->codigo_grupo){
			$filtros['reserva_grupal'] = true;
			//obtengo los ids de las reservas del grupo
			$results = $CI->model->getReservasActivasGrupo($reserva->codigo_grupo);
			foreach ($results as $r) {
				//12-12-18 las reservas ya anuladas no las considero ya que puede pasar que acabo de anular una reserva del grupo y a esa no la tengo que reasignar otra combinacion.
				if($r->estado_id != 5){
					$reservas_ids[] = $r->reserva_id;
				}
			}
		}

		$reservas_ids = array_unique($reservas_ids);

		$combinacion = $CI->Combinacion->get($combinacion_id)->row();
		$paquete = $CI->Paquete->get($combinacion->paquete_id)->row();

		//este numero de habitacion se setea con la primer reserva, y luego los demas del grupo toman la misma
		//tambien este valor puede venir por post ($nro_hab)
		$nhab = (isset($nro_hab) && $nro_hab) ? $nro_hab : 0;

		foreach ($reservas_ids as $rid) {
			$reserva = $CI->Reserva->get($rid)->row();

			//nuevos precios a actualizar en registro de reserva
			$precio = precio_bruto($combinacion,$numeric=true,$reserva->pasajeros);
			$impuestos = precio_impuestos($combinacion,$numeric=true,$reserva->pasajeros);

			if($cambia_la_combinacion){

				$upd = array();
				$upd['combinacion_id'] = $combinacion->id;
				$upd['fecha_alojamiento_id'] = $combinacion->fecha_alojamiento_id;
				$upd['transporte_fecha_id'] = $combinacion->fecha_transporte_id;
				$upd['lugar_id'] = $combinacion->lugar_id;
				$upd['alojamiento_id'] = $combinacion->alojamiento_id;
				$upd['habitacion_id'] = $combinacion->habitacion_id;
				$upd['paquete_regimen_id'] = $combinacion->paquete_regimen_id;
				$upd['paquete_precio'] = $precio;
				$upd['impuestos'] = $impuestos;
				$nueva_reserva_id = $reserva->id;
				$CI->Reserva->update($nueva_reserva_id,$upd);

				//18-09-18 el cambio de combinacion implica un movimiento en el haber por el monto de la anterior combinacion, y un movimient oen eldebe por el nuevo valor.
				$monto_anterior = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
				$monto_nuevo = $combinacion->v_total*$reserva->pasajeros+$reserva->adicionales_precio;
				$haber = $monto_anterior;
				$debe = $monto_nuevo;

				//data de reserva
				$r = $CI->Reserva->get($nueva_reserva_id)->row();

				$habitacion_anterior = $reserva->habitacion;
				$habitacion_nueva = $r->habitacion;

				//14-06
				//genero registro en el historial para que luego se le envie mail de cambios en reserva
				$mail = true;
				$template = 'cambios_reserva';
				registrar_comentario_reserva($r->id,7,'envio_mail','Envio de email de cambios en la reserva por nueva combinacion elegida. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo,$mail,$template);

				//genero movimiento en cta cte de USUARIO por diferencia en el adicional
				$mov = $CI->Movimiento->getLastMovimientoByTipoUsuario($r->usuario_id,"U",$r->precio_usd)->row();

				//18-09-18 MOVIMIENTO EN EL HABER POR ANULACION DE COMBINACION ANTERIOR
				$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $haber;

				$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
				$moneda = $r->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $CI->settings->cotizacion_dolar;

				registrar_movimiento_cta_cte($r->usuario_id,"U",$r->id, date('Y-m-d H:i:s'), 'ANULACION DE COMBINACION - '.$r->nombre." - ".$r->paquete_codigo.' | '.$habitacion_anterior,0.00,$haber,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				//registro en historial el movimiento por ajuste de precio
				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario por ANULACION DE COMBINACION. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo.' | '.$habitacion_anterior.' | HABER '.$haber);

				//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
				$mov2 = $CI->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$r->precio_usd)->row();
				$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $haber;
				registrar_movimiento_cta_cte(1,"A",$r->id, date('Y-m-d H:i:s'), $r->nombre." - ".$r->paquete_codigo,0.00,$haber,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS por ANULACION DE COMBINACION. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo.' | '.$habitacion_anterior.' | HABER '.$haber);
				//-------------------------------------------------------------------------------------------------------------------


				//18-09-18 MOVIMIENTO EN EL DEBE POR NUEVA DE COMBINACION ELEGIDA
				$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $debe;

				$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
				$moneda = $r->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $CI->settings->cotizacion_dolar;

				registrar_movimiento_cta_cte($r->usuario_id,"U",$r->id, date('Y-m-d H:i:s'), 'NUEVA COMBINACION ELEGIDA - '.$r->nombre." - ".$r->paquete_codigo.' | '.$habitacion_nueva,$debe,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				//registro en historial el movimiento por ajuste de precio
				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario por NUEVA COMBINACION ELEGIDA. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo.' | '.$habitacion_nueva.' | DEBE '.$debe);

				//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
				$mov2 = $CI->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$r->precio_usd)->row();
				$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $debe;
				registrar_movimiento_cta_cte(1,"A",$r->id, date('Y-m-d H:i:s'), $r->nombre." - ".$r->paquete_codigo,$debe,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				registrar_comentario_reserva($r->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS por NUEVA COMBINACION ELEGIDA. CONCEPTO: '.$r->nombre." - ".$r->paquete_codigo.' | '.$habitacion_nueva.' | DEBE '.$debe);
				//-------------------------------------------------------------------------------------------------------------------
			}
			else{
				//data de reserva
				$r = $reserva;
			}

			//EN ROOMING: a cada reserva tambien tengo que asignarla a la misma habitacion que las demas reservas asi estan juntos

			$room = array();
			$room['paquete_id'] = $r->paquete_id;
			$room['reserva_id'] = $r->id;

			//borro la referencia actual de dicha reserva en rooming
			$CI->Paquete_rooming->deleteWhere($room);

			$room['alojamiento_fecha_cupo_id'] = $r->fecha_alojamiento_cupo_id;


			//con la primera reserva entra acá y luego las demás del grupo toman este valor
			if(!$nhab){
				//para definir el numero de habitacion a asignar, obtengo el primero las usadas
				$filt = [];
				$filt['paquete_id'] = $r->paquete_id;
				//Maxi 27/01/2020, la linea de abajo estaba comentada
				$filt['alojamiento_fecha_cupo_id'] = $r->fecha_alojamiento_cupo_id;

				$nhabs = $CI->Paquete_rooming->getWhere($filt)->result();
				$usadas = [];
				foreach ($nhabs as $n) {
					$usadas[] = $n->nro_habitacion;
				}

				//get data de cupo de habitacion
				$datahab = $CI->Fecha_alojamiento_cupo->get($r->fecha_alojamiento_cupo_id)->row();

				for ($i=1; $i <= $datahab->cantidad; $i++) {
					if(!in_array($i,$usadas)){
						//si el numero de habitacion i no está en las usadas, uso este
						$nhab = $i;
						break;
					}
				}
			}

			$room['nro_habitacion'] = $nhab;
			$room['observaciones'] = '';

			//guardo rooming nuevo
			$CI->Paquete_rooming->insert($room);
		}

		$ret['status'] = 'success';
		//$ret['msg'] = 'El cambio de paquete se ha efectuado correctamente<br>Por favor revisa la <a target="_blank" href="'.base_url().'admin/reservas/paquete/'.$paquete->id.'">nueva reserva</a> generada, junto con la <a href="'.base_url().'admin/reservas/edit/'.$nueva_reserva_id.'?tab=cta_cte" target="_blank">cuenta corriente</a> del pasajero.';
		$ret['msg'] = 'El cambio de paquete se ha efectuado correctamente.';

		//23-08-18 llamo al metodo que actualiza manualmente los cupos luego de cambiar de combinacion de paquete
		actualizar_cupos();

		if($return){
			return true;
		}
		else{
			echo json_encode($ret);
		}
	}
	else{
		$ret['status'] = 'error';
		$ret['msg'] = 'Hubo un problema. Intente mas tarde.';

		echo json_encode($ret);
		exit();
	}

}

/* metodo que obtiene todas las reservas del mismo grupo y las reasigna a habitacion compartida */
function asignar_habitacion_compartida($res_id=false){
	if(!$res_id){
		return false;
	}

	$CI =& get_instance();
	$CI->load->model('Reserva_model', 'Reserva');
	$CI->load->model('Combinacion_model', 'Combinacion');

	$reserva = $CI->Reserva->get($res_id)->row();

	$reservas_grupo = [];
	if(isset($reserva->codigo_grupo) && $reserva->codigo_grupo){
		//si pertenece a una reserva de grupo, obtengo las reservas del mismo para asiganrle la compartida
		$ids = $CI->db->query("select reserva_id from bv_reservas_grupos where codigo_grupo = '".$reserva->codigo_grupo."'")->result();

		foreach($ids as $res){
			$reservas_grupo[] = $res->reserva_id;
		}
	}

	//si la reserva tiene reservas asociadas
	if(count($reservas_grupo)){
		//me fijo si hay combinacion de habitacion compartida para reasigarle esa a todas las reservas del grupo
		$filtros = array();
		$filtros['pax'] = $reserva->pasajeros;
		$filtros['habitacion_id'] = 99;
		$filtros['not_combinacion_id'] = $reserva->combinacion_id;//que no traiga la combinacion actual

		$combinaciones = $CI->Combinacion->getByPaquete($reserva->paquete_id,9999,$filtros);

		//con las combinaciones del pauqete me fijo las que me alcance el cupo de alojamiento y transporte segun la cantidad de pax
		//si hay alguna combinacion donde el cupo de transporte o alojamiento no me alcance, no la considero
		$final = array();
		foreach($combinaciones as $c){
			if($c->cupo_aloj >= $reserva->pasajeros && $c->cupo_trans >= $reserva->pasajeros){
					$final[] = $c;
			}
		}

		if(isset($final[0]) && $final[0]){
			$comb = $final[0];

			//tengo la combinacion de habitacion compartida, se la asigno a todas las reservas del grupo
			foreach ($reservas_grupo as $rid) {
				//reuso este metodo que es el que se usa desde el form de reserva para hacer el cambio de combinacion
				$data_post = [];
				$data_post['reserva_id'] = $rid;
				$data_post['combinacion_id'] = $comb->id;

				elegir_combinacion($data_post,$return=true);
			}
		}
		else{
			//no hay combinacion compartida
			return false;
		}


	}

}

/*
funcion que verifica si corrsponde aplicar penalidad y devuelve los valores a aplicar
*/
function verificar_penalidad($paquete,$reserva,$fecha_baja){
	$CI =& get_instance();
    $CI->load->model('Combinacion_model', 'Combinacion');

    $CI->data['penalidad_paquete'] = false;
    /*
    50% sobre el precio total del viaje (precio más impuestos) si la anulación se realiza hasta la fecha en que se deba realizar el pago total del viaje.
	100% sobre el precio total del viaje en el caso de anulaciones que se produzcan con posterioridad a la fecha estipulada para el pago total del viaje. Dicha penalidad se aplicará cualquiera sea el motivo de la cancelación del viaje.
    */

	$aux = $fecha_baja;
	$aux = explode('/',$aux);
	$aux = $aux[2].'-'.$aux[1].'-'.$aux[0];
	$fecha_baja = $aux;
    $porcentaje_penalidad = ($fecha_baja <= $paquete->fecha_limite_pago_completo) ? 50 : 100;
	if($porcentaje_penalidad){

		$CI->data['combinacion'] = $CI->Combinacion->get($reserva->combinacion_id)->row();
		$reserva_adicionales = adicionales_reserva($reserva);
		$precios = calcular_precios_totales($CI->data['combinacion'],$CI->data['adicionales_valores'],@$CI->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);

		$CI->data['penalidad_paquete'] = $porcentaje_penalidad/100*$precios['num']['precio_total'];
		$CI->data['penalidad_tope'] = $CI->data['penalidad_paquete'] < $precios['num']['monto_abonado'] ? $CI->data['penalidad_paquete'] : $precios['num']['monto_abonado'];

		//estos valores ya estan formateados
		$CI->data['precio_total'] = $precios['precio_total'];
		$CI->data['monto_abonado'] = $precios['monto_abonado'];
		$CI->data['precios'] = $precios;
		$CI->data['porcentaje_penalidad'] = $porcentaje_penalidad;
	}
}

/*
funcion que realiza el envio del mailing que se recibe por parametro a los usuarios
que tambien llegan por 2° parametro
*/
function realizar_envio_mailing($envio_id,$restantes){
	$CI =& get_instance();
    $CI->load->model('Config_model');
    $CI->load->model('Enviomailing_model', 'Enviomailing');
    $CI->load->model("Mailing_model","Mailing");
    $CI->load->model("Enviomailinghecho_model","Enviomailinghecho");
	$envio = $CI->Enviomailing->get($envio_id)->row();

	//obtengo template del mailing a enviar
	$mailing = $CI->Mailing->get($envio->mail_id)->row();

	//genero lista de email separados por comas
	$mails = "";
	foreach($restantes as $row){
		//28-08-2014 -> solo envio mail al usuario si este lo admite
		//if(isset($row->enviar_mail) && $row->enviar_mail){
			$mails .= $row->email.", ";
		//}
	}

	if(strlen($mails)>=2){
		$mails[strlen($mails)-2] = " ";
		$mails[strlen($mails)-1] = " ";
	}

	//$from = 'maxi@id4you.com';
	$bcc='';

	//obtengo la data de configuracion para tomar el email configurado
	$config = $CI->Config_model->get(1)->row();
	if($config->email_mailings){
		$from = $config->email_mailings;
	}
	else{
		//si no está seteado, dejo por defecto este que estaba antes
		$from = 'reservas@buenas-vibras.com.ar';
	}

	$bcc=$mails;

	$data = array();
	$data['title'] = $mailing->formato;

    $mailing->contenido = html_entity_decode(base64_decode($mailing->contenido));

    $data['contenido'] = $mailing->contenido;

    $CI->load->model('Sucursal_model','Sucursal');
    $CI->Sucursal->filters = "id in (3)";
    $data['sucursales'] = $CI->Sucursal->getAll(1,0,'id','asc')->result();

    $o = new stdClass();
    $o->telefono = '(011) 5235-3810. Lunes a Viernes de 10 a 19 hs.';
    $data['orden'] = $o;

    //cargo footer de mail generico
    $data['mail_footer'] = $CI->load->view('mails/mail_footer',$data,true);

    $view = $CI->load->view('admin/mailings/mailings_base',$data,true);

	if(count($restantes)){
		if( enviarMail($from,$from,$bcc,$mailing->asunto,$view,'BUENAS VIBRAS VIAJES') ){
		//if( true ){
			foreach($restantes as $row){
				$data = array(
							"inscripto_id" => $row->usuario_id,
							"envio_id" => $envio_id,
							"fecha" => date('Y-m-d H:i:s'),
							"enviado" => 1
					);
				$CI->Enviomailinghecho->insert($data);
			}

			return "ok";
		}
		else{
			return "fallo";
		}
	}
}

function fecha_salida($fecha){
	//setlocale(LC_ALL,"es_ES");
	setlocale(LC_TIME, 'spanish');
	$fecha_n = strtotime($fecha);
	return strftime("%A %d de %B",$fecha_n);

	//Salida: viernes 24 de febrero
}

/*
Function que devuelve true/false si el perfil en sesion tiene permiso para ver/usar la alarma
*/
function perfil_alarma($tipo_alarma){
	$CI =& get_instance();
	$alarmas = get_alarmas();

	$rol = $CI->session->userdata('perfil');
	$puede = false;

	foreach ($alarmas as $a) {
		//me fijo si la alarma que viene por parametro está permitida para el tipo de perfil del usuario en sesion
		if($a['id'] == $tipo_alarma && in_array($rol,$a['perfiles'])){
			$puede = true;
			break;
		}
	}

	return $puede;
}

function get_alarmas(){
	return array(
                                array('id'=>'alerta_contestador','nombre' => 'Contestador / Rellamar','perfiles' => array('VEN','VENEXT','SUP')),
                                array('id'=>'falta_factura_proveedor','nombre' => 'Falta factura del Proveedor', 'perfiles' => array('ADM','SUP')),
                                array('id'=>'completar_datos_pax','nombre' => 'Faltan completar datos de pasajeros','perfiles' => array('OPE','SUP')),
                                array('id'=>'informes','nombre' => 'Hay informes de pago sin verificar','perfiles' => array('ADM','SUP')),
                                array('id'=>'faltan_cargar_vouchers','nombre' => 'Hay vouchers sin cargar','perfiles' => array('OPE','SUP')),
                                array('id'=>'fecha_limite_pago_completo','nombre' => 'Hay reservas con saldo pendiente','perfiles' => array('ADM','SUP')),
                                array('id'=>'alerta_cupos_vencidos','nombre' => 'La reserva tiene cupos de transporte vencidos','perfiles' => array('OPE','SUP')),
                                array('id'=>'alerta_llamar_pax','nombre' => 'Llamar al pasajero','perfiles' => array('VEN','VENEXT','SUP')),
                                array('id'=>'alerta_reestablecida','nombre' => 'Restablecida / Extendida','perfiles' => array('VEN','VENEXT','SUP')),
                            );
}

/*
Función que devuelve las alarmas permitidas para cada tipo de perfil
*/
function cargar_alarmas_perfil(){
	$CI =& get_instance();

	//listado total de alarmas con perfiles asociados
	$alarmas = get_alarmas();

	//en base al rol de sesion, devuelvo las alarmas asociadas a dicho perfil
	$rol = $CI->session->userdata('perfil');

	$return = [];
	foreach ($alarmas as $a) {
		if(in_array($rol,$a['perfiles'])){
			$return[] = $a;
		}
	}

	return $return;
}

/*
23-08-18
Esta funcion actualiza los cupos de transportes.
Se llama desde:
cron/disponibilidad_cupo (cuando corre el cron y se actualizan estados de reservas automaticamente)
admin/reservas/actualizarEstado (cuando cambia manualmente el estado de una reserva)
admin/reservas/confirmar_disponibilidad (cuando se confirma manualmente la disponiblilidad de una reserva)
admin/reservas/elegirCombinacion (cuando se cambia la combinacion de paquete de una reserva)
checkout/efectivizar_orden (cuando se efectiviza una orden en nueva reserva)
admin/alojamientos/agregar_habitacion (cuando se agrega nuevo cupo de alojamiento)
admin/alojamientos/update_habitacion (cuando se actualiza cupo de alojamiento)
admin/transportes/agregar_fecha (cuando se agrega nuevo cupo de transporte)
admin/transportes/update_fecha (cuando se actualiza cupo de transporte)
*/
function actualizar_cupos(){
	$CI =& get_instance();
	$cupos_transportes = [];

	//reseteo el cupo disponible en base al total de cada transporte
	$CI->db->query("update bv_transportes_fechas set cupo = cupo_total");
	//reseteo el cupo disponible en base al total de cada alojamiento
	$CI->db->query("update bv_alojamientos_fechas_cupos set cupo = cupo_total");

	//primero actualizo el cupo diponible de los transportes segun las reservas de cada uno hechas
	$res = $CI->db->query("select ifnull(t.cantidad,0) as reservados, tf.*
		from bv_transportes_fechas tf
		left join (select sum(r.pasajeros) as cantidad, r.transporte_fecha_id
					from bv_reservas r
					join bv_paquetes_combinaciones pc on pc.id = r.combinacion_id
				        join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
					where r.estado_id in (1,2,4,14) group by r.transporte_fecha_id) t on t.transporte_fecha_id = tf.id")->result();

	foreach($res as $r){
		$t_cupo = $r->cupo-$r->reservados;
		$t_cupo = $t_cupo > 0 ? $t_cupo : 0;
		//$t_cupo = $r->cupo_total-$r->reservados;
		$CI->db->query("UPDATE bv_transportes_fechas set cupo = ".$t_cupo." WHERE id = ".$r->id);
	}

	//idem para alojamientos
	$res = $CI->db->query("select ifnull(t.cantidad,0) as reservados, fc.*
		from bv_alojamientos_fechas_cupos fc
		left join (select sum(r.pasajeros) as cantidad, r.fecha_alojamiento_id
					from bv_reservas r
					join bv_paquetes_combinaciones pc on pc.id = r.combinacion_id
				        join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
					where r.estado_id in (1,2,4,14) group by r.fecha_alojamiento_id) t on t.fecha_alojamiento_id = fc.id
		where fc.habitacion_id != 99")->result();

	foreach($res as $r){
		$t_cupo = $r->cupo-$r->reservados;
		$t_cupo = $t_cupo > 0 ? $t_cupo : 0;
		//$t_cupo = $r->cupo_total-$r->reservados;
		$CI->db->query("UPDATE bv_alojamientos_fechas_cupos set cupo = ".$t_cupo." WHERE id = ".$r->id);
	}
	//-----------------------------------------------------------------------------

	//actualizo cupos del paquete segun sus combinaciones
	//obtengo las combinaciones agrupadas por ID de transporte_fecha y ID de alojamiento_fecha_cupo
//NO tomo en cuenta el registro de habitacion id 99 que es la COMPARTIDA
	$res = $CI->db->query("
				select  pc.paquete_id, group_concat(distinct tf.id) as tcid, group_concat(distinct fc.id) as acid
				from bv_paquetes_combinaciones pc
				join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
				join bv_alojamientos_fechas_cupos fc on fc.id = pc.fecha_alojamiento_cupo_id and fc.habitacion_id != 99
				join bv_transportes_fechas tf on tf.id = pc.fecha_transporte_id
				where pc.agotada = 0 group by pc.paquete_id")->result();

	//echo $CI->db->last_query();
	//echo "<br>";

	//ids de paquetes actualizados
	$ids_updated = array();

	foreach($res as $p){
		//por cada paquete
		$ct = $CI->db->query("
				select
					sum(tf.cupo) as t_disponible,
					sum(tf.cupo_total) as t_total
				from bv_paquetes p
				join bv_transportes_fechas tf on  tf.id in (".$p->tcid.")
				where p.activo = 1 and p.id = ".$p->paquete_id."
				group by p.id")->row();
	//echo $CI->db->last_query();
	//echo "<br>";

		$ca = $CI->db->query("
				select
					sum(fc.cupo) as a_disponible,
					sum(fc.cupo_total) as a_total
				from bv_paquetes p
				join bv_alojamientos_fechas_cupos fc on fc.id in (".$p->acid.")
				where p.activo = 1 and p.id = ".$p->paquete_id."
				group by p.id")->row();

	//echo $CI->db->last_query();
	//echo "<br>";



		//en el paquete actualizo el cupo disponible en base al menor cupo de alojamiento y transporte
		$p_cupo_dispo = $ca->a_disponible > $ct->t_disponible ? $ct->t_disponible : $ca->a_disponible;
		//el total que respete cuál es el menor de los disponibles
		$p_cupo_total = $ca->a_total > $ct->t_total ? $ct->t_total: $ca->a_total;

		if ($p_cupo_total) {
			$p_cupo_dispo = $p_cupo_dispo > 0 ? $p_cupo_dispo : 0;
			$CI->db->query("UPDATE bv_paquetes set cupo_total = ".$p_cupo_total.", cupo_disponible = ".$p_cupo_dispo." WHERE id = ".$p->paquete_id);

	//echo $CI->db->last_query();
	//echo "<br>";
			$ids_updated[] = $p->paquete_id;
		}

		//tambien obtengo por paquete la cantidad de reservas hechas de habitacion compartida
		//y actualizo el cupo disponible
		$q = $CI->db->query("select  pc.paquete_id,ifnull(sum(x.reservadas),0) as reservadas
								from bv_paquetes_combinaciones pc
								join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
								join (select r.combinacion_id, sum(r.pasajeros) as reservadas from bv_reservas r where r.estado_id IN (1,2,4,14) group by r.combinacion_id) x
									on x.combinacion_id = pc.id and pc.paquete_id = ".$p->paquete_id."
								where pc.habitacion_id = 99
								group by pc.paquete_id")->row();

	//echo $CI->db->last_query();
	//echo "<br>";

		$reservadas = isset($q->reservadas) && $q->reservadas ? $q->reservadas : 0;
		if ($reservadas) {
			$dispo = $p_cupo_dispo-$reservadas;
			$dispo = $dispo > 0 ? $dispo : 0;
			$CI->db->query("UPDATE bv_paquetes set cupo_disponible = ".$dispo." WHERE id = ".$p->paquete_id);

//	echo $CI->db->last_query();
	//echo "<br>";

			$ids_updated[] = $p->paquete_id;
		}
	}


	//reseteo el cupo disponible en base al total de cada transporte
	$CI->db->query("update bv_transportes_fechas set cupo = cupo_total");
	//reseteo el cupo disponible en base al total de cada alojamiento
	$CI->db->query("update bv_alojamientos_fechas_cupos set cupo = cupo_total");

	//obtengo reservas NUEVAS o CONFIRMADAS de paquetes activos
	$reservas = $CI->db->query("select pc.fecha_alojamiento_cupo_id, pc.fecha_transporte_id,
										r.pasajeros, r.paquete_id, r.estado_id
									from bv_reservas r
									join bv_paquetes_combinaciones pc on pc.id = r.combinacion_id
									join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
									join bv_alojamientos_fechas_cupos fc on fc.id = pc.fecha_alojamiento_cupo_id
									join bv_transportes_fechas tf on tf.id = pc.fecha_transporte_id
									where r.estado_id in (1,2,4,14)")->result();

	//echo $CI->db->last_query();
	//echo "<br>";

	foreach($reservas as $r){
		//obtengo data alojamiento de reserva
		$aloj = $CI->db->query("SELECT * FROM bv_alojamientos_fechas_cupos WHERE id = ".$r->fecha_alojamiento_cupo_id)->row();

	//echo $CI->db->last_query();
	//echo "<br>";

		//obtengo data transporte de reserva
		$tran = $CI->db->query("SELECT * FROM bv_transportes_fechas WHERE id = ".$r->fecha_transporte_id)->row();

	//echo $CI->db->last_query();
	//echo "<br>";

		$a_cupo = 0;
		$t_cupo = 0;

		if($r->estado_id == 5){
			//reserva anulada, devuelvo cupo de alojamiento y transporte
			// $a_cupo = $aloj->cupo_total+($r->pasajeros);
			// $t_cupo = $tran->cupo_total+($r->pasajeros);
		}
		else{
			//reserva confirmada, decremento cupo de alojamiento y transporte
			$a_cupo = $aloj->cupo-($r->pasajeros);
			$t_cupo = $tran->cupo-($r->pasajeros);
			$a_cupo = $a_cupo > 0 ? $a_cupo : 0;
			$t_cupo = $t_cupo > 0 ? $t_cupo : 0;
			/*$a_cupo = $aloj->cupo_total-($r->pasajeros);
			$t_cupo = $tran->cupo_total-($r->pasajeros);*/
		}

		//update nuevos cupos de alojamiento y transporte
		$CI->db->query("UPDATE bv_alojamientos_fechas_cupos set cupo = ".$a_cupo." WHERE id = ".$r->fecha_alojamiento_cupo_id);

	//echo $CI->db->last_query();
	//echo "<br>";

	$CI->db->query("UPDATE bv_transportes_fechas set cupo = ".$t_cupo." WHERE id = ".$r->fecha_transporte_id);

	//echo $CI->db->last_query();
	///echo "<br>";


		//actualizo cupos del paquete segun sus combinaciones
		//obtengo las combinaciones agrupadas por ID de transporte_fecha y ID de alojamiento_fecha_cupo
		//NO tomo en cuenta el registro de habitacion id 99 que es la COMPARTIDA
		/*
		$res = $CI->db->query("
					select  fc.habitacion_id, pc.paquete_id, group_concat(distinct tf.id) as tcid, group_concat(distinct fc.id) as acid
					from bv_paquetes_combinaciones pc
					join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
					join bv_alojamientos_fechas_cupos fc on fc.id = pc.fecha_alojamiento_cupo_id and fc.habitacion_id != 99
					join bv_transportes_fechas tf on tf.id = pc.fecha_transporte_id
					where pc.agotada = 0 and pc.paquete_id = ".$r->paquete_id." group by pc.paquete_id")->result();
		*/

		//17/07/18
		$res = $CI->db->query("
					select  fc.habitacion_id, pc.paquete_id, group_concat(distinct tf.id) as tcid, group_concat(distinct fc.id) as acid
					from bv_paquetes_combinaciones pc
					join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
					join bv_alojamientos_fechas_cupos fc on fc.id = pc.fecha_alojamiento_cupo_id
					join bv_transportes_fechas tf on tf.id = pc.fecha_transporte_id
					where pc.paquete_id = ".$r->paquete_id." group by pc.paquete_id")->result();

	//echo $CI->db->last_query();
	//echo "<br>";

		foreach($res as $p){
			//por cada paquete
			$ct = $CI->db->query("
					select
						p.id as paquete_id,
						tf.id as transporte_fecha_id,
						sum(tf.cupo) as t_disponible,
						sum(tf.cupo_total) as t_total
					from bv_paquetes p
					join bv_transportes_fechas tf on  tf.id in (".$p->tcid.")
					where p.activo = 1 and p.id = ".$p->paquete_id."
					group by 1, 2")->row();

	//echo $CI->db->last_query();
	//echo "<br>";
			//Completamos el array de cupos de transportes
			// ESTO ESTA TIRANDO ERROR DE QUE T_DISPONIBLE NO EXISTE
			/*
			if (isset($cupos_transportes[$ct->transporte_fecha_id])) {
				if ($p->t_disponible < $cupos_transportes[$ct->transporte_fecha_id]) {
					$cupos_transportes[$ct->transporte_fecha_id] = $ct->t_disponible;
				}
			}
			else {
				$cupos_transportes[$ct->transporte_fecha_id] = $ct->t_disponible;
			}
			*/

			if($p->habitacion_id == 99){
				//si es hab compartida, me baso solo en transporte, por eso pongo numero mas grande
				$ca = new stdClass();
				$ca->a_disponible = 9999;
				$ca->a_total = 9999;
			}
			else{
				$ca = $CI->db->query("
					select
						sum(fc.cupo) as a_disponible,
						sum(fc.cupo_total) as a_total
					from bv_paquetes p
					join bv_alojamientos_fechas_cupos fc on fc.id in (".$p->acid.")
					where p.activo = 1 and p.id = ".$p->paquete_id."
					group by p.id")->row();
			}


	//echo $CI->db->last_query();
	//echo "<br>";


			//en el paquete actualizo el cupo disponible en base al menor cupo de alojamiento y transporte
			$p_cupo_dispo = $ca->a_disponible > $ct->t_disponible ? $ct->t_disponible : $ca->a_disponible;
			//el total que respete cuál es el menor de los disponibles
			$p_cupo_total = $ca->a_total > $ct->t_total ? $ct->t_total: $ca->a_total;

			if ($p_cupo_total) {
				$p_cupo_dispo = $p_cupo_dispo > 0 ? $p_cupo_dispo : 0;
				$CI->db->query("UPDATE bv_paquetes set cupo_total = ".$p_cupo_total.", cupo_disponible = ".$p_cupo_dispo." WHERE id = ".$p->paquete_id);

	//echo $CI->db->last_query();
	//echo "<br>";

				$ids_updated[] = $p->paquete_id;
			}

			//tambien obtengo por paquete la cantidad de reservas hechas de habitacion compartida
			//y actualizo el cupo disponible
			$q = $CI->db->query("select  pc.paquete_id,ifnull(sum(x.reservadas),0) as reservadas
									from bv_paquetes_combinaciones pc
									join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
									join (select r.combinacion_id, sum(r.pasajeros) as reservadas from bv_reservas r where r.estado_id IN (1,2,4,14) group by r.combinacion_id) x
										on x.combinacion_id = pc.id and pc.paquete_id = ".$p->paquete_id."
									where pc.habitacion_id = 99
									group by pc.paquete_id")->row();

	//echo $CI->db->last_query();
	//echo "<br>";

			$reservadas = isset($q->reservadas) && $q->reservadas ? $q->reservadas : 0;
			if ($reservadas) {
				$dispo = $p_cupo_dispo-$reservadas;
				$dispo = $dispo > 0 ? $dispo : 0;
				$CI->db->query("UPDATE bv_paquetes set cupo_disponible = ".$dispo." WHERE id = ".$p->paquete_id);

	///echo $CI->db->last_query();
	//echo "<br>";

				$ids_updated[] = $p->paquete_id;
			}
		}
	}

	//actualizo el cupo disponible de cada paquete segun el cupo total recien actualizado, menos la cantidad de reservas hechas

	$res = $CI->db->query("select t1.cant as cantidad, p.*
		from bv_paquetes p
		left join (select R2.paquete_id, sum(R2.pasajeros) as cant from bv_reservas R2 join bv_paquetes_combinaciones c on c.id = R2.combinacion_id where R2.estado_id > 0 and R2.estado_id not in (5,12,13) group by R2.paquete_id) t1 on t1.paquete_id = p.id")->result();


	/*
	$res = $CI->db->query("select p.id, tr.id as p_id, p.cupo_disponible, p.cupo_total, ifnull(t1.cant,0) as cantidad , ifnull(tr.cant,0) as cant_r
			from bv_paquetes p
			left join (select R2.paquete_id, sum(R2.pasajeros) as cant from bv_reservas R2 join bv_paquetes_combinaciones c on c.id = R2.combinacion_id where R2.estado_id > 0 and R2.estado_id not in (5,12,13) group by R2.paquete_id) t1 on t1.paquete_id = p.id
			left join (
						select tx.id, sum(tx.cant) as cant
						from
							(select P.id, R2.pasajeros AS cant
							   from bv_paquetes P
							   join bv_paquetes_combinaciones c on c.paquete_id = P.id
							   join bv_reservas R2 on R2.transporte_fecha_id = c.fecha_transporte_id
							   where R2.estado_id > 0 and R2.estado_id not in (5,12,13)
							   group by P.id, R2.id
							) tx
						group by tx.id
					  ) tr on tr.id = p.id")->result();
	*/

	//CUPO TOTAL DE ALOJAMIENTO
	$paquetes_cupos_alojamiento = $CI->db->query("	select paquete_id, sum(cupo_total_alojamiento) as cupo_total_alojamiento
						from
						(
							select distinct comb.paquete_id, comb.fecha_alojamiento_cupo_id, alo.cupo_total as cupo_total_alojamiento
							from bv_paquetes_combinaciones comb
							inner join bv_alojamientos_fechas_cupos alo on alo.id = comb.fecha_alojamiento_cupo_id
							where alo.habitacion_id <> 99
						) t
						group by paquete_id
					")->result();

	//CUPO TOTAL DE TRANSPORTE
	$paquetes_cupos_transporte = $CI->db->query("	select paquete_id, sum(cupo_total_transporte) as cupo_total_transporte
						from
						(
							select distinct comb.paquete_id, comb.fecha_transporte_id, trans.cupo_total as cupo_total_transporte
							from bv_paquetes_combinaciones comb
							inner join bv_transportes_fechas trans on trans.id = comb.fecha_transporte_id
							where comb.paquete_id
						) t
						group by paquete_id
					")->result();

	$arrPaquetes = [];
	foreach ($paquetes_cupos_alojamiento as $p) {
		$arrPaquetes[$p->paquete_id] = $p->cupo_total_alojamiento;
	}
	foreach ($paquetes_cupos_transporte as $p) {
		if (isset($arrPaquetes[$p->paquete_id])) {
			if ($p->cupo_total_transporte < $arrPaquetes[$p->paquete_id]) {
				$arrPaquetes[$p->paquete_id] = $p->cupo_total_transporte;
			}
		}
		else {
			$arrPaquetes[$p->paquete_id] = $p->cupo_total_transporte;
		}
	}

	//ACTUALIZA CUPO TOTAL DE LOS PAQUETES QUE NO TENGAN CUPO PERSONALIZADO
	foreach ($arrPaquetes as $paquete_id=>$cupo) {
		$CI->db->query("update bv_paquetes set cupo_total = ".$cupo.", cupo_disponible = ".$cupo.", cupo_paquete_disponible_real = ".$cupo." where id = ".$paquete_id);
	}

	//OBTIENE PASAJEROS POR PAQUETE, EN FUNCION AL USO DE FECHA-TRANSPORTE y FECHA-ALOJAMIENTO
	$pasajeros = $CI->db->query("	select distinct c.paquete_id, t.pasajeros
									from
									bv_paquetes_combinaciones c,
									(
									select sum(r.pasajeros) as pasajeros, c.fecha_transporte_id, c.fecha_alojamiento_cupo_id
									from bv_reservas r
									inner join bv_paquetes_combinaciones c on c.id = r.combinacion_id
									where r.estado_id not in (5, 12, 13) and r.paquete_id
									group by c.fecha_transporte_id, c.fecha_alojamiento_cupo_id
									) t
									where c.fecha_transporte_id = t.fecha_transporte_id and c.fecha_alojamiento_cupo_id = t.fecha_alojamiento_cupo_id
								")->result();

	foreach ($pasajeros as $pax) {
		$arrPaquetes[$pax->paquete_id] -= $pax->pasajeros;
	}

	//ACTUALIZA CUPOS DE LOS PAQUETES QUE NO TENGAN CUPO PERSONALIZADO
	foreach ($arrPaquetes as $paquete_id=>$cupo) {
		//$CI->db->query("update bv_paquetes set cupo_disponible = ".$cupo.", cupo_paquete_disponible_real = ".$cupo." where id = ".$paquete_id);
	}


	$reservas_transportes = $CI->db->query("	select c.fecha_transporte_id, sum(r.pasajeros) as pasajeros
												from bv_reservas r, bv_paquetes_combinaciones c
												where r.combinacion_id = c.id and r.estado_id not in (5,12,13)
												group by c.fecha_transporte_id
											")->result();

	/*
	$reservas_alojamientos = $CI->db->query("	select c.fecha_alojamiento_cupo_id, sum(r.pasajeros) as pasajeros
												from bv_reservas r, bv_paquetes_combinaciones c
												where r.combinacion_id = c.id and r.estado_id not in (5,12,13)
												group by c.fecha_transporte_id
											")->result();
	*/

	foreach ($reservas_transportes as $trans) {
		$CI->db->query("UPDATE bv_paquetes SET cupo_disponible = cupo_total - ".$trans->pasajeros.", cupo_paquete_disponible_real = cupo_total - ".$trans->pasajeros." WHERE cupo_paquete_personalizado=0 AND id IN (SELECT DISTINCT paquete_id FROM bv_paquetes_combinaciones WHERE fecha_transporte_id = ".$trans->fecha_transporte_id.")");

		$CI->db->query("UPDATE bv_paquetes SET cupo_paquete_disponible_real = cupo_total - ".$trans->pasajeros." WHERE cupo_paquete_personalizado=1 AND id IN (SELECT DISTINCT paquete_id FROM bv_paquetes_combinaciones WHERE fecha_transporte_id = ".$trans->fecha_transporte_id.")");

	}

	//el cupo disponible nunca puede ser mayor que el real, para los de cupo personalizado
	$results = $CI->db->query("select * from bv_paquetes where activo = 1 and cupo_paquete_personalizado = 1")->result();
	foreach($results as $res){
		if($res->cupo_paquete_disponible > $res->cupo_paquete_disponible_real){
			$cupo_upd = $res->cupo_paquete_disponible_real > 0 ? $res->cupo_paquete_disponible_real : 0;
			$CI->db->query("UPDATE bv_paquetes SET cupo_paquete_disponible = ".$cupo_upd." WHERE id = ".$res->id);
		}
	}
}


/* devuelve la fecha para el buscador */
function fecha_buscador($fecha){
	$ano = date('Y',strtotime($fecha));
	$mes = date('m',strtotime($fecha));
	$mes = monthName($mes);

	return $mes.' '.$ano;
}

//devuelve el precio redondeado hacia arriba
//AR$ 120.000 (ej)
function precio_redondeado($precio,$precio_usd){
	$precio = number_format($precio,2,'.','');

	$precio = explode('.',$precio);

	//si tiene decimales, le sumo 1 al valor entero
	if($precio[1]>0){
		$precio[0] +=1;
	}

	$precio[0] = number_format($precio[0],0,'','.');

	return ($precio_usd?'USD':'ARS')." ".$precio[0];
}


function limit_text($text,$limit){
	if(strlen($text) <= $limit){
		return $text;
	}
	else{
		return substr($text,0,$limit-3).'...';
	}
}

function rol($codigo) {
	switch ($codigo) {
		case 'VEN': return 'Vendedor'; break;
		case 'LID': return 'Lider'; break;
		case 'GER': return 'Gerente'; break;
	}
}

function perfil() {
	$CI =& get_instance();
	return $CI->session->userdata('perfil');
}

function granted($modulo, $section) {
	switch (perfil()) {
		case 'SUP':
			switch ($modulo) {
				case 'reservas_vendedor':
					return FALSE;
				break;
				default:
					return TRUE;
			}
			break;
		case 'ADM':
			switch ($modulo.'-'.$section) {
				case 'panel-panel':
				case 'home-home':
				case 'seguridad-seguridad':
				case 'config-admins':
				case 'config-config':
				case 'config-destacados':
				case 'config-viajes_grupales':
				case 'config-conceptos':
				case 'paquetes-paquetes':
				case 'reservas-reservas':
				case 'reservas-alarmas':
				case 'reservas-operadores':
				case 'reservas-usuarios':
				case 'ordenes':
				case 'vendedores-vendedores':
				case 'reportes-facturacion':
				case 'reportes-reportes':
				case 'reportes-utilidades':
				case 'reportes-ingresos':
				case 'caja-caja':
				case 'mailings-mailings':
				case 'mailings-enviosmailing':
				case 'comisiones-comisiones':
				case 'comisiones-comisiones_escalas':
				case 'comisiones-comisiones_porcentajes':
				case 'comisiones-comisiones_minimos':
				case 'comisiones-equipos':
				case 'comisiones-comisiones_reportes':
					return TRUE;
					break;
				default:
					return FALSE;
			}
			break;
		case 'OPE':
			switch ($modulo) {
				case 'panel':
				case 'home':
				case 'seguridad':
				case 'catalogos':
				case 'categorias':
				case 'paquetes':
				case 'reservas':
				case 'ordenes':
				case 'mailings':
					return TRUE;
					break;
				default:
					return FALSE;
			}
			break;
		case 'VEN':
			switch ($modulo) {
				case 'panel':
				case 'home':
				case 'seguridad':
				case 'vendedores':
				case 'consultas':
					return TRUE;
					break;
				case 'reservas':
					switch ($section) {
						case 'reservas':
						case 'ordenes':
						case 'alarmas':
						case 'usuarios':
							return TRUE;
							break;
						default:
							return FALSE;
							break;
					}
					break;
				default:
					return FALSE;
			}
			break;
		case 'VENEXT':
			switch ($modulo) {
				case 'home':
				case 'panel':
				case 'reservas_vendedor':
				case 'reservas-reservas':
					return TRUE;
				break;
				default:
					return FALSE;
			}
			break;
		default:
			return false;
			break;
	}
}

function eliminar_tildes($cadena){

    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    //$cadena = utf8_encode($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}

function verificar_cupo($combinacion,$orden,$paquete,$reserva=false){
	$CI =& get_instance();
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Habitacion_model','Habitacion');

	$CI->data['combinacion'] = $combinacion;
	$CI->data['orden'] = $orden;
	$CI->data['paquete'] = $paquete;

	if(!isset($CI->data['combinacion']) || !$CI->data['combinacion']){
		$CI->data['combinacion'] = $CI->Combinacion->get($CI->data['orden']->combinacion_id)->row();
	}

	$filters = array();
	$filters['field'] = '';
	$filters['paquete_id'] = $CI->data['combinacion']->paquete_id;
	$filters['pax'] = $CI->data['combinacion']->pax;
	$filters['lugar_salida'] = $CI->data['combinacion']->lugar_id;
	//fecha_id ahroa se usa como rango
	//$filters['fecha_id'] = $CI->data['combinacion']->fecha_alojamiento_id;
	$filters['fecha_id'] = $CI->data['combinacion']->fecha_checkin.'|'.$CI->data['combinacion']->fecha_checkout;
	$filters['fecha'] = '';
	$filters['alojamiento'] = $CI->data['combinacion']->alojamiento_id;
	$filters['habitacion'] = $CI->data['combinacion']->habitacion_id;
	$filters['pension'] = $CI->data['combinacion']->paquete_regimen_id;
	$filters['transporte'] = $CI->data['combinacion']->fecha_transporte_id;

	//por defecto los pasajeros son los de la orden
	$pax =  $CI->data['orden']->pasajeros;
	//si la reserva está seteada, tomo los pax de la reserva (ya que pudo ser inicialmente una orden por 2 pax pero luego se separó en varias reservas individuales)
	if($reserva && isset($reserva->pasajeros) && $reserva->pasajeros){
		$pax =  $reserva->pasajeros;
	}

	//si es paquete grupal
	if($CI->data['paquete']->grupal){
		//piso este dato con las habitaciones que haya para la cantidad de pax elegidos
		$filters['pax_elegidos'] = $pax;
	}

	//tipos de habitacion disponibles
	$tipos_habitacion = $CI->Habitacion->getTiposCombinacionPaquete($CI->data['combinacion']->paquete_id,$filters);
	$CI->data['tipos_habitacion'] = $tipos_habitacion;


	//aca defino si la habitacion tiene cupo o no segun la cantidad de pasajeros elegida y lo que queda
	$habitacion_sin_cupo = false;
	if(@$CI->data['combinacion']->habitacion_id != 99){
		foreach($tipos_habitacion as $th){
			if($th->id == @$CI->data['combinacion']->habitacion_id && $th->completo){
				$habitacion_sin_cupo=true;
			}
		}
	}

	//si es habitacion compartida y la cantidad de pax elegida es mayor al cupo disponible ó cupo disponible real (si es persionalizado)
	if(@$CI->data['combinacion']->habitacion_id == 99 &&
		( ($pax > $CI->data['paquete']->cupo_disponible && !$CI->data['paquete']->cupo_paquete_personalizado)
			|| ($pax > $CI->data['paquete']->cupo_paquete_disponible_real && $CI->data['paquete']->cupo_paquete_personalizado)
		  )
		){
		$habitacion_sin_cupo = true;
	}

	$CI->data['habitacion_sin_cupo'] = $habitacion_sin_cupo;


	//transportes disponibles
	$CI->load->model('Transporte_model','Transporte');
	$transportes = $CI->Transporte->getCombinacionPaquete($CI->data['paquete']->id,$filters);
	$CI->data['transportes'] = $transportes;
	//echo $CI->db->last_query();

	//aca defino si el transporte tiene cupo o no segun la cantidad de pasajeros elegida y lo que queda
	$transporte_sin_cupo = false;
	foreach($transportes as $tf){

		if($tf->id == @$CI->data['combinacion']->fecha_transporte_id && $tf->cupo < @$pax){
			$transporte_sin_cupo=true;
		}
	}

	$CI->data['transporte_sin_cupo'] = $transporte_sin_cupo;
}

function cargar_alarmas($row, $todas=FALSE){
	$CI =& get_instance();
	$CI->load->model('Combinacion_model','Combinacion');

	$alarmas = new stdClass;

	//alarma informes
	$alarmas->informes = false;
	if ($todas || perfil_alarma('informes')){
		$alarmas->informes = $row->informes_de_pago;
	}

	//completar datos pax
	$alarmas->completar_datos_pax = false;
	if (($todas || perfil_alarma('completar_datos_pax')) && $row->confirmacion_inmediata && !$row->completo_paso3 && date('Y-m-d H:i:s') >= $row->fecha_limite_datos){
		$alarmas->completar_datos_pax = true;
	}

	//obtengo informe de pago mas reciente
	$q = "select * from bv_reservas_informes_pago where reserva_id = ".$row->id." order by id desc limit 1";
	$informe = $CI->db->query($q)->row();
	if(isset($informe->id) && $informe->id){
		$row->fecha_informe_pago = $informe->fecha_pago.' '.$informe->hora_pago;
	}

	//obtengo ultimo registro de historial de la reserva
	$q = "select TA.nombre, TA.tipo, TA.id
			from bv_reservas_comentarios C
			join bv_comentarios_tipos_acciones TA on TA.id = C.tipo_id and (TA.tipo = 'restablecida' || TA.tipo = 'anulacion' || TA.tipo = 'anulacion_automatica' || TA.tipo = 'contestador')
			where C.reserva_id = ".$row->id." order by C.fecha desc limit 1";
	$ultimo = $CI->db->query($q)->row();
	$row->ultima_accion = (isset($ultimo->tipo) && $ultimo->tipo) ? $ultimo->tipo : '';
	$row->accion_id = (isset($ultimo->id) && $ultimo->id) ? $ultimo->id : '';
	$row->nombre_accion = (isset($ultimo->nombre) && $ultimo->nombre) ? $ultimo->nombre : '';

	//llamados
	$alarmas->alerta_no_llamar = false;
	$alarmas->alerta_llamar_pax = false;
	$alarmas->alerta_reestablecida = false;
	$alarmas->alerta_contestador = false;
	if( ($row->estado_id == 4 && $row->saldo_viaje == 0)
		|| ($row->estado_id == 5 && $row->ultima_accion == 'anulacion') ){
			//si está confirmada y pago completo,
			//ó si esta anulada y fue manualmente, no muestro iconos
		//$alarmas->alerta_no_llamar = true;
	}
	else if(($row->estado_id == 2 || $row->estado_id == 5)){
		//estado por vencer o anulada
		if($row->ultima_accion != 'anulacion'){
			//si no tiene registro de anulacion manual en este sistemita de telefonos, muestro el icono
			//llamar al pax (ej porque se anulo automaticamente 'anulacion_automatica')

			if ($todas || perfil_alarma('alerta_llamar_pax')){
				$alarmas->alerta_llamar_pax = true;
			}
		}
	}
	else{
		if($row->ultima_accion == 'anulacion' && !in_array($row->estado_id,array(1,4))){
			//anulacion manual de reserva
			//le agregue en if validacion de estado para que no lo muestre en las confirmadas
			//llmar al pax
			if ($todas || perfil_alarma('alerta_llamar_pax')){
				$alarmas->alerta_llamar_pax = true;
			}
		} else if($row->ultima_accion == 'restablecida'){
			if ($todas || perfil_alarma('alerta_reestablecida')){
				$alarmas->alerta_reestablecida = true;
			}
		} else if($row->ultima_accion == 'contestador'){
			if ($todas || perfil_alarma('alerta_contestador')){
				$alarmas->alerta_contestador = true;
			}
		}
	}


	//obtengo ultimo documento de pago de la reserva del usuarios para ver si fue un recibo
	//y en este caso mostrar alerta "falta factura del proveedor"
	$q = "select M.talonario
			from bv_movimientos M
			where M.tipoUsuario = 'U' and M.usuario_id = ".$row->usuario_id." and M.reserva_id = ".$row->id."
			order by M.fecha desc limit 1";
	$ultimo_doc = $CI->db->query($q)->row();
	$alarmas->falta_factura_proveedor = false;
	if(@$ultimo_doc->talonario == 'RE_X'){
		if ($todas || perfil_alarma('falta_factura_proveedor')){
			$alarmas->falta_factura_proveedor = true;
		}
	}

	//alarma cargar vouchers (tambien tiene en cuenta los q tienen saldo pendiente)
	$combinacion = $CI->Combinacion->get($row->combinacion_id)->row();
	$reserva_adicionales = adicionales_reserva($row);
	$precios = calcular_precios_totales($combinacion,$CI->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$row->id);

	$saldo_pendiente = $precios['num']['saldo_pendiente'];

	$alarmas->faltan_cargar_vouchers = false;
	if($row->estado_id == 4 && $row->operador_id > 1 && $row->vouchers_cargados < $row->cantidad_vouchers && date('Y-m-d H:i:s') >= $row->fecha_limite_vouchers && $saldo_pendiente == 0){

		if ($todas || perfil_alarma('faltan_cargar_vouchers')){
			$alarmas->faltan_cargar_vouchers = true;
		}
	}

	//alarma de cupo de transporte vencido
	$alarmas->alerta_cupos_vencidos = false;
	/*$cv = $CI->db->query("select p.id, max(tf.fecha_vencimiento) as fecha_vencimiento
								from bv_transportes_fechas tf
								join bv_paquetes_combinaciones pc on pc.fecha_transporte_id = tf.id
								join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
									and p.id = ".$row->paquete_id."
								group by p.id")->row();*/
	//if(isset($cv->id) && isset($cv->fecha_vencimiento) && $cv->fecha_vencimiento >= '0000-00-00' && $cv->fecha_vencimiento <= date('Y-m-d') && $cv->id){

	//ahora sale del row de la reserva
	if(isset($row->paquete_id) && isset($row->fecha_vencimiento) && $row->fecha_vencimiento >= '0000-00-00' && $row->fecha_vencimiento <= date('Y-m-d') && $row->paquete_id){
		if ($todas || perfil_alarma('alerta_cupos_vencidos')){
			$alarmas->alerta_cupos_vencidos = true;
		}
	}

	//si alcanzó la fecha limite de pago completo y hay
	$alarmas->fecha_limite_pago_completo = false;
	if ($row->estado_id != 5 && strtotime($row->fecha_limite_pago_completo) <= time()) {
		//esto ya se calculo arriba
		//$combinacion = $CI->Combinacion->get($row->combinacion_id)->row();
		//$reserva_adicionales = adicionales_reserva($row);
		//$precios = calcular_precios_totales($combinacion,$CI->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$row->id);
		$pagos_hechos = $precios['num']['monto_abonado'];
		$saldo_pendiente = $precios['num']['saldo_pendiente'];

		if($saldo_pendiente > 0){

			if ($todas || perfil_alarma('fecha_limite_pago_completo')){
				$alarmas->fecha_limite_pago_completo = true;
			}
		}
	}

	//29-08-18 alarma para mostrar que hay diferencias entre la habitacion reservada y la asignada en roomin
	//19-09-18 esta alarma no se usa mas por pedido de juan
	$alarmas->diferencias_rooming = false;
	/*if ($row->rooming_cupo_id && $row->fecha_alojamiento_cupo_id && $row->rooming_cupo_id != $row->fecha_alojamiento_cupo_id){
		$alarmas->diferencias_rooming = true;
	}*/

	//if($_SERVER['REMOTE_ADDR'] == '181.171.24.39'){
		//pre($row);
	//}

	//05-09-18 esta alarma no la quieren mas
	//$alarmas->tiene_adicionales = false;

	//05-11-18, esta alarma no se muestra en ALARMAS sino al lado del codigo de cada reserva
	$alarmas->tiene_adicionales = $row->adicionales;

	return $alarmas;
}


function cargar_alarmas_optim($row){
    $CI =& get_instance();
	$CI->load->model('Combinacion_model','Combinacion');

	$alarmas = new stdClass;

	//alarma informes
	$alarmas->informes = false;
	if (perfil_alarma('informes')){
		$alarmas->informes = $row->informes_de_pago;
	}

	//completar datos pax
	$alarmas->completar_datos_pax = false;
	if (perfil_alarma('completar_datos_pax')) {
		$alarmas->completar_datos_pax = @$row->completar_datos_pax;
	}

	//llamados
	$alarmas->alerta_no_llamar = false;
	$alarmas->alerta_llamar_pax = false;
	$alarmas->alerta_reestablecida = false;
	$alarmas->alerta_contestador = false;
	if (perfil_alarma('alerta_llamar_pax')) {
		$alarmas->alerta_llamar_pax = @$row->alerta_llamar_pax;
	}
	if (perfil_alarma('alerta_reestablecida')) {
		$alarmas->alerta_reestablecida = @$row->alerta_reestablecida;
	}
	if (perfil_alarma('alerta_contestador')) {
		$alarmas->alerta_contestador = @$row->alerta_contestador;
	}

	//obtengo ultimo documento de pago de la reserva del usuarios para ver si fue un recibo
	//y en este caso mostrar alerta "falta factura del proveedor"
	$q = "select M.talonario
			from bv_movimientos M
			where M.tipoUsuario = 'U' and M.usuario_id = ".$row->usuario_id." and M.reserva_id = ".$row->id."
			order by M.fecha desc limit 1";
	$ultimo_doc = $CI->db->query($q)->row();
	$alarmas->falta_factura_proveedor = false;
	if(@$ultimo_doc->talonario == 'RE_X'){
		if (perfil_alarma('falta_factura_proveedor')){
			$alarmas->falta_factura_proveedor = true;
		}
	}

	//alarma cargar vouchers (tambien tiene en cuenta los q tienen saldo pendiente)
	$combinacion = $CI->Combinacion->get($row->combinacion_id)->row();
	// echo '<pre>';
	// print_r($CI->data);
	// die();
	$precios = calcular_precios_totales($combinacion,@$CI->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$row->id);

	$saldo_pendiente = $precios['num']['saldo_pendiente'];

	$alarmas->faltan_cargar_vouchers = false;
	if($row->estado_id == 4 && $row->operador_id > 1 && $row->vouchers_cargados < $row->cantidad_vouchers && date('Y-m-d H:i:s') >= $row->fecha_limite_vouchers && $saldo_pendiente == 0){

		if (perfil_alarma('faltan_cargar_vouchers')){
			$alarmas->faltan_cargar_vouchers = true;
		}
	}

	//alarma de cupo de transporte vencido
	$alarmas->alerta_cupos_vencidos = false;
	if(isset($row->paquete_id) && isset($row->fecha_vencimiento) && $row->fecha_vencimiento >= '0000-00-00' && $row->fecha_vencimiento <= date('Y-m-d') && $row->paquete_id){
		if (perfil_alarma('alerta_cupos_vencidos')){
			$alarmas->alerta_cupos_vencidos = true;
		}
	}

	//si alcanzó la fecha limite de pago completo y hay
	$alarmas->fecha_limite_pago_completo = false;
	if (perfil_alarma('fecha_limite_pago_completo')) {
		$alarmas->fecha_limite_pago_completo = @$row->fecha_limite_pago_completo;
	}

	//29-08-18 alarma para mostrar que hay diferencias entre la habitacion reservada y la asignada en roomin
	//19-09-18 esta alarma no se usa mas por pedido de juan
	$alarmas->diferencias_rooming = false;

	//05-09-18 esta alarma no la quieren mas
	//$alarmas->tiene_adicionales = false;

	//05-11-18, esta alarma no se muestra en ALARMAS sino al lado del codigo de cada reserva
	$alarmas->tiene_adicionales = $row->adicionales;

	return $alarmas;
}

function cotizacion_dolar(){
	return cotizacion_dolar_geek();

	/*
	$cotizacion = 0.00;
	$data_in = "https://www.dolarsi.com/api/api.php?type=valoresprincipales";

	$data_json = @file_get_contents($data_in);
	$data_json = json_decode($data_json);

	$dolar = str_replace(',','.',$data_json[0]->casa->venta);
	$cotizacion = number_format($dolar,2,'.','');

	return $cotizacion;
	*/
}

function cotizacion_dolar_geek(){
	$cotizacion = 0.00;
	// $data_in = "http://ws.geeklab.com.ar/dolar/get-dolar-json.php"; // api vieja 
	$data_in = get_instance()->config->item('url_dolar');

	$data_json = @file_get_contents($data_in);
	$data_json = json_decode($data_json);

	$dolar = str_replace(',','.',$data_json->compra);
	$cotizacion = number_format($dolar,2,'.','');

	return $cotizacion;
}

function cotizacion_dolar_old(){
	$cotizacion = 0.00;
	$data_in = "http://api.bluelytics.com.ar/v2/latest";
	$data_json = @file_get_contents($data_in);
	if(strlen($data_json)>0)
	{
	  $data_out = json_decode($data_json,true);

	  if(is_array($data_out)){
		if(isset($data_out['oficial'])){
			$cotizacion = $data_out['oficial']['value_avg'];
		}
		//if(isset($data_out['blue'])) print "Blue: ".$data_out['blue']."<br>\n";
	  }
	}

	return $cotizacion;
}

function verificar_costo_operador($reserva){
	$CI =& get_instance();
	$CI->load->model('Movimiento_model','Movimiento');
	$CI->load->model('Concepto_model','Concepto');

	//tengo que ver si ya estaba registrado el 100% del costo del viaje en el operador
	//chequeo si en la cuenta del operador ya registré o no el costo de este viaje
	/*$existe_costo = $CI->Movimiento->getWhere(
									array(
											'tipoUsuario'=>'A',
											'usuario_id'=>$reserva->operador_id,
											'reserva_id'=>$reserva->id
										 )
								 )->row();*/
	$CI->load->model('Reserva_comentario_model','Reserva_comentario');
	$existe_costo = $CI->Reserva_comentario->getStatusCostosOperador($reserva->id);

	//si existe, entonces le genero contra movimiento para anularlo
	if( $existe_costo->generados > 0 && ($existe_costo->generados > $existe_costo->anulados) ){

	// if(isset($existe_costo->id) && $existe_costo->id){
		//si existe, entonces le genero contra movimiento para anularlo
		$op_conc = $CI->Concepto->get(57)->row();
		$op_concepto = $op_conc->nombre;
		$valor_costo = $reserva->c_costo_operador*$reserva->pasajeros;
		$moneda_costo = $reserva->precio_usd ? 'USD' : 'ARS';
		$fecha = date('Y-m-d H:i:s');
		registrar_movimiento_cta_cte($reserva->operador_id,'A',$reserva->id,$fecha,$op_concepto,0.00,$valor_costo,false,$comentarios='',$comprobante='',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda_costo,$tipo_cambio=$CI->settings->cotizacion_dolar,$informe_id=false);

		//Registrar el registro de costo de viaje para el operador
		$mail = false;
		$template = '';
		registrar_comentario_reserva($reserva->id,7,'registro_costo_operador','Anulación Registro 100% costo viaje en Cta Cte de Operador '.$reserva->operador.'. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | HABER '.$valor_costo);
	}
}

function validateDate($fecha){
	$f = explode('-',$fecha);
	return checkdate($f[1],$f[2],$f[0]);
}

function admin_id(){
	$CI =& get_instance();

	return $CI->session->userdata('admin_id');
}

function duplicar_paquete($id){
	$CI =& get_instance();
    $CI->load->model('Paquete_model','Paquete');
	$nuevo_id = $CI->Paquete->duplicar($id);
	return $nuevo_id;
}

function validar_codigo($id){
	$CI =& get_instance();
	$CI->load->model('Paquete_model','Paquete');
  	$code = $CI->Paquete->validar_codigo($id);
  	return $code;
}

// ------------------------------------------------------------------------
function isValidCuit($cuit) {
	$digits = array();
	if (strlen($cuit) != 13) return false;
	for ($i = 0; $i < strlen($cuit); $i++) {
		if ($i == 2 or $i == 11) {
			if ($cuit[$i] != '-') return false;
		} else {
			if (!ctype_digit($cuit[$i])) return false;
			if ($i < 12) {
				$digits[] = $cuit[$i];
			}
		}
	}
	$acum = 0;
	foreach (array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2) as $i => $multiplicador) {
		$acum += $digits[$i] * $multiplicador;
	}
	$cmp = 11 - ($acum % 11);
	if ($cmp == 11) $cmp = 0;
	if ($cmp == 10) $cmp = 9;
	return ($cuit[12] == $cmp);
}

/*
Funcion que chequea si la reserva tiene saldo pendiente de pago, y generar registros en historial
para luego el cron enviarle los mails
*/
function chequear_saldo_pendiente($reserva_id){
	$CI =& get_instance();
    $CI->load->model('Reserva_model','Reserva');
    $CI->load->model('Combinacion_model','Combinacion');

	$reserva = $CI->Reserva->get($reserva_id)->row();
	$combinacion = $CI->Combinacion->get($reserva->combinacion_id)->row();

	$reserva->adicionales = $CI->Reserva->getAdicionales($reserva->id);
	$adicionales_valores = array();
	foreach($reserva->adicionales as $a){
		$adicionales_valores[$a->paquete_adicional_id] = $a->v_total;
	}
	$precios = montos_numericos($combinacion,$adicionales_valores,false,$reserva->id);

	if( $precios['monto_abonado'] >= $precios['precio_total']){
		//si completo el pago, status CONFIRMADA
		$CI->Reserva->update($reserva->id,array('estado_id' => '4'));

		//genero registro de pago completo en historial para enviar mail
		//registrar_comentario_reserva($reserva->id,7,'informe_pago_recibido','Se recibió un informe de pago por parte del usuario');

	}
}

/*
Generacion de comprobante
*/
function generar_comprobante($data, $tipo_comprobante, $path) {
    $CI =& get_instance();
    $CI->load->helper(array('dompdf', 'file'));

	//cargo datos de facturacion
	$CI->load->model('Reserva_facturacion_model','Reserva_facturacion');
	$CI->Reserva_facturacion->filters = "reserva_id = ".$data['reserva_id'];
	$f = $CI->Reserva_facturacion->getAll(1,0,'id','asc')->row();

	if(isset($f->id) && $f->id){
		$data['direccion'] = $f->f_ciudad.', '.$f->f_domicilio.' '.$f->f_numero.($f->f_depto?(' Depto: '.$f->f_depto):'').' CP: '.$f->f_cp;
	}

	if($tipo_comprobante == 'RE_X') {
		$html = $CI->load->view('recibo', $data, true);
	}
	else {
		$html = $CI->load->view('factura', $data, true);
	}


	$pdf = pdf_create($html, '', false);
	$comprobante = $tipo_comprobante.'-'.str_pad($data['punto_venta'], 4, '0', STR_PAD_LEFT).'-'.str_pad($data['numero_factura'], 8, '0', STR_PAD_LEFT);

	write_file($path.$comprobante.'.pdf', $pdf);

	return $comprobante;
}


function generar_comprobante_S3($data, $tipo_comprobante, $path) {
	$CI =& get_instance();
    $CI->load->helper(array('dompdf', 'file'));
	$CI->load->library('aws_s3');
	$CI->load->helper(array('form', 'url'));

	$config['upload_path'] = './uploads/temp/';
	$config['allowed_types'] = 'jpg|jpeg|png|pdf';
	$config['max_size'] = 5048;
	$CI->load->library('upload', $config);


	//cargo datos de facturacion
	$CI->load->model('Reserva_facturacion_model','Reserva_facturacion');
	$CI->Reserva_facturacion->filters = "reserva_id = ".$data['reserva_id'];
	$f = $CI->Reserva_facturacion->getAll(1,0,'id','asc')->row();

	if(isset($f->id) && $f->id){
		$data['direccion'] = $f->f_ciudad.', '.$f->f_domicilio.' '.$f->f_numero.($f->f_depto?(' Depto: '.$f->f_depto):'').' CP: '.$f->f_cp;
	}

	if($tipo_comprobante == 'RE_X') {
		$html = $CI->load->view('recibo', $data, true);
	}
	else {
		$html = $CI->load->view('factura', $data, true);
	}


	$pdf = pdf_create($html, '', false);
	
	$comprobante = $tipo_comprobante.'-'.str_pad($data['punto_venta'], 4, '0', STR_PAD_LEFT).'-'.str_pad($data['numero_factura'], 8, '0', STR_PAD_LEFT);
	
	write_file($path.$comprobante.'.pdf', $pdf);

	

	try {
		$CI->aws_s3->send_file_to_s3(
			FCPATH . 'data/facturas/'.$comprobante.'.pdf',  // full path
			$comprobante.'.pdf' // nombre del archivo 
		);
	} catch (Exception $e) {
		$e->getMessage();
	}

	// borro archivo
	unlink($path.$comprobante.'.pdf');

	return $comprobante;
}



/*
Registra comentario sobre la reserva: es para el historial y seguimiento de las mismas
*/
function registrar_comentario_reserva($id,$admin_id,$tipo,$motivo,$mail=false,$template=false,$ref_id=false,$data_updated=false){
	$CI =& get_instance();

	$CI->load->model('Comentario_tipo_accion_model','Comentario_tipo_accion');
	$row_tipo = $CI->Comentario_tipo_accion->getWhere(array('tipo' => $tipo))->row();

	$CI->load->model('Reserva_comentario_model','Reserva_comentario');

	$coment['reserva_id'] = $id;
	if($admin_id==7 && !esVendedor()){
		$admin_id = admin_id();

		$coment['admin_id'] = $admin_id ? $admin_id : 15;//cambio el ID 7 de sistema viejo por el 15 del nuevo
		$coment['tipoUsuario'] = 'A';//tipo admin
	}
	else{
		if(esVendedor()){
			if(perfil() == 'VENEXT'){
				$coment['tipoUsuario'] = 'V';//tipo vendedor externo
				$coment['admin_id'] = admin_id();
			}
			else{
				//es ADMIN tipo venta
				$coment['tipoUsuario'] = 'A';//admin tipo venta
				$coment['admin_id'] = admin_id();
			}
		}
		else{
			//es ADMIN
			$coment['tipoUsuario'] = 'A';//tipo admin
			$coment['admin_id'] = admin_id();
		}
		//$coment['admin_id'] = $admin_id;
	}

	$coment['fecha'] = date('Y-m-d H:i:s');
	$coment['tipo_id'] = isset($row_tipo->id)?$row_tipo->id:'';
	$coment['comentarios'] = $motivo;
	$coment['mail'] = $mail;
	$coment['template'] = $template;
	$coment['ref_id'] = $ref_id;
	$coment['data_updated'] = $data_updated;

	$CI->Reserva_comentario->insert($coment);
	$ins_id = $CI->db->insert_id();

	return $ins_id;
}

/*
funcion para generar movimiento en cuenta corriente de usuario, o vendedor, o agencia
*/
function registrar_movimiento_cta_cte($tipo_id,$tipo_usuario,$reserva_id,$fecha,$concepto,$debe,$haber,$parcial,$comentarios='',$comprobante='',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda='ARS',$tipo_cambio,$informe_id=false,$alta_de_reserva=false,$paquete_id=false,$reserva_adicional_id=false,$mov_asoc_id=false){
	$CI =& get_instance();
	$CI->load->model('Movimiento_model','Movimiento');
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Paquete_model','Paquete');

	//obtengo data de reserva
	if(isset($reserva_id) && $reserva_id){
		$reserva_row = $CI->Reserva->get($reserva_id)->row();
	}
	else{
		//si viene data de paquete
		if(isset($paquete_id) && $paquete_id){
			$paquete_row = $CI->Paquete->get($paquete_id)->row();
			$reserva_row = new stdClass();
			$reserva_row->precio_usd = $paquete_row->precio_usd;
		}
		else{
			$reserva_row = new stdClass();
			$reserva_row->precio_usd = $moneda=='ARS'?0:1;
		}
	}

	//Si el paquete es en ARS y el pago en ARS => NO registro equivalente en USD
	$registro_usd = (!$reserva_row->precio_usd && $moneda == 'ARS') ? false : true;

	//salvo que sea un alta de reserva, donde registro en ambas para inicializarla
	//eso no se usaria debido a que cada reserva registra los movs en la cta q le corresponde
	if(false && $alta_de_reserva){
		$registro_usd = true;
	}

	//la actualizacion del SALDO (parcial y parcial_usd) de la cuenta ahora cambio
	//le paso el tipo de moneda del viaje para saber en qué CTA CTE cae el movimiento
	$mov = $CI->Movimiento->getLastMovimientoByTipoUsuario($tipo_id,$tipo_usuario,$reserva_row->precio_usd)->row();

	if(!isset($mov->id) || !$mov->id){
		//si el ultimo movimiento no existe (es el primero que voy a generar)
		//seteo en 0 los parciales
		$mov = new stdClass();
		$mov->parcial = 0.00;
		$mov->parcial_usd = 0.00;
	}

	if($reserva_row->precio_usd){
		//viaje en USD

		//si el pago es en USD
		if($moneda == 'USD'){
			$parcial_usd = $mov->parcial_usd+$debe-$haber;//los tomo tal cual vienen
			$parcial = $mov->parcial;
		}
		else{
			//si el pago es en ARS
			$parcial = $mov->parcial;
			$parcial_usd = $mov->parcial_usd+$debe/$tipo_cambio-$haber/$tipo_cambio;//los paso a la cotizacion del dia
		}

	}
	else{
		//viaje en ARS

		//si el pago es en USD
		if($moneda == 'USD'){
			$parcial_usd = $mov->parcial_usd;
			$parcial = $mov->parcial+$debe*$tipo_cambio-$haber*$tipo_cambio;//los paso a la cotizacion del dia
		}
		else{
			//si el pago es en ARS
			$parcial = $mov->parcial+$debe-$haber;//los tomo tal cual vienen
			$parcial_usd = $mov->parcial_usd;
		}

	}

	$data_a = array(
				"usuario_id" => $tipo_id,
				"tipoUsuario" => $tipo_usuario,
				"reserva_id" => $reserva_id,
				"fecha" => $fecha,
				"concepto" => $concepto,
				"tipo_cambio" => $tipo_cambio,
				"debe" => ($moneda == 'ARS') ? $debe : ($debe*$tipo_cambio),
				"haber" => ($moneda == 'ARS') ? $haber : ($haber*$tipo_cambio),
				"parcial" => $parcial,
				"debe_usd" => (!$registro_usd) ? 0.00 : (($moneda == 'USD') ? $debe : ($debe/$tipo_cambio)),
				"haber_usd" => (!$registro_usd) ? 0.00 : (($moneda == 'USD') ? $haber : ($haber/$tipo_cambio)),
				"parcial_usd" => (!$registro_usd) ? $mov->parcial_usd : $parcial_usd,
				"pago_usd" => $moneda=='USD'?1:0,
				"comentarios" => $comentarios,
				"comprobante" => $comprobante,
				"factura_id" => $factura_id,
				"talonario" => $talonario,
				"factura_asociada_id" => $factura_asociada_id,
				"talonario_asociado" => $talonario_asociado,
				"informe_id" => $informe_id,
				"paquete_id" => $paquete_id,
				"reserva_adicional_id" => $reserva_adicional_id,
				"mov_asoc_id" => $mov_asoc_id,
				"cta_usd" => $reserva_row->precio_usd?1:0
		);
	$movimiento_id = $CI->Movimiento->insert($data_a);

	//actualizo el id del movimiento en la tabla de informes de pago
	if($informe_id){
		$CI->load->model('Reserva_informe_pago_model','Reserva_informe_pago');
		$CI->Reserva_informe_pago->update($informe_id,array('movimiento_id'=>$movimiento_id));
	}

	//si estoy registrando pago de un pasajero el cual hace que la reserva pase a estado CONFIRMADA
	$CI->load->model('Concepto_model','Concepto');
	$conceptoObj = $CI->Concepto->getBy('nombre='.$concepto);
	if(isset($conceptoObj->id) && $tipo_usuario == 'U' && $conceptoObj->aplica_a == 'haber' && $conceptoObj->pasa_a_confirmada && $haber > 0){
		//chequeo si en la cuenta del operador ya registré o no el costo de este viaje
		registrar_costo_operador($reserva_row,$fecha,$tipo_cambio,$informe_id);
	}

	return $movimiento_id;
}

function registrar_costo_operador($reserva_row,$fecha,$tipo_cambio,$informe_id=false){
	/*$existe_costo = $CI->Movimiento->getWhere(
										array(
												'tipoUsuario'=>'A',
												'usuario_id'=>$reserva_row->operador_id,
												'reserva_id'=>$reserva_row->id
											 )
									 )->result();*/

	$CI =& get_instance();
	$CI->load->model('Reserva_comentario_model','Reserva_comentario');
	$CI->load->model('Concepto_model','Concepto');
	$existe_costo = $CI->Reserva_comentario->getStatusCostosOperador($reserva_row->id);

	//si no hay costo cargado, no lo registra
	//if(count($existe_costo)==0 && $reserva_row->c_costo_operador > 0){

	//si la cantidad de costos registrados es CERO ó si es IGUAL a la cantidad de ANULADOS, lo vuelvo a crear (viene de anulacion)
	if( $reserva_row->c_costo_operador > 0 &&
			($existe_costo->generados == 0 || ($existe_costo->generados == $existe_costo->anulados))
	 ){
		//registrar en la cuenta del operador el 100% del costo del viaje cuando la reserva se confirma al recibir al menos 1 pago.
		$op_conc = $CI->Concepto->get(56)->row();
		$op_concepto = $op_conc->nombre;
		$valor_costo = $reserva_row->c_costo_operador*$reserva_row->pasajeros;
		$moneda_costo = $reserva_row->precio_usd ? 'USD' : 'ARS';
		registrar_movimiento_cta_cte($reserva_row->operador_id,'A',$reserva_row->id,$fecha,$op_concepto,$valor_costo,0.00,false,$comentarios='',$comprobante='',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda_costo,$tipo_cambio,$informe_id);

		//Registrar el registro de costo de viaje para el operador
		$mail = false;
		$template = '';
		registrar_comentario_reserva($reserva_row->id,7,'registro_costo_operador','Registro 100% costo viaje en Cta Cte de Operador '.$reserva_row->operador.'. CONCEPTO: '.$reserva_row->nombre." - ".$reserva_row->paquete_codigo.' | DEBE '.$valor_costo);
	}
}

/*
Determina si se pueden completar los datos de los pasajeros para la reserva
Se usa en el resumen del viaje
*/
function puede_completar_datos($reserva){
	$CI =& get_instance();
	$CI->load->model('Paquete_model','Paquete');
	$paquete = $CI->Paquete->get($reserva->paquete_id)->row();

	//puede completar datos si es QUE NO SE ALCANZÓ LA FECHA DE COMPLETITUD DE DATOS
	return $reserva->fecha_limite_datos > date('Y-m-d H:i:s') ? 1 : 0;

	/*
	//diferencia en dias desde la fecha de la reserva a la del paquete
	$diff = abs(strtotime($paquete->fecha_inicio) - strtotime($reserva->fecha_reserva));
	$d = floor($diff / (60*60*24));

	return $d > $paquete->dias_completar_datos ? true : false;
	*/
}

/*
Devuelve la fecha limite hasta donde puede completar datos de pasajeros.
Se usa en el resumen del viaje y en mails.
*/
function fecha_completar_datos($reserva_id){
	$CI =& get_instance();
	$CI->load->model('Paquete_model','Paquete');
	$CI->load->model('Reserva_model','Reserva');
	$reserva = $CI->Reserva->get($reserva_id)->row();
	$paquete = $CI->Paquete->get($reserva->paquete_id)->row();

	//return date('Y-m-d H:i:s', strtotime($paquete->fecha_fin. ' - '.$paquete->dias_completar_datos.' days'));
	return $reserva->fecha_limite_datos;
}

/*
Devuelve la fecha limite hasta cuando puede saldar el viaje.
Se usa en mails.
*/
function fecha_saldar_viaje($reserva_id){
	$CI =& get_instance();
	$CI->load->model('Paquete_model','Paquete');
	$CI->load->model('Reserva_model','Reserva');
	$reserva = $CI->Reserva->get($reserva_id)->row();
	$paquete = $CI->Paquete->get($reserva->paquete_id)->row();

	//return date('Y-m-d H:i:s', strtotime($paquete->fecha_fin. ' - '.$paquete->dias_pago_completo.' days'));
	return $reserva->fecha_limite_pago_completo;
}

/*
Calcula la edad en base a la fecha de nacimiento
*/
function edad($fecha){
	return date_diff_years($fecha,date('Y-m-d'));
}

/*
Genera transaccion en base de datos y devuelve URL de redireccion con mensaje final
*/
function registrar_transaccion_mp($data){
	$CI =& get_instance();
	$CI->load->model('Transaccion_model','Transaccion');

	$datos['numtransaccion'] = $data['collection_id'];

	//me fijo si ya existe en el sistema
	$trans = $CI->Transaccion->getWhere($datos)->result();

	$datos['estado'] = 'pending';
	$datos['reserva_id'] = $data['reserva_id'];
	$datos['monto_total'] = isset($data['monto'])?$data['monto']:0;
	$datos['origen'] = 'mercadopago';
	$datos['fecha_procesado'] = date('Y-m-d H:i:s');

	//si no existe
	if(count($trans) == 0){
		$tr_id = $CI->Transaccion->insert($datos);

		//genero registro en historial de reserva por nueva transaccion de MP generada en estado pendiente
		registrar_comentario_reserva($data['reserva_id'],7,'mp_transaccion_registrada','Nueva transaccion de Mercado Pago registrada. ID: '.$tr_id.' | NUM TRANSACCION: '.$data['collection_id'].' | MONTO TOTAL '.$datos['monto_total']);
	}
	else{
		$tr_id = $trans[0]->id;
		$CI->Transaccion->update($tr_id,$datos);
	}

	//redirijo a pagina final con mensaje
	return site_url('reservas/pago_realizado/'.$data['hash']);
}

/*
Obtiene URL de checkout de Mercado Pago
*/
function get_link_mp($data){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$reserva = $CI->Reserva->get($data['reserva_id'])->row();
	$titulo = $reserva->nombre;
	$CI->load->library('mercadopago');
	$mp = $CI->mercadopago->init();

	$monto = floatval($data['monto']);

	//si el paquete es en usd, lo paso a ARS
	if($reserva->precio_usd){
		$monto = $monto*$CI->settings->cotizacion_dolar;
	}

	if($CI->settings->mp_gastos_admin){
		$monto = $monto*$CI->settings->mp_gastos_admin;
	}

	$preference_data = array(
		"items" => array(
						array(
							"title" => $titulo,
							"quantity" => 1,
							"currency_id" => "ARS",
							"unit_price" => $monto
						)
				 ),
		"external_reference" => $reserva->code,
		"notification_url"	=> site_url('pagos/update_mp'),
		"back_urls" => array(
			"success"	=> site_url('reservas/resumen/'.encriptar($reserva->code)),//ver URL
			"pending"	=> site_url('reservas/resumen/'.encriptar($reserva->code)),//ver URL
			"failure"	=> site_url('reservas/resumen/'.encriptar($reserva->code)),//ver URL
		),
		"sponsor_id" => 417525753
	);

	//excluyo las formas de pago que no son tarjeta de credito
	$preference_data['payment_methods']['excluded_payment_types'] = array (
		array ( "id" => "atm" )
	);

	$preference = $mp->create_preference($preference_data);
	return $preference['response']['init_point'];
	//return $preference['response']['sandbox_init_point'];
}

/*
Obtiene URL de checkout de Paypal
*/
function get_link_pp($data){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$reserva = $CI->Reserva->get($data['reserva_id'])->row();
	$titulo = $reserva->nombre;
	$CI->load->library('PayPallib');

    $settings = [];
    $settings['API_Username'] = $CI->config->item('pp_username');
    $settings['API_Password'] = $CI->config->item('pp_password');
    $settings['API_Signature'] = $CI->config->item('pp_signature');
    $settings['currency'] = 'USD'; //siempre paga en usd En paypal

	$settings['returnURL'] = site_url('pagos/paypal/'.encriptar($reserva->code));
	$settings['cancelURL'] = site_url('pagos/paypal/'.encriptar($reserva->code));

    $pp = $CI->paypallib;

    //inicializo
	$pp->setup($settings);

	//defino modo prueba o no
	$pp->sandbox(false);

	//si el paquete es en ARS, lo paso a USD, independientemente de la moneda que haya elegido el usuario
	if(!$reserva->precio_usd){
		$data['monto'] = number_format($data['monto']/$CI->settings->cotizacion_dolar,2,'.','');
	}

	$monto = floatval($data['monto']);

	//el valor de taxes sale del importe configurado en base
	$taxes = number_format($CI->settings->pp_gastos_admin*$monto+$CI->settings->pp_gastos_admin_fijos,2,'.','');

	$result = $pp->checkout($titulo, $monto, $taxes);

	#pre($result);

	if ($result['status'] == 'OK' && $result['url']) {

		return $result['url'];
	}
	else{
		//url redirect a desde donde viene para indicar que hubo error
		$url = $_SERVER['HTTP_REFERER'].'?error=1';
		return $url;
	}
}

function adicionales_reserva($reserva){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$reserva->adicionales = $CI->Reserva->getAdicionales($reserva->id);
	$adicionales_nombre = array();
	$adicionales_valores = array();
	foreach($reserva->adicionales as $a){
		$adicionales_nombre[] = $a->nombre;
		$adicionales_valores[$a->paquete_adicional_id] = $a->adicional_valor;
	}
	$reserva->nombre_adicionales = implode(', ',$adicionales_nombre);

	$CI->data['adicionales_valores'] = $adicionales_valores;

	return $reserva->adicionales;
}

/*
A partir de un objeto ORDEN, genero la reserva asociada, junto con todos sus datos
*/
function generar_reserva($orden){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Paquete_model','Paquete');
	$CI->load->model('Orden_model','Orden');
	$CI->load->model('Orden_facturacion_model','Orden_facturacion');
	$CI->load->model('Orden_pasajero_model','Orden_pasajero');
	$CI->load->model('Movimiento_model','Movimiento');
	$CI->load->model('Paquete_rooming_model','Paquete_rooming');
	$CI->load->model('Fecha_alojamiento_cupo_model','Fecha_alojamiento_cupo');

	//genera reserva a partir de codigo de reserva
	$reserva = $CI->Reserva->getWhere(array('orden_id'=>$orden->id))->row();
	if(isset($reserva->id) && $reserva->id){
		$reserva_id = $reserva->id;
		$reserva = $CI->Reserva->get($reserva_id)->row();
	}
	else{
		/*
		23-08-18 si el paquete es GRUPAL, para cada uno de los pasajeros le deberia generar una reserva diferente
		*/

		$reservas_ids = [];

		//determino la cantidad de reservas que voy a crear, segun el viaje sea grupal y la cantidad de pax
		$cantReservas = ($orden->grupal) ? $orden->pasajeros : 1;

		$nhab=false;
		for($i=1;$i<=$cantReservas;$i++){

			$reserva_id = $CI->Reserva->generar($orden->id);

			$reservas_ids[] = $reserva_id;

			//los adicionales
			$ret_ad = $CI->Reserva->generarAdicionales($orden->id,$reserva_id,$i);

			//los datos de facturacion
			$CI->Reserva->generarDatosFacturacion($orden->id,$reserva_id);

			//los datos de pasajeros
			//27-08-18 le paso el numero de pax para que genere un pasajero por reserva
			$CI->Reserva->generarDatosPasajeros($orden->id,$reserva_id,($orden->grupal) ? $i : false);

			//actualizo que completó el paso 4 de la orden (clickeo en alguno de los botones)
			$CI->Orden->update($orden->id,array('completo_paso4' => '1'));

			//si no se le cargó adicional, le actualizo el valor
			if(!$ret_ad){
				$CI->Reserva->update($reserva_id,['adicionales_precio' => 0]);
			}

			$reserva = $CI->Reserva->get($reserva_id)->row();
			$paquete = $CI->Paquete->get($reserva->paquete_id)->row();

			//genero movimiento en cta cte de USUARIO por monto de reserva
			//3er parametro para saber de que tipo de cuenta
			$mov = $CI->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();

			$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
			$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;

			//si es reserva nueva
			if($reserva->estado_id == 1){

				//globales
				$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
				$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $CI->settings->cotizacion_dolar;

				registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id,$reserva->fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio,false,$alta_de_reserva=true);

				//tambien seteo marcas en el historial para que luego se le envie mail de reserva
				$mail = true;
				$template = 'datos_reserva';
				registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);

				//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
				$mov2 = $CI->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();
				$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
				registrar_movimiento_cta_cte(1,"A",$reserva_id,$reserva->fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
				registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
				//-------------------------------------------------------------------------------------------------------------------

			}
			else if($reserva->estado_id == 13){
				//reserva a confirmar
				//tambien seteo marcas en el historial para que luego se le envie mail de reserva
				$mail = true;
				$template = 'datos_reserva';
				registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nueva reserva a confirmar. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);

			}
			else if($reserva->estado_id == 12){
				//reserva lista espera
				//tambien seteo marcas en el historial para que luego se le envie mail de reserva
				$mail = true;
				$template = 'datos_reserva';
				registrar_comentario_reserva($reserva_id,7,'nueva_reserva','Nueva reserva en Lista de Espera. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);

			}

		}

		//23-08-18 si el viaje es grupal, con cada uno de los IDs de reservas de este grupo, los guardo en nueva tabla de asociacion
		if($orden->grupal){
			$codigo_grupo = codigo_grupo();
			foreach($reservas_ids as $rid){
				$CI->db->query("insert into bv_reservas_grupos (reserva_id,codigo_grupo) values (".$rid.",'".$codigo_grupo."')");

				$reserva = $CI->Reserva->get($rid)->row();

				//17-09-18 le asigno la habitacion que pueda para el rooming
				$room = array();
				$room['paquete_id'] = $reserva->paquete_id;
				$room['reserva_id'] = $reserva->id;
				$room['alojamiento_fecha_cupo_id'] = $reserva->fecha_alojamiento_cupo_id;

				if(!$nhab){
					//para definir el numero de habitacion a asignar, obtengo el primero las usadas
					$filt = [];
					$filt['paquete_id'] = $reserva->paquete_id;
					$filt['alojamiento_fecha_cupo_id'] = $reserva->fecha_alojamiento_cupo_id;

					$nhabs = $CI->Paquete_rooming->getWhere($filt)->result();
					$usadas = [];
					foreach ($nhabs as $n) {
						$usadas[] = $n->nro_habitacion;
					}

					//get data de cupo de habitacion
					$datahab = $CI->Fecha_alojamiento_cupo->get($reserva->fecha_alojamiento_cupo_id)->row();

					for ($i=1; $i <= $datahab->cantidad; $i++) {
						if(!in_array($i,$usadas)){
							//si el numero de habitacion i no está en las usadas, uso este
							$nhab = $i;
							break;
						}
					}
				}

				$room['nro_habitacion'] = $nhab;
				$room['observaciones'] = '';

				if($nhab){
					//guardo rooming nuevo
					$CI->Paquete_rooming->insert($room);
				}

			}


		}

		//23-08-18 llamo manualmente al proceso que actualiza cupos luego de efectivizar una nueva reserva
		actualizar_cupos();

		//devuelvo los datos de la reserva del responsable
		if(count($reservas_ids)){
			$res_id = $reservas_ids[0];
			$reserva = $CI->Reserva->get($res_id)->row();
		}
	}

	return $reserva;
}

function codigo_grupo(){
	//me fio si ya hay un grupo con esos chars recien creados
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');

	$code = '';
	while(1){
		$code = genRandomString("alnum",3);
		//me fijo si ya hay reservas con este codigo de grupo
		$row = $CI->Reserva->getReservasActivasGrupo($code,$count=true);
		if(!$row->cantidad){
			//si no hay, salgo de aca y devuelvo el CODE
			break;
		}

		//sigo iterando hasta encontrar un CODE no usado que me sirva
	}

	return $code;
}

//enviar mail al usuario de que se ha liberado un cupo del viaje
function enviar_mail_cupo_liberado($reserva_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');

	$reserva = $CI->Reserva->get($reserva_id)->row();

	if(!isset($reserva->id) || !$reserva->id){
		return true;
	}

	//responsable
	$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 1 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
	$responsable = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->row();

	$data = array();
	$data['reserva'] = $reserva;
	$data['hash_reserva'] = encriptar($reserva->code);

	$html = cargar_mail('cupo_liberado',$data);

	$asunto = 'Se liberó un lugar en '.$reserva->nombre;
	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
}

//enviar mail al usuario que todavia no completo la orden (pre-reserva)
function enviar_datos_orden($orden_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Orden_model','Orden');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Orden_pasajero_model','Orden_pasajero');

	$orden = $CI->Orden->get($orden_id)->row();

	if(!isset($orden->id) || !$orden->id){
		return true;
	}

	$combinacion = $CI->Combinacion->get($orden->combinacion_id)->row();

	//responsable
	$CI->Orden_pasajero->filters = "bv_ordenes_pasajeros.responsable = 1 and bv_ordenes_pasajeros.orden_id = ".$orden_id;
	$responsable = $CI->Orden_pasajero->getAll(999,0,'bv_ordenes_pasajeros.numero_pax','asc')->row();

	//acompañantes
	$CI->Orden_pasajero->filters = "bv_ordenes_pasajeros.responsable = 0 and bv_ordenes_pasajeros.orden_id = ".$orden_id;
	$acompanantes = $CI->Orden_pasajero->getAll(999,0,'bv_ordenes_pasajeros.numero_pax','asc')->result();

	$incompletos = 0;
	foreach($acompanantes as $a){
		if(!$a->completo)
			$incompletos+=1;
	}

	//adicionales
	$adicionales = $CI->Orden->getAdicionales($orden_id);
	$adicionales_valores = array();
	foreach($adicionales as $a){
		$adicionales_valores[$a->paquete_adicional_id] = $a->v_total;
	}

	$data = array();
	$data['orden'] = $orden;
	$data['combinacion'] = $combinacion;
	$data['responsable'] = $responsable;
	$data['acompanantes'] = $acompanantes;
	$data['incompletos'] = $incompletos;

	$html = cargar_mail('datos_orden',$data);

	$asunto = 'Completá tu reserva en Buenas Vibras Viajes';
	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		//envio de email
		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
}

//enviar mail de confirmacion al usuario, se usa cuando se lanza desde listado reservas manualmente
function enviar_mail_confirmacion($reserva_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');

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
	$data['precios'] = $precios;
	$data['incompletos'] = $incompletos;

	$html = '';

	//obtengo monto abonado (segun los pagos que tenga)
	$CI->load->model('Movimiento_model','Movimiento');
	$mov = $CI->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
	$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;
	$pagos_hechos = $precios['num']['monto_abonado'];

	//se lo envio con boton de pago activo
	$data['ocultar_boton_pago'] = false;
	$html = cargar_mail('datos_reserva',$data);

	$asunto = $reserva->nombre.' - Tu reserva en Buenas Vibras Viajes';

	if ($reserva->estado_id != 5) {
		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
	else{
		return true;
	}
}

//enviar mail al usuario segun haya completado o no los datos de pasajeros
function enviar_datos_reserva($reserva_id,$echo=false,$forzar_datos=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');

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
	$data['precios'] = $precios;
	$data['incompletos'] = $incompletos;

	$html = '';

	//Si la reserva es a confirmar, el mail es diferente
	if ($reserva->estado_id == 13) {
		$html = cargar_mail('datos_reserva_a_confirmar', $data);
	}
	else if ($reserva->estado_id == 12) {
		$html = cargar_mail('datos_reserva_lista_espera', $data);
	}
	else {

		//obtengo monto abonado (segun los pagos que tenga)
		$CI->load->model('Movimiento_model','Movimiento');
		$mov = $CI->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
		$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;
		$pagos_hechos = $precios['num']['monto_abonado'];

		//fuerzo datos para "simularle" el mail
		if($forzar_datos){
			$pagos_hechos = 0.00;
			$reserva->estado_id = 1;
			$data['reserva'] = $reserva;
			$precios['monto_abonado'] = $pagos_hechos;
			$precios['saldo_pendiente'] = $precios['precio_total'];
			$data['precios'] = $precios;
		}

		//Si todavia no pago nada lo primero es avisarle que si no paga se cae la reserva
		if ($pagos_hechos == 0) {
			$data['ocultar_boton_pago'] = false;
			$html = cargar_mail('datos_reserva',$data);
		}
		else {
			if(!$reserva->completo_paso3){
				//si no completó el paso 3 de datos de pasajeros, envio mail
				$html = cargar_mail('datos_reserva_faltan_datos',$data);
			}
			else {
				//si ya hizo algun pago, me fijo si ya saldó la reserva o no
				$total_reserva = $precios['num']['precio_total'];

				if($pagos_hechos < $total_reserva){
					if($incompletos || !$responsable->completo){
						//si algun acompañante tiene datos incompletos o tampoco está completo el responsable
						$data['ocultar_boton_pago'] = false;
						$html = cargar_mail('datos_reserva',$data);
					}
					else{
						//datos compeltos, solo resta pagar
						$html = cargar_mail('reserva_confirmada',$data);
					}
				}
				else{
					//pago completo
					$html = cargar_mail('pago_completo',$data);
				}
			}
		}
	}


	if ($reserva->estado_id == 2) {
		$asunto = $reserva->nombre.' - Tu reserva en Buenas Vibras Viajes está por vencer';
	}
	else if ($reserva->estado_id == 13) {
		$asunto = $reserva->nombre.' - Solicitud de reserva recibida';
	}
	else if ($reserva->estado_id == 12) {
		$asunto = $reserva->nombre.' - Solicitud de reserva recibida';
	}
	else {
		$asunto = $reserva->nombre.' - Tu reserva en Buenas Vibras Viajes';
	}

	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		//echo $html = cargar_mail('datos_reserva_a_confirmar',$data);
		echo $html = cargar_mail('datos_reserva',$data);
		//echo $html = cargar_mail('datos_reserva_faltan_datos',$data);
		//echo $html = cargar_mail('reserva_confirmada',$data);
		//echo $html = cargar_mail('pago_completo',$data);
		echo "<br/><hr/>";
	}
	else{
		//envio de email
		if ($reserva->estado_id != 5) {
			return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
		}
		else{
			return true;
		}
	}
}

//enviar mail al usuario segun haya completado o no los datos de pasajeros
function enviar_saldo_pendiente($reserva_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');

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
	$data['precios'] = $precios;
	$data['incompletos'] = $incompletos;

	$html = '';

	//obtengo monto abonado (segun los pagos que tenga)
	$CI->load->model('Movimiento_model','Movimiento');
	$mov = $CI->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
	$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;

	//Si todavia no pago nada lo primero es avisarle que si no paga se cae la reserva
	$html = cargar_mail('saldo_pendiente',$data);

	$asunto = $reserva->nombre.' - Tu reserva tiene un saldo pendiente';


	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		//envio de email
		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
}

//enviar mail al usuario que completo todos los datos
function enviar_datos_completos($reserva_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');

	$reserva = $CI->Reserva->get($reserva_id)->row();

	if(!isset($reserva->id) || !$reserva->id){
		return true;
	}

	$combinacion = $CI->Combinacion->get($reserva->combinacion_id)->row();

	//responsable
	$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 1 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
	$responsable = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->row();

	//solo a fines de q en el mail figuren como completo
	if($echo){
		$responsable->completo = 1;
	}

	//acompañantes
	$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 0 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
	$acompanantes = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();

	$incompletos = 0;
	foreach($acompanantes as $a){
		if(!$a->completo)
			$incompletos+=1;

		//solo a fines de q en el mail figuren como completo
		if($echo){
			$a->completo = 1;
		}
	}

	//solo a fines de q en el mail figuren como completo
	if($echo){
		$incompletos = 0;
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
	$data['precios'] = $precios;
	$data['incompletos'] = $incompletos;

	$html = '';

	//obtengo monto abonado (segun los pagos que tenga)
	$CI->load->model('Movimiento_model','Movimiento');
	$mov = $CI->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
	$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;

	//Si todavia no pago nada lo primero es avisarle que si no paga se cae la reserva
	$html = cargar_mail('datos_completos',$data);

	$asunto = $reserva->nombre.' - Los datos de los pasajeros de tu reserva fueron confirmados';

	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		//envio de email
		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
}

//enviar mail al usuario que no completo todos los datos de los pasajeros aun
function enviar_faltan_datos($reserva_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');

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
	$data['precios'] = $precios;
	$data['incompletos'] = $incompletos;

	$html = '';

	//obtengo monto abonado (segun los pagos que tenga)
	$CI->load->model('Movimiento_model','Movimiento');
	$mov = $CI->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
	$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;

	//Si todavia no pago nada lo primero es avisarle que si no paga se cae la reserva
	$html = cargar_mail('faltan_datos',$data);

	$asunto = $reserva->nombre.' - Faltan datos de pasajeros en tu reserva';


	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		$bcc='';

		//16-08-18 agrego copia a vendedor si la reserva lo tiene
		if($reserva->vendedor_id){
			$CI->load->model('Vendedor_model','Vendedor');
			$vendedor = $CI->Vendedor->get($reserva->vendedor_id)->row();
			if(isset($vendedor->id) && $vendedor->id){
				$bcc .= $vendedor->email;
			}
		}

		//envio de email
		$bcc='';

		//16-08-18 agrego copia a vendedor si la reserva lo tiene
		if($reserva->vendedor_id){
			$CI->load->model('Vendedor_model','Vendedor');
			$vendedor = $CI->Vendedor->get($reserva->vendedor_id)->row();
			if(isset($vendedor->id) && $vendedor->id){
				$bcc .= $vendedor->email;
			}
		}

		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc, $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
}

//enviar mail al usuario de que se ha recibido el INFORME del pago
function enviar_informe_pago_recibido($reserva_id,$informe_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');
	$CI->load->model('Reserva_informe_pago_model','Reserva_informe_pago');
	$CI->load->model('Movimiento_model','Movimiento');

	$reserva = $CI->Reserva->get($reserva_id)->row();

	if(!isset($reserva->id) || !$reserva->id){
		return true;
	}

	$combinacion = $CI->Combinacion->get($reserva->combinacion_id)->row();

	//responsable
	$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 1 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
	$responsable = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->row();

	//adicionales
	$adicionales = $CI->Reserva->getAdicionales($reserva_id);
	$adicionales_valores = array();
	foreach($adicionales as $a){
		$adicionales_valores[$a->paquete_adicional_id] = $a->v_total;
	}

	$precios = calcular_precios_totales($combinacion,$adicionales_valores,$reserva->precio_usd?'USD':'ARS',$reserva->id);

	//obtengo data del informe de pago
	$informe = $CI->Reserva_informe_pago->get($informe_id)->row();

	//le decremento el cupo al paquete
	$data = array();
	$data['reserva'] = $reserva;
	$data['combinacion'] = $combinacion;
	$data['precios'] = $precios;
	$data['informe'] = $informe;
	$data['responsable'] = $responsable;

	$html = cargar_mail('informe_pago_recibido',$data);
	$asunto = 'Recibimos un informe de pago en tu reserva';

	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		//envio de email
		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
}

//enviar mail al usuario de que se ha recibido el pago
function enviar_recepcion_pago($reserva_id,$pago_id,$echo=false){
	$CI =& get_instance();
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

	$mov = $CI->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
	$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;
	$total_reserva = $precios['num']['precio_total'];

	$data = array();
	$data['reserva'] = $reserva;
	$data['combinacion'] = $combinacion;
	$data['precios'] = $precios;
	$data['responsable'] = $responsable;
	$data['acompanantes'] = $acompanantes;
	$data['incompletos'] = $incompletos;

	//el monto abonado lo saco de este array de precios
	//$precios['num']['monto_abonado']
	$pagos_hechos = $precios['num']['monto_abonado'];

	$attachments = false;

	if ($pagos_hechos >= $total_reserva) {
		$data['ocultar_boton_pago'] = TRUE;

		$html = cargar_mail('pago_completo',$data);

		if($pago_id>0){
			//obtengo data del pago
			$pago = $CI->Movimiento->get($pago_id)->row();
			$attachments = array();
			if(isset($pago->comprobante) && $pago->comprobante && file_exists('./data/facturas/'.$pago->comprobante.'.pdf')){
				$attachments[] = './data/facturas/'.$pago->comprobante.'.pdf';
			}
		}
	}
	else {
		//obtengo data del pago
		$pago = $CI->Movimiento->get($pago_id)->row();

		$data['pago'] = $pago;

		$html = cargar_mail('pago_recibido',$data);

		$attachments = array();
		if(isset($pago->comprobante) && $pago->comprobante && file_exists('./data/facturas/'.$pago->comprobante.'.pdf')){
			$attachments[] = './data/facturas/'.$pago->comprobante.'.pdf';
		}
	}

	$asunto = 'Recibimos un pago en tu reserva';

	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html = cargar_mail('pago_recibido',$data);
		echo $html = cargar_mail('pago_completo',$data);
		echo "<br/><hr/>";
	}
	else{
		//si la reserva no está anulada, mando el mail
		if ($reserva->estado_id != 5) {
			return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments, $reply_to='');
		}
		else{
			return true;
		}
	}
}

//enviar mail al usuario de que se ha pagado la totalidad de la reserva
function enviar_pago_completo($reserva_id,$echo=false){
	$CI =& get_instance();
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

	$mov = $CI->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
	$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;
	$total_reserva = $precios['num']['precio_total'];

	$data = array();
	$data['reserva'] = $reserva;
	$data['combinacion'] = $combinacion;
	$data['precios'] = $precios;
	$data['responsable'] = $responsable;
	$data['acompanantes'] = $acompanantes;
	$data['incompletos'] = $incompletos;

	$data['ocultar_boton_pago'] = TRUE;

	$html = cargar_mail('pago_completo',$data);

	$asunto = 'Pago completo de tu reserva';
	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		//si la reserva no está anulada, mando el mail
		if ($reserva->estado_id != 5) {
			return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
		}
		else{
			return true;
		}
	}
}

//enviar mail al usuario de que se ha anulado la reserva
function enviar_reserva_anulada($reserva_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');
	$CI->load->model('Movimiento_model','Movimiento');

	$reserva = $CI->Reserva->get($reserva_id)->row();

	if(!isset($reserva->id) || !$reserva->id){
		return true;
	}

	//responsable
	$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 1 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
	$responsable = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->row();

	$data = array();
	$data['reserva'] = $reserva;

	$html = cargar_mail('reserva_anulada',$data);

	$asunto = 'Tu reserva fue anulada por falta de pago';

	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		return enviarMail('jma.montes87@gmail.com', "jma.montes87@gmail.com", $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
		//return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}

}

//enviar mail al usuario de que se ha anulado la reserva manualmente
function enviar_reserva_anulada_manualmente($reserva_id,$echo=false){
	$CI =& get_instance();
	$CI->load->model('Reserva_model','Reserva');
	$CI->load->model('Combinacion_model','Combinacion');
	$CI->load->model('Reserva_pasajero_model','Reserva_pasajero');
	$CI->load->model('Movimiento_model','Movimiento');

	$reserva = $CI->Reserva->get($reserva_id)->row();

	if(!isset($reserva->id) || !$reserva->id){
		return true;
	}

	//responsable
	$CI->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 1 and bv_reservas_pasajeros.reserva_id = ".$reserva_id;
	$responsable = $CI->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->row();

	$data = array();
	$data['reserva'] = $reserva;

	$html = cargar_mail('reserva_anulada_manual',$data);

	$asunto = 'Tu reserva fue anulada';
	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
	}
}

//enviar mail al usuario con link a los vouchers
function enviar_mail_vouchers($reserva_id,$pago_id=false,$echo=false){
	$CI =& get_instance();
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
	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		if($pago_id){
			//obtengo data del pago
			$pago = $CI->Movimiento->get($pago_id)->row();

			$attachments = array();
			if(isset($pago->comprobante) && $pago->comprobante && file_exists('./data/facturas/'.$pago->comprobante.'.pdf')){
				$attachments[] = './data/facturas/'.$pago->comprobante.'.pdf';
			}
		}

		if($reserva->estado_id != 5){
			return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments, $reply_to='');
		}
		else{
			return true;
		}
	}
}

//enviar mail al usuario de aviso de pago completo
function enviar_mail_pago_completo($reserva_id){
	$CI =& get_instance();
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

		$html = cargar_mail('pago_completo',$data);

		//si la reserva no está anulada, mando el mail
		if ($reserva->estado_id != 5) {
			return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', 'Tu reserva está pagada', $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
		}
		else{
			return true;
		}
}

//enviar mail al usuario de aviso de ajuste de precio
function enviar_ajuste_precio($reserva_id,$echo=false){
	$CI =& get_instance();
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

		$html = cargar_mail('ajuste_precio',$data);

	$asunto='Ajuste de precio en tu reserva';
	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		if ($reserva->estado_id != 5) {
			return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
		}
		else{
			return true;
		}
	}
}

//enviar mail al usuario de aviso de cambios en reserva
function enviar_cambios_reserva($reserva_id,$echo=false){
	$CI =& get_instance();
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

		$html = cargar_mail('cambios_reserva',$data);

	$asunto='Hay cambios en tu reserva';
	if($echo){
		//imprimo el mail en pantalla
		echo $asunto;
		echo "<br/>";
		echo $html;
		echo "<br/><hr/>";
	}
	else{
		if ($reserva->estado_id != 5) {
			return enviarMail('reservas@buenas-vibras.com.ar', $responsable->email, $bcc='', $asunto, $html, 'BUENAS VIBRAS VIAJES', $attachments=FALSE, $reply_to='');
		}
		else{
			return true;
		}
	}
}

function cargar_mail($nombre,$data){
	$CI =& get_instance();

	//si es una reserva, le cargo los nombres de adicionales para que aparezcan en el mail
	if(isset($data['reserva']) && $data['reserva']){
		adicionales_reserva($data['reserva']);
	}

	//cargo datos del viaje generico
	$data['mail_datos_viaje'] = $CI->load->view('mails/mail_datos_viaje',$data,true);

	//datos de sucursal de bs as y rosario para footer
	$CI->load->model('Sucursal_model','Sucursal');
	$CI->Sucursal->filters = "id in (1,2)";
	$data['sucursales'] = $CI->Sucursal->getAll(2,0,'id','asc')->result();

	//cargo footer de mail generico
	$data['mail_footer'] = $CI->load->view('mails/mail_footer',$data,true);

	return $CI->load->view('mails/'.$nombre,$data,true);
}

function nombre_dia($nombre_dia){
	$j = '';
	switch($nombre_dia){
		case 'Sun':
			$j = 'Dom';
		break;
		case 'Mon':
			$j = 'Lun';
		break;
		case 'Tue':
			$j = 'Mar';
		break;
		case 'Wed':
			$j = 'Mie';//Mié
		break;
		case 'Thu':
			$j = 'Jue';
		break;
		case 'Fri':
			$j = 'Vie';
		break;
		case 'Sat':
			$j = 'Sab';//Sáb
		break;
	}
	return utf8_encode($j);
}

function pasajeros($pax){
	$pax = explode(',',$pax);
	if(count($pax) == 1){
		return "x ".$pax[0];
	}
	else{
		return min($pax)." a ".max($pax);
	}
}

function calcular_precios_totales($combinacion,$adicionales,$tipo_moneda=false,$reserva_id=false,$orden_id=false){
	$CI =& get_instance();
	$precio_final_persona = 0.00;
	$precio_bruto = 0.00;
	$precio_impuestos = 0.00;
	$precio_total= 0.00;

	$pax_elegidos = false;
	if($reserva_id){
		$reserva = $CI->Reserva->get($reserva_id)->row();
		$pax_elegidos = $reserva->pasajeros;
		$combinacion->pax = $reserva->pasajeros;
	}
	if($orden_id){
		$orden = $CI->Orden->get($orden_id)->row();
		$combinacion->pax = $orden->pasajeros;
	}

	//bruto del paquete
	$precio_bruto = precio_bruto($combinacion,true,$pax_elegidos);

	//impuestos del paquete
	$precio_impuestos = precio_impuestos($combinacion,true,$pax_elegidos,$reserva);

	//calculo nuevo total con los adicionales
	if(isset($adicionales) && count($adicionales)){
		foreach($adicionales as $paq_adicional_id=>$valor){
			$CI->load->model('Adicional_model','Adicional');
			$a = $CI->Adicional->getAsociacionPaquete($paq_adicional_id);

			$precio_bruto += $combinacion->pax*($a->v_exento+$a->v_nogravado+$a->v_comision+$a->v_gravado21+$a->v_gravado10+$a->v_gastos_admin+$a->v_rgafip);
			$precio_impuestos += $combinacion->pax*($a->v_iva21+$a->v_iva10+$a->v_otros_imp);
		}
	}

	//precio total de la combinacion
	$precio_total = $precio_bruto+$precio_impuestos;

	//precio final por persona
	$precio_final_persona = $precio_total/$combinacion->pax;
	$precio_bruto_persona = $precio_bruto/$combinacion->pax;
	$precio_impuestos_persona = $precio_impuestos/$combinacion->pax;

	//ahora el monto minimo se calcular porque por backend se indica el %
	//$monto_minimo_reserva = ($combinacion->monto_minimo_reserva/100)*$combinacion->pax*$precio_final_persona;

	//nuevamente, el monto minimo de reserva se toma directamente del back
	$monto_minimo_reserva = $combinacion->monto_minimo_reserva*$combinacion->pax;
	$monto_minimo_reserva_persona = $combinacion->monto_minimo_reserva;

	if($reserva_id){
		$monto_minimo_reserva = $combinacion->monto_minimo_reserva*$reserva->pasajeros;
	}
	if($orden_id){
		$monto_minimo_reserva = $combinacion->monto_minimo_reserva*$orden->pasajeros;
	}

	$precio_usd = $combinacion->precio_usd;

	//para viajes individuales
	//if(!$combinacion->grupal){
		//si pide conconversion de cotizacion en ars
		if(isset($tipo_moneda) && $tipo_moneda == 'ARS'){
			if($combinacion->precio_usd){
				$precio_final_persona = $precio_final_persona*$CI->settings->cotizacion_dolar;
				$precio_bruto_persona = $precio_bruto_persona*$CI->settings->cotizacion_dolar;
				$precio_impuestos_persona = $precio_impuestos_persona*$CI->settings->cotizacion_dolar;
				$precio_bruto = $precio_bruto*$CI->settings->cotizacion_dolar;
				$precio_impuestos = $precio_impuestos*$CI->settings->cotizacion_dolar;
				$precio_total = $precio_total*$CI->settings->cotizacion_dolar;
				$monto_minimo_reserva = $monto_minimo_reserva*$CI->settings->cotizacion_dolar;
				$monto_minimo_reserva_persona = $monto_minimo_reserva_persona*$CI->settings->cotizacion_dolar;

				$precio_usd = false;//ars
			}
			else{
				$precio_usd = false;//ars
			}
		}
		else if(isset($tipo_moneda) && $tipo_moneda == 'USD'){
			//25-02-19 SI PIDO EL PRECIO EN USD
			if(!$combinacion->precio_usd){
				//y el viaje es en ARS, conversion
				$precio_final_persona = $precio_final_persona/$CI->settings->cotizacion_dolar;
				$precio_bruto_persona = $precio_bruto_persona/$CI->settings->cotizacion_dolar;
				$precio_impuestos_persona = $precio_impuestos_persona/$CI->settings->cotizacion_dolar;
				$precio_bruto = $precio_bruto/$CI->settings->cotizacion_dolar;
				$precio_impuestos = $precio_impuestos/$CI->settings->cotizacion_dolar;
				$precio_total = $precio_total/$CI->settings->cotizacion_dolar;
				$monto_minimo_reserva = $monto_minimo_reserva/$CI->settings->cotizacion_dolar;
				$monto_minimo_reserva_persona = $monto_minimo_reserva_persona/$CI->settings->cotizacion_dolar;

				$precio_usd = true;//usd
			}
			else{
				$precio_usd = true;//usd
			}
		}
		else{
			$precio_usd = true;//usd
		}
	//}

	$ret = array();
	$ret['precio_final_persona'] = precio($precio_final_persona,$precio_usd);
	$ret['precio_bruto_persona'] = precio($precio_bruto_persona,$precio_usd,false);
	$ret['precio_impuestos_persona'] = precio($precio_impuestos_persona,$precio_usd,false);
	$ret['precio_bruto'] = precio($precio_bruto,$precio_usd,false);
	$ret['precio_impuestos'] = precio($precio_impuestos,$precio_usd,false);
	$ret['precio_total'] = precio($precio_total,$precio_usd,true,true);
	$ret['monto_minimo_reserva'] = precio($monto_minimo_reserva,$precio_usd,true,true);
	$ret['monto_minimo_reserva_persona'] = precio($monto_minimo_reserva_persona,$precio_usd,true,true);

	$monto_abonado = 0.00;

	if($reserva_id){
		$reserva = $CI->Reserva->get($reserva_id)->row();

		//obtengo monto abonado (segun los pagos que tenga)
		$CI->load->model('Movimiento_model','Movimiento');
		$mov = $CI->Movimiento->getPagosHechos($reserva_id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
		$monto_abonado = $mov->pago_hecho>0?$mov->pago_hecho:0.00;

		//if($_SERVER['REMOTE_ADDR'] == '190.18.187.8'){
			//12-06-18 lo obtengo en base al saldo de la reserva
			$saldo = $CI->Reserva->getSaldoReserva($reserva_id);
			$saldo_pendiente = ($reserva->precio_usd) ? $saldo->saldo_usd : $saldo->saldo;
			$monto_abonado = $precio_total - $saldo_pendiente;

			//para que no haya lio en los mails de queviaje saldo negativo
			if($saldo_pendiente < 0){
				$saldo_pendiente = 0;
				$monto_abonado = $precio_total;
			}

			if($monto_abonado < 0){
				$monto_abonado = 0;
				$saldo_pendiente = $precio_total;
			}
		//}
	}

	$ret['monto_abonado'] = precio($monto_abonado,$combinacion->precio_usd,true,true);//en pesos lo manda

	//calculo saldo pendiente de pago
	$ret['saldo_pendiente'] = precio($precio_total-$monto_abonado,$combinacion->precio_usd,true,true);

	//------ MONTOS NUMERICOS ------
	$num = array();
	$num['precio_final_persona'] = precio($precio_final_persona,$precio_usd,true,false,true);
	$num['precio_bruto_persona'] = precio($precio_bruto_persona,$precio_usd,false,false,true);
	$num['precio_impuestos_persona'] = precio($precio_impuestos_persona,$precio_usd,false,false,true);
	$num['precio_bruto'] = precio($precio_bruto,$precio_usd,false,false,true);
	$num['precio_impuestos'] = precio($precio_impuestos,$precio_usd,false,false,true);
	$num['precio_total'] = precio($precio_total,$precio_usd,true,true,true);
	$num['monto_minimo_reserva'] = precio($monto_minimo_reserva,$precio_usd,true,true,true);
	$num['monto_minimo_reserva_persona'] = precio($monto_minimo_reserva_persona,$precio_usd,true,true,true);
	$num['monto_abonado'] = precio($monto_abonado,$precio_usd,true,true,true);
	$num['saldo_pendiente'] = precio($precio_total-$monto_abonado,$precio_usd,true,true,true);
	//---------------------------------

	$ret['num'] = $num;

	$ret['success'] = true;

	return $ret;
}

function montos_numericos($combinacion,$adicionales,$tipo_moneda=false,$reserva_id=false){
	$CI =& get_instance();
	$precio_final_persona = 0.00;
	$precio_bruto = 0.00;
	$precio_impuestos = 0.00;
	$precio_total= 0.00;

	$pax_elegidos = false;
	if($reserva_id){
		$reserva = $CI->Reserva->get($reserva_id)->row();
		$pax_elegidos = $reserva->pasajeros;
	}

	//bruto del paquete
	$precio_bruto = precio_bruto($combinacion,true,$pax_elegidos);

	//impuestos del paquete
	$precio_impuestos = precio_impuestos($combinacion,true);

	//calculo nuevo total con los adicionales
	if(isset($adicionales) && count($adicionales)){
		foreach($adicionales as $paq_adicional_id=>$valor){
			$CI->load->model('Adicional_model','Adicional');
			$a = $CI->Adicional->getAsociacionPaquete($paq_adicional_id);
			$precio_bruto += $combinacion->pax*($a->v_exento+$a->v_nogravado+$a->v_comision+$a->v_gravado21+$a->v_gravado10+$a->v_gastos_admin+$a->v_rgafip);
			$precio_impuestos += $combinacion->pax*($a->v_iva21+$a->v_iva10+$a->v_otros_imp);
		}
	}

	//precio total de la combinacion
	$precio_total = $precio_bruto+$precio_impuestos;

	//precio final por persona
	$precio_final_persona = $precio_total/$combinacion->pax;
	$precio_bruto_persona = $precio_bruto/$combinacion->pax;
	$precio_impuestos_persona = $precio_impuestos/$combinacion->pax;

	//para viajes individuales
	if(!$combinacion->grupal){
		//si pide con cotizacion en dolares
		if(isset($tipo_moneda) && $tipo_moneda == 'USD'){
			$precio_final_persona = $precio_final_persona*$CI->settings->cotizacion_dolar;
			$precio_bruto_persona = $precio_bruto_persona*$CI->settings->cotizacion_dolar;
			$precio_impuestos_persona = $precio_impuestos_persona*$CI->settings->cotizacion_dolar;
			$precio_bruto = $precio_bruto*$CI->settings->cotizacion_dolar;
			$precio_impuestos = $precio_impuestos*$CI->settings->cotizacion_dolar;
			$precio_total = $precio_total*$CI->settings->cotizacion_dolar;
		}
	}

	$ret = array();
	$ret['precio_final_persona'] = precio($precio_final_persona,$combinacion->precio_usd,true,false,true);
	$ret['precio_bruto_persona'] = precio($precio_bruto_persona,$combinacion->precio_usd,false,false,true);
	$ret['precio_impuestos_persona'] = precio($precio_impuestos_persona,$combinacion->precio_usd,false,false,true);
	$ret['precio_bruto'] = precio($precio_bruto,$combinacion->precio_usd,false,false,true);
	$ret['precio_impuestos'] = precio($precio_impuestos,$combinacion->precio_usd,false,false,true);
	$ret['precio_total'] = precio($precio_total,$combinacion->precio_usd,true,true,true);

	//ahora el monto minimo se calcular porque por backend se indica el %
	//$monto_minimo_reserva = ($combinacion->monto_minimo_reserva/100)*$combinacion->pax*$precio_final_persona;

	//nuevamente el monto minimo se toma del backend
	$monto_minimo_reserva = $combinacion->monto_minimo_reserva*$combinacion->pax;

	if($reserva_id){
		$monto_minimo_reserva = $combinacion->monto_minimo_reserva*$reserva->pasajeros;
	}

	$ret['monto_minimo_reserva'] = precio($monto_minimo_reserva,$combinacion->precio_usd,true,true,true);

	$monto_minimo_reserva_persona = $combinacion->monto_minimo_reserva;
	$ret['monto_minimo_reserva_persona'] = precio($monto_minimo_reserva_persona,$combinacion->precio_usd,true,true,true);

	//obtengo monto abonado (segun los pagos que tenga)
	$monto_abonado = 0.00;

	if($reserva_id){
		$reserva = $CI->Reserva->get($reserva_id)->row();

		//obtengo monto abonado (segun los pagos que tenga)
		/*$CI->load->model('Movimiento_model','Movimiento');
		$mov = $CI->Movimiento->getPagosHechos($reserva_id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
		$monto_abonado = $mov->pago_hecho>0?$mov->pago_hecho:0.00;*/

		//obtengo monto abonado (segun los pagos que tenga)
		$CI->load->model('Movimiento_model','Movimiento');
		$mov = $CI->Movimiento->getPagosHechos($reserva_id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
		$monto_abonado = $mov->pago_hecho>0?$mov->pago_hecho:0.00;

		//12-06-18 lo obtengo en base al saldo de la reserva
		$saldo = $CI->Reserva->getSaldoReserva($reserva_id);
		$saldo_pendiente = ($reserva->precio_usd) ? $saldo->saldo_usd : $saldo->saldo;
		$monto_abonado = $precio_total - $saldo_pendiente;

		//para que no haya lio en los mails de queviaje saldo negativo
		if($saldo_pendiente < 0){
			$saldo_pendiente = 0;
			$monto_abonado = $precio_total;
		}

		if($monto_abonado < 0){
			$monto_abonado = 0;
			$saldo_pendiente = $precio_total;
		}

	}

	$ret['monto_abonado'] = precio($monto_abonado,$combinacion->precio_usd,true,true,true);//en pesos lo manda

	//calculo saldo pendiente de pago
	$ret['saldo_pendiente'] = precio($precio_total-$monto_abonado,$combinacion->precio_usd,false,false,true);

	$ret['success'] = true;

	return $ret;
}

//devuelve el precio  formateado
function precio($precio,$precio_usd,$bold=true,$alternate=false,$numeric=false){
	$precio = number_format($precio,2,'.','');

	if($numeric){
		return $precio;

	}
	else{
		/*
		<p><strong>ARS 14.500,00</strong> <span>+ 2.000,00 imp.</span></p>
		*/
		$precio = explode('.',$precio);
		$precio[0] = number_format($precio[0],0,'','.');
		if($bold)
			if($alternate)
				return "<span><strong>".($precio_usd?'USD':'ARS')." ".$precio[0].",".$precio[1]."</strong></span>";
			else
				return "<strong><span>".($precio_usd?'USD':'ARS')."</span> ".$precio[0].",".$precio[1]."</strong>";
		else
			return "<span>".($precio_usd?'USD':'ARS')." ".$precio[0].",".$precio[1]."</span>";
	}
}

//devuelve el valor del viaje sin impuestos
//el tercer parametro se usa para saber la cantidad de pasajeros al reservar en forma grupal
function precio_bruto($comb,$numeric=false,$pasajeros=false){
	$precio = $comb->v_exento+$comb->v_nogravado+$comb->v_comision+$comb->v_gravado21+$comb->v_gravado10+$comb->v_gastos_admin+$comb->v_rgafip;
	$precio = $precio*($pasajeros?$pasajeros:$comb->pax);
	$precio = number_format($precio,2,'.','');

	if($numeric){
		return $precio;
	}
	else{
		$precio = explode('.',$precio);
		$precio[0] = number_format($precio[0],0,'','.');
		return "<span>".($comb->precio_usd?'USD':'ARS')." ".$precio[0].",".$precio[1]."</span>";
	}
}

//devuelve el valor de impuestos del viaje
function precio_impuestos($comb,$numeric=false,$pasajeros=false,$reserva=false){
	$CI =& get_instance();
	$CI->load->model('Paquete_model','Paquete');
	$paquete = $CI->Paquete->get($comb->paquete_id)->row();

	$CI->load->model('Config_model');
	$conf = $CI->Config_model->get(1)->row();

	$precio = $comb->v_iva21+$comb->v_iva10+$comb->v_otros_imp;
	if(isset($paquete->impuesto_pais) && $paquete->impuesto_pais && $paquete->exterior){
		//el precio del impuesto se lo sumo solo para las reservas posteriores a la fecha de vigencia de la ley
		if(isset($reserva) && isset($reserva->id)){
			if($CI->config->item('vigencia_impuesto_pais') <= $reserva->fecha_reserva){
				$precio += $paquete->impuesto_pais;
			}
		}
		else{
			//si la reserva no existe, muestro precio actualizado
			$precio += $paquete->impuesto_pais;
		}
	}
	$precio = $precio*($pasajeros?$pasajeros:$comb->pax);
	$precio = number_format($precio,2,'.','');

	if($numeric){
		return $precio;
	}
	else{
		$precio = explode('.',$precio);
		$precio[0] = number_format($precio[0],0,'','.');
		return "<span>".($comb->precio_usd?'USD':'ARS')." ".$precio[0].",".$precio[1]."</span>";
	}
}

//devuelve el valor de impuestos del viaje sin la moneda
function precio_impuestos_clean($impuestos,$numeric=false,$pasajeros=false){
	$precio = $impuestos;
	$precio = number_format($precio,2,'.','');

	if($numeric){
		return $precio;
	}
	else{
		$precio = explode('.',$precio);
		$precio[0] = number_format($precio[0],0,'','.');
		return $precio[0].",".$precio[1]."";
	}
}

/* devuelve fecha formateada con numero de dia, 3 iniciales del mes y año */
function fecha_completa($fecha_inicio,$fecha_fin=false){
	$fecha_inicio = explode('/',$fecha_inicio);
	$fecha_inicio = $fecha_inicio[2].'-'.$fecha_inicio[1].'-'.$fecha_inicio[0];

	if($fecha_fin){
		$fecha_fin = explode('/',$fecha_fin);
		$fecha_fin = $fecha_fin[2].'-'.$fecha_fin[1].'-'.$fecha_fin[0];
	}

	$ndia = strftime("%a",strtotime($fecha_inicio));
	$ndia = nombre_dia($ndia);
	$dia = date('d',strtotime($fecha_inicio));
	$mes = date('m',strtotime($fecha_inicio));
	//$mes = strtolower(substr(monthName($mes),0,3));

	//$inicio = ucfirst($ndia).' '.$dia.' '.$mes;
	$inicio = ucfirst($ndia).' '.$dia.'/'.$mes;

	if($fecha_fin){
		$ndia = strftime("%a",strtotime($fecha_fin));
		$ndia = nombre_dia($ndia);
		$dia = date('d',strtotime($fecha_fin));
		$mes = date('m',strtotime($fecha_fin));
		//$mes = strtolower(substr(monthName($mes),0,3));
		//$fin = ucfirst($ndia).' '.$dia.' '.$mes;
		$fin = ucfirst($ndia).' '.$dia.'/'.$mes;
	}

	if($fecha_fin)
		return $inicio.' a '.$fin;
	else
		return $inicio;
}

/* devuelve fecha formateada con numero de dia y 3 iniciales del mes */
function fecha_corta($fecha){
	$dia = date('d',strtotime($fecha));
	$mes = date('m',strtotime($fecha));
	$mes = strtolower(substr(monthName($mes),0,3));

	return $dia.' '.$mes;
}

/* devuelve la data del contenido, sea destino o categoria, para mostrar en front */
function contenido($row,$clases,$mostrar_tipo=true,$es_destino=true){
	$CI =& get_instance();
	$data['row'] = $row;
	$data['clases'] = $clases;
	$data['mostrar_tipo'] = $mostrar_tipo;
	$data['es_destino'] = $es_destino;
	return $CI->load->view('contenido',$data,true);
}

function formato_fecha($fecha,$en=false){
	if($en){
		$fecha = explode('/',$fecha);
		return $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
	}
	else{
		return date('d/m/Y',strtotime($fecha));
	}
}

function combinations($aloj,$habs,$regs,$luga) {
	$result = array();

	foreach($aloj as $a){

		foreach($habs as $b){

			foreach($regs as $c){

				foreach($luga as $d){
					//si coinciden los alojamientos y los regimenes por hab
					if($a[0] == @$b[2] && @$c[2] == @$b[2] && @$a[2] == @$c[3] && @$a[2] == @$b[3]){
						$res = array();
						$res[] = $a[0];//alojamiento_id
						$res[] = $a[1];//transporte_id
						$res[] = $a[2];//fecha_alojamiento_id
						$res[] = $a[3];//fecha_transporte_id
						$res[] = @$b[0];//fecha_alojamiento_cupo_id
						$res[] = @$b[1];//habitacion_id
						$res[] = $c[0];//paquete_regimen_id
						$res[] = $c[1];//regimen_id
						$res[] = is_array($d) ? $d[0] : @$d;//lugar_id

						$result[] = $res;
					}
				}
			}
		}
	}

    return $result;
}

/* devuelve una fila de datos del voucher asociado a la reserva */
function vouchers_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/vouchers_row',$data,true);
}

/* devuelve una fila de datos del alojamiento asociado al paquete */
function alojamiento_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/alojamiento_row',$data,true);
}


/* devuelve una fila de datos de la habitacion asociada al paquete */
function habitacion_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/habitacion_row',$data,true);
}

/* devuelve una fila de datos de la fecha asociada al transporte */
function fechas_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/fechas_row',$data,true);
}

/* devuelve una fila de datos de la fecha asociada al alojamiento */
function fechas_alojamiento_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/fechas_alojamiento_row',$data,true);
}

/* devuelve una fila de datos del cupo de la fecha asociada al alojamiento */
function fechas_alojamiento_cupo_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/fechas_alojamiento_cupo_row',$data,true);
}

/* devuelve una fila de datos del regimen asociado al paquete */
function regimen_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/regimen_row',$data,true);
}

/* devuelve una fila de datos del adicional asociado al paquete */
function adicional_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/adicional_row',$data,true);
}

/* devuelve una fila de datos de la parada asociada al paquete */
function paradas_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/paradas_row',$data,true);
}

/* devuelve una fila de datos de la combinacion creada */
function combinacion_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/combinacion_row',$data,true);
}

/* devuelve una fila de datos de la combinacion para elegir en cambio de micro */
function cambio_micro_row($row){
	$CI =& get_instance();
	$data['row'] = $row;
	return $CI->load->view('admin/cambio_micro_row',$data,true);
}

function parse_menu_content($contenido) {
	$regex = '#\{\{(.*?)\}\}#';
	if (preg_match_all($regex, $contenido, $matches)) {
		foreach ($matches[1] as $widget) {
			$call = '$value = '.$widget.'();';
			eval($call);
			$contenido = str_replace('{{'.$widget.'}}', $value, $contenido);
		}
	}
	return $contenido;
}

function imagen($seccion, $id, $archivo, $ancho, $alto) {
	$name = pathinfo($archivo, PATHINFO_FILENAME);
	$ext = pathinfo($archivo, PATHINFO_EXTENSION);

	if ($id == 0) {
		$path = 'uploads/temp/'.$name.'_'.$ancho.'x'.$alto.'.'.$ext;
	}
	else {
		$path = 'uploads/'.$seccion.'/'.$id.'/'.$name.'_'.$ancho.'x'.$alto.'.'.$ext;
	}

	if (!file_exists('./'.$path))
		return false;
	else
		return base_url().$path;
}

function parse_widget($html) {
	$CI =& get_instance();
	foreach ($CI->vars as $var=>$value) {
		$html = str_replace($var, $value, $html);
	}
	$html = str_replace('{{lang}}', $CI->config->item('language'), $html);

	return $html;
}

function static_url($url) {
	$CI =& get_instance();
	return $CI->config->item('static_url').$url;
}

function status($status) {
	switch ($status) {
		case 0: return ""; break;
		case 1: return "approved"; break;
		case 2: return "failed"; break;
		case 3: return "pending"; break;
	}
}

function esAdmin() {
	$CI =& get_instance();
	return $CI->session->userdata('es_admin');
}

function esVendedor() {
	$CI =& get_instance();
	return $CI->session->userdata('es_vendedor');
}

function esGuest() {
	$CI =& get_instance();
	return $CI->session->userdata('es_guest');
}

function esMarca() {
	$CI =& get_instance();
	return $CI->session->userdata('es_marca');
}

function esAuspiciante() {
	$CI =& get_instance();
	return $CI->session->userdata('es_auspiciante');
}

function estado_usuario($status,$label=false,$id=''){
	$CI =& get_instance();

	$estados = $CI->config->item('estados');
	$estado = $estados[$status];

	if($status == 'invitado'){
		$label_class = 'label-warning';
	}
	elseif($status == 'a_confirmar'){
		$label_class = 'label-primary';
	}
	elseif($status == 'confirmado'){
		$label_class = 'label-success';
	}

	if($label)
		return '<label id="estado_usuario_'.$id.'" class="label '.$label_class.'">'.$estado.'</label>';
	else
		return $estado;
}

function estado_revision($status,$label=true,$big_size=false){
	$CI =& get_instance();

	$label_class = "";
	$estado = "";

	if($status == '0'){
		$label_class = 'label-info';
		$estado = 'Pendiente';
	}
	elseif($status == '1'){
		$label_class = 'label-success';
		$estado = 'Aprobado';
	}
	elseif($status == '2'){
		$label_class = 'label-danger';
		$estado = 'Rechazado';
	}

	$styles = "";
	if($big_size){
		$styles = "padding: 10px 15px; font-size: 16px;";
	}

	if($label)
		return '<label class="label '.$label_class.'" style="'.$styles.'">'.$estado.'</label>';
	else
		return $estado;
}

function ordenar_espiral($videos){
	$cantidad = count($videos);
	$resultados = array();

	/*
	for($c=0; $c<$cantidad ;$c++){
		$resultados[$c] = $cantidad-$c;
	}
	*/

	//armo array con los videos al reves
	$c=1;
	foreach($videos as $vid){
		$resultados[$cantidad-$c] = $vid;
		$c++;
	}

	ksort($resultados);

	//array que tendra orden espiral
	$spiralArray = array();

	$dimension = ceil(sqrt($cantidad));
    $numConcentricSquares = ceil(($dimension) / 2.0);
    $sideLen = $dimension;
    $currNum = 0;

	//relleno el array con los que faltan al principio
	for($i=0;$i<(($dimension*$dimension)-$cantidad);$i++){
		array_unshift($resultados, "vacio");
	}

    for ($i = 0; $i < $numConcentricSquares; $i++) {
      // do top side
      for ($j = 0; $j < $sideLen; $j++) {
        //$spiralArray[$i][$i + $j] = $currNum++;
        $spiralArray[$i][$i + $j] = $resultados[$currNum++];
      }

      // do right side
      for ($j = 1; $j < $sideLen; $j++) {
        //$spiralArray[$i + $j][$dimension - 1 - $i] = $currNum++;
        $spiralArray[$i + $j][$dimension - 1 - $i] = $resultados[$currNum++];
      }

      // do bottom side
      for ($j = $sideLen - 2; $j > -1; $j--) {
        //$spiralArray[$dimension - 1 - $i][$i + $j] = $currNum++;
        $spiralArray[$dimension - 1 - $i][$i + $j] = $resultados[$currNum++];
      }

      // do left side
      for ($j = $sideLen - 2; $j > 0; $j--) {
        //$spiralArray[$i + $j][$i] = $currNum++;
        $spiralArray[$i + $j][$i] = $resultados[$currNum++];
      }

      $sideLen -= 2;
    }

	return $spiralArray;
}

function vimeo_thumb($id) {
    $data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
    $data = json_decode($data);

    return $data[0]->thumbnail_medium;
}

//default para player de front
function vimeo_player($url,$w=1170,$h=658,$margin='3%'){
	$params = explode('/',$url);
	$video_id = $params[count($params)-1];

	return '<div style="margin:'.$margin.';" class="embed-container"><iframe src="//player.vimeo.com/video/'.$video_id.'?title=0&byline=0&;portrait=0" width="'.$w.'" height="'.$h.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
}

function youtube_embed_url($url) {
	return 'https://www.youtube.com/embed/'.youtube_id($url);
}

function youtube_id($url) {
	parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
	if (isset($my_array_of_vars['v'])) {
		return $my_array_of_vars['v'];
	}
	else
		return "";
}

function admin_username(){
	$CI =& get_instance();
	return ($CI->session->userdata('usuario')) ? $CI->session->userdata('usuario') : '';
}

function date_diff_years($date1,$date2){
	$diff = abs(strtotime($date2) - strtotime($date1));
	$years = floor($diff / (365*60*60*24));
	return $years;
}

function dias_viaje($date1,$date2){
	$diff = abs(strtotime($date2) - strtotime($date1));
	$d = floor($diff / (60*60*24));
	$d+=1;//sumo 1 dia mas para la esatadia
	return $d;
}

function noches_viaje($date1,$date2){
	$diff = abs(strtotime($date2) - strtotime($date1));
	$d = floor($diff / (60*60*24));
	return $d;
}

function getSession() {
	$CI =& get_instance();
	$id = $CI->session->userdata('id');
	if ($id) {
		$CI->id = $CI->session->userdata('id');
		$CI->nombre = $CI->session->userdata('nombre');
		$CI->email = $CI->session->userdata('email');
	}
}

function login($id, $nombre, $email) {
	$CI =& get_instance();
	$CI->session->set_userdata('id', $id);
	$CI->session->set_userdata('nombre', $nombre);
	$CI->session->set_userdata('email', $email);
}

function logout() {
	$CI =& get_instance();
	$CI->session->sess_destroy();
}

function userloggedId(){
	$CI =& get_instance();
	if( $CI->session->userdata('admin_id') ){
		return $CI->session->userdata('admin_id');
	}
	else
		return false;
}

function islogged(){
	$CI =& get_instance();
	if( $CI->session->userdata('user_id') ){
		$CI->load->model('MUsuario');
		$miembro = $CI->MUsuario->get($CI->session->userdata('user_id'))->row();
		if(!empty($miembro))
			return true;
		else
			return false;
	}
	else
		return false;
}

function nombre_usuario(){
	$CI =& get_instance();
	if( $CI->session->userdata('user_id') ){
		$CI->load->model('MUsuario');
		$miembro = $CI->MUsuario->get($CI->session->userdata('user_id'))->row();
		return $miembro->nombre.' '.$miembro->apellido;
	}
	else
		return '';
}

function formatear_minutos($hora){
	$horas = explode(':',$hora);

	if($horas[0] > 0)
		return $horas[0].' horas '.$horas[1].' minutos';
	else
		return $horas[1].' minutos';
}
/*
//CON SENDGRID
function enviarMail($from,$to,$bcc='',$asunto,$mensaje,$nombre='',$attachments=FALSE, $reply_to=''){
	$CI =& get_instance();
	$CI->load->library('email');

	$CI->email->initialize(
		array(
			'protocol' => $CI->config->item('protocol'),
			'smtp_host' => $CI->config->item('smtp_host'),
			'smtp_user' => $CI->config->item('smtp_user'),
			'smtp_pass' => $CI->config->item('smtp_pass'),
			'smtp_port' => $CI->config->item('smtp_port'),
			'charset' => $CI->config->item('charset'),
			'wordwrap' => $CI->config->item('wordwrap'),
			'mailtype' => $CI->config->item('mailtype')
		)
	);

	$CI->email->set_newline("\r\n");
	$CI->email->clear(TRUE);

	$CI->email->to($to);
	if($bcc != "")
		$CI->email->bcc($bcc);

	if ($reply_to) {
		$CI->email->reply_to($reply_to);
	}
	$CI->email->from($from, $nombre);
	$CI->email->subject($asunto);
	$CI->email->message($mensaje);

	if ($attachments) {
		if (is_array($attachments)) {
			foreach ($attachments as $attach) {
				$CI->email->attach($attach);
			}
		}
		else {
			$CI->email->attach($attachments);
		}
	}

	if( $CI->email->send() ){
		return TRUE;
	}else {
		return FALSE; //$CI->email->print_debugger();
	}

}*/

function enviarMail($from,$to,$bcc='',$asunto,$mensaje,$nombre='',$attachments=FALSE, $reply_to=''){
	$CI =& get_instance();
	$CI->load->library('email');
	$config['charset'] = 'utf-8';
	$config['wordwrap'] = TRUE;
	$config['mailtype'] = "html";
	$config['protocol'] = 'smtp';
	$config['smtp_host'] = 'smtp.sendgrid.net';
	$config['smtp_user'] = 'apikey';
	$config['smtp_pass'] = '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';
	$config['smtp_port'] = 587;
	
	$CI->email->initialize($config);
	$CI->email->set_newline("\r\n");

	$CI->email->clear(TRUE);

	$CI->email->to($to);
	if($bcc != "")
		$CI->email->bcc($bcc);

	if ($reply_to) {
		$CI->email->reply_to($reply_to);
	}
	$CI->email->from($from, $nombre);
	$CI->email->subject($asunto);
	$CI->email->message($mensaje);

	if ($attachments) {
		if (is_array($attachments)) {
			foreach ($attachments as $attach) {
				$CI->email->attach($attach);
			}
		}
		else {
			$CI->email->attach($attachments);
		}
	}

	if( $CI->email->send() )
		return TRUE;
	else {
		return FALSE; //$CI->email->print_debugger();
	}

}

function formatearFecha($fecha){
    $fecha_actual = date('Y-m-d H:i:s',time());

    //obtengo diferencia en dias del comentario a la fecha
    $diff = abs(strtotime($fecha_actual) - strtotime($fecha));
    $diff_dias = round($diff/(60*60*24));
    $diff_horas =  round($diff/(60*60));
    $diff_minutos = round($diff/60);
    $diff_segundos = round($diff);

    if($diff_dias == 0){ //si es de hace menos de una semana, "Hace 3 horas", "Hace 2 días", etc.
            if($diff_horas == 0){
                if($diff_minutos == 0){
                    if($diff_segundos == 1){
                        return 'Hace '.$diff_segundos.' segundo';
                    }else{
                        return 'Hace '.$diff_segundos.' segundos';
                    }
                }else{
                    if($diff_minutos == 1){
                        return 'Hace '.$diff_minutos.' minuto';
                    }else{
                        return 'Hace '.$diff_minutos.' minutos';
                    }
                }
            }else{
                if($diff_horas == 1){
                        return 'Hace '.$diff_horas.' hora';
                }else{
                        return 'Hace '.$diff_horas.' horas';
                }
            }
    }elseif($diff_dias == 1){
        return 'Hace '.$diff_dias.' d&iacute;a';
    }else{
        return 'Hace '.$diff_dias.' d&iacute;as';
    }
}

function formatearColorvital($texto){
    return str_replace('#colorvital','<strong>#colorvital</strong>',$texto);
}

function formatearLinks($texto){
    return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target='blank'>\\0</a>", $texto);
}

function formatearFechaBlog($fecha){
    $dia = date('d',strtotime($fecha));
    $mes = date('m',strtotime($fecha));
    $anio = date('Y',strtotime($fecha));
    $horas = date('H',strtotime($fecha));
    $minutos = date('m',strtotime($fecha));
    return "$dia/$mes/$anio";
}

function obtenerCombinaciones($set){

    $size = count($set) - 1;
    $perm = range(0, $size);
    $j = 0;

    do {
        foreach ($perm as $i) { $perms[$j][] = $set[$i]; }
    } while ($perm = pc_next_permutation($perm, $size) and ++$j);

    $k = 0;
    foreach ($perms as $p) {
        $combinaciones[$k++] = join('', $p);
    }
    return $combinaciones;
}

function pc_next_permutation($p, $size) {
        // slide down the array looking for where we're smaller than the next guy
        for ($i = $size - 1; $p[$i] >= $p[$i+1]; --$i) { }

        // if this doesn't occur, we've finished our permutations
        // the array is reversed: (1, 2, 3, 4) => (4, 3, 2, 1)
        if ($i == -1) { return false; }

        // slide down the array looking for a bigger number than what we found before
        for ($j = $size; $p[$j] <= $p[$i]; --$j) { }

        // swap them
        $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;

        // now reverse the elements in between by swapping the ends
        for (++$i, $j = $size; $i < $j; ++$i, --$j) {
             $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;
        }

        return $p;
}

function currentUser() {
	return wp_get_current_user();
}

//funcion que recibe datos de un model y exporta el excel.
function exportExcel($results,$filename='archivo'){
    //results debe llegar como ->result() para que funcione

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=".$filename.".xls");

    echo "<table>
        <tr>";

    $i = 1;
    //nombre de columnas
    foreach($results as $pos=>$rows){
        foreach($rows as $key=>$val){
            echo "<td style='font-weight:bold; border:.6px solid #000000; text-align:center;'>".strtoupper($key)."</td>";
        }
        if($i==1) break;
    }

    echo "</tr>";

    foreach($results as $pos=>$rows){
          echo "<tr>";
            foreach($rows as $key=>$val){
                echo "<td style='border:.6px solid #000000; text-align:center;'>".utf8_decode($val)."</td>";
            }
            echo "</tr>";
    }
    echo "</table>";
}

function monthName($m) {
	switch ($m) {
		case '01': return "Enero"; break;
		case '02': return "Febrero"; break;
		case '03': return "Marzo"; break;
		case '04': return "Abril"; break;
		case '05': return "Mayo"; break;
		case '06': return "Junio"; break;
		case '07': return "Julio"; break;
		case '08': return "Agosto"; break;
		case '09': return "Septiembre"; break;
		case '10': return "Octubre"; break;
		case '11': return "Noviembre"; break;
		case '12': return "Diciembre"; break;
	}
}
/*
Funcion que devuelve las 3 primeras letras del nombre del mes
*/
function nombre_mes($mes,$format="%b"){
	setlocale(LC_TIME, 'spanish');
	$nombre=strftime($format,mktime(0, 0, 0, $mes, 1, 2000));
	return $nombre;
}

function zerofill($entero, $largo){
    // Limpiamos por si se encontraran errores de tipo en las variables
    $entero = (int)$entero;
    $largo = (int)$largo;

    $relleno = '';

    /**
     * Determinamos la cantidad de caracteres utilizados por $entero
     * Si este valor es mayor o igual que $largo, devolvemos el $entero
     * De lo contrario, rellenamos con ceros a la izquierda del número
     **/
    if (strlen($entero) < $largo) {
        $relleno = str_repeat('0',$largo-strlen($entero));
    }
    return $relleno . $entero;
}

function genRandomString($type="alnum",$length = 10) {
    return random_string($type,$length);
}

function encriptar($code){
	$CI =& get_instance();
	//return base64_encode($code.'|'.md5($CI->config->item('encryption_key')));
	//17-10-18
	//return utf8_encode($code.'134');

	//cambio caracteres del codigo para
	/*$sta = array("B","V","-","0","2","4","6","8");
	$stb = array("Z","Y","X","9","7","5","3","1");

	$code = str_replace($sta,$stb,$code);
	return ($code);*/

	/*
	obtengo el codigo de orden o reserva
	1. si es orden, devuelvo ese codigo
	2. si es reserva, con ese codigo busco el hash que corresponda en la tabla y devuelvo ese hash
	*/
	$CI->load->model('Orden_model','Orden');
	$ord = $CI->Orden->getWhere(['code'=>$code])->row();
	if(isset($ord->id) && $ord->id){
		//si existe la orden
		return $code;
	}
	else{
		$CI->load->model('Reserva_model','Reserva');
		$res = $CI->Reserva->getWhere(['code'=>$code])->row();
		if(isset($res->id) && $res->id && $res->hash_code){
			//si existe la reserva y el hash
			return $res->hash_code;
		}
		else{
			return base64_encode($code.'|'.md5($CI->config->item('encryption_key')));
		}
	}
}

function desencriptar($hash){
	/*
	recibo el hash de la orden o reserva
	1. si es orden devuelvo ese codigo
	2. si es reserva, devuelvo el array con el codigo correspondiente
	*/

	$CI =& get_instance();

	//17-10-18
	if(strlen($hash) < 50){
		$CI->load->model('Orden_model','Orden');
		$ord = $CI->Orden->getWhere(['code'=>$hash])->row();
		if(isset($ord->id) && $ord->id){
			//si existe la orden
			$data = [];
			$data[0] = $hash;
			$data[1] = $hash;//este ni se usa, pero tiene que estar
			return $data;
		}
		else{
			$CI->load->model('Reserva_model','Reserva');
			$res = $CI->Reserva->getWhere(['hash_code'=>$hash])->row();
			if(isset($res->id) && $res->id && $res->hash_code){
				//si existe la reserva y el hash_code
				$data = [];
				$data[0] = $res->code;
				$data[1] = $res->code;//este ni se usa, pero tiene que estar
				return $data;
			}
			else{
				return base64_encode($code.'|'.md5($CI->config->item('encryption_key')));
			}
		}

		//$hash = ($hash);

		//proceso inverso
		/*$sta = array("Z","Y","X","9","7","5","3","1");
		$stb = array("B","V","-","0","2","4","6","8");

		$hash = str_replace($sta,$stb,$hash);

		//es el nuevo hash
		//lo devuelvo con el mismo formato que se usa en controllers
		$data = [];
		$data[0] = $hash;
		$data[1] = $hash;//este ni se usa, pero tiene que estar
		return $data;*/
	}
	else{
		//hash anteriores
		$CI =& get_instance();
		$hash_decoded = base64_decode($hash);
		$aux = $hash_decoded;
		$data = explode('|',$aux);

		if(@$data[1] == md5($CI->config->item('encryption_key')) && $data[0].'|'.$data[1] == $hash_decoded){
			return $data;
		}
		else{
			return false;
		}
	}

}


function pre($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function get_curl_data($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

function post($url, $fields){
	$fields_string = "";
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	$result = curl_exec($ch);

	curl_close($ch);

	return $result;
}

function player_youtube($url, $width, $height) {
	preg_match(
    	    '/[\\?\\&]v=([^\\?\\&]+)/',
	        $url,
        	$matches
    	);
	$id = $matches[1];

	return '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://www.youtube.com/v/' . $id . '&amp;hl=en_US&amp;fs=1?rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $id . '&amp;hl=en_US&amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $width . '" height="' . $height . '"></embed></object>';
}

function thumb_youtube($url, $n) {
	preg_match(
    	    '/[\\?\\&]v=([^\\?\\&]+)/',
	        $url,
        	$matches
    	);
	$id = $matches[1];

	return 'http://img.youtube.com/vi/'.$id.'/'.$n.'.jpg';
}

function isValidEmail($email,$multipleEmails=false){
	if($multipleEmails){
		//devuelve true or false segun haya algun email no valido
		$emails = explode(',',$email);
		$valid = array();
		foreach( $emails as $email ) {
			if($email != '')
				if( preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.)?@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email) )
					$valid[] = $email;
		}
		return (count($valid) > 0) ? $valid : 0;
	}
	else{
		return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.)?@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email);
	}
}

function download_file($file_source,$download_name){
    if($file_source != ''){
		$aux = $file_source;

		$aux = explode('.',$aux);
		header("Content-type:application/".$aux[1]);

		header("Content-Disposition:attachment;filename='".$download_name."'");

		readfile($file_source);
	}
}



/**
 * Array to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('array_to_csv'))
{
	function array_to_csv($array, $download = "", $separation_line)
	{
		if ($download != "")
		{
			header('Content-Type: application/csv');
			header('Content-Disposition: attachement; filename="' . $download . '"');
		}

		ob_start();
		$f = fopen('php://output', 'w') or show_error("Can't open php://output");
		$n = 0;
		foreach ($array as $line)
		{
			$n++;
			if ( $n > 1){ //los nombres de columnas no

			$Delimiter = '';
			$Separator = ';';

			//fwrite($f, $Delimiter.implode($Delimiter.$Separator.$Delimiter, $Line).$Delimiter."\n");

			//09-01-15 por ahora no lo usamos porquen o sirvio
			$separation_line = false;

			//if ( ! fputcsv($f, $line, ';', " ")) //original from csv_helper.php
			if ( ! fwrite($f, $Delimiter.implode($Delimiter.$Separator.$Delimiter, $line).$Delimiter."\n".($separation_line?"\r":"")))
				{
					show_error("Can't write line $n: $line");
				}
			}

		}
		fclose($f) or show_error("Can't close php://output");
		$str = ob_get_contents();
		ob_end_clean();

		if ($download == "")
		{
			return $str;
		}
		else
		{
			echo $str;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Query to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('query_to_csv'))
{
	function query_to_csv($query, $headers = TRUE, $download = "", $separation_line = false)
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('invalid query');
		}

		$array = array();

		if ($headers)
		{
			$line = array();
			foreach ($query->list_fields() as $name)
			{
				$line[] = utf8_decode($name);
			}
			$array[] = $line;
		}

		foreach ($query->result_array() as $row)
		{
			$line = array();
			foreach ($row as $item)
			{
				$line[] = utf8_decode($item);
			}
			$array[] = $line;
		}

		echo array_to_csv($array, $download, $separation_line);
	}
}

function dias_transcurridos($fecha_i,$fecha_f){
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);
	return $dias;
}

function array_to_object($array){
	$object = new stdClass();
	foreach($array as $key=>$value){
	  $object->$key = $value;
	}

	return $object;
}

function buscar_s3_o_servidor ($fileName, $fecha) {
	$CI =& get_instance();
	$CI->load->library('aws_s3');
	if($fecha) {

		$fechas = date_parse_from_format("Y.n.j", $fecha);
		
		foreach($fechas as $f) { // parse fecha to int
			$f = intval($f);
		}
	}
	
	// fecha apartir en cual las facturas se guardaran en S3 (configurar en config.php)
	if(empty($fecha) || $fechas['year'] > $CI->config->item('s3_fecha_year') || ($fechas['day'] >= $CI->config->item('s3_fecha_day')  && $fechas['month'] >= $CI->config->item('s3_fecha_month')  && $fechas['year'] == $CI->config->item('s3_fecha_year'))) {
		try {
			return $CI->aws_s3->get_s3_object($fileName); // buscar en aws s3
		} catch(Exception $e) {
			$e->getMessage();
		}
	} else {
		if(file_exists( FCPATH.'/data/facturas/'.$fileName)) { // buscar en servidor
			return FCPATH.'/data/facturas/'.$fileName;
		} else {
			return false;
		}
	}

}

// Con los datos de la factura genero url con los datos correspondiente en formato base64 
function generarUrlQR ($datos) {
	$CI =& get_instance();
    $CI->load->helper('file');

	$qr = json_encode([
		'ver' => 1, 
		'fecha' => substr($datos['fecha'],0,10),
		'cuit' => 30711604932,
		'ptoVta' => intval($datos['punto_venta']),
		'$tipoCmp' => intval($datos['codigo_factura']),
		'nroCmp' => intval($datos['numero_factura']),
		'importe' => floatval($datos['total']),
		'moneda' => 'PES',
		'ctz' => floatval(1),
		'tipoDocRec' => intval($datos['tipo_documento']),
		'nroDocRec' => intval($datos['nro_documento']),
		'tipoCodAut' => 'E',
		'codAut' => 70417054367476
	]);
	
	$qr = base64_encode($qr);
	
	$url = 'https://www.afip.gob.ar/fe/qr/?p='.$qr;

	$barcode = new \Com\Tecnick\Barcode\Barcode();
	$bobj = $barcode->getBarcodeObj(
		'QRCODE,H',
		$url,
		-4,
		-4,
		'black',
		array(-2, -2, -2, -2)
		)->setBackgroundColor('white');

		
    return $bobj->getPngData();
}


