<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?> <? if($is_required){ ?><span class="required">*</span><? } ?></label>
	<div class="col-md-9">
		<input data-mask="<?=$format_date;?>" type="text" id="<?php echo $name;?>" name="<?php echo $name;?>" class="form-control <?=$class;?> <?=($is_required)?'required':'';?>" value="<?=set_value($name, $value);?>" <?=($readonly)?'disabled':'';?>>
		<label for="<?php echo $name;?>" generated="true" class="has-error help-block" style="display:none;"></label>
	</div>
</div>