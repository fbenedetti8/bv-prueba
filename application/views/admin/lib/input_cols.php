
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo $label;?>:</label>
	<div class="col-md-10">
		<div class="row">
			<? for($i=0; $i<$cols ;$i++){ ?>
			<div class="col-md-1">
				<input type="text" name="<?=$names[$i];?>" value="<?=isset($values[$i])?$values[$i]:'';?>" class="form-control <?php echo $class;?> <?=($is_required)?'required':'';?>">
			</div>
			<? } ?>
		</div>
	</div>
</div>