<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<?=$fb_meta;?>
	
	<title>Buenas Vibras Viajes</title>

	<link rel="stylesheet" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap-select.css" />
	<link rel="stylesheet" media="all" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap-datepicker.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/owl-carousel/owl.carousel.min.css" />
	<link rel="stylesheet" media="all" href="<?=base_url();?>media/assets/fancybox/jquery.fancybox.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/estilos.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/media-queries.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/selectric/selectric.css" />
	<link rel="stylesheet" media="all" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap-datepicker.css" />

	<!-- [if lt IE 9] >
		<script src="<?=base_url();?>media/assets/js/css3-mediaqueries.js"></script>
		<script src="<?=base_url();?>media/assets/js/html5shiv.js"></script>
	<! [endif] -->
	
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5J23BKX');</script>
	<!-- End Google Tag Manager -->
</head>

<body id="checkout" class="sublanding">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J23BKX"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<div id="main">

		<div class="aside_content">

			<div class="contenido">

				<div>
					<a class="logo" href="<?=base_url();?>">
						<img src="<?=base_url();?>media/assets/images/logo/logo-sticky.png" alt="Buenas Vibras" style="width:120px;margin-bottom:10px;" />
					</a>

					<? if(isset($reserva) && $reserva): ?>
					<a href="<?=site_url('reservas/resumen/'.$hash);?>">Ver resumen de tu compra</a>
					<? else: ?>
					<a href="<?=site_url('checkout/orden/'.$hash);?>">Ver resumen de tu compra</a>
					<? endif; ?>
				</div>

				<div class="module info_transferencia transferencia_recibida hidden">
					<h2>Recibimos el informe de tu pago</h2>

					<p>Una vez que esté acreditado y verificado será agregado en tu reserva.
					<br/>Tené en cuenta que esta verificación puede demorar <b>hasta 48 hs hábiles.</b>
					</p>	
				</div>
				
				<div class="module info_transferencia">
					<h2>Informar Transferencia</h2>

					<p>Si realizaste un pago en horas o días no hábiles, tené en cuenta que la mayoría de los pagos se acreditan dentro de las 48 hs hábiles de  haberlos realizado. </p>

					
					<? if($reserva->estado_id != 5): ?>
				
						<form id="frmInformar" method="post" enctype="multipart/form-data">
							<input type="hidden" name="hash" value="<?=$hash_reserva;?>"/>
							<div class="row">
								<!--
								<option value="Banco Santander Río">Banco Santander Río</option>
								-->
								<label class="required">
									<span>BANCO <span class="obligatorio">(Obligatorio)</span></span>

									<select class="selectric" data-title="Elegí una opción" name="banco">
										<option value="">Seleccionar</option>
										<option value="Banco Galicia">Banco Galicia</option>
										<option value="Banco HSBC">Banco HSBC</option>
										<option value="Banco Macro">Banco Macro</option>
									</select>
									<span class="msg">CAMPO OBLIGATORIO</span>
								</label>

								<label class="required">
									<span>MEDIOS DE PAGO <span class="obligatorio">(Obligatorio)</span></span>

									<select class="selectric" data-title="Elegí una opción" id="tipo_pago" name="tipo_pago">
										<option value="">Seleccionar</option>
										<option value="Transferencia bancaria">Transferencia bancaria</option>
										<option value="Depósito bancario">Depósito bancario</option>
									</select>
									<span class="msg">CAMPO OBLIGATORIO</span>
								</label>

								<label class="required">
									<span>FECHA DE PAGO <span class="obligatorio">(Obligatorio)</span></span>

									<input type="text" class="datepicker" readonly placeholder="Elegí fecha" name="fecha_pago" />
									<span class="msg">CAMPO OBLIGATORIO</span>
								</label>

								<label class="required">
									<span>HORA DE PAGO <span class="obligatorio">(Obligatorio)</span></span>

									<input type="text" placeholder="HH:MM" name="hora_pago" id="hora_pago" maxlength="5" class=""/>
									<span class="msg">CAMPO OBLIGATORIO</span>
								</label>

								<label class="required">
									<span>MONTO DE PAGO <span class="obligatorio">(Obligatorio)</span></span>

									<input type="text" name="monto_pago" class=" money" placeholder="12.345,67"/>
									<span class="msg">CAMPO OBLIGATORIO</span>
								</label>

								<label class="required">
									<span>TIPO DE MONEDA <span class="obligatorio">(Obligatorio)</span></span>

									<select class="selectric" data-title="Elegí una opción" id="tipo_moneda" name="tipo_moneda">
										<option value="">Seleccionar</option>
										<option value="ARS">Pesos Argentinos</option>
										<option value="USD">Dólares</option>
									</select>
									<span class="msg">CAMPO OBLIGATORIO</span>
								</label>
								
								<label class="required">
									<span>ADJUNTAR COMPROBANTE <span class="obligatorio">(Obligatorio)</span></span>
									<small>Formatos aceptados JPG, PNG o PDF. Tamaño máximo del archivo: 2mb</small>

									<input type="file" name="comprobante" accept=".jpg, .jpeg, .png, .pdf" />
									
									<!-- ERROR -->
									<p class="msg error">Debes seleccionar un archivo válido</p>
									<!-- FIN ERROR -->
								</label>
								
								<label>
								
								<div class="submit">
									
									<input class="button lnkInformar" type="button" value="Enviar" />
								</div>
								</label>

							</div>


						</form>
					
					<? endif; ?>
				</div>

				<? if($reserva->estado_id == 5): //anulada ?>
					<div class="msg alert alert-danger">Tu reserva se encuentra <strong>ANULADA</strong>.<br>Por cualquier duda o inconveniente, por favor comunicate con nosotros.<br/>
						<ul>
							<li>Escribinos a <a rel="nofollow" href="mailto:reservas@buenas-vibras.com.ar">reservas@buenas-vibras.com.ar</a></li>
							<li>Llamanos al <a rel="nofollow" href="tel:01152353810">(011) 5235-3810.</a> de Lunes a Viernes de 10 a 19 hs.</li>
						</ul>
					</div>
				<? endif; ?>
					
			</div>

		</div>

	</div>

	<?=$this->carabiner->display('js');?>
	<script>
		var baseURL = '<?=base_url();?>';
		var mp_gastos_admin = '<?=$this->settings->mp_gastos_admin;?>';
		var start_date = '<?=date("d/m/Y",strtotime("-1 year",strtotime(date("Y-m-d"))));?>';
		var end_date = '<?=date("d/m/Y",strtotime(date("Y-m-d")));?>';
	</script>

	<script>
	$(document).ready(function(){
		$("#hora_pago").timeMask();
		$('.money').mask("#.##0,00", {reverse: true});

		$('.datepicker').datepicker({
			format: "dd/mm/yyyy",
			language: "es",
			startDate: start_date,
			endDate: end_date,
			autoclose: true
		});

		$('select[name="banco"]').selectric().on('change', function() {
		  var val = $(this).val();
		  if(val == 'Banco HSBC'){
		  	//si es hsbc siempre es pesos, no usd
		  	$('select[name="tipo_moneda"] option[value=USD]').prop('disabled', 'disabled');
		  }
		  else{
		  	$('select[name="tipo_moneda"] option[value=USD]').removeProp('disabled');
		  }
	  	  $('select[name="tipo_moneda"]').selectric('refresh');
		});

		$('select[name="tipo_moneda"]').selectric().on('change', function() {
		  var val = $(this).val();
		  if(val == 'USD'){
		  	//si acabo de seleccionar seleccionado usd, no puedo seleccionar
		  	$('select[name="banco"] option[value="Banco HSBC"]').prop('disabled', 'disabled');
		  }
		  else{
	  		$('select[name="banco"] option[value="Banco HSBC"]').removeProp('disabled');
		  }
	  	  $('select[name="banco"]').selectric('refresh');
		});
	});
	</script>

</body>
</html>