<?php
include "AdminController.php";

class Destacados extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Destacado_model', 'Destacado');
		$this->model = $this->Destacado;
		$this->page = "destacados";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Destacados Home";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;

		$this->uploadsFolder = 'destacados';
		$this->uploads = array(
			'imagen' => ['type' => 'image', 'width' => 1140, 'height' => 460],
			'imagen_mobile' => ['type' => 'image', 'width' => 767, 'height' => 720],
			'imagen_bg' => ['type' => 'image', 'width' => 1140, 'height' => 460],
			'imagen_bg_mobile' => ['type' => 'image', 'width' => 767, 'height' => 720],
			'video_mp4' => ['name'=>'video_mp4', 'type' => 'video', 'width' => 1140, 'height' => 460,'allowed_types' => 'mp4', 'maxsize' => '204800', 'folder' => '/uploads/destacados/', 'keep' => 'true', 'prefix' => ''],
			'video_webm' => ['name'=>'video_webm', 'type' => 'video', 'width' => 1140, 'height' => 460,'allowed_types' => 'webm', 'maxsize' => '204800', 'folder' => '/uploads/destacados/', 'keep' => 'true', 'prefix' => ''],
			'video_ogg' => ['name'=>'video_ogg', 'type' => 'video', 'width' => 1140, 'height' => 460,'allowed_types' => 'ogg', 'maxsize' => '204800', 'folder' => '/uploads/destacados/', 'keep' => 'true', 'prefix' => ''],
		);

		ini_set('upload_max_filesize','10M');
		ini_set('post_max_size','10M');
		ini_set('max_execution_time',3600);
		ini_set('memory_limit','512M');
	}


	function onBeforeSave($id='') {
		
		$_POST['visible'] = @$_POST['visible'] ? $_POST['visible'] : 0;	
	}
	
}