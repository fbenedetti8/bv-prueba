<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?> <?php if($is_required){ ?><span class="required">*</span><?php } ?></label>
	<div class="col-md-9 ">
		<input type="<?=@$input_type?$input_type:'text';?>" id="<?php echo $name;?>" name="<?php echo $name;?>" class="form-control <?php echo $class;?> <?php echo ($is_required)?'required':'';?> <?=$limit ? 'limited' : '';?>" <?=$limit ? 'data-limit="'.$limit.'"' : '';?> value="<?php echo set_value($name, $value);?>" <?php echo ($readonly)?'disabled':'';?> <?=isset($placeholder)?('placeholder="'.$placeholder.'"'):'';?> style="<?php echo $style;?>">
		<span><small><b><?php echo (isset($note) && $note)?$note:'';?></b></small></span>
		<? if ($limit): ?>
		<span class="countinfo"><span><?=$limit - strlen($value);?></span> caracteres disponibles</span>
		<? endif; ?>
		<label for="<?php echo $name;?>" generated="true" class="has-error help-block" style="display:none;"></label>
		<? if($max_length !== false){ ?>
			<span id="<?php echo $name;?>_chars"><?=$max_length;?></span> caracteres restantes
		<? } ?>
	</div>
</div>