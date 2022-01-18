<?=$header;?>

	<div class="container faq">

		<div class="faq_centered-container">
			<div class="faq__hero"></div>

				<div class="faq_title">
					<div class="faq_title--highlight">¿TIENES ALGUNA DUDA?</div>
					<div class="faq_title--normal">PREGUNTAS FRECUENTES</div>
				</div>

			<? foreach($categorias as $name=>$arr): ?>
			<div class="faq_container">
				<div class="faq_container-title"><?=$name?></div>

				<? foreach($arr['preguntas'] as $p): ?>
					<div class="faq_container-question"><p><?=$p->pregunta;?></p><div class="faq_arrow"><img src="<?=base_url();?>media/assets/images/icon/red-down.png"></div>
						<div class="faq_container-answer">
								<p><?=$p->respuesta;?></p>
							</div>
					</div>
				<? endforeach; ?>
			</div>
			<? endforeach; ?>

			<div class="faq_container2">
				<div class="faq_contact-title">¿TIENES ALGUNA OTRA DUDA? <span>ESCRÍBENOS</span></div>
				<form class="faq_contact-form" id="fFaqs">
					<div class="faq_input-email">
						<input class="faq_contact-input" type="text" id="email" name="email" placeholder="Tu email" required>
					</div>
					<div class="dropdown-country">
						<select name="pais" id="pais" required>
							<option>País en el que resides</option>
							<? foreach($paises as $p): ?>
								<option value="<?=$p->nombre;?>"><?=$p->nombre;?></option>
							<? endforeach; ?>
						</select>
					</div>
					<div class="faq_input-message">
						<textarea id="comentario" name="comentario" placeholder="¿En qué podemos ayudarte?" maxlength="350" required></textarea>
						<div id="chars-left">Hasta <span id="char-count">350</span> caracteres más</div>
					</div>

					<div class="g-recaptcha" name="captcha" data-sitekey="<?=$this->config->item('google_recaptcha_key');?>"></div>

					<div class="error" id="msgError"></div>
					<div class="msg"></div>

					<input type="submit" id="btnSub" class="button button--primary" value="Enviar">
				</form>
			</div>
		</div>

	</div>


	<section class="banner-notice">
		<div class="container">
			<div class="type-1">
				<div class="title">
					<div class="text">
						<h3>¿Eres una agencia de viajes? <span>¿quieres vender nuestros paquetes?</span></h3>
					</div>
					<div class="group-button">
						<button class="btn btn-pink"><a href="<?=site_url('contacto?a=vender');?>">Acceso agencias</a></button>
					</div>
				</div>
			</div>
		</div>
	</section>


<?=$footer;?>

	<script>
		// form submit
		$( document ).on('click', "#btnSub", (function(e) {
			e.preventDefault();
			submit_form();
			return false;
		}));

		// arrow click
		$( document ).on('click', ".faq_container-question", (function() {
			// $( this ).parent().children('.description2').removeClass('display-none');
			$( this ).children('.faq_container-answer').slideToggle();
			transform($( this ));

		}));

		function transform(element) {
			if ($( element ).children('.faq_arrow').children().hasClass('faq_vertical-transform')) {
			 $( element ).children('.faq_arrow').children().removeClass('faq_vertical-transform');
			 } else {
				 $( element ).children('.faq_arrow').children().addClass('faq_vertical-transform');
			 }
		}

		var maxLength = 350;
		$('textarea').keyup(function() {
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#char-count').text(length);
		});


		function submit_form(){
			// $('#fFaqs .req.error').removeClass('error');
			$('#fFaqs #msgError').hide();
			$('#fFaqs .msg').hide();

			$('#btnSub').attr('disabled', 'disabled');
			$.post(baseURL+"estaticas/validar_form_faqs",$('#fFaqs').serialize(),function(data){
				$('#btnSub').removeAttr('disabled');
				if (data.status == 'ERROR'){
					if(data.errors){
						for (var i=0; i<data.errors.length; i++){
							$('#' + data.errors[i]).addClass('error');
						}
					}

					$('#fFaqs #msgError').text(data.msg).show();	
				}
				else{
					$('#fFaqs .msg').html(data.msg).show();
					document.getElementById('fFaqs').reset();
				}			
			},'json');
		}
	</script>