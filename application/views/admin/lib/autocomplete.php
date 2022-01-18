<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?>:</label>
	<div class="col-md-9">
		<input type="text" name="<?=$name;?>" class="form-control" id="<?=$name;?>" <?=($is_required)?'required':'';?> value="<?=$value;?>">
		<span class="help-block">Este es un campo autocompletable</span>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#<?=$name;?>').typeahead({
		name: '<?=$name;?>',
		<? if(isset($matches) && $matches){ ?>
			local: <?=$matches;?>
		<? } else { ?>
			remote: '<?=$url;?>/%QUERY'
		<? } ?>
	});
});
</script>