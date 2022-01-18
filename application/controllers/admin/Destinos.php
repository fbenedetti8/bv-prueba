<?php
include "AdminController.php";

class Destinos extends AdminController{

	function __construct() {
		parent::__construct();
		$this->load->model('Destino_model', 'Destino');
		$this->load->model('Destino_foto_model', 'Destino_foto');
		$this->model = $this->Destino;
		$this->page = "destinos";
		$this->data['currentModule'] = "catalogos";
		$this->data['page'] = $this->page;
		$this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Destinos";
		$this->limit = 50;
		$this->init();
		$this->validate = FALSE;
		
		$this->uploadsFolder = 'destinos';
		//'imagen' => ['type' => 'image', 'width' => 1905, 'height' => 270],
		$this->uploads = array(
			'imagen' => ['type' => 'image', 'width' => 1140, 'height' => 633,
						 'resizes' => $this->config->item('resizes_destinos')
						]
		);
		$this->load->model('Lista_espera_model', 'Lista_espera');
	}
		
	function onEditReady($id='') {
		$this->load->model('Categoria_model', 'Categoria');
		$this->data['categorias'] = $this->Categoria->getList('', 'nombre asc');
		
		$this->load->model('Estacionales_model', 'Estacionales');
		$this->data['estacionales'] = $this->Estacionales->getList('', 'id asc');
		
		$this->data['mis_estacionales'] = [];
		$this->data['fotos'] = [];
		if ($id) {
			$this->data['mis_estacionales'] = $this->model->getEstacionales($id);
			
			//cargo las fotos del destino
			$this->data['fotos'] = $this->Destino_foto->getWhere(array('destino_id'=>$id))->result();	
		}
	}
	
	function onBeforeSave($id='') {
			
		$this->form_validation->set_rules('nombre','Nombre del Destino','required');
		$this->form_validation->set_rules('slug','Slug','required');
		$this->form_validation->set_rules('codigo','Código','required|min_length[3]');
		
		if ($this->form_validation->run() == FALSE) {
			if($_POST['id']>0){
				redirect(site_url('admin/destinos/edit/'.$_POST['id'].'?error=1'));
			}
			else{
				redirect(site_url('admin/destinos/add/?error=1'));
			}
		}
		
		$_POST['publicado'] = @$_POST['publicado'] ? $_POST['publicado'] : 0;
		$_POST['manifiesto'] = @$_POST['manifiesto'] ? $_POST['manifiesto'] : 0;		
	}
	
	function onAfterSave($id) {
		if($id){
			//asociacion de categorias estacionales con el destino
			$this->model->clearEstacionales($id);
			$_POST['estacionales'] = isset($_POST['estacionales']) ? $_POST['estacionales'] : array();
			foreach($_POST['estacionales'] as $d){
				if($d){
					$this->model->addEstacional($id,$d);
				}
			}
		}
		
		$this->generate_dynamic_routes();
		
		parent::onAfterSave($id);
	}
	
	//metodos para la galeria de fotos
	function upload_fotos($id=''){
		if($id > 0){

			// echo "<pre>";
			// print_r($_FILES);

			$this->uploadsMIME = array('jpeg', 'jpg', 'gif', 'png');

			//File uploads
			$config['upload_path'] = $this->uploadsFolder;
			$config['allowed_types'] = $this->uploadsMIME;
			$config['max_size']	= '10000000';
			$this->load->library('upload', $config);

			$upload = array(
							'name' => 'file',
							'prefix' => 'foto_',
							'keep' => true,
							'allowed_types' => $this->uploadsMIME,
							'maxsize' => '10000000',
							'folder' => '/uploads/destinos/'
						);					

			$config['upload_path'] = "." . $upload['folder'] . $id. '/';
		
			//1er nivel dentro de carpeta uploads
			if(!file_exists(".".$upload['folder']))
				mkdir("." . $upload['folder'],0777);
		
			//2do nivel dentro de carpeta uploads
			if(!file_exists($config['upload_path']))
				mkdir($config['upload_path'],0777);
		

			$this->load->library('Urlify');
			$source_file_name = $_FILES[$upload['name']]['name'];
			$fileinfo = pathinfo($source_file_name);
			$target_file_name = URLify::filter($fileinfo['filename']).'.'.strtolower($fileinfo['extension']);
			$config['file_name'] = $target_file_name;

			$this->upload->initialize($config);
		
		
			if ($this->upload->do_upload($upload['name'])) {
				
                $data = $this->upload->data();

				$orig_name = $data['file_name'];
				$new_name = eliminar_tildes($data['file_name']);
				
				rename($config['upload_path'].$orig_name,$config['upload_path'].$new_name);
				
				//unlink($config['upload_path'].$orig_name);
				
				//Save filenames in database
				#$uploadsdata['foto'] = $data['file_name'];
				$uploadsdata['foto'] = $new_name;
				$uploadsdata['destino_id'] = $id;
				$foto_id = $this->Destino_foto->insert($uploadsdata); 
				
				$ret['success'] = true;
				$ret['foto_id'] = $foto_id;
				echo json_encode($ret);
			} else {
				
				echo $this->upload->display_errors();
				
				$ret['success'] = false;
				echo json_encode($ret);
			}
					
				
		}
		else {
			return false;
		}

	}

	function borrar_foto($id){
		$foto = $this->Destino_foto->get($id)->row(); 
		unlink('./uploads/destinos/'.$foto->destino_id.'/'.$foto->foto);
		$this->Destino_foto->delete($id);
		echo 1;
	}

	/* Exporta la lista de Interesados para el destino */
	function exportar_lista_espera($id){
		$results = $this->Lista_espera->getAllExport($id,'destino');
		$this->page = "lista-de-interesados-destino-".$id;
		parent::exportar($results);
	}
	
}
