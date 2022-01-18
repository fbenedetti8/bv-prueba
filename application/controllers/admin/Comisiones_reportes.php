<?php
include "AdminController.php";

class Comisiones_reportes extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Comisiones_reportes_model', 'Reportes');
		$this->model = $this->Reportes;
		$this->page = "comisiones_reportes";
		$this->data['currentModule'] = "comisiones";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Reportes";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;

	}

	function index() {
		$this->data['row'] = [];

		$this->data['anio'] = $this->session->userdata('reporte_anio');
		$this->data['mes'] = $this->session->userdata('reporte_mes');
		$this->data['tipo'] = $this->session->userdata('reporte_tipo');
		$this->data['desde'] = $this->session->userdata('reporte_desde');
		$this->data['hasta'] = $this->session->userdata('reporte_hasta');

		if ($this->data['tipo'] == '') $this->data['tipo'] = 'mes';

		$this->load->view('admin/comisiones_reportes', $this->data);
	}

	function generar() {
		$reporte_comisiones = $this->input->post('comisiones');
		$reporte_ranking = $this->input->post('ranking');
		$estado_de_venta = $this->input->post('ventas');
		$anio = $this->input->post('anio');
		$mes = $this->input->post('mes');
		$tipo = $this->input->post('tipo');
		$desde = $this->input->post('desde');
		$hasta = $this->input->post('hasta');

		$this->session->set_userdata('reporte_tipo', $tipo);
		$this->session->set_userdata('reporte_desde', $desde);
		$this->session->set_userdata('reporte_hasta', $hasta);
		$this->session->set_userdata('reporte_anio', $anio);
		$this->session->set_userdata('reporte_mes', $mes);

		if ($reporte_comisiones) {
			$reporte = 'comisiones';
			$title = $reporte_comisiones;
		}
		elseif ($reporte_ranking) {
			$reporte = 'ranking';
			$title = $reporte_ranking;
		}
		else {
			$reporte = 'ventas';
			$title = $estado_de_venta;
		}

		if ($tipo == 'mes') {
			$periodo = monthName($mes).' - '.$anio;
			$p1 = $anio;
			$p2 = $mes;
		}
		else {
			$periodo = $desde.' a '.$hasta;

			$desdeArr = explode("/", $desde);
			$p1 = $desdeArr[2].'-'.$desdeArr[1].'-'.$desdeArr[0];
			$hastaArr = explode("/", $hasta);
			$p2 = $hastaArr[2].'-'.$hastaArr[1].'-'.$hastaArr[0];
		}
		$this->data['title'] = $title . ' de ' . $periodo;

		$this->data['export_url'] = site_url('admin/comisiones_reportes/export');

		$this->data['rows'] = $this->Reportes->generarReporte($reporte, $tipo, $p1, $p2);

		if($_SERVER['REMOTE_ADDR'] == '190.191.147.49'){
			#echo $this->db->last_query();
		}

		if ($reporte == 'ventas') {
			$this->data['rows_equipos'] = $this->Reportes->generarReporte($reporte, $tipo, $p1, $p2, TRUE);
		}

		$this->session->set_flashdata('title_export', $this->data['title']);
		$this->session->set_flashdata('rows_export', $this->data['rows']);

		$this->load->view('admin/comisiones_reportes_'.$reporte, $this->data);
	}

	function export() {
		$title = strtolower(url_title($this->session->flashdata('title_export')));
		$rows = $this->session->flashdata('rows_export');

		exportExcel($rows, $title);
	}

	function detalle_ventas($liquidacion_id, $tipo='') {
		if (strpos($liquidacion_id, "-")) {
			$ids = explode("-", $liquidacion_id);
			$this->db->where_in('id', $ids);
			$liq = $this->db->get('bv_comisiones_liquidaciones')->row();

			$this->db->where_in('liquidacion_id', $ids);

			if($tipo){
				$this->db->where('tipo', $tipo);	
			}

			$this->db->select('liq.codigo, concat(pax.nombre, \' \', pax.apellido) as pasajero, paq.nombre as paquete, liq.vendedor, liq.total_facturado, liq.monto_comisionable, conf.fecha_confirmada', false);
			$this->db->join('bv_reservas r', 'r.id = liq.reserva_id', 'left');
			$this->db->join('bv_paquetes paq', 'paq.id = r.paquete_id', 'left');
			$this->db->join('bv_reservas_pasajeros rp', 'rp.reserva_id = r.id and rp.responsable = 1', 'left');
			$this->db->join('bv_pasajeros pax', 'pax.id = rp.pasajero_id', 'left');
			$this->db->join('(	select m.reserva_id, min(fecha) as fecha_confirmada
								from bv_movimientos m
								inner join bv_conceptos c on c.nombre = m.concepto
								where m.tipoUsuario = \'U\' and c.pasa_a_confirmada = 1
								group by m.reserva_id
							) conf', 'conf.reserva_id = r.id');
			$rows = $this->db->get('bv_comisiones_liquidaciones_reservas liq')->result();
		}
		else {
			$liq = $this->db->get_where('bv_comisiones_liquidaciones', array('id' => $liquidacion_id))->row();

			$this->db->select('liq.codigo, concat(pax.nombre, \' \', pax.apellido) as pasajero, paq.nombre as paquete, liq.vendedor, liq.total_facturado, liq.monto_comisionable, conf.fecha_confirmada', false);
			$this->db->join('bv_reservas r', 'r.id = liq.reserva_id', 'left');
			$this->db->join('bv_paquetes paq', 'paq.id = r.paquete_id', 'left');
			$this->db->join('bv_reservas_pasajeros rp', 'rp.reserva_id = r.id and rp.responsable = 1', 'left');
			$this->db->join('bv_pasajeros pax', 'pax.id = rp.pasajero_id', 'left');
			$this->db->join('(	select m.reserva_id, min(fecha) as fecha_confirmada
								from bv_movimientos m
								inner join bv_conceptos c on c.nombre = m.concepto
								where m.tipoUsuario = \'U\' and c.pasa_a_confirmada = 1
								group by m.reserva_id
							) conf', 'conf.reserva_id = r.id');

			if($tipo){
				$this->db->where('liq.tipo',$tipo);
			}

			$rows = $this->db->get_where('bv_comisiones_liquidaciones_reservas liq', array('liq.liquidacion_id' => $liquidacion_id))->result();
		}
		
		switch ($tipo) {
			case 'propia': $tipo_str = 'propias'; break;
			case 'equipo': $tipo_str = 'equipo'; break;
		}

		echo "<big>Ventas de ".$liq->usuario." (".$tipo_str.")</big>";
		echo "<table class='table table-striped'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th width='10%'>Codigo</th>";
		echo "<th width='10%'>Fecha Conf.</th>";
		echo "<th width='20%'>Pasajero</th>";
		echo "<th width='20%'>Paquete</th>";
		echo "<th width='10%'>Vendedor</th>";
		echo "<th width='15%' class='text-right'>Total Venta</th>";
		echo "<th width='15%' class='text-right'>Comisionable</th>";
		echo "</tr>";
		echo "</thead>";
		echo "</table>";

		if (count($rows)) {
			if (count($rows)>7) {
				echo "<div style='height:300px; overflow:auto;'>";
			}
			echo "<table class='table table-striped'>";
			echo "<tbody>";
			
			$total_facturado_total = 0;
			$monto_comisionable_total = 0;
			foreach ($rows as $row) {
				$total_facturado_total += $row->total_facturado;
				$monto_comisionable_total += $row->monto_comisionable;

				echo "<tr>";
				echo "<td width='10%'>".$row->codigo."</td>";
				echo "<td width='10%'>".date('d/m/Y', strtotime($row->fecha_confirmada))."</td>";
				echo "<td width='20%'>".$row->pasajero."</td>";
				echo "<td width='20%'>".$row->paquete."</td>";
				echo "<td width='10%'>".$row->vendedor."</td>";
				echo "<td width='15%' class='text-right'>$ ".number_format($row->total_facturado, 2, ',', '.')."</td>";
				echo "<td width='15%' class='text-right'>$ ".number_format($row->monto_comisionable, 2, ',', '.')."</td>";
				echo "</tr>";
			}
			echo "</tbody>";
			echo "</table>";
			if (count($rows)>7) {
				echo "</div>";
			}

			echo "<table class='table table-striped' style='border-top:solid 2px #CCC'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th width='50%' class='text-left'>Total</th>";
			echo "<th width='25%' class='text-right'>$ ".number_format($total_facturado_total, 2, ',', '.')."</th>";
			echo "<th width='25%' class='text-right'>$ ".number_format($monto_comisionable_total, 2, ',', '.')."</th>";
			echo "</tr>";
			echo "</thead>";
			echo "</table>";
		}
		else {
			echo "<div class='alert alert-warning text-center' style='padding:25px 0'>No se encontraron reservas confirmadas en este periodo para este usuario.</div>";
		}
	}
}