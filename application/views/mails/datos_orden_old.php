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
      
      <table style="padding-top: 10px; border-spacing: 0; color: #333333;" align="center">
        <tr>
          <td style="padding: 0; text-align: center; font-size: 0;">

            <div style="padding: 0 20px; background-color: white">

              <div>
                <div style="width: 50%; display: inline-block; vertical-align: middle; text-align: left">
                  <a href="<?=site_url();?>" style="display: inline-block">
                    <img style="max-width: 100%; max-height: 50px" src="<?=base_url();?>media/assets/mails/logo.png" alt="Buenas Vibras viajes" />
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

                <div style="margin-top: 10px; height: 6px; background-color: #00efd6; width: 100%"></div>
              </div>


              <div>
                <h1 style="color: #001862; font-family: 'arial'; font-size: 28px; margin-bottom: 5px">Te invitamos completar tu reserva</h1>

                <p style="font-size: 14px; line-height: 22px; width: 100%; max-width: 500px; margin: 10px auto; display: block">Tu reserva aún no ha sido generada. <strong>Para continuar con el proceso de reserva sólo debes completar algunos datos más. Apurate! Los cupos son limitados.</strong></p>

                <a href="<?=site_url('checkout/orden/'.encriptar($orden->code));?>" style="box-sizing: border-box; color: #001862; background-color: #f7f7f7; font-size: 16px; font-weight: 900; text-align: center; padding: 10px 20px; border: 1px solid #6f6f6f; text-decoration: none; display: block; width: 100%; max-width: 255px; margin: 18px auto; text-transform: uppercase">Completar mi reserva</a>
              </div>

              <div>
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Destino:</p>
                </div>

                <? //defino la fecha a mostrar
                if($combinacion->fecha_indefinida && $combinacion->mostrar_calendario): ?>
                  <? $fecha_elegida = fecha_completa(formato_fecha($orden->fecha));?>
                <? else: ?>
                  <? $fecha_elegida = fecha_completa(formato_fecha($combinacion->fecha_checkin),formato_fecha($combinacion->fecha_checkout));?> 
                <? endif; ?>  
                
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px; text-align: left; line-height: 23px; margin: 5px 0; color: #001862"><?=$combinacion->destino;?> <span style="display: block"><?=$fecha_elegida;?>.</span></p>
                </div>

                
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Salida:</p>
                </div>
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=fecha_completa(formato_fecha($combinacion->fecha_inicio));?> (<?=$orden->hora;?>)</p>
                </div>
              </div>


              <div style="width: 100%; display: block; background-color: #7f7f7f; height: 1px; margin-top: 15px; margin-bottom: 15px"></div>


              <div>
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Pasajero responsable:</p>
                </div>
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px;  text-align: left; margin: 5px 0; color: #001862"><?=$responsable->apellido.', '.$responsable->nombre?></p>
                </div>


                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">DNI:</p>
                </div>
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <p style="font-size: 12px; text-align: left; margin: 5px 0; color: #001862"><?=$responsable->dni;?></p>
                </div>
              </div>

            </div>
            
            <?=$mail_footer;?>

          </td>
        </tr>
      </table>
      
    </div>
  </center>
</body>
</html>