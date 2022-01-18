			
			<div class="row contacto">

				<div class="envio_contacto col-xs-12 col-md-6">
					<div id="form_data">
					<form id="formContacto">
						<h3>Envíanos un mensaje</h3>

						<label id="nombre" class="req">
							<span>NOMBRE <span class="obligatorio">(Obligatorio):</span></span>
							<input type="text" name="nombre" />
						</label>

						<label id="email" class="req">
							<span>E-MAIL <span class="obligatorio">(Obligatorio):</span></span>
							<input type="text" name="email" />
						</label>

						<label id="asunto" class="req">
							<span>ASUNTO <span class="obligatorio">(Obligatorio):</span></span>
							<input type="text" name="asunto" value="<?=(@$_GET['a']=='vender')?'Quiero vender paquetes de Buenas Vibras Viajes':'';?>" />
						</label>

						<? if(@$_GET['a']=='vender'): //creamos este campo para identificar el contacto de agencia ?>
							<input type="hidden" name="agencia" value="1"/>
						<? endif; ?>
						<label>
							<span>TELÉFONO:</span>
							<input type="text" name="telefono" />
						</label>

						<label id="oficina" class="req">
							<span>OFICINA <span class="obligatorio">(Obligatorio)</span>:</span>
							<select class="selectric" title="ELEGÍ UNA OPCIÓN" name="sucursal_id">
								<option value="1">Buenos Aires</option>
							</select>
						</label>

						<label id="consulta" class="req">
							<span>CONSULTA <span class="obligatorio">(Obligatorio)</span>:</span>
							<textarea rows="3" name="consulta"></textarea>
						</label>

						<div class="g-recaptcha" name="captcha" data-sitekey="<?=$this->config->item('google_recaptcha_key');?>"></div>

						<div class="error" id="msgError"></div>
						<div class="msg"></div>

						<input class="button button--primary" type="button" value="Enviar" id="btnSendContacto" />
					</form>
					</div>
					<div id="form_success" style="display:none">
						<h3>Tu mensaje ha sido enviado</h3>
						<p style="color: #000;">¡Gracias! Nos pondremos en contacto a la brevedad.</p>
						<p style="color: #000;">Recuerda que también puedes contactarnos por teléfono a nuestras oficinas o por medio de los canales oficiales en redes sociales.</p>
						<!-- <br/>
						<img src="<?=base_url();?>media/assets/imgs/slider/7.png" class="img-responsive" /> -->
					</div>
				</div>


				<div class="datos col-xs-12 col-md-6">

					<div class="col-xs-12">
						<div class="row">

							<div class="col-xs-12">
								<h3>Escríbenos</h3>
							</div>

							<div class="escribinos col-xs-12">
								<a class="link" data-copy rel="nofollow" href="mailto:ventas@buenas-vibras.com.ar" data-copy="ventas@buenas-vibras.com.ar">ventas@buenas-vibras.com.ar</a>
								<a class="btn_tel copy" href="javascript:void(0)">COPIAR EMAIL</a>
							</div>

						</div>
					</div>

					<div class="seguinos col-xs-12">
						<div class="row">

							<div class="redes col-xs-7 col-sm-6">
								<h3>Síguenos en las redes</h3>
								<ul>
									<li class="facebook">
										<a class="fab fa-facebook-square" target="_blank" rel="nofollow" href="https://www.facebook.com/buenas.vibras"></a>
									</li>

									<li class="instagram">
										<a target="_blank" rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes/">
											<span class="sr-only">Instagram</span>
											<span><img src="<?=base_url();?>media/assets/images/icon/instagram.svg" alt="Instagram" /></span>
										</a>
									</li>
								</ul>
							</div>

							<div class="col-xs-5 col-sm-6">
								<h3>Facebook <span>Messenger</span></h3>
									<a class="messenger_button" rel="nofollow" href="https://www.messenger.com/t/buenas.vibras" target="_blank">
									<span>Mensaje</span>
									<span class="fab fa-facebook-messenger"></span>
								</a>
							</div>

						</div>
						
						<hr />

					</div>

					<div class="oficinas col-xs-12">
						<h3>Teléfonos</h3>

						<div class="row">
							<div class="col-xs-12 col-sm-6">
								<a class="tel" rel="nofollow" href="tel:01152353810">
									<span class="fas fa-phone"></span>
									(011) 5235-3810
								</a>

								<a rel="nofollow" href="https://api.whatsapp.com/send?phone=5491141745025" class="btn_whatsapp" target="_blank">
									<span class="fab fa-whatsapp"></span>
									<p>+54 911 4174-5025</p>
								</a>

								<div class="video-call-container">
									<a rel="nofollow" href="https://crm.zoho.com/bookings/Calendariodeatenci%C3%B3nBuenasVibrasViajes?rid=9589dd09fb432e99f4746ed5cf483b6bb6cb6802946e5253597719b767e8fa09gidc52cb70b206161c797f1c7f89bcd220a76e913641a5c06e9caf99aa3e12506f0" class="btn_whatsapp" target="_blank">
										<span class="fas fa-video"></span>
										<p>Agenda una videollamada</p>
									</a>
								</div>

								<p class="business_hours"><strong>Horario de atención</strong>: Lunes a Viernes de 10 a 19hs.</p>
							</div>

						</div>
					</div>

				</div>

			</div>
