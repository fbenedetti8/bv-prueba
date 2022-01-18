<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
		<!--[if (gte mso 9)|(IE)]>
			<style type="text/css">
				table {border-collapse: collapse;}
			</style>
		<![endif]-->
</head>
<body style="margin: 0; padding: 0; min-width: 100%; background-color: #ffffff; font-family: 'arial'">
	<center style="width: 100%; table-layout: fixed; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
		<div style="max-width: 600px">
			
			<table style="width:600px;border-spacing: 0; color: #333333;" align="center">
				<tr>
					<td style="padding: 0; text-align: center; font-size: initial;">

						<div style="padding: 0 20px; background-color: white">

							<div>
								<div style="width: 49%; display: inline-block; vertical-align: middle; text-align: left">
									<a href="<?=site_url();?>" style="display: inline-block">
										<img style="max-width: 100%" src="<?=base_url();?>media/assets/mails/logo.png" alt="Buenas Vibras viajes" />
									</a>
								</div>

								<div style="width: 50%; display: inline-block; text-align: right; vertical-align: middle">
									<a href="http://www.facebook.com/buenas.vibras">
										<img src="<?=base_url();?>media/assets/mails/facebook.png" alt="Facebook" />
									</a>
									<a href="https://www.instagram.com/buenasvibrasviajes" style="margin-left: 20px">
										<img src="<?=base_url();?>media/assets/mails/instagram.png" alt="Instagram" />
									</a>
								</div>

								<div style="margin-top: 10px">
									<img style="width: 100%" src="<?=base_url();?>media/assets/mails/border.png" alt="---" />
								</div>
							</div>

							<div style="font-size: initial;text-align: initial;margin-top:20px;">
								<h1 style="text-align: center;color: #00529b; font-family: 'arial'; font-size: 28px; margin-bottom: 5px"><?=@$title;?></h1>
								
								<div style="font-size: initial;text-align: left;font-weight: initial;">
									<?=$contenido;?>
								</div>
							</div>

						</div>



					</td>
				</tr>
			</table>

			<table style="width:600px;border-spacing: 0; color: #333333;" align="center">
				<tr>
					<td style="padding: 0; text-align: center; font-size: initial;">

						<div style="padding: 0 20px; background-color: white; width:100%;">
							<?=$mail_footer;?>
						</div>
					</td>
				</tr>
			</table>
			
		</div>
	</center>
</body>
</html>