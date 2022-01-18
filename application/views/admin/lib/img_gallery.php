<li>
	<img alt="" src="<?=$folder . $value;?>" style="width:100px;"/>
	
	<div class="actions">	
		<a  class="btn btn-orange btn-small" rel="facebox" href="<?=$folder . $value;?>">Ver</a>
		<a href="#" class="btn btn-grey btn-small del_<?=$name;?>" id="<?=$folder . $value;?>" rel="<?=$name;?>">Borrar</a>
		<input type="hidden" id="foto_<?php echo $id;?>" value="<?php echo $id;?>"/>
	</div>
</li>

<script type="text/javascript">
	$(document).ready(function(){
		$('.del_<?=$name;?>').click(function(e){
			e.preventDefault();
			
			var me = this;
			jConfirm('Está seguro?', 'Confirmacion de borrado', function(r) {
				var msg ="";
				if(r == true)
					msg = "OK";
				else
					msg = "NO";
				jAlert('Borrado: ' + msg, 'Resultado de la confirmacion');
				if(msg == "OK"){
					

					var rel = me.rel;
					$.post('/admin/ajaxtools/deletefile', {filename:me.id}, function(result){
						if (result != '')
							alert('File does not exist');						
					});
					
					var route = "<?php echo $this->uri->segment(2);?>";	
					var id = $('#foto_<?php echo $id;?>').val();
					
					$.post('/admin/'+route+'/deletePhoto', {id:id}, function(result){
						if (result != '')
							alert('Can not delete file. Try again later.');
					});
						
					//location.href = location.href;
					window.location = window.location;
				}
				else
					return false;
			});
			
		});
		
	});
</script>