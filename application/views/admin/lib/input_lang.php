<div class="field">
	<label style="padding-top:20px"><?php echo $label;?>:</label>
    <div class="fieldcontainer">
    	<div class="tabs">
    		<ul class="mc_menu clearfix" style="height: 15px;">
    			<?php 
    			$i = 0;
    			foreach ($languages as $lang) { 
    				$on = ($i == 0) ? "on" : "";
    				?>
    			<li><a href="#" id="<?php echo $lang['lang'];?>" class="<?php echo $name;?> lang <?php echo $on;?>"><?php echo $lang['lang'];?></a> <?=($i!=count($languages)-1)?'&nbsp;&nbsp;|&nbsp;':'';?> </li> 
    			<?php 
    				$i += 1;
    			} 
    			?>
    		</ul> 
    		<span class="input_wrapper" style="width:600px; height:17px;">
    			<?php 
    			$i = 0;
    			$vis = "";
    			foreach ($languages as $lang) { 
    				if ($i != 0) $vis = "display:none"; ?>
    				<input type="text" class="text <?php echo $class;?>" name="<?php echo $name;?>_<?php echo $lang['lang'];?>" id="<?php echo $name;?>_<?php echo $lang['lang'];?>" style="<?php echo $vis;?>; width: 348px;" value="<?=($lang['value']);?>" />
    			<?php 
    				$i += 1;
    			} 
    			?>
    		</span>
    	</div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.<?php echo $name;?>.lang').click(function(e){
		e.preventDefault();
		lang = this.id;
		$('.<?php echo $name;?>.lang').each(function(){
			$(this).removeClass('on');
			$('#<?php echo $name;?>_' + this.id).hide();
		});
		$(this).addClass('on');
		$('#<?php echo $name;?>_' + this.id).show();
	});	
});
</script>