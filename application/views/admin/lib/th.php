<?php 
$style = "";
if (is_array($attributes)):
	foreach ($attributes as $key=>$value)
		$style .= "$key:$value; ";
endif;
?>
<th id="cell_<?php echo $name;?>" style="<?php echo $style;?>" <?php echo ($hide != '')?'data-hide="'.$hide.'"':'';?>><a href="#" class="<?php echo $sort?'sort':'';?>" id="<?php echo $name;?>"><?php echo $label;?></a></th>
