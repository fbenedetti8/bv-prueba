<div class="form-group">
	<label class="col-md-3 control-label"><?php echo $label;?></label>
	<div class="col-md-8">
		<div class="input-group">
			<? $i=0; foreach ($languages as $lang): ?>
			<textarea class="form-control custom-control <?=$i==0 ? '' : 'hide';?> <?=$name;?>" name="<?=$name;?>_<?=$lang['lang'];?>" id="<?=$name;?>_<?=$lang['lang'];?>" rows="5" style="resize:none"></textarea> 
			<? $i+=1; endforeach; ?>
			<div class="input-group-btn">
				<div class="btn-group-vertical">
					<? $i=0; foreach ($languages as $lang): ?>
			    	<button class="btn <?=$i==0 ? 'btn-primary' : 'btn-default';?> <?=$name;?> lang" id="<?=$lang['lang'];?>" type="button" style="width:40px; padding:5px; border-radius:0;">
						<img src="<?=base_url();?>media/admin/assets/img/<?=$lang['lang'];?>.png" />
			    	</button>
		        	<? $i+=1; endforeach; ?>
				</div>
			</div>
		</div>	
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.<?php echo $name;?>.lang').click(function(e){
		e.preventDefault();
		lang = this.id;

		$('.<?=$name;?>.lang').removeClass('btn-primary').addClass('btn-default');
		$(this).addClass('btn-primary');

		$('textarea.<?=$name;?>').addClass('hide');
		$('textarea#<?=$name;?>_' + lang).removeClass('hide').focus();
	});	
});
</script>
