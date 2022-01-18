<?php
include "AdminController.php";

class Operadores extends AdminController{

  function __construct() {
    parent::__construct();
    $this->load->model('Operador_model', 'Operador');
    $this->model = $this->Operador;
    $this->page = "operadores";
    $this->data['currentModule'] = "reservas";
    $this->data['page'] = $this->page;
    $this->data['route'] = site_url('admin/' . $this->page);  
    $this->data['uploadFolder'] = "operadores";
    $this->pageSegment = 4;
    $this->data['page_title'] = "Operadores";
    $this->limit = 50;
    $this->init();
    $this->validate = FALSE;
	
    $this->load->model('Destino_model', 'Destino');
    $this->load->model('Movimiento_model', 'Movimiento');
    $this->load->model('Reserva_model', 'Reserva');
    $this->load->model('Paquete_model', 'Paquete');
    $this->load->model('Concepto_model', 'Concepto');
    $this->load->model('Reserva_estado_model', 'Reserva_estado');
  }
  
	function index(){
		//Pagination
		$this->pconfig['total_rows'] = $this->model->count($this->data['keywords']);
		$this->pconfig['uri_segment'] = $this->pageSegment;
		$this->pagination->initialize($this->pconfig);		
		$this->data['pages'] = $this->pagination->create_links();
		$this->data['totalRows'] = $this->pconfig['total_rows'];
		
		$this->data['sort'] = ($this->data['sort']) ? $this->data['sort'] : ((isset($this->model) && isset($this->model->defaultSort))?$this->model->defaultSort:"") ;
		$this->data['sortType'] = ($this->data['sortType']) ? $this->data['sortType'] : ((isset($this->model) && isset($this->model->defaultSortType))?$this->model->defaultSortType:"") ;
		
		$this->data['data'] = $this->model->getAll($this->pconfig['per_page'],$this->uri->segment($this->pconfig['uri_segment']), $this->data['sort'], isset($this->data['sortType'])?$this->data['sortType']:'desc', $this->data['keywords']);
        $this->data['saved'] = isset($_GET['saved'])?$_GET['saved']:'';
		
		foreach($this->data['data']->result() as $d){
			//el saldo a pagar al operador esta en el ultimo registro de la cuenta 
			//sum(m.debe-m.haber) as saldo, sum(m.debe_usd-m.haber_usd) as saldo_usd,
			$q = "select ifnull(sum(t.saldo),0) as saldo, ifnull(sum(t.saldo_usd),0) as saldo_usd, 
					t.precio_usd
				from (select 
						sum(case when m.pago_usd = 0 then m.debe-m.haber else 0 end) as saldo, sum(case when m.pago_usd = 1 then m.debe_usd-m.haber_usd else 0 end) as saldo_usd,
						min(p.precio_usd) as precio_usd 
						from bv_movimientos m 
					  JOIN bv_reservas r on r.id = m.reserva_id and m.reserva_id > 0 and r.estado_id = 4
						join bv_paquetes p on p.id = r.paquete_id
						join bv_operadores o on o.id = m.usuario_id and p.operador_id = o.id
						WHERE m.tipoUsuario = 'A' and m.usuario_id = ".$d->id." 
				UNION
					select 
						sum(case when m.pago_usd = 0 then m.debe-m.haber else 0 end) as saldo, sum(case when m.pago_usd = 1 then m.debe_usd-m.haber_usd else 0 end) as saldo_usd,
						min(p.precio_usd) as precio_usd 
						from bv_movimientos m 
						join bv_paquetes p on p.id = m.paquete_id and m.paquete_id > 0
						join bv_operadores o on o.id = m.usuario_id and p.operador_id = o.id
					  WHERE m.tipoUsuario = 'A' and m.usuario_id = ".$d->id." and m.reserva_id = 0) t ";
					
			$p = $this->db->query($q)->row();
			
			$d->saldoAPagar = 0.00;
			$d->precio_usd = 0;
			if(isset($p->saldo) || isset($p->saldo_usd)){
				$d->precio_usd = isset($p->precio_usd) ? $p->precio_usd : 0;
				
				$d->saldoAPagar_usd = isset($p->saldo_usd) ? $p->saldo_usd : 0;
				$d->saldoAPagar = isset($p->saldo) ? $p->saldo : 0;				
			}
		}
		
		$this->load->view('admin/' . $this->page, $this->data);
    }
    
	function onEditReady($id = '') {
		$this->breadcrumbs[] = ($id!='')?($this->data['row']->nombre):'';
		
		if($id){
			$p_id = isset($_GET['paquete_id']) ? $_GET['paquete_id'] : 0;
			$this->cargar_cuenta_corriente_operador($id,'A',$p_id);
		}
	}
		
	function onAfterSave($id) {
		
		/*if($id && isset($_POST['btnvolver']) && $_POST['btnvolver']){
			redirect($this->data['route'].'/edit/'.$id.'?saved=1');
		}*/
	}
	
	/* carga listado de paquetes del operador */
	function paquetes($operador_id){		
		$operador = $this->model->get($operador_id)->row();
		$this->data['operador'] = $operador;
		$this->breadcrumbs[] = ($operador_id!='')?($operador->nombre):'';
		
		$activo = 1;
		if(isset($_POST['activo'])){
			$activo = $_POST['activo'];
		}
		
		$year = '';
		if($activo == 0) $year = date('Y');
		
		if(isset($_POST['year']) && $_POST['year'] ){
			$year = $_POST['year'];
		}
		$this->data['year'] = $year;
		
		//listado de paquetes ordenado por codigo de paquete
		if (!$this->session->userdata('sort')) {
			$this->data['sort'] = "P.codigo";
			$this->data['sortType'] = "ASC";
			$this->session->set_userdata('sortType', $this->data['sortType']);
			$this->session->set_userdata('sort', $this->data['sort']);
		}
		$this->pconfig['uri_segment'] = '';
		$this->pconfig['per_page'] = 99999;
		
		//destinos del operador
		$this->data['destinos'] = $this->Destino->getPorOperador($operador_id);
		
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
			$this->db->where('P.codigo LIKE "%'.$this->data['keywords'].'%"');
		}
		
		//filtro por tipo
		if($this->session->userdata('tipo_destino') != ''){
			$tipo = $this->session->userdata('tipo_destino') == 'grupales' ? '0' : '1';
			$this->db->where('D.viaje_individual = '.$tipo);
		}
		
		$this->data['data'] = $this->Reserva->getAllGroupByPaquetes($activo,$this->data['sort'],$this->data['sortType'],$year,$this->pconfig['per_page'],$this->uri->segment($this->pconfig['uri_segment']),'',$operador_id);
			
		//obtengo saldo a pagar al operador de cada paquete
		foreach($this->data['data']->result() as $paq){
			//el saldo a pagar al operador es el costo del paquete por la cantidad de reservas confirmadas 
			//que ya tengan el movimiento en la cuenta del operador registrado
			//Y DE LOS movimientos de pago de cada paquete del operador
			$q = "select sum(t.saldo) as saldo, sum(t.saldo_usd) as saldo_usd
				  from 
					(select sum(m.debe-m.haber) as saldo, sum(m.debe_usd-m.haber_usd) as saldo_usd
						from bv_reservas r
						join bv_paquetes p on p.id = r.paquete_id
						join bv_paquetes_combinaciones pc on pc.id = r.combinacion_id
						join bv_movimientos m on m.reserva_id = r.id and m.tipoUsuario = 'A' and m.usuario_id = p.operador_id
						where r.estado_id = 4 and r.paquete_id = ".$paq->id."
						group by r.paquete_id
					UNION
					  select sum(m.debe-m.haber) as saldo, sum(m.debe_usd-m.haber_usd) as saldo_usd
						from bv_movimientos m 
						WHERE m.tipoUsuario = 'A' and m.usuario_id = ".$operador_id." and m.paquete_id = ".$paq->id.") t ";
					
			$datos = $this->db->query($q)->row();
			
			if($paq->precio_usd)
				$paq->saldoAPagar = isset($datos->saldo_usd) ? $datos->saldo_usd : 0;
			else
				$paq->saldoAPagar = isset($datos->saldo) ? $datos->saldo : 0;
				
			$paq->llamados_por_hacer = 0;
			
			//si esta logueado un vendedor 
			if ($this->session->userdata('es_vendedor')){
				$admin_id = $this->session->userdata('admin_id');
			}
			else
				$admin_id = '';
			
			$paq->alarmas = new stdClass;
			$paq->alarmas->alerta_cargar_costo_operador = $paq->c_costo_operador > 0 ? false : true;
			
			//$reservas = $this->Reserva->getAllByPaquete($paq->id,'','','',$admin_id,'');
			
			/*foreach($reservas->result() as $re){
				$als = $this->cargar_alarmas($re);

				$paq->alarmas->informes = $als->informes > @$paq->alarmas->informes ? $als->informes : @$paq->alarmas->informes;
				$paq->alarmas->completar_datos_pax = $als->completar_datos_pax > @$paq->alarmas->completar_datos_pax ? $als->completar_datos_pax : @$paq->alarmas->completar_datos_pax;
				$paq->alarmas->alerta_no_llamar = $als->alerta_no_llamar > @$paq->alarmas->alerta_no_llamar ? $als->alerta_no_llamar : @$paq->alarmas->alerta_no_llamar;
				$paq->alarmas->alerta_llamar_pax = $als->alerta_llamar_pax > @$paq->alarmas->alerta_llamar_pax ? $als->alerta_llamar_pax : @$paq->alarmas->alerta_llamar_pax;
				$paq->alarmas->alerta_reestablecida = $als->alerta_reestablecida > @$paq->alarmas->alerta_reestablecida ? $als->alerta_reestablecida : @$paq->alarmas->alerta_reestablecida;
				$paq->alarmas->alerta_contestador = $als->alerta_contestador > @$paq->alarmas->alerta_contestador ? $als->alerta_contestador : @$paq->alarmas->alerta_contestador;
				$paq->alarmas->falta_factura_proveedor = $als->falta_factura_proveedor > @$paq->alarmas->falta_factura_proveedor ? $als->falta_factura_proveedor : @$paq->alarmas->falta_factura_proveedor;
				$paq->alarmas->faltan_cargar_vouchers = $als->faltan_cargar_vouchers > @$paq->alarmas->faltan_cargar_vouchers ? $als->faltan_cargar_vouchers : @$paq->alarmas->faltan_cargar_vouchers;

			}*/
			
		}
		
		$this->data['totalRows'] = count($this->data['data']->result());
		$this->data['saved'] = isset($_GET['saved'])?$_GET['saved']:'';
		
		if($activo == 1)
			$this->data['msg_text'] = "Ver reservas finalizadas";
		else
			$this->data['msg_text'] = "Ver reservas vigentes";
			
		$this->data['activo'] = $activo;
		
		if( $this->session->userdata('es_vendedor') )
			$this->data['es_vendedor'] = true;
			
		$this->load->view('admin/operadores_paquetes', $this->data);
	}
	
	/* carga listado de reservas del paquete para el operador*/
	function reservas($operador_id,$paquete_id,$estado_id=""){
		$operador = $this->model->get($operador_id)->row();
		$this->data['operador'] = $operador;
		$this->breadcrumbs[] = ($operador_id!='')?($operador->nombre):'';
		
		$id = $paquete_id;
		
		//si esta logueado un vendedor 
		if ($this->session->userdata('es_vendedor')){
			$admin_id = $this->session->userdata('admin_id');
		}
		else
			$admin_id = '';
		
		$this->session->set_userdata('paquete_id',$id);
		
		if($estado_id!="")
			$this->data['estado_id'] = $estado_id;
		
		$this->data['paquete_id'] = $id;
		
		$paquete = $this->Paquete->get($id)->row();
		$this->data['paquete'] = $paquete;
		
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
		$data = $this->Reserva->getAllByPaquete($id,$estado_id,$this->data['sort'],$this->data['sortType'],$admin_id,$this->data['sucursal_id']);
		
		
		$this->data['totalRows'] = count($data->result());
		
		foreach($data->result() as $row){			
			$row->tipo_id = $row->usuario_id;
			$row->tipo = 'U';			
			
			$fecha_hora = $row->fecha;
			$fecha_hora = explode(" ",$fecha_hora);
			$row->hora = substr($fecha_hora[1],0,5);
			$row->fecha = date('d/m/Y',strtotime($row->fecha));
			
			//alarmas de cada reserva
			$row->alarmas = false;
			
			//ordeno primero las que tienen estado POR ACREDITAR
			if($row->estado_id == 14)
				$data_primeros[] = $row;
			else
				$data_restantes[] = $row;
			
			//el saldo a pagar por esta reserva es el 100% del costo del viaje
			$row->saldoAPagar = $row->c_costo_operador*$row->pasajeros;
			
			//el saldo a pagar es el que está registrado (o no) en la cuenta corriente del operador para cada reserva
			$q = "select p.precio_usd, m.id, m.debe, m.debe_usd 
					from bv_movimientos m 
					join bv_reservas r on r.id = m.reserva_id
					join bv_paquetes p on p.id = r.paquete_id
					where m.concepto = 'REGISTRO 100% COSTO VIAJE' and m.tipoUsuario = 'A' and m.usuario_id = ".$row->operador_id." and m.reserva_id = ".$row->id;
			$sp = $this->db->query($q)->row();
			
			$montoapagar = 0.00;
			$registro_de_costo = false;
			if(isset($sp->id) && $sp->id){
				$montoapagar = ($sp->precio_usd) ? $sp->debe_usd : $sp->debe;
				$registro_de_costo = true;
			}
			
			$row->saldoAPagar = $montoapagar;
			$row->registro_de_costo = $registro_de_costo;
		}
		
		$data = array_merge($data_primeros,$data_restantes);
		
		$estados = $this->Reserva_estado->getAll('','');
		$this->data['estados'] = $estados->result();
		
		$this->data['data'] = $data;
		
		$this->load->view('admin/operadores_reservas',$this->data);
	}
	
	function registrar_costo_viaje(){
		$ids = isset($_POST['ids']) ? $_POST['ids'] : false;
		
		foreach($ids as $id){
			//genero registro de costo de viaje para cada una de las reservas
			//chequeo si en la cuenta del operador ya registré o no el costo de este viaje
			$reserva_row = $this->Reserva->get($id)->row();
			$existe_costo = $this->Movimiento->getWhere(
												array(
														'tipoUsuario'=>'A',
														'usuario_id'=>$reserva_row->operador_id,
														'reserva_id'=>$reserva_row->id
													 )
											 )->result();
			
			$fecha = date('Y-m-d H:i:s');
			
			//si no hay costo cargado, no lo registra
			if(count($existe_costo)==0 && $reserva_row->c_costo_operador > 0){
				//registrar en la cuenta del operador el 100% del costo del viaje cuando la reserva se confirma al recibir al menos 1 pago.
				$op_conc = $this->Concepto->get(56)->row();	
				$op_concepto = $op_conc->nombre;
				$valor_costo = $reserva_row->c_costo_operador*$reserva_row->pasajeros;
				$moneda_costo = $reserva_row->precio_usd ? 'USD' : 'ARS';
				$tipo_cambio = $this->settings->cotizacion_dolar;
				$informe_id = false;
				registrar_movimiento_cta_cte($reserva_row->operador_id,'A',$reserva_row->id,$fecha,$op_concepto,$valor_costo,0.00,false,$comentarios='',$comprobante='',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda_costo,$tipo_cambio,$informe_id);
				
				//Registrar el registro de costo de viaje para el operador
				$mail = false;
				$template = '';
				registrar_comentario_reserva($reserva_row->id,7,'registro_costo_operador','Registro 100% costo viaje en Cta Cte de Operador '.$reserva_row->operador.'. CONCEPTO: '.$reserva_row->nombre." - ".$reserva_row->paquete_codigo.' | DEBE '.$valor_costo);
			}
		}
		
		$ret['success'] = true;
		echo json_encode($ret);
	}
	
}
