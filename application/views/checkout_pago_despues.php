<?=$header;?>

  <?=@$settings->script_conversion;?>
  
  <!-- CUERPO -->

  <div id="main">

    <div class="container">
      <h1>¡Reserva generada!</h1>
    </div>


    <div class="aside_content container">
      <div class="contenido">

        <div class="info_compra">
          <? if(isset($pasajeros_grupo) && $pasajeros_grupo>1): ?>
            <div class="pago_generado">
              <p><strong>Tu reserva fue generada correctamente.</strong> Recordá que tanto vos como tus acompañantes cuentan con <strong>24 hs.</strong> para realizar el pago total o el pago mínimo de <strong><?=precio($combinacion->monto_minimo_reserva * $reserva->pasajeros, $combinacion->precio_usd, $bold=true,$alternate=false, $numeric=false);?></strong> para que cada una de sus reservas queden confirmadas. En unos minutos recibirán un e-mail con un enlace directo a sus reservas para poder gestionarlas. Fijate también en CORREO NO DESEADO.<br>Por favor verificá que tus datos sean correctos.</p>
            </div>
          <? else: ?>
            <div class="pago_generado">
              <p><strong>Tu reserva fue generada correctamente.</strong> Recordá que <?=(isset($pasajeros_grupo) && $pasajeros_grupo>1)?'tanto vos como tus acompañantes cuentan':'contás';?> con <strong>24 hs.</strong> para realizar el pago total o el pago mínimo de <strong><?=precio($combinacion->monto_minimo_reserva * $reserva->pasajeros, $combinacion->precio_usd, $bold=true,$alternate=false, $numeric=false);?></strong> para que tu reserva quede confirmada. En unos minutos recibirás un e-mail con un enlace directo a tu reserva para poder gestionarla. Fijate también en CORREO NO DESEADO.<br>Por favor verificá que tus datos sean correctos.</p>
            </div>
          <? endif; ?>

          <h2>Detalle de tu reserva</h2>

          <div class="row">
            <div class="col-xs-12 col-md-6">
              <p><strong>Destino:</strong> <span><?=$combinacion->destino;?>.</span></p>
              <? if($combinacion->itinerario && file_exists('./uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario)): ?>
              <p><strong>Excursiones y/o actividades:</strong> <span><a href="<?=base_url().'uploads/paquetes/'.$combinacion->paquete_id.'/'.$combinacion->itinerario;?>">Descargar itinerario</a>.</span></p>
              <? endif; ?>
              <p><strong>Pasajeros:</strong> <span><?=(isset($pasajeros_grupo) && $pasajeros_grupo>1)?$pasajeros_grupo:$reserva->pasajeros;?></span></p>
              <p><strong>Lugar de salida:</strong> <span><?=$combinacion->lugar;?></span></p>
            
				<? 
				$f_inicio = ($combinacion->fecha_salida < $combinacion->fecha_checkin) ? $combinacion->fecha_salida : $combinacion->fecha_checkin; 
				$f_fin = ($combinacion->fecha_regreso > $combinacion->fecha_checkout) ? $combinacion->fecha_regreso : $combinacion->fecha_checkout; 
				?>
							
              <? //defino la fecha a mostrar
              if($combinacion->fecha_indefinida && $combinacion->mostrar_calendario): ?>
                <? $fecha_elegida = fecha_completa(formato_fecha($reserva->fecha));?>
              <? else: ?>
                <? $fecha_elegida = fecha_completa(formato_fecha($f_inicio),formato_fecha($f_fin));?> 
              <? endif; ?>  
              
              <p><strong><?=dias_viaje($combinacion->fecha_indefinida?$combinacion->fecha_checkin:$combinacion->fecha_inicio,$combinacion->fecha_indefinida?$combinacion->fecha_checkout:$combinacion->fecha_fin);?> días - <?=noches_viaje($combinacion->fecha_checkin,$combinacion->fecha_checkout)?> noches:</strong> <span><?=$fecha_elegida;?>.</span></p>
              <p><strong>Transporte:</strong> <span><?=$combinacion->transporte;?></span></p>
            </div>

            <div class="col-xs-12 col-md-6">
              <p><strong>Alojamiento:</strong> <span><?=$combinacion->alojamiento;?></span></p>
              <p><strong>Tipo de habitación:</strong> <span><?=$combinacion->habitacion;?></span></p>
              <p><strong>Pensión:</strong> <span><?=$combinacion->regimen;?></span></p>
              <? if($reserva->nombre_adicionales): ?>
                <p><strong>Adicionales:</strong> <span><?=$reserva->nombre_adicionales;?>.</span></p>
              <? endif; ?>
			  <p><span><a href="<?=site_url('reservas/resumen/'.encriptar($reserva->code));?>">Ver más info del viaje</a></span></p>
            </div>
          </div>
        </div>


        <!-- DETALLE DE TU COMPRA -->
          
        <div class="detalle_compra">
          <div>
          
            <div class="detalle_aside">

              <div class="reserva">
                <p class="dato">PASAJEROS<span class="hidden-md hidden-lg">:</span> <span><?=(isset($pasajeros_grupo) && $pasajeros_grupo>1)?$pasajeros_grupo:$reserva->pasajeros;?></span></p>
                
                <p class="precio_final">
                  
                  <? if(isset($pasajeros_grupo) && $pasajeros_grupo>1): ?>
                    <span>TOTAL<span class="hidden-md hidden-lg">:</span></span> <?=strip_tags(precio($precios['num']['precio_total']*$pasajeros_grupo,$reserva->precio_usd),'<sup><strong>');?>
                  <? else: ?>
                  <span>TOTAL<span class="hidden-md hidden-lg">:</span></span> <?=strip_tags($precios['precio_total'],'<sup><strong>');?>
                  <? endif; ?>
                </p>

                
                <div class="info_detalle">
                  <p>Precio x persona</p>
                  <p><?=$precios['precio_bruto_persona'];?></p>

                  <div class="clearfix"></div>
                  <p>Imp. x persona</p>
                  <p><?=$precios['precio_impuestos_persona'];?></p>
                </div>

              </div>

            </div>
          
          </div>
        </div>
        
        <!-- FIN DETALLE DE TU COMPRA -->
  
      </div>

    </div>

  </div>

  <!-- FIN CUERPO -->

<?=$footer;?>