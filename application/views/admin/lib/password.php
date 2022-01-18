
<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?> <span class="required">*</span></label>
	<div class="col-md-9">
		<input type="password" name="<?php echo $name;?>" id="<?php echo $name;?>" class="form-control <?=$class;?> <?=($is_required)?'required':'';?>" minlength="4" size="<?php echo ($size != '')?$size:'30'?>" value="<?php echo $value;?>">
	</div>
</div>

<div class="form-group">
	<label class="col-md-3 control-label">Verificar <?php echo $label;?> <span class="required">*</span></label>
	<div class="col-md-9">
		<input type="password" name="<?php echo $name;?>2" id="<?php echo $name;?>2" class="form-control <?=$class;?> <?=($is_required)?'required':'';?>" minlength="4" size="<?php echo ($size != '')?$size:'30'?>" value="<?php echo $value;?>" equalto="[name='<?php echo $name;?>']">
	</div>
</div>