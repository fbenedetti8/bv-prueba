<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <title></title>

  <style>
    html,
    body {
      margin: 0;
      padding: 0
    }
  </style>

</head>
<body style="background: #f5f5f5;">

  <center style="background: #f5f5f5; padding: 30px 0">
    <div style="background: white; border-radius: 13px; overflow: hidden; max-width: 580px; margin: auto">

      <table style="font-family: 'arial'; background: white; border: none; border-collapse: collapse" border="0">
        <tr style="text-align: center">
          <td style="padding: 20px">
            <a href="#" style="font-family: 'arial'; display: inline-block; margin-bottom: 30px">
              <img style="width: 100%; max-width: 100%" src="<?=base_url();?>media/assets/mail-reserva/buenas_vibras.png" alt="Buenas Vibras" />
            </a>

            <p style="font-family: 'arial'; font-size: 40px; color: #282b5a; margin: 0"><strong style="font-family: 'arial'; font-size: 40px; color: #282b5a">¡Gracias por contactarnos!</strong></p>

            <p style="font-family: 'arial'; color: #282b5a; font-size: 20px; margin: 15px 0 0">
              Recibimos tus datos. Nos pondremos en contacto a la brevedad.
            </p>

            <div>
                <div style="width: 100%; max-width: 280px; display: inline-block; vertical-align: top">
                  <? if (isset($nombre)): ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Nombre: <span style="font-weight:normal;"><?=$nombre;?></span></p>
                  <? endif; ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">E-Mail: <span style="font-weight:normal;"><?=$email;?></span></p>
                  <? if (isset($pais)): ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">País: <span style="font-weight:normal;"><?=$pais;?></span></p>
                  <? endif; ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Asunto: <span style="font-weight:normal;"><?=$asunto;?></span></p>
                  <? if (isset($telefono)): ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Teléfono: <span style="font-weight:normal;"><?=$telefono;?></span></p>
                  <? endif; ?>
                  <? if(isset($sucursal) && isset($sucursal->nombre)): ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Oficina: <span style="font-weight:normal;"><?=$sucursal->nombre;?></span></p>
                  <? endif; ?>
                  <? if(isset($consulta) && isset($consulta)): ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Consulta: <span style="font-weight:normal;"><?=$consulta;?></span></p>
                  <? endif; ?>
                  <? if(isset($comentario) && isset($comentario)): ?>
                  <p style="font-size: 12px; font-weight: bold; color: #999999; text-align: left; margin: 5px 0">Comentario: <span style="font-weight:normal;"><?=$comentario;?></span></p>
                  <? endif; ?>
                </div>
              </div>
              
          </td>
        </tr>
            
      <?=$mail_footer;?>