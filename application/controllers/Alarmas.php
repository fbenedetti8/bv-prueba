 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alarmas extends MY_Controller {

	function __construct() {
		parent::__construct();

		$this->limit = 100;
		$this->load->model('Reserva_model','Reserva');
		$this->model = $this->Reserva;
	}

	function index(){		
		//obtengo el ultimo proceso de alarmas generado
		$row = $this->db->query("SELECT * FROM bv_alarmas_procesos 
									ORDER BY fecha_proceso DESC LIMIT 1")->row();

		//si existe y esta en ejecucion, lo tomo para usar
		if(isset($row->id) && $row->id && $row->ejecucion){
			$this->procesar_reservas($row);
		}
		else{
			//si no esta en ejecucion ó no existe, genero el proximo
			//lo pongo en ejecucion porque es el proximo a usar
			$this->db->insert("bv_alarmas_procesos",array(
														'fecha_proceso'=>date('Y-m-d H:i:s'),
														'ejecucion' => 1,
														'offset' => 0
													));

			$row = $this->db->query("SELECT * FROM bv_alarmas_procesos 
								ORDER BY fecha_proceso DESC LIMIT 1")->row();

			//genera los registros de cada reserva en tabla del proceso que las procesará
			$this->asociar_reservas($row);
		}
	}

	/*
	Procesa los registros de reserva del proceso actual en ejecucion
	*/
	function procesar_reservas($row){		
	
		//obtengo las reservas que tengo que procesar
		$rows = $this->db->query("SELECT reserva_id FROM bv_alarmas_reservas WHERE proceso_id = ".$row->proceso_id." LIMIT ".$this->limit." OFFSET ".$row->offset);
				
		//obtengo saldo a cobrar de cada paquete
		foreach($rows->result() as $paq){
			$paq->llamados_por_hacer = 0;
			
			$admin_id = '';
			
			$reservas = $this->model->getAllByPaquete($paq->id,'','','',$admin_id,'');
			
			$paq->alarmas = new stdClass;
			foreach($reservas->result() as $re){
				$als = cargar_alarmas($re);

				$paq->alarmas = $als->alarmas;

				//actualizo el campo con las alarmas de la reserva
				$alarmas = json_encode($paq->alarmas);

				$this->db->query("UPDATE bv_alarmas_reservas SET alarmas = ".$alarmas." WHERE reserva_id = ".$re->id);

				/*
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
				*/
			}
			
			//actualizo el OFFSET del proceso actual
			$offset = $row->offset+$this->limit;
			$this->db->query("UPDATE bv_alarmas_procesos SET OFFSET = ".$offset." WHERE id = ".$row->proceso_id);
		
		}
		
		
	}

	/*
	Genera registros de reserva en tabla 
	*/
	function asociar_reservas($row){		
		$this->data['sort'] = "P.fecha_inicio";
		$this->data['sortType'] = "ASC";
		
		$this->pconfig['uri_segment'] = '';
		$this->pconfig['per_page'] = 99999;
		
		//registro todas las reservas en al tabla asociada al proceso
		$this->db->query("INSERT INTO bv_alarmas_reservas (reserva_id,proceso_id) (SELECT id, ".$row->id." FROM bv_reservas)");

		$this->procesar_reservas($row);
	}

}