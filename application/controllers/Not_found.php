<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Not_found extends MY_Controller {

	public function __construct() {
		parent::__construct();	
		
	}

	public function index()
	{
		$this->data['body_id'] = 'home';
		
		$this->output->set_status_header(404);
		$this->render('404');
	}

}