	<!-- FOOTER -->

	<div id="footer">

		<div class="newsletter">
			<div class="container">

				<div class="newsletter_text">
					<span class="hidden-xs hidden-sm icono icon-email"></span>
					<p><strong>Suscríbete a nuestro newsletter</strong> y ¡Entérate primero de nuestros viajes!</p>
				</div>

				<form>
					<input type="email" id="n_email" placeholder="Ingresa tu e-mail" />
					<input type="submit" value="ENVIAR >" id="n_btn_submit" />

					<!-- TOOLTIP  -  EMAIL ERRONEO -->
					<div class="tooltip error" style="display: none">
						<a class="cerrar" href="javascript:void(0)">
							<span class="sr-only">Cerrar</span>
							<span class="icon-close"></span>
						</a>

						<div></div>
						<span class="text">Ingresa un e-mail válido</span>
					</div>
					<!-- -->


					<!-- TOOLTIP  -  EMAIL EXITOSO -->
					<div class="tooltip exito" style="display:none;">
						<a class="cerrar" href="javascript:void(0)">
							<span class="sr-only">Cerrar</span>
							<span class="icon-close"></span>
						</a>

						<div></div>
						<span><strong>¡Gracias por suscribirte!</strong></span>
					</div>
					<!-- -->

				</form>
			</div>
		</div>

		<div class="contenido">
			<div class="container">

				<div class="row">

					<div class="col-xs-6 col-md-3">
						<div>
							<h3>Buenos Aires</h3>

							<p>Ciudad de la Paz 2846 5° B.<br />CABA.</p>
							<p class="ubicacion"><a href="https://maps.google.com/maps?ll=-34.555406,-58.461106&z=16&t=m&hl=es-ES&gl=AR&mapclient=embed&cid=2544908606645044649" target="_blank">¿Cómo llegar?</a></p>
							<p class="tel">(011) 5235-3810.</p>
							<a href="tel:01152353810" class="btn_tel">Llamar</a>

							<a href="https://api.whatsapp.com/send?phone=5491141745025" class="btn_whatsapp simple">
								<span class="icon-whatsapp"></span>
								<p>+54 911 4174-5025</p>
							</a>

							<p class="horarios"><strong>Horario de atención:</strong> Lunes a Viernes de 10 a 19 hs.</p>
						</div>
					</div>


					<div class="col-xs-6 col-md-3">
						<div>
							
						</div>
					</div>


					<div class="col-md-3 hidden-xs hidden-sm">

						<div>
							<h3>Buenas Vibras Viajes</h3>

							<ul>
								<li><a href="<?=base_url();?>quienes-somos">Quiénes Somos</a></li>
								<li><a href="<?=base_url();?>viajes-solas-y-solos">Viajes Solas y Solos</a></li>
								<li><a href="<?=base_url();?>como-reservar">¿Cómo reservar?</a></li>
								<li><a href="<?=base_url();?>contacto">Contacto</a></li>
								<li><a href="<?=base_url();?>blog">Blog</a></li>
								<li><a href="<?=base_url();?>terminos-condiciones.pdf" target="_blank">Términos y Condiciones</a></li>
							</ul>
						</div>

					</div>


					<div class="legales col-xs-6 col-md-3">

						<div>
							<a href="https://servicios1.afip.gov.ar/clavefiscal/qr/response.aspx?qr=LLxUnBob-0eyLT03PdOHHg,," target="_blank">
								<img class="img-responsive" src="<?=base_url();?>media/assets/imgs/iconos/codigo_fiscal.jpg" alt="Código Fiscal" />
							</a>
						</div>

					</div>

					<div class="col-xs-12 text-center" style="font-family:'Rift'; color:#636363; font-weight: 500; font-size:15px; text-align:center">
						Un producto de <a href="http://megatravel.com/" target="_blank">MEGA TRAVEL</a>
					</div>
				</div>

			</div>
		</div>


		<div class="zocalo">
			<div class="container">

				<div class="row">
					
					<aside class="links_redes">
						<ul class="redes">
							<li class="facebook">
								<a href="http://www.facebook.com/buenas.vibras" target="_blank" rel="me nofollow">
									<span class="sr-only">Facebook</span>
									<span class="icon-facebook"></span>
								</a>
							</li>
							<li class="instagram">
								<a href="https://www.instagram.com/buenasvibrasviajes" target="_blank" rel="me nofollow">
									<span class="sr-only">Instagram</span>
									<span><img src="<?=base_url();?>media/assets/imgs/iconos/instagram.svg" alt="Instagram" /></span>
								</a>
							</li>
						</ul>
					</aside>

					<div>
						<aside class="legales">
							<p><strong>BUENAS VIBRAS VIAJES</strong> <span>-</span> Todos los derechos reservados <span>-</span> EVT - Leg 14.641 - Disp 102/2011</p>
						</aside>
					</div>

					<div class="id4you">
						<a href="http://id4you.com" target="_blank">
							<img class="img-responsive hidden-xs hidden-sm" src="<?=base_url();?>media/assets/imgs/iconos/id4you.svg" alt="Id4You" />

							<img class="img-responsive hidden-md hidden-lg" src="<?=base_url();?>media/assets/imgs/iconos/id4you_blue.svg" alt="Id4You" />
						</a>
					</div>

				</div>

			</div>
		</div>

	</div>

	<!-- FIN FOOTER -->


	<script src="<?=base_url();?>media/assets/jquery/jquery-1.12.3.min.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap-select.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap-datepicker.js"></script>
	<script src="<?=base_url();?>media/assets/bootstrap/js/bootstrap-datepicker.es.js"></script>
	<script src="<?=base_url();?>media/assets/owl-carousel/owl.carousel.min.js"></script>
	<script src="<?=base_url();?>media/assets/fancybox/jquery.fancybox.js"></script>
	<script>
		var baseURL = '<?=base_url();?>';
		var mp_gastos_admin = '<?=$this->settings->mp_gastos_admin;?>';
		var start_date = '<?=@$paquete->fecha_inicio?date("d/m/Y",strtotime(@$paquete->fecha_inicio)):date("d/m/Y");?>';
		var end_date = '<?=@$paquete->fecha_fin?date("d/m/Y",strtotime(@$paquete->fecha_fin)):date("d/m/Y");?>';
		var mobile = '<?=$this->detector->isMobile()?1:0;?>';
	</script>
	<script src="<?=base_url();?>media/assets/js/jquery.scrollTo.min.js"></script>
	<script src="<?=base_url();?>media/assets/js/main.js"></script>
	<script src="<?=base_url();?>media/assets/js/functions.js"></script>
	<script src="<?=base_url();?>media/assets/js/respond.js"></script>

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