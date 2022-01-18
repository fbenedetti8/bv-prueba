<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title><?php echo $title;?></title>

	<!--=== CSS ===-->

	<!-- Bootstrap -->
	<link href="<?php echo base_url();?>media/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!-- Theme -->
	<link href="<?php echo base_url();?>media/admin/assets/css/main.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>media/admin/assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>media/admin/assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url();?>media/admin/assets/css/icons.css" rel="stylesheet" type="text/css" />

	<!-- Login -->
	<link href="<?php echo base_url();?>media/admin/assets/css/login.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="<?php echo base_url();?>media/admin/assets/css/fontawesome/font-awesome.min.css">
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo base_url();?>media/admin/assets/css/fontawesome/font-awesome-ie7.min.css">
	<![endif]-->

	<!--[if IE 8]>
		<link href="<?php echo base_url();?>media/admin/assets/css/ie8.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

	<!--=== JavaScript ===-->

	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/libs/jquery-1.10.2.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>media/admin/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/libs/lodash.compat.min.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script src="<?php echo base_url();?>admin/assets/js/libs/html5shiv.js"></script>
	<![endif]-->

	<!-- Beautiful Checkboxes -->
	<script type="text/javascript" src="<?php echo base_url();?>media/admin/plugins/uniform/jquery.uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>media/admin/assets/js/login.js"></script>
</head>

<body class="login">
	<? if (isset($_GET['recovered']) && $_GET['recovered'] == 1): ?>
	<div class="alert alert-success fade in"> 
		<i class="icon-remove close" data-dismiss="alert"></i> 
		Se ha generado y enviado una nueva contraseña a tu dirección de e-mail.
	</div>
	<? elseif (isset($_GET['recovered'])): ?>
	<div class="alert alert-danger fade in"> 
		<i class="icon-remove close" data-dismiss="alert"></i> 
		La dirección de e-mail ingresada no existe.
	</div>
	<? endif; ?>

	<!-- Logo -->
	<br/><br/>
	<div class="logo">
		<img src="<?=base_url();?>media/assets/images/logo/logo.png" style="width:115px" />
	</div>
	<!-- /Logo -->

	<!-- Login Box -->
	<div class="box">
		<h2 style="margin-bottom:0;"><center>Panel de Administración</center></h2>

		<div class="content">
			<!-- Login Formular -->
			<form id="form_login" class="form-vertical login-form" action="<?php echo site_url('admin/login/validate');?>" method="post">
				<br/>
				<!-- Error Message -->
				<?php if (isset($error)): ?>
				<div class="alert fade in alert-danger">
					<i class="icon-remove close" data-dismiss="alert"></i>
					<?php echo $error;?>
				</div>
				<?php endif; ?>

				<!-- Input Fields -->
				<div class="form-group">
					<!--<label for="username">Username:</label>-->
					<div class="input-icon">
						<i class="icon-user"></i>
						<input type="text" id="username" name="username" class="form-control" placeholder="Usuario" autofocus="autofocus" data-rule-required="true" data-msg-required="Por favor ingrese su usuario." />
					</div>
				</div>
				<div class="form-group">
					<!--<label for="password">Password:</label>-->
					<div class="input-icon">
						<i class="icon-lock"></i>
						<input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" data-rule-required="true" data-msg-required="Por favor ingrese su contraseña." />
					</div>
				</div>
				<!-- /Input Fields -->

				<!-- Form Actions -->
				<div class="form-actions" style="border-bottom:0;">
					<label class="checkbox pull-left"><input type="checkbox" class="uniform" id="remember" name="remember"> Recordarme</label>
					<button type="submit" class="submit btn btn-primary pull-right ladda-button" data-style="slide-left">
						<span class="ladda-label">
							Ingresar <i class="icon-angle-right"></i>
						</span>
					</button>
				</div>
			</form>
			<!-- /Login Formular -->
			
		</div> <!-- /.content -->

		<!-- Forgot Password Form -->
		<div class="inner-box">
			<div class="content">
				<!-- Close Button -->
				<i class="icon-remove close hide-default"></i>

				<!-- Link as Toggle Button -->
				<a href="#" class="forgot-password-link">&iquest;Olvidó su Contraseña?</a>

				<!-- Forgot Password Formular -->
				<form class="form-vertical forgot-password-form hide-default" id="fForgot" action="<?=site_url('admin/login/recover');?>" method="post">
					<!-- Input Fields -->
					<div class="form-group">
						<!--<label for="email">Email:</label>-->
						<div class="input-icon">
							<i class="icon-envelope"></i>
							<input type="text" name="email" class="form-control" placeholder="Ingrese su dirección de e-mail" data-rule-required="true" data-rule-email="true" data-msg-required="Por favor ingrese su e-mail." />
						</div>
					</div>
					<!-- /Input Fields -->

					<button type="submit" class="submit btn btn-default btn-block ladda-button" data-style="slide-left">
						<span class="ladda-label">
							Generar una nueva contraseña
						</span>
					</button>
				</form>
				<!-- /Forgot Password Formular -->

			</div> <!-- /.content -->
		</div>
		<!-- /Forgot Password Form -->
	</div>
	<!-- /Login Box -->

	<script>
	$(document).ready(function(){
		"use strict";

		var baseURL = '<?=base_url();?>';
		Login.init();

		Ladda.bind( 'button[type=submit]' );
	});
	</script>
</body>
</html>