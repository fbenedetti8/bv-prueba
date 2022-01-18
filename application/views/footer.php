<footer>

		<!-- Footer form suscripcion -->
		<section class="module__form">
			<div class="container">
				<div class="suscripcion_newsletter">

					<div class="title">
						<h3>SUSCRIBETE AL NEWSLETTER</h3>
						<p>¡Enterate primero de nuestros viajes y promociones!</p>
					</div>

					<form class="form">

						<div class="input">
							<input type="text" id="n_email" placeholder="Tu email">
							<p class="msg error " style="display: none;">* Debes completar este campo.</p>
						</div>

						<div class="input">
							<select class="selectric" id="n_pais" data-title="País en el que resides">
								<option value="">Selecciona</option>
								<? foreach ($paises as $p) { ?>
									<option value="<?=$p->nombre;?>"><?=$p->nombre;?></option>
								<? } ?>
							</select>
							<p class="msg error " style="display: none;">* Debes completar este campo.</p>
						</div>

						<div class="submit_block">
							<input type="submit" class="btn btn-pink sm submit" id="n_btn_submit" value="Suscríbete">
							<p class="msg exito " style="display: none;">Gracias por suscribirte</p>
						</div>
					</form>

				</div>
			</div>
		</section>
		<!-- //Footer form suscripcion -->

		<section class="module__footer">
			<div class="container">

				<div class="logo">
					<a href="<?=base_url();?>">
						<img src="<?=base_url();?>media/assets/images/logo/logo_footer.png" alt="Buenas Vibras">
					</a>
				</div>

				<div class="options">
					<div class="footer-colum">
						<h4 class="title">Buenas vibras viajes</h4>

						<ul class="listado">
							<li><a href="<?=base_url();?>quienes-somos">Quiénes somos</a></li>
							<li><a href="<?=base_url();?>viajes-solas-y-solos">Viajes solas y solos</a></li>
							<li><a href="<?=base_url();?>como-reservar">¿Cómo reservar?</a></li>
							<li><a href="<?=base_url();?>preguntas-frecuentes">Preguntas frecuentes</a></li>
							<li><a href="<?=base_url();?>contacto">Contacto</a></li>
							<li><a href="<?=base_url();?>blog">Blog</a></li>
							<li><a href="<?=site_url('home/terminos_y_condiciones')?>" target="_blank">Términos y condiciones</a></li>
						</ul>
					</div>

					<div class="footer-colum colum-oficina">
						<h4 class="title">Contacto</h4>

						<ul class="listado">
							<li class="margin_separator">
								<i class="fas fa-phone" style="margin-right: 6px;"></i>
								<p>Teléfono <a href="tel:01152353810" rel="nofollow" target="_blank"> (011) 5235-3810</a></p>
							</li>
							<li class="margin_separator">
								<i class="fab fa-whatsapp"></i>
								<p>Whatsapp <a rel="nofollow" href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>" target="_blank" class="btn_whatsapp simple"><?=(isset($footer_celular->telefono) && $footer_celular->telefono)?$footer_celular->telefono:'+54 911 4174-5025';?></a></p>
							</li>

							<li class="margin_separator">
								<i class="fas fa-video"></i>
								<p> <a rel="nofollow" href="https://crm.zoho.com/bookings/Calendariodeatenci%C3%B3nBuenasVibrasViajes?rid=9589dd09fb432e99f4746ed5cf483b6bb6cb6802946e5253597719b767e8fa09gidc52cb70b206161c797f1c7f89bcd220a76e913641a5c06e9caf99aa3e12506f0" target="_blank" class="btn_whatsapp simple">Agenda una videollamada</a></p>
							</li>

							<li class="openModal" data-modal="cancelContractModal">
								<i class="fas fa-window-close"></i>
								<p style="color:#ff5c5c">BOTON DE ARREPENTIMIENTO</p>
							</li>
						</ul>
					</div>

					<div class="footer-colum colum-social">
						<h4 class="title">seguinos</h4>

						<ul class="listado">
							<li>
								<a rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes" target="_blank">
									<i class="fab fa-instagram"></i>
									<span class="sr-only">Instagram</span>
								</a>
							</li>
							<li>
								<a rel="nofollow" href="http://www.facebook.com/buenas.vibras" target="_blank">
									<i class="fab fa-facebook-square"></i>
									<span class="sr-only">Facebook</span>
								</a>
							</li>
						</ul>
					</div>

				</div>


				<div class="colum-afip">
					<a rel="nofollow" href="https://servicios1.afip.gov.ar/clavefiscal/qr/response.aspx?qr=LLxUnBob-0eyLT03PdOHHg,," target="_blank">
						<img src="<?=base_url();?>media/assets/images/img/afip.png" alt="Data Fiscal">
					</a>
				</div>

			</div>
		</section>


		<div class="module__derechos">
			<div class="container">
				<div class="block">
					<p><a href="<?=site_url('home/certificado_virtual')?>" target="_blank">
						Certificado de Local Virtual
					</a></p>
					
					<p>Todos los derechos reservados <br> EVT - Leg 14.641 - Disp 102/2011</p>
				</div>

			</div>
		</div>

		<div class="module__author id4you">
			<div class="container">
				<a href="https://www.id4you.com/" target="_blank" rel="nofollow">
					<img src="<?=base_url();?>media/assets/images/logo/id4you.png" alt="id4YOU">
				</a>
			</div>
		</div>


	</footer>
	<div id="cancelContractModal" class="basic-modal hidden">
		<div class="modal-content">
			<div class="close-button-container"><i class="fas fa-times-circle close-button"></i></div>
			<iframe id="ifr_arrepentimiento" src="https://survey.zohopublic.com/zs/CJztqZ" width="100%" height="100%" frameborder="0" marginwidth='0' marginheight='0' scrolling='auto'></iframe>
		</div>
	</div>

	<!-- JS -->

	<? $this->carabiner->display('js'); ?>

	<script>
		var baseURL = '<?=base_url();?>';
		var currentURL = '<?=current_url();?>';
		var mp_gastos_admin = '<?=$this->settings->mp_gastos_admin;?>';
		var start_date = '<?=@$paquete->fecha_inicio?date("d/m/Y",strtotime(@$paquete->fecha_inicio)):date("d/m/Y");?>';
		var end_date = '<?=@$paquete->fecha_fin?date("d/m/Y",strtotime(@$paquete->fecha_fin)):date("d/m/Y");?>';
		var mobile = '<?=$this->detector->isMobile()?1:0;?>';
		var currentpage = '<?=@$page;?>';
	</script>

	<script src="<?=base_url();?>media/assets/js/functions.js"></script>
	
	<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 947015980;
	var google_custom_params = window.google_tag_params;
	var google_remarketing_only = true;
	/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
	<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/947015980/?value=0&amp;guid=ON&amp;script=0"/>
	</div>
	</noscript>
</body>
</html>
