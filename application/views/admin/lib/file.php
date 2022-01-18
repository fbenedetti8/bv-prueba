<?php 
$style = "";
if (is_array($attributes)):
	foreach ($attributes as $attkey=>$attvalue)
		$style .= "$attkey:$attvalue; ";
endif;
?>

<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?></label>
	<div class="col-md-9">
		<input type="file" id="<?php echo $name;?>" name="<?php echo $name;?>" class="<?php echo $class;?>" accept="<?php echo $accept;?>" data-style="fileinput" data-inputsize="medium" value="<?php echo $value?>" style="<?php echo $style;?>">
		<!--<p class="help-block">Images only (image/*)</p>-->
		
		<?php if ($value != '' and file_exists('.'.$folder . $value)) {?>
			<p id="options_<?php echo $name;?>" class="help-block">
				<?if($type == 'view'):?>
					<a href="<?php echo site_url($folder . $value);?>" class="fancybox">Abrir</a> |
				<?elseif($type == 'viewvid'):?>
					<a id="<?php echo $folder . $value;?>" href="<?php echo $folder . $value;?>" class="viewvid_<?php echo $name;?>" rel="<?php echo $name;?>" target="_blank">Abrir</a> |
				<?elseif($type == 'download'):?>
					<a id="<?php echo $folder . $value;?>" href="<?php echo $folder . $value;?>" class="download_<?php echo $name;?>" rel="<?php echo $name;?>" target="_blank">Descargar</a> |
				<?endif;?> 
				<a href="#" class="delete_<?php echo $name;?>" id="<?php echo $folder . $value;?>" rel="<?php echo $name;?>">Borrar</a>
			</p>
			<input type="hidden" name="delete_<?php echo $name?>" id="delete_<?php echo $name?>" value="0" />
		<?php } ?>
		
		<!--<label for="<?php echo $name?>" class="has-error help-block" generated="true" style="display:none;"></label>-->
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.delete_<?php echo $name;?>').click(function(e){
			e.preventDefault();
			var rel = $(this).attr('rel');
			$.post('/admin/ajaxtools/deletefile', {filename:this.id}, function(result){
				if (result != '')
				alert('File does not exist');				
				$('#options_' + rel).hide();
				$('#delete_' + rel).val(1);					
			});
		});
        
       $('.download_<?php echo $name;?>').click(function(e){
			e.preventDefault();
			var rel = $(this).attr('rel');
            window.location.href = this.id;
		});
		
		$('.fancybox').fancybox();
	});
</script>