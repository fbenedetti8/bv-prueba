<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?> <? if($is_required){ ?><span class="required">*</span><? } ?></label>
	<div class="col-md-9">
		<input type="text" id="<?php echo $name;?>" name="<?php echo $name;?>" class="form-control <?=$fullscreen?'datepicker-fullscreen':'datepicker';?> <?=$class;?>" value="<?=set_value($name, $value);?>" <?=($readonly)?'readonly disabled':'';?>>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('.datepicker-fullscreen').pickadate({
			format: 'dd/mm/yyyy',
			formatSubmit: 'dd-mm-yyyy',
			selectYears: 20,
			selectMonths: true
		});	
	});
</script>