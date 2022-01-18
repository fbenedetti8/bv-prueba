<?php
include "AdminController.php";

class Inicio extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['currentModule'] = "home";
		$this->page = "inicio";
		$this->data['page'] = $this->page;
		$this->data['route'] = base_url() . 'admin/' . $this->page;
		$this->init();
		$this->validate = FALSE;

		$this->load->model('Widget_model', 'Widget');
	}

	function index(){
		$this->data['widgets_viajeros_desktop'] = $this->Widget->getAvailableWidgets('viajeros', 'desktop');
		$this->data['widgets_viajeros_mobile'] = $this->Widget->getAvailableWidgets('viajeros', 'mobile');
		$this->data['widgets_agencias_desktop'] = $this->Widget->getAvailableWidgets('agencias', 'desktop');
		$this->data['widgets_agencias_mobile'] = $this->Widget->getAvailableWidgets('agencias', 'mobile');

		$this->data['widgets_viajeros_desktop_on'] = $this->Widget->getWidgetsOn('viajeros', 'desktop');
		$this->data['widgets_viajeros_mobile_on'] = $this->Widget->getWidgetsOn('viajeros', 'mobile');
		$this->data['widgets_agencias_desktop_on'] = $this->Widget->getWidgetsOn('agencias', 'desktop');
		$this->data['widgets_agencias_mobile_on'] = $this->Widget->getWidgetsOn('agencias', 'mobile');

		$this->load->view('admin/inicio', $this->data);
	}

	function save() {
		extract($_POST);

		$this->db->query('truncate table ua_home');

		$n = 0;
		foreach ($widget_viajeros_desktop as $w) {
			$n += 1;

			$this->db->insert('ua_home', array(
				'widget_id' => $w,
				'tipo' => 'Viajeros',
				'orden' => $n,
				'plataforma' => 'desktop'
			));
		}

		$n = 0;
		foreach ($widget_viajeros_mobile as $w) {
			$n += 1;

			$this->db->insert('ua_home', array(
				'widget_id' => $w,
				'tipo' => 'Viajeros',
				'orden' => $n,
				'plataforma' => 'mobile'
			));
		}

		$n = 0;
		foreach ($widget_agencias_desktop as $w) {
			$n += 1;

			$this->db->insert('ua_home', array(
				'widget_id' => $w,
				'tipo' => 'Agencias',
				'orden' => $n,
				'plataforma' => 'desktop'
			));
		}

		$n = 0;
		foreach ($widget_agencias_mobile as $w) {
			$n += 1;

			$this->db->insert('ua_home', array(
				'widget_id' => $w,
				'tipo' => 'Agencias',
				'orden' => $n,
				'plataforma' => 'mobile'
			));
		}	

		redirect(site_url('admin/inicio'));	
	}
	
}