<?php
include "AdminController.php";

class Config extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['defaultSort'] = "orden";
		$this->load->model('Config_model', 'Config');
		$this->model = $this->Config;
		$this->page = "config";
		$this->data['currentModule'] = "config";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Opciones de Configuración";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
		
		$this->uploadsFolder = 'config';
		$this->uploads = array(
			'imagen_grupales' => ['type' => 'image', 'width' => 1905, 'height' => 350],
			'imagen_mobile_grupales' => ['type' => 'image', 'width' => 744, 'height' => 419]
		);
		
		//TODO: reemplazar
		$this->uploads_files = array(
							array(
								'name' => 'file_datos_cuenta',
								'prefix' => '',
								'keep' => true,
								'allowed_types' => 'pdf|doc|docx',
								'maxsize' => '10000000',
								'uploadsFolder' => '/uploads/',
								'folder' => '/uploads/config/',
								'suffix' => ''
							)
						);

		$this->load->model('MP_model', 'MP');
	}
	
	function index() {
		$this->data['formatos'] = array(array('id' => 'foto', 'caption' => 'Fotos'), array('id' => 'video', 'caption' => 'Video'));

		$this->data['cuotas_oficina'] = $this->MP->getMetodosPagoOficina();

		$this->edit(1);
	}	
		
	function onBeforeSave() {
		if($_POST['mp_gastos_admin']){
			$_POST['mp_gastos_admin'] = $_POST['mp_gastos_admin']/100+1;
		}
		if($_POST['pp_gastos_admin']){
			$_POST['pp_gastos_admin'] = $_POST['pp_gastos_admin']/100;//guardo solo el porcentaje correspondiente a gastos
		}
	}
	
	function save() {
		extract($_POST);
		
		$this->onBeforeSave();		

		if ($this->validate && $this->form_validation->run() == FALSE) {
			$this->data['errorsFound'] = TRUE;

			if ($_POST['id'] != '')
				$this->edit($_POST['id']);
			else
				$this->add();
			return;
		}	

		foreach ($_POST as $key=>$value){			
			$data[$key] = $value;
		}

		if (isset($id) && $id != '')
			$this->model->update($id, $data); 
		else
			$id = $this->model->insert($data);

		//File uploads
		if ($this->uploads_files && count($_FILES)>0) {
			$config['upload_path'] = $this->uploadsFolder;
			$config['allowed_types'] = $this->uploadsMIME;
			$config['max_size']	= '10000000';
			$this->load->library('upload', $config);

			//carpeta uploads
			/*
			if(!is_dir($this->uploadsFolder))
				mkdir($this->uploadsFolder,0777);
			*/
			
			foreach ($this->uploads_files as $upload) {
			
				$config['allowed_types'] = $upload['allowed_types'];
				$config['max_size']	= $upload['maxsize'];
				$config['upload_path'] = "." . $upload['folder'] . $id. '/';
			
				//1er nivel dentro de carpeta uploads
				if(!is_dir("." . $upload['folder']))
					mkdir("." . $upload['folder']);
			
				//2do nivel dentro de carpeta uploads
				if(!is_dir($config['upload_path'])) {
					mkdir($config['upload_path'], 0777);
				}
			
				$this->upload->initialize($config);
			
                $this->onBeforeUpload($config['upload_path']);
				if ($this->upload->do_upload($upload['name'])) {
					
                    $data = $this->upload->data();
					
					//Image resizes
					if (isset($upload['resizes'])) {
						
						$this->load->library('image_lib');
						foreach ($upload['resizes'] as $resize) {
							$config['image_library'] = 'gd2';
							$config['maintain_ratio'] = TRUE;
							$config['source_image']	= $config['upload_path'] . $data['file_name'];
							$config['width'] = $resize['width'];
							$config['height'] = $resize['height'];
							$config['new_image'] = $resize['prefix'] . $id . $resize['suffix'] . '.'.$data['file_ext'];
							$newname = $config['new_image'];
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();							
						}
					}
				
					//Keep original?
					if ($upload['keep']) {
						$newname = $upload['prefix'] . $data['file_name'] . (isset($upload['suffix'])?$upload['suffix']:'' );
						rename($config['upload_path'] . $data['file_name'], $config['upload_path'] . $newname);
					}
					else
						unlink($config['upload_path'] . $data['file_name']);
					
					//Save filenames in database
					$uploadsdata[$upload['name']] = $newname;
					$this->model->update($id, $uploadsdata); 
					$this->uploadsdata = $uploadsdata;
					
				} else {
					
					#echo $this->upload->display_errors();
					#exit();
				}
				
			}
		}

		$this->onAfterSave($id);
        
		//header("location:" . $this->data['route']);
        header("location:" . $this->data['route'].'/index/?saved=1');
	}
	
	function onAfterSave($id){
		$cuotas = isset($_POST['mp_id']) ? $_POST['mp_id'] : array();
		$cft = isset($_POST['cft']) ? $_POST['cft'] : array();
		$tea = isset($_POST['tea']) ? $_POST['tea'] : array();
		$tem = isset($_POST['tem']) ? $_POST['tem'] : array();
		
		foreach($cuotas as $k=>$v){
			$this->MP->update($k,array('coeficiente' => $v,
										'cft'=>@$cft[$k],
										'tea'=>@$tea[$k],
										'tem'=>@$tem[$k]
									));
		}
	}

	//nuevo para generar backup de base de datos general del sitio
	function backup_db(){
		$hoy = date('Y-m-d_His');
				
		// Load the DB utility class
		$this->load->dbutil();

		// Backup your entire database and assign it to a variable
		$prefs = array(
                'tables'      => array(),  // Array of tables to backup.
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'zip',             // gzip, zip, txt
                'filename'    => 'basededatos_'.$hoy.'.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );

		//$this->dbutil->backup($prefs);

		$backup =& $this->dbutil->backup($prefs); 

		// Load the file helper and write the file to your server
		//$this->load->helper('file');
		//write_file('./backup/basededatos/basededatos_'.$hoy.'.zip', $backup); 

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');

		force_download('backup_db_'.$hoy.'.zip', $backup);

	}
	
	/*//muestra links descargables
	function backup_sitio(){
		$this->data['lnk_system'] = base_url().'admin/config/backup_system';
		$this->data['lnk_media'] = base_url().'admin/config/backup_media';
		$this->load->view('admin/config_backup',$this->data);
	}*/
	
	//backup archivos del sistema (no media)
	function backup_system(){
		ini_set('memory_limit','512M');
		ini_set('max_execution_time',480);
		$hoy = date('Y-m-d_His');
		
		$this->load->library('zip');

		$path = '../application';
		$this->zip->read_dir($path); 
		$path = '../system';
		$this->zip->read_dir($path); 
		
		$this->zip->download('backup_site_system_'.$hoy.'.zip');
	}
	
	//carpeta de archivos media
	function backup_media(){
		ini_set('memory_limit','512M');
		ini_set('max_execution_time',480);
		$hoy = date('Y-m-d_His');
		
		$this->load->library('zip');
		
		//aca descarga archivos MEDIA
		$path = '../public';
		$this->zip->read_dir($path); 
		
		$this->zip->download('backup_site_media_'.$hoy.'.zip');
	}
	
	function iframe_backup(){

	}

}