<?php
class Reserva_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->table = "bv_reservas";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}

	function onGetAll(){
		$this->db->select('bv_reservas.*, ls.nombre as nombre_lugar, x.nombre as lugarSalida, p.nombre, p.codigo as paquete_codigo, p.exterior, p.precio_usd, p.operador_id,
							pp.hora, s.nombre as sucursal, s.direccion, s.telefono, pc.agotada');
		$this->db->join('bv_paquetes p','p.id = bv_reservas.paquete_id');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = p.id and pp.id = bv_reservas.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_lugares_salida ls', 'ls.id = x.lugar_id','left');
		$this->db->join('bv_sucursales s', 's.id = bv_reservas.sucursal_id','left');
		$this->db->join('bv_paquetes_combinaciones pc', 'pc.id = bv_reservas.combinacion_id','left');
	}

	function onGet(){
		$this->db->flush_cache();

		$this->db->select('bv_reservas.*, ls.nombre as nombre_lugar, x.nombre as lugarSalida, p.nombre, p.codigo as paquete_codigo, p.exterior, p.precio_usd, p.operador_id, p.grupal,
							pp.hora, s.nombre as sucursal, s.direccion, s.telefono, u.email, pc.fecha_alojamiento_cupo_id, pc.agotada, pc.c_costo_operador, pc.v_total,
							o.nombre as operador, o.cuit as operador_cuit, o.legajo as operador_legajo,
							RG.codigo_grupo,
							PR.alojamiento_fecha_cupo_id as rooming_cupo_id, PR.nro_habitacion as rooming_nro_hab,
							h.nombre as habitacion');
		$this->db->join('bv_paquetes p','p.id = bv_reservas.paquete_id');
		$this->db->join('bv_operadores o','o.id = p.operador_id');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = p.id and pp.id = bv_reservas.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_usuarios u', 'u.id = bv_reservas.usuario_id','left');
		$this->db->join('bv_paquetes_combinaciones pc', 'pc.id = bv_reservas.combinacion_id','left');
		$this->db->join('bv_lugares_salida ls', 'ls.id = x.lugar_id','left');
		$this->db->join('bv_sucursales s', 's.id = bv_reservas.sucursal_id','left');
		$this->db->join('bv_habitaciones h','h.id = bv_reservas.habitacion_id');

		//27-08-18 join a reservas_grupos para saber si la reserva pertenece a una reserva grupal
		$this->db->join('bv_reservas_grupos RG','RG.reserva_id = bv_reservas.id','left');
		$this->db->join('bv_paquetes_rooming PR','PR.reserva_id = bv_reservas.id','left');
	}

	function getAdicionales($reserva_id){
		$this->db->select('a.*, pa.v_exento, pa.v_nogravado, pa.v_gravado21, pa.v_gravado10, pa.v_comision, pa.v_iva21, pa.v_iva10, pa.v_gastos_admin, pa.v_rgafip, pa.v_otros_imp, pa.v_total, oa.valor as adicional_valor, oa.paquete_adicional_id');
		$this->db->join('bv_paquetes_adicionales pa','pa.id = oa.paquete_adicional_id');
		$this->db->join('bv_adicionales a','a.id = pa.adicional_id');
		return $this->db->get_where('bv_reservas_adicionales oa',array('oa.reserva_id'=>$reserva_id))->result();
	}

	function generar($orden_id){
		//Obtener datos de la orden y del paquete
		$orden = $this->Orden_model->get($orden_id)->row();
		$paquete = $this->Paquete_model->get($orden->paquete_id)->row();

		//Calcular la fecha limite de carga de datos
		if ($orden->fecha != '0000-00-00') {
			$fecha_referencia = $orden->fecha;
		}
		else {
			$fecha_referencia = $paquete->fecha_inicio;
		}

		//$fecha_limite_datos = new DateTime(date('Y-m-d H:i:s', strtotime($fecha_referencia)));
		//$fecha_limite_datos->sub(new DateInterval('P'.$paquete->dias_completar_datos.'D'));

		//la fecha limite para completar datos de pax sale del backend de paquete
		$hora = date('H:i:s');
		$fecha_limite_datos = $paquete->fecha_limite_completar_datos.' '.$hora;

		//Calcular la fecha de limite de pago minimo
		$fecha_limite_pago_min = new DateTime(date('Y-m-d H:i:s'));
		$fecha_limite_pago_min->add(new DateInterval('PT'.$this->settings->horas_pago_min.'H'));


		//Calcular la fecha de limite de pago total
		//$fecha_limite_pago_completo = new DateTime(date('Y-m-d H:i:s', strtotime($fecha_referencia)));
		//$fecha_limite_pago_completo->sub(new DateInterval('P'.$paquete->dias_pago_completo.'D'));

		//la fecha limite para pagar completo sale del backend de paquete
		$hora = date('H:i:s');
		$fecha_limite_pago_completo = $paquete->fecha_limite_pago_completo.' '.$hora;

		//Maxi | 16-01-19 si en la orden no está bien seteado el monto de adicionales, lo recalculo en base a lo que tenga en la tabla de bv_ordenes_adicionales (Esto debido al problema que habia que habia reservas que se generaban con adicional agregado pero sin considerar dicho monto del adicional en el precio total del viaje)
		$adicionales = $this->Orden_model->getAdicionales($orden_id);
		$total_adicionales = 0;
		if(count($adicionales)){
			foreach($adicionales as $adicional){
				$total_adicional = $adicional->v_total ? $adicional->v_total : 0.00;
				$total_adicional = $total_adicional*$orden->pasajeros;

				$total_adicionales += $total_adicional;
			}
		}
		$orden->adicionales_precio = $total_adicionales;


		/*
		24-08-18
		Si el paquete es grupal, la reserva es personal, por lo cual "pasajeros" es 1
		*/
		if($paquete->grupal){
			$pax = 1;
			$paquete_precio = number_format($orden->paquete_precio/$orden->pasajeros,2,'.','');
			$impuestos = number_format($orden->impuestos/$orden->pasajeros,2,'.','');
			$adicionales_precio = number_format($orden->adicionales_precio/$orden->pasajeros,2,'.','');
		}
		else{
			//si es una reserva no grupal, tomo los datos ya guardados en la orden
			$pax = $orden->pasajeros;
			$paquete_precio = $orden->paquete_precio;
			$impuestos = $orden->impuestos;
			$adicionales_precio = $orden->adicionales_precio;
		}

		//18-10-18 hash code para reserva
		$hash_code = genRandomString();//codigo random de 10 chars

		$this->db->query("
			INSERT INTO bv_reservas
			(orden_id,vendedor_id,estado_id,paquete_id,combinacion_id,pasajeros,lugar_id,sucursal_id,fecha_alojamiento_id,fecha,alojamiento_id,habitacion_id,paquete_regimen_id,transporte_fecha_id,paquete_precio,impuestos,adicionales_precio,fecha_reserva,ip,paquete_parada_id,fecha_completo_paso3,completo_paso1,completo_paso2,completo_paso3,completo_paso4,paso_actual,salteo_paso3,cotizacion, fecha_limite_pago_min, fecha_limite_pago_completo, fecha_limite_datos,hash_code,fecha_completo_paso2,salteo_paso2,user_tipo_moneda)
			SELECT ".$orden_id." as orden,vendedor_id,1 as estado,paquete_id,combinacion_id,".$pax.",lugar_id,sucursal_id,fecha_alojamiento_id,fecha,alojamiento_id,habitacion_id,paquete_regimen_id,transporte_fecha_id,'".$paquete_precio."','".$impuestos."','".$adicionales_precio."', '".date('Y-m-d H:i:s')."',ip,paquete_parada_id,fecha_completo_paso3,completo_paso1,completo_paso2,completo_paso3,completo_paso4,paso_actual,salteo_paso3,cotizacion, '".$fecha_limite_pago_min->format('Y-m-d H:i:s')."', '".$fecha_limite_pago_completo."', '".$fecha_limite_datos."', '".$hash_code."', fecha_completo_paso2, salteo_paso2, user_tipo_moneda
				from bv_ordenes where id = ".$orden_id."
		");

		$id = $this->db->insert_id();

		//27/12/19 luego de generar la reserva me fijo si hay un vendedor en sesion y se lo piso a la reserva y orden. Esto es porque puede pasar el caso en que la orden se genere sin vendedor y luego se loguee desde backend. Entonces la reserva resultante quedaba sin vendedor asociado.
		if(esVendedor()){
			if(perfil()=='VEN'){
				//si es un ADMIN de tipo VENDEDOR, obtengo el vendedor externo asociado, y se lo pongo a la orden
				$vend_id = vendedor_asociado();
				$vendedor_id = $vend_id;
			}
			else{
				$vendedor_id = userloggedId();
			}

			$this->db->query("update bv_ordenes set vendedor_id = ".$vendedor_id." where id = ".$orden_id);
			$this->db->query("update bv_reservas set vendedor_id = ".$vendedor_id." where id = ".$id);
		}

		//marco como vencida a la orden esta porque ya fue confirmada en reserva
		$this->db->query("UPDATE bv_ordenes SET vencida=1 WHERE id = ".$orden_id);

		$res = $this->get($id)->row();

		$this->load->model('Combinacion_model','Combinacion');
		$comb = $this->Combinacion->get($res->combinacion_id)->row();

		/*
		el estado de la reserva entra segun si la combinacion reservada estaba agotada o no o si el paquete
		tiene confirmacion_inmediata
		13: A CONFIRMAR (si el paq no es de conf inmediata ó si la combinacion esta agotada) (pedido de Marce en mail de pendientes)
		1: si no hay restriccion
		*/
		//$estado_id = ($comb->confirmacion_inmediata && !$comb->agotada) ? 1 : 13;

		if(!$comb->confirmacion_inmediata){
			$estado_id = 13; // a confirmar
		}
		else{
			if($comb->cupo_paquete_personalizado){
				//si el paquete tiene cupo personalizado
				##$estado_id = ($comb->cupo_paquete_disponible >= $orden->pasajeros) ? 1 : 12;
				//24-08-18
				//$estado_id = ($comb->cupo_paquete_disponible_real >= $orden->pasajeros) ? 1 : 12;


				verificar_cupo($comb,$orden,$paquete);

				if(
					($comb->agotada && $comb->habitacion_id!=99)
					|| ( isset($this->data['transporte_sin_cupo']) && $this->data['transporte_sin_cupo'] )
					|| ( isset($this->data['habitacion_sin_cupo']) && $this->data['habitacion_sin_cupo'] )
					|| (date('Y-m-d') > $comb->fecha_inicio)

				){
					$estado_id = 12;//lista espera
				}
				else{
					$estado_id = ($comb->cupo_paquete_disponible >= $orden->pasajeros) ? 1 : 12;
				}
			}
			else{
				//si no usa cupo personalizado
				//$estado_id = ($comb->cupo_disponible >= $orden->pasajeros) ? 1 : $estado_id;

				verificar_cupo($comb,$orden,$paquete);

				if(
					($comb->agotada && $comb->habitacion_id!=99)
					|| ( isset($this->data['transporte_sin_cupo']) && $this->data['transporte_sin_cupo'] )
					|| ( isset($this->data['habitacion_sin_cupo']) && $this->data['habitacion_sin_cupo'] )
					|| (date('Y-m-d') > $comb->fecha_inicio)

				){
					$estado_id = 12;//lista espera
				}
				else{
					$estado_id = 1; //hay cupo, nueva
				}
			}

			/*//aca chequeo si para la orden, el tipo de habitacion que no sea grupal reservada hay cupo
			$habitacion_sin_cupo = false;
			if($orden->habitacion_id != 99 && $orden->completo){
				$habitacion_sin_cupo=true;
			}

			//si es habitacion compartida y la cantidad de pax elegida es mayor al cupo disponible
			if($orden->habitacion_id == 99 && $orden->pasajeros > $paquete->cupo_disponible){
				$habitacion_sin_cupo = true;
			}

			//acualizo el estado, si no hay cupo de hab entonces entra como LISTA ESPERA
			$estado_id = $habitacion_sin_cupo ? 12 : $estado_id;*/
		}


		//Si hay cupo personalizado, decrementarlo
		if ($estado_id == 1 && $paquete->cupo_paquete_personalizado) {
			/*
			$this->Paquete->update($paquete->id, array(
				'cupo_paquete_disponible' => $paquete->cupo_paquete_disponible - $orden->pasajeros
			));
			*/
			$this->Paquete->update($paquete->id, array(
				'cupo_paquete_disponible_real' => $paquete->cupo_paquete_disponible_real - $orden->pasajeros
			));
		}

		$upd = array();
		$upd['estado_id'] = $estado_id;

		//TENGO QUE GENERAR EL CODIGO DE RESERVA VALIDO PARA EL VIAJE
		$this->load->model('Orden_model','Orden');
		$orden = $this->Orden->get($res->orden_id)->row();
		$this->load->model('Paquete_model','Paquete');
		$paquete = $this->Paquete->get($orden->paquete_id)->row();

		$code = 'BV-'.$paquete->codigo.'-'.str_pad($id, 5, '0', STR_PAD_LEFT);
		$upd['code'] = $code;

		$this->update($id,$upd);

		return $id;
	}

	function generarAdicionales($orden_id,$reserva_id,$i=0){


		//primero miro si habia alguna restriccion para el adicional
		$row = $this->db->query("select * from bv_ordenes_adicionales where orden_id = ".$orden_id)->row();

		if(isset($row->cantidad) && $row->cantidad > 0){
			//si tiene una cantidad limitada, busco los pasajeros que le corresponde el adicional en la tabla de ordenes_pasajeros_Adicionales

			$wh_np = "1=1";
			if($i){
				$wh_np = "op.numero_pax = ".$i;
			}
			$rows = $this->db->query("select op.numero_pax, opa.* from bv_ordenes_pasajeros_adicionales opa
										join bv_ordenes_pasajeros op on op.id = opa.orden_pasajero_id
										where ".$wh_np." and op.orden_id = ".$orden_id)->result();

			if(count($rows)){
				foreach ($rows as $r) {
					//si no está generado, lo creo, sino no

					$existe = $this->db->query("
						select * from bv_reservas_adicionales
						where reserva_id = ".$reserva_id." and paquete_adicional_id = ".$row->paquete_adicional_id)->row();


					if(isset($existe->id) && $existe->id){
						//nada
						$ret = false;
					}
					else{
						//lo genero
						$this->db->query("
							INSERT INTO bv_reservas_adicionales
								(reserva_id,paquete_adicional_id,valor) values
								(".$reserva_id.",".$row->paquete_adicional_id.",".$row->valor.") ");

						$ret = true;
					}
				}

			}
			else{
				$ret = false;
			}
		}
		else{



			//funcionamiento como hasta ahora
			$this->db->query("
				INSERT INTO bv_reservas_adicionales
				(reserva_id,paquete_adicional_id,valor)
				(select ".$reserva_id.",paquete_adicional_id,valor
					from bv_ordenes_adicionales where orden_id = ".$orden_id.")
			");

			$ret = $this->db->insert_id();
		}

		return $ret;
	}

	function generarDatosFacturacion($orden_id,$reserva_id){
		$this->db->query("
			INSERT INTO bv_reservas_facturacion
			(reserva_id,f_nombre,f_apellido,f_fecha_nacimiento,f_cuit_prefijo,f_cuit_numero,f_cuit_sufijo,f_nacionalidad_id,f_residencia_id,f_provincia,f_ciudad,f_domicilio,f_numero,f_depto,f_cp,timestamp,ip)
			(select ".$reserva_id.",f_nombre,f_apellido,f_fecha_nacimiento,f_cuit_prefijo,f_cuit_numero,f_cuit_sufijo,f_nacionalidad_id,f_residencia_id,f_provincia,f_ciudad,f_domicilio,f_numero,f_depto,f_cp,date_format(now(),'%Y-%m-%d %H:%i:%s'),ip
				from bv_ordenes_facturacion where orden_id = ".$orden_id.")
		");

		return $this->db->insert_id();
	}

	//los datos del responsable lso guardo en USUARIOS y el de TODOS los pasajeros, en PASAJEROS
	//tambien genero las asociaciones de ellos con la RESERVA
	//27-08-18 num_pax es el numero de pasajero que voy a asociar a la reserva. Este caso es para los viajes que son grupales que se genera una reserva por cada pasajero
	function generarDatosPasajeros($orden_id,$reserva_id,$num_pax=false){

		if($num_pax){
			//tomo el numero de pasajero d
			$pax = $this->db->query("select * from bv_ordenes_pasajeros where orden_id = ".$orden_id." and numero_pax = ".$num_pax." order by numero_pax asc")->result();
		}
		else{
			$pax = $this->db->query("select * from bv_ordenes_pasajeros where orden_id = ".$orden_id." order by numero_pax asc")->result();
		}

		foreach($pax as $p){
			$user = array();
			$user['nombre'] = $p->nombre;
			$user['apellido'] = $p->apellido;
			$user['fecha_nacimiento'] = $p->fecha_nacimiento;
			$user['sexo'] = $p->sexo;
			$user['nacionalidad_id'] = $p->nacionalidad_id;
			$user['dni'] = $p->dni;
			$user['pasaporte'] = $p->pasaporte;
			$user['pais_emision_id'] = $p->pais_emision_id;
			$user['fecha_emision'] = $p->fecha_emision;
			$user['fecha_vencimiento'] = $p->fecha_vencimiento;
			$user['email'] = $p->email;
			$user['celular_codigo'] = $p->celular_codigo;
			$user['celular_numero'] = $p->celular_numero;
			$user['dieta'] = $p->dieta;
			$user['emergencia_nombre'] = $p->emergencia_nombre;
			$user['emergencia_telefono_codigo'] = $p->emergencia_telefono_codigo;
			$user['emergencia_telefono_numero'] = $p->emergencia_telefono_numero;
			$user['timestamp'] = date('Y-m-d H:i:s');
			$user['ip'] =  $p->ip;

			//solo al RESPONSABLE en tabla de usuarios
			//27-08-18 ó si la reserva es grupal, tambien voy a generar en USUARIOS un registro por cada pasajero
			if($p->responsable || $num_pax){
				//chequeo si el usuario DNI+EMAIL ya existe para usarlo
				$existe = $this->db->query("select * from bv_usuarios where email = '".$p->email."' and dni = '".$p->dni."' limit 1")->row();
				if($existe && $existe->id){
					$user_id = $existe->id;
				}
				else{
					$this->db->insert("bv_usuarios",$user);
					$user_id = $this->db->insert_id();
				}

				//actualizo ID de usuario responsable en reservas
				$this->Reserva->update($reserva_id,array('usuario_id'=>$user_id));
			}

			//a TODOS los pasajeros en la tabla
			$this->db->insert("bv_pasajeros",$user);
			$pax_id = $this->db->insert_id();

			//registro en tabla de RESERVAS_PASAJEROS
			$res_pax = array();
			$res_pax['reserva_id'] = $reserva_id;
			$res_pax['pasajero_id'] = $pax_id;
			//27-08-18 si el viaje es grupal, entonces cada pasajero va a ser RESPONSABLE de su reserva
			$res_pax['numero_pax'] = $num_pax ? 1 : $p->numero_pax;
			$res_pax['responsable'] = $num_pax ? 1 : $p->responsable;
			$res_pax['salteo'] = $p->salteo;
			$res_pax['completo'] = $p->completo;
			$this->db->insert("bv_reservas_pasajeros",$res_pax);
		}

		return true;
	}


	function getAllGroupByPaquetes($activo,$sort='', $type='',$year='', $limit='', $offset='',$mail_ajuste_precio='',$operador_id=false){

		$vend_id = false;
		if(esVendedor()){
			if(perfil()=='VEN'){
				//11-10-2018 pedido de juan: si es un ADMIN de tipo VENDEDOR, no lo uso, porque muestro todas las reservas de todos los viajes
				$vend_id = false;
			}
			else{
				$vend_id = userloggedId();
			}
		}

		$this->db->distinct();
		$this->db->select("D.nombre as destino, D.manifiesto, P.*,
						t1.cant as cantidad,
						t2.cant as confirmadas,
						t3.cant as en_espera_pago_bancario,
						ifnull(t4.cant,0) as en_lista_de_espera,
						ifnull(t5.cant,0) as hombres,
						ifnull(t6.cant,0) as mujeres,
						ifnull(rc.cantidad,0) as a_confirmar,
						(case when P.precio_usd = 1 then tt.saldo_usd else tt.saldo end) as saldoACobrar,
						(case when P.precio_usd = 1 then tt.saldo_usd_ars else tt.saldo end) as saldo_orden,
						P.v_total as monto_paquete,
						R.sucursal_id,
						R.lugar_id,
						'0' as llamados_por_hacer",false);
		$this->db->join("bv_destinos D","D.id = P.destino_id");
		$this->db->join($this->table." R","R.paquete_id = P.id","left");

		//join para el saldo a cobrar por paquete en ARS
		$this->db->join("(select R5.paquete_id,
							0 as saldo,
							SUM(M.debe_usd)-SUM(M.haber_usd) as saldo_usd,
							(SUM(M.debe_usd)-SUM(M.haber_usd))*R5.cotizacion as saldo_usd_ars
				from bv_movimientos M, bv_reservas R5 , bv_usuarios u , bv_paquetes_combinaciones c
				where R5.usuario_id = M.usuario_id and M.tipoUsuario = 'U'
					and R5.estado_id != 5 and u.id = R5.usuario_id and u.id = M.usuario_id and R5.id = M.reserva_id and c.id = R5.combinacion_id and M.cta_usd = 1
				group by R5.paquete_id
			UNION
				select R5.paquete_id,
							SUM(M.debe)-SUM(M.haber) as saldo,
							0 as saldo_usd,
							0 as saldo_usd_ars
				from bv_movimientos M, bv_reservas R5 , bv_usuarios u , bv_paquetes_combinaciones c
				where R5.usuario_id = M.usuario_id and M.tipoUsuario = 'U'
					and R5.estado_id != 5 and u.id = R5.usuario_id and u.id = M.usuario_id and R5.id = M.reserva_id and c.id = R5.combinacion_id and M.cta_usd = 0
				group by R5.paquete_id) tt","tt.paquete_id = P.id","left");

		if($vend_id){
			$this->db->where("R.vendedor_id = ".$vend_id);
		}

		if($operador_id){
			$this->db->where("P.operador_id = ".$operador_id);
		}

		/*
		if($mail_ajuste_precio != '')
			$this->db->join($this->table." R","R.paquete_id = P.id and R.mail_ajuste_precio = 0","left");
		else
			$this->db->join($this->table." R","R.paquete_id = P.id","left");
		*/

		//la cantidad de reservas es la suma de los pasajeros, no la cantidad de ventas
		//$this->db->join("(select R2.paquete_id, count(*) as cant from ".$this->table." R2 join bv_paquetes_combinaciones c on c.id = R2.combinacion_id where R2.estado_id > 0 and R2.estado_id not in (5,13) group by R2.paquete_id) t1","t1.paquete_id = P.id","left");
		$this->db->join("(select R2.paquete_id, sum(R2.pasajeros) as cant from ".$this->table." R2 join bv_paquetes_combinaciones c on c.id = R2.combinacion_id where R2.estado_id > 0 and R2.estado_id not in (5,12,13) group by R2.paquete_id) t1","t1.paquete_id = P.id","left");
		//la cantidad de reservas confirmadas es la suma de los pasajeros, no la cantidad de ventas
		//$this->db->join("(select R3.paquete_id, count(*) as cant from ".$this->table." R3 join bv_paquetes_combinaciones c on c.id = R3.combinacion_id where R3.estado_id > 0 and (R3.estado_id = 4 OR R3.estado_id = 14) group by R3.paquete_id) t2","t2.paquete_id = P.id","left");
		$this->db->join("(select R3.paquete_id, sum(R3.pasajeros) as cant from ".$this->table." R3 join bv_paquetes_combinaciones c on c.id = R3.combinacion_id where R3.estado_id > 0 and (R3.estado_id = 4 OR R3.estado_id = 14) group by R3.paquete_id) t2","t2.paquete_id = P.id","left");
		$this->db->join("(select R4.paquete_id, count(*) as cant from ".$this->table." R4 join bv_paquetes_combinaciones c on c.id = R4.combinacion_id where R4.estado_id = 14 group by R4.paquete_id) t3","t3.paquete_id = P.id","left");

		//para lista de espera
		$this->db->join("(select R5.paquete_id, sum(R5.pasajeros) as cant from ".$this->table." R5 join bv_paquetes_combinaciones c on c.id = R5.combinacion_id where R5.estado_id = 12 group by R5.paquete_id) t4","t4.paquete_id = P.id","left");

		//hombres y mujeres
		$this->db->join("(select R6.paquete_id, sum(R6.pasajeros) as cant from ".$this->table." R6
						    join bv_paquetes_combinaciones c on c.id = R6.combinacion_id
						    join bv_usuarios U on U.id = R6.usuario_id and U.sexo = 'masculino'
							where R6.estado_id in (1,2,4,14)
							group by R6.paquete_id) t5","t5.paquete_id = P.id","left");
		$this->db->join("(select R7.paquete_id, sum(R7.pasajeros) as cant from ".$this->table." R7
							join bv_paquetes_combinaciones c on c.id = R7.combinacion_id
							join bv_usuarios U on U.id = R7.usuario_id and U.sexo = 'femenino'
							where R7.estado_id in (1,2,4,14)
							group by R7.paquete_id) t6","t6.paquete_id = P.id","left");

		//este join es para obtener la cantidad de reservas a confirmar de cada paquete
		$this->db->join('(select count(distinct R.id) as cantidad, R.paquete_id
								from bv_reservas R
								where R.estado_id = 13
								group by R.paquete_id
							) rc','P.id = rc.paquete_id','left');

		if ($sort != '')
			$this->db->order_by($sort, $type);

		if ($limit != '')
			$this->db->limit($limit);

		if ($offset != '')
			$this->db->offset($offset);

		$this->db->group_by('P.codigo');

		//if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			//$this->db->where('P.id = 110');
		//}

		if ($year != '')
			return $this->db->get_where("bv_paquetes P",array("P.activo"=>$activo, "YEAR(P.fecha_inicio)"=>$year));
		else
			return $this->db->get_where("bv_paquetes P",array("P.activo"=>$activo));
	}



	function getAllGroupByPaquetesOptim($activo,$sort='', $type='',$year='', $limit='', $offset='',$mail_ajuste_precio='',$operador_id=false, $filtrosArr=FALSE){

		$vend_id = false;
		if(esVendedor()){
			if(perfil()=='VEN'){
				//11-10-2018 pedido de juan: si es un ADMIN de tipo VENDEDOR, no lo uso, porque muestro todas las reservas de todos los viajes
				$vend_id = false;
			}
			else{
				$vend_id = userloggedId();
			}
		}

		if($vend_id){
			$filtro_vendedor = "R.vendedor_id = ".$vend_id;
		}
		else {
			$filtro_vendedor = "1";
		}

		if($operador_id){
			$filtro_operador = "P.operador_id = ".$operador_id;
		}
		else {
			$filtro_operador = "1";
		}

		if ($filtrosArr) {
			$filtros = implode(" AND ", $filtrosArr);
		}
		else {
			$filtros = "1";
		}

		$q = "
			SELECT
				P.id,
				P.codigo,
				P.nombre,
				P.fecha_inicio,
				P.cupo_paquete_disponible_real,
				P.precio_usd,
				P.grupal,
			    D.nombre as destino,
			    D.manifiesto,
			    sexos.hombres,
			    sexos.mujeres,
			    (case when P.precio_usd = 1 then tt.saldo_usd else tt.saldo end) as saldoACobrar,
			    (case when P.precio_usd = 1 then tt.saldo_usd_ars else tt.saldo end) as saldo_orden,
			    P.v_total as monto_paquete,
			    R.sucursal_id,
			    R.lugar_id,
			    0 as llamados_por_hacer,
			    SUM(CASE WHEN R.estado_id > 0 and R.estado_id not in (5,12,13) THEN R.pasajeros ELSE 0 END) as cantidad,
			    SUM(CASE WHEN R.estado_id > 0 and (R.estado_id = 4 OR R.estado_id = 14) THEN R.pasajeros ELSE 0 END) as confirmadas,
			    SUM(CASE WHEN R.estado_id = 14 THEN 1 ELSE 0 END) as en_espera_pago_bancario,
			    SUM(CASE WHEN R.estado_id = 12 THEN 1 ELSE 0 END) as en_lista_de_espera,
			    SUM(CASE WHEN R.estado_id = 13 THEN 1 ELSE 0 END) as a_confirmar
			FROM `bv_paquetes` `P`
			JOIN `bv_destinos` `D` ON `D`.`id` = `P`.`destino_id`
			LEFT JOIN `bv_reservas` `R` ON `R`.`paquete_id` = `P`.`id`
			LEFT JOIN bv_paquetes_combinaciones c ON c.id = R.combinacion_id
			LEFT JOIN (select R5.paquete_id,
			                            SUM(CASE WHEN M.cta_usd = 0 THEN M.debe-M.haber ELSE 0 END) as saldo,
			                            SUM(CASE WHEN M.cta_usd = 1 THEN M.debe_usd-M.haber_usd ELSE 0 END) as saldo_usd,
			                            SUM(CASE WHEN M.cta_usd = 1 THEN (M.debe_usd-M.haber_usd)*R5.cotizacion ELSE 0 END) as saldo_usd_ars
			                from bv_movimientos M, bv_reservas R5
			                where R5.usuario_id = M.usuario_id and M.tipoUsuario = 'U'
			                    and R5.estado_id != 5 and R5.id = M.reserva_id
			                group by R5.paquete_id ) tt ON `tt`.`paquete_id` = `P`.`id`
			LEFT JOIN (select R6.paquete_id, sum(CASE WHEN P.sexo = 'masculino' THEN 1 ELSE 0 END) as hombres, sum(CASE WHEN P.sexo = 'femenino' THEN 1 ELSE 0 END) as mujeres
			                            from bv_reservas R6
			                            inner join bv_reservas_pasajeros RP on RP.reserva_id = R6.id
			                            inner join bv_pasajeros P on P.id = RP.pasajero_id
			                            where R6.estado_id in (1,2,4,14)
			                            group by R6.paquete_id) sexos ON `sexos`.`paquete_id` = `P`.`id`
			WHERE `P`.`activo` = $activo AND $filtro_vendedor AND $filtro_operador AND $filtros
			GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15
			ORDER BY `P`.`fecha_inicio` ASC
		";

		return $this->db->query($q);
	}

	/*
	Para exportar listado de pasajeros de viaje (por ID) al exterior
	que Marce tiene que presentar en AFIP en formato CSV
	*/
	function getAllExterior_export($id){
		//DATE_FORMAT(t.fecha,'%Y%m%d'),
		$this->db->select("'96', U.dni, CONCAT(U.apellido,' ',U.nombre),
							'' as cuit_cuil, CONCAT(P.codigo,'-',R.id),
							DATE_FORMAT(R.fecha_reserva,'%Y%m%d'),
							DATE_FORMAT(P.fecha_inicio,'%Y%m%d'),
							D.codigo_pais_afip", FALSE);
		$this->db->join('bv_paquetes P','P.id = R.paquete_id');
		$this->db->join('bv_destinos D','D.id = P.destino_id');
		$this->db->join('bv_usuarios U','U.id = R.usuario_id');
		$this->db->join('bv_lugares_salida S','S.id = R.lugar_id','left');
		$this->db->where('estado_id != 12');
		return $this->db->get_where($this->table.' R',array('paquete_id'=>$id, "estado_id != "=>5));
	}

	/*
	Para listado Manifiesto
	*/
	function getListaPasajeros($id){
		$this->db->select("R.id, R.code as codigo, R.fecha_reserva as fecha,
						   PA.nombre, PA.apellido, PA.email, PA.celular_codigo, PA.celular_numero,
						   PA.fecha_nacimiento as fechaNacimiento, PI.nombre as nacionalidad, PA.dni, 'DNI' as dniTipo",false);

		$this->db->join('bv_paquetes P','P.id = R.paquete_id');
		$this->db->join('bv_usuarios U','U.id = R.usuario_id');
		$this->db->join('bv_reservas_pasajeros RP','RP.reserva_id = R.id');
		$this->db->join('bv_pasajeros PA','PA.id = RP.pasajero_id');
		$this->db->join('bv_paises PI','PI.id = PA.nacionalidad_id');
		$this->db->join('bv_reservas_estados RE','RE.id = R.estado_id');
		$this->db->join('bv_operadores O','O.id = R.agencia_id','left');
		return $this->db->get_where($this->table.' R',array('paquete_id'=>$id));
	}

	/*
	Descarga formato XLS
	$id puede ser un unico id ó un array de ids de paquete
	*/
	function getAllByPaquete_export($id,$download_fields='',$rid=false,$admin_id=false,$estados=array()){

		if($download_fields != ''){
			$select = 'R.id,R.paquete_id,';

			foreach($download_fields as $value){
				$select .= $value.',';
			}
			$select[strlen($select)-1] = ' ';

			$this->db->select($select,false);


		}
		else{
			$this->db->select("R.id, R.paquete_id, R.id as 'id reserva', R.fecha_reserva as fecha,
							(R.paquete_precio+R.impuestos+R.adicionales_precio) as monto,
							E.nombre as estado,
							PA.apellido, PA.nombre, concat(PA.celular_codigo,' ',PA.celular_numero) as celular, PA.email, PA.dni as 'dni numero', PA.pasaporte as 'pasaporte numero', PA.fecha_emision as 'pasaporte emision', PA.fecha_vencimiento as 'pasaporte vencimiento',
							PA.dieta as menu, PI.nombre as nacionalidad, PA.fecha_nacimiento as 'nacimiento', TIMESTAMPDIFF(year,PA.fecha_nacimiento, now() ) as 'edad',
							PA.sexo, PA.emergencia_telefono_codigo, PA.emergencia_telefono_numero, PA.emergencia_nombre,
							P.codigo as 'cód. paquete', P.nombre as 'viaje',
							CONCAT(V.nombre,' ',V.apellido) as 'vendedor',
							O.nombre as 'operador',
							S.nombre as lugar_salida,
							x.nombre as parada,
							regi.nombre as pension,
							RG.codigo_grupo", FALSE);
		}

		$this->db->join('bv_paquetes P','P.id = R.paquete_id');
		$this->db->join('bv_paquetes_combinaciones PC','PC.id = R.combinacion_id');
		//al exportar listado traigo todos los pasajeros (responsables o no)
		//$this->db->join('bv_reservas_pasajeros RP','RP.responsable = 1 and RP.reserva_id = R.id');
		$this->db->join('bv_reservas_pasajeros RP','RP.reserva_id = R.id');
		$this->db->join('bv_pasajeros PA','PA.id = RP.pasajero_id');
		$this->db->join('bv_paises PI','PI.id = PA.nacionalidad_id', 'left');
		$this->db->join('bv_vendedores V','V.id = R.vendedor_id','left');
		$this->db->join('bv_operadores O','O.id = R.agencia_id','left');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = P.id and pp.id = R.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_lugares_salida S','S.id = x.lugar_id','left');
		//nombre del regimen de comida elegido
		$this->db->join('bv_paquetes_regimenes pr', 'pr.id = R.paquete_regimen_id and pr.paquete_id = P.id','left');
		$this->db->join('bv_regimenes regi', 'regi.id = pr.regimen_id','left');
		$this->db->join('bv_reservas_grupos RG', 'RG.reserva_id = R.id','left');
		$this->db->join('bv_reservas_estados E', 'R.estado_id = E.id','left');

		//$this->db->where('estado_id != 12');
		//return $this->db->get_where($this->table.' R',array('R.paquete_id'=>$id, "estado_id != "=>5));

		if($id){
			if(is_array($id) && count($id)){
				$this->db->where_in('R.paquete_id',$id);
			}
			else{
				//como hasta ahora
				$this->db->where('R.paquete_id',$id);
			}
		}
		if($rid){
			$this->db->where('R.id',$rid);
		}
		if($admin_id){
			$this->db->where('R.vendedor_id',$admin_id);
		}

		if(count($estados)){
			//si viene aplicado filtro de estado
			$this->db->where_in('R.estado_id',$estados);
		}
		else{
			//por defecto como hasta ahora solo las confirmadas
			$this->db->where('R.estado_id',4);
		}
		return $this->db->get($this->table.' R');
	}

	function getAllByPaquete($id,$estado_id='',$sort='', $type='', $admin_id='',$sucursal_id='',$mail_ajuste_precio=''){
		$this->db->select("R.id, R.code as codigo, R.fecha_reserva, R.fecha_reserva as fecha, R.usuario_id, R.vendedor_id, R.agencia_id, R.estado_id,
							R.sucursal_id, R.lugar_id, R.paquete_id, R.completo_paso3, R.fecha_limite_datos, R.pasajeros, R.fecha_limite_pago_completo,
						   RE.nombre as estado, RE.horas_vencimiento, RE.color,
						   P.precio_usd, P.cantidad_vouchers, P.dias_faltan_vouchers, P.fecha_inicio as fechaSalida, P.fecha_fin as fechaRegreso, P.dias_completar_datos, P.confirmacion_inmediata,
						   P.operador_id, P.fecha_limite_vouchers,
						   PA.nombre, PA.apellido, PA.email, concat(PA.celular_codigo,' ',PA.celular_numero) as celular,
						   PA.celular_codigo, PA.celular_numero, PA.fecha_nacimiento as fechaNacimiento, PA.pasaporte,
						   PI.nombre as nacionalidad, PA.dni, 'DNI' as dniTipo,
						   ifnull(t1.cantidad,0) as saldo,
						   ifnull(t1usd.cantidad,0) as saldo_usd,
						   ifnull(t2.cantidad,0) as saldo_viaje,
						   ifnull(t2usd.cantidad,0) as saldo_viaje_usd,
						   (case when t3.cantidad > 0 then t3.cantidad
														else 0
							end) as cantidad_comentarios,
						   (case when t4.cantidad > 0 then t4.cantidad
														else 0
							end) as cantidad_llamados,
						   O.nombre as operador,
						   S.nombre as lugar_salida,
						   (case when T.tipo_id = 1 then 1 else 0 end) as en_avion,
						   ifnull(vc.cantidad,0) as vouchers_cargados,
						   ifnull(inf.cantidad,0) as informes,
						   PC.c_costo_operador,
						   R.combinacion_id,
						   ifnull(adic.cantidad,0) as adicionales,
						   ifnull(t6.cant,0) as en_lista_de_espera,
						   RG.codigo_grupo,
						   PR.alojamiento_fecha_cupo_id as rooming_cupo_id, PC.fecha_alojamiento_cupo_id,
						   ifnull(cupv.fecha_vencimiento,'0000-00-00') as fecha_vencimiento, alarmas.*",false);


		$this->db->join('bv_reservas R', 'R.paquete_id = P.id');
		$this->db->join('bv_reservas_alarmas alarmas','alarmas.reserva_id = R.id','left');
		$this->db->join('bv_paquetes_combinaciones PC','PC.id = R.combinacion_id');
		$this->db->join('bv_transportes T','T.id = PC.transporte_id','left');
		$this->db->join('bv_usuarios U','U.id = R.usuario_id');
		$this->db->join('bv_paises PI','PI.id = U.nacionalidad_id','left');
		$this->db->join('bv_reservas_estados RE','RE.id = R.estado_id');
		$this->db->join('bv_reservas_pasajeros RP','RP.reserva_id = R.id and RP.responsable = 1');
		$this->db->join('bv_paquetes_rooming PR','PR.reserva_id = R.id','left');
		$this->db->join('bv_pasajeros PA','PA.id = RP.pasajero_id');
		$this->db->join('bv_operadores O','O.id = R.agencia_id','left');
		//mostrar el saldo del usaurio respecto de ese viaje en ARS
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1','t1.usuario_id = R.usuario_id and R.id = t1.reserva_id','left');
		//mostrar el saldo del usaurio respecto de ese viaje en USD
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1usd','t1usd.usuario_id = R.usuario_id and R.id = t1usd.reserva_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en ARS (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2','t2.usuario_id = R.usuario_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en USD (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2usd','t2usd.usuario_id = R.usuario_id','left');
		//este join es para obtener la cantidad de comentarios del viaje en particular (reserva_id)
		$this->db->join('(select count(*) as cantidad, C.reserva_id
								from bv_reservas_comentarios C
								group by C.reserva_id
							) t3','R.id = t3.reserva_id','left');

		//este join es para obtener la cantidad de llamados telefonicos del viaje en particular (reserva_id)
		$this->db->join('(select count(*) as cantidad, C.reserva_id
								from bv_reservas_comentarios C
								join bv_comentarios_tipos_acciones TA on TA.id = C.tipo_id and TA.tipo = "llamado"
								group by C.reserva_id
							) t4','R.id = t4.reserva_id','left');

		//este join es para obtener los informes de pago de la reserva que no tengan movimiento creado
		$this->db->join('(select count(*) as cantidad, R.id
								from bv_reservas_informes_pago I
								join bv_reservas R on R.id = I.reserva_id and R.paquete_id = '.$id.'
								where (I.movimiento_id is null or I.movimiento_id = "")
								group by R.id
							) inf','R.id = inf.id','left');

		//este join es para obtener la cantidad de vouchers cargados del pasajero
		$this->db->join('(select count(*) as cantidad, R.id
								from bv_reservas_vouchers V
								join bv_reservas R on R.id = V.reserva_id and R.paquete_id = '.$id.'
								group by R.id
							) vc','R.id = vc.id','left');

		//este join es para obtener la cantidad de adicionales contratados
		$this->db->join('(select count(*) as cantidad, R.id
								from bv_reservas_adicionales A
								join bv_reservas R on R.id = A.reserva_id
								group by R.id
							) adic', 'R.id = adic.id', 'left');

		//para lista de espera
		$this->db->join("(select R5.paquete_id, sum(R5.pasajeros) as cant from bv_reservas R5 where R5.estado_id = 12 group by R5.paquete_id) t6","t6.paquete_id = P.id","left");

		$this->db->join('bv_lugares_salida S','S.id = R.lugar_id','left');

		if($sucursal_id != '')
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id and SU.id = '.$sucursal_id);
		else
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id','left');

		//27-08-18 join a reservas_grupos para saber si la reserva pertenece a una reserva grupal
		$this->db->join('bv_reservas_grupos RG','RG.reserva_id = R.id','left');

		//25-10-18
		$this->db->join('(select p.id, max(tf.fecha_vencimiento) as fecha_vencimiento
								from bv_transportes_fechas tf
								join bv_paquetes_combinaciones pc on pc.fecha_transporte_id = tf.id
								join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
								group by p.id) cupv','cupv.id = R.paquete_id','left');

		if ($sort != '')
			$this->db->order_by($sort, $type);

		if (@$this->data['keywords'] != ''){
			$this->db->where('(U.nombre like "%'.$this->data['keywords'].'%"
								or U.apellido like "%'.$this->data['keywords'].'%"
								or U.email like "%'.$this->data['keywords'].'%"
								or R.code like "%'.$this->data['keywords'].'%"
							)');
		}

		if($estado_id!=""){
			if(is_array($estado_id))
				$this->db->where_in('R.estado_id',$estado_id);
			else
				$this->db->where(array('R.estado_id'=>$estado_id));
		}

		if($admin_id!="")
			return $this->db->get_where('bv_paquetes P',array('P.id' => $id, 'R.vendedor_id'=>$admin_id));
		else
			return $this->db->get_where('bv_paquetes P',array('P.id' => $id));
	}


	function getAllByPaquetesActivos($estado_id='',$sort='', $type='', $admin_id='',$sucursal_id='',$mail_ajuste_precio='',$sin_tbl=false){
		$sel = $sin_tbl ? '' : ', alarmas.*';

		$this->db->select("R.id, R.code as codigo, R.fecha_reserva, R.fecha_reserva as fecha, R.usuario_id, R.vendedor_id, R.agencia_id, R.estado_id,
							R.sucursal_id, R.lugar_id, R.paquete_id, R.completo_paso3, R.fecha_limite_datos, R.pasajeros, R.fecha_limite_pago_completo,
						   RE.nombre as estado, RE.horas_vencimiento, RE.color,
						   P.precio_usd, P.cantidad_vouchers, P.dias_faltan_vouchers, P.fecha_inicio as fechaSalida, P.fecha_fin as fechaRegreso, P.dias_completar_datos, P.confirmacion_inmediata,
						   P.operador_id, P.fecha_limite_vouchers,
						   PA.nombre, PA.apellido, PA.email, concat(PA.celular_codigo,' ',PA.celular_numero) as celular,
						   PA.celular_codigo, PA.celular_numero, PA.fecha_nacimiento as fechaNacimiento, PA.pasaporte,
						   PI.nombre as nacionalidad, PA.dni, 'DNI' as dniTipo,
						   ifnull(t1.cantidad,0) as saldo,
						   ifnull(t1usd.cantidad,0) as saldo_usd,
						   ifnull(t2.cantidad,0) as saldo_viaje,
						   ifnull(t2usd.cantidad,0) as saldo_viaje_usd,
						   (case when t3.cantidad > 0 then t3.cantidad
														else 0
							end) as cantidad_comentarios,
						   (case when t4.cantidad > 0 then t4.cantidad
														else 0
							end) as cantidad_llamados,
						   O.nombre as operador,
						   S.nombre as lugar_salida,
						   (case when T.tipo_id = 1 then 1 else 0 end) as en_avion,
						   ifnull(vc.cantidad,0) as vouchers_cargados,
						   ifnull(inf.cantidad,0) as informes_de_pago,
						   PC.c_costo_operador,
						   R.combinacion_id,
						   ifnull(adic.cantidad,0) as adicionales,
						   ifnull(t6.cant,0) as en_lista_de_espera,
						   RG.codigo_grupo,
						   PR.alojamiento_fecha_cupo_id as rooming_cupo_id, PC.fecha_alojamiento_cupo_id,
						   ifnull(cupv.fecha_vencimiento,'0000-00-00') as fecha_vencimiento,
						   CONCAT(up.fecha_pago, ' ', up.hora_pago) as fecha_informe_pago".$sel,false);

		$this->db->join('bv_reservas R', 'R.paquete_id = P.id');
		$this->db->join('bv_paquetes_combinaciones PC','PC.id = R.combinacion_id');
		$this->db->join('bv_transportes T','T.id = PC.transporte_id','left');
		$this->db->join('bv_usuarios U','U.id = R.usuario_id');
		$this->db->join('bv_paises PI','PI.id = U.nacionalidad_id','left');
		$this->db->join('bv_reservas_estados RE','RE.id = R.estado_id');
		$this->db->join('bv_reservas_pasajeros RP','RP.reserva_id = R.id and RP.responsable = 1');
		$this->db->join('bv_paquetes_rooming PR','PR.reserva_id = R.id','left');
		$this->db->join('bv_pasajeros PA','PA.id = RP.pasajero_id');
		$this->db->join('bv_operadores O','O.id = R.agencia_id','left');

		if($sel){
			$this->db->join('bv_reservas_alarmas alarmas','alarmas.reserva_id = R.id','left');
		}

		$this->db->join('(select i.reserva_id, i.fecha_pago, i.hora_pago
							from bv_reservas_informes_pago i
							inner join (
								select reserva_id, max(id) as id
								from bv_reservas_informes_pago
								group by reserva_id
							) t on t.id = i.id
						) up', 'up.reserva_id = R.id', 'left');
		//mostrar el saldo del usaurio respecto de ese viaje en ARS
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1','t1.usuario_id = R.usuario_id and R.id = t1.reserva_id','left');
		//mostrar el saldo del usaurio respecto de ese viaje en USD
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1usd','t1usd.usuario_id = R.usuario_id and R.id = t1usd.reserva_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en ARS (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2','t2.usuario_id = R.usuario_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en USD (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2usd','t2usd.usuario_id = R.usuario_id','left');
		//este join es para obtener la cantidad de comentarios del viaje en particular (reserva_id)
		$this->db->join('(select count(*) as cantidad, C.reserva_id
								from bv_reservas_comentarios C
								group by C.reserva_id
							) t3','R.id = t3.reserva_id','left');

		//este join es para obtener la cantidad de llamados telefonicos del viaje en particular (reserva_id)
		$this->db->join('(select count(*) as cantidad, C.reserva_id
								from bv_reservas_comentarios C
								join bv_comentarios_tipos_acciones TA on TA.id = C.tipo_id and TA.tipo = "llamado"
								group by C.reserva_id
							) t4','R.id = t4.reserva_id','left');

		//este join es para obtener los informes de pago de la reserva que no tengan movimiento creado
		$this->db->join('(select count(*) as cantidad, I.reserva_id
								from bv_reservas_informes_pago I
								where (I.movimiento_id is null or I.movimiento_id = "")
								group by I.reserva_id
							) inf','R.id = inf.reserva_id','left');

		//este join es para obtener la cantidad de vouchers cargados del pasajero
		$this->db->join('(select count(*) as cantidad, V.reserva_id
								from bv_reservas_vouchers V
								group by V.reserva_id
							) vc','R.id = vc.reserva_id','left');

		//este join es para obtener la cantidad de adicionales contratados
		$this->db->join('(select count(*) as cantidad, A.reserva_id
								from bv_reservas_adicionales A
								group by A.reserva_id
							) adic', 'R.id = adic.reserva_id', 'left');

		//para lista de espera
		$this->db->join("(select R5.paquete_id, sum(R5.pasajeros) as cant from bv_reservas R5 where R5.estado_id = 12 group by R5.paquete_id) t6","t6.paquete_id = P.id","left");

		$this->db->join('bv_lugares_salida S','S.id = R.lugar_id','left');

		if($sucursal_id != '')
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id and SU.id = '.$sucursal_id);
		else
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id','left');

		//27-08-18 join a reservas_grupos para saber si la reserva pertenece a una reserva grupal
		$this->db->join('bv_reservas_grupos RG','RG.reserva_id = R.id','left');

		//25-10-18
		$this->db->join('(select p.id, max(tf.fecha_vencimiento) as fecha_vencimiento
								from bv_transportes_fechas tf
								join bv_paquetes_combinaciones pc on pc.fecha_transporte_id = tf.id
								join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
								group by p.id) cupv','cupv.id = R.paquete_id','left');

		if ($sort != '')
			$this->db->order_by($sort, $type);

		if (@$this->data['keywords'] != ''){
			$this->db->where('(U.nombre like "%'.$this->data['keywords'].'%"
								or U.apellido like "%'.$this->data['keywords'].'%"
								or U.email like "%'.$this->data['keywords'].'%"
								or R.code like "%'.$this->data['keywords'].'%"
							)');
		}

		if($estado_id!=""){
			if(is_array($estado_id))
				$this->db->where_in('R.estado_id',$estado_id);
			else
				$this->db->where(array('R.estado_id'=>$estado_id));
		}

		if($admin_id!="")
			return $this->db->get_where('bv_paquetes P',array('P.activo' => 1, 'R.vendedor_id'=>$admin_id));
		else
			return $this->db->get_where('bv_paquetes P',array('P.activo' => 1));
	}



	function getAllByPaquetesActivosSinAlarmas($estado_id='',$sort='', $type='', $admin_id='',$sucursal_id='',$mail_ajuste_precio='',$sin_tbl=false){
		$sel = $sin_tbl ? '' : ', alarmas.*';

		$this->db->select("R.id, R.code as codigo, R.fecha_reserva, R.fecha_reserva as fecha, R.usuario_id, R.vendedor_id, R.agencia_id, R.estado_id,
							R.sucursal_id, R.lugar_id, R.paquete_id, R.completo_paso3, R.fecha_limite_datos, R.pasajeros, R.fecha_limite_pago_completo,
						   RE.nombre as estado, RE.horas_vencimiento, RE.color,
						   P.precio_usd, P.cantidad_vouchers, P.dias_faltan_vouchers, P.fecha_inicio as fechaSalida, P.fecha_fin as fechaRegreso, P.dias_completar_datos, P.confirmacion_inmediata,
						   P.operador_id, P.fecha_limite_vouchers,
						   PA.nombre, PA.apellido, PA.email, concat(PA.celular_codigo,' ',PA.celular_numero) as celular,
						   PA.celular_codigo, PA.celular_numero, PA.fecha_nacimiento as fechaNacimiento, PA.pasaporte,
						   PI.nombre as nacionalidad, PA.dni, 'DNI' as dniTipo,
						   ifnull(t1.cantidad,0) as saldo,
						   ifnull(t1usd.cantidad,0) as saldo_usd,
						   ifnull(t2.cantidad,0) as saldo_viaje,
						   ifnull(t2usd.cantidad,0) as saldo_viaje_usd,
						   O.nombre as operador,
						   S.nombre as lugar_salida,
						   (case when T.tipo_id = 1 then 1 else 0 end) as en_avion,
						   PC.c_costo_operador,
						   R.combinacion_id,
						   ifnull(adic.cantidad,0) as adicionales,
						   ifnull(t6.cant,0) as en_lista_de_espera,
						   RG.codigo_grupo,
						   PR.alojamiento_fecha_cupo_id as rooming_cupo_id, PC.fecha_alojamiento_cupo_id,
						   ifnull(cupv.fecha_vencimiento,'0000-00-00') as fecha_vencimiento,
						   CONCAT(up.fecha_pago, ' ', up.hora_pago) as fecha_informe_pago".$sel,false);

		$this->db->join('bv_reservas R', 'R.paquete_id = P.id');
		$this->db->join('bv_paquetes_combinaciones PC','PC.id = R.combinacion_id');
		$this->db->join('bv_transportes T','T.id = PC.transporte_id','left');
		$this->db->join('bv_usuarios U','U.id = R.usuario_id');
		$this->db->join('bv_paises PI','PI.id = U.nacionalidad_id','left');
		$this->db->join('bv_reservas_estados RE','RE.id = R.estado_id');
		$this->db->join('bv_reservas_pasajeros RP','RP.reserva_id = R.id and RP.responsable = 1');
		$this->db->join('bv_paquetes_rooming PR','PR.reserva_id = R.id','left');
		$this->db->join('bv_pasajeros PA','PA.id = RP.pasajero_id');
		$this->db->join('bv_operadores O','O.id = R.agencia_id','left');

		if($sel){
			$this->db->join('bv_reservas_alarmas alarmas','alarmas.reserva_id = R.id','left');
		}

		$this->db->join('(select i.reserva_id, i.fecha_pago, i.hora_pago
							from bv_reservas_informes_pago i
							inner join (
								select reserva_id, max(id) as id
								from bv_reservas_informes_pago
								group by reserva_id
							) t on t.id = i.id
						) up', 'up.reserva_id = R.id', 'left');
		//mostrar el saldo del usaurio respecto de ese viaje en ARS
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1','t1.usuario_id = R.usuario_id and R.id = t1.reserva_id','left');
		//mostrar el saldo del usaurio respecto de ese viaje en USD
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1usd','t1usd.usuario_id = R.usuario_id and R.id = t1usd.reserva_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en ARS (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2','t2.usuario_id = R.usuario_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en USD (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2usd','t2usd.usuario_id = R.usuario_id','left');

		//este join es para obtener la cantidad de adicionales contratados
		$this->db->join('(select count(*) as cantidad, A.reserva_id
								from bv_reservas_adicionales A
								group by A.reserva_id
							) adic', 'R.id = adic.reserva_id', 'left');

		//para lista de espera
		$this->db->join("(select R5.paquete_id, sum(R5.pasajeros) as cant from bv_reservas R5 where R5.estado_id = 12 group by R5.paquete_id) t6","t6.paquete_id = P.id","left");

		$this->db->join('bv_lugares_salida S','S.id = R.lugar_id','left');

		if($sucursal_id != '')
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id and SU.id = '.$sucursal_id);
		else
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id','left');

		//27-08-18 join a reservas_grupos para saber si la reserva pertenece a una reserva grupal
		$this->db->join('bv_reservas_grupos RG','RG.reserva_id = R.id','left');

		//25-10-18
		$this->db->join('(select p.id, max(tf.fecha_vencimiento) as fecha_vencimiento
								from bv_transportes_fechas tf
								join bv_paquetes_combinaciones pc on pc.fecha_transporte_id = tf.id
								join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
								group by p.id) cupv','cupv.id = R.paquete_id','left');

		if ($sort != '')
			$this->db->order_by($sort, $type);

		if (@$this->data['keywords'] != ''){
			$this->db->where('(U.nombre like "%'.$this->data['keywords'].'%"
								or U.apellido like "%'.$this->data['keywords'].'%"
								or U.email like "%'.$this->data['keywords'].'%"
								or R.code like "%'.$this->data['keywords'].'%"
							)');
		}

		if($estado_id!=""){
			if(is_array($estado_id))
				$this->db->where_in('R.estado_id',$estado_id);
			else
				$this->db->where(array('R.estado_id'=>$estado_id));
		}

		if($admin_id!="")
			return $this->db->get_where('bv_paquetes P',array('P.activo' => 1, 'R.vendedor_id'=>$admin_id));
		else
			return $this->db->get_where('bv_paquetes P',array('P.activo' => 1));
	}

	/*
	Obtiene las reservas segun los ID de combianciones enviados que tengan saldo pendiente
	Con estado NUEVA ó POR VENCER
	*/
	function getConfirmadasConSaldoCombinacion($ids){
		$this->db->select('r.*, p.nombre, p.codigo, p.precio_usd,
							(case p.precio_usd when 0 then ifnull(x.saldo,0) else ifnull(xx.saldo,0) end) as saldo');
		$this->db->join('bv_paquetes p','p.id = r.paquete_id');
		//SALDO PENDIENTE DE PAGO POR RESERVA
		// en ars
		$this->db->join('(select R.id, SUM(M.debe)-SUM(M.haber) as saldo
						from bv_movimientos M, bv_reservas R
						where R.usuario_id = M.usuario_id and M.tipoUsuario = "U"
							and R.estado_id IN (1,2) and R.id = M.reserva_id and M.cta_usd = 0
						group by R.id
						having saldo > 0) x','x.id = r.id','left');
		// en usd
		$this->db->join('(select R.id, SUM(M.debe_usd)-SUM(M.haber_usd) as saldo
						from bv_movimientos M, bv_reservas R
						where R.usuario_id = M.usuario_id and M.tipoUsuario = "U"
							and R.estado_id IN (1,2) and R.id = M.reserva_id and M.cta_usd = 1
						group by R.id
						having saldo > 0) xx','xx.id = r.id','left');
		$this->db->where('r.estado_id IN (1,2) and r.combinacion_id IN ('.implode(',',$ids).')');
		return $this->db->get($this->table.' r')->result();
	}

  	/*
	Obtiene reservas con saldo pendiente en estado nueva o por acreditar y que tengan el adicional que estoy actualizando precio
  	*/
  	function getConAdicionalSaldo($paq_adicional_id){
		$this->db->select('r.*, p.nombre as paquete_nombre, p.codigo as paquete_codigo, p.precio_usd,
							(case p.precio_usd when 0 then ifnull(x.saldo,0) else ifnull(xx.saldo,0) end) as saldo');
		$this->db->join('bv_paquetes p','p.id = r.paquete_id');
		//SALDO PENDIENTE DE PAGO POR RESERVA
		// en ars
		$this->db->join('(select R.id, SUM(M.debe)-SUM(M.haber) as saldo
						from bv_movimientos M, bv_reservas R
						where R.usuario_id = M.usuario_id and M.tipoUsuario = "U"
							and R.estado_id IN (1,2) and R.id = M.reserva_id and M.cta_usd = 0
						group by R.id
						having saldo > 0) x','x.id = r.id','left');
		// en usd
		$this->db->join('(select R.id, SUM(M.debe_usd)-SUM(M.haber_usd) as saldo
						from bv_movimientos M, bv_reservas R
						where R.usuario_id = M.usuario_id and M.tipoUsuario = "U"
							and R.estado_id IN (1,2) and R.id = M.reserva_id and M.cta_usd = 1
						group by R.id
						having saldo > 0) xx','xx.id = r.id','left');
		$this->db->join('bv_reservas_adicionales ra','ra.reserva_id = r.id');
		$this->db->join('bv_paquetes_adicionales pa','pa.id = ra.paquete_adicional_id');
		$this->db->join('bv_adicionales a','a.id = pa.adicional_id');

		//con las NO anuladas? o solo las estado NUEVA Y POR VENCER como el otro caso de ajuste de precio
		$this->db->where('r.estado_id != 5 and ra.paquete_adicional_id = '.$paq_adicional_id);
		//$this->db->where('r.estado_id IN (1,2) and ra.paquete_adicional_id = '.$paq_adicional_id);
		return $this->db->get($this->table.' r')->result();
	}

    //este metodo cuenta las reservas y trae cantidad y cupo según las reservas hechas que no esten ni ANULADAS NI EN LISTA DE ESPERA
	function countByCombinacion($combinacion_id,$reserva_id=''){
		if($reserva_id != ''){
			 $q = "SELECT p.id, p.cupo, ifnull(t1.cantidad,0) as cantidad
				FROM bv_paquetes_combinaciones p
				left join (select combinacion_id, count(*) as cantidad from bv_reservas where id != ".$reserva_id." and estado_id != 5 and usuario_id > 0 group by combinacion_id) t1 on t1.combinacion_id = p.id
				where p.id = '".$combinacion_id."'";
		}
		else{
			$q = "SELECT p.id, p.cupo, ifnull(t1.cantidad,0) as cantidad
				FROM bv_paquetes_combinaciones p
				left join (select combinacion_id, count(*) as cantidad from bv_reservas where estado_id != 5 and usuario_id > 0 group by combinacion_id) t1 on t1.combinacion_id = p.id
				where p.id = '".$combinacion_id."'";
		}

		return $this->db->query($q);
	}

	function eliminarAdicional($reserva_id,$paquete_adicional_id){
		$this->db->where(array('reserva_id'=>$reserva_id,'paquete_adicional_id'=>$paquete_adicional_id));
		$this->db->delete('bv_reservas_adicionales');
		return true;
	}

	function agregarAdicional($reserva_id,$paquete_adicional_id,$valor_adicional=false){
		$data = array('reserva_id'=>$reserva_id,'paquete_adicional_id'=>$paquete_adicional_id);
		if($valor_adicional){
			$data['valor'] = $valor_adicional;
		}
		$this->db->insert('bv_reservas_adicionales',$data);
		return $this->db->insert_id();
	}

	function getAdicional($reserva_id,$paquete_adicional_id){
		return $this->db->get_where('bv_reservas_adicionales',array('reserva_id'=>$reserva_id,'paquete_adicional_id'=>$paquete_adicional_id))->row();
	}

	function updateAdicional($reserva_id,$paquete_adicional_id,$data){
		$this->db->where(array('reserva_id'=>$reserva_id,'paquete_adicional_id'=>$paquete_adicional_id));
		return $this->db->update('bv_reservas_adicionales',$data);
	}

	//metodo que devuelve el saldo pendiente de la reserva del usuario
	function getSaldoReserva($id){
		$this->db->select("R.id,
						   ifnull(t1.cantidad,0) as saldo,
						   ifnull(t1usd.cantidad,0) as saldo_usd,
						   count(t1.cantidad) as cant_pagos_ars,
						   count(t1usd.cantidad) as cant_pagos_usd",false);

		//mostrar el saldo del usaurio respecto de ese viaje en ARS
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1','t1.usuario_id = R.usuario_id and R.id = t1.reserva_id','left');
		//mostrar el saldo del usaurio respecto de ese viaje en USD
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1usd','t1usd.usuario_id = R.usuario_id and R.id = t1usd.reserva_id','left');

		return $this->db->get_where($this->table.' R',array('R.id'=>$id))->row();
	}

	/*
	CUANDO SE EXPORTA SE NECESITAN ESTOS CAMPOS
	APELLIDO
	NOMBRE
	CELULAR
	EMAIL
	DNI_NUMERO
	PASAPORTE_NUMERO
	PASAPORTE_EMISION
	PASAPORTE_VENCIMIENTO
	NACIMIENTO
	NACIONALIDAD
	MENU
	SEXO
	FECHA_RESERVA
	ADICIONALES
	LUGAR_SALIDA
	PARADA
	COMENTARIO
	PENSION
	VIAJE
	ID_VENDEDOR
	VENDEDOR_NOMBRE
	VENDEDOR_APELLIDO
	*/
	function getAllAlarmasExport($sort='', $type='', $admin_id='',$sucursal_id='',$mail_ajuste_precio='',$limit=99999,$offset=0,$sin_tbl=false){
		$sel = $sin_tbl ? "" : ", alarmas.*";

		$this->db->select("CONCAT(P.nombre,' - Cód: ',P.codigo) as paquete, PA.apellido, PA.nombre, CONCAT(PA.celular_codigo,PA.celular_numero) as celular, PA.email, PA.dni,
			PA.pasaporte, PA.fecha_emision, PA.fecha_vencimiento, PA.fecha_nacimiento,
			PII.nombre as nacionalidad, PA.dieta as menu, PA.sexo, R.fecha_reserva,R.fecha_reserva as fecha,
			ifnull(adic.adicionales,'') as adicionales, S.nombre as lugar_salida, x.nombre as parada, R.comentario,
			regi.nombre as pension, R.vendedor_id, V.nombre as vendedor_nombre, V.apellido as venedor_apellido,
			ifnull(inf.cantidad,0) as informes, RE.nombre as estado_reserva".$sel, false);

		return $this->get_all_alarmas($sort, $type, $admin_id,$sucursal_id,$mail_ajuste_precio,$limit,$offset,$sin_tbl,$sel);
	}

	//ESta se usa para mostrar listado en backend
	function getAllAlarmas($sort='', $type='', $admin_id='',$sucursal_id='',$mail_ajuste_precio='',$limit=99999,$offset=0,$sin_tbl=false){
		$sel = $sin_tbl ? "" : ", alarmas.*";

		$this->db->select("R.id, R.code as codigo, R.fecha_reserva, R.fecha_reserva as fecha, R.usuario_id, R.vendedor_id, R.agencia_id, R.estado_id,
							R.sucursal_id, R.lugar_id, R.paquete_id, R.completo_paso3, R.fecha_limite_datos, R.pasajeros, R.fecha_limite_pago_completo,
						   RE.nombre as estado, RE.horas_vencimiento, RE.color,
						   P.precio_usd, P.cantidad_vouchers, P.dias_faltan_vouchers, P.fecha_inicio as fechaSalida, P.fecha_fin as fechaRegreso, P.dias_completar_datos, P.confirmacion_inmediata, P.nombre as paquete, P.codigo as paquete_codigo,
						   P.operador_id, P.fecha_limite_vouchers,
						   PA.nombre, PA.apellido, PA.email, concat(PA.celular_codigo,' ',PA.celular_numero) as celular,
						   PA.celular_codigo, PA.celular_numero, PA.fecha_nacimiento as fechaNacimiento,
						   PI.nombre as nacionalidad, PA.dni, 'DNI' as dniTipo,
						   ifnull(t1.cantidad,0) as saldo,
						   ifnull(t1usd.cantidad,0) as saldo_usd,
						   ifnull(t2.cantidad,0) as saldo_viaje,
						   ifnull(t2usd.cantidad,0) as saldo_viaje_usd,
						   (case when t3.cantidad > 0 then t3.cantidad
														else 0
							end) as cantidad_comentarios,
						   (case when t4.cantidad > 0 then t4.cantidad
														else 0
							end) as cantidad_llamados,
						   O.nombre as operador,
						   S.nombre as lugar_salida,
						   (case when T.tipo_id = 1 then 1 else 0 end) as en_avion,
						   ifnull(vc.cantidad,0) as vouchers_cargados,
						   ifnull(inf.cantidad,0) as informes_de_pago,
						   PC.c_costo_operador,
						   R.combinacion_id,
						   ifnull(cupv.fecha_vencimiento,'0000-00-00') as fecha_vencimiento".$sel, false);

		return $this->get_all_alarmas($sort, $type, $admin_id,$sucursal_id,$mail_ajuste_precio,$limit,$offset,$sin_tbl,$sel);
	}

	function get_all_alarmas($sort, $type, $admin_id,$sucursal_id,$mail_ajuste_precio,$limit,$offset,$sin_tbl,$sel=false){
		$this->db->join('bv_paquetes P','P.id = R.paquete_id and P.activo = 1');
		$this->db->join('bv_paquetes_combinaciones PC','PC.id = R.combinacion_id');
		$this->db->join('bv_transportes T','T.id = PC.transporte_id','left');
		$this->db->join('bv_usuarios U','U.id = R.usuario_id');
		$this->db->join('bv_paises PI','PI.id = U.nacionalidad_id','left');
		$this->db->join('bv_reservas_pasajeros RP','RP.reserva_id = R.id','left');
		$this->db->join('bv_pasajeros PA','PA.id = RP.pasajero_id and RP.responsable = 1','left');
		$this->db->join('bv_paises PII','PII.id = PA.nacionalidad_id','left');
		$this->db->join('bv_reservas_estados RE','RE.id = R.estado_id');
		$this->db->join('bv_operadores O','O.id = R.agencia_id','left');
		$this->db->join('bv_paquetes_paradas pp', 'pp.paquete_id = P.id and pp.id = R.paquete_parada_id','left');
		$this->db->join('bv_paradas x', 'x.id = pp.parada_id','left');
		$this->db->join('bv_paquetes_regimenes pr', 'pr.id = R.paquete_regimen_id and pr.paquete_id = P.id','left');
		$this->db->join('bv_regimenes regi', 'regi.id = pr.regimen_id','left');
		$this->db->join('bv_vendedores V','V.id = R.vendedor_id','left');

		if($sel){
			$this->db->join('bv_reservas_alarmas alarmas','alarmas.reserva_id = R.id','left');
		}

		//mostrar el saldo del usaurio respecto de ese viaje en ARS
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1','t1.usuario_id = R.usuario_id and R.id = t1.reserva_id','left');
		//mostrar el saldo del usaurio respecto de ese viaje en USD
		$this->db->join('(select M.reserva_id, M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id, M.reserva_id
							) t1usd','t1usd.usuario_id = R.usuario_id and R.id = t1usd.reserva_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en ARS (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe)-SUM(M.haber) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2','t2.usuario_id = R.usuario_id','left');
		//para mostrar el saldo de la cuenta cte del usuario en USD (ahora NO respecto del viaje)
		$this->db->join('(select M.usuario_id, SUM(M.debe_usd)-SUM(M.haber_usd) as cantidad
								from bv_movimientos M
								where M.tipoUsuario = "U"
								group by M.usuario_id
							) t2usd','t2usd.usuario_id = R.usuario_id','left');
		//este join es para obtener la cantidad de comentarios del viaje en particular (reserva_id)
		$this->db->join('(select count(*) as cantidad, C.reserva_id
								from bv_reservas_comentarios C
								group by C.reserva_id
							) t3','R.id = t3.reserva_id','left');

		//este join es para obtener la cantidad de llamados telefonicos del viaje en particular (reserva_id)
		$this->db->join('(select count(*) as cantidad, C.reserva_id
								from bv_reservas_comentarios C
								join bv_comentarios_tipos_acciones TA on TA.id = C.tipo_id and TA.tipo = "llamado"
								group by C.reserva_id
							) t4','R.id = t4.reserva_id','left');

		//este join es para obtener los informes de pago de la reserva que no tengan movimiento creado
		$this->db->join('(select count(*) as cantidad, R.id
								from bv_reservas_informes_pago I
								join bv_reservas R on R.id = I.reserva_id
								where (I.movimiento_id is null or I.movimiento_id = "")
								group by R.id
							) inf','R.id = inf.id','left');

		//este join es para obtener la cantidad de vouchers cargados del pasajero
		$this->db->join('(select count(*) as cantidad, R.id
								from bv_reservas_vouchers V
								join bv_reservas R on R.id = V.reserva_id
								group by R.id
							) vc','R.id = vc.id','left');

		$this->db->join('bv_lugares_salida S','S.id = R.lugar_id','left');

		//25-10-18
		$this->db->join('(select p.id, max(tf.fecha_vencimiento) as fecha_vencimiento
								from bv_transportes_fechas tf
								join bv_paquetes_combinaciones pc on pc.fecha_transporte_id = tf.id
								join bv_paquetes p on p.id = pc.paquete_id and p.activo = 1
								group by p.id) cupv','cupv.id = R.paquete_id','left');

		//este join es para obtener los adicionales contratados
		$this->db->join('(select GROUP_CONCAT(AD.nombre SEPARATOR ", ") as adicionales, R.id
								from bv_reservas_adicionales A
								join bv_reservas R on R.id = A.reserva_id
								join bv_paquetes_adicionales PA on PA.paquete_id = R.paquete_id
								join bv_adicionales AD on AD.id = PA.adicional_id
								group by R.id
							) adic', 'R.id = adic.id', 'left');

		if($sucursal_id != '')
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id and SU.id = '.$sucursal_id);
		else
			$this->db->join('bv_sucursales SU','SU.id = R.sucursal_id','left');

		if ($sort != '')
			$this->db->order_by($sort, $type);

		if (@$this->data['keywords'] != ''){
			$this->db->where('(U.nombre like "%'.$this->data['keywords'].'%"
								or U.apellido like "%'.$this->data['keywords'].'%"
								or U.email like "%'.$this->data['keywords'].'%"
								or R.code like "%'.$this->data['keywords'].'%"
							)');
		}

		$this->db->limit($limit);
		$this->db->offset($offset);
		if($admin_id!="")
			return $this->db->get_where($this->table.' R',array("R.vendedor_id"=>$admin_id));
		else
			return $this->db->get($this->table.' R');
	}

	function updateFechaLimiteVoucher($paquete_id,$fecha_limite_vouchers){
		$this->db->query("update bv_reservas set fecha_limite_vouchers = '".$fecha_limite_vouchers."' where estado_id != 5 and paquete_id = ".$paquete_id);
	}
	function updateFechaLimitePagoCompleto($paquete_id,$fecha_limite_pago_completo){
		$this->db->query("update bv_reservas set fecha_limite_pago_completo = '".$fecha_limite_pago_completo."' where estado_id != 5 and paquete_id = ".$paquete_id);
	}

	/*
	Devuelve las reservas de pasajeros que NO ESTÉN ANULADAS
	*/
	function getPasajerosConfirmados($paquete_id) {
		return $this->db->query("
			SELECT r.id, r.estado_id, r.code, r.habitacion_id, r.pasajeros as pax, GROUP_CONCAT(CONCAT(pax.nombre, ' ', pax.apellido) SEPARATOR ', ') as pasajeros, gr.codigo_grupo, h.nombre as habitacion, h.pax as hab_pax, ifnull(t.confirmadas,0) as confirmadas_grupo, pr.alojamiento_fecha_cupo_id as rooming_cupo_id, pr.nro_habitacion as rooming_nro_hab,
				a.nombre as alojamiento
			from bv_reservas r
			inner join bv_reservas_pasajeros rp on rp.reserva_id = r.id
			inner join bv_pasajeros pax on pax.id = rp.pasajero_id
			inner join bv_habitaciones h on h.id = r.habitacion_id
			join bv_alojamientos a on a.id = r.alojamiento_id
			left join bv_reservas_grupos gr on gr.reserva_id = r.id
			left join (select g.codigo_grupo, r.paquete_id, sum(case when r.estado_id != 5 then 1 else 0 end) as confirmadas
						from bv_reservas r
						join bv_reservas_grupos g on g.reserva_id = r.id
						group by g.codigo_grupo, r.paquete_id) t on t.codigo_grupo = gr.codigo_grupo and t.paquete_id = r.paquete_id
			left join bv_paquetes_rooming pr on pr.paquete_id = r.paquete_id and pr.reserva_id = r.id
			where r.paquete_id = $paquete_id and r.estado_id != 5
			group by r.id, r.habitacion_id
		")->result();
	}

	function getReservasVendedor($limit='', $offset='',$sort='', $type='',$filters=[]){
		$vendedor_id = userloggedId();

		$str_where = "v.id = $vendedor_id";

		/*
		if(isset($filters['anio']) && $filters['anio']){
			$where .= ' and YEAR(conf.fecha_confirmada) = "'.$filters['anio'].'"';
		}
		if(isset($filters['mes']) && $filters['mes']){
			$where .= ' and MONTH(conf.fecha_confirmada) = "'.$filters['mes'].'"';
		}
		*/
		//filstros por fechas, por fecha de confirmacion o fecha de reserva
		$wh_fc = '(1=1 ';
		$wh_fr = '(conf.fecha_confirmada is null ';
		if(isset($filters['anio']) && $filters['anio']){
			#$this->db->where('YEAR(conf.fecha_confirmada) = "'.$filters['anio'].'"');
			$wh_fc .= ' and YEAR(conf.fecha_confirmada) = "'.$filters['anio'].'"';
			$wh_fr .= ' and YEAR(R.fecha_reserva) = "'.$filters['anio'].'"';
		}
		if(isset($filters['mes']) && $filters['mes']){
			#$this->db->where('MONTH(conf.fecha_confirmada) = "'.$filters['mes'].'"');
			$wh_fc .= ' and MONTH(conf.fecha_confirmada) = "'.$filters['mes'].'"';
			$wh_fr .= ' and MONTH(R.fecha_reserva) = "'.$filters['mes'].'"';
		}
		$wh_fc .= ')';
		$wh_fr .= ')';
		$where = $wh_fc.' or '.$wh_fr;

		$str_where .= ' and ('.$where.')';

		$cant = $this->db->query("SELECT count(distinct R.id) as cant
			FROM bv_reservas R
			JOIN bv_vendedores v ON v.id = R.vendedor_id
			left JOIN bv_comisiones_liquidaciones_reservas clr ON clr.reserva_id = R.id
			left JOIN bv_comisiones_liquidaciones cl ON cl.id = clr.liquidacion_id and cl.usuario = concat(v.nombre,' ' ,v.apellido)
			left JOIN (select m.reserva_id, min(fecha) as fecha_confirmada from bv_movimientos m inner join bv_conceptos c on c.nombre = m.concepto
			where m.tipoUsuario = 'U' and c.pasa_a_confirmada = 1 group by m.reserva_id ) conf ON conf.reserva_id = R.id
			WHERE $str_where
			limit 1")->row();

		$cantidad = isset($cant->cant) && $cant->cant > 0 ? $cant->cant : 0;

		//si detecto q hay liquidaciones defino el tipo de join
		//$tipojoin = $cantidad>0 ? '' : 'left';
		$tipojoin = 'left';

		/*
		comision_estado:
		0 pendiente, 1 liquidada
		comision_monto: hay que calcularlo
		*/

		$this->db->select("R.*,
						PA.nombre, PA.apellido, PA.email,
						case when cl.confirmada then 1 else 0 end as comision_estado,
						cl.id as liquidacion_id,
						cl.fecha_confirmada,
						cl.total_comision as comision_monto,
						p.v_total,
						p.precio_usd,
						p.monto_comisionable",false);

		$this->db->join('bv_reservas_pasajeros RP','RP.reserva_id = R.id');
		$this->db->join('bv_vendedores v','v.id = R.vendedor_id');
		$this->db->join('bv_paquetes p','p.id = R.paquete_id');
		$this->db->join('bv_pasajeros PA','PA.id = RP.pasajero_id');
		$this->db->join('(select m.reserva_id, min(fecha) as fecha_confirmada
														from bv_movimientos m
														inner join bv_conceptos c on c.nombre = m.concepto
														where m.tipoUsuario = "U" and c.pasa_a_confirmada = 1
														group by m.reserva_id
													) conf','conf.reserva_id = R.id',$tipojoin);
		$this->db->join('bv_comisiones_liquidaciones_reservas clr','clr.reserva_id = R.id',$tipojoin);
		$this->db->join('bv_comisiones_liquidaciones cl','cl.id = clr.liquidacion_id and cl.usuario = concat(v.nombre," ",v.apellido)',$tipojoin);

		if(isset($filters['estado']) && $filters['estado']){
			$status = ($filters['estado'] == 'Liquidada' ? 1 : 0);

			$this->db->having('comision_estado = "'.$status.'"');
		}

		//filstros por fechas, por fecha de confirmacion o fecha de reserva
		$wh_fc = '(1=1 ';
		$wh_fr = '(conf.fecha_confirmada is null ';
		if(isset($filters['anio']) && $filters['anio']){
			#$this->db->where('YEAR(conf.fecha_confirmada) = "'.$filters['anio'].'"');
			$wh_fc .= ' and YEAR(conf.fecha_confirmada) = "'.$filters['anio'].'"';
			$wh_fr .= ' and YEAR(R.fecha_reserva) = "'.$filters['anio'].'"';
		}
		if(isset($filters['mes']) && $filters['mes']){
			#$this->db->where('MONTH(conf.fecha_confirmada) = "'.$filters['mes'].'"');
			$wh_fc .= ' and MONTH(conf.fecha_confirmada) = "'.$filters['mes'].'"';
			$wh_fr .= ' and MONTH(R.fecha_reserva) = "'.$filters['mes'].'"';
		}
		$wh_fc .= ')';
		$wh_fr .= ')';
		$where = $wh_fc.' or '.$wh_fr;

		$this->db->where('('.$where.')');

		$this->db->group_by('R.id');
		return $this->db->get_where($this->table.' R',array('vendedor_id'=>$vendedor_id))->result();
	}

	function getReservasActivasGrupo($codigo,$count=false){
		if($count){
			$this->db->select("count(distinct g.id) as cantidad",false);
		}
		else{
			$this->db->select("g.*, r.estado_id",false);
		}

		//de las reservas que no estén anuladas
		$this->db->join("bv_reservas r","r.id = g.reserva_id");// and r.estado_id != 5
		$this->db->where("g.codigo_grupo",$codigo);
		if($count){
			return $this->db->get("bv_reservas_grupos g")->row();
		}
		else{
			return $this->db->get("bv_reservas_grupos g")->result();
		}
	}

	function get_lista_de_espera($paquete_id){
		return $this->db->query("select R5.*
								from ".$this->table." R5
								where R5.estado_id = 12 and R5.paquete_id = ".$paquete_id)->result();
	}

}
