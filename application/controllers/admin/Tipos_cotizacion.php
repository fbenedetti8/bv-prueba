<?php
include "AdminController.php";

class Tipos_cotizacion extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Tipocotizacion_model', 'TipoCotizacion');
		$this->model = $this->TipoCotizacion;
		$this->page = "tipos_cotizacion";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Tipos de Cotización";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;

        $pid = $this->input->get('pid');
        if ($pid) {
        	$this->model->filters = array('grupo_id' => $pid);
			$m = $this->model->get($pid)->row();
        	$this->data['page_title'] = "<a href='".site_url('admin/tipos_cotizacion')."'>Tipos de Cotización</a> &raquo; ".$m->nombre;
        }
        else {
        	$this->model->filters = array('grupo_id' => 0);
        }
        $this->data['pid'] = $pid;        
	}
	
	function onEditReady($id='') {
		$this->load->model('Region_model', 'Region');
		$regiones = $this->Region->getRegiones();
		$this->data['regiones'] = $regiones;

		$convenios_ua = array();
		$convenios_ta = array();

		if ($id) {
			$traducciones = $this->model->getTranslations($id);
			foreach ($traducciones as $lang=>$langrow) {
				foreach ($langrow as $key=>$value) {
					$this->data['row']->{$key.'_'.$lang} = $value;
				}
			}

			$convenios = $this->model->getConveniosRegiones($id);
			foreach ($convenios as $convenio) {
				$convenios_ua[$convenio->region_id] = $convenio->convenio_ua;
				$convenios_ta[$convenio->region_id] = $convenio->convenio_ta;
			}
		}
		else {
			foreach ($regiones as $region) {
				$convenios_ua[$region->id] = '';
				$convenios_ta[$region->id] = '';
			}
		}

		$this->data['convenios_ua'] = $convenios_ua;
		$this->data['convenios_ta'] = $convenios_ta;

		$this->data['tipos'] = $this->model->getTiposCotizacion(0);
	}	

	function onBeforeSave() {
		if (!isset($_POST['anual'])) $_POST['anual'] = 0;
	}

	function onAfterSave($id) {
		$regiones = $this->input->post('regiones');
		$convenios_ua = $this->input->post('convenios_ua');
		$convenios_ta = $this->input->post('convenios_ta');

		for ($i=0; $i<count($regiones); $i++) {
			$this->model->setConveniosRegion($id, $regiones[$i], $convenios_ua[$i], $convenios_ta[$i]);
		}

		if ($_POST['grupo_id']) {
			redirect(site_url('admin/tipos_cotizacion/?pid='.$_POST['grupo_id']));
		}
	}
		
}