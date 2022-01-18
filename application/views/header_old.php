<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<?=$fb_meta;?>
	
	<title>Buenas Vibras Viajes</title>

	<link rel="stylesheet" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap-select.css" />
	<link rel="stylesheet" media="all" href="<?=base_url();?>media/assets/bootstrap/css/bootstrap-datepicker.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/owl-carousel/owl.carousel.min.css" />
	<link rel="stylesheet" media="all" href="<?=base_url();?>media/assets/fancybox/jquery.fancybox.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/estilos.css" />
	<link rel="stylesheet" href="<?=base_url();?>media/assets/css/media-queries.css" />

	<!-- GOOGLE MAPS API -->
	<!-- Solo para contacto.html -->
	<script async src="http://maps.google.com/maps/api/js?key=<?=$this->config->item('gapi_key');?>"></script>

	<!-- GOOGLE RECAPTCHA API -->
	<script async src='https://www.google.com/recaptcha/api.js'></script>

	<!-- [if lt IE 9] >
		<script src="<?=base_url();?>media/assets/js/css3-mediaqueries.js"></script>
		<script src="<?=base_url();?>media/assets/js/html5shiv.js"></script>
	<! [endif] -->

	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-4354235-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');

	fbq('init', '102528616748506');
	fbq('track', "PageView");</script>
	<!-- End Facebook Pixel Code -->
	
	<meta name="google-site-verification" content="WpYczJ9WbZjtysspbbFr3AlO0YzJkJf2h0WYuEAyf18" />	
</head>

<body id="<?=@$body_id;?>" class="<?=@$body_class;?>">
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=102528616748506&ev=PageView&noscript=1"
	/></noscript>

	<!-- FACEBOOK API -->
	<div id="fb-root"></div>
	<script>
		window.fbAsyncInit = function() {
    FB.init({
      appId      : '<?=$this->config->item("fb_id");?>',
      xfbml      : true,
      version    : 'v2.7'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/es_LA/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
	</script>


	<!-- TWITTER API -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



	<!-- POPUP CONTACTO -->

	<div class="popup full contacto">
		<div>
			<div class="container">

				<p><strong>Buenos Aires</strong></p>

				<div>
					<div class="btn_phone">
						<span class="icon-phone"></span>
						<p>(011) 5235-3810.</p>
					</div>

					<a class="btn_tel btn_grey" href="tel:01152353810">Llamar</a>
				</div>

				<div>
					<div class="btn_whatsapp color">
						<span class="icon-whatsapp"></span>
						<p>+54 911 4174-5025 <span class="alert">(Sólo mensajes)</span></p>
					</div>

					<a class="btn_tel btn_grey" href="https://api.whatsapp.com/send?phone=5491141745025">Escribir</a>
				</div>

				<span>Lunes a Viernes de 10 a 19 hs.</span>

			</div>
		</div>
	</div>

	<!-- FIN POPUP CONTACTO -->



	<div class="popup terminos">
		<div>
			<a href="#" class="btn_close">
				<span class="sr-only">Cerrar</span>
				<span class="icon-close"></span>
			</a>

			<div>
				<div class="content">
					<div class="head">
						<p>Términos y condiciones generales</p>
					</div>

					<div>
						<div style="text-align:justify;">
							<div style="text-align:center;">BUENAS VIBRAS VIAJES - Legajo Nro. 14641</div>
							<br>
							El presente servicio turístico será prestado por BUENAS VIBRAS VIAJES - EVT
							Leg. 14.641. Las partes declaran que conocen y aceptan las condiciones de viaje,
							incluyendo las políticas de cancelación que se indican a continuación. El pasajero
							no podrá contratar ni abonar nuestros servicios turísticos sin haber previamente
							leído y aceptado los siguientes términos y condiciones.
							Buenas Vibras Viajes no es responsable respecto de los servicios, paseos y
							excursiones contratados por el pasajero en forma directa y que no sean brindados
							por la empresa. La reserva y/o sus correspondientes vouchers son personales e
							intransferibles.
							<br><br>
							<b style="text-decoration:underline;">CONFIRMACIÓN DE RESERVAS.</b><br>
							BUENAS VIBRAS VIAJES considerará válida aquella reserva efectuada en las
							formas y procedimientos indicados en su sitio web. El pasajero será responsable
							por la carga y/o confirmación de los datos personales (propios y de sus
							acompañantes) y asumirá las consecuencias de cualquier error que le fuera
							imputable que impida o demore la realización del viaje contratado.
							BUENAS VIBRAS VIAJES confirmará la reserva y asignará los lugares en forma
							definitiva una vez efectuado los pagos correspondientes. Una vez efectuada la
							reserva en nuestros sistemas, los pasajeros dispondrán de 48 horas (o un plazo
							menor, ya sea por proximidad a la fecha de salida o cuando expresamente se
							indique en cualquier otro caso) para efectuar e informar los pagos
							correspondientes de acuerdo con los medios de pago habilitados, ya sea en forma
							directa o a través de las agencias de viaje autorizadas. De no realizarse dichos
							pagos en los términos y condiciones establecidos en las condiciones particulares
							de cada paquete, BUENAS VIBRAS VIAJES podrá anular la reserva y asignar el
							lugar a otra persona interesada sin necesidad de comunicación previa. Los viajes
							se señan con un 30% del valor total del viaje o el mayor porcentaje que se
							establezca en el paquete contratado.
							<br><br>
							<b style="text-decoration:underline;">VOUCHER PARA EL PASAJERO.</b><br>
							Una vez abonada la totalidad del viaje, BUENAS VIBRAS VIAJES procederá a
							enviar el o los vouchers correspondientes al pasajero, hasta 72 horas antes del
							viaje. Se entenderá que se encuentra abonada la totalidad del viaje una vez
							acreditado el monto correspondiente en las cuentas de BUENAS VIBRAS VIAJES
							siempre que dicho pago haya sido informado por el pasajero.
							<br><br>
							<b style="text-decoration:underline;">DERECHO DE ADMISIÓN Y PERMANENCIA.</b><br>
							BUENAS VIBRAS VIAJES se reserva el derecho de admisión y permanencia del
							pasajero en el viaje. Todo viaje de tipo GRUPAL estará a cargo de uno o más
							coordinadores designados por la empresa quienes están facultados para cancelar
							en forma automática el contrato si a criterio de los mismos el pasajero pone el
							peligro la normal realización del viaje, ya sea por provocar daños materiales,
							demoras injustificadas, estado de ebriedad, consumo y/o tenencia de sustancias
							prohibidas o elementos que pongan en peligro la seguridad de los pasajeros y del
							personal contratado. La misma facultad se podrá aplicar si se detectan conductas
							abusivas, agresivas o que afecten la privacidad de cualquiera de los pasajeros y
							del personal contratado y/o implique violación de leyes nacionales y/o
							internacionales y/o resulten ofensivas para la cultura de los lugares visitados y que
							afecten la seguridad del contingente.
							<br>En estos casos, el pasajero no tendrá derecho a reintegro del precio pagado y
							deberá regresar al lugar de origen por sus propios medios desligando a BUENAS
							VIBRAS VIAJES de cualquier tipo de responsabilidad.
							Buenas Vibras Viajes tiene la facultad de cancelar el viaje del pasajero en caso
							que el mismo importe riesgo para su salud y/o pueda afectar la salud, tranquilidad
							o seguridad de terceras personas.
							<br>Las personas con capacidades diferentes que deban ser asistidas por terceros o
							que por disposiciones de los proveedores de los servicios contratados y/o las
							autoridades nacionales o extranjeras deban ser acompañadas durante el viaje por
							terceros, deberán procurarse dicha asistencia y compañía a su costa, bajo
							apercibimiento en caso de incumplimiento de considerar resuelto el contrato por
							parte de BUENAS VIBRAS VIAJES, sin derecho a indemnización alguna y
							aplicando la política de cancelación vigente.
							En el caso de viajes NO GRUPALES (o de GRUPALES que no cuentan con la
							cantidad mínima de pasajeros) la empresa no designará coordinadores. En estos
							casos serán los prestadores de cada uno de los servicios incluidos en el paquete
							quienes estarán facultados para disponer del derecho de admisión y permanencia
							indicado en este apartado.
							<br><br>
							<b style="text-decoration:underline;">PRECIO:</b> Los precios no tendrán modificaciones si el pasajero abona 100% del
							valor total del viaje (precio más impuestos), en los términos y condiciones
							establecidos en la oferta. De lo contrario, están sujetos a variaciones en el precio
							sin importar el importe abonado el cual se considerará como pago a cuenta.
							Respecto de los viajes contratados en moneda extranjera: se pueden pagar en
							pesos al tipo de cambio que utiliza BUENAS VIBRAS VIAJES ese día y que el
							pasajero debe consultar telefónicamente en la empresa o bien en nuestra web. Si
							el pago se acredita y se informa antes de las 14:30 hs del día, resultará de
							aplicación el tipo de cambio del día del pago. Caso contrario BUENAS VIBRAS
							VIAJES tomará el tipo de cambio del día hábil siguiente. El valor en moneda
							extranjera que resulte de ese pago, se imputará al valor total del paquete en
							moneda extranjera.
							<br>El precio de los servicios, impuestos y/o percepciones de impuestos que
							componen el tour quedan sujetos a modificaciones sin previo aviso cuando se
							produzca una alteración en los servicios, modificaciones en los costos o en los
							tipos de cambio previstos, por causas no imputables a las partes. 2) Todos los
							importes pagados antes de la confirmación definitiva de los servicios son
							percibidos en concepto de reserva, por lo que en ningún supuesto podrán ser
							considerados como confirmación definitiva de los servicios solicitados ni del precio
							de los mismos. La confirmación definitiva de los servicios y precios respectivos se
							producirá con la emisión de pasajes y/u órdenes de servicios y la facturación
							correspondiente. 3) Se deberá abonar en los tiempos indicados el importe mínimo
							requerido en la publicación en concepto de reserva para considerarse inscripto en
							cualquiera de los programas de servicios. 4) Se deberá cumplimentar el pago del
							importe mínimo de reserva dentro de las 48 hs siguientes al momento de la
							compra de los productos publicados o de un plazo menor, cuando se indique lo
							contrario. 5) La falta de información y acreditación de los pagos en tiempo y forma
							habilitará a la empresa a cancelar la inscripción sin necesidad de intimación
							previa.
							<br>El precio incluye los servicios que se indican en cada publicación y que se envía
							por mail al momento de efectuar la reserva. Transporte, en caso de estar incluido,
							según lo especificado en cada uno de los itinerarios. Alojamiento en los hoteles
							mencionados u otros similares en clasificación, ocupando habitaciones según
							tarifa elegida y de acuerdo al sistema de habitación compartida en los casos que
							así se indique, con baño privado e impuestos. Régimen de comidas según se
							indique en cada oportunidad. Visitas y excursiones que se mencionen, en caso de
							estar incluidas en la publicación. Traslados hasta y desde aeropuertos, terminales
							y hoteles, cuando se indique. La cantidad prevista de días de alojamiento teniendo
							en cuenta que el día de alojamiento hotelero se computa desde las quince horas y
							finaliza a las diez o doce horas del día siguiente (según los casos),
							independientemente de la hora de llegada y de salida y de la utilización completa o
							fraccionada del mismo. La duración del tour será indicada en cada caso tomando
							como primer día el de salida y como último incluido el día de llegada,
							independientemente del horario de salida o de llegada en el primer día o en el
							último.
							<br>Los precios no incluyen: visas, tasas de aeropuerto, tasas de ingreso de turista
							percibido en destino, impuestos diversos actuales o futuros, DNT, IVA, entradas a
							museos, parques nacionales, impuestos locales de turismo, asistencia médica y,
							en general, cualquier otro gasto considerado de índole personal; lavado y
							planchado de ropa, comunicaciones, propinas, comidas y bebidas no
							especificadas, alimentación en ruta excepto aquellas que estuviesen
							expresamente incluidas en los programas, inscripciones en congresos, ferias y
							eventos, etc. Los gastos por prolongación de los servicios por deseo voluntario de
							los pasajeros, estadas, comidas y/o gastos adicionales o perjuicios producidos por
							cancelaciones, demoras en las salidas o llegadas de los medios de transporte o
							por causas de fuerza mayor o fuera del alcance del organizador y en general de
							ningún concepto que no se encuentre específicamente detallado en el itinerario
							correspondiente. Las habitaciones deberán ser ocupadas hasta la hora del check
							out correspondiente, pasado este límite el pasajero deberá abonar al hotel lo que
							éste considere y sujeto a la disponibilidad del lugar.
							<br>El precio podrá abonarse con cualquiera de los medios autorizados por BUENAS
							VIBRAS VIAJES. Los cargos administrativos e impuestos y tasas
							correspondientes al medio de pago elegido están a cargo del pasajero. Los pagos
							efectuados mediante depósito o transferencia deberán ser informados por el
							pasajero, adjuntando foto o del comprobante o los datos de la transacción en la
							forma indicada en el sitio web de BUENAS VIBRAS VIAJES. Dicha comunicación
							habilitará la generación del correspondiente recibo de pago.
							<br>El viaje deberá estar pago en su totalidad 30 días antes de la fecha de salida,
							salvo que se indique lo contrario en cada una de las publicaciones. Pasado ese
							plazo, BUENAS VIBRAS VIAJES podrá considerar que el pasajero ANULA su
							reserva, quedando facultada para asignar el lugar a otro pasajero interesado y
							aplicando las políticas de cancelación indicadas más abajo. Se considerará fecha
							de pago la de información y acreditación (si ambas acciones se producen en
							fechas diferentes, se tomará la última) de los fondos en la cuenta de BUENAS
							VIBRAS VIAJES.
							<br><br>
							<b style="text-decoration:underline;">POLÍTICA DE CANCELACIÓN:</b> Una vez realizada la reserva y en caso que el
							pasajero decidiere desistir del viaje por cualquier causa o razón, deberá
							comunicarlo a BUENAS VIBRAS VIAJES al mail reservas@buenas-vibras.com.ar
							indicando los datos del viaje y la intención de su cancelación. Se tomará como
							fecha de la cancelación la del día de recepción del mail y si el mismo fuera inhábil
							la del siguiente día hábil. La cancelación importará la aplicación de las siguientes
							penalidades:<br>
							50% sobre el precio total del viaje (precio más impuestos) si la anulación se realiza
							hasta la fecha en que se deba realizar el pago total del viaje.
							<br>100% sobre el precio total del viaje en el caso de anulaciones que se produzcan
							con posterioridad a la fecha estipulada para el pago total del viaje.
							<br>Dicha penalidad se aplicará cualquiera sea el motivo de la cancelación del viaje.
							<br><br>
							<b style="text-decoration:underline;">EXCURSIONES Y ACTIVIDADES.</b><br>
							El cronograma de excursiones y/o actividades incluidas podrá ser modificado y/o
							sustituido y/o cancelado cuando la autoridad competente disponga la suspensión
							de las actividades programadas y/o a criterio de los prestadores o de BUENAS
							VIBRAS VIAJES se ponga en riesgo la integridad de los pasajeros o por
							cuestiones climáticas o de fuerza mayor. Dichas cancelaciones serán
							interpretadas como de fuerza mayor o caso fortuito no generando derecho
							indemnizatorio alguno.
							<br><br>
							<b style="text-decoration:underline;">RECLAMACIONES Y REEMBOLSOS:</b> Cualquier reclamo deberá ser presentado
							dentro de un plazo de 15 días corridos de finalizado el viaje adjuntando con este
							sus respectivos comprobantes. Pasado este término BUENAS VIBRAS VIAJES se
							reserva el derecho de no dar curso a la correspondiente reclamación.
							<br><br>
							<b style="text-decoration:underline;">DOCUMENTACIÓN:</b> Para los viajes al exterior es necesario atender a la
							legislación vigente en cada caso. La documentación y visado son de índole
							estrictamente personal, siendo por ello responsabilidad exclusiva del pasajero
							contar con los documentos y visados en perfecto estado y vigencia ya que se le ha
							informado fehacientemente y con anticipación suficiente sobre los requisitos que
							exigen las autoridades migratorias, aduaneras y sanitarias de los destinos que
							incluyen el tour. En caso de viajar menores serán sus padres y/o tutores los
							encargados de gestionar y obtener toda la documentación exigidas por las
							diferentes autoridades, locales y extranjeras, para el egreso, permanencia y
							regreso de los menores. En el caso de viajes dentro del país, será necesario
							contar con DNI o el documento que establezcan las normas vigentes. El
							organizador no será responsable por inconvenientes que sufrieran los pasajeros
							que carezcan de documentación en orden. En caso de que algún pasajero no
							pudiera viajar por estas razones, perderá el total de los servicios contratados sin
							derecho a reclamo o reintegro alguno.
							<br><br>
							<b style="text-decoration:underline;">ALTERACIONES Y/O MODIFICACIONES:</b> Los prestadores se reservan el
							derecho, por razones técnicas u operativas, de alterar total o parcialmente el
							ordenamiento diario y/o de servicios que componen el tour, antes o durante la
							ejecución del mismo. Salvo condición expresa en contrario, los hoteles estipulados
							podrán ser cambiados por otro de igual o mayor categoría dentro del mismo
							núcleo urbano sin cargo alguno para el pasajero. Respecto de estas variaciones el
							pasajero no tendrá derecho a indemnización alguna. La empresa podrá anular
							cualquier tour cuando se configure alguna de las circunstancias previstas en el art.
							24 del Decreto N° 2182/72 incluida la de no haber alcanzado un suficiente número
							de inscripciones, teniendo el pasajero solamente derecho al reintegro del importe
							abonado y renunciando a cualquier otro tipo de reclamo o indemnización
							suplementarios. Una vez comenzado el viaje, la suspensión, modificación o
							interrupción de los servicios por parte del pasajero por razones personales de
							cualquier índole, no dará lugar a reclamo alguno, reembolso o devolución alguna.
							El organizador se reserva el derecho de alterar horarios e itinerarios, excursiones
							u hoteles para mejor desarrollo del tour, sin alterar la duración del mismo. Si el
							viaje tuviese que acortarse o alargarse mas allá de los términos fijados por causas
							de fuerza mayor no imputables al operador, sobreventas de las compañías aéreas
							u hoteles, los gastos correspondientes correrán por cuenta del pasajero sin
							derecho a devolución o compensación alguna.
							<br><br>
							<b style="text-decoration:underline;">RESPONSABILIDAD:</b> BUENAS VIBRAS VIAJES declara expresamente que
							actúa en el carácter de intermediaria en la reserva o contratación de los distintos
							servicios vinculados e incluidos en el respectivo tour o reservación de servicios:
							hoteles, restaurantes, medios de transportes aéreos y terrestres u otros
							prestadores. Por lo tanto declina toda responsabilidad por daños de cualquier
							naturaleza que pudieran ocurrir a las personas que, por su mediación, efectúen el
							viaje así como también respecto del equipaje y demás objetos de su propiedad en
							los programas con traslados incluidos. La empresa no se responsabiliza por los
							hechos que se produzcan por caso fortuito o fuerza mayor, fenómenos climáticos
							o hechos de la naturaleza que acontezcan antes o durante el desarrollo del tour
							que impidan, demoren o de cualquier modo obstaculicen la ejecución total o
							parcial de las prestaciones comprometidas por la empresa, de conformidad con lo
							dispuesto por el Código Civil y Comercial de la República Argentina.
							<br><br>
							<b style="text-decoration:underline;">COMPAÑIAS AÉREAS:</b> En el transporte aéreo de pasajeros y equipajes, la
							responsabilidad de BUENAS VIBRAS VIAJES se limita a efectuar la reserva y
							abonar el precio del servicio contratado según las condiciones pactadas con cada
							prestador. En lo que de ello exceda, la responsabilidad es exclusiva y directa de
							los prestadores de servicios. Las líneas aéreas que intervienen en nuestros
							programas no podrán considerarse responsables durante el tiempo en que los
							pasajeros no están a bordo de sus aviones. El billete o pasaje constituirá el único
							compromiso entre la línea aérea y el comprador de la excursión y/o pasajero. En
							los casos de los servicios aéreos que son a través de los vuelos regulares, las
							condiciones de recargos, multas, postergación de fechas y horarios de partida, son
							de la exclusiva incumbencia de las transportadoras con quien deberá entenderse
							directamente el pasajero. En los casos que los servicios aéreos son a través de
							vuelos charter o vuelos especiales, las fechas de partida y regresos no se pueden
							modificar salvo fuerza mayor o caso fortuito. En dicho supuesto BUENAS VIBRAS
							VIAJES no será responsable por las demoras, cancelaciones y/o mayores costos
							que le signifique al pasajero dicha circunstancia. La no presentación del pasajero
							en el mostrador con la debida anticipación o el no embarque por problemas
							personales, de documentación y/o fuerza mayor o caso fortuito, dará lugar a la
							cancelación y pérdida del vuelo que se trate, sin derecho a reclamación,
							reembolso o devolución alguna, tanto del precio del vuelo como de los restantes
							servicios incluidos en el paquete. Las fechas, horarios y aeropuertos previstos
							para la partida y regreso son de exclusiva competencia del transportador aéreo, el
							que podrá modificarlas de acuerdo con usos y necesidades, razones técnicas o
							cualquier otra, propia de la actividad.
							<br><br>
							<b style="text-decoration:underline;">IMAGENES TOMADAS DURANTE EL VIAJE.</b><br>
							El pasajero faculta a BUENAS VIBRAS VIAJES a publicar las imágenes tomadas
							durante el viaje y/o eventos organizados que estén relacionados o no con la
							presente reserva. Las imágenes a las que hacemos referencia podrán tomarse
							como fotografías, videos o cualquier otra forma y ser publicadas en medios
							gráficos, digitales, internet, TV, etc. con fines publicitarios, didácticos, informativos,
							etc.
							<br><br>
							<b style="text-decoration:underline;">NORMAS DE APLICACIÓN:</b> El presente contrato y en su caso la prestación de
							los servicios, se regirá exclusivamente por estas condiciones generales, por la Ley
							N° 18.829 y su reglamentación y por la Convención de Bruselas aprobada por la
							Ley 19.918. Ello así, sin perjuicio de lo establecido respecto de la responsabilidad
							por accidentes en el transporte. Las presentes condiciones generales junto con la
							restante documentación que se entregue a los pasajeros conformarán el Contrato
							de Viaje que establece la citada Convención.
							<br><br>
							<b style="text-decoration:underline;">JURISDICCIÓN Y COMPETENCIA:</b> Toda cuestión que surja con motivo de la
							celebración, cumplimiento, incumplimiento, prórroga o rescisión del presente
							contrato, será sometida por las partes a la resolución de los Tribunales Ordinarios
							del Fuero Comercial de la Capital Federal de la República Argentina, renunciando
							las partes a cualquier otra jurisdicción y competencia.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- HEADER -->

	<div id="header">

		<?=$menu;?>

	</div>

	<!-- FIN HEADER -->