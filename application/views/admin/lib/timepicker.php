<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?> <? if($is_required){ ?><span class="required">*</span><? } ?></label>
	<div class="col-md-9">
		<input type="text" id="<?php echo $name;?>" name="<?php echo $name;?>" class="form-control timepicker-fullscreen <?=$class;?>" value="<?=set_value($name, $value);?>" <?=($readonly)?'readonly':'';?>>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('.timepicker-fullscreen').pickatime({
			format: 'HH:i',
			formatSubmit: 'HH:i'
		});
	});
</script>