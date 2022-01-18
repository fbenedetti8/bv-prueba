<div class="widget box" style="overflow-x:hidden;">

	<div class="widget-header">
	  <h3 style="margin-bottom:20px;">Datos actualizados</h3>
	</div>
	<div class="widget-content">
		<div class="row"><div class="col-md-12"><?=$row->comentarios;?> el dia <?=date('d/m/Y', strtotime($row->fecha));?> a las <?=date('H:i', strtotime($row->fecha));?>hs</div></div>
	</div>
</div>

<div class="widget box" style="overflow-x:hidden;">
<? $datos = $row->data_updated;
$datos = json_decode($datos);
foreach($datos as $num=>$data_pax): ?>

	
	<div class="widget-content">
		<div class="row">	
			<div class="col-md-12"><h4 style="margin-bottom:10px; margin-top: 0;">Pasajero <?=$num;?></h4></div>
		</div>
		<div class="row">			
			<? foreach($data_pax as $k=>$v): 
				$k_aux = $k; 
				if(!in_array($k_aux,array('nacionalidad_id','pais_emision_id'))):?>
				<div class="col-md-12">
					<strong><?=ucfirst(str_replace('_',' ',$k_aux));?></strong>:&nbsp;
					<? if(in_array($k,array('fecha_nacimiento','fecha_emision','fecha_vencimiento'))):
						echo date('d/m/Y',strtotime($v));
					else:
						echo $v;
					endif; ?>
				</div>
				<? endif;
				endforeach; ?>
		</div>
		<hr style="margin:10px auto 0;"/>
	</div>

<? endforeach; ?>

</div>