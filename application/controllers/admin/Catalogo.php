<?php
include "AdminController.php";

class Catalogo extends AdminController{

	function __construct() {
		parent::__construct();
		$this->data['defaultSort'] = "orden";
		$this->load->model('Catalogo_model', 'Catalogo');
		$this->model = $this->Catalogo;
		$this->page = "catalogo";
		$this->data['currentModule'] = "catalogo";
		$this->data['uploadFolder'] = "premios";
		$this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);	
		$this->pageSegment = 4;
		$this->data['page_title'] = "Catálogo de Premios";
		$this->limit = 50;
		$this->init();
        $this->validate = FALSE;
	}
	
	function onEditReady($id='') {
		if ($id) {			
			$this->breadcrumbs[] = ($id!='')?$this->data['row']->titulo:'';			
		}
	}	

	function onBeforeSave() {
		$id = $this->input->post('id');
		if (!$id) {
			$id = entityid(TYPE_PREMIO);
			$_POST['id'] = $id;
		}
		else {
			//Verificar si se cambio la foto, para borrar las anteriores
			$row = $this->model->get($id)->row();
			if ($_POST['foto'] != $row->foto) {
				$path_parts = pathinfo($row->foto);
				$pattern = './uploads/'.$this->data['uploadFolder'].'/'.$id.'/'.$path_parts['filename'].'*';

				foreach (glob($pattern) as $file) {
					unlink("./uploads/".$this->data['uploadFolder'].'/'.$id.'/'.pathinfo($file, PATHINFO_BASENAME));
				}				
			}			
		}

		if (!isset($_POST['nuevo'])) $_POST['nuevo'] = 0;
		if (!isset($_POST['destacado'])) $_POST['destacado'] = 0;
		if (!isset($_POST['visible'])) $_POST['visible'] = 0;		
	}

	function onAfterDelete($id) {
		deleteEntity(TYPE_PREMIO, $id);
	}

	function upload() {
		if ($_POST['id'] != '') {
			$path = '/uploads/'.$this->data['uploadFolder'].'/'.$_POST['id'];
			$config['upload_path'] = '.'.$path;
		}
		else {
			$path = '/uploads/temp';
			$config['upload_path'] = '.'.$path;
		}
		
		if(!file_exists('./uploads')) {
			mkdir('./uploads', 0777);
		}
		
		if(!file_exists('./uploads/temp')) {
			mkdir('./uploads/temp', 0777);
		}
		
		if(!file_exists('./uploads/'.$this->data['uploadFolder'])) {
			mkdir('./uploads/'.$this->data['uploadFolder'], 0777);
		}
		
		if(!file_exists($config['upload_path'])) {
			mkdir($config['upload_path'], 0777);
		}
		
		$config['allowed_types'] = 'jpg|png|jpeg|gif';
		$config['max_size']	= '102400';
		$this->load->library('upload', $config);


		foreach ($_FILES as $key=>$updata) {

			if ($this->upload->do_upload($key)) {
				$data = $this->upload->data();

				
				//Se trata de la foto general
				if ($key == 'foto_upload') {
					//Completar el hidden con el nombre del archivo original
					echo "<script>parent.setField('foto', '".$data['file_name']."');</script>";

					//Procesar ahora todos los resizes a generar
					$sizes = $this->config->item('sizes');
					foreach ($sizes['premios'] as $size) {
						$src = base_url().$path.'/'.$data['file_name'];
						$sd = explode("x", $size);
						$target = $config['upload_path'].'/'.str_replace('.', '_'.$size.'.', $data['file_name']);
						if($data['image_height'] > $data['image_width']) { 
							file_put_contents(
								$target, 
								get_curl_data(base_url().'media/admin/resizer/resizer.php?src='.$src.'&h='.$sd[1])
							);
							
							echo "<script>parent.finishUpload('".$target."', '".$size."');</script>";

						} else {
							
							file_put_contents(
								$target, 
								get_curl_data(base_url().'media/admin/resizer/resizer.php?src='.$src.'&w='.$sd[0].'&h='.$sd[1])
							);
							echo "<script>parent.finishUpload('".$target."', '".$size."');</script>";
						}
						
					}
				}
				else {
					//Se trata de un override de alguna foto
					$fdata = explode("_", $key);
					$size = $fdata[2];

					$src = base_url().$path.'/'.$data['file_name'];
					$sd = explode("x", $size);
					$target = $config['upload_path'].'/'.str_replace('.', '_'.$size.'.', $data['file_name']);
					if($data['image_height'] > $data['image_width']) { 
						file_put_contents(
							$target, 
							get_curl_data(base_url().'media/admin/resizer/resizer.php?src='.$src.'&h='.$sd[1])
						);
						
						echo "<script>parent.finishUpload('".$target."', '".$size."');</script>";

					} else {
						
						file_put_contents(
							$target, 
							get_curl_data(base_url().'media/admin/resizer/resizer.php?src='.$src.'&w='.$sd[0].'&h='.$sd[1])
						);
						echo "<script>parent.finishUpload('".$target."', '".$size."');</script>";
					}
					

					//Completar el hidden con el nombre del archivo original
					echo "<script>parent.setField('foto', '".$data['file_name']."');</script>";
					
					echo "<script>parent.finishUpload('".$target."', '".$size."');</script>";					
				}
			}

		}
		echo "<script>parent.resetForm(); parent.Ladda.stopAll();</script>";
	}

	function onAfterSave($id){
		if (!empty($_POST['foto']) && file_exists("./uploads/temp/".$_POST['foto'])) {
			if(!file_exists("./uploads/".$this->data['uploadFolder']."/".$id."/")) {
				mkdir("./uploads/".$this->data['uploadFolder']."/".$id."/", 0777);
			}
			
			$path_parts = pathinfo('./uploads/temp/'.$_POST['foto']);
			$pattern = './uploads/temp/'.$path_parts['filename'].'*';

			foreach (glob($pattern) as $file) {
				rename($file, "./uploads/".$this->data['uploadFolder'].'/'.$id.'/'.pathinfo($file, PATHINFO_BASENAME));
			}			
		}
	}	 	
		
}