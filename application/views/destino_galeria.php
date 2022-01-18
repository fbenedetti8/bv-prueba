<? if(count($fotos)>0): ?>
		  <article class="section-galeria">
			<h2>GALERÍA</h2>
			<div class="ver-mas ver-mas--galeria">
			  <a class="ver-mas__btn"
				   href="">Ver más</a>
			</div>
			<div class="galeria clearfix relative">
			  <i class="galeria__fs fas fa-expand"></i>
			  <div class="galeria__featured owl-carousel owl-theme" galeria-owl>
			  	
			  	<? $n=0; 
			  	foreach($fotos as $f): 
					if($f->foto != '' && file_exists('./uploads/destinos/'.$f->destino_id.'/'.$f->foto)): 
						$n++; 
						if($n<=4): ?>
						<div style="background-image: url('<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>')"></div>
					<? endif; 
					endif; 
				endforeach; ?>

			  </div>
			  <div class="galeria__nav">
				<? $n=0; 
				foreach($fotos as $f): 
					if($f->foto != '' && file_exists('./uploads/destinos/'.$f->destino_id.'/'.$f->foto)): 
						$n++; 
						if($n<=4): ?>
						<div style="background-image: url('<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>')" galeria-owl-bullet></div>
					<? endif; 
					endif; 
				endforeach; ?>

				<!-- esta es la primera-->
				<? $n=0; 
				foreach($fotos as $f): 
					if($f->foto != '' && file_exists('./uploads/destinos/'.$f->destino_id.'/'.$f->foto)): 
						$n++; 
						if($n==1): ?>
							<div><a data-fancybox="cl-group" data-thumb="<?=base_url();?>uploads/destinos/<?=$f->destino_id.'/'.$f->foto;?>" href="<?=base_url();?>uploads/destinos/<?=$f->destino_id.'/'.$f->foto;?>" class="galeria__ver-mas">Ver más</a></div>
						<? break;
						endif; 
					endif; 
				endforeach; ?>
			  </div>
			  
			  <div class="galeria__hidden" >
			  	<? $n=0; 
				foreach($fotos as $f): 
					if($f->foto != '' && file_exists('./uploads/destinos/'.$f->destino_id.'/'.$f->foto)): 
						$n++; 
						if($n>1):?>
						<a data-fancybox="cl-group" data-thumb="<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>" href="<?=base_url();?>uploads/destinos/<?=$f->destino_id;?>/<?=$f->foto;?>"></a>
					<? endif;
					endif; 
				endforeach; ?>
			  </div>

			</div>
		  </article>
		<? endif; ?>