<?php

//Fecha vigencia Ley Impuesto PAIS
$config['vigencia_impuesto_pais'] = "2020-01-07";

$config['languages'] = array(
	'es' => 'Español'
);

//Facebook config
//test 115938512456632
//prod 127939987293887

if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'www.buenas-vibras.com.ar'){
    $config['fb_id'] = '115938512456632';
}
else{
    $config['fb_id'] = '127939987293887';
}

//Google api key
$config['gapi_key'] = 'AIzaSyAfzEIAqaeHI1nx4Siz_AD1S0Ori4qkY1w';

//mensajes genericos segun el tipo de viaje
$config['viaje_grupal'] = 'Para jóvenes de 20 a 40 años, con todo incluido. Vas a poder conocer los mejores destinos del mundo, de una manera mucho más divertida y con nuevos amigos. Podés viajar solo o acompañado, como más te guste.';
$config['viaje_no_grupal'] = 'Para viajar en pareja, con amigos o en familia. Tenemos muchísimos destinos en Argentina y en el resto del mundo con muchísimas opciones de acuerdo a tus preferencias y posibilidades. Estos viajes no son grupales y te permiten manejarte con mayor libertad en cada destino.';
$config['email_from'] = 'no-reply@buenas-vibras.com.ar';


//google recaptcha
if($_SERVER['HTTP_HOST'] != 'www.buenas-vibras.com.ar'){
    //server test 
    $config['google_recaptcha_key'] = '6LdkuaIUAAAAAOyIJ_Cckf6CpuZ7HiyGkkVulWqE';
    $config['google_recaptcha_secret'] = '6LdkuaIUAAAAAJ7a89hIk_hFHWiXyvUwErHSL-6H';
}
else{
    //server prod
    $config['google_recaptcha_key'] = '6LdcPRMUAAAAALXBvxIbc4r3oeZ8JstpMLkc27fY';
    $config['google_recaptcha_secret'] = '6LdcPRMUAAAAABmfj4pdhr-GKxalokf_geKGxoAd';
}

/*if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'www.buenas-vibras.com.ar'){
    //mercado pago (USUARIO PRUEBA)
    $config['mp_client_id'] = '2531245726910885';
    $config['mp_client_secret'] = '5U2rWxEsMr4q2rjuuard9KnPyTGooPxJ';
}
else{*/
    //mercado pago (BUENAS VIBRAS PRODUCTIVO)
    $config['mp_client_id'] = "8038305266910906";
    $config['mp_client_secret'] = "mgIhXTkzjOlg9OvTXscXWonnOyeNOuEB";
// }

//PAYPAL
$config['pp_username'] = 'jmontes_api1.buenas-vibras.com.ar';
$config['pp_password'] = 'UHPKZD7K5VAGX2JJ';
$config['pp_signature'] = 'AkkZel67crrALNbnwjMVMtyfu0hJACv8SWtnV8k9.YVKNBIyz-eaH2nI';

/*
local
username: maxi_api1.id4you.com
password: 2DZSXDQUG53QG5G4
signature: Ai9m2cHKduaJKDISALcoqqnGVNf8AcrZe6hxb7ilJDA8gZf5UAgclR9P

produccion
username: jmontes_api1.buenas-vibras.com.ar
password: UHPKZD7K5VAGX2JJ
signature: AkkZel67crrALNbnwjMVMtyfu0hJACv8SWtnV8k9.YVKNBIyz-eaH2nI
*/


/*
RESPONSE CREACION USUARIO TEST: ESTE ES ***VENDEDOR***
Array
(
    [status] => 201
    [response] => Array
        (
            [id] => 323424321
            [nickname] => TETE7003307
            [password] => qatest8756
            [site_status] => active
            [email] => test_user_91565794@testuser.com
        )
)
***ESTE ES COMPRADOR***
Array
(
    [status] => 201
    [response] => Array
        (
            [id] => 323431887
            [nickname] => TETE8579957
            [password] => qatest2526
            [site_status] => active
            [email] => test_user_2448484@testuser.com
        )

)
*/

//emails de salida
$config['email_ventas'] = "ventas@buenas-vibras.com.ar";
$config['email_reservas'] = "reservas@buenas-vibras.com.ar";
$config['email_info'] = "info@buenas-vibras.com.ar";

//currencylayer.com api key
$config['currency_api_key'] = "cd8ff7401dc3a5ec10f949508299b1b7";
$config['dieta'] = array(
						array('id' => 'Vegetariano' ),
						array('id' => 'Celíaco' ),
						array('id' => 'Diabético' ),
						array('id' => 'Ninguno' ),
					);

$config['texto_abogado'] = 'POR FAVOR CONTROLÁ QUE LOS DATOS INGRESADOS SEAN EXACTOS Y FIELES A LA DOCUMENTACIÓN RESPALDATORIA. BUENAS VIBRAS NO SERÁ RESPONSABLE POR CUALQUIER INGRESO INCORRECTO DE DATOS QUE DEMORE Y/O IMPIDA LA REALIZACIÓN DEL VIAJE Y/O INCREMENTE SUS COSTOS. ASIMISMO SERÁS RESPONSABLE POR LOS DATOS INGRESADOS  CORRESPONDIENTE A OTROS PASAJEROS, EXIMIENDO DE TODA RESPONSABILIDAD A BUENAS VIBRAS.';
$config['texto_abogado_1pax'] = 'POR FAVOR CONTROLÁ QUE LOS DATOS INGRESADOS SEAN EXACTOS Y FIELES A LA DOCUMENTACIÓN RESPALDATORIA. BUENAS VIBRAS NO SERÁ RESPONSABLE POR CUALQUIER INGRESO INCORRECTO DE DATOS QUE DEMORE Y/O IMPIDA LA REALIZACIÓN DEL VIAJE Y/O INCREMENTE SUS COSTOS.';


//Se cobra un 5% de retencion para viajes al exterior que se paguen en 
/*
1  => EFECTIVO
11 => NOTA DE CREDITO - EFECTIVO
30 => HSBC - DEPOSITO
32 => HSBC - NOTA DE CREDITO - DEPOSITO
34 => GALICIA - DEPOSITO
36 => GALICIA - NOTA DE CREDITO - DEPOSITO
40 => SANTANDER RIO - DEPOSITO 
44 => SANTANDER RIO - NOTA DE CREDITO - DEPOSITO
46 => GALICIA USD DEPOSITO
48 => SANTANDER USD DEPOSITO
49 => GALICIA NOTA DE CREDITO USD DEPOSITO
50 => SANTANDER NOTA DE CREDITO USD DEPOSITO
63 => MACRO USD - DEPOSITO
65 => MACRO USD - DEPOSITO - NOTA DE CREDITO
67 => MACRO - DEPOSITO
69 => MACRO - DEPOSITO - NOTA DE CREDITO
*/
$config['conceptos_con_percepcion'] = array(1,11,30,32,34,36,40,44,46,48,49,50,63,65,67,69);


//uris por defecto para los filtros de la seccion proximos viajes
$config['uri_fecha'] = 'todas-las-fechas';
$config['uri_categoria'] = 'todas-las-categorias';
$config['uri_regiones'] = 'todas-las-regiones';

$config['url_como_llegar'] = 'https://maps.google.com/maps?ll=-34.555406,-58.461106&z=16&t=m&hl=es-ES&gl=AR&mapclient=embed&cid=2544908606645044649" target="_blank';

//Resizes para las imagenes de Destinos
$config['resizes_destinos'] = ['mobile' => ['width' => 520, 'height' => 289],
                               'desktop' => ['width' => 420, 'height' => 233] ];
//Resizes para las imagenes de Testimonios
$config['resizes_testimonios'] = ['mobile' => ['width' => 400, 'height' => 400],
                               'desktop' => ['width' => 186, 'height' => 186] ];