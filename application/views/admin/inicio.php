<?php echo $header;?>

	<style>
	.wid { display:block; padding:5px 10px; border:solid 1px #CCC; margin:5px 0; position:relative; cursor:move; }
	.area, .lists { min-height:310px; }
	.btn-remove-widget { display:block; padding:6px 10px; position:absolute; top:-1px; right:-1px; }
	</style>

	<!--=== Page Header ===-->
	<div class="page-header">
		<div class="page-title">
			<h3>Configuración de Página Inicial</h3>
		</div>
	</div>
	<br/>
	<form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

	<div class="row">
		<div class="col-md-12">	
			<div class="tabbable tabbable-custom tabbable-full-width">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-viajeros-desktop" data-toggle="tab">Viajeros Desktop</a></li>
					<li class=""><a href="#tab-viajeros-mobile" data-toggle="tab">Viajeros Mobile</a></li>
					<li class=""><a href="#tab-agencias-desktop" data-toggle="tab">Agencias Desktop</a></li>
					<li class=""><a href="#tab-agencias-mobile" data-toggle="tab">Agencias Mobile</a></li>
				</ul>
				<div class="tab-content row">
					<div class="tab-pane active" id="tab-viajeros-desktop">
						<div class="col-md-8">
							<div class="widget box">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Activos</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 area area-viajeros-desktop">	
											<? foreach ($widgets_viajeros_desktop_on as $widget): ?>
											<div class="wid added" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
												<input type="hidden" name="widget_viajeros_desktop[]" value="<?=$widget->id;?>" />
												<a class="btn btn-xs btn-remove-widget">X</a>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="widget box" data-spy="affix" data-offset-top="150" data-offset-bottom="130">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Disponibles</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 lists" id="list-widgets-viajeros-desktop">	
											<? foreach ($widgets_viajeros_desktop as $widget): ?>
											<div id="widget-<?=$widget->id;?>" class="wid" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane" id="tab-viajeros-mobile">
						<div class="col-md-8">
							<div class="widget box">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Activos</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 area area-viajeros-mobile">	
											<? foreach ($widgets_viajeros_mobile_on as $widget): ?>
											<div class="wid added" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
												<input type="hidden" name="widget_viajeros_mobile[]" value="<?=$widget->id;?>" />
												<a class="btn btn-xs btn-remove-widget">X</a>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="widget box" data-spy="affix" data-offset-top="150" data-offset-bottom="130">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Disponibles</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 lists" id="list-widgets-viajeros-mobile">	
											<? foreach ($widgets_viajeros_mobile as $widget): ?>
											<div id="widget-<?=$widget->id;?>" class="wid" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane" id="tab-agencias-desktop">
						<div class="col-md-8">
							<div class="widget box">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Activos</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 area area-agencias-desktop">	
											<? foreach ($widgets_agencias_desktop_on as $widget): ?>
											<div class="wid added" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
												<input type="hidden" name="widget_agencias_desktop[]" value="<?=$widget->id;?>" />
												<a class="btn btn-xs btn-remove-widget">X</a>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="widget box" data-spy="affix" data-offset-top="150" data-offset-bottom="130">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Disponibles</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 lists" id="list-widgets-agencias-desktop">	
											<? foreach ($widgets_agencias_desktop as $widget): ?>
											<div id="widget-<?=$widget->id;?>" class="wid" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane" id="tab-agencias-mobile">
						<div class="col-md-8">
							<div class="widget box">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Activos</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 area area-agencias-mobile">	
											<? foreach ($widgets_agencias_mobile_on as $widget): ?>
											<div class="wid added" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
												<input type="hidden" name="widget_agencias_mobile[]" value="<?=$widget->id;?>" />
												<a class="btn btn-xs btn-remove-widget">X</a>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="widget box" data-spy="affix" data-offset-top="150" data-offset-bottom="130">
								<div class="widget-header">
									<h3 style="margin-bottom:20px;">Widgets Disponibles</h3>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-12 lists" id="list-widgets-agencias-mobile">	
											<? foreach ($widgets_agencias_mobile as $widget): ?>
											<div id="widget-<?=$widget->id;?>" class="wid" data-widget="<?=$widget->id;?>">
												<?=$widget->nombre;?>
											</div>
											<? endforeach; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-actions">
				<input type="submit" value="Grabar" class="btn btn-primary">
			</div>
		</div>

	</div>

	</form>

	<script>
	var config_droppable_viajeros_desktop = {
		  accept: ".wid",
		  activeClass: "ui-state-hover",
		  hoverClass: "ui-state-active",
		  drop: function( event, ui ) {
		  	if ($(ui.draggable).hasClass('added')) {
		  		return;
		  	}
		  	else {
			  	var widget_id = ui.draggable.data('widget');
			  	var obj = $(ui.draggable).clone();

			  	obj.append('<input type="hidden" name="widget_viajeros_desktop[]" value="'+widget_id+'" />');
				obj.append('<a class="btn btn-xs btn-remove-widget">X</a>');
				obj.removeClass('list-group-item-info');
				obj.addClass('added').appendTo('.area-viajeros-desktop');
				
				ui.draggable.slideUp();			
			}
		  }
	};

	var config_droppable_viajeros_mobile = {
		  accept: ".wid",
		  activeClass: "ui-state-hover",
		  hoverClass: "ui-state-active",
		  drop: function( event, ui ) {
		  	if ($(ui.draggable).hasClass('added')) {
		  		return;
		  	}
		  	else {
			  	var widget_id = ui.draggable.data('widget');
			  	var obj = $(ui.draggable).clone();

			  	obj.append('<input type="hidden" name="widget_viajeros_mobile[]" value="'+widget_id+'" />');
				obj.append('<a class="btn btn-xs btn-remove-widget">X</a>');
				obj.removeClass('list-group-item-info');
				obj.addClass('added').appendTo('.area-viajeros-mobile');
				
				ui.draggable.slideUp();			
			}
		  }
	};

	var config_droppable_agencias_desktop = {
		  accept: ".wid",
		  activeClass: "ui-state-hover",
		  hoverClass: "ui-state-active",
		  drop: function( event, ui ) {
		  	if ($(ui.draggable).hasClass('added')) {
		  		return;
		  	}
		  	else {
			  	var widget_id = ui.draggable.data('widget');
			  	var obj = $(ui.draggable).clone();

			  	obj.append('<input type="hidden" name="widget_agencias_desktop[]" value="'+widget_id+'" />');
				obj.append('<a class="btn btn-xs btn-remove-widget">X</a>');
				obj.removeClass('list-group-item-info');
				obj.addClass('added').appendTo('.area-agencias-desktop');
				
				ui.draggable.slideUp();			
			}
		  }
	};	

	var config_droppable_agencias_mobile = {
		  accept: ".wid",
		  activeClass: "ui-state-hover",
		  hoverClass: "ui-state-active",
		  drop: function( event, ui ) {
		  	if ($(ui.draggable).hasClass('added')) {
		  		return;
		  	}
		  	else {
			  	var widget_id = ui.draggable.data('widget');
			  	var obj = $(ui.draggable).clone();

			  	obj.append('<input type="hidden" name="widget_agencias_mobile[]" value="'+widget_id+'" />');
				obj.append('<a class="btn btn-xs btn-remove-widget">X</a>');
				obj.removeClass('list-group-item-info');
				obj.addClass('added').appendTo('.area-agencias-mobile');
				
				ui.draggable.slideUp();			
			}
		  }
	};

	$(document).ready(function(){
		var $listWidgetsViajerosDesktop = $("#list-widgets-viajeros-desktop");
		var $listWidgetsViajerosMobile = $("#list-widgets-viajeros-mobile");
		var $listWidgetsAgenciasDesktop = $("#list-widgets-agencias-desktop");
		var $listWidgetsAgenciasMobile = $("#list-widgets-agencias-mobile");
				
		$('body').on('click', '.btn-remove-widget', function(e){
			e.preventDefault();
			var $widget = $(this).parent();
			$widget.slideUp( 'slow', function() { $widget.remove() } );
			$('.lists #widget-' + $widget.data('widget') ).slideDown( 'slow' );
		});

		$(".wid", $listWidgetsViajerosDesktop).draggable({
			revert: "invalid",
			helper: "clone",
			cursor: "move",
			zIndex: 3000,
			live: true,
			drag: function( event, ui ) {
				$(this).addClass("list-group-item-info");
			},
			stop: function( event, ui ) {
				$(this).removeClass("list-group-item-info");
			},
		});

		$(".wid", $listWidgetsViajerosMobile).draggable({
			revert: "invalid",
			helper: "clone",
			cursor: "move",
			zIndex: 3000,
			live: true,
			drag: function( event, ui ) {
				$(this).addClass("list-group-item-info");
			},
			stop: function( event, ui ) {
				$(this).removeClass("list-group-item-info");
			},
		});		

		$(".wid", $listWidgetsAgenciasDesktop).draggable({
			revert: "invalid",
			helper: "clone",
			cursor: "move",
			zIndex: 3000,
			live: true,
			drag: function( event, ui ) {
				$(this).addClass("list-group-item-info");
			},
			stop: function( event, ui ) {
				$(this).removeClass("list-group-item-info");
			},
		});

		$(".wid", $listWidgetsAgenciasMobile).draggable({
			revert: "invalid",
			helper: "clone",
			cursor: "move",
			zIndex: 3000,
			live: true,
			drag: function( event, ui ) {
				$(this).addClass("list-group-item-info");
			},
			stop: function( event, ui ) {
				$(this).removeClass("list-group-item-info");
			},
		});	
		
		
		$('.area-viajeros-desktop').droppable(config_droppable_viajeros_desktop);		
		$('.area-viajeros-mobile').droppable(config_droppable_viajeros_mobile);		
		$('.area-agencias-desktop').droppable(config_droppable_agencias_desktop);		
		$('.area-agencias-mobile').droppable(config_droppable_agencias_mobile);
		
		// Inicio para elementos cargados x db
		$('.area-viajeros-desktop').sortable({
			  connectWith:".area",
			  placeholder: 'sortable-placeholder'
		});

		$('.area-viajeros-mobile').sortable({
			  connectWith:".area",
			  placeholder: 'sortable-placeholder'
		});		

		$('.area-agencias-desktop').sortable({
			  connectWith:".area",
			  placeholder: 'sortable-placeholder'
		});		

		$('.area-agencias-mobile').sortable({
			  connectWith:".area",
			  placeholder: 'sortable-placeholder'
		});		

		$('.area-viajeros-desktop .added').each(function(index, obj){
			$('#list-widgets-viajeros-desktop #widget-' + $(obj).data('widget')).hide();
		});

		$('.area-viajeros-mobile .added').each(function(index, obj){
			$('#list-widgets-viajeros-mobile #widget-' + $(obj).data('widget')).hide();
		});

		$('.area-agencias-desktop .added').each(function(index, obj){
			$('#list-widgets-agencias-desktop #widget-' + $(obj).data('widget')).hide();
		});

		$('.area-agencias-mobile .added').each(function(index, obj){
			$('#list-widgets-agencias-mobile #widget-' + $(obj).data('widget')).hide();
		});
	});
	</script>

		
<?php echo $footer;	?>