<?php echo $header;?>

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
    </div>
  </div>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

  <div class="row">
    <div class="col-md-12"> 
              <div class="widget box">
                <div class="widget-header">
                  <h3 style="margin-bottom:20px;">Agregar nuevo registro de caja</h3>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12"> 


						<div class="form-group">
							<label class="control-label col-md-3">Concepto:</label>
							<div class="col-md-9">
								<select class="form-control required" name="concepto" id="concepto"  onchange="javascript: habilitar(this);">
									<option value=""></option>
									<? foreach($conceptos->result() as $c): ?>
									<option value2="<?=$c->aplica_a;?>" value="<?=$c->nombre;?>" <?=(isset($row->concepto) and $row->concepto == $row->nombre)?"selected":"";?>><?=$c->nombre;?></option>
									<? endforeach; ?>
								</select>
								<span style="display:none; color:#f00;" id="error_concepto">Campo requerido</span>
							</div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Sucursal:</label>
								<div class="col-md-9">
									<select class="form-control required" name="sucursal_id" id="sucursal_id">
										<option value=""></option>
										<? foreach($sucursales as $r): ?>
										<option value="<?=$r->id;?>" <?=(isset($row->sucursal_id) and $row->sucursal_id == $r->id)?"selected":"";?>><?=$r->nombre_completo;?></option>
										<? endforeach; ?>
									</select>
									<span style="display:none; color:#f00;" id="error_sucursal">Campo requerido</span>
								</div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Ingreso:</label>
							<div class="col-md-9">
								<input type="text" name="ingreso" id="ingreso" class="form-control money" value="" placeholder="Ej 1.234,56"/>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Egreso:</label>
							<div class="col-md-9">
								<input type="text" name="egreso" id="egreso" class="form-control money" value="" placeholder="Ej 1.234,56"/>
							</div>
						</div>

						<?php echo $this->admin->textarea('observaciones', 'Observaciones', '', '');?>
					</div>
                  </div>
                </div>
                <div class="form-actions">
                  <input type="hidden" name="id" value="<?=@$row->id;?>" />
                  <input type="submit" id="btnSubmit" value="Grabar" class="btn btn-primary">
                  <input type="button" value="Cancelar" class="btn btn-default" onclick="history.back();">
                </div>
              </div>
            
    </div>
  </div>

  </form>

<?php echo $footer;	?>	


	<script type="text/javascript">
		$('.money').mask("#.##0,00", {reverse: true});
  
		$('#btnSubmit').click(function(){
			res = true;
			$('.required').each(function(){
				if ($(this).val() == '') {
					$('#error_' + this.id).show();
					res = false;
				}
				else
					$('#error_' + this.id).hide();
			});
			
			if(res)
				$("#formEdit").submit();
			else
				return false;
		});		
	
		function habilitar(obj){
			var aplica_a = $('#'+obj.id+' option:selected').attr('value2');
			$("#ingreso").val("");
			$("#egreso").val("");
			
			switch(aplica_a){
				case "haber":
					$("#ingreso").attr("disabled",false).focus();
					$("#egreso").attr("disabled",true);
				break;
				case "debe":
					$("#egreso").attr("disabled",false).focus();
					$("#ingreso").attr("disabled",true);
				break;
				case "ambos":
					$("#ingreso").attr("disabled",false).focus();
					$("#egreso").attr("disabled",false);
				break;						
			}				
		}
	</script>
