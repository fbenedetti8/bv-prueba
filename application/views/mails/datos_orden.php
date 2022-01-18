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

            <p style="font-family: 'arial'; font-size: 40px; color: #282b5a; margin: 0"><strong style="font-family: 'arial'; font-size: 40px; color: #282b5a">Te invitamos completar tu reserva</strong></p>

            <p style="font-family: 'arial'; color: #282b5a; font-size: 20px; margin: 15px 0 0">
              Tu reserva aún no ha sido generada. <strong>Para continuar con el proceso de reserva sólo debes completar algunos datos más. Apurate! Los cupos son limitados.</strong>
            </p>

            <div style="text-align: center">
              <a href="<?=site_url('checkout/orden/'.encriptar($orden->code));?>" target="_blank" style="font-family: 'arial'; box-sizing: border-box; background: #ff5c5d; color: white; font-size: 16px; display: inline-block; font-weight: bold; border-radius: 20px; padding: 9px 40px; text-decoration: none; margin: 20px auto">Completar mi reserva</a>
            </div>
          </td>
        </tr>

        <tr>
          <td style="padding: 0 15px">
            <div style="border-top: 1px solid #cccccc; padding: 20px 0">
             
              <p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Destino:</strong><br />
                <?=$combinacion->destino;?>
              </p>


              <p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Salida:</strong><br />
                <?=fecha_completa(formato_fecha($combinacion->fecha_inicio));?> (<?=$orden->hora;?>)
              </p>

              <p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Regreso:</strong><br />
                <?=$fecha_elegida_fin;?>
              </p>

            </div>
          </td>
        </tr>


        <tr>
          <td style="padding: 0 15px">
            <div style="border-top: 1px solid #cccccc; padding: 20px 0">
              <p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Pasajero responsable:</strong><br />
                <?=$responsable->apellido.', '.$responsable->nombre.(!$responsable->completo?' <span style="color: #e71656">Faltan datos</span>':'');?>
              </p>

              <p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">DNI:</strong><br />
                <?=$responsable->dni;?>
              </p>

              <? if(count($acompanantes)>0): ?>
              <p style="font-family: 'arial'; color: #282b5a; font-size: 20px"><strong style="font-family: 'arial'">Acompañantes (<?=count($acompanantes);?>):</strong><br />
                <? foreach($acompanantes as $a): ?>
                  <?=($a->completo)?($a->apellido.', '.$a->nombre):'<span style="color: #e71656">Faltan datos</span>';?><br>
                <? endforeach; ?>
              </p>
              <? endif; ?>


            </div>
          </td>
        </tr>


            
      <?=$mail_footer;?>