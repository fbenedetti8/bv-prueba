
	<form id="" method="post" action="<?=$download_link;?>">
	<div class="col-md-12 download_fields">
		<div class="row">
			<div class="col-md-12">
				<h2>Selecciona el/los estados de las reservas</h2>
			
				<div class="row estados_reserva">
					<div class="col-md-12 field" style="margin-left: 0; ">
						<div class="col-md-3 field" style="padding:0; margin-left: 0; margin-bottom:0;">
							<div class="inputs" style="width:20px; float:left;">
								<input class="check_all_estados" type="checkbox" value="todos"/>
							</div>
							<label style=" float:left;">Todos</label>
						</div>

						<? foreach ($estados as $e) : ?>
						<div class="col-md-3 field" style="padding:0; margin-left: 0; margin-bottom:0;">
							<div class="inputs" style="width:20px; float:left;">
								<input class="check_estado" type="checkbox" name="estados[]"  value="<?=$e->id;?>"/>
							</div>
							<label style="float:left;"><?=$e->nombre;?></label>
						</div>	
						<? endforeach; ?>
					</div>	
				</div>	
			</div>
			
			<div class="col-md-12">
				<h2>Selecciona los campos para descargar</h2>
			
				<div class="row">
					<div class="col-md-12 field" style="margin-left: 0; ">
						<div class="inputs" style="width:20px; float:left;">
							<input class="check_all" type="checkbox" value="todos"/>
						</div>
						<label style="width:210px; float:left;">Seleccionar Todos</label>
					</div>	
				</div>	
			</div>
			
			<div class="col-md-12">
				<? foreach($fields as $t=>$f){ ?>
					<div class="row">
						<div class="col-md-12">
							<p>
							<strong><?=$t;?></strong>
							<input class="check_all" type="checkbox" name="" id="" value="<?=str_replace(" ","-",$t);?>" style="vertical-align: middle;"/>					
							<br><br>				
						</p>
						</div>
					</div>

					<div class="row" id="<?=str_replace(" ","-",$t);?>">
					<? foreach($f as $k=>$v){ ?>
						<div class="col-md-3 field" style="margin-left: 0; margin-bottom:0;">
							<div class="inputs" style="width:20px; float:left;">
								<input type="checkbox" name="<?=$t.'-'.substr($k,0,-1);?>" id="<?=$t.'-'.substr($k,0,-1);?>" value="<?=$v;?>" class="<?=(strpos($k,'*'))?'req_f':'';?>" <?=(strpos($k,'*'))?'checked readonly onclick="javascript: return false;"':'';?>/>
							</div>
							<label for="<?=$t.'-'.substr($k,0,-1);?>" style="width:110px; float:left;"><?=$k;?></label>
						</div>
					<? } ?>
					</div>
					
					<div class="clearfix"></div>
					<hr style="margin: 10px 0;"/>
					<div class="clearfix"></div>
				<? } ?>
				
				<input type="submit" class="btn btn-primary" value="Exportar"/>
				
				<? if(isset($referer) && $referer == 'caja'){ ?>
					<input type="hidden" name="exportar" id="exportar" value="1" />
				<? } ?>
			</div>
		</div>
	</div>
	</form>
	
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('body').on('click','.check_all',function(e){
				//e.preventDefault();
				var val = $(this).val();
				var checked = $(this).is(':checked');				
				
				if(val == 'todos'){
					$('.download_fields input').each(function(i,el){
						if(checked){
							el.checked = true;
						}
						else{
							if(!$(el).hasClass('req_f'))
								el.checked = false;
						}
					});
				}
				else {
					$('.download_fields #'+val+' input').each(function(i,el){
						if(checked){
							el.checked = true;
						}
						else{
							if(!$(el).hasClass('req_f'))
								el.checked = false;
						}
					});
				}
			});
			
			
			$('body').on('click','.check_all_estados',function(e){
				//e.preventDefault();
				var val = $(this).val();
				var checked = $(this).is(':checked');				
				
				if(val == 'todos'){
					$('.estados_reserva input').each(function(i,el){
						if(checked){
							el.checked = true;
						}
						else{
							if(!$(el).hasClass('req_f'))
								el.checked = false;
						}
					});
				}
				else {
					$('.estados_reserva #'+val+' input').each(function(i,el){
						if(checked){
							el.checked = true;
						}
						else{
							if(!$(el).hasClass('req_f'))
								el.checked = false;
						}
					});
				}
			});
		});
	</script>