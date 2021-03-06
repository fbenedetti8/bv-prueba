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
						<p>+54 911 4174-5025 <span class="alert">(S??lo mensajes)</span></p>
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
						<p>T??rminos y condiciones generales</p>
					</div>

					<div>
						<div style="text-align:justify;">
							<div style="text-align:center;">BUENAS VIBRAS VIAJES - Legajo Nro. 14641</div>
							<br>
							El presente servicio tur??stico ser?? prestado por BUENAS VIBRAS VIAJES - EVT
							Leg. 14.641. Las partes declaran que conocen y aceptan las condiciones de viaje,
							incluyendo las pol??ticas de cancelaci??n que se indican a continuaci??n. El pasajero
							no podr?? contratar ni abonar nuestros servicios tur??sticos sin haber previamente
							le??do y aceptado los siguientes t??rminos y condiciones.
							Buenas Vibras Viajes no es responsable respecto de los servicios, paseos y
							excursiones contratados por el pasajero en forma directa y que no sean brindados
							por la empresa. La reserva y/o sus correspondientes vouchers son personales e
							intransferibles.
							<br><br>
							<b style="text-decoration:underline;">CONFIRMACI??N DE RESERVAS.</b><br>
							BUENAS VIBRAS VIAJES considerar?? v??lida aquella reserva efectuada en las
							formas y procedimientos indicados en su sitio web. El pasajero ser?? responsable
							por la carga y/o confirmaci??n de los datos personales (propios y de sus
							acompa??antes) y asumir?? las consecuencias de cualquier error que le fuera
							imputable que impida o demore la realizaci??n del viaje contratado.
							BUENAS VIBRAS VIAJES confirmar?? la reserva y asignar?? los lugares en forma
							definitiva una vez efectuado los pagos correspondientes. Una vez efectuada la
							reserva en nuestros sistemas, los pasajeros dispondr??n de 48 horas (o un plazo
							menor, ya sea por proximidad a la fecha de salida o cuando expresamente se
							indique en cualquier otro caso) para efectuar e informar los pagos
							correspondientes de acuerdo con los medios de pago habilitados, ya sea en forma
							directa o a trav??s de las agencias de viaje autorizadas. De no realizarse dichos
							pagos en los t??rminos y condiciones establecidos en las condiciones particulares
							de cada paquete, BUENAS VIBRAS VIAJES podr?? anular la reserva y asignar el
							lugar a otra persona interesada sin necesidad de comunicaci??n previa. Los viajes
							se se??an con un 30% del valor total del viaje o el mayor porcentaje que se
							establezca en el paquete contratado.
							<br><br>
							<b style="text-decoration:underline;">VOUCHER PARA EL PASAJERO.</b><br>
							Una vez abonada la totalidad del viaje, BUENAS VIBRAS VIAJES proceder?? a
							enviar el o los vouchers correspondientes al pasajero, hasta 72 horas antes del
							viaje. Se entender?? que se encuentra abonada la totalidad del viaje una vez
							acreditado el monto correspondiente en las cuentas de BUENAS VIBRAS VIAJES
							siempre que dicho pago haya sido informado por el pasajero.
							<br><br>
							<b style="text-decoration:underline;">DERECHO DE ADMISI??N Y PERMANENCIA.</b><br>
							BUENAS VIBRAS VIAJES se reserva el derecho de admisi??n y permanencia del
							pasajero en el viaje. Todo viaje de tipo GRUPAL estar?? a cargo de uno o m??s
							coordinadores designados por la empresa quienes est??n facultados para cancelar
							en forma autom??tica el contrato si a criterio de los mismos el pasajero pone el
							peligro la normal realizaci??n del viaje, ya sea por provocar da??os materiales,
							demoras injustificadas, estado de ebriedad, consumo y/o tenencia de sustancias
							prohibidas o elementos que pongan en peligro la seguridad de los pasajeros y del
							personal contratado. La misma facultad se podr?? aplicar si se detectan conductas
							abusivas, agresivas o que afecten la privacidad de cualquiera de los pasajeros y
							del personal contratado y/o implique violaci??n de leyes nacionales y/o
							internacionales y/o resulten ofensivas para la cultura de los lugares visitados y que
							afecten la seguridad del contingente.
							<br>En estos casos, el pasajero no tendr?? derecho a reintegro del precio pagado y
							deber?? regresar al lugar de origen por sus propios medios desligando a BUENAS
							VIBRAS VIAJES de cualquier tipo de responsabilidad.
							Buenas Vibras Viajes tiene la facultad de cancelar el viaje del pasajero en caso
							que el mismo importe riesgo para su salud y/o pueda afectar la salud, tranquilidad
							o seguridad de terceras personas.
							<br>Las personas con capacidades diferentes que deban ser asistidas por terceros o
							que por disposiciones de los proveedores de los servicios contratados y/o las
							autoridades nacionales o extranjeras deban ser acompa??adas durante el viaje por
							terceros, deber??n procurarse dicha asistencia y compa????a a su costa, bajo
							apercibimiento en caso de incumplimiento de considerar resuelto el contrato por
							parte de BUENAS VIBRAS VIAJES, sin derecho a indemnizaci??n alguna y
							aplicando la pol??tica de cancelaci??n vigente.
							En el caso de viajes NO GRUPALES (o de GRUPALES que no cuentan con la
							cantidad m??nima de pasajeros) la empresa no designar?? coordinadores. En estos
							casos ser??n los prestadores de cada uno de los servicios incluidos en el paquete
							quienes estar??n facultados para disponer del derecho de admisi??n y permanencia
							indicado en este apartado.
							<br><br>
							<b style="text-decoration:underline;">PRECIO:</b> Los precios no tendr??n modificaciones si el pasajero abona 100% del
							valor total del viaje (precio m??s impuestos), en los t??rminos y condiciones
							establecidos en la oferta. De lo contrario, est??n sujetos a variaciones en el precio
							sin importar el importe abonado el cual se considerar?? como pago a cuenta.
							Respecto de los viajes contratados en moneda extranjera: se pueden pagar en
							pesos al tipo de cambio que utiliza BUENAS VIBRAS VIAJES ese d??a y que el
							pasajero debe consultar telef??nicamente en la empresa o bien en nuestra web. Si
							el pago se acredita y se informa antes de las 14:30 hs del d??a, resultar?? de
							aplicaci??n el tipo de cambio del d??a del pago. Caso contrario BUENAS VIBRAS
							VIAJES tomar?? el tipo de cambio del d??a h??bil siguiente. El valor en moneda
							extranjera que resulte de ese pago, se imputar?? al valor total del paquete en
							moneda extranjera.
							<br>El precio de los servicios, impuestos y/o percepciones de impuestos que
							componen el tour quedan sujetos a modificaciones sin previo aviso cuando se
							produzca una alteraci??n en los servicios, modificaciones en los costos o en los
							tipos de cambio previstos, por causas no imputables a las partes. 2) Todos los
							importes pagados antes de la confirmaci??n definitiva de los servicios son
							percibidos en concepto de reserva, por lo que en ning??n supuesto podr??n ser
							considerados como confirmaci??n definitiva de los servicios solicitados ni del precio
							de los mismos. La confirmaci??n definitiva de los servicios y precios respectivos se
							producir?? con la emisi??n de pasajes y/u ??rdenes de servicios y la facturaci??n
							correspondiente. 3) Se deber?? abonar en los tiempos indicados el importe m??nimo
							requerido en la publicaci??n en concepto de reserva para considerarse inscripto en
							cualquiera de los programas de servicios. 4) Se deber?? cumplimentar el pago del
							importe m??nimo de reserva dentro de las 48 hs siguientes al momento de la
							compra de los productos publicados o de un plazo menor, cuando se indique lo
							contrario. 5) La falta de informaci??n y acreditaci??n de los pagos en tiempo y forma
							habilitar?? a la empresa a cancelar la inscripci??n sin necesidad de intimaci??n
							previa.
							<br>El precio incluye los servicios que se indican en cada publicaci??n y que se env??a
							por mail al momento de efectuar la reserva. Transporte, en caso de estar incluido,
							seg??n lo especificado en cada uno de los itinerarios. Alojamiento en los hoteles
							mencionados u otros similares en clasificaci??n, ocupando habitaciones seg??n
							tarifa elegida y de acuerdo al sistema de habitaci??n compartida en los casos que
							as?? se indique, con ba??o privado e impuestos. R??gimen de comidas seg??n se
							indique en cada oportunidad. Visitas y excursiones que se mencionen, en caso de
							estar incluidas en la publicaci??n. Traslados hasta y desde aeropuertos, terminales
							y hoteles, cuando se indique. La cantidad prevista de d??as de alojamiento teniendo
							en cuenta que el d??a de alojamiento hotelero se computa desde las quince horas y
							finaliza a las diez o doce horas del d??a siguiente (seg??n los casos),
							independientemente de la hora de llegada y de salida y de la utilizaci??n completa o
							fraccionada del mismo. La duraci??n del tour ser?? indicada en cada caso tomando
							como primer d??a el de salida y como ??ltimo incluido el d??a de llegada,
							independientemente del horario de salida o de llegada en el primer d??a o en el
							??ltimo.
							<br>Los precios no incluyen: visas, tasas de aeropuerto, tasas de ingreso de turista
							percibido en destino, impuestos diversos actuales o futuros, DNT, IVA, entradas a
							museos, parques nacionales, impuestos locales de turismo, asistencia m??dica y,
							en general, cualquier otro gasto considerado de ??ndole personal; lavado y
							planchado de ropa, comunicaciones, propinas, comidas y bebidas no
							especificadas, alimentaci??n en ruta excepto aquellas que estuviesen
							expresamente incluidas en los programas, inscripciones en congresos, ferias y
							eventos, etc. Los gastos por prolongaci??n de los servicios por deseo voluntario de
							los pasajeros, estadas, comidas y/o gastos adicionales o perjuicios producidos por
							cancelaciones, demoras en las salidas o llegadas de los medios de transporte o
							por causas de fuerza mayor o fuera del alcance del organizador y en general de
							ning??n concepto que no se encuentre espec??ficamente detallado en el itinerario
							correspondiente. Las habitaciones deber??n ser ocupadas hasta la hora del check
							out correspondiente, pasado este l??mite el pasajero deber?? abonar al hotel lo que
							??ste considere y sujeto a la disponibilidad del lugar.
							<br>El precio podr?? abonarse con cualquiera de los medios autorizados por BUENAS
							VIBRAS VIAJES. Los cargos administrativos e impuestos y tasas
							correspondientes al medio de pago elegido est??n a cargo del pasajero. Los pagos
							efectuados mediante dep??sito o transferencia deber??n ser informados por el
							pasajero, adjuntando foto o del comprobante o los datos de la transacci??n en la
							forma indicada en el sitio web de BUENAS VIBRAS VIAJES. Dicha comunicaci??n
							habilitar?? la generaci??n del correspondiente recibo de pago.
							<br>El viaje deber?? estar pago en su totalidad 30 d??as antes de la fecha de salida,
							salvo que se indique lo contrario en cada una de las publicaciones. Pasado ese
							plazo, BUENAS VIBRAS VIAJES podr?? considerar que el pasajero ANULA su
							reserva, quedando facultada para asignar el lugar a otro pasajero interesado y
							aplicando las pol??ticas de cancelaci??n indicadas m??s abajo. Se considerar?? fecha
							de pago la de informaci??n y acreditaci??n (si ambas acciones se producen en
							fechas diferentes, se tomar?? la ??ltima) de los fondos en la cuenta de BUENAS
							VIBRAS VIAJES.
							<br><br>
							<b style="text-decoration:underline;">POL??TICA DE CANCELACI??N:</b> Una vez realizada la reserva y en caso que el
							pasajero decidiere desistir del viaje por cualquier causa o raz??n, deber??
							comunicarlo a BUENAS VIBRAS VIAJES al mail reservas@buenas-vibras.com.ar
							indicando los datos del viaje y la intenci??n de su cancelaci??n. Se tomar?? como
							fecha de la cancelaci??n la del d??a de recepci??n del mail y si el mismo fuera inh??bil
							la del siguiente d??a h??bil. La cancelaci??n importar?? la aplicaci??n de las siguientes
							penalidades:<br>
							50% sobre el precio total del viaje (precio m??s impuestos) si la anulaci??n se realiza
							hasta la fecha en que se deba realizar el pago total del viaje.
							<br>100% sobre el precio total del viaje en el caso de anulaciones que se produzcan
							con posterioridad a la fecha estipulada para el pago total del viaje.
							<br>Dicha penalidad se aplicar?? cualquiera sea el motivo de la cancelaci??n del viaje.
							<br><br>
							<b style="text-decoration:underline;">EXCURSIONES Y ACTIVIDADES.</b><br>
							El cronograma de excursiones y/o actividades incluidas podr?? ser modificado y/o
							sustituido y/o cancelado cuando la autoridad competente disponga la suspensi??n
							de las actividades programadas y/o a criterio de los prestadores o de BUENAS
							VIBRAS VIAJES se ponga en riesgo la integridad de los pasajeros o por
							cuestiones clim??ticas o de fuerza mayor. Dichas cancelaciones ser??n
							interpretadas como de fuerza mayor o caso fortuito no generando derecho
							indemnizatorio alguno.
							<br><br>
							<b style="text-decoration:underline;">RECLAMACIONES Y REEMBOLSOS:</b> Cualquier reclamo deber?? ser presentado
							dentro de un plazo de 15 d??as corridos de finalizado el viaje adjuntando con este
							sus respectivos comprobantes. Pasado este t??rmino BUENAS VIBRAS VIAJES se
							reserva el derecho de no dar curso a la correspondiente reclamaci??n.
							<br><br>
							<b style="text-decoration:underline;">DOCUMENTACI??N:</b> Para los viajes al exterior es necesario atender a la
							legislaci??n vigente en cada caso. La documentaci??n y visado son de ??ndole
							estrictamente personal, siendo por ello responsabilidad exclusiva del pasajero
							contar con los documentos y visados en perfecto estado y vigencia ya que se le ha
							informado fehacientemente y con anticipaci??n suficiente sobre los requisitos que
							exigen las autoridades migratorias, aduaneras y sanitarias de los destinos que
							incluyen el tour. En caso de viajar menores ser??n sus padres y/o tutores los
							encargados de gestionar y obtener toda la documentaci??n exigidas por las
							diferentes autoridades, locales y extranjeras, para el egreso, permanencia y
							regreso de los menores. En el caso de viajes dentro del pa??s, ser?? necesario
							contar con DNI o el documento que establezcan las normas vigentes. El
							organizador no ser?? responsable por inconvenientes que sufrieran los pasajeros
							que carezcan de documentaci??n en orden. En caso de que alg??n pasajero no
							pudiera viajar por estas razones, perder?? el total de los servicios contratados sin
							derecho a reclamo o reintegro alguno.
							<br><br>
							<b style="text-decoration:underline;">ALTERACIONES Y/O MODIFICACIONES:</b> Los prestadores se reservan el
							derecho, por razones t??cnicas u operativas, de alterar total o parcialmente el
							ordenamiento diario y/o de servicios que componen el tour, antes o durante la
							ejecuci??n del mismo. Salvo condici??n expresa en contrario, los hoteles estipulados
							podr??n ser cambiados por otro de igual o mayor categor??a dentro del mismo
							n??cleo urbano sin cargo alguno para el pasajero. Respecto de estas variaciones el
							pasajero no tendr?? derecho a indemnizaci??n alguna. La empresa podr?? anular
							cualquier tour cuando se configure alguna de las circunstancias previstas en el art.
							24 del Decreto N?? 2182/72 incluida la de no haber alcanzado un suficiente n??mero
							de inscripciones, teniendo el pasajero solamente derecho al reintegro del importe
							abonado y renunciando a cualquier otro tipo de reclamo o indemnizaci??n
							suplementarios. Una vez comenzado el viaje, la suspensi??n, modificaci??n o
							interrupci??n de los servicios por parte del pasajero por razones personales de
							cualquier ??ndole, no dar?? lugar a reclamo alguno, reembolso o devoluci??n alguna.
							El organizador se reserva el derecho de alterar horarios e itinerarios, excursiones
							u hoteles para mejor desarrollo del tour, sin alterar la duraci??n del mismo. Si el
							viaje tuviese que acortarse o alargarse mas all?? de los t??rminos fijados por causas
							de fuerza mayor no imputables al operador, sobreventas de las compa????as a??reas
							u hoteles, los gastos correspondientes correr??n por cuenta del pasajero sin
							derecho a devoluci??n o compensaci??n alguna.
							<br><br>
							<b style="text-decoration:underline;">RESPONSABILIDAD:</b> BUENAS VIBRAS VIAJES declara expresamente que
							act??a en el car??cter de intermediaria en la reserva o contrataci??n de los distintos
							servicios vinculados e incluidos en el respectivo tour o reservaci??n de servicios:
							hoteles, restaurantes, medios de transportes a??reos y terrestres u otros
							prestadores. Por lo tanto declina toda responsabilidad por da??os de cualquier
							naturaleza que pudieran ocurrir a las personas que, por su mediaci??n, efect??en el
							viaje as?? como tambi??n respecto del equipaje y dem??s objetos de su propiedad en
							los programas con traslados incluidos. La empresa no se responsabiliza por los
							hechos que se produzcan por caso fortuito o fuerza mayor, fen??menos clim??ticos
							o hechos de la naturaleza que acontezcan antes o durante el desarrollo del tour
							que impidan, demoren o de cualquier modo obstaculicen la ejecuci??n total o
							parcial de las prestaciones comprometidas por la empresa, de conformidad con lo
							dispuesto por el C??digo Civil y Comercial de la Rep??blica Argentina.
							<br><br>
							<b style="text-decoration:underline;">COMPA??IAS A??REAS:</b> En el transporte a??reo de pasajeros y equipajes, la
							responsabilidad de BUENAS VIBRAS VIAJES se limita a efectuar la reserva y
							abonar el precio del servicio contratado seg??n las condiciones pactadas con cada
							prestador. En lo que de ello exceda, la responsabilidad es exclusiva y directa de
							los prestadores de servicios. Las l??neas a??reas que intervienen en nuestros
							programas no podr??n considerarse responsables durante el tiempo en que los
							pasajeros no est??n a bordo de sus aviones. El billete o pasaje constituir?? el ??nico
							compromiso entre la l??nea a??rea y el comprador de la excursi??n y/o pasajero. En
							los casos de los servicios a??reos que son a trav??s de los vuelos regulares, las
							condiciones de recargos, multas, postergaci??n de fechas y horarios de partida, son
							de la exclusiva incumbencia de las transportadoras con quien deber?? entenderse
							directamente el pasajero. En los casos que los servicios a??reos son a trav??s de
							vuelos charter o vuelos especiales, las fechas de partida y regresos no se pueden
							modificar salvo fuerza mayor o caso fortuito. En dicho supuesto BUENAS VIBRAS
							VIAJES no ser?? responsable por las demoras, cancelaciones y/o mayores costos
							que le signifique al pasajero dicha circunstancia. La no presentaci??n del pasajero
							en el mostrador con la debida anticipaci??n o el no embarque por problemas
							personales, de documentaci??n y/o fuerza mayor o caso fortuito, dar?? lugar a la
							cancelaci??n y p??rdida del vuelo que se trate, sin derecho a reclamaci??n,
							reembolso o devoluci??n alguna, tanto del precio del vuelo como de los restantes
							servicios incluidos en el paquete. Las fechas, horarios y aeropuertos previstos
							para la partida y regreso son de exclusiva competencia del transportador a??reo, el
							que podr?? modificarlas de acuerdo con usos y necesidades, razones t??cnicas o
							cualquier otra, propia de la actividad.
							<br><br>
							<b style="text-decoration:underline;">IMAGENES TOMADAS DURANTE EL VIAJE.</b><br>
							El pasajero faculta a BUENAS VIBRAS VIAJES a publicar las im??genes tomadas
							durante el viaje y/o eventos organizados que est??n relacionados o no con la
							presente reserva. Las im??genes a las que hacemos referencia podr??n tomarse
							como fotograf??as, videos o cualquier otra forma y ser publicadas en medios
							gr??ficos, digitales, internet, TV, etc. con fines publicitarios, did??cticos, informativos,
							etc.
							<br><br>
							<b style="text-decoration:underline;">NORMAS DE APLICACI??N:</b> El presente contrato y en su caso la prestaci??n de
							los servicios, se regir?? exclusivamente por estas condiciones generales, por la Ley
							N?? 18.829 y su reglamentaci??n y por la Convenci??n de Bruselas aprobada por la
							Ley 19.918. Ello as??, sin perjuicio de lo establecido respecto de la responsabilidad
							por accidentes en el transporte. Las presentes condiciones generales junto con la
							restante documentaci??n que se entregue a los pasajeros conformar??n el Contrato
							de Viaje que establece la citada Convenci??n.
							<br><br>
							<b style="text-decoration:underline;">JURISDICCI??N Y COMPETENCIA:</b> Toda cuesti??n que surja con motivo de la
							celebraci??n, cumplimiento, incumplimiento, pr??rroga o rescisi??n del presente
							contrato, ser?? sometida por las partes a la resoluci??n de los Tribunales Ordinarios
							del Fuero Comercial de la Capital Federal de la Rep??blica Argentina, renunciando
							las partes a cualquier otra jurisdicci??n y competencia.
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