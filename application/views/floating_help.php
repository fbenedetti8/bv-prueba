<div class="module__Menuhelp">
			<div class="container">
				<button class="button">
					<span class="sr-only">Ayuda</span>
				</button>

				<div class="nav help">

					<div class="select-options">
						<p>Podemos ayudarte a resorver tus dudas por distintos medios,<strong> ¿Cúal prefieres?</strong></p>
						<button class="btn-arrow">
						</button>
					</div>
					<div class="hand-options">
						<ul class="listado social">
							<li>
								<a href="tel:01152353810" target="_blank">
									<i class="fas fa-phone"></i>
								Telefono</a>
							</li>
							<li>
								<a href="https://api.whatsapp.com/send?phone=<?=(isset($footer_celular->telefono) && $footer_celular->telefono)?str_replace(['+',' ','-'], ['','',''], $footer_celular->telefono):'5491141745025';?>"><i class="fab fa-whatsapp"></i>
								Whatsapp</a>

							</li>
							<li>
								<a href="http://www.facebook.com/buenas.vibras" target="_blank">
									<i class="fab fa-facebook-square"></i>
								Facebook</a>
							</li>
							<li>
								<a href="https://www.instagram.com/buenasvibrasviajes" target="_blank">
									<i class="fab fa-instagram"></i>
								Instagram</a>
							</li>
						</ul>
						<div class="option-emails open">
							<div class="m_title">
								<i class="far fa-envelope"></i>
								<p>Email</p>
							</div>
							<ul class="listado mail">
								<li>
									<i class="fas fa-arrow-right"></i>
									<div class="info">
										<p class="text">Ayuda en general</p>
										<p class="mail"><a href="mailto: info@buenas-vibras.com">info@buenas-vibras.com</a></p>
									</div>
								</li>
								<li>
									<i class="fas fa-arrow-right"></i>
									<div class="info">
										<p class="text">Ayuda para realizar tu reserva online</p>
										<p class="mail"><a href="mailto: reservas@buenas-vibras.com">reservas@buenas-vibras.com</a></p>
									</div>
								</li>
							</ul>
						</div>
						<ul class="listado faqs">
							<li>
								<a href="<?=base_url();?>preguntas-frecuentes" target="_blank">
									<i class="fas fa-question"></i>
									<span>Preguntas Frecuentes</span>
								</a>
							</li>
						</ul>
					</div>

				</div>
			</div>
		</div>