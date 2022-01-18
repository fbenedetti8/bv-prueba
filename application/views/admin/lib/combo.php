<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label.': ';?> <? if($is_required){ ?><span class="required">*</span><? } ?></label>
	<div class="col-md-9 clearfix">
		<? if($is_multiple){ ?>
			<select id="<?php echo $name;?>" name="<?php echo $name;?>" class="col-md-12 form-control <?php echo $class;?> <?=($is_required)?'required':'';?> <?=($readonly)?'readonly':'';?> <?=($is_multiple)?'multiselect':'';?>" multiple="multiple">
		<? } else { ?>
			<select id="<?php echo $name;?>" name="<?php echo $name;?>" class="col-md-12 form-control select2 full-width-fix <?php echo $class;?> <?=($is_required)?'required':'';?>" <?=($readonly)?'readonly':'';?>>
		<? } ?>
		
			<option></option>
			<?php
			if (is_array($data)) {
				foreach ($data as $item)
					if (is_object($item)) {
						echo "<option value='" . $item->{$valueField} . "' " . ($item->{$valueField}==$value?"selected":"") . set_select($name,$item->{$valueField},false) . (isset($item->tipo)?("data-tipo='".$item->tipo."'"):"") . ">" . $item->{$captionField} . "</option>";
					}
					else {
						echo "<option value='" . $item[$valueField] . "' " . ($item[$valueField]==$value?"selected":"") . set_select($name,$item[$valueField],false) . (isset($item['tipo'])?("data-tipo='".$item['tipo']."'"):"") . ">" . $item[$captionField] . "</option>";
					}
			} else {
				foreach ($data->result() as $row)
					echo "<option value='" . $row->{$valueField} . "' " . ($row->{$valueField}==$value?"selected":"") . set_select($name,$row->{$valueField},false) . (isset($row->tipo)?("data-tipo='".$row->tipo."'"):"") . ">" . $row->{$captionField} . "</option>";
			} 
			?>
		</select>
		<label for="<?php echo $name;?>" generated="true" class="has-error help-block" style="display:none;"></label>
	</div>
</div>