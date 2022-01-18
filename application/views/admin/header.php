<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title><?php echo $appName;?></title>

	<!--=== CSS ===-->

	<!-- Bootstrap -->
	<link href="<?php echo base_url();?>media/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!-- jQuery UI -->
	<link href="<?php echo base_url();?>media/admin/assets/css/spinner.css" rel="stylesheet" type="text/css" />
	
	<!--<link href="<?php echo base_url();?>media/admin/plugins/jquery-ui/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>media/admin/plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/>
	<![endif]-->

	<!-- Theme -->
	<link href="<?php echo base_url();?>media/admin/assets/css/main.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>media/admin/assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>media/admin/assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>media/admin/assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>media/admin/assets/css/plugins/pickadate.css" rel="stylesheet" type="text/css" />
	
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="<?=base_url();?>media/admin/assets/css/fontawesome/font-awesome.min.css">
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?=base_url();?>media/admin/assets/css/fontawesome/font-awesome-ie7.min.css">
	<![endif]-->
	
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

	<!--=== JavaScript ===-->

	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/libs/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>media/admin/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/libs/lodash.compat.min.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="<?php echo base_url();?>media/admin/assets/js/libs/html5shiv.js"></script>
	<![endif]-->

	<!-- Smartphone Touch Events -->
	<!--
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/event.swipe/jquery.event.move.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/event.swipe/jquery.event.swipe.js"></script>
	-->
	<!-- General -->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/libs/breakpoints.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/respond/respond.min.js"></script> <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/cookie/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/validation/bootstrapValidator.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/validation/language/es_ES.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/holder.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/jquery.form.js"></script>

	<!-- Forms -->
	<!--
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/inputlimiter/jquery.inputlimiter.min.js"></script>
	-->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/uniform/jquery.uniform.min.js"></script> <!-- Styled radio and checkboxes -->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>media/admin/plugins/duallistbox/bootstrap-duallistbox.css">
    <script src="<?php echo base_url();?>media/admin/plugins/duallistbox/jquery.bootstrap-duallistbox.js"></script>

	<!-- Pickers -->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/pickadate/picker.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/pickadate/picker.date.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/pickadate/picker.time.js"></script>
	<script type="text/javascript" src="<?=base_url();?>media/admin/plugins/daterangepicker/moment.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>media/admin/plugins/daterangepicker/daterangepicker.js"></script>	
	<script type="text/javascript" src="<?=base_url();?>media/admin/plugins/blockui/jquery.blockUI.min.js"></script>
	
	<!-- DataTables -->
	<script type="text/javascript" src="<?=base_url();?>media/admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>media/admin/plugins/datatables/DT_bootstrap.js"></script>
	
	
	
	<!-- App -->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/plugins.js"></script>
	<!--
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/plugins.form-components.js"></script>
	-->

	<!-- Add fancyBox main JS and CSS files. before main.js -->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/fancybox/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>media/admin/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/fancybox/helpers/jquery.fancybox-media.js?v=2.1.5"></script>
	
	<script src="<?=base_url();?>media/admin/assets/js/tinymce/tinymce.min.js"></script>
	
	
	
	<script>
		<? if (isset($from) && isset($to)): ?>
		var startDate = moment('<?=$from['year'].'-'.$from['month'].'-'.$from['day'];?>', 'YYYY-MM-DD');
		var endDate = moment('<?=$to['year'].'-'.$to['month'].'-'.$to['day'];?>', 'YYYY-MM-DD');
		<? else: ?>
		var startDate = moment().subtract('days', 29);
		var endDate = moment();
		<? endif; ?>
	
	$(document).ready(function(){
		App.init(); // Init layout and core plugins
		Plugins.initDT(); // inicializa datatables para form de paquete
		//Plugins.init(); // Init all plugins
		//FormComponents.init(); // Init all form-specific plugins
	});
	</script>
	
	<style>
	.btn.btn-sm { padding:2px 6px; }
	.btn.btn-sm i { font-size:16px; }
	</style>
</head>

<body class="theme-dark">

	<? if(isset($page) && $page != 'productos_fotos'){ ?>
	<!-- Header -->
	<header class="header navbar navbar-fixed-top" role="banner">
		<!-- Top Navigation Bar -->
		<div class="container">

			<!-- Only visible on smartphones, menu toggle -->
			<ul class="nav navbar-nav">
				<li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
			</ul>

			<!-- Logo -->
			<a class="navbar-brand" href="<?php echo site_url('admin');?>">
				<!--<img src="<?php echo base_url();?>admin/assets/img/logo.png" alt="logo" />-->
				<?php echo $appName;?>
			</a>
			<!-- /logo -->

			<!-- Sidebar Toggler -->
			<a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation">
				<i class="icon-reorder"></i>
			</a>
			<!-- /Sidebar Toggler -->


			<!-- Top Right Menu -->
			<ul class="nav navbar-nav navbar-right">
				<!-- User Login Dropdown -->
				<li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-male"></i>
						<span class="username"><?php echo $this->session->userdata('usuario');?></span>
						<i class="icon-caret-down small"></i>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url('admin/seguridad');?>"><i class="icon-lock"></i> Cambiar contraseña</a></li>
						<li><a href="<?php echo site_url('admin/login/logoff');?>"><i class="icon-key"></i> Cerrar sesión</a></li>
					</ul>
				</li>
				<!-- /user login dropdown -->
			</ul>
			<!-- /Top Right Menu -->
		</div>
		<!-- /top navigation bar -->
	</header> <!-- /.header -->
	<? } ?>
	
	<div id="container">
		<? if(isset($page) && $page != 'productos_fotos'){ ?>
		<div id="sidebar" class="sidebar-fixed">
			<div id="sidebar-content">

				<!--=== Navigation ===-->
				<ul id="nav">
					<?php foreach ($modules as $module): ?>
        			<li class="<?php echo strtolower($currentModule)==strtolower($module['label'])?'current':'';?>">
						<a href="<?php echo $module['url']==""?"#":site_url('admin/' . $module['url']);?>"><?php echo $module['name'];?></a>	
					
						<?php if (isset($module['sections'])): ?>
						<ul class="sub-menu">
							<?php foreach ($module['sections'] as $section): ?>
							<li><a href="<?php echo site_url('admin/' . $section['url']);?>"><?php echo $section['name'];?></a></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<!-- /Navigation -->

			</div>
			<div id="divider" class="resizeable"></div>
		</div>
		<!-- /Sidebar -->
		<? } ?>
		
		<div id="content" style="<?=(isset($page) && $page == 'productos_fotos')?'margin-left:0;':'';?>">
			<div class="container">
				