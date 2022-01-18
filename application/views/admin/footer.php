			</div>
			<!-- /.container -->

		</div>
	</div>

	
<script type="text/javascript" src="<?php echo base_url()?>media/admin/assets/js/tinymce/tinymce.min.js"></script>
<!--
<link rel="stylesheet" href="<?php echo base_url()?>media/admin/assets/js/tinyeditor/style.css" />
-->

<script type="text/javascript" src="<?php echo base_url()?>media/admin/assets/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>media/admin/assets/js/locales/bootstrap-datepicker.es.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>media/admin/assets/css/datepicker.css" />
<script type="text/javascript" src="<?php echo base_url()?>media/admin/assets/js/jquery.simpletip-1.3.1.min.js"></script>	
<script type="text/javascript" src="<?php echo base_url()?>media/admin/assets/js/jquery.mask.js"></script>

<script type="text/javascript">
	function update_chars(obj,length,limit){
		if(length > limit){
			$(obj).val(text.substr(0,limit));
	    	$(obj).parent().find('.countinfo span').text(0);
	    	//$(obj).closest('.input-group').find('.countinfo span').text(0);
	    }
	    else{
			$(obj).parent().find('.countinfo span').text(limit-length);
			//$(obj).closest('.input-group').find('.countinfo span').text(limit-length);
	    }
	}

	$(document).ready(function(){

		$(".limited").keyup(function (e) {
			var text = $(this).val(); 
		    var length = text.length;
		    var limit = $(this).data('limit');
		    console.log('aca0');
		    console.log(length);
		    console.log(limit);
		    console.log(text);
		    update_chars(this,length,limit);
		});

		$(".limited").keydown(function (e) {
		   var $this      = $(this),
		       charLength = $this.val().length,
		       charLimit  = $this.attr("data-limit");
		    console.log('aca1');
		    console.log(charLength);
		    console.log(charLimit);
		   if ($this.val().length >= charLimit && e.keyCode !== 8 && e.keyCode !== 46) {
		       return false;
		   }
		});
		


		// se usa en listado de reservas por paquete y en interna de form de reserva
		$('.estados_cambio').change(function(){
			var obj = $(this);
			
			var url = "<?=$route;?>/cambiar_estado/"+obj.val();
			
			var valor_ant = obj.attr('data-prev-value');
			
			var valor = obj.val();
			valor = valor.split("/");
			
			//si el estado seleccionado es ANULADA -> le pido confirmacion
			if( valor[1] == '5'){
				bootbox.prompt({
					title: "La reserva pasará a estar <b>ANULADA</b>.<br> Por favor ingresa el <b>motivo</b> de anulación:",
					inputType: 'textarea',
					callback: function (result) {
						if(!result){
							obj.find('option[VALUE="'+valor_ant+'"]').attr("selected",true);
							bootbox.alert("Debes ingresar un motivo de anulación.");
							return false;
						}
						else{
							$.post(url,{motivo: result},function(data2){
								if(data2){
									
									bootbox.alert("Cambio de estado: OK");
									return false;
								}
								else{
									
									bootbox.alert("Cambio de estado: NO.<br>Intente mas tarde.");
									return false;
								}
							});
						}
					}
				});
			}
			else if(valor[1] != '5' && valor[1] != '12'){   //else if(valor[1] == '1'){ //si la quiere pasar a estado NUEVA
			//01-09-2014  ->  si la quiere pasar a cualquier otro estado que no sea ANULADA o LISTA ESPERA
				//chequeo la disponibilidad de cupo para ese viaje
				var reserva_id = valor[0];
				
				$.post("<?=base_url();?>admin/reservas/chequear_disponibilidad_paquete/<?=@$paquete_id;?>/"+reserva_id,function(data){
					if(data == 0){
						
						
						obj.find('option[VALUE="'+valor_ant+'"]').attr("selected",true);
						bootbox.alert("El paquete no tiene cupo disponible.");
						return false;
					}
					else{
						//chequeo la disponibilidad de adicionales para ese viaje
						$.post("<?=base_url();?>admin/reservas/chequear_disponibilidad_adicionales/<?=@$paquete_id;?>/"+reserva_id,function(data){
							if(data.error){
								
								
								obj.find('option[VALUE="'+valor_ant+'"]').attr("selected",true);
								bootbox.alert(data.msg);
								return false;
							}
							else{
								//todo ok
								$.post(url,function(data){
									if(data){
										
										bootbox.alert("Cambio de estado: OK");
										return false;
									}
									else{
										
										bootbox.alert("Cambio de estado: NO.<br>Intente mas tarde.");
										return false;
									}
								});
							}
							
						},'json');
					}
				});
			}
			else{
					
				
				//si es otro estado no pido confirmacion
				$.post(url,function(data){
					if(data){
							
						bootbox.alert("Cambio de estado: OK");
						return false;
					}
					else{
							
						bootbox.alert("Cambio de estado: NO.<br>Intente mas tarde.");
						return false;
					}
				});
			}
		});
					
					
		Ladda.bind( 'button[type=submit]' );

		$('.s2').select2();

		$('input[type=checkbox]:not(.default)').bootstrapSwitch({ onText: 'SI', offText: 'NO' });
		
		//$('.datepicker').datepicker();
		$('.datepicker').datepicker({
			format: "dd/mm/yyyy",
			language: "es"
		});
		
		$(".fancybox").fancybox();
		
		$('.blocking').click(function(e){
			e.preventDefault();
			$.blockUI();
		});

		//para videos youtube
		$('.fancybox-media').fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			helpers : {
				media : {}
			}
		});
	
		$("a.modal-alert").click(function(e) {
			e.preventDefault();
			var url = $(this).attr('data-href');
			$.post(url,function(data){
				if(data.msg){
					bootbox.dialog({
						message: data.msg,
						title: data.title,
						buttons: {
							success: {
								label: "Cerrar",
								className: "btn-primary"
							}
						}
					});
				}
			},"json");
		});
		
		$('#btnReset').click(function(e){
			$("#keywords").val("");
			location.href = "<?php echo site_url('admin/reset');?>";
		});
				
		$('.delete').click(function(e){
			e.preventDefault();
			var me = this;

			bootbox.confirm("Esta seguro que desea borrar el registro?", function(result){
				if (result) {
					window.location = me.href;
				}
			});
		});	
		
		$('.lnkBulkSort').click(function(e){
			e.preventDefault();
			$.post($(this).attr('href'), $('#' + $(this).data('form')).serialize(), function(){
				location.reload();
			});
			return false;
		});
		
		$('.sort').click(function(e){
			e.preventDefault();
			if ($('#sort').val() == this.id) {
				if ($('#sortType').val() == 'ASC') {
					$('#sortType').val('DESC');
				}
				else
					$('#sortType').val('ASC');
			} 
			else {
				$('#sortType').val('ASC');
			}
			$('#sort').val(this.id);
			if(document.getElementById('frmSearch'))
				document.getElementById('frmSearch').submit();
			else if(document.getElementById('frmFilter'))
				document.getElementById('frmFilter').submit();
		});	
		
		$('.toggle_2').click(function(e){
			e.preventDefault();
				
			var me = this;
			$(me).addClass('loading');
			
			$.get(this.href + this.rel, function(data){
				$(me).removeClass('loading');
				if (data == 0) {
					me.rel = 1;
					$(me).removeClass('yes').addClass('no');
				} else {
					me.rel = 0;
					$(me).removeClass('no').addClass('yes');
				}
			});
				
			return false;
		});
		
		//para url friendly
		$(".url_friendly").keyup(function (e) {
			var text = $(this).val(); 
		    var length = text.length;
		    var limit = $(this).data('limit');
			
			$(this).val(text.replace(/[^a-zA-Z0-9\-\_]/g,''));
			
		    if(length > limit){
				$(this).val(text.substr(0,limit));
		    }
		});

		$(".url_friendly").keydown(function (e) {
		   var $this      = $(this),
		       charLength = $this.val().length,
		       charLimit  = $this.attr("data-limit");
		    
			$this.val($this.val().replace(/[^a-zA-Z0-9\-\_]/g,''));
			
		   if ($this.val().length >= charLimit && e.keyCode !== 8 && e.keyCode !== 46) {
		       return false;
		   }
		});

		$('.btnRemoveImg').click(function(e){
			e.preventDefault();
			var size = $(this).data('size');
			var img = $('#size_' + size).css('background-image').replace(/.*\s?url\([\'\"]?/, '').replace(/[\'\"]?\).*/, '');
			bootbox.confirm('Esta operación no es reversible. ¿Está seguro?', function(res){
				if (res) {
					$.post('<?=$route;?>/upload_remove', {img:img}, function(){
						$('#size_' + size).css('background-image', 'none');
					});
				}
			});
		});

		/* restriccion chars form checkout */
		if($( ".onlynum" ).length){
			$("body").on("keypress",".onlynum:not(.mobile)",function( e ) {
				return isNumber(e);
			});
			$("body").on("keydown",".onlynum:not(.mobile)",function( e ) {
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


		   //abre popup para cambiar de paquete, se usa en reserva_form
		  $("body").on('click','.btnCambiarPaquete',function(e) {
				e.preventDefault();
				var me = $(this);
				var url = me.attr('data-href');
				var code = me.attr('data-codigo');
				var grupal = me.attr('data-grupal');

				if(grupal == '1'){
					bootbox.confirm("Deseas hacer cambio de combinación para dicha reserva?<br>Recuerda que el cambio se realizará también en otras reservas con el mismo código de grupo.",function(result){
						if (result) {
							popup_cambiar_combinacion(url,code,'','');
						}
					});
				}
				else{
					popup_cambiar_combinacion(url,code,'','');
				}
				
			});
			
	});		
		
	/*
	code: codigo de reserva
	num: numero de habitacion elegida desde rooming (desde reservas no se usa)
	*/
	function popup_cambiar_combinacion(url,code,num,hab_id){
		$.post(url,{nro_hab: num, hab_id: hab_id},function(data){
			if(data.view){
				var dialog = bootbox.dialog({
					title: 'Cambio de combinación del paquete para la reserva Cód. '+code,
					message: data.view,
					buttons: {
						cancel: {
							label: "Cancelar",
							className: 'btn-danger',
							callback: function(){
								if(hab_id){
									//si viene de arrastrar pax desde el rooming y no eligio combinacion, cancelo el drag
									$( ".sortablelist" ).sortable( "cancel" );
								}
							}
						},
						ok: {
							label: "OK",
							className: 'btn-success',
							callback: function(){
								//chequeo que haya seleccionado alguna combinacion
								//ó si está la combinacion por defecto elegida
								var comb = false;
								if($('#combinacion_actual').length){
									var comb = $('#combinacion_actual').val();
								}

								if(!$('.comb_opcion:checked').val() && !comb){
									bootbox.alert('Debes seleccionar alguna de las combinaciones del paquete.');
									return false;
								}
								
								var furl = $('#fCambioPaquete').attr('action');
								$.post(furl,$('#fCambioPaquete').serialize(),function(d){
									if(d.status == 'success'){
										bootbox.alert(d.msg,function(){
											location.href=location.href;
										});
									}
								},'json');
							}
						}
					}
				});
			}
		},"json");
	}

	function isNumber(e) {
		k = (document.all) ? e.keyCode : e.which;
		console.log(k);
		if(k==8 || k==0) return true;
		if(k >= 96 && k <= 105) return true;//pad numerico
		if(k==9) return true; //tab
		
		patron = /^([0-9])/;
		n = String.fromCharCode(k);
		return patron.test(n);
	}

	function validar(){
		//Agregar inputs hiddens para los checkboxes que no esten marcados ya que sino no viajan en el post
		$('input').each(function(){
			if ($(this).is('input:checkbox') && !$(this).is(':checked')) {
				$('<input type="hidden" value="0" name="' + this.name + '" />').appendTo('form');
			}
		});	
		
		var res = true;
		$('input.required').each(function(){
			if ($(this).val() == '') {
				res = false;
			}
		});		
		
		if(!res){
			bootbox.alert('Los campos marcados con * son obligatorios.');
			Ladda.stopAll()
			return false;
		}
		
		return true;
	}
	
	
	function init_max_length(name,max_length){
		$('#'+name).on('input change blur focus',function() {
		  var length = $(this).val().length;
		  $('#'+name+'_chars').text(max_length-length);
				
			if(length < max_length){
				return true;
			}else if(length > max_length){			
				var sobrante = $('#'+name).val().slice(0, max_length);
				$('#'+name).val(sobrante);
				$('#'+name+'_chars').text('0 caracteres restantes');
				return false;
			}
		});
		$('#'+name).change();
	}
	
	
	function max_length(element,max_chars)
	{
		if(element.value.length > max_chars) {
			element.value = element.value.substr(0, max_chars);
		}
	}
</script>


</body>
</html>
