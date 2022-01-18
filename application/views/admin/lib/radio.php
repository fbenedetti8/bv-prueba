<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?></label>
	<div class="col-md-9">
		<?php 
		if (is_array($data)) { 
			foreach ($data as $row) { ?>
				<label class="radio<?=($mode!='')?('-'.$mode):'';?>"><input type="radio" name="<?php echo $name;?>" class="<?php echo $class;?> uniform" value="<?php echo $row[$valueField]?>" <?php ($row[$valueField]==$value)?"checked":"";?>> <?php echo $row[$captionField]?></label>
		<?php }
		} else {
			foreach ($data->result() as $row) { ?>
				<label class="radio<?=($mode!='')?('-'.$mode):'';?>"><input type="radio" name="<?php echo $name;?>" class="<?php echo $class;?> uniform" value="<?php echo $row->{$valueField}?>" <?php ($row->{$valueField}==$value)?"checked":"";?>> <?php echo $row->{$captionField}?></label>
		<?php } 
		} 
		?>
		<label for="<?php echo $name;?>" class="has-error help-block" generated="true" style="display:none;"></label>
	</div>
</div>