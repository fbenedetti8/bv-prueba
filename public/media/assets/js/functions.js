var mediopago = 'MP';

$(document).ready(function(){
		
	/* grabar newsletter */
	$('body').on('submit','.suscripcion_newsletter form',function(e){
		e.preventDefault();
		submit_nform();
		return false;
	});

	$('body').on('click','#n_btn_submit',function(e){
		e.preventDefault();
		submit_nform();
		return false;
	});
	
	$('body').on('change','.date_selector',function(e){
		e.preventDefault();
		var val = $(this).val();
		if(val!=0){
			location.href = val;
		}
	});

	$('body').on('change','.place_selector',function(e){
		e.preventDefault();
		var val = $(this).val();
		if(val!=0){
			location.href = val;
		}
	});

	var loading = false;
	var nomore = false;
	/* para el infinit scroll de la pagina de proximos viajes */
	$(window).scroll(function () {
		console.log($(window).scrollTop());
		console.log( $("#divContent").height());
		/*console.log($(window).scrollTop());
		console.log($(document).height());
		console.log($(window).height());
		console.log( $("#divContent").height());*/
       // if ($(window).scrollTop() == $(document).height() - $(window).height()) {
        if ($(window).scrollTop() >= $("#divContent").height()) {
            
            if(!loading && !nomore){
            	$('div#divNomore').hide();
	            $('div#divLoading').show();
	            var offset = $('#offset').val();

            	loading = true;
            	 //le pego a currentURL para mantener los filtors de la uri
	            $.post(currentURL,{offset:offset},function (html) {
	                if (html) {
	                    $("#divContent").append(html.view);

	                    $('#offset').val(html.offset);

	                    $('div#divLoading').hide();
	                	loading = false;

	                    if(html.cant == '0'){
	                    	nomore=true;
	                    	$('div#divNomore').show();
	                    }
	                }
	            },'json');
            }
           
        }
    });


	$('body').on('click','.btnReservar',function(e) {
		e.preventDefault();
		solicitar_reservar();
	});

	//para actualizar precio total si hay adicionales obligatorios
	/*$.each($('.lnkAdicional'),function(i,el) {
		var valor = $(el).attr('data-valor');
		if($(el).is(':checked')){
			var pax = $('#pasajeros_adicional').length ? $('#pasajeros_adicional').val() : $('#pasajeros').val();
			valor = valor*pax;
			sumar_adicional(valor);
		}
	});*/
	if($('.lnkAdicional').length){
		$.each($('.lnkAdicional'),function(i,el) {
			var valor = $(el).attr('data-valor');
			if($(el).is(':checked')){
				var pax = $('#pasajeros').val();
				valor = valor*pax;
				sumar_adicional(valor);
			}
		});
	}

	//seleccion de adicional debe actualizar precio
	$('body').on('click','.lnkAdicional',function(e) {
		var me = this;
		if(!$(me).attr('readonly')){
			validar_adicional(me);
		}
	});

	//el cambio de moneda se usa para viajes individuales (mobile)
	/*$('body').on('click','[name="tipo_moneda_m"]',function(e) {
		moneda = $(this).val();
		$('input[name=tipo_moneda_m]').prop('checked',false);
		$('input[name=tipo_moneda_m][value='+moneda+']').prop('checked',true);
		//aside = 'aside_mobile';
		$('#aside_mobile').val('1');
		update_precio(moneda);
	});*/

	/* grabar form lista espera */
	$('body').on('submit','#frmLE',function(e){
		e.preventDefault();
		submit_form_le();
		return false;
	});

	$('body').on('click','#le_btn_submit',function(e){
		e.preventDefault();
		submit_form_le();
		return false;
	});

	//share FB
	$('body').on('click','.lnkFB',function(e){
		e.preventDefault();
		var url = $(this).attr('rel');
		fb_share(url);
	});


	if($( ".onlynum" ).length){
		$("body").on("keypress",".onlynum",function( e ) {
			var max = $(this).attr('maxlength');
		    if (max > 0 && $(this).val().length > max) {
		        $(this).val($(this).val().substr(0, max));
		    }
			return isNumber(e);
		});

		$("body").on("keydown",".onlynum",function( e ) {
			var max = $(this).attr('maxlength');
		    if (max > 0 && $(this).val().length > max) {
		        $(this).val($(this).val().substr(0, max));
		    }
			return isNumber(e);
		});

		$("body").on("keyup",".onlynum",function( e ) {
			var max = $(this).attr('maxlength');
		    if (max > 0 && $(this).val().length > max) {
		        $(this).val($(this).val().substr(0, max));
		    }
			return isNumber(e);
		});

	}
	
	if($( ".onlydecimal" ).length){		
		 $("body").on("keypress keyup blur",".onlydecimal",function (event) {
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
	}
	if($( ".mesvalido" ).length){
		$("body").on("change",".mesvalido",function( e ) {
			var me = $(this);
			var val = me.val();
			var valido = val>=1&&val<=12;
			if(!valido){
				me.val(val.substring(0,val.length-1));
				me.focus();
			}
		});
	}
	if($( ".diavalido" ).length){
		$("body").on("change",".diavalido",function( e ) {
			var me = $(this);
			var val = me.val();
			var valido = val>=1&&val<=31;
			if(!valido){
				me.val(val.substring(0,val.length-1));
				me.focus();
			}
		});
	}

	//pasar al siguiente campo al completar la fecha_nacimiento
	if($('.fecha_nacimiento').length){
		init_fecha_nacimiento();
	}

	if($('.selectpicker.nacionalidad_id').length){
		init_picker_nacionalidad();
	}

	//pasar al siguiente campo al completar el cuit
	if($('.campo_cuil').length){
		init_cuil_cuit();
	}

	//boton guardar datos pasajero X (ID)
	$('body').on('click','.btnSavePax',function(e){
		e.preventDefault();
		var pax = $(this).attr('rel');
		$('#grabar_pax').val(pax);
		$('#frmPaso3').submit();
	});

	//boton guardar datos pasajero X (ID)
	$('body').on('click','.btnSavePaxRes',function(e){
		e.preventDefault();
		var pax = $(this).attr('rel');
		$('#grabar_pax').val(pax);
		$('#frmPaso2').submit();
	});

	//link saltear pasajero X (ID)
	$('body').on('click','.lnkSaltearPasajero',function(e){
		e.preventDefault();
		var pax = $(this).attr('rel');
		$('#saltear_pax').val(pax);
		$('#frmPaso3').submit();
	});

	//reusar para facturacion los datos del pasajero 1
	$('body').on('click','#reusar',function(e){
		if($(this).is(':checked')){
			$(document.getElementById('frmPaso2').elements['f_nombre']).val($(document.getElementById('frmPaso1').elements['nombre']).val());
			$(document.getElementById('frmPaso2').elements['f_apellido']).val($(document.getElementById('frmPaso1').elements['apellido']).val());
			/*
			$(document.getElementById('frmPaso2').elements['f_nacimiento_dia']).val($(document.getElementById('frmPaso1').elements['nacimiento_dia']).val());
			$(document.getElementById('frmPaso2').elements['f_nacimiento_mes']).val($(document.getElementById('frmPaso1').elements['nacimiento_mes']).val());
			$(document.getElementById('frmPaso2').elements['f_nacimiento_ano']).val($(document.getElementById('frmPaso1').elements['nacimiento_ano']).val());
			*/

			/*//head
			console.log('aqui');
			//$(document.getElementById('frmPaso2').elements['f_fecha_nacimiento']).datepicker('setDate',$(document.getElementById('frmPaso1').elements['nacimiento']).val());
			$(document.getElementById('frmPaso2').elements['f_nacionalidad_id']).selectpicker('val',$(document.getElementById('frmPaso1').elements['nacionalidad_id']).val());
			*/
			console.log($(document.getElementById('frmPaso1').elements['nacimiento']).val());
			//$(document.getElementById('frmPaso2').elements['f_fecha_nacimiento']).datepicker('setDate',$(document.getElementById('frmPaso1').elements['nacimiento']).val());
			var indx = $(document.getElementById('frmPaso1').elements['nacionalidad_id']).prop('selectedIndex');
			$(document.getElementById('frmPaso2').elements['f_nacionalidad_id']).prop('selectedIndex',indx).selectric('refresh');
		}
		else{
			$(document.getElementById('frmPaso2').elements['f_nombre']).val();
			$(document.getElementById('frmPaso2').elements['f_apellido']).val();
			/*
			$(document.getElementById('frmPaso2').elements['f_nacimiento_dia']).val();
			$(document.getElementById('frmPaso2').elements['f_nacimiento_mes']).val();
			$(document.getElementById('frmPaso2').elements['f_nacimiento_ano']).val();
			*/
			//$(document.getElementById('frmPaso2').elements['f_fecha_nacimiento']).val();
			$(document.getElementById('frmPaso2').elements['f_nacionalidad_id']).prop('selectedIndex',0).selectric('refresh');
		}
	});
	
	//link saltear facturacion
	$('body').on('click','.lnkSaltearFacturacion',function(e){
		e.preventDefault();
		$('#frmPaso2').submit();
	});
	
	//link completar luego los datos pasajeros
	$('body').on('click','.lnkCompletarLuego',function(e){
		e.preventDefault();
		$('#completa_luego').val(1);
		$('#frmPaso3').submit();
	});
	
	//submit form pago
	$('body').on('submit','.panel-group form#formPago',function(ev){
		ev.preventDefault();
		efectuar_pago();
	});
	
	//pagar con mp
	$('body').on('click','.pagarMP',function(e){
		e.preventDefault();		
		efectuar_pago();
	});

	//pagar con paypal
	$('body').on('click','.pagarPP',function(e){
		e.preventDefault();		
		mediopago = 'PP';
		efectuar_pago();
	});

	//pagar luego
	$('body').on('click','.lnkPagarLuego',function(e){
		e.preventDefault();
		pagar_luego();		
	});


	//informar transferencia
	$('body').on('click','.lnkInformar',function(e){
		e.preventDefault();	
		$('.lnkInformar').attr('disabled','disabled').val('ENVIANDO...');
		informar_transferencia();
	});
	$('body').on('submit','#frmInformar',function(e){
		ev.preventDefault();
		$('.lnkInformar').attr('disabled','disabled').val('ENVIANDO...');
		informar_transferencia();
	});


	$('.form_pago .monto_pago_parcial').on('click focus input', function(){
		$(this).prev().find('input').prop('checked', true);
	});

	//if($('.selectpicker.nacionalidad_id').length){
	//ya no es selectpicker
	if($('.nacionalidad_id').length){
		init_picker_nacionalidad();
	}

	$('body').on('click', '.form_pago input[name="pago"]', function(){
		if($(this).val() == 'total'){
			$('.form_pago .monto_pago_parcial').val('');
		}
		else{
			$('.form_pago .monto_pago_parcial').val($('.form_pago .monto_pago_parcial').attr('data-minimo'));
		}
	});

	if($('#frmPaso1').length){
		if($(document.getElementById('frmPaso1').elements['nacionalidad_id']).length){
			$(document.getElementById('frmPaso1').elements['nacionalidad_id']).selectric('refresh');
		}
		if($(document.getElementById('frmPaso1').elements['pais_emision_id']).length){
			$(document.getElementById('frmPaso1').elements['pais_emision_id']).selectric('refresh');
		}
	}

	if($('#frmPaso2').length){
		if($(document.getElementById('frmPaso2').elements['f_nacionalidad_id']).length){
			$(document.getElementById('frmPaso2').elements['f_nacionalidad_id']).selectric('refresh');
		}
		if($(document.getElementById('frmPaso2').elements['f_residencia_id']).length){
			$(document.getElementById('frmPaso2').elements['f_residencia_id']).selectric('refresh');
		}
	}

	if($('#frmPaso3').length){
		if($(document.getElementById('frmPaso3').getElementsByClassName('nacionalidad_id')).length){
			$(document.getElementById('frmPaso3').getElementsByClassName('nacionalidad_id')).selectric('refresh');
		}
		if($(document.getElementById('frmPaso3').getElementsByClassName('pais_emision_id')).length){
			$(document.getElementById('frmPaso3').getElementsByClassName('pais_emision_id')).selectric('refresh');
		}
	}

	$('body').on('click','#btnSendContacto',function(e){
		e.preventDefault();
		submit_form_contacto();
		return false;
	});

	$('body').on('click','[name="tipo_moneda"]',function(e) {
		moneda = $(this).val();
		//aside = 'aside_desktop';
		$('#aside_mobile').val('0');
		update_precio(moneda);
	});

	$('body').on('click','[name="tipo_moneda_m"]',function(e) {
		moneda = $(this).val();
		//aside = 'aside_mobile';
		$('#aside_mobile').val('1');
		update_precio(moneda);
	});
	$('body').on('click','[name="tipo_moneda_ch"]',function(e) {
		moneda = $(this).val();
		$('#aside_mobile').val('1');
		aside = 'detalle_aside';
		$('#fPaquete #tipo_moneda_m').val(moneda);
		update_precio_ch(moneda);
	});
	
	$('body').on('click', '.sku__options', function () {
		$(this).toggleClass('active');
		$(this).parent().parent().parent().find('.sku__more-content-info').slideToggle();
		return false;
	})

	// open global basic modal

	$('.openModal').on('click',function(e) {
		var modalName = $(this).attr("data-modal");
		$('#'+modalName).removeClass("hidden");
	});

	// close global basic modal
	$('.basic-modal').on('click',function(e) {
		if($(e.target).attr('class').includes('basic-modal')){
			$(this).addClass("hidden");	
		}
	});

	// close global basic modal
	$('.close-button').on('click',function(e) {
		$('.basic-modal').addClass("hidden");
	});

});

function submit_nform(){
	var email = $("#n_email").val();
	var pais = $("#n_pais").val();

	console.log(email);
	console.log(pais);
	
	$('.suscripcion_newsletter .msg.error').fadeOut();

	if(email == ""){
		$('.suscripcion_newsletter #n_email').closest('.input').find('.msg.error').text("* Ingresá un e-mail válido.").fadeIn();
		return false;
	}
	if(pais == ""){
		$('.suscripcion_newsletter #n_pais').closest('.input').find('.msg.error').text("* Debes completar este campo.").fadeIn();
		return false;
	}

	if(checkEmail(email)){
		
		$.post(baseURL+"newsletter/save",{email: email, pais: pais},function(data){
			if(data.status == 'success'){
				$("#n_email").val('').blur();
				$("#n_pais").val('').blur();
				$('.suscripcion_newsletter .msg.exito').fadeIn();		
				/*newsletter fb conversion*/
				$('body').append('<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6025243133650&amp;cd[value]=0.00&amp;cd[currency]=ARS&amp;noscript=1" /></noscript>');
			}
			else{
				$("#n_email").val('').blur();
				if(data.msg)
					$('.suscripcion_newsletter .msg.error').text(data.msg);
				else
					$('.suscripcion_newsletter .msg.error').text("Ha ocurrido un error, intenta mas tarde.");
				
				$('.suscripcion_newsletter .msg.error').fadeIn();
			}
		},'json');
	}
	else{
		$('.suscripcion_newsletter #n_email').closest('.input').find('.msg.error').text("* Ingresá un e-mail válido.").fadeIn();
	}
	
	return false;
}

function checkEmail(email) {
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email)){
		return false;
	}
	else{
		return true;
	}
}

/******************************** FUNCIONES: ********************************/
init_selectpickers();
function init_selectpickers(){

	
	/* interna paquete, seleccion de salida */
	$('#form_salidas .dropdown__select').on('change.bs.select', function(ev){
		var name = $(this).attr('name');
		obtener_combinacion(name);
	});
		
	$('.alojamiento .dropdown__select').on('change.bs.select', function(ev){
		var name = $(this).attr('name');
		
		if( name!= 'habitacion'){
			//muestro info de alojamiento actual
			var hid = $(this).val();
			console.log(hid);
			$('.descripcion_hotel').hide();
			$('.descripcion_hotel.descripcion_hotel_'+hid).show();
		}
		
		if ( 1 ) {
			obtener_combinacion(name);

		}

	});


	$('.transporte .dropdown__select').on('change.bs.select', function(ev){
		//muestro info de transporte actual
		var id = $(this).val();
		$('.descripcion_transporte').hide();
		$('.descripcion_transporte.descripcion_transporte_'+id).show();
		
		var name = $(this).attr('name');
		obtener_combinacion(name);
		
	});

	//muestro los adicionales que correspondan al transporte
	if($('.transporte .dropdown__select').length){
		show_hide_adicionales($('.transporte .dropdown__select').val());
	}
	else if($('.transporte #transporte').length){//si esta oculto
		show_hide_adicionales($('.transporte #transporte').val());
	}
}

function show_hide_adicionales(id){
	$('.adicional_t').hide();
	$('.adicional_t.adicional_'+id).show();
}

/* validacion datos form checkout, salvo el paso del pago */
$('body').on('submit','.panel-group form:not(#formPago), .frmResumen:not(#formPago)',function(ev){
	ev.preventDefault();
	
	if($('#reserva_id').length)
		var url = baseURL+'reservas/validar_pasajeros';
	else
		var url = baseURL+'checkout/validar_form';
	
	$('.paxreq').addClass('hidden');

	// ToDo:
	// AJAX
	var me = $(this);
	$(document.getElementById(me.attr('id'))).find('.required').removeClass('error');
	$.post(url,me.serialize(),function(data){
		//reseteo flags necesarios
		if($('#completa_luego').length) $('#completa_luego').val(0);
		if($('#saltear_pax').length) $('#saltear_pax').val(0);
		if($('#grabar_pax').length) $('#grabar_pax').val(0);
		
		if(data.status == 'success'){

			console.log(data);
			
			var d = false;
			
			//si todo esta ok, voy al siguiente paso			
			if(data.paso2){
				$(".head-pasajero-1").html('Pasajero 1: '+data.nombre+' '+data.apellido+'  <span>Editar</span>');
				$(".head-pasajero-1").closest('.panel-heading').addClass('completo');
				$('#paso2').html(data.paso2);
				if($('.dropdown__select').length){
					$('.dropdown__select').selectpicker();
				}
				init_datepicker();
				init_cuil_cuit();
				init_fecha_nacimiento();
				d = document.getElementById("paso2");
			}
			
			if(data.paso3){
				var nombre_txt = '';
				if(data.f_nombre && data.f_apellido){
					nombre_txt = data.f_nombre+' '+data.f_apellido;
				}
				if(data.f_nombre && !data.f_apellido){
					nombre_txt = data.f_nombre;
				}
				if(!data.f_nombre && data.f_apellido){
					nombre_txt = data.f_apellido;
				}
				$(".head-facturacion").html('Persona a quien se le emitirá la factura: '+nombre_txt);
				

				$(".head-facturacion").closest('.panel-heading').addClass('completo');
				$('#paso3').html(data.paso3);
				if($('.dropdown__select').length){
					$('.dropdown__select').selectpicker();
				}
				init_datepicker();
				d = document.getElementById("paso3");
				
				init_picker_nacionalidad();
				init_fecha_nacimiento();
				//init_selectpickers();
				$.each($('.dropdown__select.nacionalidad_id'),function(i,el){
					var obj = $(el);console.log('a '+obj.val());
					campos_documentacion(obj);
				});

				if(data.completar_paso3){
					$('.paxreq').removeClass('hidden');
				}
			}
			
			if(data.paso4){
				if(data.f_nombre){
					$(".head-facturacion").html('Persona a quien se le emitirá la factura: '+data.f_nombre+' '+data.f_apellido);
					$(".head-facturacion").closest('.panel-heading').addClass('completo');
				}
			
				if(data.completa_luego){
					$(".head-acompa").html('Completá los datos de los acompañantes (pendiente)');
				}
				$('#paso4').html(data.paso4);
				if($('.dropdown__select').length){
					$('.dropdown__select').selectpicker();
				}
				
				d = document.getElementById("paso4");
			}
			
			if(data.pax){
				if($('#reserva_id').length){
					init_fecha_nacimiento();
					
					//resumen de viaje
					if(data.pax.completo == 1){
						$(".head-pax-"+data.pax.pasajero_id).html('Pasajero '+data.pax.numero_pax+(data.pax.responsable==1?' <span>(responsable)</span>':''));
						$(".head-pax-"+data.pax.pasajero_id+' + span').text('Editar datos');
					}
					else{
						$(".head-pax-"+data.pax.pasajero_id).html('Pasajero '+data.pax.numero_pax+(data.pax.responsable==1?' <span>(responsable)</span>':''));
						$(".head-pax-"+data.pax.pasajero_id+' + span').text('Completar datos');
					}
					$(".head-pax-"+data.pax.pasajero_id).closest('.panel-heading').removeClass('incompleto').addClass('completo');
					$(".head-pax-"+data.pax.pasajero_id).closest('a').trigger('click');
				}
				else{
					//estoy dentro de paso 3
					if(data.pax.completo == 1){
						$(".head-pax-"+data.pax.id).html('Pasajero '+data.pax.numero_pax+': '+data.pax.nombre+' '+data.pax.apellido);
						$(".head-pax-"+data.pax.id+' + span').text('Editar');
					}
					else{
						$(".head-pax-"+data.pax.id).html('Pasajero '+data.pax.numero_pax+': '+data.pax.nombre+' '+data.pax.apellido);
						$(".head-pax-"+data.pax.id+' + span').text('Completar');
					}
					$(".head-pax-"+data.pax.id).closest('.panel-heading').addClass('completo');
					$(".head-pax-"+data.pax.id).closest('a').trigger('click');
				}
				
				d = document.getElementById("paso3");
				if(d){
					var topPos = d.offsetTop;
					$('body').scrollTo(topPos+50,500);
				}
			}
			else{
				$('body').scrollTo(0);
				
				me.closest('.collapse').collapse('hide');
				
				setTimeout(function(){
								var topPos = d.offsetTop;
								$('body').scrollTo(topPos+50,500);
				},500,{onAfter: function(){
					me.closest('.panel-group').next().collapse('show').find('.collapse:first').collapse('show');
					me.closest('.panel-group').next().find('.collapse:first').collapse('show');
				}});
			}
			
			if(data.aceptar_terminos){
				$('.form_terminos').removeClass('hidden');
			}
			
			if(data.redirect){
				location.href = data.redirect;
			}
			
		}
		else{
			//muestro errores en desktop, en mobile pongo alert de campos obligatorios
			if(mobile == 1){
				$.each(data.fields,function(i,el){
					$(document.getElementById(me.attr('id')).elements[el]).closest('.required').addClass('error');
				});
				
				// alert('Debes completar todos los campos obligatorios.');
				$('body').scrollTo(me.closest('form'),500,{axis: 'y', offset: -120});

				if(data.invalid_user){
					//le cambio el msg al campo de email y dni para avisar
					var orig_msg = $(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').attr('rel',orig_msg);
					
					orig_msg = $(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_nacimiento){
					//le cambio el msg al campo de fecha nacimiento para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_dni){
					//le cambio el msg al campo de dni para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_email){
					//le cambio el msg al campo de email para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_cuit){
					//le cambio el msg al campo de cuit
					var orig_msg = $(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').attr('rel',orig_msg);
				}		
				
				/*validacion de datos de pasajeros en comparacion con datos de responsable */
				if(data.pax_invalid_email || data.pax_invalid_dni || data.pax_invalid_pasaporte){
					//le cambio el msg al campo de email, dni o pasaporte
					if($(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').length){
						console.log('aca');
						var orig_msg = $(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text();
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text(data.msg);
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').attr('rel',orig_msg);
					}
					else{
						//caso email, dni y pasaporte NO OBLIGATORIOS en checkout (no llevan la clase .required)
						var orig_msg = $(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').text();
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').text(data.msg);
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').attr('rel',orig_msg);
						//lo hago visible
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').css('display','block');
					}
				}	
				
				return false;
			}
			else{
				//reseteo los msgs
				var orig_msg = $(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').attr('rel');
				$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text(orig_msg);		
				orig_msg = $(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').attr('rel');
				$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text(orig_msg);				
				orig_msg = $(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').attr('rel');
				$(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').text(orig_msg);				
					
				$.each(data.fields,function(i,el){
					$(document.getElementById(me.attr('id')).elements[el]).closest('.required').addClass('error');
					
				});
				$('body').scrollTo(me.closest('form'),500,{axis: 'y', offset: -120});	
				
				if(data.invalid_user){
					//le cambio el msg al campo de email y dni para avisar
					var orig_msg = $(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').attr('rel',orig_msg);
					
					orig_msg = $(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_nacimiento){
					//le cambio el msg al campo de fecha nacimiento para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_dni){
					//le cambio el msg al campo de dni para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_email){
					//le cambio el msg al campo de email para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['email']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				if(data.invalid_cuit){
					//le cambio el msg al campo de cuit
					var orig_msg = $(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').text(data.msg);
					$(document.getElementById(me.attr('id')).elements['f_cuit_numero']).closest('.required').find('.msg').attr('rel',orig_msg);
				}		
				
				//responsable
				if(data.error_dni){
					//le cambio el msg al campo de dni para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').text(data.error_dni);
					$(document.getElementById(me.attr('id')).elements['dni']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				
				//responsable
				if(data.error_pasaporte){
					//le cambio el msg al campo de pasaporte para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['pasaporte']).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['pasaporte']).closest('.required').find('.msg').text(data.error_pasaporte);
					$(document.getElementById(me.attr('id')).elements['pasaporte']).closest('.required').find('.msg').attr('rel',orig_msg);
				}	

				//acompañante
				if(data.error_dni_pax){
					//le cambio el msg al campo de dni para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['dni_'+data.pax_id]).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['dni_'+data.pax_id]).closest('.required').find('.msg').text(data.error_dni_pax);
					$(document.getElementById(me.attr('id')).elements['dni_'+data.pax_id]).closest('.required').find('.msg').attr('rel',orig_msg);
				}	
				
				//acompañante
				if(data.error_pasaporte_pax){
					//le cambio el msg al campo de pasaporte para avisar
					orig_msg = $(document.getElementById(me.attr('id')).elements['pasaporte_'+data.pax_id]).closest('.required').find('.msg').text();
					$(document.getElementById(me.attr('id')).elements['pasaporte_'+data.pax_id]).closest('.required').find('.msg').text(data.error_pasaporte_pax);
					$(document.getElementById(me.attr('id')).elements['pasaporte_'+data.pax_id]).closest('.required').find('.msg').attr('rel',orig_msg);
				}	

				/*validacion de datos de pasajeros en comparacion con datos de responsable */
				if(data.pax_invalid_email || data.pax_invalid_dni || data.pax_invalid_pasaporte){
					//le cambio el msg al campo de email, dni o pasaporte
					if($(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').length){
						console.log('aca');
						var orig_msg = $(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text();
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').text(data.msg);
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).closest('.required').find('.msg').attr('rel',orig_msg);
					}
					else{
						//caso email, dni y pasaporte NO OBLIGATORIOS en checkout (no llevan la clase .required)
						var orig_msg = $(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').text();
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').text(data.msg);
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').attr('rel',orig_msg);
						//lo hago visible
						$(document.getElementById(me.attr('id')).elements[data.fields[0]]).next('.msg').css('display','block');
					}
				}				
			}
		}
	},'json');
			
});

function obtener_combinacion(field){
	$('#field').val(field);
	
	/*if($('.detalle_compra.hidden-md.hidden-lg').hasClass('fixed')){
		$('#zocalo_mobile').val('1');
	}
	else{
		$('#zocalo_mobile').val('0');
	}*/
	
	$.post(baseURL+'paquetes/obtener_combinacion',$('#fPaquete').serialize(),function(data){
		console.log(data.compartida);
		if(data.no_action){
			//solo detalle actualizo
			if(data.detalle_calculador){
				$('#detalle_calculador .detalle_compra').html(data.detalle_calculador);
				
				//tengo que reinicializar los selectores
				init_selectpickers();
				
				//inicializo tambien el contenido del calculador
				init_aside_content();
			}
		}
		else{
			if(data.compartida){
				//cargo solo el dropdown de pasajeros
				var options = '';
				$('#pasajeros').html(options);
				for(var i=1;i<=10;i++){
					options += '<option value="'+i+'">' + i + (i==1?' Pasajero':' Pasajeros') + '</option>';
				}
				$('#pasajeros').html(options);
				$('#pasajeros').selectpicker('refresh');
				$('.pax').text('1');
			}
			else{
				if(data.form_salidas){
					$('#form_salidas').html(data.form_salidas);
				}
				if(data.form_alojamientos){
					$('#form_alojamientos').html(data.form_alojamientos);
				}
				if(data.form_transportes){
					$('#form_transportes').html(data.form_transportes);
				}
				if(data.form_adicionales){
					$('#form_adicionales').html(data.form_adicionales);
				}
				if(data.combinacion){
					$('.pax').text(data.combinacion.pax);
				}
				if(data.detalle_calculador){
					$('#detalle_calculador .detalle_compra').html(data.detalle_calculador);
				}
				if(data.forzar_moneda){
					if(data.forzar_moneda == 'USD'){
						usd();
					}
					if(data.forzar_moneda == 'ARS'){
						ars();
					}
				}
				
				//tengo que reinicializar los selectores
				init_selectpickers();
				
				//inicializo datepickers
				init_datepicker();

				//init js view paquete
				init_js_paquete();
			}
		}
	},'json');
}

function init_datepicker(){
	/*$('.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es",
		startDate: start_date,
		endDate: end_date,
		autoclose: true
	});
	$('.datepicker-free:not(.fnacimiento)').datepicker({
		format: "dd/mm/yyyy",
		language: "es",
		autoclose: true
	});
	
	var now = new Date();
	var oneYr = new Date();
	oneYr.setYear(now.getYear() - 20);
	mes = parseInt(oneYr.getMonth())+1;
	$('.datepicker-free.fnacimiento').datepicker({
		format: "dd/mm/yyyy",
		defaultViewDate: {year:oneYr.getFullYear(), month:mes-1, day:oneYr.getDate()},
		language: "es",
		autoclose: true
	});*/
}
	

/*$(".estimado__ocultar-detalles--dp").click(function() {
  $(this).toggleClass("isOpen");
  $(".ticket__detail__desgloce").slideToggle();
});*/



function init_js_paquete(){
	$(".estimado__ocultar-detalles--dp").click(function() {
	  $(this).toggleClass("isOpen");
	  $(".ticket__detail__desgloce").slideToggle();
	});



	$('.galeria .fa-expand').click(function(){
		$('.galeria .galeria__ver-mas').trigger('click')
	})

	var ticket = document.getElementById('ticket');
	var mode = 'close'
	$(".ticket__ocultar-detalle--mb").click(function(e) {
	  e.preventDefault();
	  mode = mode === 'close' ? 'open' : 'close'

	  showTicketDetail($(ticket), mode);
	});


	$('.mas_info_btn').click(function(){

		$(this).next('.dropdown_info').slideToggle()
	});
}

function showTicketDetail($el, mode) {
	if (mode === 'open') {
		$el.addClass('isOpen');
		$(".ticket__ocultar-detalle--mb").addClass('isOpen');
		$(".ticket__detail__desgloce").slideDown();
		$(".ticket .paquete__lugares-disponibles").slideDown();
		$(".ticket .paquete__consultas").slideDown();

	} else {
		$el.removeClass('isOpen');
		$(".ticket__ocultar-detalle--mb").removeClass('isOpen');
		$(".ticket__detail__desgloce").slideUp();
		$(".ticket .paquete__lugares-disponibles").slideUp();
		$(".ticket .paquete__consultas").slideUp();
	}
}


function ars(){
	//$('#tipo_moneda').val("ARS");
	moneda = 'pesos';
	$('input[name="tipo_moneda"].input_tipo_moneda[value="ARS"]').trigger('click');
	update_precio(moneda);
}
function usd(){
	//$('#tipo_moneda').val("USD");
	moneda = 'dolares';
	$('input[name="tipo_moneda"].input_tipo_moneda[value="USD"]').trigger('click');
	update_precio(moneda);
}


function update_precio(moned){	
	//actualizo el precio del viaje, no las cuotas
	var paquete_precio = $('#paquete_precio').val();
	var impuestos = $('#impuestos').val();
	var adicionales_precio = $('#adicionales_precio').val();
	
	$.post(baseURL+'paquetes/get_total_precio',$('#fPaquete').serialize(),function(data){
		if(data.success){
			//actualizo valores en detalle
			$('.'+aside+' #precio_bruto').html(data.precio_bruto);
			$('.'+aside+' #precio_impuestos').html(data.precio_impuestos);
			$('.'+aside+' #precio_final_persona').html(data.precio_bruto_persona);
			$('.'+aside+' #precio_impuestos_persona').html(data.precio_impuestos_persona);
			$('.'+aside+' #precio_total').html(data.precio_total);
			$('.'+aside+' #precio_minimo').html(data.monto_minimo_reserva_persona);
			
			$('#detalle #paquete_precio').val(data.paquete_precio);
			$('#detalle #impuestos').val(data.impuestos);
			$('#detalle #adicionales_precio').val(data.adicionales_precio);
			
		}
	},'json');
	
	
}

function update_precio_ch(moned){	
	//actualizo el precio del viaje, no las cuotas
	var paquete_precio = $('#paquete_precio').val();
	var impuestos = $('#impuestos').val();
	var adicionales_precio = $('#adicionales_precio').val();
	
	$.post(baseURL+'paquetes/get_total_precio',$('#fPaquete').serialize(),function(data){
		if(data.success){
			//actualizo valores en detalle
			$('.'+aside+' #ordenfinal').html(data.precio_total);
			$('.'+aside+' #ordenpersonaimp').html(data.precio_impuestos);
			$('.'+aside+' #ordenpersonafinal').html(data.precio_bruto_persona);
			$('.'+aside+' #ordenpersonareserva').html(data.monto_minimo_reserva_persona);
			/*
			$('.'+aside+' #precio_impuestos_persona').html(data.precio_impuestos_persona);
			$('.'+aside+' #precio_total').html(data.precio_total);
			$('.'+aside+' #precio_minimo').html(data.monto_minimo_reserva_persona);
			
			$('#detalle #paquete_precio').val(data.paquete_precio);
			$('#detalle #impuestos').val(data.impuestos);
			$('#detalle #adicionales_precio').val(data.adicionales_precio);*/
			
		}
	},'json');
	
	
}


function solicitar_reservar(){
	if($('#fecha').length){
		if($('#fecha').val() == ''){
			//muestro errores en desktop, en mobile pongo alert de campos obligatorios
			if(mobile == 1){
				$('#fecha').closest('label').addClass('error');
				
				alert('Debes completar todos los campos.');
				return false;
			}
			else{
				$('#fecha').closest('label').addClass('error');
				return false;
			}
		}
		else{
			$('#fecha').closest('label').removeClass('error');
		}
	}
	
	block_form();
	
	$.post(baseURL+'paquetes/solicitar_reservar',$('#fPaquete').serialize(),function(data){
		unblock_form();
		
		if(data.status == 'success'){
			location.href = data.redirect;
		}
	},'json');
}


function block_form(){
	$('.btnReservar').attr('disabled','disabled');
}

function unblock_form(){
	$('.btnReservar').removeAttr('disabled');
}


function sumar_adicional(valor){
	var adicionales_precio = $('#adicionales_precio').val();
	adicionales_precio = parseFloat(adicionales_precio)+parseFloat(valor);
	$('#adicionales_precio').val(adicionales_precio.toFixed(2));
	update_precio(moneda);
}

function validar_adicional(el){
	var checked = $(el).is(':checked');
	var valor = $(el).attr('data-valor');
	var pax = $('#pasajeros').val();
	valor = valor*pax;
	if(checked){
		sumar_adicional(valor);
	}
	else{
		var adicionales_precio = $('#adicionales_precio').val();
		adicionales_precio = parseFloat(adicionales_precio)-parseFloat(valor);
		$('#adicionales_precio').val(adicionales_precio.toFixed(2));
	}
	update_precio(moneda);
}


function submit_form_le(){
	var email = $("#email").val();
	
	$('#frmLE .field_required').removeClass('error');
	$('#frmLE .alert').addClass('hidden');
	
	$.post(baseURL+"lista_espera/save",$('#frmLE').serialize(),function(data){
		if(data.msg){
			$('#frmLE .alert .msg').html(data.msg);
			$('#frmLE .alert').removeClass('hidden');
		}
		
		if(data.status == 'success'){
			document.getElementById('frmLE').reset();
		}
		
		if(data.status == 'error'){
			//muestro errores
			$.each(data.fields,function(i,el){
				$(document.getElementById('frmLE').elements[el]).closest('.field_required').addClass('error');
			});
		}
	},'json');
	
	return false;
}

function campos_documentacion(obj){
	var me = obj;
		console.log('aca '+me.attr('name')+' '+me.val());	
	if(me.val() == 1){
		//argentina
		me.closest('.data_pax').find('.data_dni').show();
		me.closest('.data_pax').find('.data_dni .obligatorio').removeClass('hidden');	
		me.closest('.data_pax').find('.completar_dni_pasaporte').addClass('hidden');

		if($('#pasaporte_obligatorio').val() == 1){
			me.closest('.data_pax').find('.data_pasaporte.pasaporte_obligatorio').removeClass('hidden');
		}
		else{
			me.closest('.data_pax').find('.data_pasaporte').addClass('hidden');
		}
	}
	else{
		if(me.val()>1){
			//otro pais
			//21-08-19 ahora el campo Documento de Identificación es visible para todos
			// me.closest('.data_pax').find('.data_dni').hide();
			//me.closest('.data_pax').find('.data_pasaporte.pasaporte_obligatorio').removeClass('hidden');
			if($('#pasaporte_obligatorio').val() == 1){
				me.closest('.data_pax').find('.data_dni .obligatorio').addClass('hidden');		
			}
			else{
				//si pax no es obligatorio y eligio nac extranjero entonces el DNI o PASAPORTE uno de los 2 es obligatorio
				//me.closest('.data_pax').find('.data_pasaporte .obligatorio').first().text('Debe completar Documento y/o Pasaporte');		
				//me.closest('.data_pax').find('.data_dni .obligatorio').text('Debe completar Documento y/o Pasaporte');		
				me.closest('.data_pax').find('.completar_dni_pasaporte').removeClass('hidden');
			}

			me.closest('.data_pax').find('.data_pasaporte').removeClass('hidden');
		}
	}
}

function fb_share(url) {
  FB.ui({
    method: 'share',
    href: url,
  }, function(response){});
}

function isNumber(e) {
	k = (document.all) ? e.keyCode : e.which;
	if(k==8 || k==0) return true;
	if(k >= 96 && k <= 105) return true;//pad numerico
	if(k==9) return true; //tab
	
	patron = /^([0-9])/;
	n = String.fromCharCode(k);
	return patron.test(n);
}




if(document.querySelectorAll('.copy')){

	var btn_copy = document.querySelectorAll('.copy');

	for(var i = 0; i < btn_copy.length; i++){

		btn_copy[i].addEventListener('click', function(){

			text_copy = $(this).parent().find('*[data-copy]')[0];

			var range = document.createRange();
			range.selectNode(text_copy);
			window.getSelection().addRange(range);

			try {
				document.execCommand('copy');


				var temp = $(this).parent().find('*[data-copy]')[0];

				var orginal_color = $(temp).css('color');


				text_copy.style.color = '#000000';

				var fx = setTimeout(function(){
					text_copy.style.color = orginal_color;
				}, 200);
			}catch(err){
				return false;
			}

			window.getSelection().removeAllRanges();
		});
	}
}

function init_fecha_nacimiento(){
	var containers = document.getElementsByClassName("fecha_nacimiento");
	$.each(containers,function(i,container){
		
		container.onkeyup = function(e) {
			var target = e.srcElement;
			var maxLength = parseInt(target.attributes["maxlength"].value, 10);
			var myLength = target.value.length;
			if (myLength >= maxLength) {
				var next = target;
				while (next = next.nextElementSibling) {
					if (next == null)
						break;
					if (next.tagName.toLowerCase() == "input") {
						next.focus();
						break;
					}
				}
			}
		}
	});
}

function init_picker_nacionalidad(){
	$('.selectpicker.nacionalidad_id').on('change.bs.select', function(e){
		var me = $(this);
		console.log(me);
		campos_documentacion(me);
	});
}

function init_cuil_cuit(){
	var container = document.getElementsByClassName("campo_cuil")[0];
	if(container){
		container.onkeyup = function(e) {
			var target = e.srcElement;
			var maxLength = parseInt(target.attributes["maxlength"].value, 10);
			var myLength = target.value.length;
			if (myLength >= maxLength) {
				var next = target;
				while (next = next.nextElementSibling) {
					if (next == null)
						break;
					if (next.tagName.toLowerCase() == "input") {
						next.focus();
						break;
					}
				}
			}
		}
	}
}


function efectuar_pago(){
	if($('#reserva_id').length)
		var url = baseURL+'reservas/validar_pago';
	else
		var url = baseURL+'checkout/validar_paso4';
	
	//25-02-19 se agrego funcionalidad para procesar tambien pagos de Paypal (mediopago)
	//$.fancybox.showLoading();
	$('.pagar'+mediopago+' span').text('PROCESANDO...');
	$('.pagar'+mediopago).attr('disabled','disabled');
	$('.pagar'+mediopago).attr('readonly','readonly');

	if(!$('#user_mediopago').length){
		var input = '<input type="hidden" id="user_mediopago" name="user_mediopago" value="'+mediopago+'"/>';
	}
	else{
		$('#user_mediopago').val(mediopago);
	}
	$('#formPago').append(input);

	$.post(url,$('#formPago').serialize(),function(data){
		//$.fancybox.hideLoading();
		$('.pagar'+mediopago+' span').text('PAGAR CON');
		$('.pagar'+mediopago).attr('disabled',false);
		$('.pagar'+mediopago).attr('readonly',false);

		if(data.status == 'success'){
			if(data.redirect){
				if(mediopago == 'MP'){
					//mercadopago
					$MPC.openCheckout({
						url: data.redirect,
						mode: "redirect",
						onreturn: function(data2) {
							console.log(data2);
							if(data2.collection_id){
								url = baseURL+'reservas/registrar_transaccion_MP';
		
								$.post(url,{hash: data.hash, reserva_id: data.reserva_id, collection_id: data2.collection_id, monto: data.monto},function(data3){
									if(data3.redirect){
										location.href = data3.redirect;
									}
								},"json");
							}
						}
					});
				}
				else{
					//paypal
					location.href = data.redirect;
				}
			}
		}
		else{
			//informar algun error
			alert(data.msg);
			return false;
		}
	},'json');	
	
}


function pagar_luego(){
	$.post(baseURL+'checkout/pagar_luego',$('#formPago').serialize(),function(data){
		if(data.status == 'success'){
			location.href = data.next;
			return false;
		}
		else {
			alert('No fue posible procesar tu solicitud.');
		}
	},'json');
}

function informar_transferencia(){
	$(document.getElementById('frmInformar')).find('.required').removeClass('error');
	
	var form = $('#frmInformar')[0]; // You need to use standard javascript object here
	var formData = new FormData(form);
	formData.append('image', $('input[type=file]')[0].files[0]); 
	
	$('.msg.error').html('').hide();
	
	$.ajax({
		url: baseURL+'reservas/grabar_informe',
		data: formData,
		type: 'POST',
		dataType: "json",
		contentType: false,
		processData: false,
		success: function(data) {
			
			$('.lnkInformar').attr('disabled',false);
		
			if(data.fields){
				//muestro errores
				$.each(data.fields,function(i,el){
					$(document.getElementById('frmInformar').elements[el]).closest('.required').addClass('error');
				});
			}
			if(data.status == 'success'){
				$('.info_transferencia').addClass('hidden');
				$('.transferencia_recibida').removeClass('hidden');
			}
			if(data.status == 'error' && data.msg){
				$('.msg.error').html(data.msg).show();
			}
		}
	});

}


function submit_form_contacto(){
	$('#formContacto .req.error').removeClass('error');
	$('#formContacto #msgError').hide();
	$('#formContacto .msg').hide();

	$('#btnSendContacto').attr('disabled', 'disabled');
	$.post(baseURL+"estaticas/validar_datos",$('#formContacto').serialize(),function(data){
		$('#btnSendContacto').removeAttr('disabled');
		if (data.status == 'ERROR'){
			if(data.errors){
				for (var i=0; i<data.errors.length; i++){
					$('#' + data.errors[i]).addClass('error');
				}
			}

			$('#formContacto #msgError').text(data.msg).show();	
		}
		else{
			$('#form_data').fadeOut(function(){
				$('#form_success').fadeIn();
			});
			document.getElementById('formContacto').reset();
		}			
	},'json');
}