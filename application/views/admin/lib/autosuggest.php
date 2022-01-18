<div class="field">
	<label><?php echo $label;?>:</label>
	<div class="ui-widget">
		<input type="text" class="<?php echo $class;?>" name="<?php echo $name;?>" id="<?php echo $name;?>" />
		<span class="system negative" id="error_<?php echo $name;?>"><?php echo $this->lang->line('Please complete');?></span> 
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

	<?php if (is_array($value)) { ?>
	var data = {items: [
	<?php foreach ($value as $v) { ?>
	{value: "<?php echo $v['id'];?>", name: "<?php echo $v['nombre'];?>"},
	<?php } ?>
	]};
	<?php } ?>
	$("#<?php echo $name;?>").autoSuggest('<?php echo $url;?>', {minChars:2, matchCase:false, 
																asHtmlID:'<?php echo $name;?>', startText:'', emptyText:'No hay resultados', 
																selectedItemProp:"name", 
																searchObjProps:"name"<?php if ($value!='') { ?>, preFill:<?php echo $value;?> <?php } ?>
																});
	
	
});

</script>