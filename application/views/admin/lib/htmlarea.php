<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?></label>
	<div class="col-md-9">
		<div class="input-group" style="width:100%;">
			<span class="input-group-addon" style="width:30px;"><img src="<?=base_url();?>media/admin/assets/img/es.png" /></span>
			<? 
			if (isset($value->{$name.'_es'})) {
				$val = $value->{$name.'_es'};
			}
			else {
				$val = '';
			}
			?>
			<textarea rows="5" id="<?php echo $name;?>_es" name="<?php echo $name;?>_es" class="form-control wysiwyg"><?=set_value($name.'_es', $val);?></textarea>
		</div>
		<div class="input-group" style="width:100%;">
			<span class="input-group-addon" style="width:30px;"><img src="<?=base_url();?>media/admin/assets/img/en.png" /></span>
			<? 
			if (isset($value->{$name.'_en'})) {
				$val = $value->{$name.'_en'};
			}
			else {
				$val = '';
			}
			?>
			<textarea rows="5" id="<?php echo $name;?>_en" name="<?php echo $name;?>_en" class="form-control wysiwyg"><?=set_value($name.'_en', $val);?></textarea>
		</div>
		<div class="input-group" style="width:100%;">
			<span class="input-group-addon" style="width:30px;"><img src="<?=base_url();?>media/admin/assets/img/fr.png" /></span>
			<? 
			if (isset($value->{$name.'_fr'})) {
				$val = $value->{$name.'_fr'};
			}
			else {
				$val = '';
			}
			?>
			<textarea rows="5" id="<?php echo $name;?>_fr" name="<?php echo $name;?>_fr" class="form-control wysiwyg"><?=set_value($name.'_fr', $val);?></textarea>
		</div>
	</div>
</div>
 
<script type="text/javascript">
$(document).ready(function(){

	tinymce.init({
		plugins: "link",
		language: "es",
		selector:'#<?php echo $name;?>_fr', 
		menubar:false, 
		statusbar:false,
		toolbar:"undo redo | bold italic underline | link unlink"
	});

	tinymce.init({
		plugins: "link",
		language: "es",
		selector:'#<?php echo $name;?>_en', 
		menubar:false, 
		statusbar:false,
		toolbar:"undo redo | bold italic underline | link unlink"
	});

	tinymce.init({
		plugins: "link",
		language: "es",
		selector:'#<?php echo $name;?>_es', 
		menubar:false, 
		statusbar:false,
		toolbar:"undo redo | bold italic underline | link unlink"
	});

});
</script>

<!--
controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
				  'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
				  'centeralign','rightalign','blockjustify','|','|','n',
				  'font','size','style','|','image','hr','link','unlink','|','cut','copy','paste'],
-->