<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mantenimiento extends MY_Controller {

	public function __construct() {
		parent::__construct();	
		
	}

	public function index()
	{
		$this->data['body_id'] = 'home';
		
		$this->carabiner->css('../bootstrap/css/bootstrap.min.css');
		$this->carabiner->css('main.css');

		$this->carabiner->js('../jquery/jquery-1.12.3.min.js');
		$this->carabiner->js('../bootstrap/js/bootstrap.min.js');
		$this->carabiner->js('main.js');
		
		$this->render('mantenimiento');
	}

}