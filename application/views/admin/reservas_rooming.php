<?php echo $header;?>

<style>
  .sortablelist {
    border: 1px solid #eee;
    min-height: 200px;
    list-style-type: none;
    margin: 0;
    padding: 0px 10px;
    margin-right: 10px;
  }
  .sortablelist li, .sortablelist li {
    margin: 0 5px 5px 5px;
    padding: 5px;
    font-size: 14px;
    width: 100%;
  }
  .widget-header h4 { vertical-align: middle; line-height: 20px; }
  .widget-header h4 small { margin: 0px 0 0px 20px; display: inline-block; }
  .sortablelist li a { float:right; }
  .ui-state-default.ui-state-disabled, .ui-widget-content .ui-state-default.ui-state-disabled, .ui-widget-header .ui-state-default.ui-state-disabled { cursor: not-allowed !important; background-color: #ffcfcf; }
  </style>

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
      <h4><?=$paquete->nombre.' Cód: '.$paquete->codigo;?></h3>
    </div>
    <p style="float: left;display: block;width: 100%;">Para descargar el rooming debes guardar primero los cambios utilizando los botones al pie de esta página.</p>
  </div>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save_rooming" enctype="multipart/form-data">

  <div class="row">
  	<div class="col-md-12"> 
    	
    	<div class="alert alert-info">
    		<p><strong>Asignación de pasajeros</strong>: Para asignar cada uno de los pasajeros a las diferentes habitaciones debes arrastrar cada uno de ellos dentro de la caja de habitación que desees.<br>En caso de corresponder un cambio de combinación en la reserva, debe realizarse desde el formulario de cada una, la cual se puede acceder desde el boton <i class="icon-external-link"></i> que figura junto al nombre del pasajero.</p>
    	</div>

    	<div class="row">
			<div class="col-md-3 box-hab" data-num="" data-rel="-1">
				<!-- data-rel en -1 (hab_id) para diferenciarlo del form de reserva -->
				<!-- este no lleva ID porque es el listado de pasajeros sin asignar-->
			 	<div class="widget box">
					<div class="widget-header">
						<h4><i class="icon-user"></i> Compartida</h4>
					</div>
					<div class="widget-content">
						<div class="row">
							<ul id="pasajeros_sin_asignar" class="sortablelist connectedSortable">
								<? foreach ($reservas as $r) :?>
									<? if(!isset($r->num_habitacion)): ?>
										<li class="ui-state-default <?=($r->estado_id!=4)?'ui-state-disabled':'';?>"><?=$r->pasajeros;?> <?=$r->codigo_grupo?('<br><small>Grupo: '.$r->codigo_grupo.'</small>'):'';?> <a href="<?=base_url();?>admin/reservas/edit/<?=$r->id?>" title="Ver reserva" target="_blank"><i class="icon-external-link"></i></a>
										<input type="hidden" class="reserva_ids" data-rid="<?=$r->id;?>" data-rcode="<?=$r->code;?>" name="reserva_ids[<?=$r->id;?>]" value="<?=@$r->rooming_cupo_id;?>|<?=@$r->rooming_nro_hab;?>"/>
										<!-- el value de este hidden es si tiene algun rooming seteado, para por JS automoverlo cuando se abre esta pagina -->
										</li>
									<? endif; ?>
								<? endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		 
		 	<div class="col-md-9">
			<? foreach ($habitaciones as $nombrehotel=>$habs) :?>
				<div class="clearfix"></div>
				<div class="col-md-12"><h3><b><?=$nombrehotel;?></b></h3></div>
				<? foreach ($habs as $pos=>$h) : //Maxi 27-01-2020 reemplazao el $pos+1 de abajo por $h['numHab']?>
					 <div class="col-md-4 box-hab box_hab_<?=$h['numHab'];?>_<?=$h['fecha_alojamiento_cupo_id'];?>" data-num="<?=$h['numHab'];?>" data-rel="<?=$h['fecha_alojamiento_cupo_id'];?>">
					 	<div class="widget box">
							<div class="widget-header">
								<h4>#<?=$h['numHab'];?> Habitación <?=$h['nombre'];?></h4>
							</div>
							<div class="widget-content" >
								<div class="row">
									<ul  class="sortablelist connectedSortable" data-max="<?=$h['pax'];?>">
								  		<? foreach ($h['pasajeros'] as $p) :?>
											<li class="ui-state-default <?=($p->estado_id!=4)?'ui-state-disabled':'';?>"><?=$p->pasajeros;?> <?=$p->codigo_grupo?('<br><small>Grupo: '.$p->codigo_grupo.'</small>'):'';?> <a href="<?=base_url();?>admin/reservas/edit/<?=$p->id?>" title="Ver reserva" target="_blank"><i class="icon-external-link"></i></a>
											<input type="hidden" class="reserva_ids" data-rid="<?=$p->id;?>" data-rcode="<?=$p->code;?>" name="reserva_ids[<?=$p->id;?>]" value="<?=$h['fecha_alojamiento_cupo_id'];?>|<?=$h['numHab'];?>"/>
											</li>
										<? endforeach; ?>
									</ul>
								</div>
							</div>
						</div>
						
					</div>
				<? endforeach; ?>
			<? endforeach; ?>
			</div>
		</div>

		<div class="form-actions">
              <input type="hidden" name="paquete_id" value="<?=$paquete->id;?>">
              <a href="#" class="btn btn-primary btnSave">Guardar</a>
              <a target="_blank" href="<?=site_url('admin/reservas/download_rooming/'.$paquete->id);?>" class="btn btn-success btnDown">Descargar</a>
              <input type="button" value="Volver" class="btn btn-default" onclick="history.back();">
            </div>
    </div>
  </div>

  
            

  </form>

<script>
	var silent = false;

	$(document).ready(function(){
		//Grabar lo que se haya auto-asignado
		//$.post($('#formEdit').attr('action'),$('form').serialize(),function(data){ });
console.log('11');
		$('body').on('click','.btnSave',function(e){
			e.preventDefault();

			$.post($('#formEdit').attr('action'),$('form').serialize(),function(data){
				if(data.status == 'OK'){
					if(!silent){
						bootbox.alert('El Rooming fue actualizado correctamente');
					}
					silent=false;
					return false;
				}
			},'json');
		});

		
	});

	$( function() {
		console.log('a');
	    $( ".sortablelist" ).sortable({
	      	connectWith: ".connectedSortable:not(.full)",
	      	items: "li:not(.ui-state-disabled)",
	      	receive: function( event, ui ) {
				
		console.log('caca');
				
	      		var h_id = $(ui.item).closest('.box-hab').attr('data-rel');
	      		var num = $(ui.item).closest('.box-hab').attr('data-num');
	      		h_id = h_id ? h_id : "";
	      		num = num ? num : "";
	      		var a = $(ui.item).find('.reserva_ids').val(h_id+'|'+num);
	      		var rid = $(ui.item).find('.reserva_ids').attr('data-rid');//id de reserva
	      		var rcode = $(ui.item).find('.reserva_ids').attr('data-rcode');//codigo

		console.log('pis');
				
	      		//consulto si desea cambiar de combiacion
	      		bootbox.confirm("Deseas hacer cambio de combinación para dicha reserva?<br>Recuerda que el cambio se realizará también en otras reservas con el mismo código de grupo.",function(result){
					if (result) {
						var url = '<?=base_url();?>admin/reservas/cambiar_combinacion/'+rid;
						$.post(url,function(data){
						  	if(data){
						 		popup_cambiar_combinacion(data,rcode,num,h_id);

	      						check_room_is_full();
	      						/*silent = true;
	      						$('.btnSave').trigger('click');*/
						  	}
						});
					}
					else{
						//si eligio que no va a cambiar combinacion, entonces cancelo el pasajero del pax a la hab privada
						$( ".sortablelist" ).sortable( "cancel" );
						check_room_is_full();
						/*silent = true;
						$('.btnSave').trigger('click');*/
					}
				});

		console.log('loro');
				
	      	}
	    }).disableSelection();
console.log('b');
	    //si tengo rooming cargado, hago la reasignacion
		$('#pasajeros_sin_asignar li').each(function(){
		    var $this = $(this);

		    var valores = $this.find('.reserva_ids').val();
console.log('c');
		    //si los pasajeros tienen la marca de rooming seteada, los muevo
		    //el valor puede ser del estilo X|X
		    //al menos deberia tener el pipe |
		    if(valores.length > 1){
		console.log('d');
				var aux = valores;
		    	aux = aux.split('|');
		    	var clase = 'box_hab_'+aux[1]+'_'+aux[0];
			
				var listname = '.'+clase+' .sortablelist';
		    console.log('e');
		    	$this.appendTo(listname);
				// Trigger
		    	$(listname).sortable('option', 'receive', { item: $this });
console.log('f');
		    }	
		    
		});
console.log('g');
		check_room_is_full();
console.log('h');
	  } );

	function check_room_is_full(){
		//por cada hab, me fijo si alcanzó la cantidad max de pax
		$('.sortablelist').each(function(){
		    var me = $(this);

		    if(me.find('li').length == me.attr('data-max')){
		    	me.addClass('full');
		    }
		    else{
		    	me.removeClass('full');
		    }
		});

	}
</script>

<?php echo $footer; ?>