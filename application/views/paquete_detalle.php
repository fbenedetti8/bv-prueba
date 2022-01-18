
	<? //si quiere mostrar cupo personalizado, piso los datos reales
	if($paquete->cupo_paquete_personalizado): 
		$paquete->cupo_disponible = $paquete->cupo_paquete_disponible>=((isset($pax_elegidos)&&$pax_elegidos>0)?$pax_elegidos:$combinacion->pax) ? $paquete->cupo_paquete_disponible : FALSE;
		$paquete->cupo_total = $paquete->cupo_paquete_total;
	endif; 
	
	if(date('Y-m-d') > $paquete->fecha_inicio):
		$paquete->cupo_disponible = 0;	
	endif;
	?>

			<article id="ticket" class="ticket relative">
			  <div class="ticket__sticky-center">
				<div class="ticket__sticky-width">
				  <div id="ticket-detail" class="ticket__detail">
					<div class="ticket__detail__estimado relative">
						<div class="moneda_button">
							<span>Moneda</span>

							<label class="moneda_block">
								<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="ARS" <?=!$combinacion->precio_usd?'checked':'';?>/>
								<span>ARS</span>
							</label>

							<label class="moneda_block">
								<input type="radio" name="tipo_moneda" class="input_tipo_moneda" value="USD" <?=$combinacion->precio_usd?'checked':'';?>/>
								<span>USD</span>
							</label>
						</div>

					  <div>
						<p class="estimado__label-dp">Ahora</p>
						<p class="estimado__label-mb">Estimado total</p>
						<p class="estimado__precio"><comb id="precio_final_persona"><?=precio_redondeado($combinacion->precio_total,$combinacion->precio_usd);?></comb></p>
						<p class="estimado__imp">+<comb id="precio_impuestos_persona"><?=precio_redondeado($combinacion->precio_impuestos,$combinacion->precio_usd);?></comb> imp</p>
					  </div>
					  <a class="estimado__ocultar-detalles--dp isOpen">detalles</a>
					</div>
					<div class="ticket__detail__desgloce" style="display: none">
					  <div class="desgloce__item clearfix">
						<p class="desgloce__item__label"><comb class="pax"><?=(isset($pax_elegidos)&&$pax_elegidos>0)?$pax_elegidos:$combinacion->pax;?></comb> personas</p>
						<p class="desgloce__item__cantidad"><comb id="precio_bruto"><?=strip_tags(str_replace('<sup>',',',precio_bruto($combinacion)));?></comb></p>
					  </div>
					  <div class="desgloce__item clearfix">
						<p class="desgloce__item__label">Imp. y/o tasas</p>
						<p class="desgloce__item__cantidad"><comb id="precio_impuestos"><?=strip_tags(str_replace('<sup>',',',precio_impuestos($combinacion)));?></comb></p>
					  </div>
					  <div class="desgloce__item clearfix">
						<p class="desgloce__item__label">Precio total</p>
						<p class="desgloce__item__cantidad"><comb id="precio_total"><?=strip_tags(str_replace('<sup>',',',precio($combinacion->v_total*$combinacion->pax+($combinacion->exterior?$combinacion->impuesto_pais:0),$combinacion->precio_usd,true,true)));?></comb></p>
					  </div>

					  <div class="moneda_button">
							<span>Moneda</span>

							<label class="moneda_block">
								<input type="radio" name="tipo_moneda_m" class="input_tipo_moneda" value="ARS" <?=!$combinacion->precio_usd?'checked':'';?>/>
								<span>ARS</span>
							</label>

							<label class="moneda_block">
								<input type="radio" name="tipo_moneda_m" class="input_tipo_moneda" value="USD" <?=$combinacion->precio_usd?'checked':'';?>/>
								<span>USD</span>
							</label>
						</div>
					</div>
					<div class="paquete__lugares-disponibles">
					  <div class="clearfix">
						<img class="paquete__lugares-disponibles__icon" src="<?=base_url();?>media/assets/images/icon/icon-suitcase-w-bg.png">
						<? if($paquete->cupo_disponible): ?>
							<p class="paquete__lugares-disponibles__cantidad"><span><?=$paquete->cupo_disponible;?></span>/<?=$paquete->cupo_total;?></p>
							<p class="paquete__lugares-disponibles__leyenda">Lugares<br>
							  disponibles</p>
						<? else: ?>
							<p class="paquete__lugares-disponibles__cantidad"><span>COMPLETO</p>

						<? endif; ?>
					  </div>
					</div>

					<?
					if((@$combinacion->agotada && $combinacion->habitacion_id!=99) || !$paquete->cupo_disponible || $habitacion_sin_cupo): ?>
							
							<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--dp">Anotarme<br>en lista de espera</a>
							<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--mb">Anotarme<br>en lista de espera</a>

					<? else: ?>

							<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--dp"><span>Reserva</span> con solo<br><span class="rift"><comb id="precio_minimo"><?=precio_redondeado($combinacion->monto_minimo_reserva,$combinacion->precio_usd);?></comb></span> por persona</a>
							<a href="" class="btnReservar ticket__detail__reserva-btn ticket__detail__reserva-btn--mb">Reserva con solo<br><span><comb id="precio_minimo"><?=precio_redondeado($combinacion->monto_minimo_reserva,$combinacion->precio_usd);?></comb> <span class="isSmaller">P/P</span></span></a>

					<? endif; ?>
					
				  </div>
				  <div id="paquete-consultas" class="paquete__consultas clearfix relative">
					<p>¿Tenés una consulta? <span>Escribinos</span></p>
					<div class="paquete__consultas__icons">
						<a rel="nofollow" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank"><i class="fab fa-whatsapp"></i><span>Whatsapp</span></a> <a rel="nofollow" href="https://www.messenger.com/t/buenas.vibras" target="_blank"><i class="fab fa-facebook-messenger"></i><span>Facebook</span></a>
				  	</div>
				  </div>
				  <!-- <div class="ticket__blue-div--mb"></div> -->
				</div>
				
			  </div>
			  <a class="ticket__ocultar-detalle--mb" href=""><img class="closed" src="<?=base_url();?>media/assets/images/icon/icon-doble-arrow-up-w.png"><img class="open" src="<?=base_url();?>media/assets/images/icon/double-arrow-down.png"><span>Clickear para detalles y más opciones</span></a>
			</article>

	<script type="text/javascript">
	var tipo_viaje = "<?=(!$paquete->grupal && $paquete->exterior)?'individual':'grupal';?>";
	var origen_activo = "<?=($paquete->grupal)?'backend':'mercadopago';?>";
	var viaje_ind_ext = '<?=$paquete->precio_usd;?>';
	var moneda = '<?=$paquete->precio_usd?'dolares':'pesos';?>';
	var aside = 'ticket';
	</script>	

	<input type="hidden" id="combinacion_id" name="combinacion_id" value="<?=@$combinacion->id;?>"/>						