<? 
$style = "";
if (is_array($attributes)):
	foreach ($attributes as $attkey=>$attvalue)
		$style = "$attkey:$attvalue; ";
endif;
?>

<div class="field">
	
	<label for="<?php echo $name;?>"><?php echo $label;?></label> 
	<input id="<?php echo $name;?>" name="<?php echo $name;?>" size="50" style="float:left; <?=$style;?>" type="text" class="medium <?php echo $class;?>" value="<?php echo utf8_encode($value);?>" />	
	<label style="width:60px"><?php echo $label2;?></label> 
	<input id="<?php echo $name2;?>" name="<?php echo $name2;?>" style="float:left;width:50px" type="text" class="medium <?php echo $class;?>" value="<?php echo utf8_encode($value2);?>" />
	<label style="width:60px"><?php echo $label3;?></label>
	<input id="<?php echo $name3;?>" name="<?php echo $name3;?>" style="float:left;width:50px" type="text" class="medium <?php echo $class;?>" value="<?php echo utf8_encode($value3);?>" />
	<label style="width:60px"><?php echo $label4;?></label>
	<input id="<?php echo $name4;?>" name="<?php echo $name4;?>" style="float:left;width:50px" type="checkbox" <?php if($value4==1)echo 'checked';?> class="medium" value="<?php echo ($value4);?>" />
	
	<br/>
	<span style="margin:0 0 0 260px; display:none; float:left; text-align:right; color:#f00;" id="error_<?php echo $name;?>">Campo requerido</span>	
	<span style="margin:0 0 0 250px; display:none; float:left; text-align:right; color:#f00;" id="error_<?php echo $name2;?>">Campo requerido</span>
	<span style="margin:0 0 0 70px;  display:none; float:left; text-align:right; color:#f00;" id="error_<?php echo $name3;?>">Campo requerido</span>
	<span style="margin:0 0 0 70px;  display:none; float:left; text-align:right; color:#f00;" id="error_<?php echo $name4;?>">Campo requerido</span>
</div>
