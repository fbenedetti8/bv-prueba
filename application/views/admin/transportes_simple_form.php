 <form id="fTransporteAdd" method="post" action="<?=$route;?>/quicksave">
 <div class="row">
	<div class="col-md-12"> 
	  <?php echo $this->admin->input('nombre', 'Nombre de transporte', '', false, false); ?>
	</div>
 </div>
 <div class="row">
	<div class="col-md-12"> 
	  <?php echo $this->admin->combo('tipo_id', 'Tipo de transporte', 's2', false, $tipos, 'id', 'nombre'); ?>
	</div>
 </div>
 </form>
 <style>
 .datepicker.dropdown-menu { z-index:10000 !important; }
 </style>
 <script>
	$('.datepicker').datepicker({
		format: "dd/mm/yyyy",
		language: "es"
	});
 </script>