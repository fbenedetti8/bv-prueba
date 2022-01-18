<div class="form-group">
	<label class="<?=(isset($attr['class_label']))?$attr['class_label']:'col-md-3';?> control-label"><?php echo $label;?></label>
	<div class="<?=(isset($attr['class_div']))?$attr['class_div']:'col-md-9';?>">
		<label class="checkbox<?=($mode!='')?('-'.$mode):'';?>" style="padding:0;"><input type="checkbox" name="<?php echo $name;?>" id="<?php echo $name;?>" class="<?php echo $class;?> uniform" value="1" <?php echo $value==1?"checked":"";?>></label>
		<label for="<?php echo $name;?>" class="has-error help-block" generated="true" style="display:none;"></label>
	</div>
</div>