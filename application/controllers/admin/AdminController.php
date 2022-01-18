<?php
use Intervention\Image\ImageManager;

class AdminController extends MY_Controller {

    // --------------------------------------------------------------------

    /**
     * __construct()
     *
     * Class    Constructor PHP 5+ - not need if not setting things
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->config('admin');

        $this->config->set_item('url_suffix', '');

		if (false && $_SERVER['REMOTE_ADDR'] == '190.19.217.190') {
			$this->output->enable_profiler(TRUE);
		}

        $this->data['languages'] = $this->config->item('languages');
		$this->data['appName'] = $this->config->item('appName');
		$this->data['version'] = $this->config->item('appVersion');
		$this->data['currentSection'] = ($_SERVER['HTTP_HOST'] == 'lab.id4you.com') ? $this->uri->segment(3) : $this->uri->segment(2);
		$this->data['currentModule'] = "";
		$this->pageSegment = 0;
        $this->limit = 20;

		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->library('admin');

		//Configuracion por defecto de uploads
		$this->uploadsFolder = './uploads/';
		$this->uploadsMIME = 'gif|jpg|png|swf';
		$this->uploads = array();

		$this->breadcrumbs = array();

        //Carga de módulos
		$this->data['modules'] = array(
			array(
				"label"		=>	"config",
				"name"		=>	"<i class='glyphicon glyphicon-cog'></i> Configuración",
				"url"		=>	"config",
				"sections"	=>	array(
									array(
										"label"		=>	"admins",
										"name"		=>	"Administradores",
										"url"		=>	"admins",
									),
									array(
										"label"		=>	"config",
										"name"		=>	"Preferencias admin",
										"url"		=>	"config",
									),
									array(
										"label"		=>	"destacados",
										"name"		=>	"Destacados Home",//<i class='glyphicon glyphicon-home'></i>
										"url"		=>	"destacados",
									),
									/*array(
										"label"		=>	"viajes_grupales",
										"name"		=>	"Viajes grupales",
										"url"		=>	"viajes_grupales",
									),	*/
									array(
										"label"		=>	"conceptos",
										"name"		=>	"Conceptos",
										"url"		=>	"conceptos",
									),
									array(
										"label"		=>	"celulares_contacto",
										"name"		=>	"Celulares",
										"url"		=>	"celulares_contacto",
									),
									array(
										"label"		=>	"oficina",
										"name"		=>	"Oficina Feliz",
										"url"		=>	"oficina",
									),
									array(
										"label"		=>	"preguntas_categorias",
										"name"		=>	"Categorías de Preguntas",
										"url"		=>	"preguntas_categorias",
									),
									array(
										"label"		=>	"preguntas_frecuentes",
										"name"		=>	"Preguntas Frecuentes",
										"url"		=>	"preguntas_frecuentes",
									),
									array(
										"label"		=>	"telefonos_contacto",
										"name"		=>	"Teléfonos de contacto",
										"url"		=>	"telefonos_contacto",
									),
									array(
										"label"		=>	"testimonios",
										"name"		=>	"Testimonios",
										"url"		=>	"testimonios",
									),
									array(
										"label"		=>	"solas_solos",
										"name"		=>	"Solas y Solos",
										"url"		=>	"solas_solos",
									),
								)
			),
			array(
				"label"		=>	"catalogos",
				"name"		=>	"<i class='glyphicon glyphicon-list-alt'></i> Catalogos",
				"url"		=>	"catalogos",
				"sections" => array(
									array(
										"label"		=>	"destinos",
										"name"		=>	"Destinos",
										"url"		=>	"destinos",
									),
									array(
										"label"		=>	"alojamientos",
										"name"		=>	"Alojamientos",
										"url"		=>	"alojamientos",
									),
									array(
										"label"		=>	"habitaciones",
										"name"		=>	"Tipos de Habitaciones",
										"url"		=>	"habitaciones",
									),
									array(
										"label"		=>	"regimenes",
										"name"		=>	"Regímenes de Comidas",
										"url"		=>	"regimenes",
									),
									array(
										"label"		=>	"transportes",
										"name"		=>	"Transportes",
										"url"		=>	"transportes",
									),
									array(
										"label"		=>	"lugares_salida",
										"name"		=>	"Lugares de Salida",
										"url"		=>	"lugares_salida",
									),
									array(
										"label"		=>	"paradas",
										"name"		=>	"Paradas de Transportes",
										"url"		=>	"paradas",
									),
									array(
										"label"		=>	"adicionales",
										"name"		=>	"Adicionales",
										"url"		=>	"adicionales",
									),
									array(
										"label"		=>	"excursiones",
										"name"		=>	"Excursiones",
										"url"		=>	"excursiones",
									),
									array(
										"label"		=>	"documentaciones",
										"name"		=>	"Documentaciones",
										"url"		=>	"documentaciones",
									),
									array(
										"label"		=>	"medios",
										"name"		=>	"Medios de Pago",
										"url"		=>	"medios",
									),
									array(
										"label"		=>	"promociones",
										"name"		=>	"Promociones",
										"url"		=>	"promociones",
									),
								)
			),
			array(
				"label"		=>	"categorias",
				"name"		=>	"<i class='glyphicon glyphicon-tags'></i> Categorías",
				"url"		=>	"categorias",
				"sections" => array(
									array(
										"label"		=>	"categorias",
										"name"		=>	"Regionales",
										"url"		=>	"categorias",
									),
									array(
										"label"		=>	"estacionales",
										"name"		=>	"Estacionales",
										"url"		=>	"estacionales",
									),
								)
			),
			array(
				"label"		=>	"paquetes",
				"name"		=>	"<i class='glyphicon glyphicon-globe'></i> Paquetes",
				"url"		=>	"paquetes",
				"sections" => array(
									array(
										"label"		=>	"paquetes",
										"name"		=>	"Listado de Paquetes",
										"url"		=>	"paquetes",
									),
									array(
										"label"		=>	"paquetes",
										"name"		=>	"Celulares",
										"url"		=>	"celulares",
									),
								),
			),
			array(
				"label"		=>	"reservas",
				"name"		=>	"<i class='glyphicon glyphicon-list'></i> Reservas",
				"url"		=>	"reservas",
				"sections" => array(
									array(
										"label"		=>	"reservas",
										"name"		=>	"Listado de Reservas",
										"url"		=>	"reservas/vigentes",
									),
									array(
										"label"		=>	"alarmas",
										"name"		=>	"Alarmas",
										"url"		=>	"alarmas",
									),
									array(
										"label"		=>	"operadores",
										"name"		=>	"Operadores",
										"url"		=>	"operadores",
									),
									array(
										"label"		=>	"usuarios",
										"name"		=>	"Usuarios",//<i class='glyphicon glyphicon-list'></i>
										"url"		=>	"usuarios",
									),
									array(
										"label"		=>	"ordenes",
										"name"		=>	"Listado de Órdenes",
										"url"		=>	"ordenes",
									),
								)
			),
			array(
				"label"		=>	"reservas_vendedor",
				"name"		=>	"<i class='glyphicon glyphicon-list'></i> Reservas",
				"url"		=>	"reservas_vendedor",
			),
			array(
				"label"		=>	"vendedores",
				"name"		=>	"<i class='glyphicon glyphicon-list'></i> Vendedores",
				"url"		=>	"vendedores",
			),
			array(
				"label"		=>	"reportes",
				"name"		=>	"<i class='glyphicon glyphicon-stats'></i> Reportes",
				"url"		=>	"reportes",
				"sections" => array(
									array(
										"label"		=>	"facturacion",
										"name"		=>	"Facturación",//<i class='glyphicon glyphicon-usd'></i>
										"url"		=>	"facturacion",
									),
									/*array(
										"label"		=>	"ventas",
										"name"		=>	"Ventas",
										"url"		=>	"reportes/index/ventas",
									),	*/
									array(
										"label"		=>	"utilidades",
										"name"		=>	"Utilidades",
										"url"		=>	"reportes/index/utilidades",
									),
									array(
										"label"		=>	"ingresos",
										"name"		=>	"Ingresos",
										"url"		=>	"reportes/index/ingresos",
									),
								)
			),
			array(
				"label"		=>	"caja",
				"name"		=>	"<i class='glyphicon glyphicon-usd'></i> Caja",
				"url"		=>	"caja",
			),
			array(
				"label"		=>	"consultas",
				"name"		=>	"<i class='glyphicon glyphicon-info-sign'></i> Consultas",
				"url"		=>	"consultas",
				"sections"	=>	array(
									array(
										"label"		=>	"interesados_categorias",
										"name"		=>	"Interesados a Categorias",
										"url"		=>	"interesados_categorias",
									),
									array(
										"label"		=>	"interesados_destinos",
										"name"		=>	"Interesados a Destinos",
										"url"		=>	"interesados_destinos",
									),
									array(
										"label"		=>	"contactos",
										"name"		=>	"Contactos",
										"url"		=>	"contactos",
									),
									array(
										"label"		=>	"contactos_agencias",
										"name"		=>	"Contactos de Agencias",
										"url"		=>	"contactos_agencias",
									),
									array(
										"label"		=>	"contactos_faqs",
										"name"		=>	"Contactos Preguntas Frec.",
										"url"		=>	"contactos_faqs",
									),
									array(
										"label"		=>	"newsletter",
										"name"		=>	"Suscriptos a Newsletter",
										"url"		=>	"newsletter",
									),
								)
			),
			array(
				"label"		=>	"mailings",
				"name"		=>	"<i class='glyphicon glyphicon-picture'></i> Mailings",
				"url"		=>	"mailings",
				"sections"	=>	array(
									array(
										"label"		=>	"mailings",
										"name"		=>	"Listado de Mailings",
										"url"		=>	"mailings",
									),
									array(
										"label"		=>	"enviosmailing",
										"name"		=>	"Envios de Mailings",
										"url"		=>	"enviosmailing",
									),
								),
			),
			array(
				"label"		=>	"comisiones",
				"name"		=>	"<i class='glyphicon glyphicon-flag'></i> Comisiones",
				"url"		=>	"comisiones",
				"sections"	=>	array(
									array(
										"label"		=>	"comisiones",
										"name"		=>	"Liquidaciones",
										"url"		=>	"comisiones",
									),
									array(
										"label"		=>	"comisiones_escalas",
										"name"		=>	"Escalas",
										"url"		=>	"comisiones_escalas",
									),
									array(
										"label"		=>	"comisiones_porcentajes",
										"name"		=>	"Comisiones",
										"url"		=>	"comisiones_porcentajes",
									),
									array(
										"label"		=>	"comisiones_minimos",
										"name"		=>	"Minimos no Comisionables",
										"url"		=>	"comisiones_minimos",
									),
									array(
										"label"		=>	"equipos",
										"name"		=>	"Equipos",
										"url"		=>	"comisiones_equipos",
									),
									array(
										"label"		=>	"comisiones_reportes",
										"name"		=>	"Reportes",
										"url"		=>	"comisiones_reportes",
									),
								)
			)
		);

		//Verificar permisos por perfil
		$modules = [];
		foreach ($this->data['modules'] as $module) {
			if (isset($module['sections'])) {
				$sections = [];
				foreach ($module['sections'] as $section) {
					if (granted($module['label'], $section['label'])) {
						$sections[] = $section;
					}
				}
				if (count($sections)) {
					$module['sections'] = $sections;
					$modules[] = $module;
				}
			}
			else {
				if (granted($module['label'], $module['label'])) {
					$modules[] = $module;
				}
			}
		}
		$this->data['modules'] = $modules;

		foreach ($this->data['modules'] as $module) {
			$currentModule = $module['url'];
			if (isset($module['sections'])) {
				foreach ($module['sections'] as $section) {
					if ($this->data['currentSection'] == $section['url']) {
						$this->data['currentModule'] = $currentModule;
						break;
					}
				}
			}
		}

		//Controller memory
		if ($this->router->method == 'index') {
			if ($this->session->userdata('owner') == $this->router->class) {
				if (count($_POST)) {
					$this->session->set_userdata('search', $_POST);
				}
				else {
					if ($this->session->userdata('search')) {
						$_POST = array_merge($_POST, $this->session->userdata('search'));
					}
					$this->session->set_userdata('search', $_POST);
				}
			}
			else {
				$this->session->unset_userdata('search');
			}
			$this->session->set_userdata('owner', $this->router->class);
		}

		//Destroy session data
		if (!isset($_POST['sort']) && !isset($_POST['keywords']) && !$this->uri->segment($this->pageSegment-1)) { //Look for index segment
			$this->session->unset_userdata('sort');
			$this->session->unset_userdata('sortType');
			$this->session->unset_userdata('keywords');
		}

		//Sort for listings
		if (isset($_POST['sort'])) {
			$this->session->set_userdata('sort', $_POST['sort']);
			$this->session->set_userdata('sortType', $_POST['sortType']);
		}
		if ($this->session->userdata('sort')) {
			$sort = $this->session->userdata('sort');
			$sortType = $this->session->userdata('sortType');
			$this->data['sort'] = $sort;
			$this->data['sortType'] = $sortType;
		} else {
			$this->data['sort'] = "";
			$this->data['sortType'] = "";
		}

		//Filters
		if (isset($_POST['keywords'])) {
			$this->session->set_userdata('keywords', $_POST['keywords']);
		}
		if ($this->session->userdata('keywords')) {
			$this->data['keywords'] = $this->session->userdata('keywords');
		} else {
			$this->data['keywords'] = "";
		}

		//si no esta iniciada la sesion redirijo al login
		if( !$this->session->userdata('admin_id') ){
			redirect(site_url('admin/login'));
			exit();
		}

		if($_SERVER['REMOTE_ADDR'] == '190.19.217.190'){
			//$this->output->enable_profiler(TRUE);
		}
    }

    function reset(){
		$this->session->unset_userdata('keywords');
		$this->data['keywords'] = "";
		redirect(site_url('admin/'.$this->page));
	}

	function initDates() {
		if (!empty($_POST['from']) && !empty($_POST['to'])) {
			$from_date = strtotime($_POST['from']);
			$to_date = strtotime($_POST['to']);
		}
		elseif ($this->session->userdata('from') && $this->session->userdata('to')) {
			$from = $this->session->userdata('from');
			$to = $this->session->userdata('to');
			$from_date = strtotime($from['ymd']);
			$to_date = strtotime($to['ymd']);
		}
		else {
			$from_date = mktime(0, 0, 0, date('m')-1, date('d'), date('Y'));
			$to_date = time();
		}

		$from = array('ymd' => date('Y-m-d', $from_date), 'year' => date('Y', $from_date), 'month' => date('m', $from_date), 'day' => date('d', $from_date));
		$to = array('ymd' => date('Y-m-d', $to_date), 'year' => date('Y', $to_date), 'month' => date('m', $to_date), 'day' => date('d', $to_date));

		$this->session->set_userdata('from', $from);
		$this->session->set_userdata('to', $to);

		$this->data['from'] = $from;
		$this->data['to'] = $to;
	}

	function init($skip_permission=false){
		#echo $this->data['currentModule'].'-'.$this->data['page'];
		#exit();

        $this->initDates();
		$this->loadPagination();
		$this->data['header'] = $this->load->view('admin/header', $this->data, true);
		$this->data['footer'] = $this->load->view('admin/footer', $this->data, true);
		if (isset($this->data['page_title'])) {
			$this->breadcrumbs[] = "<a href='".$this->data['route']."'>".$this->data['page_title']."</a>";
		}
	}

	function index(){

		//Pagination
		$this->pconfig['total_rows'] = $this->model->count($this->data['keywords']);
		$this->pconfig['uri_segment'] = $this->pageSegment;
		$this->pagination->initialize($this->pconfig);
		$this->data['pages'] = $this->pagination->create_links();
		$this->data['totalRows'] = $this->pconfig['total_rows'];

		$this->data['sort'] = ($this->data['sort']) ? $this->data['sort'] : ((isset($this->model) && isset($this->model->defaultSort))?$this->model->defaultSort:"") ;
		$this->data['sortType'] = ($this->data['sortType']) ? $this->data['sortType'] : ((isset($this->model) && isset($this->model->defaultSortType))?$this->model->defaultSortType:"") ;

		$this->data['data'] = $this->model->getAll($this->pconfig['per_page'],$this->uri->segment($this->pconfig['uri_segment']), $this->data['sort'], isset($this->data['sortType'])?$this->data['sortType']:'desc', $this->data['keywords']);

		//echo $this->db->last_query();die;

		if($_SERVER['REMOTE_ADDR'] == '181.171.24.39'){
			//echo $this->db->last_query();
		}

		$this->data['saved'] = isset($_GET['saved'])?$_GET['saved']:'';
		$this->load->view('admin/' . $this->page, $this->data);
    }


	function add() {
		$this->onBeforeEdit('');
		$this->breadcrumbs[] = "Nuevo";

		$this->data['row'] = '';

		$this->onEditReady('');
		$this->load->view('admin/' . $this->page . '_form', $this->data);
	}

	function edit($id) {
		$this->onBeforeEdit($id);

		//Get data
		$results = $this->model->get($id);

		foreach ($results->result() as $row)
			$this->data['row'] = $row;

		$this->onEditReady($id);

		$this->load->view('admin/' . $this->page . '_form', $this->data);
	}

	function config($id) {
		$this->onBeforeEdit($id);

		//Get data
		$results = $this->model->get($id);
		foreach ($results->result() as $row)
			$this->data['row'] = $row;
		$this->data['data'] = $this->model->getAll(100, 0);

		$this->load->view('admin/' . $this->page . '_config', $this->data);
	}

	function photos($id) {
		//Get fotos
		$results = $this->modelPhoto->getAll($id)->result();
		$this->data['results'] = $results;
		$this->data['id'] = $id;
		$this->load->view('admin/' . $this->page . '_fotos', $this->data);
	}

	function delete($id) {
		$this->onBeforeDelete($id);

		$this->model->delete($id);

		if (count($this->uploads)>0) {
			foreach ($this->uploads as $upload) {
				if (isset($upload['resizes'])){
					foreach ($upload['resizes'] as $resize) {
						unlink(realpath($this->uploadsFolder . $id . '_' . $resize['suffix'] . '.jpg'));
					}
				}

				if (isset($upload['folder']) && isset($upload['prefix'])){
					if( file_exists('.' . $upload['folder'] . $id . '/' . $upload['prefix'] . $id . '.jpg') ){
						unlink('.' .$upload['folder'] . $id . '/' . $upload['prefix'] . $id . '.jpg');
					}
				}
			}

			if (file_exists('.' . $upload['folder'] . $id . '/')) {
				rmdir('.' . $upload['folder'] . $id . '/');
			}
		}

		$this->onAfterDelete($id);

		redirect(site_url('admin/'.$this->page));
	}

	function onBeforeEdit($id="") {
	}

	function onEditReady($id="") {
	}

	function onBeforeSave(){
	}

    function onBeforeUpload($upload_path){
    }

	function onAfterSave($id) {
		if ($this->uploads) {
			if (!file_exists('./uploads/'.$this->uploadsFolder)) {
				mkdir('./uploads/'.$this->uploadsFolder);
			}

			if (!file_exists('./uploads/'.$this->uploadsFolder.'/'.$id)) {
				mkdir('./uploads/'.$this->uploadsFolder.'/'.$id);
			}

			foreach ($this->uploads as $file=>$upload) {
				$file = $this->input->post($file);
				if ($file && file_exists('./uploads/temp/'.$file)) {
					rename('./uploads/temp/'.$file, './uploads/'.$this->uploadsFolder.'/'.$id.'/'.$file);

					//si tiene configurado resizes y existen, tambien los copio
					$resizes = isset($upload['resizes']) ? $upload['resizes'] : [];
					if(count($resizes)){
						foreach($resizes as $r){
							$filename = $file;
							$filename = explode('.',$filename);
							$filename = $filename[0].'_'.$r['width'].'x'.$r['height'].'.'.$filename[1];
							if ($filename && file_exists('./uploads/temp/'.$filename)) {
								rename('./uploads/temp/'.$filename, './uploads/'.$this->uploadsFolder.'/'.$id.'/'.$filename);
							}
						}
					}
				}
			}
		}
	}

	function onBeforeDelete($id) {
	}

	function onAfterDelete($id) {
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
		if ($this->uploads && count($_FILES)>0) {
			$config['upload_path'] = $this->uploadsFolder;
			$config['allowed_types'] = $this->uploadsMIME;
			$config['max_size']	= '10000000';
			$this->load->library('upload', $config);

			//carpeta uploads
			if(!is_dir($this->uploadsFolder))
				mkdir($this->uploadsFolder,0777);
			foreach ($this->uploads as $upload) {


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
						//si tiene configurado nuevos resizes por hacer
						foreach($upload['resizes'] as $r){
							$newname = $data['file_name'];
							$newname = explode('.',$newname);

							$manager = new ImageManager();
							$img = $manager->make($data['full_path']);
							$img->resize($r['width'], null, function ($constraint) {
							    $constraint->aspectRatio();
							});
							$img->save($config['upload_path'] . $newname[0] . '_' . $r['width'] . 'x' . $r['height'] . $data['file_ext']);

						}

						/*
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
						*/
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

					echo $this->upload->display_errors();
					#exit();
				}

			}
		}

		$this->onAfterSave($id);

		//header("location:" . $this->data['route']);
        header("location:" . $this->data['route'].'/index/?saved=1');
	}

	function savePhoto() {
		extract($_POST);

		$this->onBeforeSave();

		foreach ($_POST as $key=>$value){
			$data[$key] = $value;
		}

		if (isset($id) && $id != '')
			$this->modelPhoto->update($id, $data);
		else
			$id = $this->modelPhoto->insert($data);

		//File uploads
		if (count($_FILES)>0) {
			$config['upload_path'] = $this->uploadsFolder;
			$config['allowed_types'] = $this->uploadsMIME;
			$config['max_size']	= '10000000';
			$this->load->library('upload', $config);

			foreach ($this->uploads as $upload) {

				$config['allowed_types'] = $upload['allowed_types'];
				$config['max_size']	= $upload['maxsize'];
				$config['upload_path'] = "." . $upload['folder'] . $fk. '/';

				//carpeta uploads
				if(!is_dir($this->uploadsFolder))
					mkdir($this->uploadsFolder);

				//1er nivel dentro de carpeta uploads
				if(!is_dir($upload['folder']))
					mkdir("." . $upload['folder']);

				//2do nivel dentro de carpeta uploads
				if(!is_dir($config['upload_path']))
					mkdir($config['upload_path']);

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
							$config['new_image'] = $resize['prefix'] . $id . $resize['suffix'] . '.jpg';
							$newname = $config['new_image'];
							$this->image_lib->initialize($config);
							$this->image_lib->resize();
						}
					}

					//Keep original?
					if ($upload['keep']) {
						$newname = $upload['prefix'] . $id . $upload['suffix'] . $data['file_ext'];
						rename($config['upload_path'] . $data['file_name'], $config['upload_path'] . $newname);
					}
					else
						unlink($config['upload_path'] . $data['file_name']);

					//Save filenames in database
					//$uploadsdata[$upload['name']] = $newname;
					//$this->modelPhoto->update($id, $uploadsdata);

				} else {

					echo $this->upload->display_errors();
				}

			}

		}

		//$this->onAfterSave($id);

		//header("location:" . $this->data['route'] . '/photos/' . $fk);
	}

	function deletePhoto($photo_id,$fk){
		$this->modelPhoto->delete($photo_id);
		unlink("./uploads/".$this->page."/".$fk."/fg_".$photo_id.".jpg");
		header('location: /admin/'.$this->page.'/photos/'.$fk);
	}

	function addPhoto($fk){
		$this->init();

		$this->data['fk'] = $fk;
		$this->load->view('admin/'.$this->page.'_fotos_form',$this->data);
	}

	function editPhoto($id){
		$results = $this->modelPhoto->get($id);
		foreach ($results->result() as $row){
			$this->data['row'] = $row;
			//$this->data['fk'] = $row->fk;
		}

		$this->load->view('admin/'.$this->page.'_fotos_form', $this->data);
	}

	function destacar($id,$value){
		$data = array('destacado' => $value);
		$this->model->update($id, $data);
		echo $value;
	}

	function proximamente($id,$value){
		$data = array('proximamente' => $value);
		$this->model->update($id, $data);
		echo $value;
	}

	function visible($id,$value){
		$data = array('visible' => $value);
		$this->model->update($id, $data);
		echo $value;
	}

	function destacada_home($id,$value){
		$data = array('destacada_home' => $value);
		$this->model->update($id, $data);
		echo $value;
	}

	public function loadPagination(){
		//Pagination configuration
		$this->pconfig['base_url'] = $this->data['route'].'/index/';
		$this->pconfig['per_page'] = $this->limit;
		$this->pconfig['num_links'] = '5';
		$this->pconfig['full_tag_open'] = '<ul class="pagination">';
		$this->pconfig['full_tag_close'] = '</ul>';
		$this->pconfig['first_link'] = 'Primero';
		$this->pconfig['first_tag_open'] = '<li>';
		$this->pconfig['first_tag_close'] = '</li>';
		$this->pconfig['last_link'] = '&Uacute;ltimo';
		$this->pconfig['last_tag_open'] = '<li>';
		$this->pconfig['last_tag_close'] = '</li>';
		$this->pconfig['next_link'] = '→';
		$this->pconfig['next_tag_open'] = '<li>';
		$this->pconfig['next_tag_close'] = '</li>';
		$this->pconfig['prev_link'] = '←';
		$this->pconfig['prev_tag_open'] = '<li>';
		$this->pconfig['prev_tag_close'] = '</li>';
		$this->pconfig['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
		$this->pconfig['cur_tag_close'] = '</a></li>';
		$this->pconfig['num_tag_open'] = '<li>';
		$this->pconfig['num_tag_close'] = '</li>';

		$this->pconfig['uri_segment'] = $this->pageSegment;
		$this->pagination->initialize($this->pconfig);
		$this->data['pages'] = $this->pagination->create_links();
	}

	function ordenar() {
		for ($i=0; $i<count($_POST['orden']); $i++) {
			$this->model->update($_POST['id'][$i], array('orden' => $_POST['orden'][$i]));
		}
	}

	function onBeforeExport(){

	}

	function exportar($res=false, $filename=""){
		$this->onBeforeExport();

		if(!$res)
			$results = $this->model->getAll()->result();
		else
			$results = $res;

    if ($filename == "") $filename = $this->page;

		exportExcel($results,$filename);
	}

	function upload_remove() {
		$img = $this->input->post('img');

		if ($img) {
			$url = str_replace(base_url(), '', $img);
			if (file_exists('./'.$url)) {
				unlink('./'.$url);
			}
		}
	}

	function iframe() {

	}

	function upload() {

		$this->load->helper(array('form', 'url'));
		$this->load->library('aws_s3');
		
		$config['upload_path'] = './uploads/temp/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 2048;
		
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('foto')) {
			//$this->upload->display_errors();
			$data = array('status' => 500, 'msj' => "No fue posible cargar el archivo. ");
			echo json_encode($data);
		}else{
			$upload_s3 = $this->aws_s3->send_file_to_s3(
				$this->upload->data('full_path'), 
				$this->upload->data('file_name')
			);
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($upload_s3);
		}



		/*
		array(
			["file_name"]=> string(30) "WIN_20200404_01_30_46_Pro1.jpg" 
			["file_type"]=> string(10) "image/jpeg" 
			["file_path"]=> string(49) "C:/xampp/htdocs/buenasvibras/public/uploads/temp/" 
			["full_path"]=> string(79) "C:/xampp/htdocs/buenasvibras/public/uploads/temp/WIN_20200404_01_30_46_Pro1.jpg" 
			["raw_name"]=> string(26) "WIN_20200404_01_30_46_Pro1" 
			["orig_name"]=> string(29) "WIN_20200404_01_30_46_Pro.jpg" 
			["client_name"]=> string(29) "WIN_20200404_01_30_46_Pro.jpg" 
			["file_ext"]=> string(4) ".jpg" 
			["file_size"]=> float(152.73) 
			["is_image"]=> bool(true) 
			["image_width"]=> int(1280) 
			["image_height"]=> int(720) 
			["image_type"]=> string(4) "jpeg" 
			["image_size_str"]=> string(25) "width="1280" height="720"" }
			*/

			/*
			extract($_POST);
			$this->load->library('Urlify');
			$this->load->library('aws_s3');

			if (!isset($_FILES['foto']) || (isset($_FILES['foto']) && $_FILES['foto']['error'])) {
					$data['success'] = FALSE;
					$data['error'] = 'No fue posible cargar el archivo. ';
					echo json_encode($data);
			}else{
				
				$target = './uploads/temp/';
				if (!file_exists($target)) {
						mkdir($target);
				}

				$source_file_name = $_FILES['foto']['name'];
				$fileinfo = pathinfo($source_file_name);
				$target_file_name = URLify::filter($fileinfo['filename']).'.'.strtolower($fileinfo['extension']);

				$config['upload_path'] = $target;
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['file_ext_tolower'] = TRUE;
				$config['max_size'] = 2048;
				$config['file_name'] = $target_file_name;

				$this->load->library('upload');
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('foto')) {
						$data['success'] = FALSE;
						$data['error'] = 'No fue posible cargar el archivo. ';
						echo json_encode($data);
				}else {			
						$filedata = $this->upload->data();
						$dir_path = './uploads/temp/';
						$path = $dir_path.$filedata['file_name'];
						
						//Resize
						if ($this->uploads[$_POST['field']]['type'] == 'image') {

								$img_config['image_library'] = 'gd2';
								$img_config['source_image'] = $path;
								$img_config['width'] = $this->uploads[$_POST['field']]['width'];
								$img_config['height'] = $this->uploads[$_POST['field']]['height'];
								$this->load->library('image_lib', $img_config);
								$this->image_lib->resize();

								//si tiene configurado nuevos resizes por hacer
								$resizes = isset($this->uploads[$_POST['field']]['resizes']) ? $this->uploads[$_POST['field']]['resizes'] : [];

								if(count($resizes)){

									foreach($resizes as $r){
											$newname = $filedata['file_name'];
											$newname = explode('.',$newname);

											$manager = new ImageManager();
											$img = $manager->make($filedata['full_path']);
											$img->resize($r['width'], null, function ($constraint) {
													$constraint->aspectRatio();
											});

											$new_name = $newname[0] . '_' . $r['width'] . 'x' . $r['height'] . $filedata['file_ext'];
											
											//$this->aws_s3->send_file_to_s3($path, $new_name);

											$img->save($dir_path . $newname[0] . '_' . $r['width'] . 'x' . $r['height'] . $filedata['file_ext']);
									}

								}

						}

						$data['success'] = TRUE;
						$data['url'] = base_url().'uploads/temp/'.$filedata['file_name'];
						$data['filename'] = $filedata['file_name'];
						echo json_encode($data);
				}
			}*/
	}

	function img_resize(){
			//Resize
			$img_config['image_library'] = 'gd2';
			$img_config['source_image'] = $path;
			$img_config['width'] = $this->uploads[$_POST['field']]['width'];
			$img_config['height'] = $this->uploads[$_POST['field']]['height'];
			$this->load->library('image_lib', $img_config);
			$this->image_lib->resize();

			//si tiene configurado nuevos resizes por hacer
			$resizes = isset($this->uploads[$_POST['field']]['resizes']) ? $this->uploads[$_POST['field']]['resizes'] : [];

			if(count($resizes)){
				foreach($resizes as $r){
					$newname = $filedata['file_name'];
					$newname = explode('.',$newname);

					$manager = new ImageManager();
					$img = $manager->make($filedata['full_path']);
					$img->resize($r['width'], null, function ($constraint) {
							$constraint->aspectRatio();
					});

					$new_name = $newname[0] . '_' . $r['width'] . 'x' . $r['height'] . $filedata['file_ext'];
					
					//$this->aws_s3->send_file_to_s3($path, $new_name);
					$img->save($dir_path . $newname[0] . '_' . $r['width'] . 'x' . $r['height'] . $filedata['file_ext']);
				}
			}
	}

	/* Carga tabla con cuenta corriente del usuario y tipousuario */
	function cargar_cuenta_corriente($usuario_id,$tipo,$reserva_id=false){
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('MP_model','MP');

		$origen = 'backend';
		$this->data['metodos_pago'] = $this->MP->getMetodosPago($origen);

		switch($tipo){
			case 'U':
				$this->Movimiento->filters = "bv_movimientos.tipoUsuario = 'U' and bv_movimientos.usuario_id = ".$usuario_id;
			break;
			case 'A':
				$this->Movimiento->filters = "bv_movimientos.tipoUsuario = 'A' and bv_movimientos.usuario_id = ".$usuario_id;
			break;
			case 'P':
				$this->Movimiento->filters = "bv_movimientos.tipoUsuario = 'P' and bv_movimientos.usuario_id = ".$usuario_id;
			break;
			case 'V':
				$this->Movimiento->filters = "bv_movimientos.tipoUsuario = 'V' and bv_movimientos.usuario_id = ".$usuario_id;
			break;
		}

		$moneda_activa = isset($_GET['moneda']) ? $_GET['moneda'] : 'ARS';

		if($reserva_id){
			//si estoy en la cuenta de la RESERVA, solo traigo sus movimientos
			$this->Movimiento->filters .= " and bv_movimientos.reserva_id = ".$reserva_id;

			$res = $this->Reserva->get($reserva_id)->row();

			//si el viaje es en USD, entonces lo fuerzo a cta cte en USD
			if($res->precio_usd){
				$moneda_activa = 'USD';

				//solo los movimientos de los viajes en USD
				// $this->Movimiento->filters .= " and p.precio_usd = 1";

				//los movimientos de la cta en usd
				$this->Movimiento->filters .= " and bv_movimientos.cta_usd = 1";
			}
			else{
				//sino, el ARS
				$moneda_activa = 'ARS';

				//solo los movimientos de los viajes en ARS
				// $this->Movimiento->filters .= " and p.precio_usd = 0";

				//los movimientos de la cta en ars
				$this->Movimiento->filters .= " and bv_movimientos.cta_usd = 0";
			}

			//los movimientos de la cuenta son en funcion de la cta sea en usd o ars
			/*if($moneda_activa == 'USD'){

				//solo los movimientos de la CTA en USD
				$this->Movimiento->filters .= " and bv_movimientos.cta_usd = 1";
			}
			else{
				//solo los movimientos de la CTA en ARS
				$this->Movimiento->filters .= " and bv_movimientos.cta_usd = 0";
			}*/

			/*
			if($moneda_activa == 'USD' && !$res->precio_usd){
				//Si es CTA CTE en USD y si el paquete es en ARS, no muestro los registros
				//con debe_usd y haber_usd ambos en 0 (eso significa que para esos no hubo registracion)
				$this->Movimiento->filters .= " and (bv_movimientos.debe_usd > 0 or bv_movimientos.haber_usd > 0)";
			}
			*/
		}
		else{
			if($moneda_activa == 'USD'){
				$this->Movimiento->filters .= " and p.precio_usd = 1";
			}
			else{
				$this->Movimiento->filters .= " and p.precio_usd = 0";
			}
		}

		$movimientos = $this->Movimiento->getAll(9999,0,'bv_movimientos.fecha','asc');

		if($_SERVER['REMOTE_ADDR'] == '181.171.24.39'){
			//echo $this->db->last_query();
		}

		$this->data['movimientos'] = $movimientos;

		//obtengo los pagos (FACTURAS) hechos por parte del usuario para que si genera
		//una Nota de Credito, la pueda asociar a la factura que corresponda
		$this->data['facturas_generadas'] = $this->Movimiento->get_pagos_de_reserva($reserva_id,$usuario_id,$tipo)->result();

		//puede que ya sepa sobre que reserva voy a generar el movimiento
		$this->data['reserva_id'] = $reserva_id;

		//para saber el tipo y ID de usuario
		$this->data['usuario_id'] = $usuario_id;
		$this->data['tipo'] = $tipo;
		$this->data['moneda_activa'] = $moneda_activa;

		//listado de reservas del pasajero
		$this->Reserva->filters = "usuario_id = ".$usuario_id;
		$this->data['reservas'] = $this->Reserva->getAll(999,0,'fecha_reserva','desc')->result();

		$this->data['cuenta_corriente'] = $this->load->view('admin/cuenta_corriente',$this->data,true);
	}

	function verq(){
		echo $this->session->userdata('query');
	}

	/* Grabar movimiento en cuenta corriente del usuario */
	function grabarMovimiento(){
		error_reporting(E_ALL);
		ini_set('display_errors', true);
		extract($_POST);
		//pre($_POST);exit();

		ini_set('max_execution_time',360);

		if(!isset($informe_id))
			$informe_id = 0;

		//le cambio el formato al que admite la DB
		if(!isset($debe))
			$debe = 0;
		else
			$debe = str_replace(array('.',','),array('','.'),$debe);

		if(!isset($haber))
			$haber = 0;
		else
			$haber = str_replace(array('.',','),array('','.'),$haber);

		if(!isset($gastos_administrativos) || !$gastos_administrativos)
			$gastos_administrativos = 0;
		else
			$gastos_administrativos = str_replace(array('.',','),array('','.'),$gastos_administrativos);


		if(!isset($intereses) || !$intereses)
			$intereses = 0;
		else
			$intereses = str_replace(array('.',','),array('','.'),$intereses);

		if(!isset($tipo_cambio))
			$tipo_cambio = 0;
		else
			$tipo_cambio = str_replace(array('.',','),array('','.'),$tipo_cambio);

		if($debe == 0 && $haber == 0){
			$ret = array();
			$ret['status'] = 'error';
			$ret['msg'] = 'Los valores ingresados en Debe y Haber no pueden ser 0.';
			echo json_encode($ret);
			exit();
		}

		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Sucursal_model','Sucursal');
		$this->load->model('Concepto_model','Concepto');
		$this->load->model('Factura_model','Factura');
		$this->load->model('Caja_model','Caja');
		$this->load->model('Caja_descuento_model','Caja_descuento');
		$this->load->model('Combinacion_model','Combinacion');

		$reserva = $this->Reserva->get($reserva_id)->row();

		//me fijo si la reserva tiene asociada la sucursal -> sino le pongo por defecto BUENOS AIRES
		if($reserva->sucursal_id == 0){
			$this->Reserva->updateWhere(array('id'=>$reserva_id),array('sucursal_id'=>'1'));
			$reserva->sucursal_id = 1;
		}

		$sucursal = $this->Sucursal->get($reserva->sucursal_id)->row();

		$conceptoObj = $this->Concepto->getBy('nombre='.$concepto);

		//chequeo si los pagos son con TARJETA DE CREDITO EN CUOTAS para facturar los intereses
		//$cuotas $conceptoObj->id IN (21,22)

		$reserva_queda_anulada = false;

		//si el concepto implica que pase a confirmada y acaba de generar pago y estaba anulada, le genero registrio inicial de reserva
		if($conceptoObj->pasa_a_confirmada && $haber>0 && $reserva->estado_id == 5){
			//chequear si hay cupo en el viaje (combinacion) para la cantidad de pasajeros de la reserva
			$filtros = array();
			$filtros['pax'] = $reserva->pasajeros;
			//$filtros['disponibles'] = '1';
			$filtros['combinacion_id'] = $reserva->combinacion_id;
			$data_combinacion = $this->Combinacion->getByPaquete($reserva->paquete_id,1,$filtros);

			$this->session->set_userdata('query',$this->db->last_query());

			//si hay combinacion ,chequeo que la cantidad de pasajeros me alcance para ambos cupos de transporte y alojamiento
			//se muestra esta alarma en el listado de reservas del back
			if(isset($data_combinacion->id) && $data_combinacion->id &&
				($reserva->pasajeros > $data_combinacion->cupo_trans || $reserva->pasajeros > $data_combinacion->cupo_aloj) ){

				//seteo marca para que luego de facturar la ponga en ANULADA
				$reserva_queda_anulada = true;

				registrar_comentario_reserva($reserva->id,7,'comentario','Se recibió pago nuevo pero no hay cupo para el viaje');
			}
			else{
				//pasar a nueva (con registro del movimiento por el importe de reserva)

				//genero movimiento en cta cte de ese usuario
				$reserva_id = $reserva->id;
				$usuario_id = $reserva->usuario_id;
				$fecha_reserva = date('Y-m-d H:i:s');
				$monto_reserva = $reserva->paquete_precio+$reserva->impuestos+$reserva->adicionales_precio;

				$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($usuario_id,"U",$reserva->precio_usd)->row();
				$nuevo_parcial = (isset($mov->parcial) ? $mov->parcial : .00 ) + $monto_reserva;

				//globales
				$factura_id='';$talonario='';$factura_asociada_id='';$talonario_asociado='';
				$moneda = (isset($moneda) && $moneda ) ? $moneda : ($reserva->precio_usd ? 'USD' : 'ARS');
				$tipo_cambio = (isset($tipo_cambio) && $tipo_cambio ) ? $tipo_cambio : $this->settings->cotizacion_dolar;

				registrar_movimiento_cta_cte($reserva->usuario_id,"U",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);

				//tambien seteo marcas en el historial para que luego se le envie mail de reserva
				$mail = true;
				$template = 'datos_reserva';
				registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva,$mail,$template);

				//genera movimiento en cta cte de BUENAS VIBRAS por monto de reserva
				$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A",$reserva->precio_usd)->row();
				$nuevo_parcial2 = (isset($mov2->parcial) ? $mov2->parcial : .00 ) + $monto_reserva;
				registrar_movimiento_cta_cte(1,"A",$reserva_id,$fecha_reserva,$reserva->nombre." - ".$reserva->paquete_codigo,$monto_reserva,0.00,$nuevo_parcial2,'','',$factura_id='',$talonario='',$factura_asociada_id='',$talonario_asociado='',$moneda,$tipo_cambio);
				registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo.' | DEBE '.$monto_reserva);
				//-------------------------------------------------------------------------------------------------------------------

			}
		}

		// FACTURA-------------------
		$talonario = $sucursal->talonario;

		//solo para facturas y notas de credito en efectivo de argentina
		//el talonario puede ser yellow
		if( $talonario == 'X' && $reserva->exterior == 0 && ($concepto == 'PAGO EN EFECTIVO' || $concepto == 'NOTA DE CREDITO - EFECTIVO')){
			$talonario = 'X';
		}

		$paquete = $this->Paquete->get($reserva->paquete_id)->row();

		$valor_factura = 0;
		if(isset($debe) && $debe > 0){
			$tipo_comprobante = 'NC_'.$talonario;
			$valor_factura = $debe;
		}
		else if(isset($haber) && $haber > 0){
			//me fijo si tiene el COSTO CARGADO o no el paquete para generar un RECIBO o FACTURA
			if($paquete->c_costo_operador > 0){
				$tipo_comprobante = 'FA_'.$talonario;
			}
			else{
				//asumo que no tiene el costo cargado => genero RECIBO
				$tipo_comprobante = 'RE_X';
			}

			$valor_factura = $haber;
		}

		$valor_total = $valor_factura;

		//01-03-19 si el concepto de pago es PAYPAL le tengo que sacar el importe de gastos al valor, para no cargarselo al pasajero
		if($conceptoObj->id == 70 || $conceptoObj->id == 71 ){
			$valor_factura = number_format($valor_factura*(1-$this->settings->pp_gastos_admin),2,'.','');
		}

		if($moneda=='USD'){
			$valor_factura = $valor_factura*$tipo_cambio;
			$gastos_administrativos = $gastos_administrativos*$tipo_cambio;
			$intereses = $intereses*$tipo_cambio;
		}

		//le sumo el importe fijo pasado a ARS para la factura
		if($conceptoObj->id == 70 || $conceptoObj->id == 71 ){
			$valor_factura -= $this->settings->pp_gastos_admin_fijos*$tipo_cambio;
		}

		$datos_factura['sucursal_id'] = $reserva->sucursal_id;
		$datos_factura['vendedor_id'] = $reserva->vendedor_id;
		$datos_factura['usuario_id'] = $tipo_id;
		$datos_factura['reserva_id'] = $reserva_id;
		$datos_factura['fecha'] = date('Y-m-d H:i:s');
		$datos_factura['valor'] = $valor_factura;
		$datos_factura['punto_venta'] = 2;
		$datos_factura['forma_pago'] = $concepto;
		$datos_factura['total'] = $valor_factura;
		$datos_factura['gastos_adm'] = ( isset($gastos_administrativos) && $gastos_administrativos > 0 ) ? $gastos_administrativos : 0.00;
		$datos_factura['intereses'] = ( isset($intereses) && $intereses > 0 ) ? $intereses : 0.00;

		//Si corresponde facturacion, generar comprobante
		if ($conceptoObj->facturacion) {
			$datos_factura['tipo_factura'] = $talonario;
			$datos_factura['tipo'] = $tipo_comprobante;

			//agregado 08/07/14
			$datos_factura['concepto_id'] = $conceptoObj->id;
			$factura_id = $this->Factura->insert($datos_factura);

		}
		else {
			$factura_id = 0;
		}
		//---------------------------------------

		/*
		//al monto a facturar le sumo los intereses de tarjeta de credito
		if($intereses > 0 && $haber > 0){
			$haber += $intereses;
		}
		if($intereses > 0 && $debe > 0){
			$debe += $intereses;
		}
		*/

		//obtengo parcial segun ultimo movimiento en base a $tipo_id,$tipo,
		//genero movimiento en cta cte de ese usuario en concepto de Pago de parte o total de la deuda
		$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($tipo_id,$tipo)->row();

		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		if(isset($mov->parcial))
			$nuevo_parcial = $mov->parcial+$debe-$haber;
		else
			$nuevo_parcial = .00+$debe-$haber;

		$fecha = date("Y-m-d H:i:s");

		$new_concepto = $concepto;

		//24-07-15 si es una nota de credito, le asocio la factura y el tipo de talonario
		$factura_asociada_id = '';
		$talonario_asociado = '';
		if(isset($factura_asociada) && $factura_asociada != ''){
			$asoc = explode('|',$factura_asociada);
			$factura_asociada_id = isset($asoc[0]) ? $asoc[0] : '';
			$talonario_asociado = isset($asoc[1]) ? $asoc[1] : '';
		}

		$movimiento_id = registrar_movimiento_cta_cte($tipo_id,$tipo,$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id);

		if($tipo == 'V')
			registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del vendedor. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);
		else if($tipo == 'U')
			registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del usuario. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);

		//idem genero movimiento en cta cte de BBV en concepto de Pago de parte o total de la deuda por parte del usuario o agencia externa
		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A")->row();
		$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) + $debe - $haber;

		$movimiento_agencia_id = registrar_movimiento_cta_cte(1,'A',$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial2,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id);
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);

		//16/01/2020 si hay un comentario asociado al pago, lo registro en el historial
		$comentarios_mov = isset($_POST['comentarios_mov']) && $_POST['comentarios_mov'] ? $_POST['comentarios_mov'] : '';
		if($comentarios_mov){
			registrar_comentario_reserva($reserva_id,7,'comentario','Nuevo comentario interno asociado al movimiento.<br>'.$comentarios_mov,false,'');

		}


		// pasa a CONFIRMADA si el concepto lo especifica -> según el estado TARJETA DE CREDITO / PAGO FACIL (MERCADOPAGO) -> Sí
		//if($conceptoObj->pasa_a_confirmada == 1){
		//si está seteada esta marca ($reserva_queda_anulada) la dejo en anulada y no la CONFIRMO
		if ($conceptoObj->pasa_a_confirmada == 1 && !$reserva_queda_anulada){
			//$pagos = $this->Movimiento->getPagosHechos($reserva_id, $reserva->usuario_id);
			//TODO: habria que confirmar si lo que pago alcanza para confirmar la reserva. Hay que verificar el minimo.

			$this->Reserva->update($reserva_id,array("estado_id"=>4));

			//Registrar la confirmacion de la reserva
			/*
			$mail = true;
			$template = 'pago_recibido';
			registrar_comentario_reserva($reserva->id,7,'cambio_estado','Reserva confirmada por acreditación de pago',$mail,$template,$movimiento_id);
			*/
		}

		//chequeo si la suma de los movimientos en la cta cte de BBV sobre esa reserva no alcanzan el monto total
		//este query ya no se usaria
		$movs = $this->Movimiento->getPago($reserva_id,"A",1)->row();

		//genero movimiento en sistema de caja
		//LOS REGISTROS LOS HAGO TODOS EN PESOS COMO HASTA AHORA
		if($concepto == 'PAGO EN EFECTIVO' || $concepto == 'PAGO EN EFECTIVO - BRASIL' || $concepto == 'NOTA DE CREDITO - EFECTIVO'){
			$data_caja['movimiento_id'] = $movimiento_id;
			$data_caja['fecha'] = $fecha;
			$data_caja['concepto'] = $concepto;

			//si el pago es en USD LO MULTIPLICO POR LA COTIZACION PARA PASARLO A PESOS
			if($moneda == 'USD'){
				$debe = $debe*$tipo_cambio;
				$haber = $haber*$tipo_cambio;
			}

			if($concepto == 'NOTA DE CREDITO - EFECTIVO'){
				$data_caja['egreso'] = $debe;
				$data_caja['ingreso'] = 0;
			}
			else{
				$data_caja['ingreso'] = $haber;
				$data_caja['egreso'] = 0;
			}

			//28-12-15 sólo para movimiento en EFECTIVO -> le agrego el 5% para viajes al exterior
			if($concepto == 'PAGO EN EFECTIVO' && $reserva->exterior == 1){
				$haber = number_format($haber*1.05,2,'.','');
				$data_caja['ingreso'] = $haber;
				$data_caja['egreso'] = 0;
			}
			else if($concepto == 'NOTA DE CREDITO - EFECTIVO' && $reserva->exterior == 1){
				$debe = number_format($debe*1.05,2,'.','');
				$data_caja['egreso'] = $debe;
				$data_caja['ingreso'] = 0;
			}

			//obtengo ultimo movimiento de caja de la sucursal correspondiente
			if($reserva->sucursal_id > 0)
				$this->Caja->filters = "bv_caja.sucursal_id = ".$reserva->sucursal_id;
			$mov_caja = $this->Caja->getAll(1,0,'fecha','desc')->row();

			if(isset($mov_caja->id))
				$data_caja['saldo'] = $mov_caja->saldo+$data_caja['ingreso']-$data_caja['egreso'];
			else
				$data_caja['saldo'] = $data_caja['ingreso']-$data_caja['egreso'];

			$data_caja['admin_id'] = $this->session->userdata('admin_id');
			$data_caja['sucursal_id'] = $reserva->sucursal_id;

			$this->Caja->insert($data_caja);
		}

		//25-09-15 por pedido de andrea se vuelve a registrar en caja paralela segun el concepto DESCUENTO
		//LOS REGISTROS LOS HAGO TODOS EN PESOS COMO HASTA AHORA
		if($concepto == 'DESCUENTO'){
			$data_caja_dto['movimiento_id'] = $movimiento_id;
			$data_caja_dto['fecha'] = $fecha;
			$data_caja_dto['concepto'] = $concepto;

			//si el DTO es en USD LO MULTIPLICO POR LA COTIZACION PARA PASARLO A PESOS
			if($moneda == 'USD'){
				$haber = $haber*$tipo_cambio;
			}

			$data_caja_dto['ingreso'] = $haber;
			$data_caja_dto['egreso'] = 0;

			//obtengo ultimo movimiento de caja
			if($reserva->sucursal_id > 0)
				$this->Caja_descuento->filters = "bv_caja_descuentos.sucursal_id = ".$reserva->sucursal_id;
			$mov_caja_dto = $this->Caja_descuento->getAll(1,0,'fecha','desc')->row();

			if(isset($mov_caja_dto->id))
				$data_caja_dto['saldo'] = $mov_caja_dto->saldo+$data_caja_dto['ingreso']-$data_caja_dto['egreso'];
			else
				$data_caja_dto['saldo'] = $data_caja_dto['ingreso']-$data_caja_dto['egreso'];

			$data_caja_dto['admin_id'] = $this->session->userdata('admin_id');
			$data_caja_dto['sucursal_id'] = $reserva->sucursal_id;

			$this->Caja_descuento->insert($data_caja_dto);
		}

		//Si corresponde facturar, armo el comprobante
		if ($conceptoObj->facturacion) {
			//el ultimo parametro en false poruqe primero informa a afip, entonces el detalle tiene que vijar como si fuera para facturacion
			//luego cuando se va a generar el archivo fisico pdf (dentro de generar() ) se obtienen los datos para la factura con ese ultimo parametro en true

			//NO INFORMO LA FACTURA AHORA, LO HARA EL CRON
			//$comprobante = $this->Factura->generar($factura_id, $tipo_comprobante, $sucursal->id, $reserva_id,true,false);
			$comprobante = FALSE;



			//si el comprobante fallo o vino con error
			if(!$comprobante || (isset($comprobante['error']) && $comprobante['error']) ){
				//$movimiento_id
				if($movimiento_id){
					$updata = array();
					$updata['factura_id'] = 0;
					$updata['comprobante'] = '';
					//$updata['talonario'] = '';
					//$this->Movimiento->update($movimiento_id,$updata);
				}
			}

			//04-01-18
			//tomo los IDs de movimientos de cuenta de psaajero y bv para ajustar saldo y haber si corresponde
			//si le movimiento tuvo percepcion, tengo que actualizar el valor del haber y parcial
			//sacandole el importe correspondiente a percepcion
			$movs_ids = array($movimiento_id,$movimiento_agencia_id);

			$datos = $this->Factura->obtenerDatos($factura_id, $tipo_comprobante, $sucursal->id, $reserva_id);
			if(isset($datos['percepcion_3825']) && $datos['percepcion_3825']){
				foreach($movs_ids as $m_id){
					//movimiento_id
					$mov2 = $this->Movimiento->get($m_id)->row();

					//pre($mov2);

					if(isset($mov2) && isset($mov2->id) && $mov2->id){
						if($moneda == 'USD'){
							//si el pago fue en usd
							if($tipo_comprobante == 'NC_X' || $tipo_comprobante == 'NC_B'){
								//si es Nota de Credito
								$debe_usd = $mov2->debe_usd - ($datos['percepcion_3825']/$tipo_cambio);
								$debe = ($mov2->debe_usd*$tipo_cambio) - $datos['percepcion_3825'];
								$parcial_usd = $mov2->parcial_usd + ($datos['percepcion_3825']/$tipo_cambio);
								$parcial = 0.00;
								$haber = 0.00;
								$haber_usd = 0.00;
							}
							else{
								//es Factura
								$haber_usd = $mov2->haber_usd - ($datos['percepcion_3825']/$tipo_cambio);
								$haber = ($mov2->haber_usd*$tipo_cambio) - $datos['percepcion_3825'];
								$parcial_usd = $mov2->parcial_usd + ($datos['percepcion_3825']/$tipo_cambio);
								$parcial = 0.00;
								$debe = 0.00;
								$debe_usd = 0.00;
							}
						}
						else{
							//si fue en pesos
							if($tipo_comprobante == 'NC_X' || $tipo_comprobante == 'NC_B'){
								//si es Nota de Credito
								$debe = $mov2->debe - $datos['percepcion_3825'];
								$debe_usd = ($mov2->debe - $datos['percepcion_3825'])/$tipo_cambio;
								$parcial = $mov2->parcial + $datos['percepcion_3825'];
								$parcial_usd = 0.00;
								$haber = 0.00;
								$haber_usd = 0.00;
							}
							else{
								//si es factura
								$haber = $mov2->haber - $datos['percepcion_3825'];
								$haber_usd = ($mov2->haber - $datos['percepcion_3825'])/$tipo_cambio;
								$parcial = $mov2->parcial + $datos['percepcion_3825'];
								$parcial_usd = 0.00;
								$debe = 0.00;
								$debe_usd = 0.00;
							}
						}


						$data_mov = array(
									"haber" => $haber,
									"haber_usd" => $haber_usd,
									"parcial" => $parcial,
									"debe" => $debe,
									"debe_usd" => $debe_usd,
									"parcial_usd" => $parcial_usd
							);
						$this->Movimiento->update($m_id,$data_mov);
					}
				}
			}

			/*
			//si hay intereses, se lo saco al valor de la operacion en la cuenta corriente
			if(isset($datos['intereses']) && $datos['intereses'] > 0){
				//se lo saco al total factura
				//$datos['total'] -= $datos['intereses'];
				foreach($movs_ids as $m_id){
					//movimiento_id
					$mov3 = $this->Movimiento->get($m_id)->row();

					//pre($mov3);

					if(isset($mov3) && isset($mov3->id) && $mov3->id){
						if($moneda == 'USD'){
							//si el pago fue en usd
							if($tipo_comprobante == 'NC_X' || $tipo_comprobante == 'NC_B'){
								//si es Nota de Credito
								$debe_usd = $mov3->debe_usd - ($datos['intereses']/$tipo_cambio);
								$debe = ($mov3->debe_usd*$tipo_cambio) - $datos['intereses'];
								$parcial_usd = $mov3->parcial_usd + ($datos['intereses']/$tipo_cambio);
								$parcial = 0.00;
								$haber = 0.00;
								$haber_usd = 0.00;
							}
							else{
								//es Factura
								$haber_usd = $mov3->haber_usd - ($datos['intereses']/$tipo_cambio);
								$haber = ($mov3->haber_usd*$tipo_cambio) - $datos['intereses'];
								$parcial_usd = $mov3->parcial_usd + ($datos['intereses']/$tipo_cambio);
								$parcial = 0.00;
								$debe = 0.00;
								$debe_usd = 0.00;
							}
						}
						else{
							//si fue en pesos
							if($tipo_comprobante == 'NC_X' || $tipo_comprobante == 'NC_B'){
								//si es Nota de Credito
								$debe = $mov3->debe - $datos['intereses'];
								$debe_usd = ($mov3->debe - $datos['intereses'])/$tipo_cambio;
								$parcial = $mov3->parcial + $datos['intereses'];
								$parcial_usd = 0.00;
								$haber = 0.00;
								$haber_usd = 0.00;
							}
							else{
								//si es factura
								$haber = $mov3->haber - $datos['intereses'];
								$haber_usd = ($mov3->haber - $datos['intereses'])/$tipo_cambio;
								$parcial = $mov3->parcial + $datos['intereses'];
								$parcial_usd = 0.00;
								$debe = 0.00;
								$debe_usd = 0.00;
							}
						}


						$data_mov = array(
									"haber" => $haber,
									"haber_usd" => $haber_usd,
									"parcial" => $parcial,
									"debe" => $debe,
									"debe_usd" => $debe_usd,
									"parcial_usd" => $parcial_usd
							);
						$this->Movimiento->update($m_id,$data_mov);
					}
				}
			}
			*/
		}
		else {
			$comprobante = FALSE;
		}

		if($tipo=="U"){
			//12-06-18 obtengo los precios totales para tomar el saldo pendiente
			//esto reemplaza al query anterior de $movs
			$saldo = $this->Reserva->getSaldoReserva($reserva->id);
			$saldo_pendiente = ($reserva->precio_usd) ? $saldo->saldo_usd : $saldo->saldo;

			//01-11-19 Maxi: envio mail de voucher solo si la reserva esta confirmada
			if($reserva->estado_id == 4){
				/*
				si el operador del viaje es Buenas Vibras y si ya pago todo el monto del viaje
				genero registro en historial para luego enviar el voucher adjunto
				*/
				//if( $paquete->operador_id == 1 && $movs->saldo == 0 ){
				//if( $paquete->operador_id == 1 && $saldo_pendiente == 0 ){
				//06-03-19 EL envío del voucher ahora estaá definido por paquete
				if( $paquete->voucher_automatico && $saldo_pendiente == 0 ){
					$mail = true;
					$template = 'voucher';
					registrar_comentario_reserva($reserva->id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template,$movimiento_id);
				}
				else{
					//if( $paquete->operador_id > 1 && $saldo_pendiente == 0){
					//el tipo de envio de vouches está definido en el paquete
					if( !$paquete->voucher_automatico && $saldo_pendiente == 0){
						//si es un envio manual, voy a enviar el mail de vouchers si hay algun voucher cargado.
						$this->load->model('Reserva_voucher_model', 'Reserva_voucher');
						$mis_vouchers = $this->Reserva_voucher->getWhere(array('reserva_id' => $reserva->id))->result();

						if(count($mis_vouchers)){
							$mail = true;
							$template = 'voucher';
							registrar_comentario_reserva($reserva->id,7,'envio_mail_voucher','Envio de email de vouchers al pasajero. CONCEPTO: '.$reserva->nombre." - ".$reserva->paquete_codigo,$mail,$template,$movimiento_id);
						}
					}
				}
			}

		}


		$ret = array();
		$ret['status'] = 'success';

		if(@$referer == 'reservas')
			$ret['redirect'] = base_url().'admin/reservas/edit/'.$reserva_id.'?tab=cta_cte&comprobante='.$comprobante;
		else
			$ret['redirect'] = base_url().'admin/usuarios/edit/'.$tipo_id.'?tab=cta_cte&comprobante='.$comprobante;

		//header('location: '.base_url().'admin/reservas/cta_cte/'.$tipo_id.'/'.$tipo.'/'.$usuario_id.'/'.$reserva_id.'?comprobante='.$comprobante);
		echo json_encode($ret);
	}

	/* este metodo asocia y genera la factura con el recibo previo
	Se recibe como parametro el ID del movimiento que posee el recibo */
	function facturarRecibo($movimiento_id=false){
		if(!$movimiento_id){
			redirect(base_url().'admin');
		}

		ini_set('max_execution_time',360);

		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Sucursal_model','Sucursal');
		$this->load->model('Concepto_model','Concepto');
		$this->load->model('Factura_model','Factura');
		$this->load->model('Caja_model','Caja');
		$this->load->model('Caja_descuento_model','Caja_descuento');

		$mov = $this->Movimiento->get($movimiento_id)->row();

		$reserva_id = $mov->reserva_id;
		$reserva = $this->Reserva->get($reserva_id)->row();
		$sucursal_id = $reserva->sucursal_id;
		$factura = $this->Factura->getFactura($mov->factura_id,$mov->talonario,$reserva->sucursal_id)->row();

		//tomo los datos del movimiento del recibo para generar la factura
		$haber = $mov->haber;
		$debe = 0;
		$informe_id = 0;
		$gastos_administrativos = $factura->gastos_adm;
		$tipo_cambio = $mov->tipo_cambio;
		$concepto = $mov->concepto;
		$tipo_id = $mov->usuario_id;
		$tipo = $mov->tipoUsuario;

		$sucursal = $this->Sucursal->get($sucursal_id)->row();

		$conceptoObj = $this->Concepto->getBy('nombre='.$concepto);

		// FACTURA-------------------
		$talonario = $sucursal->talonario;

		//solo para facturas y notas de credito en efectivo de argentina
		//el talonario puede ser yellow
		if( $talonario == 'X' && $reserva->exterior == 0 && ($concepto == 'PAGO EN EFECTIVO' || $concepto == 'NOTA DE CREDITO - EFECTIVO')){
			$talonario = 'X';
		}

		$paquete = $this->Paquete->get($reserva->paquete_id)->row();

		$valor_factura = 0;

		//el talonario del nuevo comprobante (factura)
		$tipo_comprobante = 'FA_'.$talonario;

		$valor_factura = $haber;

		$datos_factura['sucursal_id'] = $sucursal_id;
		$datos_factura['vendedor_id'] = $reserva->vendedor_id;
		$datos_factura['usuario_id'] = $tipo_id;
		$datos_factura['reserva_id'] = $reserva_id;
		$datos_factura['fecha'] = date('Y-m-d H:i:s');
		$datos_factura['valor'] = $valor_factura;
		$datos_factura['punto_venta'] = 2;
		$datos_factura['forma_pago'] = $concepto;
		$datos_factura['total'] = $valor_factura;
		$datos_factura['gastos_adm'] = ( isset($gastos_administrativos) && $gastos_administrativos > 0 ) ? $gastos_administrativos : 0.00;

		//Si corresponde facturacion, generar comprobante
		if ($conceptoObj->facturacion) {
			$datos_factura['tipo_factura'] = $talonario;
			$datos_factura['tipo'] = $tipo_comprobante;

			$datos_factura['concepto_id'] = $conceptoObj->id;

			//genero el registro de la nueva factura
			$factura_id = $this->Factura->insert($datos_factura);
		}
		else {
			$factura_id = 0;
		}
		//---------------------------------------

		$new_concepto = $concepto;

		//Registrar la generacion de la factura asociada al recibo
		$mail = false;
		$template = '';//pago_recibido
		registrar_comentario_reserva($reserva_id,7,'generacion_factura','Factura generada asociada al Recibo '.$mov->comprobante,$mail,$template,$mov->id);

		//Si corresponde facturar, armo el comprobante
		if ($conceptoObj->facturacion) {
			//el primer parametro es el ID del RECIBO
			//el segundo parametro es el talonario del RECIBO
			//el tercer parametro es el tipo de comprobante del RECIBO,
			//el cuarto parametro es el ID de la nueva FACTURA
			//y el 5to parametro es el nuevo tpo FACTURA
			$comprobante = $this->Factura->facturarRecibo($mov->factura_id, $mov->talonario, $mov->comprobante, $factura_id, $tipo_comprobante, $sucursal->id, $reserva_id);
		}
		else {
			$comprobante = FALSE;
		}

		$ret = array();
		$ret['status'] = 'success';

		$ret['redirect'] = base_url().'admin/reservas/edit/'.$reserva_id.'?tab=cta_cte&comprobante='.$comprobante;

		header('location: '.$ret['redirect']);
		//echo json_encode($ret);
	}

	/* Carga tabla con cuenta corriente del operador*/
	function cargar_cuenta_corriente_operador($usuario_id,$tipo,$paquete_id=false){
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Paquete_model','Paquete');
		$this->load->model('Concepto_model','Concepto');

		switch($tipo){
			case 'A':
				$this->Movimiento->filters = "bv_movimientos.tipoUsuario = 'A' and bv_movimientos.usuario_id = ".$usuario_id;
			break;
		}

		$moneda_activa = isset($_GET['moneda']) ? $_GET['moneda'] : 'ARS';

		$this->Paquete->filters = "activo = 1 and operador_id = ".$usuario_id;
		$res = $this->Paquete->getAll(9999,0,'nombre','asc')->result();
		$this->data['paquetes'] = $res;

		if($paquete_id){
			$res = $this->Paquete->get($paquete_id)->row();
			$this->data['paquete'] = $res;

			//si el viaje es en USD, entonces lo fuerzo a cta cte en USD
			if($res->precio_usd){
				$moneda_activa = 'USD';

				//solo los movimientos de los viajes en USD
				$this->Movimiento->filters .= " and (p.id = ".$paquete_id." or bv_movimientos.paquete_id = ".$paquete_id.") and (p.precio_usd = 1 or p1.precio_usd = 1) and bv_movimientos.pago_usd = 1";
			}
			else{
				//sino, el ARS
				$moneda_activa = 'ARS';

				//solo los movimientos de los viajes en ARS
				$this->Movimiento->filters .= " and (p.id = ".$paquete_id." or bv_movimientos.paquete_id = ".$paquete_id.") and (p.precio_usd = 0 or p1.precio_usd = 0) and bv_movimientos.pago_usd = 0";
			}

		}
		else{
			$paq = new stdClass();
			$this->data['paquete'] = $paq;

			if($moneda_activa == 'USD')
				$this->Movimiento->filters .= " and bv_movimientos.pago_usd = 1";
			else
				$this->Movimiento->filters .= " and bv_movimientos.pago_usd = 0";
		}

		$movimientos = $this->Movimiento->getAll(9999,0,'bv_movimientos.fecha','asc');

		//echo $this->db->last_query();

		$this->data['movimientos'] = $movimientos;

		//puede que ya sepa sobre que paquete voy a generar el movimiento
		$this->data['paquete_id'] = $paquete_id;

		//para saber el tipo y ID de usuario
		$this->data['usuario_id'] = $usuario_id;
		$this->data['tipo'] = $tipo;
		$this->data['moneda_activa'] = $moneda_activa;

		$this->Concepto->filters = "operador = 1 and sistema_caja = 0";
		$this->data['conceptos'] = $this->Concepto->getAll(999,0,'nombre','asc')->result();

		$this->data['cuenta_corriente'] = $this->load->view('admin/cuenta_corriente_operador',$this->data,true);
	}

	/* Carga tabla con cuenta corriente del vendedor*/
	function cargar_cuenta_corriente_vendedor($usuario_id,$tipo,$paquete_id=false){
		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Paquete_model','Paquete');
		$this->load->model('Concepto_model','Concepto');

		switch($tipo){
			case 'V':
				$this->Movimiento->filters = "bv_movimientos.tipoUsuario = 'V' and bv_movimientos.usuario_id = ".$usuario_id;
			break;
		}

		$moneda_activa = isset($_GET['moneda']) ? $_GET['moneda'] : 'ARS';

		$this->Paquete->filters = "activo = 1";
		$res = $this->Paquete->getAll(9999,0,'nombre','asc')->result();
		$this->data['paquetes'] = $res;

		if($paquete_id){
			$res = $this->Paquete->get($paquete_id)->row();
			$this->data['paquete'] = $res;

			//si el viaje es en USD, entonces lo fuerzo a cta cte en USD
			if($res->precio_usd){
				$moneda_activa = 'USD';

				//solo los movimientos de los viajes en USD
				$this->Movimiento->filters .= " and (p.id = ".$paquete_id." or bv_movimientos.paquete_id = ".$paquete_id.") and (p.precio_usd = 1 or p1.precio_usd = 1) and bv_movimientos.pago_usd = 1";
			}
			else{
				//sino, el ARS
				$moneda_activa = 'ARS';

				//solo los movimientos de los viajes en ARS
				$this->Movimiento->filters .= " and (p.id = ".$paquete_id." or bv_movimientos.paquete_id = ".$paquete_id.") and (p.precio_usd = 0 or p1.precio_usd = 0) and bv_movimientos.pago_usd = 0";
			}

		}
		else{
			$paq = new stdClass();
			$this->data['paquete'] = $paq;

			if($moneda_activa == 'USD')
				$this->Movimiento->filters .= " and bv_movimientos.pago_usd = 1";
			else
				$this->Movimiento->filters .= " and bv_movimientos.pago_usd = 0";
		}

		$movimientos = $this->Movimiento->getAll(9999,0,'bv_movimientos.fecha','asc');

		//echo $this->db->last_query();

		$this->data['movimientos'] = $movimientos;

		//puede que ya sepa sobre que paquete voy a generar el movimiento
		$this->data['paquete_id'] = $paquete_id;

		//para saber el tipo y ID de usuario
		$this->data['usuario_id'] = $usuario_id;
		$this->data['tipo'] = $tipo;
		$this->data['moneda_activa'] = $moneda_activa;

		$this->Concepto->filters = "operador = 1 and sistema_caja = 0";
		$this->data['conceptos'] = $this->Concepto->getAll(999,0,'nombre','asc')->result();

		$this->data['cuenta_corriente'] = $this->load->view('admin/cuenta_corriente_vendedor',$this->data,true);
	}

	/* Grabar movimiento en cuenta corriente del operador */
	function grabarMovimientoOperador(){
		extract($_POST);

		ini_set('max_execution_time',360);

		if(!isset($informe_id))
			$informe_id = 0;

		//le cambio el formato al que admite la DB
		if(!isset($debe))
			$debe = 0;
		else
			$debe = str_replace(array('.',','),array('','.'),$debe);

		if(!isset($haber))
			$haber = 0;
		else
			$haber = str_replace(array('.',','),array('','.'),$haber);

		if(!isset($gastos_administrativos))
			$gastos_administrativos = 0;
		else
			$gastos_administrativos = str_replace(array('.',','),array('','.'),$gastos_administrativos);

		if(!isset($tipo_cambio))
			$tipo_cambio = 0;
		else
			$tipo_cambio = str_replace(array('.',','),array('','.'),$tipo_cambio);

		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Paquete_model','Paquete');
		$this->load->model('Sucursal_model','Sucursal');
		$this->load->model('Concepto_model','Concepto');
		$this->load->model('Factura_model','Factura');
		$this->load->model('Caja_model','Caja');
		$this->load->model('Caja_descuento_model','Caja_descuento');

		//$reserva = $this->Reserva->get($reserva_id)->row();
		$reserva_id = false;

		//la registracion de movimiento de pago al proveedor es por PAQUETE, no por reserva
		$paquete = false;
		if($paquete_id){
			$paquete = $this->Paquete->get($paquete_id)->row();
		}

		$conceptoObj = $this->Concepto->getBy('nombre='.$concepto);

		$valor_factura = 0;
		if(isset($debe) && $debe > 0){
			$valor_factura = $debe;
		}
		else if(isset($haber) && $haber > 0){
			//me fijo si tiene el COSTO CARGADO o no el paquete para generar un RECIBO o FACTURA
			$valor_factura = $haber;
		}


		//obtengo parcial segun ultimo movimiento en base a $tipo_id,$tipo,
		//genero movimiento en cta cte de ese usuario en concepto de Pago de parte o total de la deuda
		$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($tipo_id,$tipo)->row();

		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		if(isset($mov->parcial))
			$nuevo_parcial = $mov->parcial+$debe-$haber;
		else
			$nuevo_parcial = .00+$debe-$haber;

		$fecha = date("Y-m-d H:i:s");

		$new_concepto = $concepto;

		$factura_id = false;
		$tipo_comprobante = '';
		$factura_asociada_id = '';
		$talonario_asociado = '';

		$movimiento_id = registrar_movimiento_cta_cte($tipo_id,$tipo,$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id,false,$paquete_id);

		if($tipo == 'A')
			registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del operador. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);

		//idem genero movimiento en cta cte de BBV en concepto de Pago de parte o total de la deuda por parte del operador
		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A")->row();
		$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) + $debe - $haber;

		registrar_movimiento_cta_cte(1,'A',$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial2,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id,false,$paquete_id);
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);


		//16/01/2020 si hay un comentario asociado al pago, lo registro en el historial
		$comentarios_mov = isset($_POST['comentarios_mov']) && $_POST['comentarios_mov'] ? $_POST['comentarios_mov'] : '';
		if($comentarios_mov){
			registrar_comentario_reserva($reserva_id,7,'comentario','Nuevo comentario interno asociado al movimiento.<br>'.$comentarios_mov,false,'');

		}

		//genero movimiento en sistema de caja
		//LOS REGISTROS LOS HAGO TODOS EN PESOS COMO HASTA AHORA
	//esta registracion en CAJA se va a usar para este caso de operadores?
		if(FALSE && $concepto == 'PAGO EN EFECTIVO'){
			$data_caja['movimiento_id'] = $movimiento_id;
			$data_caja['fecha'] = $fecha;
			$data_caja['concepto'] = $concepto;

			//si el pago es en USD LO MULTIPLICO POR LA COTIZACION PARA PASARLO A PESOS
			if($moneda == 'USD'){
				$debe = $debe*$tipo_cambio;
				$haber = $haber*$tipo_cambio;
			}

			if($concepto == 'NOTA DE CREDITO - EFECTIVO'){
				$data_caja['egreso'] = $debe;
				$data_caja['ingreso'] = 0;
			}
			else{
				$data_caja['ingreso'] = $haber;
				$data_caja['egreso'] = 0;
			}

			//28-12-15 sólo para movimiento en EFECTIVO -> le agrego el 5% para viajes al exterior
			if($concepto == 'PAGO EN EFECTIVO' && $reserva->exterior == 1){
				$haber = number_format($haber*1.05,2,'.','');
				$data_caja['ingreso'] = $haber;
				$data_caja['egreso'] = 0;
			}
			else if($concepto == 'NOTA DE CREDITO - EFECTIVO' && $reserva->exterior == 1){
				$debe = number_format($debe*1.05,2,'.','');
				$data_caja['egreso'] = $debe;
				$data_caja['ingreso'] = 0;
			}

			//obtengo ultimo movimiento de caja de la sucursal correspondiente
			if($reserva->sucursal_id > 0)
				$this->Caja->filters = "sucursal_id = ".$reserva->sucursal_id;
			$mov_caja = $this->Caja->getAll(1,0,'fecha','desc')->row();

			if(isset($mov_caja->id))
				$data_caja['saldo'] = $mov_caja->saldo+$data_caja['ingreso']-$data_caja['egreso'];
			else
				$data_caja['saldo'] = $data_caja['ingreso']-$data_caja['egreso'];

			$data_caja['admin_id'] = $this->session->userdata('admin_id');
			$data_caja['sucursal_id'] = $reserva->sucursal_id;

			$this->Caja->insert($data_caja);
		}

		$comprobante = FALSE;

		$ret = array();
		$ret['status'] = 'success';
		if($paquete_id){
			$ret['redirect'] = base_url().'admin/operadores/edit/'.$tipo_id.'?tab=cta_cte&moneda='.$moneda.'&paquete_id='.$paquete_id;
		}
		else{
			$ret['redirect'] = base_url().'admin/operadores/edit/'.$tipo_id.'?tab=cta_cte&moneda='.$moneda;
		}

		echo json_encode($ret);
	}

	/* Grabar movimiento en cuenta corriente del vendedor */
	function grabarMovimientoVendedor(){
		extract($_POST);

		ini_set('max_execution_time',360);

		if(!isset($informe_id))
			$informe_id = 0;

		//le cambio el formato al que admite la DB
		if(!isset($debe))
			$debe = 0;
		else
			$debe = str_replace(array('.',','),array('','.'),$debe);

		if(!isset($haber))
			$haber = 0;
		else
			$haber = str_replace(array('.',','),array('','.'),$haber);

		if(!isset($gastos_administrativos))
			$gastos_administrativos = 0;
		else
			$gastos_administrativos = str_replace(array('.',','),array('','.'),$gastos_administrativos);

		if(!isset($tipo_cambio))
			$tipo_cambio = 0;
		else
			$tipo_cambio = str_replace(array('.',','),array('','.'),$tipo_cambio);

		$this->load->model('Movimiento_model','Movimiento');
		$this->load->model('Reserva_model','Reserva');
		$this->load->model('Paquete_model','Paquete');
		$this->load->model('Sucursal_model','Sucursal');
		$this->load->model('Concepto_model','Concepto');
		$this->load->model('Factura_model','Factura');
		$this->load->model('Caja_model','Caja');
		$this->load->model('Caja_descuento_model','Caja_descuento');

		//$reserva = $this->Reserva->get($reserva_id)->row();
		$reserva_id = false;

		//la registracion de movimiento de pago al proveedor es por PAQUETE, no por reserva
		$paquete = false;
		if($paquete_id){
			$paquete = $this->Paquete->get($paquete_id)->row();
		}

		$conceptoObj = $this->Concepto->getBy('nombre='.$concepto);

		$valor_factura = 0;
		if(isset($debe) && $debe > 0){
			$valor_factura = $debe;
		}
		else if(isset($haber) && $haber > 0){
			//me fijo si tiene el COSTO CARGADO o no el paquete para generar un RECIBO o FACTURA
			$valor_factura = $haber;
		}


		//obtengo parcial segun ultimo movimiento en base a $tipo_id,$tipo,
		//genero movimiento en cta cte de ese usuario en concepto de Pago de parte o total de la deuda
		$mov = $this->Movimiento->getLastMovimientoByTipoUsuario($tipo_id,$tipo)->row();

		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		if(isset($mov->parcial))
			$nuevo_parcial = $mov->parcial+$debe-$haber;
		else
			$nuevo_parcial = .00+$debe-$haber;

		$fecha = date("Y-m-d H:i:s");

		$new_concepto = $concepto;

		$factura_id = false;
		$tipo_comprobante = '';
		$factura_asociada_id = '';
		$talonario_asociado = '';

		$movimiento_id = registrar_movimiento_cta_cte($tipo_id,$tipo,$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id,false,$paquete_id);

		if($tipo == 'A')
			registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte del vendedor. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);

		//idem genero movimiento en cta cte de BBV en concepto de Pago de parte o total de la deuda por parte del operador
		//03-11-17 este dato de PARCIAL se vuelve a calcular en el metodo registrar_movimiento_cta_cte segun el tipo de moneda de pago
		$mov2 = $this->Movimiento->getLastMovimientoByTipoUsuario(1,"A")->row();
		$nuevo_parcial2 = ( isset($mov2->parcial) ? $mov2->parcial : 0.00 ) + $debe - $haber;

		registrar_movimiento_cta_cte(1,'A',$reserva_id,$fecha,$new_concepto,$debe,$haber,$nuevo_parcial2,$comentarios,'',$factura_id,$tipo_comprobante,$factura_asociada_id,$talonario_asociado,$moneda,$tipo_cambio,$informe_id,false,$paquete_id);
		registrar_comentario_reserva($reserva_id,7,'movimiento_cta_cte','Nuevo movimiento registrado en Cta Cte de BUENAS VIBRAS. CONCEPTO: '.$new_concepto.' | DEBE '.$debe.' | HABER '.$haber);


		//16/01/2020 si hay un comentario asociado al pago, lo registro en el historial
		$comentarios_mov = isset($_POST['comentarios_mov']) && $_POST['comentarios_mov'] ? $_POST['comentarios_mov'] : '';
		if($comentarios_mov){
			registrar_comentario_reserva($reserva_id,7,'comentario','Nuevo comentario interno asociado al movimiento.<br>'.$comentarios_mov,false,'');

		}

		$comprobante = FALSE;

		$ret = array();
		$ret['status'] = 'success';
		if($paquete_id){
			$ret['redirect'] = base_url().'admin/vendedores/edit/'.$tipo_id.'?tab=cta_cte&moneda='.$moneda.'&paquete_id='.$paquete_id;
		}
		else{
			$ret['redirect'] = base_url().'admin/vendedores/edit/'.$tipo_id.'?tab=cta_cte&moneda='.$moneda;
		}

		echo json_encode($ret);
	}

	function generate_dynamic_routes() {
		$routes = '<?php'.PHP_EOL;

		//ruteo de categorias regionales
		$contenidos = $this->db->query("select slug from bv_categorias where visible = 1")->result();
		foreach ($contenidos as $contenido) {
			if ($contenido->slug) {
				$routes .= '$route[\''.$contenido->slug.'\'] = \'categorias/regionales/'.$contenido->slug.'\';'.PHP_EOL;
			}
		}

		//ruteo de categorias estacionales
		$contenidos = $this->db->query("select slug from bv_estacionales")->result();
		foreach ($contenidos as $contenido) {
			if ($contenido->slug) {
				$routes .= '$route[\''.$contenido->slug.'\'] = \'categorias/estacionales/'.$contenido->slug.'\';'.PHP_EOL;
			}
		}

		//ruteo de destinos
		$contenidos = $this->db->query("select d.slug, c.slug as categoria from bv_destinos d join bv_categorias c on c.id = d.categoria_id where d.publicado = 1")->result();
		foreach ($contenidos as $contenido) {
			if ($contenido->slug) {
				$routes .= '$route[\''.$contenido->categoria.'/'.$contenido->slug.'\'] = \'destinos/ver/'.$contenido->slug.'\';'.PHP_EOL;
			}
		}

		//ruteo de paquetes
		$contenidos = $this->db->query("select p.slug, d.slug as destino, c.slug as categoria from bv_paquetes p join bv_destinos d on d.id = p.destino_id join bv_categorias c on c.id = d.categoria_id")->result();
		foreach ($contenidos as $contenido) {
			if ($contenido->slug) {
				$routes .= '$route[\''.$contenido->slug.'\'] = \'paquetes/ver/'.$contenido->slug.'\';'.PHP_EOL;
				//esta es la nueva estrucutra de url de paquetes, con la region y destino por delante
				$routes .= '$route[\''.$contenido->categoria.'/'.$contenido->destino.'/'.$contenido->slug.'\'] = \'paquetes/ver/'.$contenido->slug.'\';'.PHP_EOL;

				//nueva url paquete 2019: url de destino + paquete (sin la categoria regional)
				$routes .= '$route[\''.$contenido->destino.'/'.$contenido->slug.'\'] = \'paquetes/ver/'.$contenido->slug.'\';'.PHP_EOL;
			}
		}

		$routes .= '?>'.PHP_EOL;

		$this->load->helper('file');
		if (file_exists(APPPATH.'config/routes_dynamic.php')) {
			unlink(APPPATH.'config/routes_dynamic.php');
		}
		write_file(APPPATH.'config/routes_dynamic.php', $routes);
	}

}

// ------------------------------------------------------------------------
/* End of file Admin_Controller.php */
/* Location: ./application/core/Admin_Controller.php */
?>
