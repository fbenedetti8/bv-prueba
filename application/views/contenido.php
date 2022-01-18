
				<div class="item_viaje <?=$row->grupal?'grupal':'paquete';?> <?=$clases;?>">
					
					<a href="<?=site_url((@$row->categoria_slug?$row->categoria_slug.'/':'').$row->slug);?>">
						<? if($es_destino): ?>
						<div class="caratula" style="background-image: url('<?=base_url();?>uploads/destinos/<?=$row->id;?>/<?=$row->imagen_mobile;?>')">
						</div>
						<? else: ?>
						<div class="caratula" style="background-image: url('<?=base_url();?>uploads/categorias/<?=$row->id;?>/<?=$row->imagen_mobile;?>')">
						</div>
						<? endif; ?>

						<? if($mostrar_tipo): ?>
						<div class="item_tag">
							<span class="<?=$row->grupal?'icon-group':'icon-suitcase';?>"></span>
							<span><?=$row->grupal?'Grupal':'Paquete';?></span>

							<div class="btn_tooltip">
								<span class="icon-question-mark"></span>

								<div class="tooltip">
									<div></div>
									<? if($row->grupal): ?>
										<span><?=$this->config->item('viaje_grupal');?></span>
									<? else: ?>
										<span><?=$this->config->item('viaje_no_grupal');?></span>
									<? endif; ?>
								</div>
							</div>
						</div>
						<? endif; ?>
						
						<div class="contenido">
							<div class="detalle">
								<p><?=$row->nombre;?></p>
								<h3><?=strlen($row->subtitulo)>95?(substr($row->subtitulo,0,95).'...'):$row->subtitulo;?></h3>
							</div>

							<div class="precio">
								<? if($row->disponibles>0 && isset($row->precio) && $row->precio): ?>
								<div>
									<span>Por persona desde</span>
									<p><?=strip_tags(precio($row->precio,$row->precio_usd),'<sup><strong>');?> <span>+ <?=precio_impuestos_clean($row->impuestos,false,false);?> imp.</span></p>
								</div>
								<? else: ?>
								<div><p>Próximamente</p></div>
								<? endif; ?>
								
								<span class="icono icon-right_2"></span>
							</div>
						</div>
					</a>
				</div>