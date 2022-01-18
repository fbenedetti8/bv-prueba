<? if ($twolines): ?>
<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?></label>
</div>
<div class="form-group">
	<div class="col-md-9">
		<textarea rows="<?=isset($rows)?$rows:7;?>" cols="<?=isset($cols)?$cols:50;?>" id="<?php echo $name;?>" name="<?php echo $name;?>" class="<?=($chars_limit!='')?'limited':'';?> form-control <?=$class;?>" <?=($chars_limit!='')?('data-limit="'.$chars_limit.'"'):'';?>><?=set_value($name, $value);?></textarea>
		<? if ($chars_limit): ?>
		<span class="countinfo"><span><?=$chars_limit - strlen($value);?></span> caracteres disponibles</span>
		<? endif; ?>
	</div>
</div>
<? else: ?>
<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?></label>
	<div class="col-md-9">
		<div class="input-group" style="width:100%;">
			<textarea rows="<?=isset($rows)?$rows:7;?>" id="<?php echo $name;?>" name="<?php echo $name;?>" class="<?=($chars_limit!='')?'limited':'';?> form-control <?=$class;?>" <?=($chars_limit!='')?('data-limit="'.$chars_limit.'"'):'';?>><?=set_value($name.'', $value);?></textarea>
			<? if ($chars_limit): ?>
				<? $longitud = strlen(utf8_decode(html_entity_decode(strip_tags($value), ENT_COMPAT, 'utf-8'))); ?>
			<span class="countinfo"><span><?=$chars_limit - $longitud;?></span> caracteres disponibles</span>
			<? endif; ?>
		</div>
	</div>
</div>
<? endif; ?>