<?php
include "AdminController.php";

class Reservas extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Reserva_model', 'Reserva');
		$this->load->model('Usuario_model', 'Usuario');
		$this->load->model('Admin_model', 'Admin');
		$this->load->model('Vendedor_model', 'Vendedor');
		$this->load->model('Operador_model', 'Operador');
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Paquete_model', 'Paquete');
		$this->load->model('Mailing_model','Mailing');
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Combinacion_model','Combinacion');
		$this->load->model('Reserva_pasajero_model','Reserva_pasajero');
		$this->load->model('Pasajero_model','Pasajero');
		$this->load->model('Reserva_facturacion_model','Reserva_facturacion');
		$this->load->model('Reserva_estado_model','Reserva_estado');
		$this->load->model('Reserva_informe_pago_model','Reserva_informe_pago');
		$this->load->model('Reserva_voucher_model', 'Reserva_voucher');
		$this->load->model('Concepto_model', 'Concepto');
		$this->load->model('Reserva_comentario_model','Comentario');
		$this->load->model('Comentario_tipo_accion_model','Comentario_tipo');
		$this->load->model('Adicional_model','Adicional');
		$this->load->model('Pais_model','Pais');

		$this->load->model('Habitacion_model','Habitacion');
		$this->load->model('Paquete_rooming_model','Paquete_rooming');
		$this->load->model('Parada_model', 'Parada');
		$this->load->model('Orden_model', 'Orden');
		$this->load->model('Fecha_alojamiento_cupo_model', 'Fecha_alojamiento_cupo');
		$this->model = $this->Reserva;
		$this->page = "reservas";
		$this->data['currentModule'] = "reservas";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);
		$this->pageSegment = 4;
		$this->data['page_title'] = "Reservas";
		$this->limit = 50;

		$this->init();
		$this->validate = FALSE;


		$this->load->model('Pais_model','Pais');
		$this->data['paises'] = $this->Pais->getAll(999,0,'nombre','asc')->result();

		$this->load->model('Lugar_salida_model','Lugar_salida');
		$this->data['lugares'] = $this->Lugar_salida->getAll(999,0,'nombre','asc')->result();

		//no mostrar estos
		/*
		ANULACIÓN RESERVA, COSTO PAQUETE ANULACION, COSTO PAQUETE REGISTRO, PAGO (es de OPERADOR)
		PENALIDAD POR ANULACION
		*/
		$this->Concepto->filters = "id not in(2,56,57,10,62) and sistema_caja = 0";
		$this->data['conceptos'] = $this->Concepto->getAll(999,0,'nombre','asc')->result();

		$this->data['dietas'] = array(
									array('nombre' => 'Vegetariano'),
									array('nombre' => 'Celíaco'),
									array('nombre' => 'Diabético'),
									array('nombre' => 'Ninguno'),
								);
		$this->data['sexos'] = array(
									array('id' => 'femenino','nombre' => 'Femenino'),
									array('id' => 'masculino','nombre' => 'Masculino'),
								);

		$this->data['estados'] = $this->Reserva_estado->getAll(999,0,'nombre','asc')->result();
		$this->data['vendedores'] = $this->Vendedor->getAll(999,0,'nombreCompleto','asc')->result();


		/*if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			$this->output->enable_profiler(TRUE);
			ini_set('memory_limit','256M');
			ini_set('max_execution_time','3600');
		}*/
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

	}

	function individuales(){
		$this->session->set_userdata('tipo_destino','individuales');
		redirect(base_url().'admin/reservas');
	}

	function grupales(){
		$this->session->set_userdata('tipo_destino','grupales');
		redirect(base_url().'admin/reservas');
	}

	function finalizadas(){
		$this->session->unset_userdata('destino_id');
		$this->session->unset_userdata('filter_activo');
		redirect($this->data['route']);
	}

	function vigentes(){
		$this->session->unset_userdata('destino_id');
		$this->session->set_userdata('filter_activo',true);
		redirect($this->data['route']);
	}

	function index_sincache(){
		/*if ($_SERVER['REMOTE_ADDR'] == '190.195.15.46') {
			$this->output->enable_profiler(TRUE);
		}*/

		if($this->session->userdata('filter_activo')){
			$activo = 1;
		}
		else{
			$activo = 0;
		}

		/*
		$activo = 1;
		if(isset($_POST['activo'])){
			$activo = $_POST['activo'];
		}
		*/

		$year = '';
		if($activo == 0) $year = date('Y');

		if(isset($_POST['year']) && $_POST['year'] ){
			$year = $_POST['year'];
		}
		$this->data['year'] = $year;

		//listado de paquetes ordenado por codigo de paquete
		if (!$this->session->userdata('sort')) {
			//$this->data['sort'] = "P.codigo";
			//pedido juan 08-08-18 por defecto que sea fecha inicio del viaje
			$this->data['sort'] = "P.fecha_inicio";
			$this->data['sortType'] = "ASC";
			$this->session->set_userdata('sortType', $this->data['sortType']);
			$this->session->set_userdata('sort', $this->data['sort']);
		}
		$this->pconfig['uri_segment'] = '';
		$this->pconfig['per_page'] = 99999;

		/*
		//fix ID porque si no no sale el FERRUGEM JOVEN
		$this->MDestino->filters = "publicado = 1 or id = 35";
		*/
		$this->data['destinos'] = $this->Destino->getAll(999,0,'nombre','asc');

		//dividido en individuales y grupales
		$this->data['destinos_grupales'] = $this->Destino->getGrupales();
		$this->data['destinos_individuales'] = $this->Destino->getIndividuales();


		//filtro de destino
		if(isset($_GET['destino_id']) && $_GET['destino_id'] != ''){
			$this->data['destino_id'] = $_GET['destino_id'];
			$this->db->where('P.destino_id = '.$_GET['destino_id']);
			$this->session->set_userdata('destino_id',$_GET['destino_id']);
		}
		elseif(isset($_GET['destino_id']) && $_GET['destino_id'] == ''){
			$this->session->unset_userdata('destino_id');
		}
		else {
			if($this->session->userdata('destino_id')){
				$this->data['destino_id'] =$this->session->userdata('destino_id');
				$this->db->where('P.destino_id = '.$this->session->userdata('destino_id'));
			}
		}

		//filtro de keyword
		if(isset($this->data['keywords']) && $this->data['keywords'] != ''){
			$this->db->where('P.codigo LIKE "%'.$this->data['keywords'].'%" or
							  P.nombre LIKE "%'.$this->data['keywords'].'%"');
		}

		//filtro por tipo
		if($this->session->userdata('tipo_destino') != ''){
			$tipo = $this->session->userdata('tipo_destino') == 'grupales' ? '0' : '1';
			$this->db->where('D.viaje_individual = '.$tipo);
		}

		$this->data['data'] = $this->model->getAllGroupByPaquetes($activo,$this->data['sort'],$this->data['sortType'],$year,$this->pconfig['per_page'],$this->uri->segment($this->pconfig['uri_segment']));

		if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			echo "<pre>";
			echo $this->db->last_query();
			echo "</pre>";
		}

		//obtengo saldo a cobrar de cada paquete
		foreach($this->data['data']->result() as $paq){
			/*
			$q = "select R5.paquete_id, SUM(M.debe)-SUM(M.haber) as saldo, SUM(M.debe_usd)-SUM(M.haber_usd) as saldo_usd
				from bv_movimientos M, bv_reservas R5
				where R5.usuario_id = M.usuario_id and M.tipoUsuario = 'U'
					and R5.estado_id != 5 and R5.paquete_id = ".$paq->id."
					and R5.id = M.reserva_id";
			$datos = $this->db->query($q)->row();

			if($paq->precio_usd)
				$paq->saldoACobrar = isset($datos->saldo_usd) ? $datos->saldo_usd : 0;
			else
				$paq->saldoACobrar = isset($datos->saldo) ? $datos->saldo : 0;
			*/

			//08/11/2018 de cada paquete calculo cuantos adicionales disponibles del total tengo
			$adicionales = $this->Adicional->getByPaquete($paq->id);
			$cant=0; $usados=0;
			foreach ($adicionales as $a) {
				if($a->obligatorio){
					$cant += $a->cantidad;
					$usados += $a->usados;
				}
			}
			$paq->adicionales_total = $cant;
			$paq->adicionales_disponibles = $cant-$usados>0 ? $cant-$usados : 0;

			$paq->llamados_por_hacer = 0;

			//si esta logueado un vendedor
			if (esVendedor() && perfil() == 'VENEXT'){
				$admin_id = $this->session->userdata('admin_id');
			}
			else
				$admin_id = '';

			$reservas = $this->model->getAllByPaquete($paq->id,'','','',$admin_id,'');

			$paq->alarmas = new stdClass;
			foreach($reservas->result() as $re){

				$als = $this->cargar_alarmas($re);

				$paq->alarmas->informes = $als->informes > @$paq->alarmas->informes ? $als->informes : @$paq->alarmas->informes;
				$paq->alarmas->completar_datos_pax = $als->completar_datos_pax > @$paq->alarmas->completar_datos_pax ? $als->completar_datos_pax : @$paq->alarmas->completar_datos_pax;
				$paq->alarmas->alerta_no_llamar = $als->alerta_no_llamar > @$paq->alarmas->alerta_no_llamar ? $als->alerta_no_llamar : @$paq->alarmas->alerta_no_llamar;
				$paq->alarmas->alerta_llamar_pax = $als->alerta_llamar_pax > @$paq->alarmas->alerta_llamar_pax ? $als->alerta_llamar_pax : @$paq->alarmas->alerta_llamar_pax;
				$paq->alarmas->alerta_reestablecida = $als->alerta_reestablecida > @$paq->alarmas->alerta_reestablecida ? $als->alerta_reestablecida : @$paq->alarmas->alerta_reestablecida;
				$paq->alarmas->alerta_contestador = $als->alerta_contestador > @$paq->alarmas->alerta_contestador ? $als->alerta_contestador : @$paq->alarmas->alerta_contestador;
				$paq->alarmas->falta_factura_proveedor = $als->falta_factura_proveedor > @$paq->alarmas->falta_factura_proveedor ? $als->falta_factura_proveedor : @$paq->alarmas->falta_factura_proveedor;
				$paq->alarmas->faltan_cargar_vouchers = $als->faltan_cargar_vouchers > @$paq->alarmas->faltan_cargar_vouchers ? $als->faltan_cargar_vouchers : @$paq->alarmas->faltan_cargar_vouchers;
				$paq->alarmas->reservas_a_confirmar = $paq->a_confirmar;
				$paq->alarmas->alerta_cupos_vencidos = $als->alerta_cupos_vencidos > @$paq->alarmas->alerta_cupos_vencidos ? $als->alerta_cupos_vencidos : @$paq->alarmas->alerta_cupos_vencidos;
				$paq->alarmas->fecha_limite_pago_completo = $als->fecha_limite_pago_completo > @$paq->alarmas->fecha_limite_pago_completo ? $als->fecha_limite_pago_completo : @$paq->alarmas->fecha_limite_pago_completo;
				$paq->alarmas->diferencias_rooming = $als->diferencias_rooming > @$paq->alarmas->diferencias_rooming ? $als->diferencias_rooming : @$paq->alarmas->diferencias_rooming;
			}

			if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
				#pre($paq->alarmas);
			}
		}


		$this->data['totalRows'] = count($this->data['data']);
		$this->data['saved'] = isset($_GET['saved'])?$_GET['saved']:'';

		if($activo == 1){
			$this->data['msg_text'] = "Ver reservas finalizadas";
			$this->breadcrumbs[] = 'Vigentes';
			//$this->init();
		}
		else{
			$this->data['msg_text'] = "Ver reservas vigentes";
			$this->breadcrumbs[] = 'Finalizadas';
			//$this->init();
		}

		$this->data['activo'] = $activo;

		if( $this->session->userdata('es_vendedor') )
			$this->data['es_vendedor'] = true;

		$this->load->view('admin/' . $this->page, $this->data);
	}

	function index(){
		if ($_SERVER['REMOTE_ADDR'] == '190.195.15.46') {
			//$this->output->enable_profiler(TRUE);
		}

		if($this->session->userdata('filter_activo')){
			$activo = 1;
		}
		else{
			$activo = 0;
		}

		/*
		$activo = 1;
		if(isset($_POST['activo'])){
			$activo = $_POST['activo'];
		}
		*/

		$year = '';
		if($activo == 0) $year = date('Y');

		if(isset($_POST['year']) && $_POST['year'] ){
			$year = $_POST['year'];
		}
		$this->data['year'] = $year;

		//listado de paquetes ordenado por codigo de paquete
		if (!$this->session->userdata('sort')) {
			//$this->data['sort'] = "P.codigo";
			//pedido juan 08-08-18 por defecto que sea fecha inicio del viaje
			$this->data['sort'] = "P.fecha_inicio";
			$this->data['sortType'] = "ASC";
			$this->session->set_userdata('sortType', $this->data['sortType']);
			$this->session->set_userdata('sort', $this->data['sort']);
		}
		$this->pconfig['uri_segment'] = '';
		$this->pconfig['per_page'] = 99999;

		/*
		//fix ID porque si no no sale el FERRUGEM JOVEN
		$this->MDestino->filters = "publicado = 1 or id = 35";
		*/
		$this->data['destinos'] = $this->Destino->getAll(999,0,'nombre','asc');

		//dividido en individuales y grupales
		$this->data['destinos_grupales'] = $this->Destino->getGrupales();
		$this->data['destinos_individuales'] = $this->Destino->getIndividuales();

		$filtros = [];

		//filtro de destino
		if(isset($_GET['destino_id']) && $_GET['destino_id'] != ''){
			$this->data['destino_id'] = $_GET['destino_id'];
			$filtros[] = 'P.destino_id = '.$_GET['destino_id'];
			$this->session->set_userdata('destino_id',$_GET['destino_id']);
		}
		elseif(isset($_GET['destino_id']) && $_GET['destino_id'] == ''){
			$this->session->unset_userdata('destino_id');
		}
		else {
			if($this->session->userdata('destino_id')){
				$this->data['destino_id'] =$this->session->userdata('destino_id');
				$filtros[] = 'P.destino_id = '.$this->session->userdata('destino_id');
			}
		}

		//filtro de keyword
		if(isset($this->data['keywords']) && $this->data['keywords'] != ''){
			$filtros[] = '(P.codigo LIKE "%'.$this->data['keywords'].'%" or
							  P.nombre LIKE "%'.$this->data['keywords'].'%")';
		}

		//filtro por tipo
		if($this->session->userdata('tipo_destino') != ''){
			$tipo = $this->session->userdata('tipo_destino') == 'grupales' ? '0' : '1';
			$filtros[] = 'D.viaje_individual = '.$tipo;
		}

		$this->data['data'] = $this->model->getAllGroupByPaquetesOptim($activo,$this->data['sort'],$this->data['sortType'],$year,$this->pconfig['per_page'],$this->uri->segment($this->pconfig['uri_segment']), '', '', $filtros);

		/*if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
			echo "<pre>";
			echo $this->db->last_query();
			echo "</pre>";
		}*/

		//si esta logueado un vendedor
		if (esVendedor() && perfil() == 'VENEXT'){
			$admin_id = $this->session->userdata('admin_id');
		}
		else
			$admin_id = '';

		$reservas_paquetes = $this->model->getAllByPaquetesActivos('','','',$admin_id,'')->result();
		//obtengo saldo a cobrar de cada paquete
		foreach($this->data['data']->result() as $paq){
			/*
			$q = "select R5.paquete_id, SUM(M.debe)-SUM(M.haber) as saldo, SUM(M.debe_usd)-SUM(M.haber_usd) as saldo_usd
				from bv_movimientos M, bv_reservas R5
				where R5.usuario_id = M.usuario_id and M.tipoUsuario = 'U'
					and R5.estado_id != 5 and R5.paquete_id = ".$paq->id."
					and R5.id = M.reserva_id";
			$datos = $this->db->query($q)->row();

			if($paq->precio_usd)
				$paq->saldoACobrar = isset($datos->saldo_usd) ? $datos->saldo_usd : 0;
			else
				$paq->saldoACobrar = isset($datos->saldo) ? $datos->saldo : 0;
			*/

			//08/11/2018 de cada paquete calculo cuantos adicionales disponibles del total tengo
			$adicionales = $this->Adicional->getByPaquete($paq->id);
			$cant=0; $usados=0;
			foreach ($adicionales as $a) {
				if($a->obligatorio){
					$cant += $a->cantidad;
					$usados += $a->usados;
				}
			}
			$paq->adicionales_total = $cant;
			$paq->adicionales_disponibles = $cant-$usados>0 ? $cant-$usados : 0;

			$paq->llamados_por_hacer = 0;

			$reservas = [];
			foreach ($reservas_paquetes as $res) {
				if ($res->paquete_id == $paq->id) {
					$reservas[] = $res;
				}
			}
			//$reservas = $this->model->getAllByPaquete($paq->id,'','','',$admin_id,'');

			$paq->alarmas = new stdClass;
			foreach($reservas as $re){

				$als = $this->cargar_alarmas_optim($re);

				$paq->alarmas->informes = @$als->informes > @$paq->alarmas->informes ? $als->informes : @$paq->alarmas->informes;
				$paq->alarmas->completar_datos_pax = @$als->completar_datos_pax > @$paq->alarmas->completar_datos_pax ? $als->completar_datos_pax : @$paq->alarmas->completar_datos_pax;
				$paq->alarmas->alerta_no_llamar = @$als->alerta_no_llamar > @$paq->alarmas->alerta_no_llamar ? $als->alerta_no_llamar : @$paq->alarmas->alerta_no_llamar;
				$paq->alarmas->alerta_llamar_pax = @$als->alerta_llamar_pax > @$paq->alarmas->alerta_llamar_pax ? $als->alerta_llamar_pax : @$paq->alarmas->alerta_llamar_pax;
				$paq->alarmas->alerta_reestablecida = @$als->alerta_reestablecida > @$paq->alarmas->alerta_reestablecida ? $als->alerta_reestablecida : @$paq->alarmas->alerta_reestablecida;
				$paq->alarmas->alerta_contestador = @$als->alerta_contestador > @$paq->alarmas->alerta_contestador ? $als->alerta_contestador : @$paq->alarmas->alerta_contestador;
				$paq->alarmas->falta_factura_proveedor = @$als->falta_factura_proveedor > @$paq->alarmas->falta_factura_proveedor ? $als->falta_factura_proveedor : @$paq->alarmas->falta_factura_proveedor;
				$paq->alarmas->faltan_cargar_vouchers = @$als->faltan_cargar_vouchers > @$paq->alarmas->faltan_cargar_vouchers ? $als->faltan_cargar_vouchers : @$paq->alarmas->faltan_cargar_vouchers;
				$paq->alarmas->reservas_a_confirmar = @$paq->a_confirmar;
				$paq->alarmas->alerta_cupos_vencidos = @$als->alerta_cupos_vencidos > @$paq->alarmas->alerta_cupos_vencidos ? $als->alerta_cupos_vencidos : @$paq->alarmas->alerta_cupos_vencidos;
				$paq->alarmas->fecha_limite_pago_completo = @$als->fecha_limite_pago_completo > @$paq->alarmas->fecha_limite_pago_completo ? $als->fecha_limite_pago_completo : @$paq->alarmas->fecha_limite_pago_completo;
				$paq->alarmas->diferencias_rooming = @$als->diferencias_rooming > @$paq->alarmas->diferencias_rooming ? $als->diferencias_rooming : @$paq->alarmas->diferencias_rooming;
			}

			if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
				#pre($paq->alarmas);
			}
		}


		$this->data['totalRows'] = count($this->data['data']->result());
		$this->data['saved'] = isset($_GET['saved'])?$_GET['saved']:'';

		if($activo == 1){
			$this->data['msg_text'] = "Ver reservas finalizadas";
			$this->breadcrumbs[] = 'Vigentes';
			//$this->init();
		}
		else{
			$this->data['msg_text'] = "Ver reservas vigentes";
			$this->breadcrumbs[] = 'Finalizadas';
			//$this->init();
		}

		$this->data['activo'] = $activo;

		if( $this->session->userdata('es_vendedor') )
			$this->data['es_vendedor'] = true;

		$this->load->view('admin/' . $this->page, $this->data);
	}


	function index_sinalarmas(){
		if (isset($_GET['prof'])) {
			$this->output->enable_profiler(TRUE);
		}

		if($this->session->userdata('filter_activo')){
			$activo = 1;
		}
		else{
			$activo = 0;
		}

		/*
		$activo = 1;
		if(isset($_POST['activo'])){
			$activo = $_POST['activo'];
		}
		*/

		$year = '';
		if($activo == 0) $year = date('Y');

		if(isset($_POST['year']) && $_POST['year'] ){
			$year = $_POST['year'];
		}
		$this->data['year'] = $year;

		//listado de paquetes ordenado por codigo de paquete
		if (!$this->session->userdata('sort')) {
			//$this->data['sort'] = "P.codigo";
			//pedido juan 08-08-18 por defecto que sea fecha inicio del viaje
			$this->data['sort'] = "P.fecha_inicio";
			$this->data['sortType'] = "ASC";
			$this->session->set_userdata('sortType', $this->data['sortType']);
			$this->session->set_userdata('sort', $this->data['sort']);
		}
		$this->pconfig['uri_segment'] = '';
		$this->pconfig['per_page'] = 99999;

		/*
		//fix ID porque si no no sale el FERRUGEM JOVEN
		$this->MDestino->filters = "publicado = 1 or id = 35";
		*/
		$this->data['destinos'] = $this->Destino->getAll(999,0,'nombre','asc');

		//dividido en individuales y grupales
		$this->data['destinos_grupales'] = $this->Destino->getGrupales();
		$this->data['destinos_individuales'] = $this->Destino->getIndividuales();


		//filtro de destino
		if(isset($_GET['destino_id']) && $_GET['destino_id'] != ''){
			$this->data['destino_id'] = $_GET['destino_id'];
			$this->db->where('P.destino_id = '.$_GET['destino_id']);
			$this->session->set_userdata('destino_id',$_GET['destino_id']);
		}
		elseif(isset($_GET['destino_id']) && $_GET['destino_id'] == ''){
			$this->session->unset_userdata('destino_id');
		}
		else {
			if($this->session->userdata('destino_id')){
				$this->data['destino_id'] =$this->session->userdata('destino_id');
				$this->db->where('P.destino_id = '.$this->session->userdata('destino_id'));
			}
		}

		//filtro de keyword
		if(isset($this->data['keywords']) && $this->data['keywords'] != ''){
			$this->db->where('P.codigo LIKE "%'.$this->data['keywords'].'%" or
							  P.nombre LIKE "%'.$this->data['keywords'].'%"');
		}

		//filtro por tipo
		if($this->session->userdata('tipo_destino') != ''){
			$tipo = $this->session->userdata('tipo_destino') == 'grupales' ? '0' : '1';
			$this->db->where('D.viaje_individual = '.$tipo);
		}

		$this->data['data'] = $this->model->getAllGroupByPaquetesOptim($activo,$this->data['sort'],$this->data['sortType'],$year,$this->pconfig['per_page'],$this->uri->segment($this->pconfig['uri_segment']));

		/*if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
			echo "<pre>";
			echo $this->db->last_query();
			echo "</pre>";
		}*/

		//si esta logueado un vendedor
		if (esVendedor() && perfil() == 'VENEXT'){
			$admin_id = $this->session->userdata('admin_id');
		}
		else
			$admin_id = '';

		$reservas_paquetes = $this->model->getAllByPaquetesActivosSinAlarmas('','','',$admin_id,'')->result();

		//obtengo saldo a cobrar de cada paquete
		foreach($this->data['data']->result() as $paq){
			/*
			$q = "select R5.paquete_id, SUM(M.debe)-SUM(M.haber) as saldo, SUM(M.debe_usd)-SUM(M.haber_usd) as saldo_usd
				from bv_movimientos M, bv_reservas R5
				where R5.usuario_id = M.usuario_id and M.tipoUsuario = 'U'
					and R5.estado_id != 5 and R5.paquete_id = ".$paq->id."
					and R5.id = M.reserva_id";
			$datos = $this->db->query($q)->row();

			if($paq->precio_usd)
				$paq->saldoACobrar = isset($datos->saldo_usd) ? $datos->saldo_usd : 0;
			else
				$paq->saldoACobrar = isset($datos->saldo) ? $datos->saldo : 0;
			*/

			//08/11/2018 de cada paquete calculo cuantos adicionales disponibles del total tengo
			$adicionales = $this->Adicional->getByPaquete($paq->id);
			$cant=0; $usados=0;
			foreach ($adicionales as $a) {
				if($a->obligatorio){
					$cant += $a->cantidad;
					$usados += $a->usados;
				}
			}
			$paq->adicionales_total = $cant;
			$paq->adicionales_disponibles = $cant-$usados>0 ? $cant-$usados : 0;

			$paq->llamados_por_hacer = 0;

			/*
			$reservas = [];
			foreach ($reservas_paquetes as $res) {
				if ($res->paquete_id == $paq->id) {
					$reservas[] = $res;
				}
			}
			*/
			//$reservas = $this->model->getAllByPaquete($paq->id,'','','',$admin_id,'');

			/******
			//	DESACTIVO ALARMAS
			$paq->alarmas = new stdClass;
			foreach($reservas as $re){

				$als = $this->cargar_alarmas_optim($re);

				$paq->alarmas->informes = @$als->informes > @$paq->alarmas->informes ? $als->informes : @$paq->alarmas->informes;
				$paq->alarmas->completar_datos_pax = @$als->completar_datos_pax > @$paq->alarmas->completar_datos_pax ? $als->completar_datos_pax : @$paq->alarmas->completar_datos_pax;
				$paq->alarmas->alerta_no_llamar = @$als->alerta_no_llamar > @$paq->alarmas->alerta_no_llamar ? $als->alerta_no_llamar : @$paq->alarmas->alerta_no_llamar;
				$paq->alarmas->alerta_llamar_pax = @$als->alerta_llamar_pax > @$paq->alarmas->alerta_llamar_pax ? $als->alerta_llamar_pax : @$paq->alarmas->alerta_llamar_pax;
				$paq->alarmas->alerta_reestablecida = @$als->alerta_reestablecida > @$paq->alarmas->alerta_reestablecida ? $als->alerta_reestablecida : @$paq->alarmas->alerta_reestablecida;
				$paq->alarmas->alerta_contestador = @$als->alerta_contestador > @$paq->alarmas->alerta_contestador ? $als->alerta_contestador : @$paq->alarmas->alerta_contestador;
				$paq->alarmas->falta_factura_proveedor = @$als->falta_factura_proveedor > @$paq->alarmas->falta_factura_proveedor ? $als->falta_factura_proveedor : @$paq->alarmas->falta_factura_proveedor;
				$paq->alarmas->faltan_cargar_vouchers = @$als->faltan_cargar_vouchers > @$paq->alarmas->faltan_cargar_vouchers ? $als->faltan_cargar_vouchers : @$paq->alarmas->faltan_cargar_vouchers;
				$paq->alarmas->reservas_a_confirmar = @$paq->a_confirmar;
				$paq->alarmas->alerta_cupos_vencidos = @$als->alerta_cupos_vencidos > @$paq->alarmas->alerta_cupos_vencidos ? $als->alerta_cupos_vencidos : @$paq->alarmas->alerta_cupos_vencidos;
				$paq->alarmas->fecha_limite_pago_completo = @$als->fecha_limite_pago_completo > @$paq->alarmas->fecha_limite_pago_completo ? $als->fecha_limite_pago_completo : @$paq->alarmas->fecha_limite_pago_completo;
				$paq->alarmas->diferencias_rooming = @$als->diferencias_rooming > @$paq->alarmas->diferencias_rooming ? $als->diferencias_rooming : @$paq->alarmas->diferencias_rooming;
			}
			*****/

			if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
				#pre($paq->alarmas);
			}
		}


		$this->data['totalRows'] = count($this->data['data']);
		$this->data['saved'] = isset($_GET['saved'])?$_GET['saved']:'';

		if($activo == 1){
			$this->data['msg_text'] = "Ver reservas finalizadas";
			$this->breadcrumbs[] = 'Vigentes';
			//$this->init();
		}
		else{
			$this->data['msg_text'] = "Ver reservas vigentes";
			$this->breadcrumbs[] = 'Finalizadas';
			//$this->init();
		}

		$this->data['activo'] = $activo;

		if( $this->session->userdata('es_vendedor') )
			$this->data['es_vendedor'] = true;

		$this->load->view('admin/' . $this->page, $this->data);
	}


	function verad($id=0){
error_reporting(E_ALL);
ini_set('display_errors',1);

		$reserva = $this->model->get($id)->row();
		$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
		$precios = calcular_precios_totales($this->data['combinacion'],array(),@$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);

		//$saldo = $this->model->getSaldoReserva($id);
		pre($precios);
		exit();
		pre($saldo);

		$this->load->model('Factura_model','Factura');
		$this->load->model('Sucursal_model','Sucursal');
		$datos = $this->Factura->obtenerDatos(172,'FA_B',1,75);

		pre($datos);
	}

	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?('Cód. '.$this->data['row']->code):'';

		if($id){
			$reserva = $this->data['row'];
			$reserva->adicionales = adicionales_reserva($reserva);
			$adicionales_reservados = array();
			foreach($reserva->adicionales as $a){
				$adicionales_reservados[] = $a->paquete_adicional_id;
			}
			$reserva->adicionales_reservados = $adicionales_reservados;

			$this->data['row'] = $reserva;

			$this->data['paquete_id'] = $reserva->paquete_id;
			$this->data['paquete'] = $this->Paquete->get($reserva->paquete_id)->row();
			$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();

			$this->data['precios'] = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);

			//datos facturacion (para paso 2)
			$this->Reserva_facturacion->filters = "reserva_id = ".$reserva->id;
			$this->data['facturacion'] = $this->Reserva_facturacion->getAll(1,0,'id','asc')->row();

			//responsable y acompañantes, ordenado por numero de pax asc
			$this->Reserva_pasajero->filters = "bv_reservas_pasajeros.reserva_id = ".$reserva->id;
			$this->data['pasajeros'] = $this->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();

			foreach($this->data['pasajeros'] as $p){
				if($p->responsable){
					$this->breadcrumbs[] = $p->nombre.' '.$p->apellido;
				}
			}

			//acompañantes (para ver cuantos incompletos hay)
			$this->Reserva_pasajero->filters = "bv_reservas_pasajeros.responsable = 0 and bv_reservas_pasajeros.reserva_id = ".$reserva->id;
			$this->data['acompanantes'] = $this->Reserva_pasajero->getAll(999,0,'bv_reservas_pasajeros.numero_pax','asc')->result();

			//informes de pago
			$this->Reserva_informe_pago->filters = "bv_reservas_informes_pago.reserva_id = ".$reserva->id;
			$informes_pago = $this->Reserva_informe_pago->getAll(999,0,'bv_reservas_informes_pago.fecha_pago','desc')->result();

			foreach($informes_pago as $inf){
				/* por cada informe
				trato de definir qué Concepto le corresponde a este informe,
				según los campos completados
				$banco
				$tipo_pago
				$tipo_moneda
				*/
				//armo array de filtros con los datos que machean
				$filtros = 'aplica_a = "haber"';

				//FILTRO BANCO
				$banco = $inf->banco;
				$banco = explode(' ',$banco);
				if(isset($banco[1]) && $banco[1]){
					$filtros .= ' and nombre like "%'.$banco[1].'%"';
				}

				//FILTRO TIPO PAGO
				$tipo_pago = $inf->tipo_pago;
				if(strpos($tipo_pago,'Depósito') !== false){
					$filtros .= ' and nombre like "%deposito%"';
					$filtros .= ' and nombre not like "%transferencia%"';
				}
				if(strpos($tipo_pago,'Transferencia') !== false){
					$filtros .= ' and nombre like "%transferencia%"';
					$filtros .= ' and nombre not like "%deposito%"';
				}

				//FILTRO TIPO MONEDA
				if($inf->tipo_moneda == 'ARS'){
					$filtros .= ' and nombre not like "%USD%"';
				}
				if($inf->tipo_moneda == 'USD'){
					$filtros .= ' and nombre like "%USD%"';
				}

				$concepto = $this->Concepto->getWhere($filtros)->result();

				$concepto = isset($concepto[0]) ? $concepto[0] : false;
				$inf->concepto = $concepto;
			}

			$this->data['informes_pago'] = $informes_pago;

			//cargo adicionales asociados a la reserva
			$this->data['paquete_adicionales'] = $this->Adicional->getByPaquete($reserva->paquete_id);

			$this->cargar_cuenta_corriente($this->data['row']->usuario_id,'U',$reserva->id);

			$this->cargar_historial($reserva->id);

			//para cambio de paquete (combiancion)
			$this->data['paquete_combinaciones'] = $this->Combinacion->getByPaquete($this->data['paquete']->id);

			//cargo los vouchers asociados a la reserva
			$this->data['mis_vouchers'] = $this->Reserva_voucher->getWhere(array('reserva_id' => $id))->result();

			$this->data['mis_paradas'] = $this->Parada->getByPaquete($this->data['paquete']->id);

			//obtengo el dato de la habitación que está asignado independientemente de la reserva original
			$this->data['mi_rooming'] = $this->Paquete_rooming->getDataReserva($reserva->id);

		}
	}

	function onBeforeSave() {
		$id = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : false;
		if($id){
			$reserva_row = $this->model->get($id)->row();
			//seteo esta variable para comparar luego en el onAfterSave
			$_POST['comentario_anterior'] = $reserva_row->comentario;
		}
	}

	function onAfterSave($id) {
		//actualizo datos de pasajeros
		$reserva_row = $this->model->get($id)->row();

		//array para guardar los datos modificados de cada pasajero
		$data_updated = array();

		//pre($_POST);


		//por cada pasajero tomo los datos del form
		for ($i=1;$i<=$reserva_row->pasajeros;$i++) {
			$str_pax = @$_POST['pasajero_'.$i];

			if(isset($str_pax) && $str_pax){
				parse_str($str_pax,$data_pax);

				if(isset($data_pax['fecha_nacimiento']) && $data_pax['fecha_nacimiento'] != ''){
					$nac = explode('/', $data_pax['fecha_nacimiento']);
					if(isset($nac[2]) && $nac[2] && isset($nac[1]) && $nac[1] && isset($nac[0]) && $nac[0]){
						$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
						$data_pax['fecha_nacimiento'] = $nac;
					}
				}
				if(isset($data_pax['fecha_emision']) && $data_pax['fecha_emision'] != ''){
					$nac = explode('/', $data_pax['fecha_emision']);
					if(isset($nac[2]) && $nac[2] && isset($nac[1]) && $nac[1] && isset($nac[0]) && $nac[0]){
						$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
						$data_pax['fecha_emision'] = $nac;
					}
				}
				if(isset($data_pax['fecha_vencimiento']) && $data_pax['fecha_vencimiento'] != ''){
					$nac = explode('/', $data_pax['fecha_vencimiento']);
					if(isset($nac[2]) && $nac[2] && isset($nac[1]) && $nac[1] && isset($nac[0]) && $nac[0]){
						$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
						$data_pax['fecha_vencimiento'] = $nac;
					}
				}

				if(isset($data_pax['pasajero_id']) && $data_pax['pasajero_id']){
					$pax_id = $data_pax['pasajero_id'];
					unset($data_pax['pasajero_id']);//no lo necesito al grabar
					unset($data_pax['pasajero_'.$i]);//no lo necesito al grabar

			//		echo "aca";
			//		pre($data_pax);

					//si modifico algun dato
					if(count($data_pax)){
						$row_pax = $this->Reserva_pasajero->getWhere(array('reserva_id'=>$id,'pasajero_id'=>$pax_id))->row();

				//	echo "row";
				//	pre($row_pax);

						//de cada pasajero por actualizar, comparo con los datos actuales para saber cuáles modificó y lo guardo en los comentarios
						foreach($data_pax as $kk=>$vv){
							if($row_pax->{$kk} != $vv){
								$data_updated[$i][$kk] = $vv;

						//echo "previo";
					//	pre($data_updated);

								if($kk == 'pais_emision_id' && $vv){
									$p = $this->Pais->get($vv)->row();
									$data_updated[$i]['pais_emision'] = $p->nombre;
								}
								if($kk == 'nacionalidad_id' && $vv){
									$p = $this->Pais->get($vv)->row();
									$data_updated[$i]['nacionalidad'] = $p->nombre;
								}

								//si al fecha emision es vacia en DB   el nuevo valor es vacio, no lo marco para udpate
								if($kk == 'fecha_emision' && $row_pax->{$kk} == '0000-00-00' && !$vv){
									unset($data_updated[$i][$kk]);
								}
								if($kk == 'fecha_vencimiento' && $row_pax->{$kk} == '0000-00-00' && !$vv){
									unset($data_updated[$i][$kk]);
								}
								if($kk == 'fecha_nacimiento' && $row_pax->{$kk} == '0000-00-00' && !$vv){
									unset($data_updated[$i][$kk]);
								}
								if($kk == 'pais_emision_id' && !$row_pax->{$kk} && !$vv){
									unset($data_updated[$i][$kk]);
								}
								if($kk == 'nacionalidad_id' && !$row_pax->{$kk} && !$vv){
									unset($data_updated[$i][$kk]);
								}
							}
						}

						//echo "posterior";
					//pre($data_updated);
						//si el array de datos termina estando vacio par ael pax, lo elimino
						if(isset($data_updated[$i]) && !count($data_updated[$i])){
							unset($data_updated[$i]);
						}
					}

					foreach($data_pax as $k=>$v){
						if($v == '' || !$v){
							unset($data_pax[$k]);
						}
					}

					//pre($data_pax);

					//actualizo cambios nuevos
					$this->Pasajero->update($pax_id,$data_pax);
				}
			}
		}

		//pre($data_updated);

		//comparo el dato del lugar de salida elegido
		$parada_elegida = @$_POST['paquete_parada_id'];
		$parada_anterior = @$_POST['paquete_parada_anterior'];
		if($parada_elegida != $parada_anterior){
			//le asigno el dato de que actualizo al pax responsable (1)
			$pe = $this->db->query("select concat(ls.nombre,' , ',p.nombre,' ',pp.hora,'hs') as parada from bv_paradas p
									join bv_paquetes_paradas pp on pp.parada_id = p.id
									left join bv_lugares_salida ls on ls.id = p.lugar_id
									where pp.id = ".$parada_elegida)->row();
			$data_updated['1']['lugar_salida'] = isset($pe->parada) ? $pe->parada : $parada_elegida;
		}

		//16/01/2020 comparo el comentario actual del form con el anterior que esta en base y si es diferente le genero un registro en historial
		$comentario_anterior = @$_POST['comentario_anterior'];
		$comentario = @$_POST['comentario'];
		if($comentario_anterior != $comentario && $comentario){
			registrar_comentario_reserva($reserva_row->id,7,'comentario','Nuevo comentario interno en la reserva.<br>'.$comentario,false,'');

		}

		//si hay datos actualizados
		if(count($data_updated) > 0){

		//echo "actualizo";
		//pre($data_updated);

			//me fijo si es un admin o un vendedor
			if(esVendedor()){
				$user_logged = $this->Vendedor->get(admin_id())->row();
				$texto_historial = 'El vendedor '.$user_logged->nombre.' '.$user_logged->apellido.' actualizó datos de los pasajeros.';
			}
			else{
				$user_logged = $this->Admin->get(admin_id())->row();
				$texto_historial = 'El usuario '.$user_logged->nombre.' actualizó datos de los pasajeros.';
			}

			$data_updated = json_encode($data_updated);

			//registro el update de datos de pax, ($data_updated lo guardo como array)
			//mail de cambio de datos
			$mail = true;
			$template = 'cambios_reserva';
			registrar_comentario_reserva($id,admin_id(),'update_datos_pax',$texto_historial,$mail,$template,false,$data_updated);
		}

		//tomo los datos de facturacion del usuario y los actualizo
		$data_fact = [];
		$data_fact['f_nombre'] = @$_POST['f_nombre'];
		$data_fact['f_apellido'] = @$_POST['f_apellido'];
		$f_fecha_nacimiento = @$_POST['f_fecha_nacimiento'];
		if($f_fecha_nacimiento){
			$aux = explode('/',$f_fecha_nacimiento);
			$f_fecha_nacimiento = $aux[2].'-'.$aux[1].'-'.$aux[0];
			$data_fact['f_fecha_nacimiento'] = $f_fecha_nacimiento;
		}
		$data_fact['f_cuit_prefijo'] = @$_POST['f_cuit_prefijo'];
		$data_fact['f_cuit_numero'] = @$_POST['f_cuit_numero'];
		$data_fact['f_cuit_sufijo'] = @$_POST['f_cuit_sufijo'];
		$data_fact['f_nacionalidad_id'] = @$_POST['f_nacionalidad_id'];
		$data_fact['f_residencia_id'] = @$_POST['f_residencia_id'];
		$data_fact['f_ciudad'] = @$_POST['f_ciudad'];
		$data_fact['f_domicilio'] = @$_POST['f_domicilio'];
		$data_fact['f_numero'] = @$_POST['f_numero'];
		$data_fact['f_depto'] = @$_POST['f_depto'];
		$data_fact['f_cp'] = @$_POST['f_cp'];
		if($id){
			$this->Reserva_facturacion->updateWhere(['reserva_id' => $id],$data_fact);
		}

		//exit();

		if($id && isset($_POST['btnvolver']) && $_POST['btnvolver']){
			redirect($this->data['route'].'/edit/'.$id.'?saved=1');
		}
	}

	function paquete($id,$estado_id=""){
		/*
		//si esta logueado un vendedor
		if ($this->session->userdata('es_vendedor')){
			$admin_id = $this->session->userdata('admin_id');
		}
		else
			$admin_id = '';
		*/

		//si esta logueado un vendedor ó admin tipo vendedor
		$vendedor_id = '';
		if(esVendedor()){
			if(perfil()=='VEN'){
				//11-10-2018 pedido de juan: si es un ADMIN de tipo VENDEDOR, no lo uso, porque muestro todas las reservas de todos los viajes
				$vendedor_id = '';
			}
			else{
				$vendedor_id = userloggedId();
			}
		}

		$this->session->set_userdata('paquete_id',$id);

		if($estado_id!="")
			$this->data['estado_id'] = $estado_id;

		$this->data['paquete_id'] = $id;

		$paquete = $this->Paquete->get($id)->row();

		$this->data['page_title'] = "Listado de Reservas<br>Paquete ".$paquete->codigo." - ".$paquete->nombre;

		//listado de reservas ordenado por fecha de reserva -> las mas nuevas arriba
		if($this->data['sort'] == "id"){
			$this->data['sort'] = "R.fecha_reserva";
			$this->data['sortType'] = "desc";
		}

		//filtro por sucursal
		$this->data['sucursal_id'] = '';
		if(isset($_POST['sucursal_id']) && $_POST['sucursal_id'] ){
			$this->data['sucursal_id'] = $_POST['sucursal_id'];
		}

		$data_primeros = array();
		$data_restantes = array();
		$data = $this->model->getAllByPaquete($id,$estado_id,$this->data['sort'],$this->data['sortType'],$vendedor_id,$this->data['sucursal_id']);

		$this->data['totalRows'] = count($data->result());

		foreach($data->result() as $row){
			$row->tipo_id = $row->usuario_id;
			$row->tipo = 'U';

			$fecha_hora = $row->fecha;
			$fecha_hora = explode(" ",$fecha_hora);
			$row->hora = substr($fecha_hora[1],0,5);
			$row->fecha = date('d/m/Y',strtotime($row->fecha));

			//alarmas de cada reserva
			$row->alarmas = $this->cargar_alarmas_optim($row);
			#pre($row->alarmas);
			// pre($row->alarmas);
			//ordeno primero las que tienen estado POR ACREDITAR
			if($row->estado_id == 14)
				$data_primeros[] = $row;
			else
				$data_restantes[] = $row;
		}

		$data = array_merge($data_primeros,$data_restantes);

		$estados = $this->Reserva_estado->getAll('','');
		$this->data['estados'] = $estados->result();

		$this->data['data'] = $data;

		$this->data['mailings'] = $this->Mailing->getAll(9999,0,'id','desc')->result();

		if($this->session->userdata('envio_mailing_ok')){
			$this->data['envio_mailing_ok'] = 'El envio del mailing se ha realizado correctamente.';
			$this->session->unset_userdata('envio_mailing_ok');
		}

		$this->load->view('admin/reservas_paquetes',$this->data);
	}

	function cargar_alarmas($row){
		$alarmas = cargar_alarmas($row);
		return $alarmas;
	}

	function cargar_alarmas_optim($row){
		$alarmas = cargar_alarmas_optim($row);
		return $alarmas;
	}

	//para exportar csv que Marce necesita informar en AFIP
	//sería solo para pasajeros que viajan al exterior
	function export_csv($id){
		$paquete = $this->Paquete->get($id)->row();

		$query = $this->model->getAllExterior_export($id);

		query_to_csv($query, TRUE, 'paquete-'.$paquete->codigo.'.csv',$separation_line=true);
	}

	//descargar formato MANIFIESTO
	function manifiesto($id){

		$p = $this->Paquete->get($id)->row();
		$this->data['destino'] = $this->Destino->get($p->destino_id)->row();

		$this->data['paquete'] = $p;

		//solo las confirmadas
		$this->db->where('R.estado_id = 4');
		$this->data['pasajeros'] = $this->model->getListaPasajeros($id)->result();

		$this->data['coordinadores'] = $this->Paquete->getCoordinadoresData($id)->result();

		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=manifiesto.xls");

		$this->load->view('admin/manifiesto',$this->data);

	}

	//descarga XLS
	function exportar($id = FALSE, $filename=""){
		ini_set('display_errors',true);
		error_reporting(E_ALL);


		$download_fields = array();

		$estados = isset($_POST['estados']) ? $_POST['estados'] : [];
		unset($_POST['estados']);

		//si esta logueado un vendedor
		if (esVendedor() && perfil() == 'VENEXT'){
			$admin_id = $this->session->userdata('admin_id');
		}
		else{
			$admin_id = '';
			#$results = $this->model->getAllByPaqueteAndVendedor_export($id,$this->session->userdata('admin_id'))->result();
		}

		#else{

			if(isset($_POST) && count($_POST) > 0){
				foreach($_POST as $k=>$v){
					$download_fields[] = $v;

				}
			}

			$filename = "reservas_multiples";
			//si viene un string de IDs separado por -
			if(strpos($id,'-') !== false){
				$id = explode('-', $id);
			} else {
				$paquete = $this->Paquete->getOne($id);
				$filename = $paquete['codigo']." - ".$paquete['nombre'];
			}

			$results = $this->model->getAllByPaquete_export($id, isset($download_fields)?$download_fields:'', false, $admin_id,$estados)->result();

		#}

		$fem = 0;
		$masc = 0;
		foreach($results as $res){
			if(isset($res->sexo) && $res->sexo == 'femenino'){
				$fem+=1;
			}
			if(isset($res->sexo) && $res->sexo == 'masculino'){
				$masc+=1;
			}

			if(isset($res->id) && $res->id){
				//si pide descargar adicionales
				if(in_array('0 as adicionales',$download_fields)) {
					adicionales_reserva($res);
					$res->adicionales = $res->nombre_adicionales;
					unset($res->nombre_adicionales);
				}

				//si pide descargar saldo del viaje
				if(in_array('0 as saldo',$download_fields)) {
					$reserva = $this->Reserva->get($res->id)->row();

					$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();


					$reserva_adicionales = adicionales_reserva($reserva);
					$precios = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],@$this->data['combinacion']->precio_usd?'USD':'ARS',$res->id);
					$saldo_pendiente = $precios['num']['saldo_pendiente'];

					$res->saldo = $saldo_pendiente;
					unset($res->nombre_adicionales);
				}


			}


			unset($res->id);
			unset($res->paquete_id);
			unset($res->co_nombre);
			unset($res->co_apellido);
			unset($res->co_telefono);
		}

		if($fem > 0 || $masc > 0){
			//agrego nueva fila para total de hombres y mujeres
			$results[count($results)] = (object) array (
												   'id_reserva' => ' '
												);

			$results[count($results)+1] = (object) array (
												   'id_reserva' => 'Total mujeres ',
												   'nombre' => $fem
												);

			$results[count($results)+2] = (object) $tot = array (
														   'id_reserva' => 'Total hombres ',
														   'nombre' => $masc
														);

		}

		//DATA DE COORDINADORES
		//en base a los datos seleccionados de usuarios, le sumo los datos de coordinadores
		$mis_coordinadores = $this->Paquete->getCoordinadoresData($id)->result();

		$i=0;

		//agrego nueva fila para separar los totales de los coordinadores
		$results[count($results)] = (object) array (
												   'id_reserva' => ' '
												);

		foreach ($mis_coordinadores as $c) {
			$i++;

			$cord = new stdClass();
			$cord->id_usuario = $c->id;
			//si pidio el apellido
			if(in_array('PA.apellido',$download_fields)){
				$cord->apellido = $c->apellido;
			}

			//si pidio el nombre
			if(in_array('PA.nombre',$download_fields)){
				$cord->nombre = $c->nombre;
			}
			//si pidio el telefono
			if(in_array('concat(PA.celular_codigo,PA.celular_numero) as celular',$download_fields)){
				$cord->celular = @$c->telefono;
			}
			//si pidio el email
			if(in_array('PA.email',$download_fields)){
				$cord->email = @$c->email;
			}
			//si pidio el dni
			if(in_array('PA.dni as dni_numero',$download_fields)){
				$cord->dni = @$c->dni;
			}
			if(in_array('PA.pasaporte as pasaporte_numero',$download_fields)){
				$cord->pasaporte = @$c->pasaporte;
			}
			if(in_array('PA.fecha_emision as pasaporte_emision',$download_fields)){
				$cord->fecha_emision = @$c->fecha_emision;
			}
			if(in_array('PA.fecha_vencimiento as pasaporte_vencimiento',$download_fields)){
				$cord->fecha_vencimiento = @$c->fecha_vencimiento;
			}
			if(in_array('PA.fecha_nacimiento as nacimiento',$download_fields)){
				$cord->nacimiento = @$c->fechaNacimiento;
			}
			if(in_array('PI.nombre as nacionalidad',$download_fields)){
				$cord->nacionalidad = @$c->nacionalidad;
			}
			if(in_array('PA.dieta as menu',$download_fields)){
				$cord->menu = @$c->dieta;
			}
			if(in_array('PA.sexo',$download_fields)){
				$cord->sexo = @$c->sexo;
			}
			if(in_array('PA.emergencia_nombre',$download_fields)){
				$cord->emergencia_nombre = @$c->emergencia_nombre;
			}
			if(in_array('PA.emergencia_telefono_codigo',$download_fields)){
				$cord->emergencia_telefono_codigo = @$c->emergencia_telefono_codigo;
			}
			if(in_array('PA.emergencia_telefono_numero',$download_fields)){
				$cord->emergencia_telefono_numero = @$c->emergencia_telefono_numero;
			}


			//pongo al final el dato de cada coordinador
			$results[count($results)+$i] = $cord;
		}

		return parent::exportar($results, $filename);
	}

	function generarCupon($reserva_id){
		$reserva = $this->Reserva->get($reserva_id)->row();

		if ($reserva->estado_id == 5) {
			echo "El envío del mail no se realizó ya que la reserva se encuentra anulada.";
		}
		else{
			//genero registro en el historial para que luego se le envie mail de reserva
			$mail = true;
			$template = 'mail_confirmacion';
			registrar_comentario_reserva($reserva_id,7,'envio_mail','Envio de email por datos de reserva al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);

			echo "El envío del mail al pasajero ha sido agendado. En breve será enviado.";
		}
	}

	/*Envia mail de voucher de pago completo manualmente*/
	function generar_voucher_pago($reserva_id){
		$reserva = $this->Reserva->get($reserva_id)->row();

		if ($reserva->estado_id == 5) {
			echo "El envío del mail no se realizó ya que la reserva se encuentra anulada.";
		}
		else{
			//$saldo = $this->Reserva->getSaldoReserva($reserva->id);
			//$saldo_pendiente = ($reserva->precio_usd) ? $saldo->saldo_usd : $saldo->saldo;
			$row = $reserva;
			$combinacion = $this->Combinacion->get($row->combinacion_id)->row();
			$reserva_adicionales = adicionales_reserva($row);
			$precios = calcular_precios_totales($combinacion,$this->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$row->id);
			$saldo_pendiente = $precios['num']['saldo_pendiente'];

			//solo enviar mail si no tiene saldo pendiente
			if( $saldo_pendiente == 0 ){
				//genero registro en el historial para que luego se le envie mail de reserva
				$mail = true;
				$template = 'pago_completo';
				registrar_comentario_reserva($reserva_id,7,'envio_mail','Envio de email por pago completo de reserva. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);

				$paquete = $this->Paquete->get($reserva->paquete_id)->row();
				//envio tambien el mail de voucher, si el operador es bv porque sale automatico
				//if( $paquete->operador_id == 1 ){

				//06-03-19 EL envío del voucher ahora estaá definido por paquete
				if( $paquete->voucher_automatico ){
					$mail = true;
					$template = 'voucher';
					registrar_comentario_reserva($reserva_id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
				}
				else{
					//if( $paquete->operador_id > 1){
					//06-03-19 EL envío del voucher ahora estaá definido por paquete
					if( !$paquete->voucher_automatico ){
						//si es un envio manual, voy a enviar el mail de vouchers si hay algun voucher cargado.
						$mis_vouchers = $this->Reserva_voucher->getWhere(array('reserva_id' => $reserva->id))->result();

						if(count($mis_vouchers)){
							$mail = true;
							$template = 'voucher';
							registrar_comentario_reserva($reserva_id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
						}
					}
				}

				echo "El envío del mail al pasajero ha sido agendado. En breve será enviado.";
			}
			else{
				echo "El envío del mail no se realizó ya que el pasajero aún no abonó la totalidad del viaje.";
			}
		}

	}

	function form_cambiar_estado($reserva_id,$id){
		$this->data['reserva'] = $this->Reserva->get($reserva_id)->row();
		$this->data['paquete'] = $this->Paquete->get($id)->row();

		//solo traigo estados nueva y anulada para cambiar manualmente (sin el estado actual)
		if($this->data['reserva']->estado_id == 1){
			$this->Reserva_estado->filters = "id in (5)";
		}
		else if($this->data['reserva']->estado_id == 5){
			$this->Reserva_estado->filters = "id in (1)";
		}
		else{
			$this->Reserva_estado->filters = "id in (1,5)";
		}

		$this->data['estados'] = $this->Reserva_estado->getAll(999,0,'nombre','asc')->result();

		$ret['view'] = $this->load->view('admin/reservas_cambiar_estado',$this->data,true);
		echo json_encode($ret);
	}

	/*metodo que recibe por post la fecha de cancelacion para ver si corresponde calcular penalidad*/
	function verificar_penalidad(){
		extract($_POST);

		$this->data['reserva'] = $this->Reserva->get($reserva_id)->row();
		$this->data['paquete'] = $this->Paquete->get($this->data['reserva']->paquete_id)->row();

		//si la va a ANULAR, chequeo si corresponde una penalidad al pasajero
		verificar_penalidad($this->data['paquete'],$this->data['reserva'],@$fecha_baja);

		$ret = [];
		$ret['view'] = $this->load->view('admin/reservas_penalidad_detalle',$this->data,true);
		echo json_encode($ret);
	}

	function cambiar_estado($res_id,$estado_id,$return=false){
		error_reporting(E_ALL);
		ini_set('display_errors', true);

		if(!$estado_id){
			redirect(base_url().'admin/reservas/edit/'.$res_id);
		}

		if($estado_id == 1){
			//si la quiere pasar a nueva y NO Hay cupo
			$reserva = $this->Reserva_model->get($res_id)->row();
			if (!$reserva) {
				return;
			}

			$paquete = $this->Paquete->get($reserva->paquete_id)->row();
			$orden = $this->Orden->get($reserva->orden_id)->row();
			$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();

			verificar_cupo($combinacion,$orden,$paquete,$reserva);

			//si hay cupo en el viaje para los pax, habilito la confirmacion
			if(
				($paquete->cupo_paquete_personalizado && $paquete->cupo_paquete_disponible_real < $reserva->pasajeros)
				|| (!$paquete->cupo_paquete_personalizado && $paquete->cupo_disponible < $reserva->pasajeros)
				|| @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo_sin_cupo']
			){
				//no hay cupo para pasar de estado
				//redirect(site_url('admin/reservas/edit/'.$res_id.'?conf=sincupo'));
				$ret = array();
				$ret['error'] = 'sin_cupo';

				if($return){
					return $ret;
				}
				else{
					echo json_encode($ret);
					exit();
				}
			}



		}

		$data = array("estado_id"=>$estado_id);

		// si la pasa a nueva pongo nueva fecha de limite pago minimo para que no se anule
		if($estado_id == 1){
			$fecha_limite_pago_min = new DateTime(date('Y-m-d H:i:s'));
			$fecha_limite_pago_min->add(new DateInterval('PT'.$this->settings->horas_pago_min.'H'));

			//Pongo el estado en NUEVA
			$data['fecha_limite_pago_min'] = $fecha_limite_pago_min->format('Y-m-d H:i:s');
		}

		//obtengo estado de reserva antes de update
		$reserva = $this->Reserva->get($res_id)->row();

		$this->Reserva->update($res_id,$data);

		$this->actualizarEstado($res_id,$estado_id,$reserva->estado_id);

		$ret = array();
		$ret['status'] = 'ok';

		if($return)
			return $ret;
		else
			echo json_encode($ret);


	}

	function actualizarEstado($id,$estado_id,$estado_ant){
		$estado = $this->Reserva_estado->get($estado_id)->row();

		//registro el cambio de estado
		registrar_comentario_reserva($id,admin_id(),'cambio_estado','Cambio de estado de reserva manualmente. Estado '.$estado->nombre);

		$reserva = $this->model->get($id)->row();

		$paquete = $this->Paquete->get($reserva->paquete_id)->row();

		//$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
		//precio de la reserva segun el total de la combinacion y los adicionales
		$monto_reserva = $reserva->pasajeros*($reserva->v_total)+$reserva->adicionales_precio;

		if(isset($paquete->impuesto_pais) && $paquete->impuesto_pais && $paquete->exterior){
			//el precio del impuesto se lo sumo solo para las reservas posteriores a la fecha de vigencia de la ley
			if($this->config->item('vigencia_impuesto_pais') <= $reserva->fecha_reserva){
				$monto_reserva += $reserva->pasajeros*$paquete->impuesto_pais;
			}
		}

		$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
		$precios = calcular_precios_totales($this->data['combinacion'],array(),@$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);

		//actualizo en el reg de la reserva el valor en base a la combinacion
		$datares = array();
		$datares['paquete_precio'] = $precios['num']['precio_bruto'];
		$datares['impuestos'] = $precios['num']['precio_impuestos'];
		$this->model->update($id,$datares);

		$usuario = $this->Usuario->get($reserva->usuario_id)->row();

		//si la reserva pasa a estado ANULADA manualmente
		if($estado_ant!= 5 && $estado_id == 5){

			//primero la penalidad
			//acá chequeo si le registro la penalidad
			//10-09-18 si fue anulada manualmente, chequeo si le confirma la aplicacion de penalidad
			if(isset($_POST['aplica_penalidad']) && $_POST['aplica_penalidad']=='SI'){
				//registro la aplicacion de penalidad
				$fecha_baja = (isset($_POST['fecha_baja']) && $_POST['fecha_baja']) ? $_POST['fecha_baja'] : '';
				verificar_penalidad($paquete,$reserva,$fecha_baja);

				if($this->data['penalidad_paquete'] && $this->data['penalidad_tope']){
					//el monto de penalidad es el TOPE
					$penalidad = $this->data['penalidad_tope'];

					//si hay algun monto de penalidad para registrar
					if($estado_ant != 13 && $estado_ant != 12){
						//si es lista de espera o a confirmar, no lo registro ??
						registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,date('Y-m-d H:i:s'),"PENALIDAD POR ANULACION",$penalidad,0.00,false,'',isset($comprobante)?$comprobante:'','','','','',$reserva->precio_usd?'USD':'ARS',$reserva->cotizacion?$reserva->cotizacion:$this->settings->cotizacion_dolar,false,false,false);

					}

					registrar_comentario_reserva($id,admin_id(),'penalidad','Penalidad aplicada por anulación manual. DEBE: '.$penalidad,false,false,$ref_id=false);

				}
			}

			$mov5 = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();
			$nuevo_parcial5 = isset($mov5->parcial) ? $mov5->parcial : 0.00;

			//si la reserva estaba en A CONFIRMAR ó en LISTA ESPERA y se ANULA -> no le genero el movimiento en cuenta corriente ya que la reserva
			//cuando se dio de alta desde el sitio NO se le registro el movimiento en su cuenta
			if($estado_ant != 13 && $estado_ant != 12){
				registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,date('Y-m-d H:i:s'),"ANULACION RESERVA",0.00,$monto_reserva,$nuevo_parcial5,'',isset($comprobante)?$comprobante:'','','','','',$reserva->precio_usd?'USD':'ARS',$reserva->cotizacion?$reserva->cotizacion:$this->settings->cotizacion_dolar,false,false,false);
			}

			//Registrar el envio de mail de reserva anulada manualmente
			$mail = true;
			$template = 'reserva_anulada_manual';
			registrar_comentario_reserva($id,admin_id(),'anulacion',isset($_POST['motivo'])?$_POST['motivo']:'Reserva anulada manualmente',$mail,$template,$ref_id=false);

			verificar_costo_operador($reserva);

			//se agrega envio de mail al usuario cuando se cancela manualmente
			$this->data['reserva'] = $reserva;


			//genero movimientos de quita de adicionales si corresponde
			$find = array('m.cta_usd'=>($reserva->precio_usd?1:0),'m.usuario_id'=>$reserva->usuario_id,'m.tipoUsuario' => 'U','m.reserva_id' => $reserva->id);
			$movs = $this->Movimiento->getAdicionalesSinAnular($find);
			foreach ($movs as $m) {
				//por cada movimiento le genero el de anulacion de dicho adicional
				//3er parametro para que me devuelva el dato acá
				$this->eliminar_adicional($m->reserva_id,$m->paquete_adicional_id,$return=true);
			}

			//27-08-18 si el viaje es grupal, y estoy anulando una reserva de ese grupo, a todas las del mismo las tengo que reasignar a compartida
			if($paquete->grupal){
				$this->asignar_compartida($id);
			}

			//si se cancela manualmente, y si hay reservas en lista de espera para este viaje, les envio mail, hasta uno por dia
			enviar_lista_espera($reserva->id);
		}
		else if(
				( ($estado_ant == 12 || $estado_ant == 13 || $estado_ant == 5) && $estado_id == 1 )
				|| ($estado_ant == 5 && $estado_id == 4)
			){
			//si estaba LISTA DE ESPERA, POR ACREDITAR ó ANULADA y pasa a estado nueva genero los mov correspondientes

			//ó si pasa a CONFIRMADA manualmente estando antes ANULADA, le genero el movimiento de reserva nueva

			//genero movimiento en cta cte de ese usuario
			$reserva_id = $reserva->id;
			$usuario_id = $reserva->usuario_id;
			$fecha_reserva = date('Y-m-d H:i:s');

			$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($usuario_id,"U",$reserva->precio_usd)->row();
			$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;

			//globales
			$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
			$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
			$tipo_cambio = $this->settings->cotizacion_dolar;

			registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

			//tambien seteo marcas en el historial para que luego se le envie mail de reserva
			$mail = true;
			$template = 'datos_reserva';

			//si la reserva estaba anulada y la pasa a NUEVA
			$tipo_accion = 'movimiento_cta_cte';
			if($estado_ant == 5 && $estado_id == 1){
				$tipo_accion = 'nueva_reserva';
			}

			registrar_comentario_reserva($reserva_id,7,$tipo_accion,'Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);

			//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
			$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();
			$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
			registrar_movimiento_cta_cte(1,"A",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
			registrar_comentario_reserva($reserva_id,7,$tipo_accion,'Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
			//-------------------------------------------------------------------------------------------------------------------

			//por pedido de Juan, si luego de pasarla de ANULADA a NUEVA y detecto que hay pagos hechos sobre la reserva, la paso a CONFIRMADA
			$mov = $this->Movimiento->getPagosHechos($reserva->id,$reserva->usuario_id,$reserva->precio_usd,$reserva->precio_usd)->row();
			$pagos_hechos = $mov->pago_hecho>0?$mov->pago_hecho:0.00;

			$this->data['combinacion'] = $this->Combinacion->get($reserva->combinacion_id)->row();
			$reserva_adicionales = adicionales_reserva($reserva);
			$precios = calcular_precios_totales($this->data['combinacion'],$this->data['adicionales_valores'],@$this->data['combinacion']->precio_usd?'USD':'ARS',$reserva->id);
			$pagos_hechos = $precios['num']['monto_abonado'];

			if($pagos_hechos > 0){
				$this->Reserva->update($reserva->id,array('estado_id' => 4));

				$mail = false;
				$template = '';
				registrar_comentario_reserva($reserva->id,7,'cambio_estado','Reserva confirmada luego de cambio de estado manual y pagos hechos detectados: '.$pagos_hechos,$mail,$template,false);

				//chequeo si en la cuenta del operador ya registré o no el costo de este viaje
				registrar_costo_operador($reserva,$fecha_reserva,$tipo_cambio,$informe_id=false);
			}
			/*if($pagos_hechos > 0){
				$this->Reserva->update($reserva->id,array('estado_id' => 4));
			}*/
		}

		//23-08-18 llamo a esta funcion para actualizar cupos de paquetes si hace falta por algun cambio de estado.
		actualizar_cupos();

		return 1;
	}


	//metodo que se ejecuta cuando desde el form de reservas ó listado de reserva de paquetes
	//se intenta pasar a NUEVA una reserva
	function chequear_disponibilidad_paquete($paquete_id,$reserva_id=''){
		//echo chequear_disponibilidad_paquete($paquete_id,$reserva_id);
		echo true;
	}

	//03-09-2014 se usa esta que es generica para todos los adicionales en lugar de la de arriba
	function chequear_disponibilidad_adicionales($paq_id,$res_id=''){
		//echo chequear_disponibilidad_adicionales($paq_id,$res_id, isset($_POST) ? $_POST : '');
		echo true;
	}


	function saldo(){
		echo enviar_informe_pago_recibido($reserva_id=3,$informe_id=5);
	}

	//Poner la reserva en estado de POR ACREDITAR, para que no la tome el proceso de update de reservas
	//en realidad ahora solo se cambia la fecha extendida pero no cambia de estado
	function por_acreditar($reserva_id) {
		extract($_POST);

		$fecha = isset($fecha) ? $fecha : date('Y-m-d');
		$hora = isset($hora) ? $hora : date('H:i');

		$fecha = explode('/',$fecha);
		$fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
		$fecha = $fecha.' '.$hora.':00';

		$reserva = $this->Reserva_model->get($reserva_id)->row();
		if (!$reserva) {
			return;
		}

		//cambio estado de reserva
		//'estado_id' => 14,
		$this->Reserva->update($reserva_id,array('fecha_extendida' => $fecha));

		//registrar_comentario_reserva($reserva_id,7,'cambio_estado','Cambio de estado. La reserva está a POR ACREDITAR: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | FECHA EXTENDIDA: '.$fecha);

		registrar_comentario_reserva($reserva_id,7,'comentario','Reserva con fecha extendida. | FECHA EXTENDIDA: '.$fecha);

		echo 1;
	}

	//Confirma una reserva en estado "a confirmar" o lista espera generando los movimientos correspondientes
	function confirmar_disponibilidad($reserva_id) {
		$reserva = $this->Reserva_model->get($reserva_id)->row();
		if (!$reserva) {
			return;
		}

		//24-08-18 solo confirma disponibilidad si el estado es LISTA ESPERA o A CONFIRMAR
		if (isset($reserva->id) && $reserva->id && $reserva->estado_id != 13 && $reserva->estado_id != 12) {
			redirect(site_url('admin/reservas/edit/'.$reserva_id));
		}

		$paquete = $this->Paquete->get($reserva->paquete_id)->row();
		$orden = $this->Orden->get($reserva->orden_id)->row();
		$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();

		verificar_cupo($combinacion,$orden,$paquete,$reserva);

		//si hay cupo en el viaje para los pax, habilito la confirmacion
		if(
			($paquete->cupo_paquete_personalizado && $paquete->cupo_paquete_disponible_real < $reserva->pasajeros)
			|| (!$paquete->cupo_paquete_personalizado && $paquete->cupo_disponible < $reserva->pasajeros)
			|| $this->data['habitacion_sin_cupo'] || $this->data['transporte_sin_cupo_sin_cupo']
		){
			//no hay cupo para pasar de estado
			redirect(site_url('admin/reservas/edit/'.$reserva_id.'?conf=sincupo'));
		}

		//Calcular la fecha de limite de pago minimo
		$fecha_limite_pago_min = new DateTime(date('Y-m-d H:i:s'));
		$fecha_limite_pago_min->add(new DateInterval('PT'.$this->settings->horas_pago_min.'H'));

		//Pongo el estado en NUEVA
		$data = array("estado_id" => 1, "fecha_limite_pago_min" => $fecha_limite_pago_min->format('Y-m-d H:i:s'));

		//chequeo si el precio del viaje cambió, entonces le cargo el nuevo valor a la reserva
		$precio_bruto = $reserva->pasajeros*($combinacion->v_exento+$combinacion->v_nogravado+$combinacion->v_comision+$combinacion->v_gravado21+$combinacion->v_gravado10+$combinacion->v_gastos_admin+$combinacion->v_rgafip);
		$precio_impuestos = $reserva->pasajeros*($combinacion->v_iva21+$combinacion->v_iva10+$combinacion->v_otros_imp);

		if(isset($paquete->impuesto_pais) && $paquete->impuesto_pais && $paquete->exterior){
			//el precio del impuesto se lo sumo solo para las reservas posteriores a la fecha de vigencia de la ley
			if($this->config->item('vigencia_impuesto_pais') <= $reserva->fecha_reserva){
				$precio_impuestos += $reserva->pasajeros*$paquete->impuesto_pais;
			}
		}

		$data['paquete_precio'] = $precio_bruto;
		$data['impuestos'] = $precio_impuestos;

		//obtengo adicionales contratados para recalcular el nuevo total si tuvo modificaciones
		$adicionales = $this->Reserva->getAdicionales($reserva_id);
		$total_adicionales = 0;
		if(count($adicionales)){
			foreach($adicionales as $adicional){
				$total_adicional = $adicional->v_total ? $adicional->v_total : 0.00;
				$total_adicional = $total_adicional*$reserva->pasajeros;

				$total_adicionales += $total_adicional;
			}
		}
		$data['adicionales_precio'] = $total_adicionales;

		$this->Reserva->update($reserva_id, $data);

		//vuelvo a obtener los datos con los valores actualizados
		$reserva = $this->Reserva_model->get($reserva_id)->row();

		//genero movimiento en cta cte de USUARIO por monto de reserva
		$this->load->model('Movimiento_model', 'Movimiento');
		$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();

		$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;
		$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;

		//globales
		$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
		$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
		$tipo_cambio = $this->settings->cotizacion_dolar;

		registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id, date('Y-m-d H:i:s'), $reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

		//tambien seteo marcas en el historial para que luego se le envie mail de reserva
		$mail = true;
		$template = 'datos_reserva';
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);

		//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
		$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();
		$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
		registrar_movimiento_cta_cte(1,"A",$reserva_id, date('Y-m-d H:i:s'), $reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda='ARS',$tipo_cambio);

		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
		//-------------------------------------------------------------------------------------------------------------------

		//23-08-18 llamo al metodo que actualiza manualmente los cupos luego de confirmar dispo de reserva
		actualizar_cupos();

		redirect(site_url('admin/reservas/edit/'.$reserva_id));
	}

	function enviar_vouchers($id){
		$reserva = $this->Reserva->get($id)->row();

		//chequeo si tiene el monto del viaje abonado completo
		$ret['status'] = 'success';

		$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();
		$reserva_adicionales = adicionales_reserva($reserva);
		$precios = calcular_precios_totales($combinacion,$this->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$reserva->id);
		$saldo_pendiente = $precios['num']['saldo_pendiente'];


		if($saldo_pendiente > 0){
			$ret['status'] = 'error';
			$ret['msg'] = 'No puedes enviar el mail ya que el pasajero aún no abonó la totalidad del viaje.<br>La reserva tiene un saldo pendiente de pago de '.($combinacion->precio_usd?'USD ':'ARS ').$saldo_pendiente;
		}
		else{
			$mail = true;
			$template = 'voucher';
			registrar_comentario_reserva($reserva->id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
		}

		echo json_encode($ret);
	}

	function borrar_voucher($id){
		$vou = $this->Reserva_voucher->get($id)->row();

		if($vou->archivo && file_exists('./uploads/reservas/'.$vou->reserva_id.'/'.$vou->archivo)){
			unlink('./uploads/reservas/'.$vou->reserva_id.'/'.$vou->archivo);
		}

		$this->Reserva_voucher->delete($id);
		echo true;
	}

	function grabar_voucher(){
		extract($_POST);

		$this->uploadsFolder = './uploads/';
		$this->uploadsMIME = 'doc|docx|gif|jpg|jpeg|png|swf|rar|zip|pdf';

		$ret['status'] = 'error';
		$ret['msg'] = 'No se pudos subi el archivo.<br>Formatos admitidos: '.str_replace('|',', ',$this->uploadsMIME);

		//File uploads
		if (count($_FILES)>0) {
			$this->uploads = array(
								array(
									'name' => 'file_voucher',
									'allowed_types' => $this->uploadsMIME,
									'maxsize' => '10000000',
									'folder' => '/uploads/reservas/'
								)
							);

			$config['upload_path'] = $this->uploadsFolder;
			$config['allowed_types'] = $this->uploadsMIME;
			$config['max_size']	= '10000000';
			$this->load->library('upload', $config);

			//carpeta uploads
			if(!file_exists($this->uploadsFolder))
				mkdir($this->uploadsFolder,0777);

			$name_comprobante = false;
			$timestamp = date('Y-m-d H:i:s');
			$strtime = strtotime($timestamp);

			foreach ($this->uploads as $upload) {
				$config['allowed_types'] = $upload['allowed_types'];
				$config['max_size']	= $upload['maxsize'];
				$config['upload_path'] = "." . $upload['folder'].$id.'/';

				//1er nivel dentro de carpeta uploads
				if(!file_exists(".".$upload['folder']))
					mkdir("." . $upload['folder'],0777);

				//1er nivel dentro de carpeta uploads
				if(!file_exists(".".$upload['folder'].'/'.$id))
					mkdir("." . $upload['folder'].'/'.$id,0777);

				$this->upload->initialize($config);

				if ($this->upload->do_upload($upload['name'])) {
					$data = $this->upload->data();
					//$data['file_name']

					//renombro archivo por las dudas
					$aux = $data['file_name'];
					$aux = str_replace('%', '', $aux);
					$newname = $aux;

					copy("." . $upload['folder'].'/'.$id.'/'.$data['file_name'],"." . $upload['folder'].'/'.$id.'/'.$newname);

					if($data['file_name'] != $newname){
						unlink("." . $upload['folder'].'/'.$id.'/'.$data['file_name']);
					}

					$upd = array();
					$upd['reserva_id'] = $id;
					$upd['archivo'] = $aux;
					//$upd['archivo'] = $data['file_name'];
					$upd['timestamp'] = date('Y-m-d H:i:s');
					$upd['ip'] = $_SERVER['REMOTE_ADDR'];
					$v_id = $this->Reserva_voucher->insert($upd);

					$vou = $this->Reserva_voucher->get($v_id)->row();

					$ret['status'] = 'success';
					$ret['row'] = vouchers_row($vou);
				}
				else{
					$ret['text'] = $this->upload->display_errors();
					$ret['msg'] = 'No se pudo subir el archivo.<br>Formatos admitidos: '.str_replace('|',', ',$this->uploadsMIME);
				}
			}

		}

		echo json_encode($ret);
	}

	/* historial de reservas */
	function cargar_historial($reserva_id){
		//datos de la reserva
		$reserva = $this->Reserva->get($reserva_id)->row();
		$this->data['reserva'] = $reserva;

		//cargo comentarios de la reserva
		//le saco los registros que tengan buenas vibras en comentario para que no figuren en el historial de la reserva del usuario
		$this->Comentario->filters = "comentarios not like '%buenas vibras%' and reserva_id = ".$reserva_id." and tipo_id != 19";

		//tipo != 'registro_costo_operador'";

		//si filtra por tipo_id -> aplico en busqueda
		if(isset($_POST['tipo_id']) && $_POST['tipo_id'] != ''){
			$this->Comentario->filters .= " and tipo_id = '".$_POST['tipo_id']."'";
		}

		$historial = $this->Comentario->getAll(9999,0,'fecha','desc','');
		//echo $this->db->last_query();
		$this->data['historial'] = $historial;

		$this->data['tipos_acciones'] = $this->Comentario_tipo->getAll(99,0,'nombre','asc')->result();

		$this->Comentario_tipo->filters = "editable = 1";
		$this->data['tipos_acciones_editables'] = $this->Comentario_tipo->getAll(99,0,'nombre','asc')->result();

		$this->data['historial_reserva'] = $this->load->view('admin/reservas_historial',$this->data,true);
	}

	//ok
	function grabar_historial(){
		extract($_POST);

		$reserva_id = $id;
		$tipo_id = @$tipo_accion_id;

		$reserva = $this->Reserva->get($reserva_id)->row();

		$fecha = date("Y-m-d H:i:s");

		$data = array(
					"reserva_id" => $reserva_id,
					"admin_id" => $this->session->userdata('admin_id'),
					"fecha" => $fecha,
					"tipo_id" => $tipo_id,
					"comentarios" => $comentarios
			);
		$this->Comentario->insert($data);

		$ret = array();
		$ret['status'] = 'success';
		$ret['redirect'] = base_url().'admin/reservas/edit/'.$reserva_id.'?tab=historial&nuevoregistro=1';
		echo json_encode($ret);
	}

	//devuelve la ruta a usar para el cambio de combinacion
	function cambiar_combinacion($reserva_id){
		$reserva = $this->Reserva->get($reserva_id)->row();

		echo base_url().'admin/reservas/combinaciones_disponibles/'.$reserva->paquete_id.'/'.$reserva_id.'/'.$reserva->combinacion_id;
	}

	/*
	Devuelve las combinaciones de paquetes disponibles para la reserva
	*/
	function combinaciones_disponibles($id,$reserva_id,$comb_id){
		$this->data['reserva'] = $this->Reserva->get($reserva_id)->row();
		$this->data['paquete'] = $this->Paquete->get($id)->row();

		//filtros los q tengan la misma cantidad de pasajeros q no esten agotadas (disponibles = 1)
		$filtros = array();
		$filtros['pax'] = $this->data['reserva']->pasajeros;
		$filtros['not_combinacion_id'] = $comb_id;//que no traiga la combinacion actual

		//si la reserva es de un grupo, entonces genero filtro para este query
		if($this->data['reserva']->codigo_grupo){
			$filtros['reserva_grupal'] = true;
			//pax: es por la cantidad de pasajeros del grupo
			$sum = $this->model->getReservasActivasGrupo($this->data['reserva']->codigo_grupo,true);

			if($sum->cantidad){
				$filtros['pax'] = $sum->cantidad;
			}
		}

		//si viene el ID de cupo de habitacion elegida, obtengo a que habitacion pertenece y lo uso para filtrar las combinaciones
		if(isset($_POST['hab_id'])){
			if($_POST['hab_id']){
				//si no es vacia

				if($_POST['hab_id'] == '-1'){
					//si es -1, va a compartida
					$filtros['habitacion'] = 99;
				}
				else{
					//privada
					$this->data['hab_id'] = $_POST['hab_id'];
					$hab_cupo = $this->Fecha_alojamiento_cupo->getAsociacionHabitacion($this->data['hab_id']);

					if(isset($hab_cupo->habitacion_id) && $hab_cupo->habitacion_id){
						$filtros['habitacion'] = $hab_cupo->habitacion_id;
					}
				}
			}
			else{
				//si es vacio no aplico filtro, viene dle form de reserva
			}
		}

		//nuevo filtro para traer las combinaciones disponibles que me de el cupo de alojamiento
		$filtros['cupo_alojam'] = true;

		//$filtros['disponibles'] = '1';
		$combinaciones = $this->Combinacion->getByPaquete($id,9999,$filtros);

		if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
		//	print_r($filtros);
		//	echo $this->db->last_query();

		}

		//con las combinaciones del pauqete me fijo las que me alcance el cupo de alojamiento y transporte segun la cantidad de pax
		//si hay alguna combinacion donde el cupo de transporte o alojamiento no me alcance, no la considero
		$final = array();
		foreach($combinaciones as $c){
			/*if($c->cupo_aloj < $this->data['reserva']->pasajeros || $c->cupo_trans < $this->data['reserva']->pasajeros){
				unset($c);
			}*/
			if($c->cupo_aloj >= $this->data['reserva']->pasajeros && $c->cupo_trans >= $this->data['reserva']->pasajeros){
					$final[] = $c;
			}
		}

		$this->data['combinaciones'] = $final;
		$this->data['nro_hab'] = (isset($_POST['nro_hab']) && $_POST['nro_hab']) ? $_POST['nro_hab'] : '';

		$ret['view'] = $this->load->view('admin/paquetes-cambiar',$this->data,true);
		echo json_encode($ret);
	}

	/*
	Determina si es posible hacer el cambio de combinacion para la reserva, chequeando cupos
	*/
	function elegirCombinacion($data_post=false){

		elegir_combinacion($data_post=false);

	}

	/*
	Borra el informe de pago, x si se generó duplicado
	*/
	function borrar_informe_pago($id){
		$vou = $this->Reserva_informe_pago->get($id)->row();

		if($vou->archivo && file_exists('./uploads/informes_pago/'.$vou->comprobante)){
			unlink('./uploads/informes_pago/'.$vou->comprobante);
		}

		$this->Reserva_informe_pago->delete($id);
		echo true;
	}

	/*
	elimina el adicional de la reserva
	*/
	function eliminar_adicional($reserva_id,$paquete_adicional_id,$return=false){
		if($reserva_id && $paquete_adicional_id){
			//obtengo data del adicional
			$adicional = $this->Adicional->getAsociacionPaquete($paquete_adicional_id);

			//data de reserva
			$reserva = $this->Reserva->get($reserva_id)->row();

			$valor_adicional = $adicional->v_total ? $adicional->v_total : 0.00;
			$valor_adicional = $valor_adicional*$reserva->pasajeros;

			//en la reserva actualizo el valor de adicionales (restandole el valor del adicional)
			$ad_price = $reserva->adicionales_precio-$valor_adicional;
			$ad_price = ($ad_price > 0) ? $ad_price : 0;

			$upd_data_ad = array();
			$upd_data_ad['adicionales_precio'] = $ad_price;
			$this->Reserva->update($reserva_id,$upd_data_ad);

			//obtengo data del adicional/reserva
			$reserva_adicional = $this->Reserva->getAdicional($reserva_id,$paquete_adicional_id);

			//con este ID de reserva_adicional, lo busco en MOVIMIENTOS para obtener cual es el MOV ID asociado
			$find = array('cta_usd'=>($reserva->precio_usd?1:0),'reserva_adicional_id'=>$reserva_adicional->id,'usuario_id'=>$reserva->usuario_id,'tipoUsuario' => 'U','reserva_id' => $reserva_id);
			$mov_asociado = $this->Movimiento->getMovAsociado($find)->row();
			$mov_asociado_id = (isset($mov_asociado->id) && $mov_asociado->id) ? $mov_asociado->id : 0;

			//elimino el adicional
			$this->Reserva->eliminarAdicional($reserva_id,$paquete_adicional_id);

			$fecha_reserva = date('Y-m-d H:i:s');

			//registro movimiento en cta cte del usuario por el importe del adicional
			$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();
			$nuevo_parcial = ( isset($mov->parcial) ? $mov->parcial : 0.00 ) - $valor_adicional;

			/*
			//12-11-15 si el nuevo parcial es <0 por la quita del adicional, entonces le genero el mov de debe para que quede saldada la cta
			if($nuevo_parcial < 0){
				registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,$fecha_reserva,$paquete->titulo." - ".$paquete->codigo,$valor_adicional,0.00,$nuevo_parcial,'','');
				registrar_comentario_reserva($reserva->id,admin_id(),'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$paquete->titulo." - ".$paquete->codigo.' | DEBE '.$valor_adicional);

				//actualizo el nuevo parcial
				$nuevo_parcial += $valor_adicional;
			}
			*/

			$factura_id='';
			$talonario='';
			$factura_asociada_id='';
			$talonario_asociado='';
			$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
			$tipo_cambio = $this->settings->cotizacion_dolar;
			$movimiento_id = registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado',0.00,$valor_adicional,$nuevo_parcial,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,false,false,false,$reserva_adicional->id,$mov_asociado_id);
			registrar_comentario_reserva($reserva->id,admin_id(),'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado | HABER '.$valor_adicional);
			//-----------------------------------------------------------------------------------------------------------------

			if($mov_asociado_id){
				//al movimiento padre le pongo como asociado el actual de la eliminacion de adicional
				$this->Movimiento->update($mov_asociado_id,array('mov_asoc_id'=>$movimiento_id));
			}

			//idem genero movimiento en cta cte de BBV
			$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();
			$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) - $valor_adicional;

			registrar_movimiento_cta_cte(1,"A",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado',0.00,$valor_adicional,$nuevo_parcial2,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio);
			registrar_comentario_reserva($reserva->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional eliminado | HABER '.$valor_adicional);
			//-----------------------------------------------------------------------------------------------------------------

				$mail = true;
				/*
				$template = 'ajuste_precio';
				registrar_comentario_reserva($reserva->id,7,'envio_mail','Envio de email por ajuste de precio del paquete. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
				*/

				$template = 'cambios_reserva';
				registrar_comentario_reserva($reserva->id,7,'envio_mail','Envio de email de cambios en la reserva por adicional eliminado. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);


			//envio de mail avisando el BORRADO del adicional?
			//$asunto = "Adicional ".$_POST['adicional'.$j]." eliminado de reserva - ".$paquete->titulo." - ".$paquete->subtitulo;
			//$this->data['asiento_cama_msg'] = "Tu reserva ha registrado modificaciones debido a la eliminación del adicional ".$_POST['adicional'.$j].".<br>Hemos quitado de tu cuenta el importe del adicional correspondiente de $ ".$valor_adicional.".";

			$ret['status'] = 'SUCCESS';
			$ret['redirect'] = site_url('admin/reservas/edit/'.$reserva->id.'?tab=adicionales');
			$ret['msg'] = 'Se ha eliminado el adicional correctamente';
			if($return){
				return $ret;
			}
			else{
				echo json_encode($ret);
			}
		}
		else{
			$ret['status'] = 'ERROR';
			$ret['msg'] = 'No se pudo eliminado el adicional.';
			if($return){
				return $ret;
			}
			else{
				echo json_encode($ret);
			}
		}
	}

	/*
	agrega el adicional a la reserva
	*/
	function agregar_adicional($reserva_id,$paquete_adicional_id){
		if($reserva_id && $paquete_adicional_id){
			//obtengo data del adicional
			$adicional = $this->Adicional->getAsociacionPaquete($paquete_adicional_id);

			//data de reserva
			$reserva = $this->Reserva->get($reserva_id)->row();

			//15-07-19 si la reserva está anulada, no le permito agregar el adicional
			if($reserva->estado_id == 5){
				$ret['status'] = 'ERROR';
				$ret['msg'] = 'No se puede agregar el adicional ya que la reserva se encuentra ANULADA.';
				echo json_encode($ret);
			}
			else{

				//si el adicional tiene cupo, tengo que chequear que haya disponibilidad para la cantidad de pasajeros de la reserva
				//si la cantidad es 0 es porque es un adicional que NO CONSIDERA EL CUPO (ej seguro viaje)
				if( ($adicional->cantidad > 0 && $adicional->usados < $adicional->cantidad &&
					($adicional->usados+$reserva->pasajeros <= $adicional->cantidad))
					|| $adicional->cantidad == 0 ){

					//el adicional es para todos los pasajeros
					$valor_adicional = $adicional->v_total ? $adicional->v_total : 0.00;
					$valor_adicional = $valor_adicional*$reserva->pasajeros;

					//en la reserva actualizo el valor de adicionales (sumandole el valor del adicional)
					$ad_price = $reserva->adicionales_precio+$valor_adicional;
					$ad_price = ($ad_price > 0) ? $ad_price : 0;

					$upd_data_ad = array();
					$upd_data_ad['adicionales_precio'] = $ad_price;
					$this->Reserva->update($reserva_id,$upd_data_ad);

					//agrego el adicional, devuelvo el ID de RESERVA/ADICIONAL para asociarselo al movimiento
					$reserva_adicional_id = $this->Reserva->agregarAdicional($reserva_id,$paquete_adicional_id,$valor_adicional);

					$fecha_reserva = date('Y-m-d H:i:s');

					//registrar en cta cte los movimientos
					//registro movimiento en cta cte del usuario por el importe del adicional
					$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($reserva->usuario_id,"U",$reserva->precio_usd)->row();
					$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : 0.00 ) + $valor_adicional;


					$factura_id='';
					$talonario='';
					$factura_asociada_id='';
					$talonario_asociado='';
					$moneda = $reserva->precio_usd ? 'USD' : 'ARS';
					$tipo_cambio = $this->settings->cotizacion_dolar;
					$movimiento_id = registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado',$valor_adicional,0.00,$nuevo_parcial,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,false,false,false,$reserva_adicional_id);
					registrar_comentario_reserva($reserva->id,admin_id(),'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado | DEBE '.$valor_adicional);
					//-----------------------------------------------------------------------------------------------------------------

					//idem genero movimiento en cta cte de BBV
					$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();
					$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) + $valor_adicional;

					registrar_movimiento_cta_cte(1,"A",$reserva->id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado',$valor_adicional,0.00,$nuevo_parcial2,'','',$factura_id,$talonario,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio);
					registrar_comentario_reserva($reserva->id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' - Adicional agregado | DEBE '.$valor_adicional);
					//-----------------------------------------------------------------------------------------------------------------

					$mail = true;
					/*$template = 'ajuste_precio';
					registrar_comentario_reserva($reserva->id,7,'envio_mail','Envio de email por ajuste de precio del paquete. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);
					*/

					$template = 'cambios_reserva';
					registrar_comentario_reserva($reserva->id,7,'envio_mail','Envio de email de cambios en la reserva por adicional agregado. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template);


					//envio de mail avisando el agregado del adicional?
					//$asunto = "Adicional ".$_POST['adicional'.$j]." agregado a la reserva - ".$paquete->titulo." - ".$paquete->subtitulo;
					//$this->data['asiento_cama_msg'] = "Tu reserva ha registrado modificaciones debido al agregado del Adicional ".$_POST['adicional'.$j].".<br>El importe del adicional correspondiente es de $ ".$_POST['adicional'.$j.'Valor'].", el cual se adicionó a tu cuenta.";

					$ret['status'] = 'SUCCESS';
					$ret['redirect'] = site_url('admin/reservas/edit/'.$reserva->id.'?tab=adicionales');
					$ret['msg'] = 'Se ha agregado el adicional <b>'.$adicional->adicional.'</b> correctamente';
					echo json_encode($ret);
				}
				else{
					$ret['status'] = 'ERROR';
					$ret['msg'] = 'No hay disponibilidad de <b>'.$adicional->adicional.'</b>.<br><br>Cantidad total del adicional: '.$adicional->cantidad.'<br>Cantidad disponible: '.($adicional->cantidad-$adicional->usados).'<br>Cantidad solicitada: '.($reserva->pasajeros);
					echo json_encode($ret);
				}
			}
		}
		else{
			$ret['status'] = 'ERROR';
			$ret['msg'] = 'No se pudo agregar el adicional.';
			echo json_encode($ret);
		}
	}

	function ver_datos_comentario($h_id){
		$this->data['row'] = $this->Comentario->get($h_id)->row();
		$this->load->view('admin/reservas_historial_datos',$this->data);
	}

	function probarmaxi(){
		$reserva_row = $this->Reserva->get(11)->row();

		$existe_costo = $this->Movimiento->getWhere(
											array(
													'tipoUsuario'=>'A',
													'usuario_id'=>$reserva_row->operador_id,
													'reserva_id'=>$reserva_row->id
												 )
										 )->result();
		pre($reserva_row);
		pre($existe_costo);

	}

	function rooming($id){
		$this->data['page_title'] = 'Rooming del viaje';
		$this->init();
		$this->data['paquete'] = $this->Paquete->get($id)->row();

		$habs = $this->Habitacion->getByPaquete($id);

		$total_habs = [];

		foreach($habs as $h){
			if($h->habitacion_id != 99){
				//a la habitacion compartida no la considero
				for ($i=1; $i <= $h->cantidad ; $i++) {
					$total_habs[$h->habitacion_id][] = array(
										'numHab'=>$i,
										'nombre'=>$h->habitacion,
										'fecha_alojamiento_cupo_id'=>$h->fecha_alojamiento_cupo_id,
										'pax'=>$h->pax,
										'alojamiento'=>$h->alojamiento,
										'grupo'=>'',
										'pasajeros'=>[]
									);
				}
			}
		}

		if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
			//pre($total_habs);
		}

		/*
		$rooming = $this->Paquete_rooming->getRooming($id);

		//si no tiene rooming creado, se lo genero
		$habs = $this->Habitacion->getByPaquete($id);

		foreach ($habs as $r){
			$total_habitacion = count($this->Paquete_rooming->getByHabitacion($id, $r->habitacion_id));
			if ($total_habitacion < $r->cantidad) {
				for($i=0; $i < ($r->cantidad - $total_habitacion); $i++){
					$room = array();
					$room['paquete_id'] = $id;
					$room['nro_habitacion'] = '';
					$room['alojamiento_fecha_cupo_id'] = $r->id;
					$room['observaciones'] = '';

					//guardo rooming
					$this->Paquete_rooming->insert($room);
				}
			}
			elseif ($total_habitacion > $r->cantidad) {
				//Ver que quieren hacer en este caso donde sobran habitaciones
			}
		}

		$rooming = $this->Paquete_rooming->getRooming($id);

		$reservas_asignadas = [];
		foreach ($rooming as $room) {
			if ($room->reserva_id) {
				$reservas_asignadas[] = $room->reserva_id;
			}
		}
		*/

		//obtengo las reservas del paquete para luego intentar asignarla a la habitacion que corresponda
		$reservas = $this->model->getPasajerosConfirmados($id);


		//por cada habitacion disponible que tenga, veo cómo asigno a los pasajeros

		//las reservas confirmadas de grupo de habitaciones privadas, ya les puedo asignar su habitacion
		foreach ($reservas as $r) {
			//si es habitacion privada y si la cantidad de pasajeros confirmados del grupo que reservó es igual a la cantidad de pax de la habitacion que se reservó.

			//si ya tiene el numero de habitacion asignado, lo pongo donde corresponda
			if($r->rooming_nro_hab){


				if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
					//pre($r);
				}

				//Maxi 27/01/2020                          ==
				if($r->habitacion_id != 99 && $r->hab_pax >= $r->confirmadas_grupo){

					//asigno al pasajero a la habitacion privada que le corresponde al grupo
					$el_grupo = false;
					foreach ($total_habs as $hid=>$arr_habs){
						foreach($arr_habs as $pos=>$ch) {
							if($ch['grupo'] == $r->codigo_grupo){
								$el_grupo = $r->codigo_grupo;

								//si ya existe el grupo en una habitacion, lo pongo ahi
								$total_habs[$hid][$pos]['pasajeros'][] = $r;

								//en el registro de la reserva, le asocio el numero de habitacion quele acabode asignar
								$r->num_habitacion = $pos;
								break;
							}
						}
					}

					if(!$el_grupo){
						//si la reserva tiene asignada un numero de habitacion, la tomo
							if($r->rooming_nro_hab){
								$total_habs[$r->habitacion_id][$r->rooming_nro_hab-1]['grupo'] = $r->codigo_grupo;
								$total_habs[$r->habitacion_id][$r->rooming_nro_hab-1]['pasajeros'][] = $r;

								//en el registro de la reserva, le asocio el numero de habitacion quele acabode asignar
								$r->num_habitacion = $r->rooming_nro_hab;

							}
							else{

							}
					}
				}
			}

		}

		$final_arr = [];
		foreach($total_habs as $p => $hh){
			foreach($hh as $h){
				$final_arr[$h['alojamiento']][] = $h;
			}
		}

				if($_SERVER['REMOTE_ADDR'] == '190.193.58.84'){
					//pre($final_arr);
				}

		/*foreach ($reservas as $r) {
			if(!$r->rooming_nro_hab){

				//sino existe, le defino a cual va a ir los de su grupo
				foreach ($total_habs as $pos=>$ch) {


						//si la habitacion es de la cantidad de pasajeros del grupo y no estap ocupada por otro grupo, la tomo
						if($ch['pax'] == $r->hab_pax && !$ch['grupo']){
								$total_habs[$pos]['grupo'] = $r->codigo_grupo;
								$total_habs[$pos]['pasajeros'][] = $r;

								//en el registro de la reserva, le asocio el numero de habitacion quele acabode asignar
								$r->num_habitacion = $pos;
							break;
						}



				}

			}
		}*/




		//aca tengo todas las reservas de pasajeros confirmados
		$this->data['reservas'] = $reservas;
		//aca ya tengo cada una de las habitaciones disponibles, con los pasajeros asignados
		$this->data['habitaciones'] = $final_arr;

		$this->load->view('admin/reservas_rooming', $this->data);
	}

	function save_rooming(){
		extract($_POST);

		//habitacion y observaciones
		$this->Paquete_rooming->clearPaquete($paquete_id);

		//guardo el rooming generado
		foreach ($reserva_ids as $id => $valor) {
			$aux = $valor;
			$aux = explode('|', $aux);

			$room = array();
			$room['paquete_id'] = $paquete_id;
			$room['nro_habitacion'] = @$aux[1];
			$room['alojamiento_fecha_cupo_id'] = @$aux[0];
			$room['observaciones'] = '';
			$room['reserva_id'] = @$id;

			//guardo rooming
			$this->Paquete_rooming->insert($room);
		}

		$ret = array('status' => 'OK');
		echo json_encode($ret);
	}

	function download_rooming($id){
		$this->data['paquete'] = $this->Paquete->get($id)->row();
		$this->data['privadas'] = $this->Paquete_rooming->getRoomingPorHabitacion($id);
		$this->data['compartidas'] = $this->Paquete_rooming->getRoomingPorHabitacion($id,$comp=true);

		$this->data['room'] = [];
		$this->data['room'][] = array('rooming' => $this->data['privadas']);
		$this->data['room'][] = array('rooming' => $this->data['compartidas']);

		//descarga
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=rooming_".$this->data['paquete']->codigo.".xls");
		$this->load->view('admin/reservas_rooming_xls',$this->data);
	}

	function get_download_fields($id=''){
		$this->data['fields']['Usuarios y Coordinadores'] = array(
											  'ID' => 'PA.id as id_usuario',
											  'Apellido' => 'PA.apellido',
											  'Nombre' => 'PA.nombre',
											  'Celular' => 'concat(PA.celular_codigo,PA.celular_numero) as celular',
											  'Email' => 'PA.email',
											  'DNI' => 'PA.dni as dni_numero',
											  'Pasaporte' => 'PA.pasaporte as pasaporte_numero',
											  'Fecha Emisión' => 'PA.fecha_emision as pasaporte_emision',
											  'Fecha Vencimiento' => 'PA.fecha_vencimiento as pasaporte_vencimiento',
											  'Fecha Nacimiento' => 'PA.fecha_nacimiento as nacimiento',
												'Edad' => 'TIMESTAMPDIFF(year,PA.fecha_nacimiento, now() ) as edad',
											  'Nacionalidad' => 'PI.nombre as nacionalidad',
											  'Menu' => 'PA.dieta as menu',
											  'Sexo' => 'PA.sexo'
										);

		$this->data['fields']['Contacto Emergencia'] = array('Nombre' => 'PA.emergencia_nombre', 'Codigo' => 'PA.emergencia_telefono_codigo', 'Numero' => 'PA.emergencia_telefono_numero');

		$this->data['fields']['Reservas'] = array(
											  'ID' => 'R.id as id_reserva',
											  'Fecha' => 'R.fecha_reserva',
											  'Valor' => '(R.paquete_precio+R.impuestos+R.adicionales_precio) as monto',
											  'Saldo' => '0 as saldo',
											  'Adicionales' => '0 as adicionales',
											  /*'Adicional 1' => 'R.adicional1',
											  'Adicional 1 Valor' => 'R.adicional1Valor',
											  'Adicional 2' => 'R.adicional2',
											  'Adicional 2 Valor' => 'R.adicional2Valor',
											  'Adicional 3' => 'R.adicional3',
											  'Adicional 3 Valor' => 'R.adicional3Valor',
											  'Adicional 4' => 'R.adicional4',
											  'Adicional 4 Valor' => 'R.adicional4Valor',
											  'Adicional 5' => 'R.adicional5',
											  'Adicional 5 Valor' => 'R.adicional5Valor',
											  'Adicional 6' => 'R.adicional6',
											  'Adicional 6 Valor' => 'R.adicional6Valor',*/
											  'Estado' => 'E.nombre as estado',
											  'Lugar de Salida' => 'S.nombre as lugar_salida',
											  'Parada' => 'x.nombre as parada',
											  'Comentarios' => 'R.comentario',
											  'Regimen' => 'regi.nombre as pension',
											  'Codigo Grupo' => 'RG.codigo_grupo',
										);
		//$this->data['fields']['Sucursales'] = array('Nombre' => 'S.nombre as sucursal');

		$this->data['fields']['Paquete'] = array('ID' => 'P.id as id_viaje', 'Codigo' => 'P.codigo', 'Nombre' => 'P.nombre as viaje');

		//$this->data['fields']['Agencia'] = array('Nombre' => 'A.nombre as agencia');

		$this->data['fields']['Vendedor'] = array('ID' => 'V.id as id_vendedor', 'Nombre' => 'V.nombre as vendedor_nombre', 'Apellido' => 'V.apellido as vendedor_apellido');

		//$this->data['fields']['Coordinadores'] = array('Nombre' => '0 as co_nombre', 'Apellido' => '0 as co_apellido', 'Telefono'=>'0 as co_telefono');

		$this->data['fields']['Contacto Emergencia'] = array('Nombre' => 'PA.emergencia_nombre', 'Codigo' => 'PA.emergencia_telefono_codigo', 'Numero' => 'PA.emergencia_telefono_numero');

		$this->data['download_link'] = $this->data['route'].'/exportar/'.$id;

		$ret['view'] = $this->load->view('admin/download_fields',$this->data,true);

		echo json_encode($ret);
	}

	function informar_factura() {

		//ini_set('display_errors', 1);
		$factura_id = isset($_POST['factura_id']) ? $_POST['factura_id'] : 0;
		$movimiento_id = isset($_POST['movimiento_id']) ? $_POST['movimiento_id'] : 0;
		$tipo_comprobante = $_POST['comprobante'];
		$sucursal_id = $_POST['sucursal_id'];
		$reserva_id = $_POST['reserva_id'];
		$this->load->model('Factura_model','Factura');
		$this->load->model('Sucursal_model','Sucursal');

		// verificacion q no se este autorizando ninguna otra factura, para q no haya desfasaje en AFIP
		$informando = $this->Factura->verificacionAfip();
		if($informando > 0) {
			$error = array();
			$error['msj'] = 'No se pudo Informar. Hay otra factura autorizandose. ';
			echo json_encode($error);
			return;
		}



		//si la factura no está seteada, tengo que generar el registro en la tabla
		if($movimiento_id > 0){
			$row_movimiento = $this->Movimiento->get($movimiento_id)->row();

			if($row_movimiento->cta_usd){
				if($row_movimiento->debe_usd > 0){
					$valor_factura = $row_movimiento->debe_usd;
				}
				if($row_movimiento->haber_usd > 0){
					$valor_factura = $row_movimiento->haber_usd;
				}

				$tipo_cambio = $row_movimiento->tipo_cambio;

				$gastos_administrativos = 0;

				$concepto = $row_movimiento->concepto;

				if($concepto == 'MERCADO PAGO'){
					$gastos_administrativos = $valor_factura*$this->settings->mp_gastos_admin;
				}

				$intereses = 0;
				/*
				//para los intereses tengo q guardar en el movimiento, el dato de las cuotas y de que tipo de tarjeta
				if($concepto == 'MERCADO PAGO'){
					$intereses = $valor_factura*$this->settings->mp_gastos_admin;
				}
				*/

				$valor_factura = $valor_factura*$tipo_cambio;
				$gastos_administrativos = $gastos_administrativos*$tipo_cambio;
				$intereses = $intereses*$tipo_cambio;
			}
			else{
				if($row_movimiento->debe > 0){
					$valor_factura = $row_movimiento->debe;
				}
				if($row_movimiento->haber > 0){
					$valor_factura = $row_movimiento->haber;
				}

				$gastos_administrativos = 0;

				$concepto = $row_movimiento->concepto;

				if($concepto == 'MERCADO PAGO'){
					$gastos_administrativos = $valor_factura*$this->settings->mp_gastos_admin;
				}

				$intereses = 0;
				/*
				//para los intereses tengo q guardar en el movimiento, el dato de las cuotas y de que tipo de tarjeta
				if($concepto == 'MERCADO PAGO'){
					$intereses = $valor_factura*$this->settings->mp_gastos_admin;
				}
				*/

				$gastos_administrativos = $gastos_administrativos;
				$intereses = $intereses;
			}

			$row_reserva = $this->Reserva->get($reserva_id)->row();

			$valor_factura = round($valor_factura,2);

			$datos_factura = array();
			$datos_factura['sucursal_id'] = $sucursal_id;
			$datos_factura['vendedor_id'] = $row_reserva->vendedor_id;
			$datos_factura['usuario_id'] = 'U';
			$datos_factura['reserva_id'] = $reserva_id;
			$datos_factura['fecha'] = $row_movimiento->fecha;
			$datos_factura['valor'] = $valor_factura;
			$datos_factura['punto_venta'] = 2;
			$datos_factura['forma_pago'] = $concepto;
			$datos_factura['total'] = $valor_factura;
			$datos_factura['gastos_adm'] = ( isset($gastos_administrativos) && $gastos_administrativos > 0 ) ? $gastos_administrativos : 0.00;
			$datos_factura['intereses'] = ( isset($intereses) && $intereses > 0 ) ? $intereses : 0.00;

			/*
			pre($row_movimiento);
			pre($datos_factura);
			exit();
			*/

			$conceptoObj = $this->Concepto->getBy('nombre='.$concepto);

			//Si corresponde facturacion, generar comprobante
			if ($conceptoObj->facturacion) {
				$datos_factura['tipo_factura'] = $tipo_comprobante;
				$datos_factura['tipo'] = $tipo_comprobante;

				//agregado 08/07/14
				$datos_factura['concepto_id'] = $conceptoObj->id;
				//$factura_id = $this->Factura->insert($datos_factura);

			}
			else {
				return false;
			}
		}

		//el ultimo parametro en false poruqe primero informa a afip, entonces el detalle tiene que vijar como si fuera para facturacion
		//luego cuando se va a generar el archivo fisico pdf (dentro de generar() ) se obtienen los datos para la factura con ese ultimo parametro en true
		$this->Factura->generar($factura_id, $tipo_comprobante,$sucursal_id,$reserva_id,true,false);

		$data['status'] = 'ok';

		/* nuevo 08/07/14 : avisar al usuario que su factura se generó */
		$factura = $this->Factura->getFactura($factura_id,$tipo_comprobante,$sucursal_id)->row();

		//si tiene seteada la marca de INFORMAR_USUARIO -> se le envia la factura por mail
		if(isset($factura->id) && $factura->id && $factura->informar_usuario){
			$reserva = $this->Reserva->get($factura->reserva_id)->row();
			$concepto = $this->Concepto->get($factura->concepto_id)->row();
		}		

		echo json_encode($data);
	}

	function probarcambio($res_id = 1){
		$reserva = $this->Reserva_model->get($res_id)->row();
		if (!$reserva) {
			return;
		}

		$paquete = $this->Paquete->get($reserva->paquete_id)->row();
		$orden = $this->Orden->get($reserva->orden_id)->row();
		$combinacion = $this->Combinacion->get($reserva->combinacion_id)->row();

		verificar_cupo($combinacion,$orden,$paquete,$reserva);

		pre($reserva);
		pre($paquete);
		echo '<br>habitacion_sin_cupo: '.@$this->data['habitacion_sin_cupo'];
		echo '<br>transporte_sin_cupo: '.@$this->data['transporte_sin_cupo_sin_cupo'];

		//fuerzo para q tome el cupo real
		$paquete->cupo_paquete_personalizado = false;

		//si hay cupo en el viaje para los pax, habilito la confirmacion
		if(
			($paquete->cupo_paquete_personalizado && $paquete->cupo_paquete_disponible_real < $reserva->pasajeros)
			|| (!$paquete->cupo_paquete_personalizado && $paquete->cupo_disponible < $reserva->pasajeros)
			|| @$this->data['habitacion_sin_cupo'] || @$this->data['transporte_sin_cupo_sin_cupo']
		){
			//no hay cupo para pasar de estado
			//redirect(site_url('admin/reservas/edit/'.$res_id.'?conf=sincupo'));
			echo "<br>- sin cupo";
		}
		else{
			echo "<br>- con cupo";
		}
	}

	function versp($reserva_id=1){
		$reserva = $this->Reserva->get($reserva_id)->row();

		$saldo = $this->Reserva->getSaldoReserva($reserva->id);
		$saldo_pendiente = ($reserva->precio_usd) ? $saldo->saldo_usd : $saldo->saldo;
		echo "sp: ".$saldo_pendiente;
		echo "<br>";
		echo "<br>";

		$row = $reserva;

		$combinacion = $this->Combinacion->get($row->combinacion_id)->row();
		$reserva_adicionales = adicionales_reserva($row);
		$precios = calcular_precios_totales($combinacion,$this->data['adicionales_valores'],@$combinacion->precio_usd?'USD':'ARS',$row->id);
		$pagos_hechos = $precios['num']['monto_abonado'];
		$saldo_pendiente = $precios['num']['saldo_pendiente'];

		echo "sp: ".$saldo_pendiente;
		echo "<br>pr: ".$pagos_hechos;
		echo "<br>";
		echo "<br>";

	}

	/* metodo que se ejecuta en helper */
	function asignar_compartida($res_id=false){
		asignar_habitacion_compartida($res_id);
	}

	function mailbaja(){
		enviar_mail_cupo_liberado(74,true);
	}

}
