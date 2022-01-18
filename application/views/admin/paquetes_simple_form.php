 <form id="fPaqueteAdd" method="post" action="<?=$route;?>/quicksave">
 <div class="row" id="errors" style="display:none;">
	 <div class="col-md-12">
		 <div class="alert alert-danger"></div>
	 </div>
 </div>
 <div class="row">
	<div class="col-md-12"> 
	  <?php echo $this->admin->input('nombre', 'Nombre de paquete', '', false, false); ?>
	</div>
 </div>
 <div class="row">
	<div class="col-md-12"> 
	  <?php echo $this->admin->combo('destino_id', 'Destino', 's2', false, $destinos, 'id', 'nombre'); ?>
	</div>
 </div>
 <div class="row">
	<div class="col-md-12"> 
	  <?php echo $this->admin->datepicker('fecha_inicio', 'Fecha Inicio', '', false, false, false, false); ?>
	</div>
 </div>
 <div class="row">
	<div class="col-md-12"> 
	  <?php echo $this->admin->datepicker('fecha_fin', 'Fecha Fin', '', false, false, false, false); ?>
	</div>
 </div>
 </form>
 <style>
 .datepicker.dropdown-menu { z-index:10000 !important; }
 </style>
 <script>
	$('#fecha_inicio.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es",
		startDate: new Date()
	});
	$('#fecha_fin.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es",
		startDate: new Date()
	});
	
	$('#fecha_inicio.datepicker').attr('readonly',true);
	$('#fecha_fin.datepicker').attr('readonly',true);
	
	$("#fecha_inicio.datepicker").on("changeDate", function (e) {
		var start = $("#fecha_inicio.datepicker").datepicker('getDate');
		$("#fecha_fin.datepicker").datepicker('setStartDate',start);
		$("#fecha_inicio.datepicker").datepicker('hide');
		$("#fecha_fin.datepicker").datepicker('show').focus();
    });
 </script>