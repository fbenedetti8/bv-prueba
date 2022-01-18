		<div class="menu">
			
			<? if(esVendedor() || $this->session->userdata('perfil') == 'VEN'): ?>
				<div class="row vendedor-logged"><div class="col-md-12 etiqueta"><i class="icon-person"></i><span><strong>Hola <?=admin_username();?>!</strong> Estás logueado como vendedor, las operaciones quedarán registradas a tu nombre.</span></div></div>
			<? endif; ?>
		
			<div class="container">

				<a class="btn_menu hidden-md hidden-lg" href="javascript:void(0)">
					<span class="sr-only">Menú</span>
					<span class="icon-menu"></span>
				</a>

				<a class="btn_tel_mobile hidden-md hidden-lg" href="javascript:show_popup('contacto')">
					<span class="sr-only">Llámanos</span>
					<span class="icon-phone"></span>
				</a>

				<a class="btn_close hide hidden-md hidden-lg" href="javascript:void(0)">
					<span class="sr-only">Cerrar</span>
					<span class="icon-close"></span>
				</a>

				<div class="logo">
					<a href="<?=base_url();?>">
						<img class="img-responsive" src="<?=base_url();?>media/assets/imgs/iconos/buenas_vibras.svg" alt="Buenas Vibras Viajes" />
					</a>
				</div>

				<ul class="menu-list">
					<li class="facebook">
						<a rel="nofollow" href="https://www.facebook.com/buenas.vibras" target="_blank">
							<span class="sr-only">Facebook</span>
							<span class="icon-facebook"></span>
						</a>
					</li>
					<li class="instagram">
						<a rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes/" target="_blank">
							<img src="<?=base_url();?>media/assets/imgs/iconos/instagram.svg" alt="Instagram" />
						</a>
					</li>
					<li>
						<a href="javascript:void(0)" class="btn_tel">Llámanos</a>
					</li>
					<li class="link">
						<a href="<?=site_url('quienes-somos');?>">Quiénes Somos</a>
					</li>
					<li class="link">
						<a href="<?=site_url('contacto');?>">Contacto</a>
					</li>
					<li class="link">
						<a href="<?=site_url('blog');?>" target="_blank">Blog</a>
					</li>
				</ul>

			</div>
		</div>


		<div class="submenu open">
						
			<? if(esVendedor() || $this->session->userdata('perfil') == 'VEN'): ?>
				<div class="row vendedor-logged"><div class="col-md-12 etiqueta"><i class="icon-person"></i><span><strong>Hola <?=admin_username();?>!</strong> Estás logueado como vendedor, las operaciones quedarán registradas a tu nombre.</span></div></div>
			<? endif; ?>

			<div class="menu_1">
				<div class="container">

					<ul>
						<li class="logo">
							<a href="<?=base_url();?>">
								<img class="img-responsive" src="<?=base_url();?>media/assets/imgs/iconos/buenas_vibras_mobile.svg" alt="Buenas Vibras" />
							</a>
						</li>

						<li class="link grupales">
							<a href="<?=site_url('viajes-grupales');?>">
								Viajes Grupales
								<div></div>
							</a>
						</li>
						<? foreach($categorias_menu['basicas'] as $c): ?>
						<li class="<?=$c->id==@$categoria->id?'active':'';?>">
							<a href="<?=site_url($c->slug);?>">
								<?=$c->nombre;?>
								<div></div>
							</a>
							
							<? if ($c->destinos): ?>
							<ul>
								<? foreach(@$c->destinos as $d): ?>
								<li class="<?=$d->id==@$destino_activo?'active':'';?> <?=@$d->menu_lista_espera?'lista_espera':'';?>">
									<a href="<?=site_url($c->slug.'/'.$d->slug);?>"><?=$d->nombre;?> <?=@$d->menu_lista_espera?'<span class="lista_espera_tag">Próximamente</span>':'';?></a>
								</li>
								<? endforeach; ?>
							</ul>
							<? endif; ?>
						</li>
						<? endforeach; ?>
						
						<? if(isset($categorias_menu['otros']) && count($categorias_menu['otros'])): ?>
						<li class="<?=@$categoria->otros?'active':'';?>">
							<a href="javascript: void(0);">
								Otros
								<div></div>
							</a>
							
							<ul>
								<? foreach($categorias_menu['otros'] as $c): ?>
								<li class="<?=$c->id==@$categoria->id?'active':'';;?>">
									<a href="<?=site_url($c->slug);?>"><?=$c->nombre;?></a>
								</li>
								<? endforeach; ?>
							</ul>
						</li>
						<? endif; ?>
					</ul>

				</div>
			</div>

			<div class="menu_2 hidden-md hidden-lg">
				<div class="container">

					<ul>
						<li>
							<a href="<?=site_url('quienes-somos');?>">Quiénes Somos</a>
						</li>
						<li>
							<a href="<?=site_url('contacto');?>">Contacto</a>
						</li>
						<li>
							<a href="<?=site_url('viajes-solas-y-solos');?>">Solas y solos</a>
						</li>
						<li>
							<a href="<?=site_url('blog');?>" target="_blank">Blog</a>
						</li>
					</ul>
				

					<div>
						<p>SEGUINOS</p>

						<a class="facebook" rel="nofollow" href="https://www.facebook.com/buenas.vibras" target="_blank">
							<span class="sr-only">Facebook</span>
							<span class="icon-facebook"></span>
						</a>

						<a class="instagram" rel="nofollow" href="https://www.instagram.com/buenasvibrasviajes/" target="_blank">
							<img class="img-responsive" src="<?=base_url();?>media/assets/imgs/iconos/instagram.svg" alt="Instagram" />
						</a>
					</div>

				</div>
			</div>
		</div>